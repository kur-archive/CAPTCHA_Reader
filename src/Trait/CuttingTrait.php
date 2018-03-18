<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 22:23
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Cutting\CuttingNeeaRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingQinGuoRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangFixedRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangMoveRepository;

trait CuttingTrait
{
    use CommonTrait;

    /**
     * @param string $label
     * @return CuttingNeeaRepository|CuttingQinGuoRepository|CuttingZhengFangFixedRepository|CuttingZhengFangMoveRepository
     */
    public function getRepository( string $label ){
        switch ($label) {
            case "ZhengFangFixed":
                return new CuttingZhengFangFixedRepository();
            case "ZhengFangMove":
                return new CuttingZhengFangMoveRepository();
            case "QinGuo":
                return new CuttingQinGuoRepository();
            case "Neea":
                return new CuttingNeeaRepository();
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
    public function isInArea( $x , $y , $beforeX , $afterX , $beforeY , $afterY ){
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
    public function getPointPositionInArea( $x_ , $y_ , $beforeX , $beforeY ){
        $x = (int)$x_ - (int)$beforeX;
        $y = (int)$y_ - (int)$beforeY;
        return compact( 'x' , 'y' );
    }

    /**
     * @param $charCollection
     * 展示切割后的结果和二值化后的数组
     */
    public function showCharWeb( $charCollection ){
        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';

        foreach($charCollection as $key => $char){
            foreach($char as $y => $row){
                foreach($row as $x => $value){
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

    /**
     * @param $charCollection
     */
    public function showChar( $charCollection ){
        foreach($charCollection as $key => $char){
            foreach($char as $y => $row){
                foreach($row as $x => $value){
                    if ($value) {
                        echo 'l ';
                    } else {
                        echo '_ ';
                    }
                }
                echo "\n";
            }
            echo "\n";

        }
    }

}