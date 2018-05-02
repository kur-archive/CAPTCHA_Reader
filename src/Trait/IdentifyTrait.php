<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 23:08
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\App\Identify\IdentifyNeea;
use CAPTCHAReader\src\Repository\Identify\IdentifyNeeaColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyNeeaColRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyQinGuoColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyQinGuoColRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyTianYiColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyTianYiColRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangColRepository;

trait IdentifyTrait
{
    use CommonTrait;

    //use CuttingTrait;

    /**
     * @param $label
     * @return IdentifyNeeaColLevenshteinRepository|IdentifyNeeaColRepository|IdentifyQinGuoColLevenshteinRepository|IdentifyQinGuoColRepository|IdentifyTianYiColLevenshteinRepository|IdentifyTianYiColRepository|IdentifyZhengFangColLevenshteinRepository|IdentifyZhengFangColRepository
     */
    public function getRepository($label)
    {
        switch ($label) {
            case 'ZhengFangColLevenshtein':
                return new IdentifyZhengFangColLevenshteinRepository();
            case 'ZhengFangCol':
                return new IdentifyZhengFangColRepository();
            case 'QinGuo':
                return new IdentifyQinGuoColRepository();
            case 'QinGuoLevenshtein':
                return new IdentifyQinGuoColLevenshteinRepository();
            case 'Neea':
                return new IdentifyNeeaColRepository();
            case 'NeeaLevenshtein':
                return new IdentifyNeeaColLevenshteinRepository();
            case 'TianYi':
                return new IdentifyTianYiColRepository();
            case 'TianYiLevenshtein':
                return new IdentifyTianYiColLevenshteinRepository();
        }
    }

    /**
     * @param $dictionaryName
     * @return array|mixed
     */
    public function getDictionary($dictionaryName)
    {
        if (!is_file(__DIR__ . '/../Dictionary/' . $dictionaryName)) {
            return [];
        }
        $dictionary = json_decode(file_get_contents(__DIR__ . '/../Dictionary/' . $dictionaryName), true);
        return $dictionary;
    }

    /**
     * @param array $twoDArray
     * @return string
     */
    public function twoD2oneDArrayRow(array $twoDArray)
    {
        $str = '';
        foreach ($twoDArray as $row) {
            foreach ($row as $value) {
                $str .= $value;
            }
        }
        return $str;
    }

    /**
     * @param array $twoDArray
     * @return string
     */
    public function twoD2oneDArrayCol(array $twoDArray)
    {
        $str = '';
        $rowNumber = count($twoDArray);
        $colNumber = count($twoDArray[0]);

        for ($x = 0; $x < $colNumber; ++$x) {
            for ($y = 0; $y < $rowNumber; ++$y) {
                $str .= $twoDArray[$y][$x];
            }
        }
        return $str;
    }


}