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
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowLevenshteinRepository;
use CAPTCHAReader\src\Repository\Identify\IdentifyZhengFangRowRepository;

trait IdentifyTrait
{
    use CommonTrait;

    public function getRepository( $label ){
        switch ($label) {
            case 'ZhengFangRowColLevenshtein':
                return new IdentifyZhengFangRowColLevenshteinRepository();
            case 'ZhengFangRowCol':
                return new IdentifyZhengFangRowColRepository();
            case 'ZhengFangRowLevenshtein':
                return new IdentifyZhengFangRowLevenshteinRepository();
            case 'ZhengFangRow':
                return new IdentifyZhengFangRowRepository();
        }
    }

    public function getDictionary( array $componentGroup ){
        $dictionaryName = $componentGroup['dictionary'];
        $dictionary     = json_decode( file_get_contents( __DIR__ . '/../Dictionary/' . $dictionaryName ) );
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