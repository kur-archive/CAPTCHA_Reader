<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:29
 */

namespace CAPTCHA_Reader;

use CAPTCHA_Reader\Cutting\Cutting;
use CAPTCHA_Reader\GetImageInfo\GetImageInfo;
use CAPTCHA_Reader\Identify\Identify;
use CAPTCHA_Reader\Pretreatment\Pretreatment;

class IndexController
{
    private $imageInfo;
    private $imageArr;
    private $charArr;
    private $result;

    public function __construct()
    {
        //完成各种函数调用
        $config = require_once(dirname( __DIR__ ) . '../Config/app.php');

        $getImageInfo    = new GetImageInfo( $config );
        $this->imageInfo = $getImageInfo->getImageInfo();
        $image           = $getImageInfo->getImage();
        //TODO 这里应该把二值化写到getImageInfo类里面，在析构函数里释放
        unset( $getImageInfo );

        $pretreatment   = new Pretreatment( $this->imageInfo , $image );
        $this->imageArr = $pretreatment->getResultArr();

        $this->showResArrWeb( $this->imageArr );

        $cutting       = new Cutting( $this->imageArr , $this->imageInfo , $config );
        $this->charArr = $cutting->getResultArr();
        $coordinate    = $cutting->getCoordinate();

        var_dump( $coordinate );
        $this->show( $this->charArr['char1'] , $this->charArr['char2'] , $this->charArr['char3'] , $this->charArr['char4'] );

        $identify     = new Identify( $this->charArr , $config );
        $this->result = $identify->getResult();
    }

    public function getResult()
    {
        return $this->result;
    }

    /**
     * web调试用，show处理好的数组
     */
    public function showResArrWeb( $imageArr )
    {
        echo "<div style='line-height: 10px;'>";
        echo "<br/>";
        foreach($imageArr as $resY)
        {
            foreach($resY as $key => $resX)
            {
                $resX ? $output = '◆' : $output = '◇';
                if (in_array( $key , [16 , 29 , 40 , 54] ))
                {
                    echo '<span style="color: red">' . $output . '</span>';
                }
                else
                {
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
    function show( $letter1 , $letter2 , $letter3 , $letter4 )
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


}