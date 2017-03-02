# PIN-Identify-by-fangzheng
##方正教务系统验证码识别
###目前字典大致为2200条左右，识别率基本站稳85%,基本可以确保请求三次之中一定有一次是识别正确的，内网环境下单次完成时间在1.5s-2s，外网环境下完成时间可能会在2.5-3s之间，
* PIN Identify by fangzheng.php 为主文件<br/>
* PIN Identify lib.php 为其函数库<br/>
* downloadImg.php 用于download验证码<br/>
* AddDictionary.php 用于添加字典<br/>
* zidian.sql 为字典，建一个叫'yzm'的数据库导入即可<br/>

[正确率测试 地址](http://kuri-su.cc/PIN/Identify_online.php "kuri-su.cc")<br/><br/>
[正确率批量测试 地址](http://kuri-su.cc/PIN/AccuracyTest.php "kuri-su.cc")<br/>
<font color='red'>批量测试设置了一次加载二十个方正验证码，大概等待时间在1min左右，而且可能会因为请求不到验证码而中断</font>
<br/><br/>
[kuri-su.cc](http://kuri-su.cc "kuri-su.cc")
