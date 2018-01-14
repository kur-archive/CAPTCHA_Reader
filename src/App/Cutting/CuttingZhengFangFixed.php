<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:03
 */

namespace CAPTCHAReader\src\App\Cutting;


use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\App\ResultContainer;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangFixedRepository;
use CAPTCHAReader\src\Traits\CuttingTrait;

class CuttingZhengFangFixed extends Load
{
    use CuttingTrait;

    private $conf;
    private $resultContainer;
    private $cuttingRepository;
    private $imageInfo;
    private $noiseCancelArr;

    public function __construct( Restriction $nextStep ){
        parent::__construct( $nextStep );
        $this->cuttingRepository = $this->getRepository( 'ZhengFangFixed' );
    }


    function run( ResultContainer $resultContainer ){
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();
        $this->noiseCancelArr  = $this->resultContainer->getNoiseCancelArr();

        $width  = $this->imageInfo['width'];
        $height = $this->imageInfo['height'];

        //获取坐标
        $xArr = $this->cuttingRepository->getXCoordinate( $width , $height , $this->noiseCancelArr );
        $yArr = $this->cuttingRepository->getYCoordinate( $xArr , $height , $this->noiseCancelArr );

        //切割
        $pixelCollection = $this->cuttingRepository->cut( $this->noiseCancelArr , compact( 'xArr' , 'yArr' ) );

        //放入容器
        $this->resultContainer->setCoordinate( compact( $xArr , $yArr ) );
        $this->resultContainer->setPixelCollection( $pixelCollection );
        //----------------------------------------------------------------------------
        $this->resultContainer = $this->nextStep->run( $this->resultContainer );
        //----------------------------------------------------------------------------

        return $this->resultContainer;

    }


}