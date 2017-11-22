<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:25
 */
namespace CAPTCHA_Reader\Identify;

interface IdentifyInterface
{
    /**
     * @return mixed
     */
    public function getResult(array $charArr);
}