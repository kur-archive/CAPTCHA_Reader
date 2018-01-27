<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/22
 * Time: 2:15
 */

namespace CAPTCHAReader\training\Abstracts;


use CAPTCHAReader\src\App\IndexController;

interface TestsInterface
{
    /**
     * @param $groupName
     * @param IndexController $indexController
     * @param null $trainingId
     * @param null $area
     * @return mixed
     */
    public function run($groupName, IndexController $indexController, $trainingId = null,$area=null);
}