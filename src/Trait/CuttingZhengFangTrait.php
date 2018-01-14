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

trait CuttingZhengFangTrait
{
    use CommonTrait;

    public function getRepository($label){
        switch ($label) {
            case "ZhengFangFixed":
                return CuttingZhengFangFixedRepository::class;
            case "ZhengFangMove":
                return CuttingZhengFangMoveRepository::class;
        }
    }

}