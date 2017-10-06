<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/09/28
 * Time: 17:29
 */

class Cutting
{
    protected $end_x0 = 0;
    protected $start_x1;
    protected $end_x1;
    protected $start_x2;
    protected $end_x2;
    protected $start_x3;
    protected $end_x3;
    protected $start_x4;
    protected $end_x4;

    protected $end_y0 = 0;
    protected $start_y1;
    protected $end_y1;
    protected $start_y2;
    protected $end_y2;
    protected $start_y3;
    protected $end_y3;
    protected $start_y4;
    protected $end_y4;

    protected $resArr;

    protected $char1 = [];
    protected $char2 = [];
    protected $char3 = [];
    protected $char4 = [];


    const THRESHOLD_LONG = 9;
    const THRESHOLD_TOO_LONG = 5;
    const THRESHOLD_SHORT = 2;
    const THRESHOLD_COLUMNS_PIXELS = 1;

    const CAPTCHA_STRING_NUM = 4;

    public function __construct( array $imageInfo , $resArr )
    {
        $this->resArr = $resArr;

        for($i = 1; $i <= self::CAPTCHA_STRING_NUM; $i++)
        {
            $this->getCutBeforeColumns( $imageInfo['width'] , $imageInfo['height'] , $i );
            $this->getCutAfterColumns( $imageInfo['width'] , $imageInfo['height'] , $i );
        }


        for($i = 1; $i <= self::CAPTCHA_STRING_NUM; $i++)
        {
            $this->getCutBeforeRow( $imageInfo['height'] , $i );
            $this->getCutAfterRow( $imageInfo['height'] , $i );
        }

        $this->CutArr();
    }


    public function getCutBeforeColumns( $width , $height , $time )
    {
        $tmpT = 'start_x' . $time;

        $tmpT_1 = 'end_x' . ($time - 1);
        $tmpX   = $this->$tmpT_1;

        for($x = $tmpX + 1; $x < $width; $x++)
        {
            $columnsSum = 0;
            for($y = 0; $y < $height; $y++)
            {
                $columnsSum += $this->resArr[$y][$x];
            }

            if ($columnsSum > self::THRESHOLD_COLUMNS_PIXELS)
            {
                $this->$tmpT = $x;
                return;
            }
        }
    }


    public function getCutAfterColumns( $width , $height , $time )
    {
        $tmpT = 'end_x' . $time;

        $tmpT_1 = 'start_x' . $time;
        $tmpX   = $this->$tmpT_1;

        for($x = $tmpX; $x < $width; $x++)
        {

            $columnsSum = 0;
            for($y = 0; $y < $height; $y++)
            {
                $columnsSum += (int)$this->resArr[$y][$x];
            }

            //当已经出现空行，且截取的宽度>2
            if ($columnsSum == 0 && $x - $tmpX > self::THRESHOLD_SHORT)
            {

                $this->$tmpT = $x;
                return;
            }

            //当在接近阈值和超出阈值时，若单列像素小于或等于1，则可以截取
            if ($columnsSum <= 1 && $x - $tmpX > 9)
            {
                $this->$tmpT = $x;
                return;
            }

            //当单个字母宽度已经超出阈值，进行预估判断，查看之后是否有空行可以截断
            if ($x - $tmpX > self::THRESHOLD_LONG)
            {
                //向后看，如果无法找到可以停止的参照，则在阈值处截断
                if (!$this->estimate( $x , $width , $height ))
                {
                    $this->$tmpT = $x;
                    return;
                }
                elseif ($x - $tmpX > self::THRESHOLD_LONG + self::THRESHOLD_TOO_LONG)
                {
                    $this->$tmpT = $x;
                    return;
                }
            }

        }

    }


    public function getCutBeforeRow( $height , $time )
    {
        $tmpTsx = 'start_x' . $time;
        $tmpTex = 'end_x' . $time;
        $tmpTsy = 'start_y' . $time;


        for($y = 0; $y < $height; $y++)
        {
            $rowSum = 0;
            for($x = $this->$tmpTsx; $x < $this->$tmpTex; $x++)
            {
                $rowSum += (int)$this->resArr[$y][$x];
            }

            if ($rowSum > 0)
            {
                $this->$tmpTsy = $y;
                return;
            }
        }
    }


