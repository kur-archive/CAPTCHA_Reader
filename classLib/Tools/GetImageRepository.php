<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/04
 * Time: 3:35
 */

namespace CAPTCHA_Reader\Tools;

class GetImageRepository
{
//两种模式，
//一种是给定链接(未给定则寻找默认位置)，去下载图像，返回图像信息和图像资源
//一种是给定路径，去路径寻找图像，返回图像信息和图像资源
//一种是去config里面找图像库，从图像库中随机选择一张图像，返回图像信息和图像资源，一般用于调试
    public function __construct()
    {
        try
        {

        }
        catch (\Exception $e)
        {

        }
    }

    public function getImage()
    {

    }

}