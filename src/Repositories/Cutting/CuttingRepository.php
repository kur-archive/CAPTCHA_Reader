<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:15
 */

namespace CAPTCHAReader\src\Repository\Cutting;


class CuttingRepository
{
    /**
     * CuttingRepository constructor.
     * 此处作为一个调用器，用__call 去调用类对应的 repository
     */
    public function __construct(){
        //根据需要创建类

    }



    public function __call( $name , $arguments ){
        // TODO: Implement __call() method.
    }


}