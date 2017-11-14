<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/12
 * Time: 23:47
 */

namespace CAPTCHA_Reader\GetImageInfo;

class GetImageInfo implements GetImageInfoInterFace
{

    private $mode;
    private $path;
    private $imageInfo;
    private $image;

    /**
     * GetImageInfo constructor.
     * @param array $config
     * @param string $url
     */
    public function __construct( array $config , $url = '' )
    {
        $this->mode = $this->getImageMode( $config );

        $this->mode == 'local'
            ? $this->path = $config['getImagePath']['local']['dir'] . rand( 0 , $config['getImagePath']['local']['number'] ) . '.png'
            : $this->path = $this->getUrl( $config , $url );

        $this->mode == 'local'
            ? $this->setImageInfoLocal( $this->path )
            : $this->setImageInfoOnline( $this->path , $config['getImagePath']['online']['save_path'] );

    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getImageMode( array $config )
    {
        return $config['getImageMode'];
    }

    /**
     * @param $config
     * @param $url
     * @return mixed
     */
    protected function getUrl( $config , $url )
    {
        if (empty( $url ))
        {
            return $config['getImagePath']['online']['url'];
        }
        else
        {
            return $url;
        }
    }

    /**
     * @param $path
     */
    protected function setImageInfoLocal( $path )
    {
        $_info           = getimagesize( $path );
        $info            = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );
        $this->imageInfo = $info;
        $fun             = "imagecreatefrom{$info['type']}";
        $this->image     = $fun( $path );
    }

    /**
     * @param $path
     * @param $savePath
     */
    protected function setImageInfoOnline( $path , $savePath )
    {
        $save_to = $savePath . time() . '.png';
        $content = file_get_contents( $path );
        file_put_contents( $save_to , $content );
        $_info = getimagesize( $save_to );
        echo "<img style='height:150px;' src=". str_replace('D:\code\\','http://code.cc/',$save_to).">";
        $info            = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );
        $this->imageInfo = $info;
        $fun             = "imagecreatefrom{$info['type']}";//根据上面获取的格式判定应该使用哪种'imagecreatefrom***'函数
        $this->image     = $fun( $save_to );
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getImageInfo()
    {
        return $this->imageInfo;
    }

    public function __destruct()
    {
        unset( $this->image );
    }
}