<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:13
 */

namespace CAPTCHAReader\src\App\Pretreatment;

use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\App\ResultContainer;
use CAPTCHAReader\src\Traits\PretreatmentTrait;

class PretreatmentTianYi extends Load
{
    use PretreatmentTrait;

    private $conf;
    private $resultContainer;
    private $pretreatmentRepository;

    /**
     * PretreatmentZhengFang constructor.
     * @param Restriction $nextStep
     */
    public function __construct(Restriction $nextStep)
    {
        parent::__construct($nextStep);
        $this->pretreatmentRepository = $this->getRepository('TianYi');
    }

    /**
     * @param ResultContainer $resultContainer
     * @return mixed
     */
    function run(ResultContainer $resultContainer)
    {
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();

        $imageInfo = $this->resultContainer->getImageInfo();
        $image     = $this->resultContainer->getImage();

        //二值化
        $imageBinaryArr = $this->pretreatmentRepository->binarization($imageInfo['width'], $imageInfo['height'], $image);
        $noiseCancelArr = $imageBinaryArr;

//        $this->showResArr($imageBinaryArr);
//        self::dd(1);

//        $noiseCancelArr = $this->pretreatmentRepository->erosion($noiseCancelArr, $imageInfo['width'], $imageInfo['height'],5);
        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height'], 3);
        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height'], 1);
        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height'], 3);
//        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height'], 1);
//        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height'], 3);
        //去掉散点
        $noiseCancelArr = $this->pretreatmentRepository->SimpleNoiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);
//        $noiseCancelArr = $this->pretreatmentRepository->SimpleNoiseCancel( $imageInfo['width'] , $imageInfo['height'] , $noiseCancelArr );
//        $noiseCancelArr = $this->pretreatmentRepository->SimpleNoiseCancel( $imageInfo['width'] , $imageInfo['height'] , $noiseCancelArr );
//        $noiseCancelArr = $this->pretreatmentRepository->SimpleNoiseCancel( $imageInfo['width'] , $imageInfo['height'] , $noiseCancelArr );
//        $noiseCancelArr = $this->pretreatmentRepository->SimpleNoiseCancel( $imageInfo['width'] , $imageInfo['height'] , $noiseCancelArr );
//        $noiseCancelArr = $this->pretreatmentRepository->shrink($noiseCancelArr);
//
//        $this->showResArr($noiseCancelArr);
//        self::dd(1);


        $this->resultContainer->unsetImage();
        $this->resultContainer->setImageBinaryArr($imageBinaryArr);
        $this->resultContainer->setNoiseCancelArr($noiseCancelArr);
        //------------------------------------------------------------------
        $this->resultContainer = $this->nextStep->run($this->resultContainer);
        //------------------------------------------------------------------
        return $this->resultContainer;
    }

}