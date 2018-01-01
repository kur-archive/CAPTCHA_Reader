<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/07
 * Time: 2:15
 */

namespace CAPTCHA_Reader\Training\MultipleTests;


use CAPTCHA_Reader\Training\MultipleTestsController;

class MultipleTests extends MultipleTestsController
{
    /**
     * 获取测试结果日志地址，
     * 测试完需要返回结果的准确率
     * 同时向日志地址记录结果的准确率和当前字典样本数，是否需要记录准确的日志，
     * 如果有传入测试结果日志地址，则将简略计入指定地址，如果需要记录完整的日志，则将地址后面加'.detail'，存入详细的字典，做好地址日志是否存在的判断
     * 以及增加测试用例的选择，可以选择某一套测试用例，也可以指定某一套测试用例，以及随机某一套测试用例
     *
     *
     * 部分共用的方法写到父类中
     * 给测试用例分组
     */

}