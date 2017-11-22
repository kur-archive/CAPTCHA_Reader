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
    private $config;
    private $getImageProvider;
    private $pretreatmentProvider;
    private $cuttingProvider;
    private $identifyProvider;

    //不应该在这里传path，应该在下面getResult方法中传path
    public function __construct( $path = '' )
    {
        //TODO wait 建立各个服务提供者的父类
        //完成各种类的初始化
        $this->config = require_once(dirname( __DIR__ ) . '../Config/app.php');

        $this->getImageProvider     = new GetImageInfo( $this->config , $path );
        $this->pretreatmentProvider = new Pretreatment();
        $this->cuttingProvider      = new Cutting( $this->config );
        $this->identifyProvider     = new Identify( $this->config );
    }

    /**
     * @return string
     */
    //TODO 在这里传path
    public function getResult($path)
    : string
    {
        $imageArr       = $this->getImageProvider->getResult($path);
        $imageInfo      = $imageArr['imageInfo'];
        $imageBinaryArr = $imageArr['imageBinaryArr'];
        $noiseCancelArr = $this->pretreatmentProvider->getResultArr( $this->config , $imageInfo , $imageBinaryArr );
        $charArr        = $this->cuttingProvider->getResultArr( $noiseCancelArr , $imageInfo );
        $result         = $this->identifyProvider->getResult( $charArr );

        return $result;
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