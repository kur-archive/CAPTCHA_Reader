<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/16
 * Time: 1:49
 */

namespace CAPTCHA_Reader\GetImageInfo;

trait GetImageInfoTrait
{
    /**
     * @param $path
     */
    public function setImageInfoLocal( $path , $localImgNumber )
    {
        $_info           = getimagesize( $path ) . rand( 0 , $localImgNumber ) . '.png';
        $info            = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );
        $this->imageInfo = $info;
        $fun             = "imagecreatefrom{$info['type']}";
        $image           = $fun( $path );
        return $image;

    }

    /**
     * @param $path
     * @param $savePath
     */
    public function setImageInfoOnline( $path , $savePath )
    {
        $save_to = $savePath . time() . '.png';
        $content = file_get_contents( $path );
        file_put_contents( $save_to , $content );
        $_info = getimagesize( $content );
//        echo "<img style='height:150px;' src=". str_replace('D:\code\\','http://code.cc/',$save_to).">";
        $info            = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );
        $this->imageInfo = $info;
        $fun             = "imagecreatefrom{$info['type']}";//根据上面获取的格式判定应该使用哪种'imagecreatefrom***'函数
        $image           = $fun( $save_to );

        return $image;
    }


}