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
    protected $config;
    protected $dictionaryPath;
    protected $dictionary;
    protected $result;

    public function __construct( $config , $dictionaryPath = '' )
    {
        $this->config         = $config;
        $this->dictionaryPath = $dictionaryPath;
        $this->dictionary     = $this->getDictionary( $this->config , $this->dictionaryPath );
    }

    public function getResult( array $charArr )
    {
        $charStrArr = [
            'char1' => $this->twoDimArrayToStr( $charArr['char1'] ) ,
            'char2' => $this->twoDimArrayToStr( $charArr['char2'] ) ,
            'char3' => $this->twoDimArrayToStr( $charArr['char3'] ) ,
            'char4' => $this->twoDimArrayToStr( $charArr['char4'] ) ,
        ];

        for($i = 1; $i <= $this->config['captchaStringNum']; $i++)
        {
            $char           = $this->getHighestSimilarity( $charStrArr['char' . $i] , $this->dictionary );
            $this->result[] = $char;
        }

        $result = '';
        foreach($this->result as $value)
        {
            $result .= $value['char'];
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getDictionary( array $config , $dictionaryPath )
    : array
    {
        $dictionary = empty( $dictionaryPath )
            ? $dictionary = json_decode( file_get_contents( __DIR__ . $config['dictionary'] ) )
            : $dictionary = json_decode( file_get_contents( $dictionaryPath ) );
        return $dictionary;
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


}