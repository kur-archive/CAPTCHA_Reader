<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:21
 */

namespace CAPTCHAReader\src\Repository\Pretreatment;


use CAPTCHAReader\src\Traits\CommonTrait;

class PretreatmentTianYiRepository
{
    use CommonTrait;

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
                $rgb = imagecolorat($image, $x, $y);
                $rgbArray = imagecolorsforindex($image, $rgb);
                if ($rgbArray['red'] < 155 && $rgbArray['green'] < 155 && $rgbArray['blue'] < 155) {
                    $imageArr[$y][$x] = '1';
                } else {
                    $imageArr[$y][$x] = '0';
                }

            }
        }
        return $imageArr;
    }

    /**
     * @param $arr
     * @param $width
     * @param $height
     * @return array
     */
    public function erosion($arr, $width, $height, $threshold = 9)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {
                $top = $indexY != 0;
//                $top2 = $indexY - 1 != 0;
                $leftmost = $indexX != 0;
//                $leftmost2 = $indexX - 1 != 0;
                $rightmost = $indexX != $width - 1;
//                $rightmost2 = $indexX + 1 != $width - 1;
                $bottom = $indexY != $height - 1;
//                $bottom2 = $indexY + 1 != $height - 1;

                $sum = 0;
                $sum += $arr[$indexY][$indexX];
                if ($top) {
                    //正上
                    $sum += $arr[$indexY - 1][$indexX];
                }
                if ($leftmost) {
                    //左上
                    $sum += $top ? $arr[$indexY - 1][$indexX - 1] : 0;
                    //左
                    $sum += $arr[$indexY][$indexX - 1];
                    //左下
                    $sum += $bottom ? $arr[$indexY + 1][$indexX - 1] : 0;
                }
                if ($bottom) {
                    //正下
                    $sum += $arr[$indexY + 1][$indexX];
                }
                if ($rightmost) {
                    //右上
                    $sum += $bottom ? $arr[$indexY + 1][$indexX + 1] : 0;
                    //右
                    $sum += $arr[$indexY][$indexX + 1];
                    //右下
                    $sum += $top ? $arr[$indexY - 1][$indexX + 1] : 0;
                }

                if ($sum < $threshold) {
                    $result[$indexY][$indexX] = 0;
                } else {
                    $result[$indexY][$indexX] = 1;
                }

            }
        }
        return $result;
    }

    /**
     * @param $width
     * @param $height
     * @param $array
     * @return mixed
     * 简单的降噪方法
     */
    public function simpleNoiseCancel($width, $height, $array)
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
                    if ($num < 3) {
                        $array[$y][$x] = '0';
                    } else {
                        $array[$y][$x] = '1';
                    }
                }
            }
        }
        return $array;
    }

    /**
     * @param $arr
     * @param $width
     * @param $height
     * @return array
     */
    public function erosion2($arr, $width, $height, $threshold = 4)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {

                /**
                 * x V
                 * x x
                 */
                $sum = 0;
                $sum += $arr[$indexY][$indexX];

//                //正下
                $sum += $arr[$indexY + 1][$indexX] ?? 0;

                //正上
//                $sum += $arr[$indexY - 1][$indexX] ?? 0;

                //左上
//                $sum += $arr[$indexY - 1][$indexX - 1] ?? 0;
                //左
                $sum += $arr[$indexY][$indexX - 1] ?? 0;
                //左下
                $sum += $arr[$indexY + 1][$indexX - 1] ?? 0;

//                //右上
//                $sum += $arr[$indexY - 1][$indexX + 1] ?? 0;
//                //右
//                $sum += $arr[$indexY][$indexX + 1] ?? 0;
//                //右下
//                $sum += $arr[$indexY + 1][$indexX + 1] ?? 0;

                if ($sum < $threshold) {
                    $result[$indexY][$indexX] = 0;
                } else {
                    $result[$indexY][$indexX] = 1;
                }

            }
        }
        return $result;
    }

    /**
     * @param $arr
     * @param $width
     * @param $height
     * @return array
     */
    public function expansion($arr, $width, $height, $threshold = 1)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {

                /**
                 * x x
                 * x V
                 */
                $sum = 0;
                $sum += $arr[$indexY][$indexX];

//                //正下
                $sum += $arr[$indexY + 1][$indexX] ?? 0;

                //正上
//                $sum += $arr[$indexY - 1][$indexX] ?? 0;

                //左上
//                $sum += $arr[$indexY - 1][$indexX - 1] ?? 0;
                //左
                $sum += $arr[$indexY][$indexX - 1] ?? 0;
                //左下
                $sum += $arr[$indexY + 1][$indexX - 1] ?? 0;

//                //右上
//                $sum += $arr[$indexY - 1][$indexX + 1] ?? 0;
//                //右
//                $sum += $arr[$indexY][$indexX + 1] ?? 0;
//                //右下
//                $sum += $arr[$indexY + 1][$indexX + 1] ?? 0;

                if ($sum >= $threshold) {
                    $result[$indexY][$indexX] = 1;
                } else {
                    $result[$indexY][$indexX] = 0;
                }

            }
        }

        return $result;
    }

    /**
     * @param $arr
     * @return array
     */
    public function shrink($arr)
    {
//        if (empty($arr)) {
//            throw new \Exception('arr can\'t empty');
//        }
        $height = count($arr);
        $weight = count($arr[0]);

        $result = [];

        for ($h = 0; $h < $height; $h += 2) {
            for ($w = 0; $w < $weight; $w += 2) {
                $sum = 0;
                if ($arr[$h][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h][$w + 1] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 1][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 1][$w + 1] ?? 0) {
                    ++$sum;
                }

                if ($sum) {
                    $result[$h / 2][$w / 2] = 1;
//                    $result[$h / 2][$w] = 1;
                } else {
                    $result[$h / 2][$w / 2] = 0;
//                    $result[$h / 2][$w] = 0;
                }
            }
        }
        return $result;
    }

    /**
     * @param $width
     * @param $height
     * @param $array
     * @return mixed
     * 简单的降噪方法
     */
    public function noiseCancel($width, $height, $array)
    {
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                //计算5*5的领点
                /** y x
                 * -2-2 -2-1 -2-0 -2+1 -2+2
                 * -1-2 -1-1 -1-0 -1+1 -1+2
                 * -0-2 -0-1 -0-0 -0+1 -0+2
                 * +1-2 +1-1 +1-0 +1+1 +1+2
                 * +2-2 +2-1 +2-0 +2+1 +2+2
                 */
                $num = 0;
                $num += $array[$y - 2][$x - 2] ?? 0;
                $num += $array[$y - 2][$x - 1] ?? 0;
                $num += $array[$y - 2][$x] ?? 0;
                $num += $array[$y - 2][$x + 1] ?? 0;
                $num += $array[$y - 2][$x + 2] ?? 0;

                $num += $array[$y - 1][$x - 2] ?? 0;
                $num += $array[$y - 1][$x - 1] ?? 0;
                $num += $array[$y - 1][$x] ?? 0;
                $num += $array[$y - 1][$x + 1] ?? 0;
                $num += $array[$y - 1][$x + 2] ?? 0;

                $num += $array[$y][$x - 2] ?? 0;
                $num += $array[$y][$x - 1] ?? 0;
                $num += $array[$y][$x] ?? 0;
                $num += $array[$y][$x + 1] ?? 0;
                $num += $array[$y][$x + 2] ?? 0;

                $num += $array[$y + 1][$x - 2] ?? 0;
                $num += $array[$y + 1][$x - 1] ?? 0;
                $num += $array[$y + 1][$x] ?? 0;
                $num += $array[$y + 1][$x + 1] ?? 0;
                $num += $array[$y + 1][$x + 2] ?? 0;

                $num += $array[$y + 2][$x - 2] ?? 0;
                $num += $array[$y + 2][$x - 1] ?? 0;
                $num += $array[$y + 2][$x] ?? 0;
                $num += $array[$y + 2][$x + 1] ?? 0;
                $num += $array[$y + 2][$x + 2] ?? 0;

                if ($array[$y][$x]) {
                    //如果周围的像素数量小于3（也就是为1，或2）则判定为噪点，去除
                    if ($num < 6) {
                        $array[$y][$x] = '0';
                    } else {
                        $array[$y][$x] = '1';
                    }
                }
            }
        }
        return $array;
    }
}