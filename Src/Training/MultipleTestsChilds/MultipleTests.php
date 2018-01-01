<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/07
 * Time: 2:15
 */

namespace CAPTCHA_Reader\Training\MultipleTests;

use CAPTCHA_Reader\IndexController;
use CAPTCHA_Reader\Training\CommonTrait;
use CAPTCHA_Reader\Training\MultipleTestsController;

class MultipleTests extends MultipleTestsController
{
    use CommonTrait;

    protected $logPath;
    protected $testSamplesDir;
    protected $whichSampleToUse;
    protected $indexController;

    /**
     * 获取测试结果日志地址，
     * 测试完需要返回结果的准确率
     * 同时向日志地址记录结果的准确率和当前字典样本数，是否需要记录准确的日志，
     * 如果有传入测试结果日志地址，则将简略计入指定地址，如果需要记录完整的日志，则将地址后面加'.detail'，存入详细的字典，做好地址日志是否存在的判断
     * 以及增加测试用例的选择，可以选择某一套测试用例，也可以指定某一套测试用例，以及随机某一套测试用例
     *
     *
     * 部分共用的方法写到父类中
     * 给测试用例分组
     * 测试分模式，是只测试一组测试用例还是测试全部的测试用例
     */
    public function __construct()
    {
        parent::__construct();
        $this->logPath          = $this->getLogPath();
        $this->testSamplesDir   = $this->getTestSamplesDir();
        $this->whichSampleToUse = $this->getTestSamplesWhichSampleToUse();
        $this->indexController  = new IndexController();
    }

    //'simple','detail'
    public function startTest( $logStyle = 'simple' )
    {
        $sampleList   = $this->getTestSampleList( $this->whichSampleToUse );
        $sampleNumber = count( $sampleList );
        $time         = time();

        $resultDistributed = [
            'count'     => $sampleNumber ,
            'true'      => 0 ,
            'false'     => 0 ,
            'charTrue'  => 0 ,
            'charFalse' => 0 ,
        ];

        $testLog                      = [];
        $testLog['resultDistributed'] = $resultDistributed;

        foreach($sampleList as $key => $sampleFileName)
        {
            //样本答案数组
            $sampleArr = [];
            for($i = 0; $i < $this->getCaptchaStringNum(); ++$i)
            {
                array_push( $sampleArr , $sampleFileName[$i] );
            }
            $path = $this->testSamplesDir . $this->whichSampleToUse . '\\' . $sampleFileName;
            //识别的结果数组
            $result = $this->indexController->getResult( $path , 'detailResult' );
            //进行判断，得到判断结果数组
            $judgmentResult = $this->judgment( $sampleArr , $result );
            if ($judgmentResult['result'])
            {
                ++$resultDistributed['true'];
            }
            else
            {
                ++$resultDistributed['false'];
            }

            $resultDistributed['charTrue']  += $judgmentResult['charResult']['true'];
            $resultDistributed['charFalse'] += $judgmentResult['charResult']['false'];

            $testLog[$key] = $judgmentResult;
        }
        dump( $resultDistributed );

        //记录日志
        //这里记录的是测试日志
        $testLog['resultDistributed'] = $resultDistributed;
        file_put_contents( $this->logPath . 'testLog_' . $time.'.log' , json_encode( $testLog ) );

        return [
            'judgmentResultPercent'     => ($resultDistributed['true']/$resultDistributed['count']) *100,
            'charJudgmentResultPercent' => ($resultDistributed['charTrue']/($resultDistributed['count']*$this->getCaptchaStringNum()))*100
        ];

    }

    /**
     * @param $sampleArr
     * @param $result
     * @return array
     */
    public function judgment( $sampleArr , $result )
    {
        $detailArr = [];

        $charResult = [
            'true'  => 0 ,
            'false' => 0 ,
        ];

        $flag = 0;
        foreach($sampleArr as $key => $sample)
        {
            if ($result[$key]['char'] != $sampleArr[$key])
            {
                ++$charResult['false'];
                ++$flag;
            }
            else
            {
                ++$charResult['true'];
            }
            $detailArr[$key]['sample']         = $sample;
            $detailArr[$key]['identifyResult'] = $result[$key];
        }

        if (!$flag)
        {
            $result = true;
            return compact( 'result' , 'detailArr' , 'charResult' );
        }
        else
        {
            $result = false;
            return compact( 'result' , 'detailArr' , 'charResult' );
        }
    }
}