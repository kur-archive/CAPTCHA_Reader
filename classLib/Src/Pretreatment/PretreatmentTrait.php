<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:26
 */

namespace CAPTCHA_Reader\Pretreatment;

trait PretreatmentTrait
{

    /**
     * 调试用，show处理好的数组
     */
    public function showResArr($imageArr)
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
    public function showResArrWeb($imageArr)
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

}