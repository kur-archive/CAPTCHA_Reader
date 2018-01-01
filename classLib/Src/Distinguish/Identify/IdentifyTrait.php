<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:25
 */

namespace CAPTCHA_Reader\Distinguish\Identify;

use CAPTCHA_Reader\CommonTrait;

trait IdentifyTrait
{
    use CommonTrait;

    public function getDictionary()
    : array
    {
        $path       = $this->getDictionaryPath();
        $dictionary = json_decode( file_get_contents( $path ) );
        self::dd( $dictionary );
        return $dictionary;
    }

}