<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:29
 */
require 'vendor/autoload.php';
use CAPTCHA_Reader\IndexController;

$start_time = microtime( true );//运行时间开始计时

$index = new IndexController();
$result=$index->getResult('D:\code\DEMO\CAPTCHA_Idenify\tianyi\41.png');
var_dump( $result );

$end_time = microtime( true );//计时停止
echo '执行时间为：' . ($end_time - $start_time) . ' s' . '<br/>';