<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Pretreatment;

class Pretreatment implements PretreatmentInterface
{
    use PretreatmentTrait;

    protected $imageInfo;
    protected $results;
    protected $imageArr;

    public function __construct( array $imageInfo , $image )
    {
        $this->imageInfo = $imageInfo;
        $this->imageArr  = $this->binarization( $this->imageInfo['width'] , $this->imageInfo['height'] , $image );

        $this->imageArr  = $this->noiseCancel( $this->imageInfo['width'] , $this->imageInfo['height'] , $this->imageArr );
    }

    /**
     * @param $width
     * @param $height
     * @param $image
     * @return array
     * 二值化
     */
    public function binarization( $width , $height , $image )
    {
        $imageArr = [];
        for($y = 0; $y < $height; ++$y)
        {
            for($x = 0; $x < $width; ++$x)
            {
                $rgb      = imagecolorat( $image , $x , $y );
                $rgbArray = imagecolorsforindex( $image , $rgb );
                if ($rgbArray['red'] < 110 && $rgbArray['green'] < 110 && $rgbArray['blue'] > 100)
                {
                    $imageArr[$y][$x] = '1';
                }
                else
                {
                    $imageArr[$y][$x] = '0';
                }
            }
        }
        imagedestroy( $image );
        return $imageArr;
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

    public function getResultArr()
    {
        return $this->imageArr;
    }

}