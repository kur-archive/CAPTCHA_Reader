<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/02/21
 * Time: 18:16
 */


function remove_noise( $height , $width , $array )//  去噪点
{
    for ($y = 0; $y < $height; ++$y)
    {
        for ($x = 0; $x < $width; ++$x)
        {
            if ($array[$y][$x] == 1)
            {
                $num = 0;
                // 上
                if (isset( $array[$y - 1][$x] ))
                {
                    $num = $num + $array[$y - 1][$x];
                }
                // 下
                if (isset( $array[$y + 1][$x] ))
                {
                    $num = $num + $array[$y + 1][$x];
                }
                // 左
                if (isset( $array[$y][$x - 1] ))
                {
                    $num = $num + $array[$y][$x - 1];
                }
                // 右
                if (isset( $array[$y][$x + 1] ))
                {
                    $num = $num + $array[$y][$x + 1];
                }
                // 上左
                if (isset( $array[$y - 1][$x - 1] ))
                {
                    $num = $num + $array[$y - 1][$x - 1];
                }
                // 上右
                if (isset( $array[$y - 1][$x + 1] ))
                {
                    $num = $num + $array[$y - 1][$x + 1];
                }
                // 下左
                if (isset( $array[$y + 1][$x - 1] ))
                {
                    $num = $num + $array[$y + 1][$x - 1];
                }
                // 下右
                if (isset( $array[$y + 1][$x + 1] ))
                {
                    $num = $num + $array[$y + 1][$x + 1];
                }
                if ($num < 3)
                {//如果周围的像素数量小于3（也就是为1，或2）则判定为噪点，去除
                    $array[$y][$x] = '0';
                }
                else
                {
                    $array[$y][$x] = '1';
                }
            }
        }
    }
    return $array;
}

function cutting( $width , $height )//剪切
{
    $tmp_x = 0;
    $tmp_y = 0;

    global $start_x1;
    global $end_x1;
    global $start_x2;
    global $end_x2;
    global $start_x3;
    global $end_x3;
    global $start_x4;
    global $end_x4;

    global $start_y1;
    global $end_y1;
    global $start_y2;
    global $end_y2;
    global $start_y3;
    global $end_y3;
    global $start_y4;
    global $end_y4;

    global $yz;
    global $all;


//切割第一个字母的前竖线
    for ($x = 0; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_x1 = $x;
            $tmp_x    = $x + 1;
            break;
        }
    }
//切割第一个字母的后竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }

        if ($x - $tmp_x > $yz)
        {
            if (!Estimate( $x , $y , 'vertical' , $all , $height ))
            {//判断是否应该继续截取
                //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                $end_x1 = $x;
                $tmp_x  = $x;
                break;
            }
        }
        if ($num == 0 && $x - $tmp_x > 2)
        {//有空行，且截取的宽度>2
            $end_x1 = $x;
            $tmp_x  = $x;
            break;
        }
        if ($num <= 2 && $x - $tmp_x > 9)
        {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
            $end_x1 = $x + 1;
            $tmp_x  = $x + 1;
            break;
        }
    }

//切割第二个字母的前竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_x2 = $x;
            $tmp_x    = $x + 1;
            break;
        }
    }
//切割第二个字母的后竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($x - $tmp_x > $yz)
        {
            if (!Estimate( $x , $y , 'vertical' , $all , $height ))
            {//判断是否应该继续截取
                //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                $end_x2 = $x;
                $tmp_x  = $x;
                break;
            }
        }
        if ($num == 0 && $x - $tmp_x > 2)
        {
            $end_x2 = $x;
            $tmp_x  = $x;
            break;
        }
        if ($num <= 2 && $x - $tmp_x >= 9)
        {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
            $end_x2 = $x + 1;
            $tmp_x  = $x + 1;
            break;
        }
    }

//切割第三个字母的前竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_x3 = $x;
            $tmp_x    = $x + 1;
            break;
        }
    }
//切割第三个字母的后竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($x - $tmp_x > $yz)
        {
            if (!Estimate( $x , $y , 'vertical' , $all , $height ))
            {//判断是否应该继续截取
                //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                $end_x3 = $x;
                $tmp_x  = $x;
                break;
            }
        }
        if ($num == 0 && $x - $tmp_x > 2)
        {
            $end_x3 = $x;
            $tmp_x  = $x;
            break;
        }
        if ($num <= 2 && $x - $tmp_x > 9)
        {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
            $end_x3 = $x + 1;
            $tmp_x  = $x + 1;
            break;
        }
    }

//切割第四个字母的前竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_x4 = $x;
            $tmp_x    = $x + 1;
            break;
        }
    }
