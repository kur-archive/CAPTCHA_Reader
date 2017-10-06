<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>AddDictionary</title>
    <style>
        * {
            line-height: 10px;
        }

        #text1, #text2, #text3, #text4 {
            width: 50px;
            height: 50px;
            outline: none;
            border: 3px solid #333;
            margin-left: 20px;
            text-align: center;
            font-size: 25px;
        }

        #button_s {
            height: 50px;
            width: 100px;
            background: #333;
            color: #fff;
            border: 3px solid #333;
        }
        div{display: inline-block;margin: 15px;}
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


//----------------------------------------------------------------------------------//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------//----------------------------------------------------------------------------------


$start_time = microtime(true);
//$number = 100;//要二值化多少个验证码
$yz = 10;
$all = array();
$url = 'http://61.142.33.204/CheckCode.aspx';
$save_to = time() . '.png';
//$save_to = '1487821939.png';
$content = file_get_contents($url);
$save = file_put_contents($save_to, $content);
echo "<img src=\"$save_to\" style=\"height: 60px;\" alt=\"\"/><a href=\"http://code.cc/DEMO/tianyi/AddDictionary.php\">换一张</a><br>
<form action='http://code.cc/DEMO/tianyi/AddDictionary.php' method='POST'>
<input type=\"text\" name=\"text1\" id=\"text1\"><input type=\"text\" name=\"text2\" id=\"text2\"><input type=\"text\" name=\"text3\" id=\"text3\"><input type=\"text\" name=\"text4\" id=\"text4\">
<input type=\"submit\" name=\"submit\" id=\"button_s\" value=\"添加字典\">
<br>
    <br><br><br>";

$_info = getimagesize($save_to);
$info = array(
    'width' => $_info[0],
    'height' => $_info[1],
    'type' => image_type_to_extension($_info[2], false),
    'mime' => $_info['mime']
);
$fun = "imagecreatefrom{$info['type']}";
$image = $fun($save_to);

//二值化
for ($y = 0; $y < $info['height']; ++$y) {
    for ($x = 0; $x < $info['width']; ++$x) {
        $rgb = imagecolorat($image, $x, $y);
        $rgbArray = imagecolorsforindex($image, $rgb);
        if ($rgbArray['red'] < 110 && $rgbArray['green'] < 110 && $rgbArray['blue'] > 100) {
            $all[$y][$x] = '1';
//                echo '◆';
        } else {
            $all[$y][$x] = '0';
//                echo '◇';
        }
    }
//        echo '<br/>';
}

//  去噪点
$all = remove_noise($info['height'], $info['width'], $all);

//    for ($y = 0; $y < $info['height']; ++$y) {
//        for ($x = 0; $x < $info['width']; ++$x) {
//切割
$tmp_x = 0;
$tmp_y = 0;

$start_x1 = 0;
$end_x1 = 0;
$start_x2 = 0;
$end_x2 = 0;
$start_x3 = 0;
$end_x3 = 0;
$start_x4 = 0;
$end_x4 = 0;

$start_y1 = 0;
$end_y1 = 0;
$start_y2 = 0;
$end_y2 = 0;
$start_y3 = 0;
$end_y3 = 0;
$start_y4 = 0;
$end_y4 = 0;


//切割第一个字母的前竖线
for ($x = 0; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_x1 = $x;
        $tmp_x = $x + 1;
        break;
    }
}
//切割第一个字母的后竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }

    if ($x - $tmp_x > $yz) {
        if (!Estimate($x, $y, 'vertical', $all, $info['height'])) {//判断是否应该继续截取
            //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
            $end_x1 = $x;
            $tmp_x = $x;
            break;
        }
    }
    if ($num == 0 && $x - $tmp_x > 2) {//有空行，且截取的宽度>2
        $end_x1 = $x;
        $tmp_x = $x;
        break;
    }
    if ($num <= 1 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
        $end_x1 = $x + 1;
        $tmp_x = $x + 1;
        break;
    }
}

