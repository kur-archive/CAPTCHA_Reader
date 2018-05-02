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

class CuttingNeeaFixed extends Load
{
    use CuttingTrait;

    private $conf;
    private $resultContainer;
    private $cuttingRepository;
    private $imageInfo;
    private $noiseCancelArr;

    public function __construct(Restriction $nextStep)
    {
        parent::__construct($nextStep);
        $this->cuttingRepository = $this->getRepository('NeeaFixed');
    }


    function run(ResultContainer $resultContainer)
    {
        $this->resultContainer = $resultContainer;
        $this->conf = $this->resultContainer->getConf();
        $this->imageInfo = $this->resultContainer->getImageInfo();
        $this->noiseCancelArr = $this->resultContainer->getNoiseCancelArr();

        list($width, $height) = [$this->imageInfo['width'], $this->imageInfo['height']];

        $type = $this->imageInfo['imageType'];
//        $this->showResArr($this->noiseCancelArr,[0, 18, 32, 45]);
        //获取坐标
        $xAllArr = $this->cuttingRepository->getXCoordinate($width, $height, $this->noiseCancelArr, $type);
        $yAllArr = $this->cuttingRepository->getYCoordinate($xAllArr, $height, $this->noiseCancelArr, $type);

        //切割
        $pixelCollection = $this->cuttingRepository->cut($this->noiseCancelArr, compact('xAllArr', 'yAllArr'));
        $charPixedCollection = [];
        foreach ($pixelCollection as $charPixel) {
            $charPixedCollection[] = $charPixel['pixel'];
        }

//        $this->showChar($charPixedCollection);
//        self::dd(1);
        //放入容器
        $this->resultContainer->setCoordinate(compact('xAllArr', 'yAllArr'));
        $this->resultContainer->setCharPixedCollection($charPixedCollection);
        //----------------------------------------------------------------------------
        $this->resultContainer = $this->nextStep->run($this->resultContainer);
        //----------------------------------------------------------------------------

        return $this->resultContainer;

    }


}