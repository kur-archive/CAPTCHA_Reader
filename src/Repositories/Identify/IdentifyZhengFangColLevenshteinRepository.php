<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 15:39
 */

namespace CAPTCHAReader\src\Repository\Identify;


use CAPTCHAReader\src\App\ResultContainer;

class IdentifyZhengFangColLevenshteinRepository
{
    public function getHighestSimilarityResultLevenshtein($oneDChar, $dictionary, ResultContainer $resultContainer)
    {
        $nowBest = [
            'score' => 255,
            'char'  => null,
        ];
        foreach ($dictionary as $key => $sample) {
            $percent = levenshtein($oneDChar, $sample['rowStr']);

            if ($percent < $nowBest['score']) {
                $nowBest['score'] = $percent;
                $nowBest['char'] = $sample['char'];
            }

            if ($nowBest['score'] < 2) {
                break;
            }
        }

        return $nowBest['char'];
    }

}