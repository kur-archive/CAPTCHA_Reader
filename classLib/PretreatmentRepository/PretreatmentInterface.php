<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Pretreatment;

interface PretreatmentInterface
{

    /**
     * @param $imageInfo
     * @param $image
     * @return mixed
     */
    public function binarization( $width , $height , $image );

    /**
     * @param $imageInfo
     * @param $binarizationArr
     * @return mixed
     */
    public function noiseCancel( $width , $height , $binarizationArr );

    /**
     * @return mixed
     */
    public function getResultArr();
}