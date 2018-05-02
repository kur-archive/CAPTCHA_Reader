<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:10
 */

namespace CAPTCHAReader\src\App\Cutting;


use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\App\ResultContainer;
use CAPTCHAReader\src\Traits\CuttingTrait;

class CuttingNeea extends Load
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
        $this->cuttingRepository = $this->getRepository('Neea');
    }


    function run(ResultContainer $resultContainer)
    {
        $this->resultContainer = $resultContainer;
        $this->conf = $this->resultContainer->getConf();
        $this->imageInfo = $this->resultContainer->getImageInfo();
        $this->noiseCancelArr = $this->resultContainer->getNoiseCancelArr();

        /**
         * 水滴切割
         *
         */

//        $this->noiseCancelArr = $this->cuttingRepository->droplet($this->noiseCancelArr);

        /**
         * 移动切割
         * 移动切割根据现在的的左右高度，来判断是否需要切割，搭配高度投影和上下差投影。
         * 从左至右遍历，同时辅以两种投影的结果，估计较好的切割位置。
         *
         * 首先计算出高度投影和上下差投影的数组。
         * 然后merge两个数组，相对应的项相加，得到一个数组，估计可能的点位。
         * 然后对每一个点进行计算，得到全部有可能的低点，然后利用方正的滑动切割方法进行切割。
         * 对每个低点计算，它和上一个点的距离以及已知的可用点数以及之类的
         *
         */


        $projectionArr = $this->getDifferenceHeightProjection($this->noiseCancelArr);
        $valleys = $this->cuttingRepository->findValley($projectionArr);
        $trueValleys = $this->cuttingRepository->findTrueValley($valleys);
//        dump($trueValleys);
        if (count($trueValleys) != 5) {//考虑这里如果不等于5就抛弃
            $trueValleys = [0, 17, 30, 45, count($this->noiseCancelArr[0])-1];
        }
//        self::dd($trueValleys);


//        $this->showResArrAndAggs($this->noiseCancelArr);
//        dump($valleys);
//        self::dd($trueValleys);
//        self::dd(1);
        $width = count($this->noiseCancelArr[0]);
        $height = count($this->noiseCancelArr);

        //获取坐标
        $xAllArr = $this->cuttingRepository->getXCoordinate($width, $height, $this->noiseCancelArr, $trueValleys);
        $yAllArr = $this->cuttingRepository->getYCoordinate($xAllArr, $height, $this->noiseCancelArr, $trueValleys);


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