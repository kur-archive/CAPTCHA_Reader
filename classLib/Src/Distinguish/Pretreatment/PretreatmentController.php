<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Distinguish\Pretreatment;


abstract class PretreatmentController
{
    use PretreatmentTrait;

    protected $config;

    /**
     * 该类用于降噪，因为降噪的策略可能会千变万化，所以这块尽可能让子类去实现
     *
     * 原本的那个简单的降噪方法移动到Trait类中去，作为一个简单的类库支持
     * getResultArr作为父类方法即可
     */

    public function __construct( array $config )
    {
        $this->config = $config;

    }

    /**
     * @param array $config
     * @param array $imageInfo
     * @param array $imageBinaryArr
     * @return mixed
     */
    public function run( array $imageInfo , array $imageBinaryArr )
    {
        $noiseCancelArr = $this->noiseCancel( $imageInfo['width'] , $imageInfo['height'] , $imageBinaryArr );
        return $noiseCancelArr;
    }

    abstract public function noiseCancel($width , $height , $imageBinaryArr);


}