//切割第二个字母的前竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_x2 = $x;
        $tmp_x = $x + 1;
        break;
    }
}
//切割第二个字母的后竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($x - $tmp_x > $yz) {
        if (!Estimate($x, $y, 'vertical', $all, $info['height'])) {//判断是否应该继续截取
            //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
            $end_x2 = $x;
            $tmp_x = $x;
            break;
        }
    }
    if ($num == 0 && $x - $tmp_x > 2) {
        $end_x2 = $x;
        $tmp_x = $x;
        break;
    }
    if ($num <= 1 && $x - $tmp_x >= 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
        $end_x2 = $x + 1;
        $tmp_x = $x + 1;
        break;
    }
}

//切割第三个字母的前竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_x3 = $x;
        $tmp_x = $x + 1;
        break;
    }
}
//切割第三个字母的后竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($x - $tmp_x > $yz) {
        if (!Estimate($x, $y, 'vertical', $all, $info['height'])) {//判断是否应该继续截取
            //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
            $end_x3 = $x;
            $tmp_x = $x;
            break;
        }
    }
    if ($num == 0 && $x - $tmp_x > 2) {
        $end_x3 = $x;
        $tmp_x = $x;
        break;
    }
    if ($num <= 1 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
        $end_x3 = $x + 1;
        $tmp_x = $x + 1;
        break;
    }
}

//切割第四个字母的前竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_x4 = $x;
        $tmp_x = $x + 1;
        break;
    }
}
//切割第四个字母的后竖线
for ($x = $tmp_x; $x < $info['width']; ++$x) {
    $num = 0;
    for ($y = 0; $y < $info['height']; ++$y) {
        $num += $all[$y][$x];
    }
    if ($x - $tmp_x > $yz) {
        if (!Estimate($x, $y, 'vertical', $all, $info['height'])) {//判断是否应该继续截取
            //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
            $end_x4 = $x;
            $tmp_x = $x;
            break;
        }
    }
    if ($num == 0 && $x - $tmp_x > 2) {
        $end_x4 = $x;
        $tmp_x = $x;
        break;
    }
    if ($num <= 1 && $x - $tmp_x > 9) {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
        $end_x4 = $x + 1;
        $tmp_x = $x + 1;
        break;
    }
}

//切割第一个字母的上横线
for ($y = 0; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x1; $x < $end_x1; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_y1 = $y;
        $tmp_y = $y;
        break;
    }
}

//切割第一个字母的下横线
for ($y = $tmp_y; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x1; $x < $end_x1; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num == 0) {
        $_num = 0;
        for ($q = $y + 1; $q < $y + 3; ++$q) {
            for ($p = $start_x1; $p < $end_x1; ++$p) {
                $_num += $all[$q][$p];
            }
        }
        if ($_num == 0) {
            $end_y1 = $y;
            $tmp_y = 0;
            break;
        }
    }
}

//切割第二个字母的上横线
for ($y = 0; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x2; $x < $end_x2; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_y2 = $y;
        $tmp_y = $y;
        break;
    }
}

//切割第二个字母的下横线
for ($y = $tmp_y; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x2; $x < $end_x2; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num == 0) {
        $_num = 0;
        for ($q = $y + 1; $q < $y + 3; ++$q) {
            for ($p = $start_x2; $p < $end_x2; ++$p) {
                $_num += $all[$q][$p];
            }
        }
        if ($_num == 0) {
            $end_y2 = $y;
            $tmp_y = 0;
            break;
        }
    }
}

//切割第三个字母的上横线
for ($y = 0; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x3; $x < $end_x3; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_y3 = $y;
        $tmp_y = $y;
        break;
    }
}

