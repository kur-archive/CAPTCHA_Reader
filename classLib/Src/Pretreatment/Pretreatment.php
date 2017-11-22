<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Pretreatment;

use CAPTCHA_Reader\Tools\CommonTrait;

class Pretreatment implements PretreatmentInterface
{
    use PretreatmentTrait,CommonTrait;


    public function __construct()
    {
    }

    /**
     * @param array $config
     * @param array $imageInfo
     * @param array $imageBinaryArr
     * @return mixed
     */
    public function getResultArr(array $config ,array $imageInfo,array $imageBinaryArr)
    {
        $noiseCancelArr = $this->noiseCancel( $imageInfo['width'] , $imageInfo['height'] , $imageBinaryArr );
        return $noiseCancelArr;
    }


    /**
     * @param $width
     * @param $height
     * @param $array
     * @return mixed
     * 降噪
     */
    public function noiseCancel( $width , $height , $array )
    {
        for($y = 0; $y < $height; ++$y)
        {
            for($x = 0; $x < $width; ++$x)
            {
                if ($array[$y][$x] == 1)
                {
                    $num = 0;
                    // 上
                    if (isset( $array[$y - 1][$x] ))
                    {
                        $num = $num + $array[$y - 1][$x];
                    }
                    // 下
                    if (isset( $array[$y + 1][$x] ))
                    {
                        $num = $num + $array[$y + 1][$x];
                    }
                    // 左
                    if (isset( $array[$y][$x - 1] ))
                    {
                        $num = $num + $array[$y][$x - 1];
                    }
                    // 右
                    if (isset( $array[$y][$x + 1] ))
                    {
                        $num = $num + $array[$y][$x + 1];
                    }
                    // 上左
                    if (isset( $array[$y - 1][$x - 1] ))
                    {
                        $num = $num + $array[$y - 1][$x - 1];
                    }
                    // 上右
                    if (isset( $array[$y - 1][$x + 1] ))
                    {
                        $num = $num + $array[$y - 1][$x + 1];
                    }
                    // 下左
                    if (isset( $array[$y + 1][$x - 1] ))
                    {
                        $num = $num + $array[$y + 1][$x - 1];
                    }
                    // 下右
                    if (isset( $array[$y + 1][$x + 1] ))
                    {
                        $num = $num + $array[$y + 1][$x + 1];
                    }
                    if ($num < 3)
                    {//如果周围的像素数量小于3（也就是为1，或2）则判定为噪点，去除
                        $array[$y][$x] = '0';
                    }
                    else
                    {
                        $array[$y][$x] = '1';
                    }
                }
            }
        }
        return $array;

    }


}