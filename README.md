## CAPTCHA_Reader
验证码识别与训练 脚手架

![](https://img.shields.io/packagist/l/doctrine/orm.svg)
![](https://img.shields.io/badge/php-%5E7.0.0-green.svg)

> 如果需要最开始的那个php脚本的版本，点这个链接 [master branch](https://github.com/Kuri-su/CAPTCHA_Reader/tree/master)

```python
  # [EN](https:// "EN" )
```

## 对 各种验证码 的支持现状

* **正方教务系统验证码** 已支持，验证码字典样本数为`500条` ，不加上网络延时的耗时在`0.14s - 0.2s`之间，
  例示 : ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/1508770737.png)
  > 测试集测试的结果
  >
  > `200`个测试样本 中 整体识别正确率 `87%`，单个字母识别正确率率到 `96.5%`

* **青果教务系统验证码** 已支持，验证码字典样本数为`200条左右`，不加上网络延时的耗时在 `0.15s - 0.25s`之间
  例示 : ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/0.png)
  > 测试集测试的结果
  >
  > `200`个测试样本 中 整体识别正确率 `90%`，单个字母识别正确率率到 `96.875%`

* **天翼校园网认证验证码** 待支持中
  例示 : ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/1.png)

* **neea.edu.cn** 待支持中
  例示 : ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/a91518a87b984b1b88d3983178ec5cad.png)

## Examples
`待添加`

## How to use
* Get Started Now
    * 执行 `git clone https://github.com/Kuri-su/CAPTCHA_Reader.git`
    * 在根目录下执行`composer update` 
    * 不用管 `sample`,`training`,`vendor`文件夹, 直接进入 `src/App/index.php` 下，仿照该文件的调用方式即可，例示代码段如下
    ```php
    <?php
    use CAPTCHAReader\src\App\IndexController;
    
    $indexController = new IndexController();  
    $res = $indexController->entrance(
      'http://61.142.33.204/CheckCode.aspx',
      'online');
    ```
    输出例示如下：
    
        qacd
    > 会直接输出结果

* Develop
  * `待添加`

## Update plan

* :heavy_check_mark: 更有效率的字典训练方法
* :heavy_check_mark: 使代码更加`oop`，更加可复用
* :soon: 增加对青果验证码的支持 
* :soon: 打包为`composer`包，方便其他项目引用 
* **补全文档**
* :clock1230: ~~以`PHP`拓展的方式重写核心函数，降低核心函数的时间复杂度  ~~
* :clock1230: ~~使用 `pthreads` 多线程识别 ~~
* 做完上面几种验证码的支持就结束，挨个支持各个验证码太累了，影响效率的是标记各个验证码，每种最少要标记将近1000个,如果有相关需求的请关注其他神经网络方案(资源消耗并不会多出多少)。
* ~~真是个倒霉孩子把这东西当毕业设计，打码工人累死了~~

## LICENSE
**MIT**