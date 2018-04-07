<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:19
 */

namespace CAPTCHAReader\src\Repository\Cutting;

use CAPTCHAReader\src\Traits\CuttingTrait;

class CuttingQinGuoUnShrinkRepository
{
    use CuttingTrait;

    /**
     * @param $width
     * @param $height
     * @param $noiseCancelArr
     * @return array
     */
    public function getXCoordinate( $width , $height , $noiseCancelArr ){
        $beforeLine = [0 , 35 , 65 , 92];
        $afterLine  = [34 , 64 , 91 , 121];

        $xArr  = $this->getCutBeforeCol( $noiseCancelArr , $width , $height , $beforeLine );
        $x_Arr = $this->getCutAfterCol( $noiseCancelArr , $width , $height , $afterLine );

        //合并xArr和x_Arr
        $xAllArr = [];
        foreach($xArr as $key => $x){
            $xAllArr[] = $xArr[$key];
            $xAllArr[] = $x_Arr[$key];
        }

        return $xAllArr;
    }

    /**
     * @param $xAllArr
     * @param $height
     * @param $noiseCancelArr
     * @return array
     */
    public function getYCoordinate( $xAllArr , $height , $noiseCancelArr ){
        $yArr  = $this->getCutBeforeRow( $xAllArr , $height , $noiseCancelArr );
        $y_Arr = $this->getCutAfterRow( $xAllArr , $height , $noiseCancelArr );

        //合并 $yArr 和 $y_Arr
        $yAllArr = [];
        foreach($yArr as $key => $x){
            $yAllArr[] = $yArr[$key];
            $yAllArr[] = $y_Arr[$key];
        }

        return $yAllArr;
    }

    /**
     * @param $noiseCancelArr
     * @param $coordinate
     * @return array
     */
    public function cut( $noiseCancelArr , $coordinate ){
        $charPixelCollection = [
            'char0' => [
                'x' => [] , 'y' => [] , 'pixel' => [] ,
            ] ,
            'char1' => [
                'x' => [] , 'y' => [] , 'pixel' => [] ,
            ] ,
            'char2' => [
                'x' => [] , 'y' => [] , 'pixel' => [] ,
            ] ,
            'char3' => [
                'x' => [] , 'y' => [] , 'pixel' => [] ,
            ] ,
        ];

        for($i = 0; $i < 4; ++$i){
            $charPixelCollection["char$i"]['x'] = [$coordinate['xAllArr'][$i*2] , $coordinate['xAllArr'][$i*2 + 1]];
            $charPixelCollection["char$i"]['y'] = [$coordinate['yAllArr'][$i*2] , $coordinate['yAllArr'][$i*2 + 1]];
        }

        foreach($noiseCancelArr as $y => $row){
            foreach($row as $x => $value){
                for($i = 0; $i < 4; ++$i){
                    $charCOORD = $this->getCharAllXY( $charPixelCollection["char$i"] );
                    if ($this->isInArea( $x , $y , $charCOORD['x'] , $charCOORD['x_'] , $charCOORD['y'] , $charCOORD['y_'] )) {
                        $position = $this->getPointPositionInArea( $x , $y , $charCOORD['x'] , $charCOORD['y'] );
                        $charPixelCollection["char$i"]['pixel'][$position['y']][$position['x']] = $noiseCancelArr[$y][$x];
                    }
                }

            }
        }
        return $charPixelCollection;
    }

    /**
     * @param $noiseCancelArr
     * @param $width
     * @param $height
     * @param $beforeLine
     * @return array
     */
    public function getCutBeforeCol( $noiseCancelArr , $width , $height , $beforeLine ){
        $xArr = [];
        foreach($beforeLine as $bLine){
            for($x = $bLine; $x < $width; ++$x){
                $sum = 0;
                for($y = 0; $y < $height; ++$y){
                    $sum += (int)$noiseCancelArr[$y][$x];
                }
                if ($sum > 1) {
                    $xArr[] = $x;
                    break;
                }
            }
        }
        return $xArr;
    }

    /**
     * @param $noiseCancelArr
     * @param $width
     * @param $height
     * @param $afterLine
     * @return array
     */
    public function getCutAfterCol( $noiseCancelArr , $width , $height , $afterLine ){
        $x_Arr = [];
        foreach($afterLine as $aLine){
            for($x = $aLine; $x < $width; --$x){
                $sum = 0;
                for($y = 0; $y < $height; ++$y){
                    $sum += (int)$noiseCancelArr[$y][$x];
                }
                if ($sum > 1) {
                    $x_Arr[] = $x;
                    break;
                }
            }
        }
        return $x_Arr;

    }

    /**
     * @param $xAllArr
     * @param $height
     * @param $noiseCancelArr
     * @return array
     */
    public function getCutBeforeRow( $xAllArr , $height , $noiseCancelArr ){
        $yArr = [];
        for($i = 0; $i < 4; ++$i){
            for($y = 0; $y < $height; ++$y){
                $sum = 0;
                for($x = $xAllArr[$i*2]; $x <= $xAllArr[$i*2 + 1]; ++$x){
                    $sum += (int)$noiseCancelArr[$y][$x];
                }
                if ($sum > 1) {
                    $yArr[] = $y;
                    break;
                }
            }
        }
        return $yArr;
    }

    /**
     * @param $xAllArr
     * @param $height
     * @param $noiseCancelArr
     * @return array
     */
    public function getCutAfterRow( $xAllArr , $height , $noiseCancelArr ){
        $y_Arr = [];
        for($i = 0; $i < 4; ++$i){
            for($y = $height - 1; $y > 0; --$y){
                $sum = 0;
                for($x = $xAllArr[$i*2]; $x <= $xAllArr[$i*2 + 1]; ++$x){
                    $sum += (int)$noiseCancelArr[$y][$x];
                }
                if ($sum > 1) {
                    $y_Arr[] = $y;
                    break;
                }
            }
        }
        return $y_Arr;
    }

    /**
     * @param $char
     * @return array
     */
    public function getCharAllXY( $char ){
        $x  = $char['x'][0];
        $x_ = $char['x'][1];
        $y  = $char['y'][0];
        $y_ = $char['y'][1];

        return compact( 'x' , 'x_' , 'y' , 'y_' );
    }

}