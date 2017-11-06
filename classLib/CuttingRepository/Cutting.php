<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:24
 */

namespace CAPTCHA_Reader\Cutting;

class Cutting implements CuttingInterface
{
    use CuttingTrait;
    public function __construct()
    {

    }

    public function getCutBeforeColumns( $width , $height , $time )
    {

    }

    public function getCutAfterColumns( $width , $height , $time )
    {

    }

    public function getCutBeforeRow( $height , $time )
    {

    }

    public function getCutAfterRow( $height , $time )
    {

    }

    public function getResult()
    {

    }
}