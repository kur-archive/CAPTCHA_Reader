<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 23:08
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowColRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowRepository;

trait IdentifyTrait
{
    public function getRepository( $label ){
        switch ($label) {
            case 'ZhengFangRowColLevenshtein':
                return new IdentifyZhengFangRowColLevenshteinRepository();
            case 'ZhengFangRowCol':
                return new IdentifyZhengFangRowColRepository();
            case 'ZhengFangRowLevenshtein':
                return new IdentifyZhengFangRowLevenshteinRepository();
            case 'ZhengFangRow':
                return new IdentifyZhengFangRowRepository();
        }

    }


}