//切割第四个字母的后竖线
    for ($x = $tmp_x; $x < $width; ++$x)
    {
        $num = 0;
        for ($y = 0; $y < $height; ++$y)
        {
            $num += $all[$y][$x];
        }
        if ($x - $tmp_x > $yz)
        {
            if (!Estimate( $x , $y , 'vertical' , $all , $height ))
            {//判断是否应该继续截取
                //如果判断向后是无法找到可以停止的参照，那么就在阈值处截断，
                $end_x4 = $x;
                $tmp_x  = $x;
                break;
            }
        }
        if ($num == 0 && $x - $tmp_x > 2)
        {
            $end_x4 = $x;
            $tmp_x  = $x;
            break;
        }
        if ($num <= 2 && $x - $tmp_x > 9)
        {//在截取的位置宽度大于9以后如果有一行只有两个点或一个点则截断
            $end_x4 = $x + 1;
            $tmp_x  = $x + 1;
            break;
        }
    }

//切割第一个字母的上横线
    for ($y = 0; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x1; $x < $end_x1; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_y1 = $y;
            $tmp_y    = $y;
            break;
        }
    }

//切割第一个字母的下横线
    for ($y = $tmp_y; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x1; $x < $end_x1; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num == 0)
        {
            $_num = 0;
            for ($q = $y + 1; $q < $y + 3; ++$q)
            {
                for ($p = $start_x1; $p < $end_x1; ++$p)
                {
                    $_num += $all[$q][$p];
                }
            }
            if ($_num == 0)
            {
                $end_y1 = $y;
                $tmp_y  = 0;
                break;
            }
        }
    }

//切割第二个字母的上横线
    for ($y = 0; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x2; $x < $end_x2; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_y2 = $y;
            $tmp_y    = $y;
            break;
        }
    }

//切割第二个字母的下横线
    for ($y = $tmp_y; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x2; $x < $end_x2; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num == 0)
        {
            $_num = 0;
            for ($q = $y + 1; $q < $y + 3; ++$q)
            {
                for ($p = $start_x2; $p < $end_x2; ++$p)
                {
                    $_num += $all[$q][$p];
                }
            }
            if ($_num == 0)
            {
                $end_y2 = $y;
                $tmp_y  = 0;
                break;
            }
        }
    }

//切割第三个字母的上横线
    for ($y = 0; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x3; $x < $end_x3; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_y3 = $y;
            $tmp_y    = $y;
            break;
        }
    }

//切割第三个字母的下横线
    for ($y = $tmp_y; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x3; $x < $end_x3; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num == 0)
        {
            $_num = 0;
            for ($q = $y + 1; $q < $y + 3; ++$q)
            {
                for ($p = $start_x3; $p < $end_x3; ++$p)
                {
                    $_num += $all[$q][$p];
                }
            }
            if ($_num == 0)
            {
                $end_y3 = $y;
                $tmp_y  = 0;
                break;
            }
        }
    }

//切割第四个字母的上横线
    for ($y = 0; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x4; $x < $end_x4; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num > 0)
        {
            $start_y4 = $y;
            $tmp_y    = $y;
            break;
        }
    }

//切割第四个字母的下横线
    for ($y = $tmp_y; $y < $height; ++$y)
    {
        $num = 0;
        for ($x = $start_x4; $x < $end_x4; ++$x)
        {
            $num += $all[$y][$x];
        }
        if ($num == 0)
        {
            $_num = 0;
            for ($q = $y + 1; $q < $y + 3; ++$q)
            {
                for ($p = $start_x4; $p < $end_x4; ++$p)
                {
                    $_num += $all[$q][$p];
                }
            }
            if ($_num == 0)
            {
                $end_y4 = $y;
                $tmp_y  = 0;
                break;
            }
        }
    }
}

function Estimate( $now_x , $now_y , $mode , $array , $reference )//判断是否应该继续截取
{
    if ($mode == 'vertical')
    {
        $height = $reference;
        for ($_x = $now_x + 1; $_x < $now_x + 5; ++$_x)
        {
            $_num = 0;
            for ($_y = 0; $_y < $height; ++$_y)
            {
                $_num += $array[$_y][$_x];
            }
            if ($_num <= 1)
            {
                return true;
            }
        }
        return false;
    }

}

