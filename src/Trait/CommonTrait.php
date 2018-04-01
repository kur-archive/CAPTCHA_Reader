<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 3:02
 */

namespace CAPTCHAReader\src\Traits;


trait CommonTrait
{
    public function getConfig($configType = 'app')
    {
        $config = require(__DIR__ . '/../Config/' . $configType . '.php');
        return $config;
    }

    /**
     * @param array ...$vars
     */
    public static function dd(...$vars)
    {
        foreach ($vars as $var) {
            dump($var);
        }
        exit();
    }

    /**
     * 调试用，show处理好的数组
     */
    public function showResArr($imageArr)
    {
        echo '  ';
//        for($i = 0; $i < 72; ++$i){
//            echo $i;
//            if (strlen( $i ) == 1) {
//                echo ' ';
//            }
//        }
        echo "\n";
        foreach ($imageArr as $key => $resY) {
//            echo $key;
//            if (strlen( $key ) == 1) {
//                echo ' ';
//            }
            foreach ($resY as $key2 => $resX) {
//                if (in_array($key2, [30, 51, 68])) {
//                    echo '●';
//                    continue;
//                }
                $resX ? $output = 'l' : $output = '_';
                echo $output;
            }
            echo "\n";

        }
        echo "\n";
    }

    public function showResArrAndAggs($imageArr)
    {
        $height = count($imageArr);
        $width = count($imageArr[0]);

        foreach ($imageArr as $key => $resY) {

            foreach ($resY as $key2 => $resX) {
//                if (in_array($key2, [35, 65, 92])) {
//                    echo '●';
//                    continue;
//                }
                $resX ? $output = 'l' : $output = '_';
                echo $output;
            }
            echo "\n";

        }
        echo "\n";

        $aggs = [];
        //获取每一列的像素数量
//        for ($i = 0; $i < $width; ++$i) {
//            for ($j = 0; $j < $height; ++$j) {
//                $aggs[$i] = ($aggs[$i] ?? 0) + $imageArr[$j][$i];
//            }
//        }

        //获取每一行的投影
        for ($i = 0; $i < $width; ++$i) {
            $start = 0;
            $startFlag = 0;
            $end = 0;
            for ($j = 0; $j < $height; ++$j) {
                if ($imageArr[$j][$i]) {
                    if (!$startFlag) {
                        $start = $j;
                        $startFlag = 1;
                    }
                }
                if ($imageArr[$j][$i]) {
                    $end = $j + 1;
                }
                if ($j == $height - 1) {
                    $aggs[$i] = $end - $start;
                }
            }
        }

        $floor = 0;
        while (true) {
            $flag = 0;
            for ($i = 0; $i < count($aggs); ++$i) {
                if ($aggs[$i] > $floor) {
                    echo '●';
                    $flag = 1;
                } else {
                    echo ' ';
                }
            }
            echo "\n";
            $floor++;
            if ($flag == 0) {
                break;
            }
        }
    }

    /**
     * @param $image
     * @param $x
     * @param $y
     * @return array
     */
    public function getPixelRGB($image, $x, $y)
    {
        $rgbArray = imagecolorsforindex($image, imagecolorat($image, $x, $y));
        return $rgbArray;
    }

//    public function colProjection($)
//    {
//
//    }


}