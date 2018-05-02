<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/05/02
 * Time: 16:00
 */

/**
 * @param array ...$vars
 */
function dd(...$vars)
{
    foreach ($vars as $var) {
        dump($var);
    }
    exit();
}