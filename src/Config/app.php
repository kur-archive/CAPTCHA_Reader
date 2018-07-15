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
    | useGroup
    |--------------------------------------------------------------------------
    |
    | 当前使用的模组名
    |
    | 这里的方案名和下面的是一一对应的
    |
    */

    'useGroup' => 'TianYiNormal',

    /*
    |--------------------------------------------------------------------------
    | componentGroup
    |--------------------------------------------------------------------------
    | 设定的组件模组
    | components组件的顺序是 获取 图片类-降噪类-切割类-识别类
    | dictionary 对应的是使用的字典
    */

    'componentGroup' => [
        'ZhengFangNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentZhengFang::class,
                \CAPTCHAReader\src\App\Cutting\CuttingZhengFangFixed::class,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangColLevenshtein::class,
            ],
            'dictionary' => 'GetImageInfo-PretreatmentZhengFang-CuttingZhengFangFixed-IdentifyZhengFangColLevenshtein.json',
        ],
        'QinGuoNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentQinGuoShrink::class,
                \CAPTCHAReader\src\App\Cutting\CuttingQinGuoShrink::class,
                \CAPTCHAReader\src\App\Identify\IdentifyQinGuoLevenshtein::class,
            ],
            'dictionary' => 'GetImageInfo-PretreatmentQinGuoShrink-CuttingQinGuoShrink-IdentifyQinGuoLevenshtein.json',
        ],
        'TianYiNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentTianYShrink::class,
                \CAPTCHAReader\src\App\Cutting\CuttingTianYiShrink::class,
                \CAPTCHAReader\src\App\Identify\IdentifyTianYiLevenshtein::class,
            ],
            'dictionary' => 'GetImageInfo-PretreatmentTianYShrink-CuttingTianYiShrink-IdentifyTianYiLevenshtein.json',
        ],
        'NeeaNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentNeea::class,
                \CAPTCHAReader\src\App\Cutting\CuttingNeeaFixed::class,
                \CAPTCHAReader\src\App\Identify\IdentifyNeeaLevenshtein::class,
            ],
            'dictionary' => 'GetImageInfo-PretreatmentNeea-CuttingNeeaFixed-IdentifyNeeaLevenshtein.json',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | note detail judge process
    |--------------------------------------------------------------------------
    |
    | 是否记录比较过程
    |
    */

    'noteDetailJudgeProcess' => true,

    /*
    |--------------------------------------------------------------------------
    | unlinkImg
    |--------------------------------------------------------------------------
    |
    | 是否需要清理掉下载的网络验证码
    |
    */

    'unlinkImg' => true
];