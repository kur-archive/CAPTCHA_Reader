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
    public function showResArr($imageArr,$arr = [], $flag = '●')
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
                if (in_array($key2, empty($arr) ? [17, 29, 42] : $arr)) {
                    if (empty($arr)) {
                        continue;
                    }
                    echo $flag;
                    continue;
                }
                $output = $resX ? '1' : '_';
                echo $output;
            }
            echo "\n";

        }
        echo "\n";
    }

    /**
     * @param $imageArr
     * @param array $arr
     * @param string $flag
     */
    public function showResArrAndAggs($imageArr, $arr = [], $flag = '●')
    {
        foreach ($imageArr as $key => $resY) {
            foreach ($resY as $key2 => $resX) {
                if (in_array($key2, empty($arr) ? [17, 29, 42] : $arr)) {
                    echo $flag;
                    continue;
                }
                $output = $resX ? '1' : '_';

                echo $output;
            }
            echo "\n";
        }
        echo "\n";

        //获取每一列的像素数量
//        $aggs = $this->getHeightProjection($imageArr);

        //获取每一行的投影
//        $aggs = $this->getDifferenceHeightProjection($imageArr);
//
//        $floor = 0;
//        while (true) {
//            $flag = 0;
//            for ($i = 0; $i < count($aggs); ++$i) {
//                if ($aggs[$i] > $floor) {
//                    echo '●';
//                    $flag = 1;
//                } else {
//                    echo ' ';
//                }
//            }
//            echo "\n";
//            $floor++;
//            if ($flag == 0) {
//                break;
//            }
//        }

        $aggs = $this->getHeightProjection($imageArr);

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

    /**
     * @param $charCollection
     */
    public function showChar($charCollection)
    {
        foreach ($charCollection as $key => $char) {
            foreach ($char as $y => $row) {
                foreach ($row as $x => $value) {
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


    /**
     * @param $noiseArr
     * @return array
     * 计算高度投影
     */
    public function getHeightProjection($noiseArr)
    {
        $result = [];
        $height = count($noiseArr);
        $width = count($noiseArr[0]);

        for ($x = 0; $x < $width; ++$x) {
            $result[$x] = 0;
            for ($y = 0; $y < $height; ++$y) {
                $result[$x] += $noiseArr[$y][$x];
            }
        }

        return $result;
    }

    /**
     * @param $noiseArr
     * @return array
     * 计算上下差投影
     */
    public function getDifferenceHeightProjection($noiseArr)
    {
        $result = [];
        $height = count($noiseArr);
        $width = count($noiseArr[0]);

        for ($x = 0; $x < $width; ++$x) {
            $min = 0;
            $max = 0;
            for ($y = 0; $y < $height; ++$y) {
                $value = $noiseArr[$y][$x];
                if ($value) {
                    $max == 0 && $min == 0 ? $min = $y : $max = $y + 1;
                }
            }
            !($max == 0 && $min != 0) ?: $max = $min + 1;
            $result[$x] = $max - $min;
        }
        return $result;
    }
}