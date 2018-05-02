<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 22:23
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Cutting\CuttingNeeaFixedRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingNeeaRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingQinGuoShrinkRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingQinGuoUnShrinkRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingTianYiRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingTianYiShrinkRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangFixedRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangMoveRepository;

trait CuttingTrait
{
    use CommonTrait;

    /**
     * @param string $label
     * @return CuttingNeeaFixedRepository|CuttingNeeaRepository|CuttingQinGuoShrinkRepository|CuttingQinGuoUnShrinkRepository|CuttingTianYiRepository|CuttingTianYiShrinkRepository|CuttingZhengFangFixedRepository|CuttingZhengFangMoveRepository
     */
    public function getRepository(string $label)
    {
        switch ($label) {
            case "ZhengFangFixed":
                return new CuttingZhengFangFixedRepository();
            case "ZhengFangMove":
                return new CuttingZhengFangMoveRepository();
            case "QinGuoShrink":
                return new CuttingQinGuoShrinkRepository();
            case "QinGuoUnShrink":
                return new CuttingQinGuoUnShrinkRepository();
            case "Neea":
                return new CuttingNeeaRepository();
            case "NeeaFixed":
                return new CuttingNeeaFixedRepository();
            case "TianYi":
                return new CuttingTianYiRepository();
            case "TianYiShrink":
                return new CuttingTianYiShrinkRepository();
        }
    }

    /**
     * @param $x
     * @param $y
     * @param $beforeX
     * @param $afterX
     * @param $beforeY
     * @param $afterY
     * @return bool
     */
    public function isInArea($x, $y, $beforeX, $afterX, $beforeY, $afterY)
    {
        $flag = 0;
        if ($x >= $beforeX && $x <= $afterX) {
            ++$flag;
        }
        if ($y >= $beforeY && $y <= $afterY) {
            ++$flag;
        }

        if ($flag == 2) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $x_
     * @param $y_
     * @param $beforeX
     * @param $beforeY
     * @return array
     */
    public function getPointPositionInArea($x_, $y_, $beforeX, $beforeY)
    {
        $x = (int)$x_ - (int)$beforeX;
        $y = (int)$y_ - (int)$beforeY;
        return compact('x', 'y');
    }

    /**
     * @param $charCollection
     * 展示切割后的结果和二值化后的数组
     */
    public function showCharWeb($charCollection)
    {
        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';

        foreach ($charCollection as $key => $char) {
            foreach ($char as $y => $row) {
                foreach ($row as $x => $value) {
                    if ($value) {
                        echo '◆';
                    } else {
                        echo '◇';
                    }
                }
                echo '<br/>';
            }
        }
        echo '</div>';
    }


}