## CAPTCHA_Reader
验证码识别与训练 框架

 ![](https://img.shields.io/packagist/l/doctrine/orm.svg)
 ![](https://img.shields.io/badge/php-%5E7.0.0-green.svg)


[EN](https://github.com/Kuri-su/CAPTCHA_Reader "EN" )

### 对各种验证码的支持现状
* `正方教务系统验证码` 已经完全支持，精简后的 验证码字典样本数 为 `500条` (不继续往上加是样本不够了。。。。)，不加上网络延时的运行用时在 `0.14s` - `0.2s` 之间，

`例示 :` ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/1508770737.png)

> 测试集测试的结果 》
>
> `200`个测试样本 中 整体识别正确率 `87%`，单个字母识别正确率率到 `96.5`
> 待补足样本后，会有更高的整体识别率

* `青果教务系统验证码` 正在支持中，请等待

`例示 :` ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/0.png)

* `天翼校园网认证验证码` 正在支持中

`例示 :` ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/1.png)

* `neea.edu.cn` 正在支持中，请等待 (这站巨烦。。。down验证码还要把之前的东西作为请求体。。。)

`例示 :` ![](https://github.com/Kuri-su/CAPTCHA_Reader/blob/oop/sample/a91518a87b984b1b88d3983178ec5cad.png)

## Examples
`待添加`

## How to use
`待添加`

## Update plan
* :heavy_check_mark: 更有效率的字典训练方法
* :heavy_check_mark: 使代码更加`oop`，更加可复用
* 增加多个字典和策略
* 增加对青果验证码的支持
* 打包为`composer`包，方便其他项目引用 :shell: `待支持了更多的验证码后会做，觉得只支持正方验证码做了没啥必要。。`
* 以`PHP`拓展的方式重写核心函数，降低核心函数的时间复杂度 :wrench: `现在突然又不太有性能瓶颈了，不过这个还是会做`
* 使用 `pthreads` 多线程识别 :wrench: `现在突然又不太有性能瓶颈了，不过这个还是会做`
