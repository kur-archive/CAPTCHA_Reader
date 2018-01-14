<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 22:23
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangFixedRepository;
use CAPTCHAReader\src\Repository\Cutting\CuttingZhengFangMoveRepository;

trait CuttingTrait
{
    use CommonTrait;

    public function getRepository(string $label){
        switch ($label) {
            case "ZhengFangFixed":
                return new CuttingZhengFangFixedRepository();
            case "ZhengFangMove":
                return new CuttingZhengFangMoveRepository();
        }
    }

}