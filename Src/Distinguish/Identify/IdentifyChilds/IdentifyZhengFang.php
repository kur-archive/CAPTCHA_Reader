<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/04
 * Time: 0:29
 */

namespace CAPTCHA_Reader\Distinguish\Identify;


class IdentifyZhengFang extends IdentifyController
{
    public function run( array $charArr , $returnStyle = 'simpleResult' )
    {
        $this->dictionary = $this->getDictionary();
        $results    = [];
        $charStrArr = [];
        //将切出来的每个字符块二维数组转为字符串
        for($i = 1; $i <= $this->config['captchaStringNum']; ++$i)
        {
            $charStrArr['char' . $i] = self::twoDimArrayToStr( $charArr['char' . $i] );
            //获取结果
            $char = $this->getHighestSimilarityResult( $charStrArr['char' . $i] , $this->dictionary );

            $results[] = $char;
        }

        //将结果按resultStyle输出
        $answer = null;
        if ($returnStyle == 'simpleResult')
        {
            foreach($results as $result)
            {
                $answer .= $result['char'];
            }
            return $answer;
        }
        elseif ($returnStyle == 'detailResult')
        {
            //以数组的形式返回每个结果字符的相似度，识别结果，确认的样本数
            $answer = $results;
            for($i = 0; $i < $this->config['captchaStringNum']; ++$i)
            {
                unset( $answer[$i]['sampleNum'] );
                unset( $answer[$i]['sampleSource'] );
            }
            return $answer;

        }
        elseif ($returnStyle == 'autoStudyResult' || $returnStyle == 'debugResult')
        {
            //以数组的形式返回结果字符串的相似度，识别结果，确认的样本是第几个数，详细的样本字符串，
            return $results;
        }
    }

    public function getHighestSimilarityResult( $charstr , $dictionary )
    {
        $char = [
            'percent'      => 0 ,
            'char'         => '' ,
            'sampleNum'    => 0 ,
            'sampleSource' => ''
        ];

        if (empty($dictionary))
        {
            $char['percent']      = 0;
            $char['char']         = 0;
            $char['sampleSource'] = $charstr;
            return $char;
        }

        foreach($dictionary as $key => $sample)
        {
            similar_text( $sample->str , $charstr , $percent );
            if ($percent > $char['percent'])
            {
                $char['percent']      = $percent;
                $char['char']         = $sample->char;
                $char['sampleNum']    = $key;
                $char['sampleSource'] = $charstr;
            }
        }
        return $char;
    }


}