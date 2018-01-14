<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:12
 */

namespace CAPTCHAReader\src\App\Identify;


use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\ResultContainer;

class IdentifyZhengFangRowColLevenshtein extends Load
{
    function run( ResultContainer $resultContainer ){
        // TODO: Implement run() method.
        //在前面两种的基础上将识别函数替换为levenshtein
    }

}