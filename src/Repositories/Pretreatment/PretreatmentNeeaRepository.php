<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:21
 */

namespace CAPTCHAReader\src\Repository\Pretreatment;


use CAPTCHAReader\src\Traits\CommonTrait;
use function PHPSTORM_META\type;

class PretreatmentNeeaRepository
{
    use CommonTrait;

    /**
     * @param $image
     * @return string
     * A 是 带网格的
     * B 是 不带网格的
     */
    public function checkCAPTCHAType($image)
    {
        $deviation = 5;
        $checkPointArr = [
            ['point'  => [6, 0],
             'target' => ['red' => 255, 'green' => 0, 'blue' => 0,]],
            ['point'  => [0, 6],
             'target' => ['red' => 0, 'green' => 0, 'blue' => 255,]],
            ['point'  => [6, 6],
             'target' => ['red' => 255, 'green' => 0, 'blue' => 0,]],
        ];

        $count = 0;
        foreach ($checkPointArr as $checkPoint) {
            $x = $checkPoint['point'][0];
            $y = $checkPoint['point'][1];
            $pixelRGB = $this->getPixelRGB($image, $x, $y);
            $flag = 0;
            foreach ($checkPoint['target'] as $key => $colorValue) {
                $pixelRGB[$key] <= $colorValue + $deviation && $pixelRGB[$key] >= $colorValue - $deviation ?: ++$flag;
            }
            $flag == 0 ?: ++$count;
        }

        return $count <= 1 ? 'A' : 'B';
    }

    /**
     * @param $image
     * @param $width
     * @param $height
     *
     * 颜色统计
     */
    public function colorAggregation($image, $width, $height)
    {
        $deviation = 30;
        $aggArr = [];
//        //生成网格点位列表
//        $gridPointArr = [];
//        for ($y = 0; $y < $height; ++$y) {
//            for ($x = 0; $x < $width; ++$x) {
//                $rgbArray = $this->getPixelRGB($image, $x, $y);
//                if ($rgbArray['red'] == 255 && $rgbArray['green'] == 0 && $rgbArray['blue'] == 0) {
//                    $gridPointArr[$y][] =   $x;
//                }
//                if ($rgbArray['red'] == 0 && $rgbArray['green'] == 0 && $rgbArray['blue'] == 255) {
//                    $gridPointArr[$y][] =   $x;
//                }
//            }
//        }
//        file_put_contents(__DIR__ . '/../../Other/neea.point.json', json_encode($gridPointArr));
//        self::dd(count($gridPointArr));
        $gridPointArr = json_decode(file_get_contents(__DIR__ . '/../../Other/neea.point.json'), true);

        for ($y = 0; $y < $height; $y += 2) {
            for ($x = 0; $x < $width; $x += 2) {
                $rgbArray = $this->getPixelRGB($image, $x, $y);
                if (in_array($x, $gridPointArr[$y]) || $rgbArray['red'] > 128 && $rgbArray['green'] > 128 && $rgbArray['blue'] > 128) {
                    continue;
                }

                if (count($aggArr) == 0) {
                    $aggArr[] = [
                        'red'   => $rgbArray['red'],
                        'green' => $rgbArray['green'],
                        'blue'  => $rgbArray['blue'],
                        'count' => 1,
                    ];
                } else {
                    if (count($aggArr) >= 100) {
                        self::dd($aggArr);
                    }
                    $flag = 0;
                    foreach ($aggArr as $key => $value) {
                        if ($value['red'] >= $rgbArray['red'] - $deviation
                            && $value['red'] <= $rgbArray['red'] + $deviation
                            && $value['green'] >= $rgbArray['green'] - $deviation
                            && $value['green'] <= $rgbArray['green'] + $deviation
                            && $value['blue'] >= $rgbArray['blue'] - $deviation
                            && $value['blue'] <= $rgbArray['blue'] + $deviation) {
                            $aggArr[$key]['count'] += 1;
                            ++$flag;
                            break;
                        }
                    }
                    if ($flag == 0) {
                        $aggArr[] = [
                            'red'   => $rgbArray['red'],
                            'green' => $rgbArray['green'],
                            'blue'  => $rgbArray['blue'],
                            'count' => 1,
                        ];
                    }
                }
            }
        }
        usort($aggArr, function ($arr1, $arr2) {
            if ($arr1['count'] == $arr2['count']) {
                return 0;
            }
            return ($arr1['count'] < $arr2['count']) ? 1 : -1;
        });
        return $aggArr;
    }

    /**
     * @param $width
     * @param $height
     * @param $image
     * @return array
     * 二值化
     */
    public function binarization($width, $height, $image)
    {
        $imageArr = [];
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($x < 6 && $y < 6) {
                    $imageArr[$y][$x] = '0';
                    continue;
                }
                $rgbArray = $this->getPixelRGB($image, $x, $y);
                if ($rgbArray['red'] > 128 && $rgbArray['green'] > 128 && $rgbArray['blue'] > 128) {
                    $imageArr[$y][$x] = '0';
                } else {
                    $imageArr[$y][$x] = '1';
                }
                if ($x == 0 || $y == 0 || $x == $width - 1 || $y == $height - 1) {
                    $imageArr[$y][$x] = '0';
                }
            }
        }
        return $imageArr;
    }

    /**
     * @param $width
     * @param $height
     * @param $array
     * @return mixed
     * 简单的降噪方法
     */
    public function SimpleNoiseCancel($width, $height, $array)
    {
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($array[$y][$x]) {
                    $num = 0;
                    // 上
                    if (isset($array[$y - 1][$x])) {
                        $num = $num + $array[$y - 1][$x];
                    }
                    // 下
                    if (isset($array[$y + 1][$x])) {
                        $num = $num + $array[$y + 1][$x];
                    }
                    // 左
                    if (isset($array[$y][$x - 1])) {
                        $num = $num + $array[$y][$x - 1];
                    }
                    // 右
                    if (isset($array[$y][$x + 1])) {
                        $num = $num + $array[$y][$x + 1];
                    }
                    // 上左
                    if (isset($array[$y - 1][$x - 1])) {
                        $num = $num + $array[$y - 1][$x - 1];
                    }
                    // 上右
                    if (isset($array[$y - 1][$x + 1])) {
                        $num = $num + $array[$y - 1][$x + 1];
                    }
                    // 下左
                    if (isset($array[$y + 1][$x - 1])) {
                        $num = $num + $array[$y + 1][$x - 1];
                    }
                    // 下右
                    if (isset($array[$y + 1][$x + 1])) {
                        $num = $num + $array[$y + 1][$x + 1];
                    }
                    //如果周围的像素数量小于3（也就是为1，或2）则判定为噪点，去除
                    if ($num < 2) {
                        $array[$y][$x] = '0';
                    } else {
                        $array[$y][$x] = '1';
                    }
                }
            }
        }
        return $array;
    }

    public function cancelLine()
    {

    }


}