//切割第三个字母的下横线
for ($y = $tmp_y; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x3; $x < $end_x3; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num == 0) {
        $_num = 0;
        for ($q = $y + 1; $q < $y + 3; ++$q) {
            for ($p = $start_x3; $p < $end_x3; ++$p) {
                $_num += $all[$q][$p];
            }
        }
        if ($_num == 0) {
            $end_y3 = $y;
            $tmp_y = 0;
            break;
        }
    }
}

//切割第四个字母的上横线
for ($y = 0; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x4; $x < $end_x4; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num > 0) {
        $start_y4 = $y;
        $tmp_y = $y;
        break;
    }
}

//切割第四个字母的下横线
for ($y = $tmp_y; $y < $info['height']; ++$y) {
    $num = 0;
    for ($x = $start_x4; $x < $end_x4; ++$x) {
        $num += $all[$y][$x];
    }
    if ($num == 0) {
        $_num = 0;
        for ($q = $y + 1; $q < $y + 3; ++$q) {
            for ($p = $start_x4; $p < $end_x4; ++$p) {
                $_num += $all[$q][$p];
            }
        }
        if ($_num == 0) {
            $end_y4 = $y;
            $tmp_y = 0;
            break;
        }
    }
}

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


//show
for ($y = 0; $y < $info['height']; ++$y) {
    for ($x = 0; $x < $info['width']; ++$x) {
        if ($all[$y][$x] == 1) {
            echo '◆';
        } else {
            echo '◇';
        }
    }
    echo '<br/>';
}
echo '<br/>';
echo '<hr/>';
echo '<br/>';

echo '<div>';
for ($y = 0; isset($letter1[$y][0]); ++$y) {
    for ($x = 0; isset($letter1[$y][$x]); ++$x) {
        if ($letter1[$y][$x] == 1) {
            echo '◆';
        } else {
            echo '◇';
        }
    }
    echo '<br/>';
}
echo '</div>';

echo '<div>';
echo '<br/>';
for ($y = 0; isset($letter2[$y][0]); ++$y) {
    for ($x = 0; isset($letter2[$y][$x]); ++$x) {
        if ($letter2[$y][$x] == 1) {
            echo '◆';
        } else {
            echo '◇';
        }
    }
    echo '<br/>';
}echo '</div>';

echo '<div>';
echo '<br/>';
for ($y = 0; isset($letter3[$y][0]); ++$y) {
    for ($x = 0; isset($letter3[$y][$x]); ++$x) {
        if ($letter3[$y][$x] == 1) {
            echo '◆';
        } else {
            echo '◇';
        }
    }
    echo '<br/>';
}echo '</div>';

echo '<div>';
echo '<br/>';
for ($y = 0; isset($letter4[$y][0]); ++$y) {
    for ($x = 0; isset($letter4[$y][$x]); ++$x) {
        if ($letter4[$y][$x] == 1) {
            echo '◆';
        } else {
            echo '◇';
        }
    }
    echo '<br/>';
}echo '</div>';
echo '<br/>';

$array = shibie($letter1, $letter2, $letter3, $letter4);
echo '<br/>' . '<br/>' . '<br/>' . $array['letter1'] . '<br/>';
echo $array['letter2'] . '<br/>';
echo $array['letter3'] . '<br/>';
echo $array['letter4'] . '<br/>';


echo '<br/>';
echo '<hr/>';
echo '<br/>';
echo "<input type=\"text\" name=\"letter1\" id=\"\" value=\"{$array['letter1']}\"/>";
echo "<input type=\"text\" name=\"letter2\" id=\"\" value=\"{$array['letter2']}\"/>";
echo "<input type=\"text\" name=\"letter3\" id=\"\" value=\"{$array['letter3']}\"/>";
echo "<input type=\"text\" name=\"letter4\" id=\"\" value=\"{$array['letter4']}\"/>";

echo "</form>";


$end_time = microtime(true);
echo '执行时间为：' . ($end_time - $start_time) . ' s';


