<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/13
 * Time: 0:34
 */

namespace CAPTCHA_Reader\GetImageInfo;


interface GetImageInfoInterFace
{

    public function getImageInfo();

    public function getImage();

    public function __destruct();
}