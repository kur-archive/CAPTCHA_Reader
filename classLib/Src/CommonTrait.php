<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/19
 * Time: 0:54
 */

namespace CAPTCHA_Reader\Tools;


trait CommonTrait
{
    /**
     * 调试用，show处理好的数组
     */
    public static function showResArr($imageArr)
    {

        echo "\n";
        foreach($imageArr as $resY)
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

    /**
     * web调试用，show处理好的数组
     */
    public static function showResArrWeb($imageArr)
    {
        echo "<div style='line-height: 10px;'>";
        echo "<br/>";
        foreach($imageArr as $resY)
        {
            foreach($resY as $key=>$resX)
            {
                $resX ? $output = '◆' : $output = '◇';
                if (in_array( $key , [16 , 29 , 40 , 54] ))
                {
                    echo '<span style="color: red">'.$output.'</span>';
                }else{
                    echo $output;
                }

            }
            echo "<br/>";

        }
        echo "<br/>";
        echo "</div>";
    }

    /**
     * @param $letter1
     * @param $letter2
     * @param $letter3
     * @param $letter4
     * 展示切割后的结果和二值化后的数组
     */
    public static function show( $letter1 , $letter2 , $letter3 , $letter4 )
    {

        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';
        for($y = 0; isset( $letter1[$y][0] ); ++$y)
        {
            for($x = 0; isset( $letter1[$y][$x] ); ++$x)
            {
                if ($letter1[$y][$x] == 1)
                {
                    echo '◆';
                }
                else
                {
                    echo '◇';
                }
            }
            echo '<br/>';
        }
        echo '</div>';

        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';
        echo '<br/>';
        for($y = 0; isset( $letter2[$y][0] ); ++$y)
        {
            for($x = 0; isset( $letter2[$y][$x] ); ++$x)
            {
                if ($letter2[$y][$x] == 1)
                {
                    echo '◆';
                }
                else
                {
                    echo '◇';
                }
            }
            echo '<br/>';
        }
        echo '</div>';

        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';
        echo '<br/>';
        for($y = 0; isset( $letter3[$y][0] ); ++$y)
        {
            for($x = 0; isset( $letter3[$y][$x] ); ++$x)
            {
                if ($letter3[$y][$x] == 1)
                {
                    echo '◆';
                }
                else
                {
                    echo '◇';
                }
            }
            echo '<br/>';
        }
        echo '</div>';

        echo '<div style="float: left;line-height: 10px;margin-left: 20px;">';
        echo '<br/>';
        for($y = 0; isset( $letter4[$y][0] ); ++$y)
        {
            for($x = 0; isset( $letter4[$y][$x] ); ++$x)
            {
                if ($letter4[$y][$x] == 1)
                {
                    echo '◆';
                }
                else
                {
                    echo '◇';
                }
            }
            echo '<br/>';
        }
        echo '</div>';
        echo '<br/>';
    }

    public static function twoDimArrayToStr( $array )
    {
        $str = '';
        foreach($array as $key => $value)
        {
            foreach($value as  $value_)
            {
                $str .= $value_;
            }
        }
        return $str;
    }
}