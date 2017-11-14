<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/04
 * Time: 3:51
 */

namespace CAPTCHA_Reader\Tools;

use CAPTCHA_Reader\Cutting\Cutting;
use CAPTCHA_Reader\GetImageInfo\GetImageInfo;
use CAPTCHA_Reader\Identify\Identify;
use CAPTCHA_Reader\Identify\IdentifyTrait;
use CAPTCHA_Reader\Pretreatment\Pretreatment;

class AddSamplesRepository
{
    use IdentifyTrait;
    private $imageInfo;
    private $imageArr;
    private $charArr;
    private $result;

    public function __construct( $mode )
    {
        if ($mode == 'load')
        {
            echo "<head><style>.charInput{height:50px;width: 50px;border-radius: 5px;border: 1px solid #333;text-align: center;font-size: 200%} .addSamples{height:50px;width: 100px;background: #333;border: none;color: #fff;border-radius: 5px;}</style></head>";

            $config = require_once(dirname( __DIR__ ) . '../Config/app.php');

            $getImageInfo    = new GetImageInfo( $config );
            $this->imageInfo = $getImageInfo->getImageInfo();
            $image           = $getImageInfo->getImage();
            unset( $getImageInfo );

            $pretreatment   = new Pretreatment( $this->imageInfo , $image );
            $this->imageArr = $pretreatment->getResultArr();

//            $this->showResArrWeb( $this->imageArr );

            $cutting       = new Cutting( $this->imageArr , $this->imageInfo , $config );
            $this->charArr = $cutting->getResultArr();
            $coordinate    = $cutting->getCoordinate();
//            var_dump( $coordinate );
            $this->show( $this->charArr['char1'] , $this->charArr['char2'] , $this->charArr['char3'] , $this->charArr['char4'] );

            $identify     = new Identify( $this->charArr , $config , dirname( __DIR__ ) . '../Src/Identify/dictionary/zhengFang_fixedCut.json' );
            $this->result = $identify->getResult();
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";

            var_dump( $this->result );
        }
    }

    public function index()
    {
        $charStrArr = [
            'char1' => $this->twoDimArrayToStr( $this->charArr['char1'] ) ,
            'char2' => $this->twoDimArrayToStr( $this->charArr['char2'] ) ,
            'char3' => $this->twoDimArrayToStr( $this->charArr['char3'] ) ,
            'char4' => $this->twoDimArrayToStr( $this->charArr['char4'] ) ,
        ];

        $content = "
            <form action='#' method='post' '>
                <input type='text' class='charInput' name='char1'/>
                <input type='text' class='charInput' name='char2'/>
                <input type='text' class='charInput' name='char3'/>
                <input type='text' class='charInput' name='char4'/>
                <input type='submit' class='addSamples' value='addSamples' name='submit'>
                <input type='text' class='charInput' name='char1str' value='{$charStrArr['char1']}' hidden/>
                <input type='text' class='charInput' name='char2str' value='{$charStrArr['char2']}' hidden/>
                <input type='text' class='charInput' name='char3str' value='{$charStrArr['char3']}' hidden/>
                <input type='text' class='charInput' name='char4str' value='{$charStrArr['char4']}' hidden/>
            </form>";
        return $content;
    }

    public function store( $arr )
    {
//        var_dump( $arr );
//        exit();
        $result = [];
        $dictionary = json_decode( file_get_contents( dirname( __DIR__ ) . '../Src/Identify/dictionary/zhengFang_fixedCut.json' ) );
        for($i = 1; $i <= 4; $i++)
        {
            if (empty( $arr['char' . $i] ))
            {
                continue;
            }
            $dictionary[] = [
                'char' => $arr['char' . $i] ,
                'str'  => $arr['char' . $i . 'str'] ,
            ];
            $result[] = [
                'char' => $arr['char' . $i] ,
                'str'  => $arr['char' . $i . 'str'] ,
            ];
        }
        file_put_contents( dirname( __DIR__ ) . '../Src/Identify/dictionary/zhengFang_fixedCut.json' , json_encode( $dictionary ) );
        header( 'Location: http://code.cc/DEMO/CAPTCHA_Idenify/new/d2/addSamples?result=' . json_encode($result ));
    }

    /**
     * web调试用，show处理好的数组
     */
    function showResArrWeb( $imageArr )
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