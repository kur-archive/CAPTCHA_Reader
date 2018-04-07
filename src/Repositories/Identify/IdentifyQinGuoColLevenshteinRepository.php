<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/14
 * Time: 15:39
 */

namespace CAPTCHAReader\src\Repository\Identify;


use CAPTCHAReader\src\App\ResultContainer;

class IdentifyQinGuoColLevenshteinRepository
{
    /**
     * @param $oneDChar
     * @param $dictionary
     * @param ResultContainer $resultContainer
     * @return mixed
     */
    public function getHighestSimilarityResultNoteDetail($oneDChar, $dictionary, ResultContainer $resultContainer)
    {
        $nowBest = [
            'score' => 255,
            'char'  => null,
        ];
        foreach ($dictionary as $key => $sample) {
            $percent = levenshtein($oneDChar, $sample['rowStr']);
            $flag = 0;
            if ($percent < $nowBest['score']) {
                $nowBest['score'] = $percent;
                $nowBest['char'] = $sample['char'];
                $flag = 1;
            }
            $judge = [
                'percent'      => $percent,
                'char'         => $sample['char'],
                'sampleRowStr' => $sample['rowStr'],
                'oneDChar'     => $oneDChar,
                'upScore'      => $flag ? true : false,
            ];
            $resultContainer->setJudgeDetails($key, $judge);

            if ($nowBest['score'] < 2) {
                break;
            }
        }
        $resultContainer->setResultArr($nowBest);

        return $nowBest['char'];
    }

    /**
     * @param $oneDChar
     * @param $dictionary
     * @return mixed
     */
    public function getHighestSimilarityResult($oneDChar, $dictionary)
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