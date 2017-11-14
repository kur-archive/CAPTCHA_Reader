<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:24
 */

namespace CAPTCHA_Reader\Cutting;

class Cutting implements CuttingInterface
{
    use CuttingTrait;

    protected $charArr = [
        'char1' => [] ,
        'char2' => [] ,
        'char3' => [] ,
        'char4' => [] ,
    ];
    protected $coordinate = [
        'x1' => '' , 'x1_' => '' ,
        'x2' => '' , 'x2_' => '' ,
        'x3' => '' , 'x3_' => '' ,
        'x4' => '' , 'x4_' => '' ,

        'y1' => '' , 'y1_' => '' ,
        'y2' => '' , 'y2_' => '' ,
        'y3' => '' , 'y3_' => '' ,
        'y4' => '' , 'y4_' => '' ,
    ];

    protected $captchaStringNum ;

    public function __construct( array $imageArr , array $imageInfo,array $config)
    {
        $this->captchaStringNum = $config['captchaStringNum'];
        for($i = 1; $i <= $this->captchaStringNum; $i++)
        {
            $a                    = 'x' . $i;
            $b                    = 'x' . $i . '_';
            $this->coordinate[$a] = $this->getCutBeforeColumns( $imageArr , $imageInfo['width'] , $imageInfo['height'] , $i );
            $this->coordinate[$b] = $this->getCutAfterColumns( $imageArr , $imageInfo['width'] , $imageInfo['height'] , $i );
        }

        for($i = 1; $i <= $this->captchaStringNum; $i++)
        {
            $a                    = 'y' . $i;
            $b                    = 'y' . $i . '_';
            $this->coordinate[$a] = $this->getCutBeforeRow( $imageArr , $this->coordinate , $imageInfo['height'] , $i );
            $this->coordinate[$b] = $this->getCutAfterRow( $imageArr , $this->coordinate , $imageInfo['height'] , $i );
        }
        $this->charArr = $this->Cut( $imageArr , $this->charArr , $this->coordinate , $this->captchaStringNum );

    }

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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
        }
    }

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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
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
                        $sum += $imageArr[$y][$x];
                    }
                    if ($sum > 1)
                    {
                        return $x;
                    }
                }
                break;
        }
    }

    public function getCutBeforeRow( $imageArr , $coordinate , $height , $time )
    {
        $_x  = 'x' . $time;
        $_x_ = 'x' . $time . '_';
        for($y = 0; $y < $height; $y++)
        {
            $rowSum = 0;
            for($x = $coordinate[$_x]; $x < $coordinate[$_x_]; $x++)
            {
                $rowSum += (int)$imageArr[$y][$x];
            }
            if ($rowSum > 1)
            {
                return $y;
            }
        }

    }

    public function getCutAfterRow( $imageArr , $coordinate , $height , $time )
    {
        $_x  = 'x' . $time;
        $_x_ = 'x' . $time . '_';
        for($y = $height - 1; $y >= 0; $y--)
        {
            $rowSum = 0;
            for($x = $coordinate[$_x]; $x < $coordinate[$_x_]; $x++)
            {
                $rowSum += (int)$imageArr[$y][$x];
            }
            if ($rowSum > 1)
            {
                return $y;
            }
        }
    }


    public function getCoordinate()
    {
        return $this->coordinate;
    }


    public function getResultArr()
    {
        return $this->charArr;
    }


}