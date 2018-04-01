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
        $this->imageInfo       = $this->resultContainer->getImageInfo();
        $this->noiseCancelArr  = $this->resultContainer->getNoiseCancelArr();

//        $this->showResArr( $this->noiseCancelArr );

        $width  = $this->imageInfo['width'];
        $height = $this->imageInfo['height'];

        //获取坐标
        $xAllArr = $this->cuttingRepository->getXCoordinate( $width , $height , $this->noiseCancelArr );
        $yAllArr = $this->cuttingRepository->getYCoordinate( $xAllArr , $height , $this->noiseCancelArr );

        //        dump( $xAllArr , $yAllArr );

        //切割
        $pixelCollection = $this->cuttingRepository->cut( $this->noiseCancelArr , compact( 'xAllArr' , 'yAllArr' )   );
        $charPixedCollection = [];
        foreach($pixelCollection as $charPixel){
            $charPixedCollection[] = $charPixel['pixel'];
        }

//        $this->showChar( $charPixedCollection);

        //放入容器
        $this->resultContainer->setCoordinate( compact( 'xAllArr' , 'yAllArr' ) );
        $this->resultContainer->setCharPixedCollection( $charPixedCollection );
        //----------------------------------------------------------------------------
        $this->resultContainer = $this->nextStep->run( $this->resultContainer );
        //----------------------------------------------------------------------------

        return $this->resultContainer;

    }


}