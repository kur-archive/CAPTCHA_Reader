<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/03
 * Time: 21:30
 */

namespace CAPTCHA_Reader\Training\AddSamples;

use CAPTCHA_Reader\IndexController;
use CAPTCHA_Reader\Training\MultipleTests\MultipleTests;

class AutomaticStudy extends AddSamplesController
{

    protected $indexController;
    protected $multipleTests;
    protected $sampleList;

    /**
     * 自动训练类需要多次调用识别方法和添加字典方法以及批量测试方法
     *
     *
     * 首先遍历训练样本库，获取全部样本，根据顺序获取一张已经标记好的图，
     * 将图传给识别方法，获取返回的结果，和确定程度，如果确定程度低于97%，则将对应的字符串和标记加入字典，
     * 在下一次的训练开始前，会调用识别类的获取字典数量方法，数量为整百的时候，调用测试用例批量测试方法，获取现在的识别率，
     * 测试用例批量测试方法将，字典数量和识别率记录到文本中
     *
     * 自动批量学习应该推荐cli的方式运行，学习过程中不断输出进度，以及批量测试输出的成功率
     * 在成功率达到多少之后就停止学习，例如达到90%
     */

    public function __construct()
    {
        parent::__construct();
        $this->indexController = new IndexController();
        $this->multipleTests   = new MultipleTests();
        $this->sampleList      = $this->getStudySampleList( $this->getStudySampleCollectionName() );
    }

    public function run()
    {
        foreach($this->sampleList as $sampleFilename)
        {
            //获取学习样本图片的标记结果
            $sampleArr = [];
            for($i = 0; $i < $this->getCaptchaStringNum(); ++$i)
            {
                array_push( $sampleArr , $sampleFilename[$i] );
            }
            //获取学习样本图片的识别结果
            $result = $this->indexController->getResult( $this->getStudySamplesDir() . $this->getStudySampleCollectionName() . '\\' . $sampleFilename );

            //对样本和标记进行对比
            $this->study( $sampleArr , $result );

            $trainingDictionary = json_decode( file_get_contents( $this->getTrainingDictionary() ) );
            //计算当前字典内的样本数量
            $dictSampleNumber = count( $trainingDictionary );
            dump( 'dictSampleNumber : ' . $dictSampleNumber );
            //当样本数量到达某个值的时候，进行批量测试，将结果记入日志
//            if (in_array( $dictSampleNumber%500 , [0 , 1 , 2 , 3] ) && $dictSampleNumber > 800)
            if (in_array( $dictSampleNumber%200 , [0 , 1 , 2 , 3] ))
            {
                $testResult = $this->multipleTests->startTest();
                array_push( $testResult , ['dictSampleNumber' => $dictSampleNumber] );
                dump( 'testResult :' , $testResult );
                //记入训练日志
                file_put_contents( $this->getLogPath() . 'AutomaticStudy_' . time() . '.log' , json_encode( $testResult ) );

                //如果测试返回的准确率大于设定值，可退出学习过程
                if ($testResult['judgmentResultPercent'] > $this->getTestSuccessRateLine())
                {
                    echo "training success \n reason: success percent more than setting value ///\n";
                    break;
                }
            }

            //如果字典样本数量到达既定值，则可退出训练
            if ($dictSampleNumber > $this->getDictionarySampleLimit())
            {
                echo "training success \n reason: dict sample number more than setting value ///\n";
                break;
            }
        }
    }

    /**
     * @param $sampleArr
     * @param $result
     */
    public function study( $sampleArr , $result )
    {
        foreach($sampleArr as $key => $sampleChar)
        {
            if ($sampleChar != $result[$key]['char'] || $result[$key]['percent'] < 95)
            {
                dump( $sampleChar , $result[$key]['char'] , $result[$key]['percent'] );
                //进行纠正，对错误和认可率小于设定值的结果进行纠正并学习
                //将纠正结果加入字典
                $this->addSampleToDictionary( $sampleChar , $result[$key]['sampleSource'] );
            }
        }
    }

}