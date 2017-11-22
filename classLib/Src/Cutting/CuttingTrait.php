<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:24
 */

namespace CAPTCHA_Reader\Cutting;

trait CuttingTrait
{
    public function Cut( $imageArr , $charArr , $coordinate , $captchaStringNum )
    {
        for($i = 1; $i <= $captchaStringNum; $i++)
        {
            for($y = $coordinate['y' . $i] , $_y = 0; $y <= $coordinate['y' . $i . '_']; $y++ , $_y++)
            {
                for($x = $coordinate['x' . $i] , $_x = 0; $x <= $coordinate['x' . $i . '_']; $x++ , $_x++)
                {
                    $charArr['char' . $i][$_y][$_x] = $imageArr[$y][$x];
                }
            }
        }

        return $charArr;
    }
}