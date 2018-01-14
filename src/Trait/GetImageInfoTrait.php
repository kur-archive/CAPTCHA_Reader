<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 19:16
 */

namespace CAPTCHAReader\src\Traits;


trait  GetImageInfoTrait
{
    use CommonTrait;

    /**
     * @param $path
     * @return string
     */
    public function downLoadOnlineImage( $path ){
        $save_to = $this->getTmpSampleSavePath() . str_replace( [' ' , '.'] , '' , microtime() ).'.' ;
        $content = file_get_contents( $path );
        $save_to .= image_type_to_extension( getimagesizefromstring( $content )[2] , false );
        file_put_contents( $save_to , $content );
        return $save_to;
    }

    /**
     * @return mixed
     */
    public function getTmpSampleSavePath(){
        $sampleConfig = $this->getConfig( 'sample' );
        return $sampleConfig['tmpSampleSavePath'];
    }

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