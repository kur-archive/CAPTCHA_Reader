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
$a->entrance('lalala');