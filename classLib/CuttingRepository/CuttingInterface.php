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
    public function getCutBeforeColumns( $width , $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutAfterColumns( $width , $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutBeforeRow( $height , $time );

    /**
     * @param $width
     * @param $height
     * @param $time
     * @return mixed
     */
    public function getCutAfterRow( $height , $time );

    /**
     * @return mixed
     */
    public function getResultArr();
}