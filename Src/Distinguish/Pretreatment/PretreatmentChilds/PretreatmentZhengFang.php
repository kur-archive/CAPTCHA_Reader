<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/04
 * Time: 0:29
 */

namespace CAPTCHA_Reader\Distinguish\Pretreatment;


class PretreatmentZhengFang extends PretreatmentController
{

    /**
     * @param $width
     * @param $height
     * @param $imageBinaryArr
     * @return mixed
     */
    public function noiseCancel( $width , $height , $imageBinaryArr )
    {
        $noiseCancelArr = $this->SimpleNoiseCancel( $width , $height , $imageBinaryArr );
        return $noiseCancelArr;
    }

}