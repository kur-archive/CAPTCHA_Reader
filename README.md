# PIN-Identify-by-fangzheng
##方正教务系统验证码识别
###目前字典大致为2200条左右，识别率基本站稳85%,可以确保请求三次之中一定有一次是识别正确的，内网环境下单次完成时间在1.5s-2s，外网环境下完成时间可能会在2.5-3s之间，
* PIN Identify by fangzheng.php 为主文件<br/>
* PIN Identify lib.php 为其函数库<br/>
* downloadImg.php 用于download验证码<br/>
* AddDictionary.php 用于添加字典<br/>
* zidian.sql 为字典，建一个叫'yzm'的数据库导入即可<br/>

[正确率测试 地址](http://kuri-su.cc/PIN/Identify_online.php "kuri-su.cc")<br/><br/>
[正确率批量测试 地址](http://kuri-su.cc/PIN/AccuracyTest.php "kuri-su.cc")<br/>
<font color='red'>批量测试设置了一次加载五个方正验证码，大概等待时间在17s左右，而且可能会因为请求不到验证码而中断</font>
<br/><br/>
[kuri-su.cc](http://kuri-su.cc "kuri-su.cc")

<hr/>

##There are 2,300 records in the current dictionary，Recognition rate of about 85%，You can make sure that the request is sure to be correct once，The single recognition run time may be between 2.5-3s。

* PIN Identify by fangzheng.php is main file<br/>
* PIN Identify lib.php is function lib<br/>
* downloadImg.php Used to download the verification code<br/>
* AddDictionary.php Used to add a dictionary<br/>
* zidian.sql Is a dictionary，Build a database called 'yzm' and imported<br/>

[to Accuracy test](http://kuri-su.cc/PIN/Identify_online.php "kuri-su.cc")<br/><br/>
[to Batch test accuracy](http://kuri-su.cc/PIN/AccuracyTest.php "kuri-su.cc")<br/>
<font color='red'>Batch test set a load of five Founder verification code, probably waiting for about 17s time, and may not be required because the request code is interrupted</font>
<br/><br/>
[kuri-su.cc](http://kuri-su.cc "kuri-su.cc")
