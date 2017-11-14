<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:25
 */

namespace CAPTCHA_Reader\Identify;


class Identify implements IdentifyInterface
{
    use IdentifyTrait;
    protected $result;

    public function __construct( array $charArr , $config,$dictionaryPath='' )
    {

        $charStrArr = [
            'char1' => $this->twoDimArrayToStr( $charArr['char1'] ) ,
            'char2' => $this->twoDimArrayToStr( $charArr['char2'] ) ,
            'char3' => $this->twoDimArrayToStr( $charArr['char3'] ) ,
            'char4' => $this->twoDimArrayToStr( $charArr['char4'] ) ,
        ];

        empty( $dictionaryPath )
            ? $dictionary = json_decode( file_get_contents( __DIR__ . $config['dictionary'] ) )
            : $dictionary = json_decode( file_get_contents( $dictionaryPath ) );
        for($i = 1; $i <= $config['captchaStringNum']; $i++)
        {
            $char           = $this->getHighestSimilarity( $charStrArr['char' . $i] , $dictionary );
            $this->result[] = $char;
        }
    }

    public function getHighestSimilarity( $charstr , $dictionary )
    {
        $char = [
            'percent' => 0 ,
            'char'    => '' ,
            'num'     => 0 ,
        ];
        foreach($dictionary as $key => $model)
        {
//            $percent = levenshtein( $model->str , $charstr );
            similar_text( $model->str , $charstr , $percent );
            if ($percent > $char['percent'])
            {
                $char['percent'] = $percent;
                $char['char']    = $model->char;
                $char['num']     = $key;
            }
            if ($percent > 99)
            {
                break;
            }
        }
        return $char;
    }

    public function getResult()
    {
        $result = '';
        foreach($this->result as $value)
        {
            $result .= $value['char'];
        }

        return $this->result;
    }
}