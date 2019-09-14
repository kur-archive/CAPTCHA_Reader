## online模式 调用代码段

```php
  <?php
    //!! 这里的 autoload.php 路径需要自己配置
    require_once(__DIR__ . '/../../vendor/autoload.php');
    use CAPTCHAReader\src\App\IndexController;

    $start_time = microtime(true);//运行时间开始计时

    $indexController = new IndexController();
    $res = $indexController->entrance('http://61.142.33.204/CheckCode.aspx','online', "ZhengFangNormal");
    dump($res);

    $end_time = microtime(true);//计时停止
    echo '执行时间为：' . ($end_time - $start_time) . ' s' . "<br/>\n";
```


## local模式 调用代码段

```php
<?php
  require_once(__DIR__ . '/../../vendor/autoload.php');
  use CAPTCHAReader\src\App\IndexController;

  $start_time = microtime(true);//运行时间开始计时

  $indexController = new IndexController();
  $res = $indexController->entrance(__DIR__ . '/../../sample/20003.png', 'local', "ZhengFangNormal");
  dump($res);

  $end_time = microtime(true);//计时停止
  echo '执行时间为：' . ($end_time - $start_time) . ' s' . "<br/>\n";
```
