<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/28
 * Time: 21:57
 */

namespace CAPTCHAReader\Training\MultiplesTests\NormalMultiplesTests\NormalMultipleTests;

use CAPTCHAReader\src\App\IndexController;
use CAPTCHAReader\Training\MultiplesTests\NormalMultiplesTests\NormalMultipleTests;

require_once(__DIR__ . '/../../vendor/autoload.php');

class Test
{
    public function t()
    {
        $i = new IndexController();
        $a = new NormalMultipleTests();
        $a->run('zhengfang', $i, 'sdfljsdkfjlksdjfklsdjkslfjklsdf');
    }
}

$t = new Test();
$t->t();