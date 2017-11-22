<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:23
 */

namespace CAPTCHA_Reader\Cutting;

interface CuttingInterface
{
    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutBeforeColumns( $imageArr , $width , $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutAfterColumns( $imageArr , $width , $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutBeforeRow( $imageArr , $coordinate , $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutAfterRow( $imageArr , $coordinate , $height , $time );

    /**
     * @return mixed
     */
    public function getResultArr(array $noiseCancelArr,array $imageInfo);
}