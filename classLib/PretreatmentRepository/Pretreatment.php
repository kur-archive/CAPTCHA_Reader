<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Pretreatment;

class Pretreatment implements PretreatmentInterface
{
    use PretreatmentTrait;

    protected $imageInfo;
    protected $results;

    public function __construct(array $imageInfo , $image )
    {

    }


    public function binarization( $width , $height , $image )
    {

    }

    public function noiseCancel( $width , $height , $binarizationArr )
    {

    }

    public function getResult()
    {

    }

}