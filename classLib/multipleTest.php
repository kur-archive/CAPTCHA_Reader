<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/16
 * Time: 1:26
 */
require 'vendor/autoload.php';

use \CAPTCHA_Reader\Tools\MultipleTestsRepository;
//TODO 这里补dir
$multipleTest = new MultipleTestsRepository( 'dir' );
$multipleTest->startTest();
//TODO 这里怎么输出测试结果
echo 'result......';