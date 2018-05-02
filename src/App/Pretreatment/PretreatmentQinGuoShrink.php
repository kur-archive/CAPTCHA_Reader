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

class PretreatmentQinGuoShrink extends Load
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
        $this->pretreatmentRepository = $this->getRepository('QinGuo');
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
        imagefilter($image, IMG_FILTER_GRAYSCALE);
//        imagejpeg($image, 'test.jpg');
//        imagefilter($image, IMG_FILTER_CONTRAST,-20);
//        imagefilter($image, IMG_FILTER_EMBOSS);
//        imagejpeg($image, 'test1.jpg');
//        self::dd(1);

        //首先進行顏色統計
//        $colorAggs = $this->pretreatmentRepository->colorAggregation($image, $imageInfo['width'], $imageInfo['height']);
//        self::dd($image);
//        self::dd($colorAggs);

//        $colorTop4 = [];
//
//        foreach ($colorAggs as $value) {
//            if (count($colorTop4) == 4) {
//                break;
//            }
//            $colorTop4[] = [
//                'red'   => $value['red'],
//                'green' => $value['green'],
//                'blue'  => $value['blue'],
//            ];
//        }
//        self::dd($colorTop4);

        //二值化
        $imageBinaryArr = $this->pretreatmentRepository->binarization($imageInfo['width'], $imageInfo['height'], $image, $colorTop4 ?? '');

        //去掉散点
        $noiseCancelArr = $imageBinaryArr;
        $noiseCancelArr = $this->pretreatmentRepository->erosion($imageBinaryArr, $imageInfo['width'], $imageInfo['height']);
        $noiseCancelArr = $this->pretreatmentRepository->noiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);
        $noiseCancelArr = $this->pretreatmentRepository->simpleNoiseCancel($imageInfo['width'], $imageInfo['height'], $noiseCancelArr);
//        $this->showResArr($noiseCancelArr);

        $noiseCancelArr = $this->pretreatmentRepository->shrink($noiseCancelArr);
        $conf = $this->resultContainer->getImageInfo();
        $conf['height'] = count($noiseCancelArr);
        $conf['width'] = count($noiseCancelArr[0]);
        $this->resultContainer->setImageInfo($conf);
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