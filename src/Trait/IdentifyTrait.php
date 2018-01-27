<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 23:08
 */

namespace CAPTCHAReader\src\Traits;


use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowColRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangColLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangColRepository;

trait IdentifyTrait
{
    use CommonTrait;

    //use CuttingTrait;

    /**
     * @param $label
     * @return IdentifyZhengFangColLevenshteinRepository|IdentifyZhengFangColRepository|IdentifyZhengFangRowColLevenshteinRepository|IdentifyZhengFangRowColRepository
     */
    public function getRepository( $label ){
        switch ($label) {
            case 'ZhengFangRowColLevenshtein':
                return new IdentifyZhengFangRowColLevenshteinRepository();
            case 'ZhengFangRowCol':
                return new IdentifyZhengFangRowColRepository();
            case 'ZhengFangColLevenshtein':
                return new IdentifyZhengFangColLevenshteinRepository();
            case 'ZhengFangCol':
                return new IdentifyZhengFangColRepository();
        }
    }

    /**
     * @param $dictionaryName
     * @return array|mixed
     */
    public function getDictionary(  $dictionaryName ){
        if (!is_file(__DIR__ . '/../Dictionary/' . $dictionaryName)) {
            return [];
        }
        $dictionary     = json_decode( file_get_contents( __DIR__ . '/../Dictionary/' . $dictionaryName ) , true );
        return $dictionary;
    }

    /**
     * @param array $twoDArray
     * @return string
     */
    public function twoD2oneDArrayRow( array $twoDArray ){
        $str = '';
        foreach($twoDArray as $row){
            foreach($row as $value){
                $str .= $value;
            }
        }
        return $str;
    }

    /**
     * @param array $twoDArray
     * @return string
     */
    public function twoD2oneDArrayCol( array $twoDArray ){
        $str       = '';
        $rowNumber = count( $twoDArray );
        $colNumber = count( $twoDArray[0] );

        for($x = 0; $x < $colNumber; ++$x){
            for($y = 0; $y < $rowNumber; ++$y){
                $str .= $twoDArray[$y][$x];
            }
        }
        return $str;
    }


}