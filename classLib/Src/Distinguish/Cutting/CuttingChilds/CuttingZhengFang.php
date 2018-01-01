<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/04
 * Time: 0:11
 */

namespace CAPTCHA_Reader\Distinguish\Cutting;

class CuttingZhengFang extends CuttingController
{
    /**
     * @param array $noiseCancelArr
     * @param array $imageInfo
     * @return array|mixed
     */
    public function run( array $noiseCancelArr , array $imageInfo )
    {
        $width  = $imageInfo['width'];
        $height = $imageInfo['height'];

        for($i = 1; $i <= $this->captchaStringNum; $i++)
        {
            $this->coordinate['x'][$i]  = $this->getCutBeforeColumns( $noiseCancelArr , $width , $height , $i );
            $this->coordinate['x_'][$i] = $this->getCutAfterColumns( $noiseCancelArr , $width , $height , $i );
        }

        for($i = 1; $i <= $this->captchaStringNum; $i++)
        {
            $this->coordinate['y'][$i]  = $this->getCutBeforeRow( $noiseCancelArr , $this->coordinate , $height , $i );
            $this->coordinate['y_'][$i] = $this->getCutAfterRow( $noiseCancelArr , $this->coordinate , $height , $i );
        }

        $this->charArr = $this->Cut( $noiseCancelArr , $this->charArr , $this->coordinate , $this->captchaStringNum );

        return $this->charArr;
    }

    /**
     * @param $imageArr
     * @param $width
     * @param $height
     * @param $time
     * 横向切前切割线
     * @return int
     */
    public function getCutBeforeColumns( $imageArr , $width , $height , $time )
    {
        switch ($time)
        {
            case 1:
                for($x = 0; $x < $width; $x++)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 2:
                for($x = 17; $x < $width; $x++)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 3:
                for($x = 29; $x < $width; $x++)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 4:
                for($x = 41; $x < $width; $x++)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
        }
    }

    /**
     * @param $imageArr
     * @param $width
     * @param $height
     * @param $time
     * 横向切后切割线
     * @return int
     */
    public function getCutAfterColumns( $imageArr , $width , $height , $time )
    {
        switch ($time)
        {
            case 1:
                for($x = 16; $x < $width; $x--)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 2:
                for($x = 28; $x < $width; $x--)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 3:
                for($x = 40; $x < $width; $x--)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
            case 4:
                for($x = 54; $x < $width; $x--)
                {
                    $sum = 0;
                    for($y = 0; $y < $height; $y++)
                    {
                        $sum += (int)$imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
        }
    }

    /**
     * @param $imageArr
     * @param $coordinate
     * @param $height
     * @param $time
     * 纵向切前切割线
     * @return int
     */
    public function getCutBeforeRow( $imageArr , $coordinate , $height , $time )
    {
        for($y = 0; $y < $height; $y++)
        {
            $rowSum = 0;
            for($x = $coordinate['x'][$time]; $x < $coordinate['x_'][$time]; $x++)
            {
                $rowSum += (int)$imageArr[$y][$x];
            }
            if ($rowSum > 0)
            {
                return $y;
            }
        }

    }

    /**
     * @param $imageArr
     * @param $coordinate
     * @param $height
     * @param $time
     * 纵向切后切割线
     * @return int
     */
    public function getCutAfterRow( $imageArr , $coordinate , $height , $time )
    {
        for($y = $height - 1; $y >= 0; $y--)
        {
            $rowSum = 0;
            for($x = $coordinate['x'][$time]; $x < $coordinate['x_'][$time]; $x++)
            {
                $rowSum += (int)$imageArr[$y][$x];
            }
            if ($rowSum > 1)
            {
                return $y;
            }
        }
    }


}