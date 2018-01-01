<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/04
 * Time: 3:51
 */

namespace CAPTCHA_Reader\Training\AddSamples;

use CAPTCHA_Reader\Training\CommonTrait;

abstract class AddSamplesController
{
    use  CommonTrait;
    protected $config;
    protected $trainingConfig;
    private $charArr;

    /**
     * 父类中需要做好写入字典的方法，子类调用，手工训练调试之类的方法由子类完成
     */
    public function __construct()
    {
        $this->config         = $this->getConfig();
        $this->trainingConfig = $this->getConfig( 'training' );
    }


    /**
     *
     *
     * 添加样本到字典
     */
    public function addSampleToDictionary( $char , $str )
    {
        $dictionaryPath = $this->getTrainingDictionary();
        $dictionary     = json_decode( file_get_contents( $dictionaryPath ) );
        $dictionary[]   = [
            'char' => $char ,
            'str'  => $str ,
        ];
        file_put_contents( $dictionaryPath , json_encode( $dictionary ) );
    }
}