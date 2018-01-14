<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 15:51
 */

namespace CAPTCHAReader\src\App\Abstracts;

use CAPTCHAReader\src\App\ResultContainer;

abstract class Restriction
{
    abstract function run( ResultContainer $resultContainer );
}