function twoDimArrayToStr($array)
{
    $str = '';
    for ($y = 0; isset($array[1][$y]); ++$y) {
        for ($x = 0; isset($array[$x][$y]); ++$x) {
            $str .= $array[$x][$y];
        }
    }
    return $str;
}

function shibie($letter1, $letter2, $letter3, $letter4)
{


    $letter1_str = twoDimArrayToStr($letter1);
    $letter2_str = twoDimArrayToStr($letter2);
    $letter3_str = twoDimArrayToStr($letter3);
    $letter4_str = twoDimArrayToStr($letter4);

    $result_arr1 = array();
    $result_arr2 = array();
    $result_arr3 = array();
    $result_arr4 = array();

    $link = mysqli_connect('localhost', 'root', '', 'yzm');
    if (!$link) {
        die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
    }
    mysqli_query($link, 'set names \'utf8\'');
    $sql = "SELECT * FROM `zidian` ORDER BY `CorrespondLetter` ASC";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_row($result)) {
        similar_text($row[2], $letter1_str, $percent1);
        similar_text($row[2], $letter2_str, $percent2);
        similar_text($row[2], $letter3_str, $percent3);
        similar_text($row[2], $letter4_str, $percent4);

        $result_arr1["$row[0]-$row[1]"] = $percent1;
        $result_arr2["$row[0]-$row[1]"] = $percent2;
        $result_arr3["$row[0]-$row[1]"] = $percent3;
        $result_arr4["$row[0]-$row[1]"] = $percent4;
    }
    $z = 0;
    $str = '';
    foreach ($result_arr1 as $key => $percent) {
        if ($percent > $z) {
            $z = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>' . '<br/>';
//    echo $str.' ';

    $z = 0;
    $str = '';
    foreach ($result_arr2 as $key => $percent) {
        if ($percent > $z) {
            $z = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>' . '<br/>';
//    echo $str.' ';

    $z = 0;
    $str = '';
    foreach ($result_arr3 as $key => $percent) {
        if ($percent > $z) {
            $z = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>' . '<br/>';
//    echo $str.' ';

    $z = 0;
    $str = '';
    foreach ($result_arr4 as $key => $percent) {
        if ($percent > $z) {
            $z = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>' . '<br/>';
//    echo $str.' ';

//    echo '<pre>';
//    print_r($result_arr1);


//    foreach ($tri as $key => $value) {
//        similar_text($value, $code1_str, $percent);
//        if ($percent > 95) {
//            echo $key;
//            break;
//        }
//    }
//    foreach ($tri as $key => $value) {
//        similar_text($value, $code2_str, $percent);
//        if ($percent > 95) {
//            echo $key;
//            break;
//        }
//    }
//    foreach ($tri as $key => $value) {
//        similar_text($value, $code3_str, $percent);
//        if ($percent > 95) {
//            echo $key;
//            break;
//        }
//    }
//    foreach ($tri as $key => $value) {
//        similar_text($value, $code4_str, $percent);
//        if ($percent > 95) {
//            echo $key;
//            break;
//        }
//    }

    return array(
        'letter1' => $letter1_str,
        'letter2' => $letter2_str,
        'letter3' => $letter3_str,
        'letter4' => $letter4_str
    );
}

function remove_noise($height, $width, $array)
{
    //  去噪点
    for ($y = 0; $y < $height; ++$y) {
        for ($x = 0; $x < $width; ++$x) {
            if ($array[$y][$x] == 1) {
                $num = 0;
                // 上
                if (isset($array[$y - 1][$x])) {
                    $num = $num + $array[$y - 1][$x];
                }
                // 下
                if (isset($array[$y + 1][$x])) {
                    $num = $num + $array[$y + 1][$x];
                }
                // 左
                if (isset($array[$y][$x - 1])) {
                    $num = $num + $array[$y][$x - 1];
                }
                // 右
                if (isset($array[$y][$x + 1])) {
                    $num = $num + $array[$y][$x + 1];
                }
                // 上左
                if (isset($array[$y - 1][$x - 1])) {
                    $num = $num + $array[$y - 1][$x - 1];
                }
                // 上右
                if (isset($array[$y - 1][$x + 1])) {
                    $num = $num + $array[$y - 1][$x + 1];
                }
                // 下左
                if (isset($array[$y + 1][$x - 1])) {
                    $num = $num + $array[$y + 1][$x - 1];
                }
                // 下右
                if (isset($array[$y + 1][$x + 1])) {
                    $num = $num + $array[$y + 1][$x + 1];
                }
                if ($num < 3) {
                    $array[$y][$x] = '0';
                } else {
                    $array[$y][$x] = '1';
                }
            }

        }
    }
    return $array;
}


function Estimate($now_x, $now_y, $mode, $array, $reference)
{//判断是否应该继续截取
    if ($mode == 'vertical') {
        $height = $reference;
        for ($_x = $now_x + 1; $_x < $now_x + 5; ++$_x) {
            $_num = 0;
            for ($_y = 0; $_y < $height; ++$_y) {
                $_num += $array[$_y][$_x];
            }
            if ($_num <= 1) {
                return true;
            }
        }
        return false;
    } elseif ($mode == 'horizontal') {
        $weight = $reference;
    }

}

$link = mysqli_connect('localhost', 'root', '', 'yzm');
if (!$link) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}
mysqli_query($link, 'set names \'utf8\'');

if (isset($_POST['text1'])) {
    $text1 = $_POST['text1'];
    $z1 = $text1;
    echo '<br/>' . $text1;
}
if (isset($_POST['text2'])) {
    $text2 = $_POST['text2'];
    $z2 = $text2;
    echo $text2;
}
if (isset($_POST['text3'])) {
    $text3 = $_POST['text3'];
    $z3 = $text3;
    echo $text3;
}
if (isset($_POST['text4'])) {
    $text4 = $_POST['text4'];
    $z4 = $text4;
    echo $text4;
}

if (isset($_POST['letter1']) && isset($_POST['letter2']) && isset($_POST['letter3']) && isset($_POST['letter4'])) {
    $letter1 = $_POST['letter1'];
    $letter2 = $_POST['letter2'];
    $letter3 = $_POST['letter3'];
    $letter4 = $_POST['letter4'];
    echo '<br/>' . $letter1;
    echo '<br/>' . $letter2;
    echo '<br/>' . $letter3;
    echo '<br/>' . $letter4;
    $sql1 = "INSERT INTO zidian(`CorrespondLetter`, `Code`) VALUES ('$z1','$letter1')";
    echo $sql1 . '<br/>' . '<br/>' . '<br/>';
    $sql2 = "INSERT INTO zidian(`CorrespondLetter`, `Code`) VALUES ('$z2','$letter2')";
    echo $sql2 . '<br/>' . '<br/>' . '<br/>';
    $sql3 = "INSERT INTO zidian(`CorrespondLetter`, `Code`) VALUES ('$z3','$letter3')";
    echo $sql3 . '<br/>' . '<br/>' . '<br/>';
    $sql4 = "INSERT INTO zidian(`CorrespondLetter`, `Code`) VALUES ('$z4','$letter4')";
    echo $sql4 . '<br/>' . '<br/>' . '<br/>';
    if (isset($text1) && trim($text1, ' ') != '') {
        $result1 = mysqli_query($link, $sql1);
        echo $result1;
    }
    if (isset($text2) && trim($text2, ' ') != '') {
        $result2 = mysqli_query($link, $sql2);
        echo $result2;
    }
    if (isset($text3) && trim($text3, ' ') != '') {
        $result3 = mysqli_query($link, $sql3);
        echo $result3;
    }
    if (isset($text4) && trim($text4, ' ') != '') {
        $result4 = mysqli_query($link, $sql4);
        echo $result4;
    }
}

