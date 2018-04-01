<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:19
 */

namespace CAPTCHAReader\src\Repository\Cutting;


use CAPTCHAReader\src\Traits\CommonTrait;

class CuttingNeeaRepository
{
    use CommonTrait;


    function Estimate($now_x, $now_y, $array, $reference)
    {
        //判断是否应该继续截取
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
    }

    public function droplet($arr, $threshold = 60)
    {
        $height = count($arr);
        $width = count($arr[0]);

        $aggs = [];
        for ($i = 0; $i < $width; ++$i) {
            $start = 0;
            $startFlag = 0;
            $end = 0;
            for ($j = 0; $j < $height; ++$j) {
                if ($arr[$j][$i]) {
                    if (!$startFlag) {
                        $start = $j;
                        $startFlag = 1;
                    }
                }
                if ($arr[$j][$i]) {
                    $end = $j + 1;
                }
                if ($j == $height - 1) {
                    $aggs[$i] = $end - $start;
                }
            }
        }
        self::dd(1);

        //根据上下差的投影和数量投影，计算可行的开始位置方案
        $startPoints = [];
        $number = 0;
        $nowValue = 0;
        foreach ($aggs as $key => $value) {
            if (count($startPoints) == 3) {
                break;
            }
            $isValleyBottom = $this->isValleyBottom($arr, $key);
            if (!$isValleyBottom) {
                $number++;
                continue;
            }
            if ($number < ($threshold * 0.09)) {
                $number++;
                continue;
            }
            $startPoints[] = $key;
        }

        $cuttingSet = [];
        //然后水滴向下，带有惯性切割，然后补齐
        foreach ($startPoints as $key => $point) {
            $nowX = $point;
            for ($nowY = 0; $nowY < count($arr); ++$nowY) {
                $cuttingSet[$key][$nowY] = $nowX;
                $nextStep = $this->nextStep($arr, $nowY, $nowX);
                $nowX += $nextStep;
            }
        }


        return $arr;
    }

    public function isValleyBottom($arr, $nowKey, $threshold = 3)
    {
        $sum = 0;
        $max = ($nowKey + $threshold) >= count($arr) ? count($arr) : $nowKey + $threshold + 1;
        for ($i = $nowKey + 1; $i < $max; ++$i) {
            $sum += $arr[$i];
        }
        $rightAve = $sum / ($max - $nowKey - 1);

        if ($rightAve <= $arr[$nowKey]) {
            return false;
        }

        $sum = 0;
        $min = ($nowKey - $threshold) < 0 ? 0 : $nowKey - $threshold - 1;
        for ($i = $nowKey - 1; $i > $min; --$i) {
            $sum += $arr[$i];
        }
        $leftAve = $sum / ($nowKey - $min + 1);

        if ($leftAve <= $arr[$nowKey]) {
            return false;
        }

        return true;
    }

    /**
     * @param $arr
     * @param $x
     * @param $y
     * @return array
     */
    public function nextStep($arr, $x, $y, $inertia = 0)
    {
        //TODO to be continue
        //这里要保存惯性,在返回的时候回传惯性，然后在再次调用的时候传入
        return [-1, 0, 1];
    }
}