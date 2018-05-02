<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/22
 * Time: 0:45
 */

namespace CAPTCHAReader\training\Traits;

use CAPTCHAReader\src\App\IndexController;
use CAPTCHAReader\src\Traits\CommonTrait as SrcCommon;

trait CommonTrait
{
    use SrcCommon;

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

    /**
     * @param $groupName
     * @param null $area
     * @return array
     * @throws \Exception
     */
    public function getTestSampleList($groupName, $area = null)
    {
        $trainingConf = $this->getConfig('training');
        $sampleDir = $trainingConf['testSampleGroup'][$groupName];
        $testSets = $this->getTestSet($sampleDir);

        if (empty($testSets)) {
            throw new \Exception('Test set is empty');
        }

        if ($area == 'all') {
            foreach ($testSets as $testSet) {
                $testSetArr = $this->getDirAllFile($sampleDir . $testSet . '/');
                $sampleList[$testSet] = $this->custom_shuffle($testSetArr);
            }
            return $sampleList;

        } elseif (is_string($area) || is_int($area)) {
            if (!in_array($area, $testSets)) {
                throw new \Exception('Invalid test set name passed in');
            }
            $testSetArr = $this->getDirAllFile($sampleDir . $area . '/');
            $sampleList[] = $this->custom_shuffle($testSetArr);
            return $sampleList;

        } elseif (is_array($area)) {
            foreach ($area as $testSet) {
                if (!in_array($area, $testSets)) {
                    throw new \Exception('Invalid test set name passed in');
                }
                $testSetArr = $this->getDirAllFile($sampleDir . $testSet . '/');
                $sampleList[$testSet] = $this->custom_shuffle($testSetArr);
            }
            return $sampleList;

        } elseif ($area == null) {
            foreach ($testSets as $testSet) {
                $testSetArr = $this->getDirAllFile($sampleDir . $testSet . '/');
                $sampleList[] = $this->custom_shuffle($testSetArr);
                return $sampleList;
            }
        }

    }

    /**
     * @param $dirPath
     * @return array
     */
    public function getTestSet($dirPath)
    {
        $fileList = scandir($dirPath);
        array_splice($fileList, 0, 2);
        return $fileList;
    }

    /**
     * @param $dirPath
     * @param array $fileList
     * @return array
     */
    public function getDirAllFile($dirPath)
    {
        $fileList = scandir($dirPath);
        $fileListTmp = [];
        array_splice($fileList, 0, 2);
        foreach ($fileList as $key => $fileName) {
            if (is_dir($dirPath . $fileName)) {
                unset($fileList[$key]);
                $fileListTmp_ = $this->getDirAllFile($dirPath . $fileName.'/');
                $fileListTmp = array_merge($fileListTmp, $fileListTmp_);
            } else {
                if (preg_match('/(jpeg|jpg|png|gif)/',$fileName)) {
                    $fileList[$key] = $dirPath . $fileName;
//                    $fileList[$key] =  $fileName;//tmp
                }else{
                    unset($fileList[$key]);
                }
            }
        }
        $fileList = array_merge($fileList, $fileListTmp);
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

    /**
     * @param $dictionaryName
     * @return array|mixed
     */
    public function getDictionary($dictionaryName)
    {
        if (!is_file(__DIR__ . '/../../src/Dictionary/' . $dictionaryName)) {
            return [];
        }
        $dictionary = json_decode(file_get_contents(__DIR__ . '/../../src/Dictionary/' . $dictionaryName), true);
        return $dictionary;
    }

    /**
     * @param $char
     * @param $rowStr
     * @param IndexController $indexController
     */
    public function addSampleToDictionary($char, $rowStr, IndexController $indexController)
    {
        $conf = $indexController->getConf();
        $dictionaryName = $conf['componentGroup'][$conf['useGroup']]['dictionary'];
        $dictionary = $this->getDictionary($dictionaryName);

        $dictionary[] = [
            'char'   => $char,
            'rowStr' => $rowStr,
        ];
        file_put_contents(__DIR__ . '/../../src/Dictionary/' . $dictionaryName, json_encode($dictionary));
    }

    /**
     * @param IndexController $indexController
     * @return int
     */
    public function getDictionarySampleCount(IndexController $indexController)
    {
        $conf = $indexController->getConf();
        $dictionaryName = $conf['componentGroup'][$conf['useGroup']]['dictionary'];
        $dictionary = $this->getDictionary($dictionaryName);
        return count($dictionary);


    }


}