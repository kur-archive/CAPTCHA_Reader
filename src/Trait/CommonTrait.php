<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 3:02
 */

namespace CAPTCHAReader\src;


trait CommonTrait
{
    public function getConfig( $configType = 'app' ){
        switch ($configType) {
            case 'app':
                $configType = require_once( __DIR__ . '/../Config/' . $configType . '.php' );
                return $configType;
        }

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

}