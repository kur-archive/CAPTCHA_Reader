<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/15
 * Time: 0:58
 */

namespace CAPTCHAReader\src\Log;


class Log
{
    public function log(string $level, $content)
    {
        if (is_array($content)) {

        } elseif (is_string($content) || is_int($content)) {

        }


    }


    public function writeMultipleTestsLog()
    {

    }

    public function writeAddSamplesAutoLog()
    {
        
    }

}