    public function getCutAfterRow( $height , $time )
    {
        $tmpTsx = 'start_x' . $time;
        $tmpTex = 'end_x' . $time;
        $tmpTey = 'end_y' . $time;

        $tmpTey_1 = 'start_y' . $time;
        $tmpY     = $this->$tmpTey_1;

        for($y = $tmpY + 1; $y < $height; $y++)
        {
            $rowSum = 0;
            for($x = $this->$tmpTsx; $x < $this->$tmpTex; $x++)
            {
                $rowSum += (int)$this->resArr[$y][$x];
            }

            //预防i和j的情况只切了个脑袋下来
            if ($rowSum == 0)
            {
                $_rowSum = 0;
                for($q = $y + 1; $q < $y + 3; $q++)
                {
                    for($p = $this->$tmpTsx; $p < $this->$tmpTex; $p++)
                    {
                        $_rowSum += (int)$this->resArr[$q][$p];
                    }
                }
                if ($_rowSum == 0)
                {
                    $this->$tmpTey = $y;
                    return;
                }
            }
        }

    }

    //向后看几位，如果可以找到空的列，则表示推荐继续向后截取
    protected function estimate( $nowX , $width , $height )
    {
        for($x = $nowX + 1;
            $x < ($nowX + self::THRESHOLD_TOO_LONG >= $width)
                ? $width
                : $nowX + self::THRESHOLD_TOO_LONG; $x++)
        {
            $columnsSum = 0;
            for($y = 0; $y < $height; $y++)
            {
                $columnsSum += (int)$this->resArr[$y][$x];
            }

            if ($columnsSum <= 1)
            {
                return true;
            }
        }
        return false;
    }

    public function CutArr()
    {
        for($y = $this->start_y1 , $_y = 0; $y < $this->end_y1; $y++ , $_y++)
        {
            for($x = $this->start_x1 , $_x = 0; $x < $this->end_x1; $x++ , $_x++)
            {
                $this->char1[$_y][$_x] = $this->resArr[$y][$x];
            }
        }

        for($y = $this->start_y2 , $_y = 0; $y < $this->end_y2; $y++ , $_y++)
        {
            for($x = $this->start_x2 , $_x = 0; $x < $this->end_x2; $x++ , $_x++)
            {
                $this->char2[$_y][$_x] = $this->resArr[$y][$x];
            }
        }

        for($y = $this->start_y3 , $_y = 0; $y < $this->end_y3; $y++ , $_y++)
        {
            for($x = $this->start_x3 , $_x = 0; $x < $this->end_x3; $x++ , $_x++)
            {
                $this->char3[$_y][$_x] = $this->resArr[$y][$x];
            }
        }

        for($y = $this->start_y4 , $_y = 0; $y < $this->end_y4; $y++ , $_y++)
        {
            for($x = $this->start_x4 , $_x = 0; $x < $this->end_x4; $x++ , $_x++)
            {
                $this->char4[$_y][$_x] = $this->resArr[$y][$x];
            }
        }
    }

    public function getAfterCutArr( $num = 0 )
    {
        switch ($num)
        {
            case 0:
                return array_merge(
                    ["char1"=> $this->char1] ,
                    ["char2"=> $this->char2] ,
                    ["char3"=> $this->char3] ,
                    ["char4"=> $this->char4]
                );
                break;

            case 1:
                return $this->char1;
                break;
            case 2:
                return $this->char2;
                break;
            case 3:
                return $this->char3;
                break;
            case 4:
                return $this->char4;
                break;
        }
    }


    public function getCoordinateArr()
    {
        $arr = [];
        for($i = 1; $i <= 4; $i++)
        {
            $a       = 'start_x' . $i;
            $arr[$a] = $this->$a;

            $b       = 'end_x' . $i;
            $arr[$b] = $this->$b;
        }

        for($i = 1; $i <= 4; $i++)
        {
            $c       = 'start_y' . $i;
            $arr[$c] = $this->$c;

            $d       = 'end_y' . $i;
            $arr[$d] = $this->$d;
        }
        return $arr;
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
//                $resX ? $output = 'O' : $output = 'x';
                $resX ? $output = '◆' : $output = '◇';
                echo $output;
            }
            echo "\n";
            echo "<br/>";
        }
        echo "\n";
        echo "<br/>";
    }

    public function showAfterCutArr()
    {
        for($i = 1; $i <= 4; $i++)
        {
            $target = 'char' . $i;
            foreach($this->$target as $resY)
            {
                foreach($resY as $resX)
                {
                    $resX ? $output = 'O' : $output = 'x';
//                    $resX ? $output = '◆' : $output = '◇';
                    echo $output;
                }
                echo "\n";
                echo "<br/>";
            }
            echo "\n";
            echo "<br/>";
        }
        echo "\n";
        echo "<br/>";
    }
}