<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:44
 */

namespace CAPTCHAReader\Training\AddSamples\AddSamplesAuto;

use CAPTCHAReader\src\App\IndexController;
use CAPTCHAReader\src\Log\Log;
use CAPTCHAReader\Training\MultiplesTests\NormalMultiplesTests\NormalMultipleTests;
use CAPTCHAReader\training\Traits\CommonTrait;

class AddSamplesAuto
{
    use CommonTrait;

    protected $trainingConf;

    protected $indexController;
    protected $multipleTests;

    protected $trainingId;

    /**
     * new
     *
     * 支持多组字典队列训练，通过改变IndexController 初始化时候的conf来实现
     * 用各个组件的名字合起来命名生成的字典，
     * 记录每次批量测试的结果，在结束的时候输出信息
     */

    /**
     * 自动训练类需要多次调用识别方法和添加字典方法以及批量测试方法
     *
     *
     * 首先遍历训练样本库，获取全部样本,打乱样本，根据顺序获取一张已经标记好的图，
     * 将图传给识别方法，获取返回的结果，和确定程度，如果确定程度低于97%，则将对应的字符串和标记加入字典，
     * 在下一次的训练开始前，会调用识别类的获取字典数量方法，数量为整百的时候，调用测试用例批量测试方法，获取现在的识别率，
     * 测试用例批量测试方法将，字典数量和识别率记录到文本中
     *
     * 自动批量学习应该推荐cli的方式运行，学习过程中不断输出进度，以及批量测试输出的成功率
     * 在成功率达到多少之后就停止学习，例如达到90%
     */

    /**
     * AddSamplesAuto constructor.
     */
    public function __construct()
    {
        $this->trainingConf = $this->getConfig('training');
        $this->indexController = new IndexController();
        $this->trainingId = $this->getRandomHexStr(32);
        $this->multipleTests = new NormalMultipleTests();
    }

    /**
     *
     */
    public function run()
    {
        //循环每类
        foreach ($this->trainingConf['studyGroup'] as $groupName => $componentGroups) {
            //获取 验证码列表
            $sampleList = $this->getStudySampleList($groupName);

            //循环 每组 学习
            foreach ($componentGroups as $key_ => $componentGroup) {
                //修改 indexController 的 conf
                $appConf = $this->indexController->getConf();
                $useGroup = $this->getRandomHexStr(32);
                $appConf['useGroup'] = $useGroup;
                $appConf['componentGroup'][$useGroup] =
                    array_merge(
                        ['components' => $componentGroup],
                        ['dictionary' => $this->generateDictionaryName($componentGroup)]
                    );
                $appConf['noteDetailJudgeProcess'] = true;
                $this->indexController->setConf($appConf);

                foreach ($sampleList as $samplePath) {
//                    dump($samplePath);
                    //识别sample 得到resultContainer
                    $resultContainer = $this->indexController->entrance($samplePath, 'local', true);

                    preg_match('/\w+(?=\.?\w+$)/', $samplePath, $matches);
                    $correctAnswer = $matches[0];
                    $answer = $resultContainer->getResultStr();
                    dump($correctAnswer, $answer);
                    if ($answer == '*****') {
                        continue;
                    }
                    if ($correctAnswer == $answer) {
                        continue;
                    }

                    // 取出一维样本字符串
                    $oneDCharStr = $resultContainer->getOneDCharStrArr();

                    //将错误识别错误的字母添加到字典
                    for ($i = 0; $i < strlen($correctAnswer); ++$i) {

                        if ($correctAnswer[$i] != $answer[$i]) {
                            dump($correctAnswer[$i] . ' --------- ' . $answer[$i] .' --------- ' . 'error');
                            dump($this->getDictionarySampleCount($this->indexController));
                            $this->addSampleToDictionary($correctAnswer[$i], $oneDCharStr[$i], $this->indexController);
                            if (!($this->getDictionarySampleCount($this->indexController) % 250)) {
                                // 如果批量测试 正确率大于既定值，则结束训练
                                //TODO 结束的时候需要全部测试集测试

                                // 调用批量测试
                                $testResult = $this->multipleTests->run($groupName, $this->indexController, $this->trainingId, 0);
                                Log::writeAddSamplesAutoLog($groupName, $testResult, $this->getDictionarySampleCount($this->indexController), $this->trainingId, $key_);

                                if ($testResult['correctRate'] > $this->trainingConf['testSuccessRateLine']) {
                                    $this->echoOverallAccuracyExceedTheLimit();
                                    $endFlag = true;
                                    break;
                                }
                            }
                        }
                    }

                    if (($endFlag ?? false) == true) {
                        break;
                    }


                    //在比对结果的过程中，如果样本数到达某个阈值，则开始批量测试，如果到达退出阈值则输出结果，结束学习过程，
                    //TODO 结束的时候需要全部测试集测试
                    if ($this->getDictionarySampleCount($this->indexController) > $this->trainingConf['dictionarySampleLimit']) {
                        $this->echoSampleExceedTheLimit();
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param $componentGroup
     * @return string
     */
    public function generateDictionaryName($componentGroup)
    {
        $name = '';
        foreach ($componentGroup as $component) {
            $n = preg_match('/(?<=\\\)[\w\-]+$/', $component, $matches);
            $name .= $matches[0] . '-';
        }
        return substr($name, 0, strlen($name) - 1) . '.json';
    }

    public function echoSampleExceedTheLimit()
    {
        echo "\n\n\n\n\n\n\n";
        echo "*****************************************************************";
        echo "*****************************************************************";
        echo "\ntraining success \n reason: dict sample number more than setting value ///\n";
    }

    public function echoOverallAccuracyExceedTheLimit()
    {
        echo "\n\n\n\n\n\n\n";
        echo "*****************************************************************";
        echo "*****************************************************************";
        echo "\ntraining success \n reason: The overall accuracy rate reached the standard ///\n";
    }


}
