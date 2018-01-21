<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/22
 * Time: 0:45
 */

namespace CAPTCHAReader\training\Traits;

use \CAPTCHAReader\src\Traits\CommonTrait as SrcCommon;

trait CommonTrait
{
    use SrcCommon;

    public function getStudySampleList($groupName)
    {
        $trainingConf = $this->getConfig('training');
        $sampleDir = $trainingConf['studySampleGroup'][$groupName];
        $sampleList = $this->getDictionaryAllFile($sampleDir);
        //randomMessingArr
        $sampleList = $this->custom_shuffle($sampleList);

        return $sampleList;
    }

    public function getTestSampleList()
    {

    }


    /**
     * @param $dirPath
     * @param array $fileList
     * @return array
     */
    public function getDictionaryAllFile($dirPath, $fileList = [])
    {
        $dir = dir($dirPath);
        while ($file = $dir->read()) {
            if ((is_dir($dirPath . $file)) && ($file != ".") && ($file != "..")) {
                $fileListTMP = $this->getDictionaryAllFile($dirPath . $file, $fileList);
                array_merge($fileListTMP, $fileList);
            } else {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                array_push($fileList, $file);
            }
        }
        $dir->close();
        return $fileList;
    }

    /**
     * @param array $myArray
     * @return array
     */
    public function custom_shuffle($myArray = []) {
        $copy = [];
        while (count($myArray)) {
            $element = array_rand($myArray);
            $copy[$element] = $myArray[$element];
            unset($myArray[$element]);
        }
        return $copy;
    }

    /**
     * @param $number
     * @return string
     */
    public function getRandomHexStr($number)
    {
        $str = '';
        for ($i = 0; $i < $number; ++$i) {
            $str .= dechex(rand(0, 15));
        }
        return $str;
    }


}