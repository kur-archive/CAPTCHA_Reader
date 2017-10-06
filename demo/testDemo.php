<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/09/29
 * Time: 11:09
 */


require_once('../classLib/GetImage.php');
require_once('../classLib/Pretreatment.php');
require_once('../classLib/Cutting.php');
require_once('../classLib/Identify.php');

/*
 * 获取图片
 * 二值化和降噪
 * 切割
 * 识别
 */

$start_time = microtime( true );



$getImage = new GetImage( 'http://jw.hzau.edu.cn/CheckCode.aspx',1 );
$imagePath = $getImage->getImagePath();
//$imagePath = GetImage::getLocalImg();

$pertreatment = new Pretreatment( $imagePath );
$resArr = $pertreatment->getResArr();
$imageInfo = $pertreatment->getImgInfo($imagePath);
//$pertreatment->showResArr();

$cutting = new Cutting($imageInfo,$resArr);
$afterCutArr = $cutting->getAfterCutArr();
//var_dump( $cutting->getCoordinateArr() );
$cutting->showResArr();
//$cutting->showAfterCutArr();

$identify = new Identify($afterCutArr);
//var_dump( $identify->getCharArrs() );



$end_time = microtime( true );//计时停止
echo 'run times: ' . ($end_time - $start_time) . ' s' ;



