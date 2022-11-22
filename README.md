# CAPTCHA_Reader 验证码识别与训练 脚手架

![](https://img.shields.io/badge/license-WTFPL-blue.svg?longCache=true&style=flat-square)
![](https://img.shields.io/badge/php-~7.0.0-green.svg?longCache=true&style=flat-square)
![](https://img.shields.io/badge/Composer-MUST！-red.svg?longCache=true&style=flat-square)




这个项目对验证码识别中常用的 `四个步骤`（**获取文件 => 降噪 => 切割 => 识别**）进行了简单的封装，减少开发的复杂程度。并提供了一些现成的解决方案。

[![digitalCoin](https://newbie.zeromesh.net/donate.7.6.svg)](https://donate.cafe/kurisu) 来个甜筒 🍦 吧~www ;)


### Install use Composer 

```bash
$ composer require kurisu/captcha_reader

#该库依赖 php-gb 扩展
# 如果是 linux 版本, php 可能未安装 php-gb
$ sudo apt install php-gb -y #自行选择合适的安装方式
```

## 对各种验证码的支持

* **正方教务系统验证码**  验证码字典样本数为`500 条` ，不加上网络延时的耗时在`0.14s - 0.2s`之间
  * 测试集测试的结果：
  * `200`个测试样本 中 整体识别正确率 `87%`，单个字母识别正确率到 `96.5%`
  * ![](docs/sample/zhengfang.png)

* **青果教务系统验证码** 验证码字典样本数为`200 条左右`，不加上网络延时的耗时在 `0.15s - 0.25s`之间  
  * 测试集测试的结果：
  * `200`个测试样本 中 整体识别正确率 `90%`，单个字母识别正确率到 `96.875%`
  * ![](docs/sample/qinguo.png)

* **neea.edu.cn** 验证码字典样本数为 `1500 条左右`，不加上网络延时的耗时在 `0.6s - 0.7s`之间
  * 测试集测试的结果：
  * `200`个测试样本 中 整体识别正确率 `54.5%`，单个字母识别正确率到 `80%`
  * ![](docs/sample/neeaA.png) &nbsp; ![](docs/sample/neeaB.png) &nbsp; ![](docs/sample/neeaC.png)

* **天翼校园网认证验证码** 验证码字典样本数为 `2800 条左右`，不加上网络延时的耗时在 `0.45s - 0.5s`之间
  * 测试集测试的结果：
  * `200`个测试样本 中 整体识别正确率 `48.5%`，单个字母识别正确率到 `82.875%`
  * ![](docs/sample/tianyi.png)

## Examples

在线测试效果待添加，可以尝试根据下面的 `Get Started Now` 测试效果

## Get Started Now

### Online 在线

* 运行指令 `git clone https://github.com/Kuri-su/CAPTCHA_Reader.git`
* 在根目录下执行 `composer update`
* 不用管 `sample`,`training`,`vendor`文件夹, 直接进入 `src/App/index.php` 下，仿照该文件的调用方式即可，例示代码段如下：

![](docs/img/onlineCode.png)

需要复制可以跳转到 [link](explore.md)

* 运行结果  
![](docs/runRes.png)

### Local 本地

在识别本地的验证码的模式，代码与上面 Online 模式相似，只需要调用 `entrance`方法的时候第二个参数传 `local` 即可，例示代码段如下：

![](docs/img/localCode.png)

需要复制可以跳转到 [link](explore.md)

-----

## How To Use

### 识别部分

#### 切换识别方案

在调用时, 传递的第三个参数指定你需要使用的方案组即可, 可用的方案组参考 `/src/Config/app.php` 文件的 componentGroup 数组的键

```php
<?php
// ZhengFangNormal
// QinGuoNormal
// TianYiNormal
// NeeaNormal
$c = $a->entrance('https://raw.githubusercontent.com/Kuri-su/CAPTCHA_Reader/master/docs/sample/qinguo.png', 'online','QinGuoNormal');
```

#### 切换识别方案中使用的类

继承 `CAPTCHAReader\src\App\Abstracts\Load` 抽象类，实现相应的方法，完成装饰器的构建，然后替换配置文件中的组件类即可。

![](docs/img/config.png)

#### 替换字典

修改配置文件中相应方案的`dictionary`的值即可

### 训练部分

![](docs/img/training.png)

配置文件中的 `studyGroup` 下的 键名 对应使用的 `学习样本组` 和 `测试样本组` ，然后下面的四个类是使用的组件类。

## 样本集 & 测试集

**已标记 学习样本集**
* 正方 [link](https://github.com/Kurisu-A/CAPTCHA_Reader_samples/blob/master/sample/StudySamples/ZhengFang/ZhengFang.zip)
* 青果 [link](https://github.com/Kurisu-A/CAPTCHA_Reader_samples/blob/master/sample/StudySamples/QinGuo/QinGuo.zip)
* neea [link](https://github.com/Kurisu-A/CAPTCHA_Reader_samples/blob/master/sample/StudySamples/neea.edu.cn/neea.edu.cn.zip)
* 天翼 [link](https://github.com/Kurisu-A/CAPTCHA_Reader_samples/blob/master/sample/StudySamples/TianYi/TianYi.zip)

**已标记 测试样本集**
* **正方** & **青果** & **neea** & **天翼** 已打包 [link](https://github.com/Kurisu-A/CAPTCHA_Reader_samples/blob/master/sample/TestSamples/TestSamples.zip)

## 目录结构

![](docs/img/directory.png)

---

## Update plan

* :heavy_check_mark: 更有效率的字典训练方法
* :heavy_check_mark: 使代码更加`oop`，更加可复用
* :heavy_check_mark: 增加对青果验证码的支持
* :heavy_check_mark: 增加对 `neea` 的支持
* :heavy_check_mark: 增加对 `天翼校园网认证验证码` 的支持
* :heavy_check_mark: 打包为`composer`包，方便其他项目引用
* :soon: ~~补全文档~~
* :clock1230: ~~以`PHP`拓展的方式重写核心函数，降低核心函数的时间复杂度~~
* :clock1230: ~~使用 `pthreads` 多线程识别~~
* ~~做完上面几种验证码的就结束支持，挨个支持各个验证码太累了，影响效率的是标记各个验证码，每种最少要标记将近 1000 个,如果有相关需求的请关注其他神经网络方案(资源消耗并不会多出多少)。~~
* ~~真是个倒霉孩子写这东西，打码工人累死了~~

[![Stargazers over time](https://starchart.cc/Kuri-su/CAPTCHA_Reader.svg)](https://starchart.cc/Kuri-su/CAPTCHA_Reader)

## LICENSE

**WTFPL**
