<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 19:52
 */

namespace CAPTCHAReader\src\Traits;


trait PretreatmentTrait
{
    /**
     * @param $imagePath
     * @return array
     */
    public function getImageAndInfo($imagePath){
        $_info = getimagesize( $imagePath );
        $info  = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );

        //根据上面获取的格式判定应该使用哪种 imagecreatefrom*** 函数
        $fun   = "imagecreatefrom{$info['type']}";
        $image = $fun( $imagePath );
        return compact( 'image' , 'info' );
    }

}