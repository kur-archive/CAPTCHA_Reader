<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:25
 */
namespace CAPTCHA_Reader\Identify;

trait IdentifyTrait
{
    /**
     * @param $array
     * @return string
     * 将二维数组转为字符串
     */
    function twoDimArrayToStr( $array )
    {
        $str = '';
        foreach($array as $key => $value)
        {
            foreach($value as  $value_)
            {
                $str .= $value_;
            }
        }
        return $str;
    }

}