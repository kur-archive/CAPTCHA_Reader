<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/09/28
 * Time: 17:29
 */

/**
 * Class NoiseCanceling
 * 降噪 & 二值化
 */
class Pretreatment
{
    protected $imagePath;
    protected $imageInfo;
    protected $resArr;
    const THRESHOLD = 3; //阈值


    public function __construct( string $imagePath )
    {
        $this->imagePath = $imagePath;

        $image = $this->getImgInfo( $this->imagePath );

        //二值化
        $resArr = $this->binarization( $this->imageInfo , $image );
//        imagedestroy( $image );

        //降噪
        $this->resArr = $this->noiseCancel( $this->imageInfo['height'] , $this->imageInfo['width'] , $resArr );
    }

    /**
     * @return mixed
     */
    public function getImgInfo( string $imagePath)
    {
        if (empty( $this->imageInfo ))
        {
            $imageInfo = $this->setImgInfo( $imagePath );
            return $imageInfo;
        }
        else
        {
            return $this->imageInfo;
        }
    }

    /**
     * get image info and set to $imgInfo
     * BP -> before processing
     */
    private function setImgInfo( string $imagePath )
    {
        $imgInfo_BP = getimagesize( $imagePath );
        $imgInfo    = array(
            'width'  => $imgInfo_BP[0] ,
            'height' => $imgInfo_BP[1] ,
            'type'   => image_type_to_extension( $imgInfo_BP[2] , false ) ,
            'mine'   => $imgInfo_BP['mime'] ,
        );

        $func  = 'imagecreatefrom' . $imgInfo['type'];
        $image = $func( $imagePath );

        $this->imageInfo = $imgInfo;
        return $image;

    }

    /**
     * 二值化
     * @param $imageInfo
     * @param $image
     * @return array
     */
    private function binarization( $imageInfo , $image )
    {
        $resArr = [];

        for($y = 0; $y < $imageInfo['height']; ++$y)
        {
            for($x = 0; $x < $imageInfo['width']; ++$x)
            {
                $rgb      = imagecolorat( $image , $x , $y );
                $rgbArray = imagecolorsforindex( $image , $rgb );

                $rgbArray['red'] < 110
                && $rgbArray['green'] < 110
                && $rgbArray['blue'] > 100
                    ? $resArr[$y][$x] = '1'
                    : $resArr[$y][$x] = '0';
            }
        }

        imagedestroy( $image );
        return $resArr;
    }

    /**
     * 降噪
     * @param $height
     * @param $width
     * @param $array
     * @return mixed
     */
    public function noiseCancel( $height , $width , $array )
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
                    if ($num < self::THRESHOLD)
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


    /**
     * 获取处理好的数组
     * @return mixed
     */
    public function getResArr()
    {
        return $this->resArr;
    }


    /**
     * 调试用，show处理好的数组
     */
    public function showResArr()
    {
        echo "\n";
        foreach($this->resArr as $resY)
        {
            foreach($resY as $resX)
            {
                $resX ? $output = '◆' : $output = '◇';
                echo $output;
            }
            echo "\n";

        }
        echo "\n";
    }


}