<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:07
 */

namespace CAPTCHAReader\src\App\GetImageInfo;

use CAPTCHAReader\src\Traits\GetImageInfoTrait;
use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\ResultContainer;


class GetImageInfo extends Load
{
    use GetImageInfoTrait;

    private $conf;
    private $resultContainer;

    public function run( ResultContainer $resultContainer ){
        //初始化
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();
        $mode                  = $this->resultContainer->getMode();
        $imagePath             = $this->resultContainer->getImagePath();

        //获取 图片和图片信息
        if ($mode == 'online') {
            $imagePath = $this->downLoadOnlineImage( $imagePath );
        }
        $imageAndInfo = $this->getImageAndInfo( $imagePath );

        //将结果存入容器
        $this->resultContainer->setImageInfo( $imageAndInfo['info'] );
        $this->resultContainer->setImage( $imageAndInfo['image'] );

        //--------------------------------------------
        $this->resultContainer = $this->nextStep->run( $this->resultContainer );
        //--------------------------------------------
        return $this->resultContainer;
    }

}