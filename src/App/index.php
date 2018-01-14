<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 17:28
 */
require_once(__DIR__ . '/../../vendor/autoload.php');
use CAPTCHAReader\src\App\IndexController;

$a = new IndexController();
$a->entrance('D:\code\projects\CAPTCHA_Reader_reset\sample\TmpSamples\1508770737.png','local');