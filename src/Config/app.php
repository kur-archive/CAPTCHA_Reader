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
    */

    'useGroup' => 'NeeaNormal' ,

    /*
    |--------------------------------------------------------------------------
    | componentGroup
    |--------------------------------------------------------------------------
    | 设定的组件模组
    */

    'componentGroup' => [
        'ZhengFangNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class ,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentZhengFang::class ,
                \CAPTCHAReader\src\App\Cutting\CuttingZhengFangFixed::class ,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangColLevenshtein::class ,
            ] ,
            'dictionary' => 'GetImageInfo-PretreatmentZhengFang-CuttingZhengFangFixed-IdentifyZhengFangColLevenshtein.json' ,
        ] ,
        'QinGuoNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class ,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentQinGuo::class ,
                \CAPTCHAReader\src\App\Cutting\CuttingQinGuo::class ,
                \CAPTCHAReader\src\App\Identify\IdentifyQinGuo::class ,
            ] ,
            'dictionary' => 'GetImageInfo-PretreatmentQinGuo-CuttingQinGuo-IdentifyQinGuo.json' ,
        ] ,
        'TianYiNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class ,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentTianYi::class ,
                \CAPTCHAReader\src\App\Cutting\CuttingZhengFangFixed::class ,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangColLevenshtein::class ,
            ] ,
            'dictionary' => 'GetImageInfo-PretreatmentZhengFang-CuttingZhengFangFixed-IdentifyZhengFangColLevenshtein.json' ,
        ] ,
        'NeeaNormal' => [
            'components' => [
                \CAPTCHAReader\src\App\GetImageInfo\GetImageInfo::class ,
                \CAPTCHAReader\src\App\Pretreatment\PretreatmentNeea::class ,
                \CAPTCHAReader\src\App\Cutting\CuttingNeea::class ,
                \CAPTCHAReader\src\App\Identify\IdentifyZhengFangColLevenshtein::class ,
            ] ,
            'dictionary' => 'GetImageInfo-PretreatmentZhengFang-CuttingZhengFangFixed-IdentifyZhengFangColLevenshtein.json' ,
        ] ,
    ] ,

    /*
    |--------------------------------------------------------------------------
    | note detail judge process
    |--------------------------------------------------------------------------
    |
    | 是否记录比较过程
    |
    */

    'noteDetailJudgeProcess' => true ,

];