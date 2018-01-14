<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:10
 */

namespace CAPTCHAReader\src\App\Cutting;


use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\ResultContainer;

class CuttingZhengFangMove extends Load
{
    function run( ResultContainer $resultContainer ){
        //↓↓↓↓↓↓↓↓↓↓↓↓↓DEL
        $info = [];
        $all  = [];
        $yz   = [];
        //↑↑↑↑↑↑↑↑↑↑↑↑↑↑DEL
        // TODO: Implement run() method.
        $tmp_x = 0;
        $tmp_y = 0;

        $start_x1 = 0;
        $end_x1   = 0;
        $start_x2 = 0;
        $end_x2   = 0;
        $start_x3 = 0;
        $end_x3   = 0;
        $start_x4 = 0;
        $end_x4   = 0;

        $start_y1 = 0;
        $end_y1   = 0;
        $start_y2 = 0;
        $end_y2   = 0;
        $start_y3 = 0;
        $end_y3   = 0;
        $start_y4 = 0;
        $end_y4   = 0;


        //切割第一个字母的前竖线
        for($x = 0; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_x1 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第一个字母的后竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }

            if ($x - $tmp_x > $yz) {
                if (!Estimate( $x , $y , 'vertical' , $all , $info['height'] )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x1 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {//有空行，且截取的宽度>2
                $end_x1 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x1 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第二个字母的前竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_x2 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第二个字母的后竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!Estimate( $x , $y , 'vertical' , $all , $info['height'] )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x2 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x2 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x >= 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x2 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第三个字母的前竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_x3 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第三个字母的后竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!Estimate( $x , $y , 'vertical' , $all , $info['height'] )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x3 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x3 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x3 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第四个字母的前竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_x4 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第四个字母的后竖线
        for($x = $tmp_x; $x < $info['width']; ++$x){
            $num = 0;
            for($y = 0; $y < $info['height']; ++$y){
                $num += $all[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!Estimate( $x , $y , 'vertical' , $all , $info['height'] )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x4 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x4 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x4 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第一个字母的上横线
        for($y = 0; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x1; $x < $end_x1; ++$x){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_y1 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第一个字母的下横线
        for($y = $tmp_y; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x1; $x < $end_x1; ++$x){
                $num += $all[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x1; $p < $end_x1; ++$p){
                        $_num += $all[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y1 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第二个字母的上横线
        for($y = 0; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x2; $x < $end_x2; ++$x){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_y2 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第二个字母的下横线
        for($y = $tmp_y; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x2; $x < $end_x2; ++$x){
                $num += $all[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x2; $p < $end_x2; ++$p){
                        $_num += $all[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y2 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第三个字母的上横线
        for($y = 0; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x3; $x < $end_x3; ++$x){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_y3 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第三个字母的下横线
        for($y = $tmp_y; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x3; $x < $end_x3; ++$x){
                $num += $all[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x3; $p < $end_x3; ++$p){
                        $_num += $all[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y3 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第四个字母的上横线
        for($y = 0; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x4; $x < $end_x4; ++$x){
                $num += $all[$y][$x];
            }
            if ($num > 0) {
                $start_y4 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第四个字母的下横线
        for($y = $tmp_y; $y < $info['height']; ++$y){
            $num = 0;
            for($x = $start_x4; $x < $end_x4; ++$x){
                $num += $all[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x4; $p < $end_x4; ++$p){
                        $_num += $all[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y4 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        echo "x:S1:$start_x1,E1:$end_x1,S2:$start_x2,E2:$end_x2,S3:$start_x3,E3:$end_x3,S4:$start_x4,E4:$end_x4<br/\><br/\><br/\>";
        echo "y:S1:$start_y1,E1:$end_y1,S2:$start_y2,E2:$end_y2,S3:$start_y3,E3:$end_y3,S4:$start_y4,E4:$end_y4<br/\><br/\><br/\>";

        $letter1 = $letter2 = $letter3 = $letter4 = array();

        //获得切割坐标后截取
        for($y = $start_y1 , $_y = 0; $y < $end_y1; ++$y , ++$_y){
            for($x = $start_x1 , $_x = 0; $x < $end_x1; ++$x , ++$_x){
                $letter1[$_y][$_x] = $all[$y][$x];
            }
        }
        for($y = $start_y2 , $_y = 0; $y < $end_y2; ++$y , ++$_y){
            for($x = $start_x2 , $_x = 0; $x < $end_x2; ++$x , ++$_x){
                $letter2[$_y][$_x] = $all[$y][$x];
            }
        }
        for($y = $start_y3 , $_y = 0; $y < $end_y3; ++$y , ++$_y){
            for($x = $start_x3 , $_x = 0; $x < $end_x3; ++$x , ++$_x){
                $letter3[$_y][$_x] = $all[$y][$x];
            }
        }
        for($y = $start_y4 , $_y = 0; $y < $end_y4; ++$y , ++$_y){
            for($x = $start_x4 , $_x = 0; $x < $end_x4; ++$x , ++$_x){
                $letter4[$_y][$_x] = $all[$y][$x];
            }
        }


    }


}