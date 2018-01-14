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
        $save_to = $this->getTmpSampleSavePath() . str_replace( [' ' , '.'] , '' , microtime() ) . 'png';
        $content = file_get_contents( $path );
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


}