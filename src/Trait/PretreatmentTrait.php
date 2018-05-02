<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 19:52
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Pretreatment\PretreatmentNeeaRepository;
use CAPTCHAReader\src\Repository\Pretreatment\PretreatmentQinGuoRepository;
use CAPTCHAReader\src\Repository\Pretreatment\PretreatmentTianYiRepository;
use CAPTCHAReader\src\Repository\Pretreatment\PretreatmentZhengFangRepository;

trait PretreatmentTrait
{
    use CommonTrait;

    public function getRepository(string $label)
    {
        switch ($label) {
            case 'ZhengFang':
                return new PretreatmentZhengFangRepository();
            case 'QinGuo':
                return new PretreatmentQinGuoRepository();
            case 'TianYi':
                return new  PretreatmentTianYiRepository();
            case 'Neea':
                return new PretreatmentNeeaRepository();
        }
    }


}