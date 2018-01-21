<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:44
 */

namespace CAPTCHAReader\Training\AddSamples\AddSamplesAuto;

use CAPTCHAReader\src\App\IndexController;
use CAPTCHAReader\training\Traits\CommonTrait;

class AddSamplesAuto
{
    use CommonTrait;

    protected $trainingConf;

    protected $indexController;
    protected $multipleTests;
    protected $sampleList;

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

    public function __construct()
    {
        $this->trainingConf = $this->getConfig('training');

        $this->indexController = new IndexController();
    }

    public function run()
    {
        foreach ($this->trainingConf['studyGroup'] as $groupName => $componentGroups) {
            $sampleList = $this->getStudySampleList($groupName);

            foreach ($componentGroups as $componentGroup) {
                //修改 indexController 的 conf
                $appConf = $this->indexController->getConf();
                $useGroup = $this->getRandomHexStr(16);
                $appConf['useGroup'] = $useGroup;
                $appComponentGroups = $appConf['componentGroup'];
                    array_push($appComponentGroups, [$useGroup => $componentGroup]);
                $appConf['componentGroup'] = $appComponentGroups;
                $this->indexController->setConf($appConf);

                foreach ($sampleList as $samplePath) {
                    //识别sample


                    //得到resultContainer 取出一维样本字符串

                    //如果结果不对，将样本加入字典

                    //在比对结果的过程中，如果样本数到达某个阈值，则开始批量测试，如果到达退出阈值则输出结果，结束学习过程

                }



            }


        }

    }


}
