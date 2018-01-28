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

class CuttingZhengFangMove extends Load
{
    use CuttingTrait;

    private $conf;
    private $resultContainer;
    private $cuttingRepository;
    private $imageInfo;
    private $noiseCancelArr;

    public function __construct( Restriction $nextStep ){
        parent::__construct( $nextStep );
        $this->cuttingRepository = $this->getRepository( 'ZhengFangMove' );
    }

    public function run( ResultContainer $resultContainer ){
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();
        $this->imageInfo       = $this->resultContainer->getImageInfo();
        $this->noiseCancelArr  = $this->resultContainer->getNoiseCancelArr();

        $width  = $this->imageInfo['width'];
        $height = $this->imageInfo['height'];

        $yz = 10;

        $tmp_x = 0;
        $tmp_y = 0;

        $start_x1 = 0;
        $end_x1   = 0;
        $start_x2 = 0;
        $end_x2   = 0;
        $start_x3 = 0;
        $end_x3   = 0;
        $start_x4 = 0;
        $end_x4   = 0;

        $start_y1 = 0;
        $end_y1   = 0;
        $start_y2 = 0;
        $end_y2   = 0;
        $start_y3 = 0;
        $end_y3   = 0;
        $start_y4 = 0;
        $end_y4   = 0;


        //切割第一个字母的前竖线
        for($x = 0; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_x1 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第一个字母的后竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }

            if ($x - $tmp_x > $yz) {
                if (!$this->cuttingRepository->Estimate( $x , $y , 'vertical' , $this->noiseCancelArr , $height )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x1 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {//有空行，且截取的宽度>2
                $end_x1 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x1 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第二个字母的前竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_x2 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第二个字母的后竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!$this->cuttingRepository->Estimate( $x , $y , 'vertical' , $this->noiseCancelArr , $height )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x2 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x2 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x >= 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x2 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第三个字母的前竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_x3 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第三个字母的后竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!$this->cuttingRepository->Estimate( $x , $y , 'vertical' , $this->noiseCancelArr , $height )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x3 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x3 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x3 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第四个字母的前竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_x4 = $x;
                $tmp_x    = $x + 1;
                break;
            }
        }
        //切割第四个字母的后竖线
        for($x = $tmp_x; $x < $width; ++$x){
            $num = 0;
            for($y = 0; $y < $height; ++$y){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($x - $tmp_x > $yz) {
                if (!$this->cuttingRepository->Estimate( $x , $y , 'vertical' , $this->noiseCancelArr , $height )) {//判断是否应该继续截取
                    //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                    $end_x4 = $x;
                    $tmp_x  = $x;
                    break;
                }
            }
            if ($num == 0 && $x - $tmp_x > 2) {
                $end_x4 = $x;
                $tmp_x  = $x;
                break;
            }
            if ($num <= 2 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
                $end_x4 = $x + 1;
                $tmp_x  = $x + 1;
                break;
            }
        }

        //切割第一个字母的上横线
        for($y = 0; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x1; $x < $end_x1; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_y1 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第一个字母的下横线
        for($y = $tmp_y; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x1; $x < $end_x1; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x1; $p < $end_x1; ++$p){
                        $_num += $this->noiseCancelArr[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y1 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第二个字母的上横线
        for($y = 0; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x2; $x < $end_x2; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_y2 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第二个字母的下横线
        for($y = $tmp_y; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x2; $x < $end_x2; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x2; $p < $end_x2; ++$p){
                        $_num += $this->noiseCancelArr[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y2 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第三个字母的上横线
        for($y = 0; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x3; $x < $end_x3; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_y3 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第三个字母的下横线
        for($y = $tmp_y; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x3; $x < $end_x3; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x3; $p < $end_x3; ++$p){
                        $_num += $this->noiseCancelArr[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y3 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        //切割第四个字母的上横线
        for($y = 0; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x4; $x < $end_x4; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num > 0) {
                $start_y4 = $y;
                $tmp_y    = $y;
                break;
            }
        }

        //切割第四个字母的下横线
        for($y = $tmp_y; $y < $height; ++$y){
            $num = 0;
            for($x = $start_x4; $x < $end_x4; ++$x){
                $num += $this->noiseCancelArr[$y][$x];
            }
            if ($num == 0) {
                $_num = 0;
                for($q = $y + 1; $q < $y + 3; ++$q){
                    for($p = $start_x4; $p < $end_x4; ++$p){
                        $_num += $this->noiseCancelArr[$q][$p];
                    }
                }
                if ($_num == 0) {
                    $end_y4 = $y;
                    $tmp_y  = 0;
                    break;
                }
            }
        }

        $letter1 = $letter2 = $letter3 = $letter4 = [];

        //获得切割坐标后截取
        for($y = $start_y1 , $_y = 0; $y < $end_y1; ++$y , ++$_y){
            for($x = $start_x1 , $_x = 0; $x < $end_x1; ++$x , ++$_x){
                $letter1[$_y][$_x] = $this->noiseCancelArr[$y][$x];
            }
        }
        for($y = $start_y2 , $_y = 0; $y < $end_y2; ++$y , ++$_y){
            for($x = $start_x2 , $_x = 0; $x < $end_x2; ++$x , ++$_x){
                $letter2[$_y][$_x] = $this->noiseCancelArr[$y][$x];
            }
        }
        for($y = $start_y3 , $_y = 0; $y < $end_y3; ++$y , ++$_y){
            for($x = $start_x3 , $_x = 0; $x < $end_x3; ++$x , ++$_x){
                $letter3[$_y][$_x] = $this->noiseCancelArr[$y][$x];
            }
        }
        for($y = $start_y4 , $_y = 0; $y < $end_y4; ++$y , ++$_y){
            for($x = $start_x4 , $_x = 0; $x < $end_x4; ++$x , ++$_x){
                $letter4[$_y][$_x] = $this->noiseCancelArr[$y][$x];
            }
        }

        $xAllArr = [$start_x1 , $end_x1 , $start_x2 , $end_x2 , $start_x3 , $end_x3 , $start_x4 , $end_x4];
        $yAllArr = [$start_y1 , $end_y1 , $start_y2 , $end_y2 , $start_y3 , $end_y3 , $start_y4 , $end_y4];

//        $this->showChar(compact('letter1', 'letter2', 'letter3', 'letter4'));

        $this->resultContainer->setCoordinate( compact( 'xAllArr' , 'yAllArr' ) );
        $this->resultContainer->setCharPixedCollection( compact( 'letter1' , 'letter2' , 'letter3' , 'letter4' ) );
        //----------------------------------------------------------------------------
        $this->resultContainer = $this->nextStep->run( $this->resultContainer );
        //----------------------------------------------------------------------------

        return $this->resultContainer;

    }


}