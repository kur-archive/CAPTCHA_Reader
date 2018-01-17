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

    public $imageBinaryArr;
    public $noiseCancelArr;

    private $coordinate;
    private $charPixedCollection;

    private $oneDCharStrArr;
    private $judgeDetails;

    private $resultArr;
    private $resultStr;


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

    /**
     * @return mixed
     */
    public function getImageBinaryArr(){
        return $this->imageBinaryArr;
    }

    /**
     * @param mixed $imageBinaryArr
     */
    public function setImageBinaryArr( $imageBinaryArr ){
        $this->imageBinaryArr = $imageBinaryArr;
    }

    /**
     * @return mixed
     */
    public function getNoiseCancelArr(){
        return $this->noiseCancelArr;
    }

    /**
     * @param mixed $noiseCancelArr
     */
    public function setNoiseCancelArr( $noiseCancelArr ){
        $this->noiseCancelArr = $noiseCancelArr;
    }

    /**
     * @return mixed
     */
    public function getCoordinate(){
        return $this->coordinate;
    }

    /**
     * @param mixed $coordinate
     */
    public function setCoordinate( $coordinate ){
        $this->coordinate = $coordinate;
    }

    /**
     * @return mixed
     */
    public function getCharPixedCollection(){
        return $this->charPixedCollection;
    }

    /**
     * @param mixed $charPixedCollection
     */
    public function setCharPixedCollection( $charPixedCollection ){
        $this->charPixedCollection = $charPixedCollection;
    }

    /**
     * @return mixed
     */
    public function getOneDCharStrArr(){
        return $this->oneDCharStrArr;
    }

    /**
     * @param mixed $oneDCharStrArr
     */
    public function setOneDCharStrArr( $oneDCharStrArr ){
        $this->oneDCharStrArr = $oneDCharStrArr;
    }

    /**
     * @return mixed
     */
    public function getJudgeDetails(){
        return $this->judgeDetails;
    }

    /**
     * @param mixed $judgeDetails
     */
    public function setJudgeDetails( $key,$judgeDetails ){
        $this->judgeDetails[$key] = $judgeDetails;
    }

    /**
     * @return mixed
     */
    public function getResultArr(){
        return $this->resultArr;
    }

    /**
     * @param mixed $resultArr
     */
    public function setResultArr( $resultStrArr ){
        $this->resultArr[] = $resultStrArr;
    }


    /**
     * @return mixed
     */
    public function getResultStr(){
        return $this->resultStr;
    }

    /**
     * @param mixed $resultStr
     */
    public function setResultStr( $resultStr ){
        $this->resultStr = $resultStr;
    }

}