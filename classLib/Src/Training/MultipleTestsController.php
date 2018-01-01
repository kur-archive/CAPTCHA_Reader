<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/04
 * Time: 3:53
 */

namespace CAPTCHA_Reader\Training;


use CAPTCHA_Reader\CommonTrait;
use CAPTCHA_Reader\IndexController;

class MultipleTestsController
{
    use CommonTrait;
    protected $samplesDir;
    protected $samplesArr;
    protected $indexController;
    protected $statistics;
    protected $details;
    protected $testSamplesNumber;

    protected $resultClassify;



    /**
     * MultipleTestsController constructor.
     * @param string $dir
     */
    public function __construct( $dir = '' )
    {
        $config                  = require_once(dirname( __DIR__ ) . '../Config/app.php');
        $this->samplesDir        = $this->getSimpleDir( $config , $dir );
        $this->samplesArr        = $this->getSamplesArr( $this->samplesDir );
        $this->indexController   = new IndexController( 'local' );
        $this->resultClassify    = [
            'count'     => 0 ,
            'true'      => 0 ,
            'false'     => 0 ,
            'charTrue'  => 0 ,
            'charFalse' => 0 ,
        ];
        $this->details           = [];
        $this->testSamplesNumber = $config['testSamples']['number'];
    }

    public function startTest()
    {
        foreach($this->samplesArr as $sample)
        {
            $path   = $this->samplesDir . $sample . '.png';
            $result = $this->indexController->getResult( $path );
            $this->resultClassify( $sample , $result );
        }

        return [$this->resultClassify , $this->details];
    }

    /**
     * @param string $samplesDir
     * @return array
     */
    public function getSamplesArr( $samplesDir )
    {
        return $this->dirTraverse( $samplesDir );
    }

    /**
     * @param string $str1
     * @param string $str2
     */
    public function resultClassify( $str1 , $str2 )
    {
        $detail = $str2;
        $str2   = $str2[0]['char'] . $str2[1]['char'] . $str2[2]['char'] . $str2[3]['char'];
        ++$this->resultClassify['count'];
        $str1 == $str2 ? ++$this->resultClassify['true'] : ++$this->resultClassify['false'];

        $str1[0] == $str2[0] ? ++$this->resultClassify['charTrue'] : ++$this->resultClassify['charFalse'];
        $str1[1] == $str2[1] ? ++$this->resultClassify['charTrue'] : ++$this->resultClassify['charFalse'];
        $str1[2] == $str2[2] ? ++$this->resultClassify['charTrue'] : ++$this->resultClassify['charFalse'];
        $str1[3] == $str2[3] ? ++$this->resultClassify['charTrue'] : ++$this->resultClassify['charFalse'];

        $this->details[] = [
            'sample'      => $str1 ,
            'result'      => $str2 ,
            'tureOrFalse' => $str1 == $str2 ,
            'detail'      => $detail ,
        ];
    }

    /**
     * @param string $directory
     */
    function dirTraverse( $directory , $simplesArr = [] )
    {
        $dir = dir( $directory );
        while ($file = $dir->read())
        {
            if ((is_dir( $directory . $file )) && ($file != ".") && ($file != ".."))
            {
                var_dump( $file );
                $simplesArr = $this->dirTraverse( $directory . $file , $simplesArr );
            }
            else
            {
                if ($file === '.' || $file === '..')
                {
                    continue;
                }
                array_push( $simplesArr , explode( '.' , $file )[0] );
            }
        }
        $dir->close();
        return $simplesArr;
    }

    /**
     * @param $config
     * @param $dir
     * @return mixed
     */
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