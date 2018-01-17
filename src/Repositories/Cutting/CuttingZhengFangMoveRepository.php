<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:19
 */

namespace CAPTCHAReader\src\Repository\Cutting;


class CuttingZhengFangMoveRepository
{
    function Estimate($now_x, $now_y, $mode, $array, $reference)
    {//判断是否应该继续截取
        if ($mode == 'vertical') {
            $height = $reference;
            for ($_x = $now_x + 1; $_x < $now_x + 5; ++$_x) {
                $_num = 0;
                for ($_y = 0; $_y < $height; ++$_y) {
                    $_num += $array[$_y][$_x];
                }
                if ($_num <= 1) {
                    return true;
                }
            }
            return false;
        } elseif ($mode == 'horizontal') {
            $weight = $reference;
        }

    }

}