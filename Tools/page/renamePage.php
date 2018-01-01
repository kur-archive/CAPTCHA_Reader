<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/08
 * Time: 2:16
 */

require '../../vendor/autoload.php';
$path   = __DIR__ . '/../../Config/training.php';
$config = require($path);
//$fileDir = $config['studySamples']['dir'] . $config['studySampleCollectionName'] . "\\";
$fileDir = 'D:\code\DEMO\CAPTCHA_Idenify\new\d2\StudySamples\\';


$arr = dirTraverse( $fileDir );


$number  = 0;
$fileArr = [];
foreach($arr as $value)
{
    if ($number == 100)
    {
        break;
    }
    if (strlen( explode( '.' , $value )[0] ) > 4)
    {
        array_push( $fileArr , $value );
        ++$number;
    }
}

if (isset( $_POST['submit'] ))
{
    $oldNameArr = json_decode( $_POST['oldname'] );
    foreach($oldNameArr as $key => $oldname)
    {
        if (empty( $_POST['newName'][$key] ))
        {
            continue;
        }
//        rename( $fileDir . $oldname , $fileDir . $config['studySampleCollectionName'].'\\'.$_POST['newName'][$key] . '.png' );
        rename( $fileDir . $oldname , $fileDir . $config['studySampleCollectionName'] . '\\' . $_POST['newName'][$key] . '.png' );
    }
    //有回传就修改文件名
    header( 'Location: http://code.cc/DEMO/CAPTCHA_Idenify/new/d2/tools/page/renamePage.php' );
}
else
{

    //没有回传就渲染页面
    echo "<form method='post' action='renamePage.php'>";

    echo "<input type='text' hidden name='oldname' value='" . json_encode( $fileArr ) . "'/>";
    foreach($fileArr as $key => $file)
    {
        echo "<div style='float: left;margin-right: 20px;'>";
        echo "<img src='http://code.cc\\" . str_replace( 'D:\code\\' , '' , $fileDir ) . $file . "' style='width:300px;'>";
        echo "<span>$file</span>";
        echo "<input type='text' name='newName[$key]' class='imgInput' style='height: 40px;border: 1px solid #ccc;font-size: 130%;width: 100px;padding-left: 10px;'>";
        echo "</div>";
    }
    echo "<input type='submit' name='submit' value='submit' style='width: 150px;height:50px;background: #333333;border:none;color: #fff;font-size: 150%;letter-spacing: 3px;'>";

    echo " </form>";

}


//foreach($arr as $value)
//{
//
//}


/**
 * @param $directory
 * @param array $simplesArr
 * @return array
 */
function dirTraverse( $directory , $simplesArr = [] )
{
    $dir = dir( $directory );
    while ($file = $dir->read())
    {
        if ((is_dir( $directory . $file )) && ($file != ".") && ($file != ".."))
        {
            $fileListTMP = dirTraverse( $directory . $file , $simplesArr );
            array_merge( $fileListTMP , $simplesArr );
        }
        else
        {
            if ($file === '.' || $file === '..')
            {
                continue;
            }
            array_push( $simplesArr , $file );
        }
    }
    $dir->close();
    return $simplesArr;
}