function discern( $letter1 , $letter2 , $letter3 , $letter4 )//识别
{
    $letter1_str = twoDimArrayToStr( $letter1 );//将二维数组转为字符串
    $letter2_str = twoDimArrayToStr( $letter2 );
    $letter3_str = twoDimArrayToStr( $letter3 );
    $letter4_str = twoDimArrayToStr( $letter4 );

    $result_arr1 = array();//用来装全部判断结果的数组
    $result_arr2 = array();
    $result_arr3 = array();
    $result_arr4 = array();

    $link = mysqli_connect( 'localhost' , 'root' , '' , 'yzm' );
    if (!$link)
    {
        die( 'Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error() );
    }
    mysqli_query( $link , 'set names \'utf8\'' );

    $sql      = "SELECT * FROM `zidian` ORDER BY `CorrespondLetter` ASC";//从数据库里获得字典
    $result   = mysqli_query( $link , $sql );
    $percent1 = $percent2 = $percent3 = $percent4 = 0;
    while ($row = mysqli_fetch_row( $result ))
    {
        if ($percent1 < 97)
        {
            similar_text( $row[2] , $letter1_str , $percent1 );//与字典比较，获得两个字符串的相似度
            $result_arr1["$row[0]-$row[1]"] = $percent1;//然后储存到结果数组中
        }
        if ($percent2 < 97)
        {
            similar_text( $row[2] , $letter2_str , $percent2 );
            $result_arr2["$row[0]-$row[1]"] = $percent2;

        }
        if ($percent3 < 97)
        {
            similar_text( $row[2] , $letter3_str , $percent3 );
            $result_arr3["$row[0]-$row[1]"] = $percent3;

        }
        if ($percent4 < 97)
        {
            similar_text( $row[2] , $letter4_str , $percent4 );
            $result_arr4["$row[0]-$row[1]"] = $percent4;

        }

    }
    echo '<div class=\'result\'>';
    //遍历数组数组获取相似度最高的那个结果，
    $z   = 0;
    $str = '';
    foreach ($result_arr1 as $key => $percent)
    {
        if ($percent > $z)
        {
            $z   = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>';
//    echo $str.' ';

    $z   = 0;
    $str = '';
    foreach ($result_arr2 as $key => $percent)
    {
        if ($percent > $z)
        {
            $z   = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>';
//    echo $str.' ';

    $z   = 0;
    $str = '';
    foreach ($result_arr3 as $key => $percent)
    {
        if ($percent > $z)
        {
            $z   = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>';
//    echo $str.' ';

    $z   = 0;
    $str = '';
    foreach ($result_arr4 as $key => $percent)
    {
        if ($percent > $z)
        {
            $z   = $percent;
            $str = $key;
        }
    }
    echo $z . '***' . $str . '<br/>';
    echo '</div>';
//    echo $str.' ';

//    echo '<pre>';
//    print_r($result_arr1);

}

function show( $height , $width , $array , $letter1 , $letter2 , $letter3 , $letter4 )//展示切割后的结果和二值化后的数组
{
    for ($y = 0; $y < $height; ++$y)
    {
        for ($x = 0; $x < $width; ++$x)
        {
            if ($array[$y][$x] == 1)
            {
                echo '◆';
            }
            else
            {
                echo '◇';
            }
        }
        echo '<br/>';
    }
    echo '<br/>';
    echo '<hr/>';
    echo '<br/>';

    echo '<div>';
    for ($y = 0; isset( $letter1[$y][0] ); ++$y)
    {
        for ($x = 0; isset( $letter1[$y][$x] ); ++$x)
        {
            if ($letter1[$y][$x] == 1)
            {
                echo '◆';
            }
            else
            {
                echo '◇';
            }
        }
        echo '<br/>';
    }
    echo '</div>';

    echo '<div>';
    echo '<br/>';
    for ($y = 0; isset( $letter2[$y][0] ); ++$y)
    {
        for ($x = 0; isset( $letter2[$y][$x] ); ++$x)
        {
            if ($letter2[$y][$x] == 1)
            {
                echo '◆';
            }
            else
            {
                echo '◇';
            }
        }
        echo '<br/>';
    }
    echo '</div>';

    echo '<div>';
    echo '<br/>';
    for ($y = 0; isset( $letter3[$y][0] ); ++$y)
    {
        for ($x = 0; isset( $letter3[$y][$x] ); ++$x)
        {
            if ($letter3[$y][$x] == 1)
            {
                echo '◆';
            }
            else
            {
                echo '◇';
            }
        }
        echo '<br/>';
    }
    echo '</div>';

    echo '<div>';
    echo '<br/>';
    for ($y = 0; isset( $letter4[$y][0] ); ++$y)
    {
        for ($x = 0; isset( $letter4[$y][$x] ); ++$x)
        {
            if ($letter4[$y][$x] == 1)
            {
                echo '◆';
            }
            else
            {
                echo '◇';
            }
        }
        echo '<br/>';
    }
    echo '</div>';
    echo '<br/>';
}

function twoDimArrayToStr( $array )//将二维数组转为字符串
{
    $str = '';
    for ($y = 0; isset( $array[1][$y] ); ++$y)
    {
        for ($x = 0; isset( $array[$x][$y] ); ++$x)
        {
            $str .= $array[$x][$y];
        }
    }
    return $str;
}