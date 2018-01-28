<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/15
 * Time: 0:58
 */

namespace CAPTCHAReader\src\Log;


class Log
{
    public static function log(string $level, $content)
    {
        if (is_array($content)) {

        } elseif (is_string($content) || is_int($content)) {

        }
    }

    /**
     * @param $groupName
     * @param $testLog
     * @param $trainingId
     * @param $testSetNumber
     */
    public static function writeMultipleTestsLog($groupName, $testLog, $trainingId, $testSetNumber)
    {
        file_put_contents(__DIR__ . '/training/MultipleTestsLog_' . $groupName . '_' . $trainingId . '_' . $testSetNumber, json_encode($testLog));
    }

    /**
     * @param $groupName
     * @param $percent
     * @param $dictionarySampleCount
     * @param $trainingId
     * @param $labelNumber
     */
    public static function writeAddSamplesAutoLog($groupName, $percent, $dictionarySampleCount, $trainingId, $labelNumber)
    {
        file_put_contents(__DIR__ . '/training/AddSamplesAutoLog' . $groupName . '_' . $trainingId . '_' . $labelNumber, "$groupName --- $labelNumber --- $trainingId --- $dictionarySampleCount --- {$percent['correctRate']} --- {$percent['charCorrectRate']} \n\n", FILE_APPEND);

    }

}