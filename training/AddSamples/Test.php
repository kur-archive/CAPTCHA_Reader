<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/27
 * Time: 20:11
 */
namespace CAPTCHAReader\Training\AddSamples\AddSamplesAuto;
require_once(__DIR__ . '/../../vendor/autoload.php');

class Test
{
    public function t()
    {
        $a=new AddSamplesAuto();
        $a->run();
    }
}

$t = new Test();
$t->t();