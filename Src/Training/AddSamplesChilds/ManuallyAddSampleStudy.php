<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/10
 * Time: 20:43
 */

namespace CAPTCHA_Reader\Training\AddSamples;


class ManuallyAddSampleStudy
{
    public function __construct( $mode )
    {
        if ($mode == 'load')
        {
            echo "<head><style>.charInput{height:50px;width: 50px;border-radius: 5px;border: 1px solid #333;text-align: center;font-size: 200%} .addSamples{height:50px;width: 100px;background: #333;border: none;color: #fff;border-radius: 5px;}</style></head>";

            $this->config = require_once(dirname( __DIR__ ) . '../Config/app.php');

//            $getImageProvider     = new GetImageInfoController( $this->config );
//            $pretreatmentProvider = new PretreatmentController();
//            $cuttingProvider      = new CuttingController( $this->config );
//            $identifyProvider     = new IdentifyController( $this->config );
//
//            $imageArr       = $getImageProvider->getResult();
//            $imageInfo      = $imageArr['imageInfo'];
//            $imageBinaryArr = $imageArr['imageBinaryArr'];
//            $noiseCancelArr = $pretreatmentProvider->getResultArr( $this->config , $imageInfo , $imageBinaryArr );
//            $charArr        = $cuttingProvider->getResultArr( $noiseCancelArr , $imageInfo );
//            $this->charArr  = $charArr;
//            CommonTrait::show(
//                $charArr['char1'] ,
//                $charArr['char2'] ,
//                $charArr['char3'] ,
//                $charArr['char4'] );
//
//            $result = $identifyProvider->getResult( $charArr , true );

            var_dump( $_GET['length'] ?? '' );

            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";

//            var_dump( $result );
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
        $content    = "
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
        $result     = [];
        $dictionary = json_decode( file_get_contents( dirname( __DIR__ ) . '../Src/Identify/dictionary/zhengFang_fixedCut.json' ) );
        for($i = 1; $i <= 4; $i++)
        {
            if ($arr['char' . $i] == '')
            {
                continue;
            }
            $dictionary[] = [
                'char' => $arr['char' . $i] ,
                'str'  => $arr['char' . $i . 'str'] ,
            ];
            $result[]     = [
                'char' => $arr['char' . $i] ,
                'str'  => $arr['char' . $i . 'str'] ,
            ];
        }
        file_put_contents( dirname( __DIR__ ) . '../Src/Identify/dictionary/zhengFang_fixedCut.json' , json_encode( $dictionary ) );
        header( 'Location: http://code.cc/DEMO/CAPTCHA_Idenify/new/d2/addSamples?result=' . json_encode( $result ) . '&length=' . count( $dictionary ) );
    }


}