<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>AccuracyTest</title>
    <style>
        * {
            line-height: 10px;
        }

        #a {
            height: 100px;
        }

        div {
            margin: 30px;
            display: inline-block
        }

        .result {
            line-height: 20px;
        }
    </style>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/02/19
 * Time: 14:00
 */
$start_time = microtime(true);//运行时间开始计时

include('AccTest_Change_function.php');
$yz = 10;//切割判断阈值
$all = array();

echo '<div>';
$url = 'http://61.142.33.204/CheckCode.aspx';//获取验证码的地址//公网，内网可以换成内网地址
$save_to = time() . '.png';
//    $save_to = './img2/' . $n . '.png';
$content = file_get_contents($url);
$save = file_put_contents($save_to, $content);
$_info = getimagesize($save_to);

$info = array(
    'width' => $_info[0],
    'height' => $_info[1],
    'type' => image_type_to_extension($_info[2], false),
    'mime' => $_info['mime']
);
$fun = "imagecreatefrom{$info['type']}";//根据上面获取的格式判定应该使用哪种'imagecreatefrom***'函数
$image = $fun($save_to);

echo "<img src='$save_to' id='a'/><br/>";


//二值化
for ($y = 0; $y < $info['height']; ++$y) {
    for ($x = 0; $x < $info['width']; ++$x) {
        $rgb = imagecolorat($image, $x, $y);
        $rgbArray = imagecolorsforindex($image, $rgb);
        if ($rgbArray['red'] < 110 && $rgbArray['green'] < 110 && $rgbArray['blue'] > 100) {
            $all[$y][$x] = '1';
        } else {
            $all[$y][$x] = '0';
        }
    }
}
imagedestroy($image);//摧毁内存中的图片

//  去噪点
$all = remove_noise($info['height'], $info['width'], $all);//引用remove_noise函数获取去噪点后的二值化数组

$start_x1=$end_x1 =$start_x2=$end_x2=$start_x3=$end_x3=$start_x4 =$end_x4= 0;//切割用的x轴参考值
$start_y1 =$end_y1=$start_y2=$end_y2=$start_y3=$end_y3=$start_y4=$end_y4= 0;//切割用的y轴参考值
//切割
cutting($info['width'],$info['height']);//获取到x轴和y轴的切割用的参考值


echo "x:S1:$start_x1,E1:$end_x1,S2:$start_x2,E2:$end_x2,S3:$start_x3,E3:$end_x3,S4:$start_x4,E4:$end_x4<br/\><br/\><br/\>";
echo "y:S1:$start_y1,E1:$end_y1,S2:$start_y2,E2:$end_y2,S3:$start_y3,E3:$end_y3,S4:$start_y4,E4:$end_y4<br/\><br/\><br/\>";

$letter1 = $letter2 = $letter3 = $letter4 = array();

//获得切割坐标后截取
for ($y = $start_y1, $_y = 0; $y < $end_y1; ++$y, ++$_y) {
    for ($x = $start_x1, $_x = 0; $x < $end_x1; ++$x, ++$_x) {
        $letter1[$_y][$_x] = $all[$y][$x];
    }
}
for ($y = $start_y2, $_y = 0; $y < $end_y2; ++$y, ++$_y) {
    for ($x = $start_x2, $_x = 0; $x < $end_x2; ++$x, ++$_x) {
        $letter2[$_y][$_x] = $all[$y][$x];
    }
}
for ($y = $start_y3, $_y = 0; $y < $end_y3; ++$y, ++$_y) {
    for ($x = $start_x3, $_x = 0; $x < $end_x3; ++$x, ++$_x) {
        $letter3[$_y][$_x] = $all[$y][$x];
    }
}
for ($y = $start_y4, $_y = 0; $y < $end_y4; ++$y, ++$_y) {
    for ($x = $start_x4, $_x = 0; $x < $end_x4; ++$x, ++$_x) {
        $letter4[$_y][$_x] = $all[$y][$x];
    }
}
//展示切割后的结果和二值化后的数组
//show($info['height'], $info['width'], $all, $letter1, $letter2, $letter3, $letter4);

discern($letter1, $letter2, $letter3, $letter4);//识别
echo '</div>';
$end_time = microtime(true);//计时停止
echo '执行时间为：' . ($end_time - $start_time) . ' s' . '<br/>';








