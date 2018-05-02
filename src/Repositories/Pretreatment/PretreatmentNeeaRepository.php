<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:21
 */

namespace CAPTCHAReader\src\Repository\Pretreatment;


use CAPTCHAReader\src\App\Pretreatment\PretreatmentNeea;
use CAPTCHAReader\src\Traits\CommonTrait;

class PretreatmentNeeaRepository
{
    use CommonTrait;

    /**
     * @param $image
     * @return string
     */
    public function checkCAPTCHAType($image)
    {
        //红色框　20*20
        for ($y = 0; $y <= 20; ++$y) {
            for ($x = 0; $x <= 20; ++$x) {
                $pixelRGB = $this->getPixelRGB($image, $x, $y);
                if ($pixelRGB['red'] == 255 && $pixelRGB['green'] == 0 && $pixelRGB['blue'] == 0) {
                    $red = true;
                }
                if ($pixelRGB['red'] == 0 && $pixelRGB['green'] == 0 && $pixelRGB['blue'] == 255) {
                    $blue = true;
                }
            }
        }
        if (($red ?? false) && ($blue ?? false)) {
            return PretreatmentNeea::A;
        }

        //有阴影
        $shadow = 0;
        for ($y = 40; $y <= 80; ++$y) {
            for ($x = 30; $x <= 170; ++$x) {
                $pixelRGB = $this->getPixelRGB($image, $x, $y);
                $color = $pixelRGB['red'];
                if ($pixelRGB['red'] == $color && $pixelRGB['green'] == $color && $pixelRGB['blue'] == $color) {
                    if ($color > 25 && $color < 120) {
                        ++$shadow;
                    }
                }
            }
        }
//        dump($shadow);
        if ($shadow > 750 && ($otherColor ?? false) == false) {
            return PretreatmentNeea::B;
        }

        //普通版
        return PretreatmentNeea::C;

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
                if ($rgbArray['red'] > 130 && $rgbArray['green'] > 130 && $rgbArray['blue'] > 130) {
                    $imageArr[$y][$x] = '0';
                } elseif ($rgbArray['red'] == $rgbArray['green'] && $rgbArray['green'] == $rgbArray['blue']) {
                    if (($rgbArray['red'] >= 0 && $rgbArray['red'] <= 55) || ($rgbArray['red'] >= 110 && $rgbArray['red'] <= 155)) {
                        $imageArr[$y][$x] = '1';
                    } else {
                        $imageArr[$y][$x] = '0';
                    }
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

    public function binarizationB($width, $height, $image)
    {
        $imageArr = [];
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($x < 6 && $y < 6) {
                    $imageArr[$y][$x] = '0';
                    continue;
                }
                $rgbArray = $this->getPixelRGB($image, $x, $y);
                if ($rgbArray['red'] > 130 && $rgbArray['green'] > 130 && $rgbArray['blue'] > 130) {
                    $imageArr[$y][$x] = '0';
                } elseif ($rgbArray['red'] == $rgbArray['green'] && $rgbArray['green'] == $rgbArray['blue']) {
                    if (($rgbArray['red'] >= 0 && $rgbArray['red'] <= 55) || ($rgbArray['red'] >= 110 && $rgbArray['red'] <= 155)) {
                        $imageArr[$y][$x] = '1';
                    } else {
                        $imageArr[$y][$x] = '0';
                    }
                }else {
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
     * @param $image
     * @return array
     * 二值化
     */
    public function binarizationC($width, $height, $image)
    {
        $imageArr = [];
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($x * $y < 180) {
                    $imageArr[$y][$x] = '0';
                    continue;
                }
                $rgbArray = $this->getPixelRGB($image, $x, $y);
                if ($rgbArray['red'] > 138 && $rgbArray['green'] > 138 && $rgbArray['blue'] > 138) {
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

    /**
     * @param $arr
     * @param $width
     * @param $height
     * @return array
     */
    public function erosion9($arr, $width, $height, $threshold = 9)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {
//                $top = $indexY != 0;
////                $top2 = $indexY - 1 != 0;
//                $leftmost = $indexX != 0;
////                $leftmost2 = $indexX - 1 != 0;
//                $rightmost = $indexX != $width - 1;
////                $rightmost2 = $indexX + 1 != $width - 1;
//                $bottom = $indexY != $height - 1;
////                $bottom2 = $indexY + 1 != $height - 1;

                $sum = 0;
                $sum += $arr[$indexY][$indexX];

                //正上
                $sum += $arr[$indexY - 1][$indexX] ?? 0;
                //左上
                $sum += $arr[$indexY - 1][$indexX - 1] ?? 0;
                //左
                $sum += $arr[$indexY][$indexX - 1] ?? 0;
                //左下
                $sum += $arr[$indexY + 1][$indexX - 1] ?? 0;
                //正下
                $sum += $arr[$indexY + 1][$indexX] ?? 0;
                //右上
                $sum += $arr[$indexY - 1][$indexX + 1] ?? 0;
                //右
                $sum += $arr[$indexY][$indexX + 1] ?? 0;
                //右下
                $sum += $arr[$indexY + 1][$indexX + 1] ?? 0;

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
    public function erosion16($arr, $width, $height, $threshold = 16)
    {
        $result = [];
        foreach ($arr as $indexY => $row) {
            foreach ($row as $indexX => $rowX) {

                $sum = 0;
                $sum += $arr[$indexY][$indexX];
                /**
                 * x x x x
                 * x V x x
                 * x x x x
                 * x x x x
                 */
                //正上
                $sum += $arr[$indexY - 1][$indexX] ?? 0;
                //正下
                $sum += $arr[$indexY + 1][$indexX] ?? 0;
                $sum += $arr[$indexY + 2][$indexX] ?? 0;

                //左上
                $sum += $arr[$indexY - 1][$indexX - 1] ?? 0;
                //左
                $sum += $arr[$indexY][$indexX - 1] ?? 0;
                //左下
                $sum += $arr[$indexY + 1][$indexX - 1] ?? 0;
                $sum += $arr[$indexY + 2][$indexX - 1] ?? 0;

                //右上
                $sum += $arr[$indexY - 1][$indexX + 1] ?? 0;
                $sum += $arr[$indexY - 1][$indexX + 2] ?? 0;
                //右
                $sum += $arr[$indexY][$indexX + 1] ?? 0;
                $sum += $arr[$indexY][$indexX + 2] ?? 0;
                //右下
                $sum += $arr[$indexY + 1][$indexX + 1] ?? 0;
                $sum += $arr[$indexY + 2][$indexX + 1] ?? 0;
                $sum += $arr[$indexY + 1][$indexX + 2] ?? 0;
                $sum += $arr[$indexY + 2][$indexX + 2] ?? 0;

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
    public function erosion4($arr, $width, $height, $threshold = 4)
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
//                if ($top) {
//                    //正上
//                    $sum += $arr[$indexY - 1][$indexX];
//                }
                if ($leftmost) {
//                    //左上
//                    $sum += $top ? $arr[$indexY - 1][$indexX - 1] : 0;
                    //左
                    $sum += $arr[$indexY][$indexX - 1];
//                    //左下
//                    $sum += $bottom ? $arr[$indexY + 1][$indexX - 1] : 0;
                }
                if ($bottom) {
                    //正下
                    $sum += $arr[$indexY + 1][$indexX];
                }
                if ($rightmost) {
//                    //右上
//                    $sum += $bottom ? $arr[$indexY + 1][$indexX + 1] : 0;
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
     * @param $arr
     * @param $width
     * @param $height
     * @return array
     */
    public function expansion($arr, $width, $height, $threshold = 0)
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
                    $sum += $top ? $arr[$indexY - 1][$indexX + 1] : 0;
                    //右
                    $sum += $arr[$indexY][$indexX + 1];
                    //右下
                    $sum += $bottom ? $arr[$indexY + 1][$indexX + 1] : 0;
                }

                if ($sum > $threshold) {
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

        for ($h = 0; $h < $height; $h += 4) {
            for ($w = 0; $w < $weight; $w += 3) {
                $sum = 0;
                if ($arr[$h][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h][$w + 1] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h][$w + 2] ?? 0) {
                    ++$sum;
                }

                if ($arr[$h + 1][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 1][$w + 1] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 1][$w + 2] ?? 0) {
                    ++$sum;
                }

                if ($arr[$h + 2][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 2][$w + 1] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 2][$w + 2] ?? 0) {
                    ++$sum;
                }

                if ($arr[$h + 3][$w] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 3][$w + 1] ?? 0) {
                    ++$sum;
                }
                if ($arr[$h + 3][$w + 2] ?? 0) {
                    ++$sum;
                }

                if ($sum) {
                    $result[$h / 4][$w / 3] = 1;
                } else {
                    $result[$h / 4][$w / 3] = 0;
                }
            }
        }
        return $result;
    }


    public function fixCircleExpansion()
    {
        //中心是90,48的位置

    }


    public function cutMiddle($imageArr)
    {
        foreach ($imageArr as $key => $value) {
            array_splice($imageArr[$key], 88, 5);
        }
        return $imageArr;
    }


}