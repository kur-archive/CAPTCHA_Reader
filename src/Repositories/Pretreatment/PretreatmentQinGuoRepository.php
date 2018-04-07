<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:21
 */

namespace CAPTCHAReader\src\Repository\Pretreatment;


use CAPTCHAReader\src\Traits\CommonTrait;

class PretreatmentQinGuoRepository
{
    use CommonTrait;

    /**
     * @param $width
     * @param $height
     * @param $image
     * @return array
     * 二值化
     */
    public function binarization($width, $height, $image, $colorTop4)
    {
        $imageArr = [];
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $rgbArray = $this->getPixelRGB($image, $x, $y);
                if ($rgbArray['red'] < 180 && $rgbArray['green'] < 180 && $rgbArray['blue'] < 180) {
                    $imageArr[$y][$x] = '1';
                } else {
                    $imageArr[$y][$x] = '0';
                }
                if ($x == 0 || $y == 0 || $x == $width - 1 || $y == $height - 1) {
                    $imageArr[$y][$x] = '0';
                }

            }
        }
        return $imageArr;
    }

    public function inArea($colorTop4, $rgbArray)
    {
        $deviation = 42;
        $deviationPow = pow($deviation, 2);
        $flag = 0;
        foreach ($colorTop4 as $value) {
            if ((pow($value['red'] - $rgbArray['red'], 2)
                    + pow($value['green'] - $rgbArray['green'], 2)
                    + pow($value['blue'] - $rgbArray['blue'], 2))
                <= $deviationPow) {
                $flag = 1;
                break;
            }
        }
        return $flag == 1 ? true : false;

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
                    if ($num < 5) {
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
    public function erosion($arr, $width, $height)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {
                $top = $indexY != 0;
                $leftmost = $indexX != 0;
                $rightmost = $indexX != $width - 1;
                $bottom = $indexY != $height - 1;

                $sum = 0;
                $sum += $arr[$indexY][$indexX];
                if ($top) {
                    //正上
                    $sum += $arr[$indexY - 1][$indexX];
                }
                if ($leftmost) {
                    //左上
//                    $sum += $top ? $arr[$indexY - 1][$indexX - 1] : 0;
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
                    $sum += $top ? $arr[$indexY - 1][$indexX + 1] : 0;
                    //右
                    $sum += $arr[$indexY][$indexX + 1];
                    //右下
//                    $sum += $bottom ? $arr[$indexY + 1][$indexX + 1] : 0;
                }

                if ($sum < 5) {
                    $result[$indexY][$indexX] = 0;
                } else {
                    $result[$indexY][$indexX] = 1;
                }

            }
        }
        return $result;

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

        for ($y = 1; $y < $height - 1; $y += 1) {
            for ($x = 1; $x < $width - 1; $x += 1) {
                $rgbArray = $this->getPixelRGB($image, $x, $y);

//                if (($rgbArray['red'] + $rgbArray['green'] + $rgbArray['blue']) > 600) {
//                    continue;
//                }

                if (count($aggArr) == 0) {
                    $aggArr[] = [
                        'red'   => $rgbArray['red'],
                        'green' => $rgbArray['green'],
                        'blue'  => $rgbArray['blue'],
                        'count' => 1,
                    ];

                } else {
                    if (count($aggArr) >= 200) {
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
                    if (!$flag) {
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
        foreach ($aggArr as $key => $value) {
            if ($value['count'] <= 5) {
                unset($aggArr[$key]);
            }
        }
        return $aggArr;
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
                if ($arr[$h + 1][$w] ?? 0) {
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
                } else {
                    $result[$h / 2][$w / 2] = 0;
                }
            }
        }
        return $result;
    }

}