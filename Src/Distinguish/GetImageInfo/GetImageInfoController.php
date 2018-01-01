<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/12
 * Time: 23:47
 */

namespace CAPTCHA_Reader\Distinguish\GetImageInfo;

abstract class GetImageInfoController
{
    use GetImageInfoTrait;

    private $config;
    private $imageInfo;

    /**
     * 这个类，在类初始化的时候，接受一个url
     * 如果存在这个$url或者$path，则优先去这个地址接受图片
     * 如果不存在，则前往config中写的目录获取图片
     * 那么也就是说有四种情况，
     * 1. 如果是传入的一个$url，则调用统一的online模式的下载图片的方法，下载该图片到config设置的位置，然后按照既定步骤返回图片的资源变量，并返回图片的位置作为全局变量
     * 2. 如果是传入一个$path,则用local模式下的获取图片的方法，然后按照既定步骤返回图片的资源变量，
     * 3. 如果没有传入变量，config设置为local，则从本地库随机获得图片，同样按照既定步骤
     * 4. 如果没有传入变量，config设置为online，则使用下载图片的方法获得图片，传出地址作为全局变量，增加获取暂存图片地址的方法
     *
     * 首先获取图片，将图片二值化之后获得目标的数组
     * 最后返回的是一个二维数组
     *
     * 对于这个类，目前之类中并不需要做什么，基本继承父类即可
     */

    /**
     * GetImageInfo constructor.
     * @param array $config
     * @param string $url
     */
    public function __construct( array $config )
    {
        $this->config = $config;
//        $this->mode            = $this->getImageMode( $this->config );
//        $this->localImgNumber  = $this->getLocalImgNumber( $this->config );
//        $this->savePath        = $this->getSavePath( $this->config );
//        $this->deleteImageFile = $this->getDeleteImageFile( $this->config );
    }

    /**
     * @return array
     */
    public function getResult( $path = null )
    {
        $imageAndInfo    = $this->fetchImageAndInfo( $path );
        $this->imageInfo = $imageAndInfo['info'];
        $image           = $imageAndInfo['image'];

        $imageBinaryArr = $this->binarization( $this->imageInfo['width'] , $this->imageInfo['height'] , $image );
        imagedestroy( $image );
        unset( $image );

        return [
            'imageInfo'      => $this->imageInfo ,
            'imageBinaryArr' => $imageBinaryArr ,
        ];
    }


    /**
     * @param array $config
     * @param $url
     * @return mixed
     */
    protected function fetchImageAndInfo( $path )
    {
        if (isset( $path ))
        {
            if (preg_match( '#^http://#i' , $path ))
            {
                $imageLocalPath = $this->downLoadOnlineImage( $path );
                $imageAndInfo   = $this->getImageAndInfo( $imageLocalPath );
                return $imageAndInfo;
            }
            elseif (is_file( $path ))
            {
                $imageAndInfo = $this->getImageAndInfo( $path );
                return $imageAndInfo;
            }
            else
            {
                throw new \Exception( 'path is not a file' );
            }
        }
        else
        {
            $mode = $this->getVerifyImageMode();
            if ($mode == 'local')
            {
                $path         = $this->getLocalImageDir() . rand( 0 , $this->getLocalImgNumber() - 1 ) . '.png';
                $imageAndInfo = $this->getImageAndInfo( $path );
                return $imageAndInfo;
            }
            elseif ($mode == 'online')
            {
                $imageLocalPath = $this->downLoadOnlineImage( $this->getOnlineUrl() );
                $imageAndInfo   = $this->getImageAndInfo( $imageLocalPath );
                return $imageAndInfo;
            }
            else
            {
                throw new \Exception( 'verifyImageMode is invalid' );
            }
        }
    }

    /**
     * @param $width
     * @param $height
     * @param $image
     * @return array
     * 二值化
     */
    public function binarization( $width , $height , $image )
    {
        $imageArr = [];
        for($y = 0; $y < $height; ++$y)
        {
            for($x = 0; $x < $width; ++$x)
            {
                $rgb      = imagecolorat( $image , $x , $y );
                $rgbArray = imagecolorsforindex( $image , $rgb );
                if ($rgbArray['red'] < 110 && $rgbArray['green'] < 110 && $rgbArray['blue'] > 100)
                {
                    $imageArr[$y][$x] = '1';
                }
                else
                {
                    $imageArr[$y][$x] = '0';
                }
            }
        }
        return $imageArr;
    }

    /**
     * @param $path
     * @return string
     */
    public function downLoadOnlineImage( $path )
    {
        $save_to = $this->getSavePath() . time() . 'png';
        $content = file_get_contents( $path );
        file_put_contents( $save_to , $content );
        return $save_to;
    }

    public function getImageAndInfo( $path )
    {
        $_info           = getimagesize( $path );
        $info            = array(
            'width'  => $_info[0] ,
            'height' => $_info[1] ,
            'type'   => image_type_to_extension( $_info[2] , false ) ,
            'mime'   => $_info['mime']
        );
        $this->imageInfo = $info;
        $fun             = "imagecreatefrom{$info['type']}";//根据上面获取的格式判定应该使用哪种'imagecreatefrom***'函数
        $image           = $fun( $path );
        return compact( 'image' , 'info' );
    }

}