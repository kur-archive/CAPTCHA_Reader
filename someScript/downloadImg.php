<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/02/18
 * Time: 10:01
 */
$start_time = microtime(true);
$url = "http://117.141.122.156:10252/jwweb/sys/ValidateCode.aspx";

for ($i = 0; $i < 500; $i++) {
    $save_to = $i.'.png';
    $content = file_get_contents($url);
    $save=file_put_contents($save_to, $content);
    if (!$save) {
        echo 'error';
    }
}

$end_time = microtime(true);
echo '执行时间为：'.($end_time-$start_time).' s';
?>
