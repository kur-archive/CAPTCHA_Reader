<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 17:28
 */
$start_time = microtime(true);//运行时间开始计时

require_once(__DIR__ . '/../../vendor/autoload.php');

use CAPTCHAReader\src\App\IndexController;

$a = new IndexController();

//$c=$a->entrance(__DIR__.'/../../sample/TmpSamples/0736425001517150928.gif','local');
//$c = $a->entrance(__DIR__ . '/../../sample/StudySamples/QinGuo/e4kn.png', 'local');
//$c = $a->entrance(__DIR__ . '/../../sample/1.png', 'local');
$c=$a->entrance(__DIR__.'/../../sample/StudySamples/neea.edu.cn/mpf3.png','local');
dump($c);

$end_time = microtime(true);//计时停止
echo '执行时间为：' . ($end_time - $start_time) . ' s' . '<br/>';