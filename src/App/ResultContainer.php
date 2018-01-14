<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:04
 */

namespace CAPTCHAReader\src\App;


class ResultContainer
{
    private $imagePath;
    private $mode;
    private $conf;

    private $imageInfo;
    private $image;


    /**
     * @return mixed
     */
    public function getConf(){
        return $this->conf;
    }

    /**
     * @param mixed $config
     */
    public function setConf( $conf ){
        $this->conf = $conf;
    }

    /**
     * @return mixed
     */
    public function getImagePath(){
        return $this->imagePath;
    }

    /**
     * @param mixed $imagePath
     */
    public function setImagePath( $imagePath ){
        $this->imagePath = $imagePath;
    }

    /**
     * @return mixed
     */
    public function getMode(){
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode( $mode ){
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getImageInfo(){
        return $this->imageInfo;
    }

    /**
     * @param mixed $imageInfo
     */
    public function setImageInfo( $imageInfo ){
        $this->imageInfo = $imageInfo;
    }

    /**
     * @return mixed
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage( $image ){
        $this->image = $image;
    }

    public function unsetImage(){
        imagedestroy( $this->image );
        unset( $this->image );
    }


}