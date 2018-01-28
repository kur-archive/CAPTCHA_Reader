<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:46
 */

namespace CAPTCHAReader\Training\MultiplesTests\NormalMultiplesTests;

use CAPTCHAReader\src\App\IndexController;
use CAPTCHAReader\src\Log\Log;
use CAPTCHAReader\training\Abstracts\TestsInterface;
use CAPTCHAReader\training\Traits\CommonTrait;

class NormalMultipleTests implements TestsInterface
{
    use CommonTrait;

    /**
     * @param $groupName
     * @param IndexController $indexController
     * @param null $trainingId
     * @param null $area
     * @return array
     * @throws \Exception
     *
     * 测试可以使用全组测试和指定某组进行测试
     */
    public function run($groupName, IndexController $indexController, $trainingId = null, $area = null)
    {
        $sampleListArr = $this->getTestSampleList($groupName, $area);
        $resultArr = [];

        foreach ($sampleListArr as $testSetNumber => $sampleList) {
            $sampleNumber = count($sampleList);
            $time = time();

            $resultDistributed = [
                'count'     => $sampleNumber,
                'true'      => 0,
                'false'     => 0,
                'charTrue'  => 0,
                'charFalse' => 0,
            ];

            foreach ($sampleList as $key => $sampleFilePath) {
                //样本答案数组
                preg_match('/\w+(?=.?\w+$)/', $sampleFilePath, $matches);
                $trueAnswer = $matches[0];

                //识别的结果数组
                $answer = $indexController->entrance($sampleFilePath, 'local');
                //进行判断，得到判断结果数组
                $judgmentResult = $this->judgment($trueAnswer, $answer);
                dump($judgmentResult);
                if ($judgmentResult['result']) {
                    ++$resultDistributed['true'];
                } else {
                    ++$resultDistributed['false'];
                }

                $resultDistributed['charTrue'] += $judgmentResult['charResult']['true'];
                $resultDistributed['charFalse'] += $judgmentResult['charResult']['false'];

                $testLog[$key] = $judgmentResult;
            }
            dump($resultDistributed);

            //记录日志
            //这里记录的是测试日志
            $testLog['resultDistributed'] = $resultDistributed;
            Log::writeMultipleTestsLog($groupName, $testLog, $trainingId, $testSetNumber);

            $resultArr = [
                'correctRate'     =>
                    ($resultDistributed['true'] / $resultDistributed['count']) * 100,
                'charCorrectRate' =>
                    ($resultDistributed['charTrue'] / ($resultDistributed['count'] * 4 )* 100),
            ];
        }

        return $resultArr;
    }


    /**
     * @param $sampleArr
     * @param $result
     * @return array
     */
    public function judgment($trueAnswer, $answer)
    {
        $target = [$trueAnswer, $answer];
        $charResult = ['true' => 0, 'false' => 0,];

        $flag = 0;
        for ($i = 0; $i < strlen($trueAnswer); ++$i) {
            if ($trueAnswer[$i] != $answer[$i]) {
                $flag = 1;
                ++$charResult['false'];
            } else {
                ++$charResult['true'];
            }
        }

        !$flag ? $result = true : $result = false;

        return compact('target', 'result', 'charResult');
    }


}