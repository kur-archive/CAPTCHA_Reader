<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/15
 * Time: 0:09
 */
require 'vendor/autoload.php';
$start_time = microtime( true );//运行时间开始计时

if (isset( $_POST['submit'] ))
{
    $a   = new \CAPTCHA_Reader\Tools\AddSamplesRepository( 'store' );
    $arr = [
        'char1'    => $_POST['char1'] ,
        'char2'    => $_POST['char2'] ,
        'char3'    => $_POST['char3'] ,
        'char4'    => $_POST['char4'] ,
        'char1str' => $_POST['char1str'] ,
        'char2str' => $_POST['char2str'] ,
        'char3str' => $_POST['char3str'] ,
        'char4str' => $_POST['char4str'] ,
    ];
    $a->store( $arr );
}
else
{
    if (isset( $_GET['result'] ))
    {
        var_dump( json_decode( $_GET['result'] ) );
    }
    $a = new \CAPTCHA_Reader\Tools\AddSamplesRepository( 'load' );
    echo($a->index());
}

$end_time = microtime( true );//计时停止
echo '执行时间为：' . ($end_time - $start_time) . ' s' . '<br/>';




