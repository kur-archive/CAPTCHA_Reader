<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/04
 * Time: 3:53
 */

namespace CAPTCHA_Reader\Tools;


use CAPTCHA_Reader\IndexController;

class MultipleTestsRepository
{
    protected $samplesDir;
    protected $samplesArr;
    protected $indexController;
    protected $statistics;
    protected $details;
    protected $testSamplesNumber;

    public function __construct( $dir )
    {
        $config                  = require_once(dirname( __DIR__ ) . '../Config/app.php');
        $this->samplesDir        = $this->getSimpleDir( $config , $dir );
        $this->samplesArr        = $this->getSamplesArr( $this->samplesDir );
        $this->indexController   = new IndexController();
        $this->statistics        = [
            'count' => 0 ,
            'true'  => 0 ,
            'false' => 0 ,
        ];
        $this->details           = [];
        $this->testSamplesNumber = $config['testSamples']['number'];
    }

    public function startTest()
    {
        foreach($this->samplesArr as $sample)
        {
            $path   = $this->samplesDir . $sample . '.png';
            $result = $this->indexController->getResult($path);
        }

        foreach($samplesArr as $sample)
        {
            //直接一个函数验证给回结果
            $result = $indexObject->getResult( $dir . '/' . $sample . '.png' );
            $sample == $result ? $statistics['true'] += 1 : $statistics['false'] += 1;
            $statistics['count'] += 1;
            $details[]           = [
                'path'   => $dir . '/' . $sample . '.png' ,
                'sample' => $sample ,
                'result' => $result ,
                'true'   => $sample == $result ,
            ];
        }

        return compact( 'statistics' , 'details' );

    }

    /**
     * @return IndexController
     */
    public function getSamplesArr( $samplesDir )
    {
        $samplesArr = [];
        //TODO 去dir下面寻找全部的文件，得到数组，返回

        return $samplesArr;
    }

    //TODO 对结果分类
    public function resultClassify(...$result)
    {
        //这里对结果分类，写到全局变量中
    }

    public function getSimpleDir( $config , $dir )
    {
        return empty( $dir ) ? $config['testSamples']['dir'] : $dir;
    }

    /**
     * @param $dir
     * @return array
     * 返回样本列表
     */
    public function getAllSamples( $dir )
    {
        $samplesArr = [];

        return $samplesArr;
    }


}