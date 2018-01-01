<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/10
 * Time: 23:07
 */

require '../../vendor/autoload.php';

use CAPTCHA_Reader\Training\AddSamples\AutomaticStudy;

$start_time = microtime( true );//运行时间开始计时

$automaticStudy = new AutomaticStudy();
$automaticStudy->run();

$end_time = microtime( true );//计时停止
dump( '执行时间为：' . ($end_time - $start_time) . ' s' );