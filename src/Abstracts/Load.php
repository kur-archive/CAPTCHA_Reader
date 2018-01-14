<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 15:52
 */

namespace CAPTCHAReader\src\App\Abstracts;


abstract class Load extends Restriction
{
    protected $nextStep;
    public function __construct(Restriction $nextStep){
        $this->nextStep = $nextStep;
    }

}