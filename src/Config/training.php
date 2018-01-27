<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:36
 */

return [

    /*
    |--------------------------------------------------------------------------
    | studyGroup
    |--------------------------------------------------------------------------
    |
    | 需要学习的组和使用的各个组件
    |
    */

    'studyGroup' => [
        'zhengfang' => [
            [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentZhengFang::class,
                \CAPTCHAReader\src\App\Cutting\CuttingZhengFangFixed::class,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangColLevenshtein::class,
            ],
            [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentZhengFang::class,
                \CAPTCHAReader\src\App\Cutting\CuttingZhengFangFixed::class,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangCol::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | studySampleGroup
    |--------------------------------------------------------------------------
    |
    | 学习 样本组
    |
    */

    'studySampleGroup' => [
        'neea.edu.cn' => __DIR__ . '/../../sample/StudySamples/neea.edu.cn/',
        'qinguo'      => __DIR__ . '/../../sample/StudySamples/qinguo/',
        'tianyi'      => __DIR__ . '/../../sample/StudySamples/tianyi/',
        'zhengfang'   => __DIR__ . '/../../sample/StudySamples/zhengfang/',
    ],

    /*
    |--------------------------------------------------------------------------
    | testSampleGroup
    |--------------------------------------------------------------------------
    |
    | 测试 样本组
    |
    */
    'testSampleGroup'  => [
        'neea.edu.cn' => __DIR__ . '/../../sample/TestSamples/neea.edu.cn/',
        'qinguo'      => __DIR__ . '/../../sample/TestSamples/qinguo/',
        'tianyi'      => __DIR__ . '/../../sample/TestSamples/tianyi/',
        'zhengfang'   => __DIR__ . '/../../sample/TestSamples/zhengfang/',
    ],

    /*
    |--------------------------------------------------------------------------
    | LogPath
    |--------------------------------------------------------------------------
    | 日志位置
    */

    'LogPath' => __DIR__.'/../Log/' ,

    /*
    |--------------------------------------------------------------------------
    | dictionary Sample Limit by automaticStudy
    |--------------------------------------------------------------------------
    | 自动训练的字典数量上限
    */

    'dictionarySampleLimit' => 4000 ,

    /*
    |--------------------------------------------------------------------------
    | test Success Rate Line by automaticStudy
    |--------------------------------------------------------------------------
    | 自动训练到达触发的测试成功率线
    */

    'testSuccessRateLine' => 85 ,

];