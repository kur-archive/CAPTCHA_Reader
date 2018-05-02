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
use CAPTCHAReader\src\Repository\Pretreatment\PretreatmentNeeaRepository;
use CAPTCHAReader\src\Traits\PretreatmentTrait;

class PretreatmentNeea extends Load
{
    use PretreatmentTrait;

    private $conf;
    private $resultContainer;
    private $pretreatmentRepository;

    const A = 'redBox'; //网格
    const B = 'shadow'; //带阴影扭曲
    const C = 'normal'; //普通

    /**
     * PretreatmentZhengFang constructor.
     * @param Restriction $nextStep
     */
    public function __construct(Restriction $nextStep)
    {
        parent::__construct($nextStep);
        $this->pretreatmentRepository = $this->getRepository('Neea');
    }

    /**
     * @param ResultContainer $resultContainer
     * @return mixed
     */
    function run(ResultContainer $resultContainer)
    {
        $this->resultContainer = $resultContainer;
        $this->conf = $this->resultContainer->getConf();

        $imageInfo = $this->resultContainer->getImageInfo();
        $image = $this->resultContainer->getImage();

        //首先对图片分类
        $type = $this->pretreatmentRepository->checkCAPTCHAType($image);
        $imageInfo['imageType'] = $type;
        $this->resultContainer->setImageInfo($imageInfo);


        if ($type == self::A) {//红框
            //二值化
            $imageBinaryArr = $this->pretreatmentRepository->binarization($imageInfo['width'], $imageInfo['height'], $image);
            $noiseCancelArr = $imageBinaryArr;

            $noiseCancelArr = $this->pretreatmentRepository->cutMiddle($noiseCancelArr);
            $imageInfo['height'] = count($noiseCancelArr);
            $imageInfo['width'] = count($noiseCancelArr[0]);
            $noiseCancelArr = $this->pretreatmentRepository->erosion9($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->erosion4($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);

        } elseif ($type == self::B) {//带阴影扭曲
            //二值化
            $imageBinaryArr = $this->pretreatmentRepository->binarizationB($imageInfo['width'], $imageInfo['height'], $image);
            $noiseCancelArr = $imageBinaryArr;

            $noiseCancelArr = $this->pretreatmentRepository->erosion9($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->erosion4($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->erosion4($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->noiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);

        } elseif ($type == self::C) {//普通
            //二值化
            $imageBinaryArr = $this->pretreatmentRepository->binarizationC($imageInfo['width'], $imageInfo['height'], $image);
            $noiseCancelArr = $imageBinaryArr;
            $noiseCancelArr = $this->pretreatmentRepository->erosion9($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->erosion4($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->erosion4($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
            $noiseCancelArr = $this->pretreatmentRepository->noiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);
        }

        //去掉散点
//        $noiseCancelArr = $this->pretreatmentRepository->expansion($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
//
//        $this->showResArr($noiseCancelArr);
//        self::dd(1);
//        $noiseCancelArr = $this->pretreatmentRepository->erosion2($noiseCancelArr, $imageInfo['width'], $imageInfo['height']);
//        $noiseCancelArr = $this->pretreatmentRepository->noiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);
//        $noiseCancelArr = $this->pretreatmentRepository->simpleNoiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);

        $noiseCancelArr = $this->pretreatmentRepository->shrink($noiseCancelArr);

        $imageInfo['height'] = count($noiseCancelArr);
        $imageInfo['width'] = count($noiseCancelArr[0]);
        $this->resultContainer->setImageInfo($imageInfo);

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