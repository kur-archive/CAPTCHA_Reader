<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 3:02
 */

namespace CAPTCHAReader\src\Traits;


trait CommonTrait
{
    public function getConfig( $configType = 'app' ){
        $configType = require_once(__DIR__ . '/../Config/' . $configType . '.php');
        return $configType;
    }

    /**
     * @param array ...$vars
     */
    public static function dd( ...$vars ){
        foreach($vars as $var){
            dump( $var );
        }
        exit();
    }

    /**
     * 调试用，show处理好的数组
     */
    public  function showResArr( $imageArr ){
        echo '  ';
        for($i = 0; $i < 72; ++$i){
            echo $i;
            if (strlen( $i ) == 1) {
                echo ' ';
            }
        }
        echo "\n";
        foreach($imageArr as$key=> $resY){
            echo $key;
            if (strlen( $key ) == 1) {
                echo ' ';
            }
            foreach($resY as $resX){
                $resX ? $output = 'l ' : $output = '_ ';
                echo $output;
            }
            echo "\n";

        }
        echo "\n";
    }


}