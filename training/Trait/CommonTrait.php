<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/22
 * Time: 0:45
 */

namespace CAPTCHAReader\training\Traits;

use CAPTCHAReader\src\App\IndexController;
use \CAPTCHAReader\src\Traits\CommonTrait as SrcCommon;
use CAPTCHAReader\src\Traits\IdentifyTrait;

trait CommonTrait
{
    use SrcCommon, IdentifyTrait;

    /**
     * @param $groupName
     * @return array
     */
    public function getStudySampleList($groupName)
    {
        $trainingConf = $this->getConfig('training');
        $sampleDir = $trainingConf['studySampleGroup'][$groupName];
        $sampleList = $this->getDirAllFile($sampleDir);

        //randomMessingArr 随机打乱数组
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
    public function getDirAllFile($dirPath, $fileList = [])
    {
        $dir = dir($dirPath);
        while ($file = $dir->read()) {
            if ((is_dir($dirPath . $file)) && ($file != ".") && ($file != "..")) {
                $fileListTMP = $this->getDirAllFile($dirPath . $file, $fileList);
                array_merge($fileListTMP, $fileList);
            } else {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                array_push($fileList, $dirPath . $file);
            }
        }
        $dir->close();
        return $fileList;
    }

    /**
     * @param array $myArray
     * @return array
     */
    public function custom_shuffle($myArray = [])
    {
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

    public function addSampleToDictionary($char, $rowStr,IndexController $indexController)
    {
        $conf = $indexController->getConf();
        $dictionaryName = $conf['componentGroup'][$conf['useGroup']]['dictionary'];
        $dictionary = $this->getDictionary($dictionaryName);

        $dictionary[] = [
            'char' => $char,
            'rowStr'  => $rowStr,
        ];
        file_put_contents(__DIR__ . '/../../src/Dictionary/' . $dictionaryName, json_encode($dictionary));
    }

    public function getDictionarySampleCount(IndexController $indexController)
    {
        $conf = $indexController->getConf();
        $dictionaryName = $conf['componentGroup'][$conf['useGroup']]['dictionary'];
        $dictionary = $this->getDictionary($dictionaryName);
        return count($dictionary);

        
    }


}