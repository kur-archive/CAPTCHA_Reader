<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:25
 */

namespace CAPTCHA_Reader\Distinguish\Identify;

abstract class IdentifyController
{
    use IdentifyTrait;

    protected $config;
    protected $dictionary;
    public $result;
    public $charStrArr;
    /**
     * 该类用于识别获取结果，增加抽象方法run()，用于获取结果，获取结果使用多个方法，simpleResult，详细的结果（排版好认可率，结果等等的信息），用于调试的结果
     * 获取字典的方法移动到IdentifyTrait类中，
     * 批量训练会有需要获取切割好的字符串的结果的需求，需要做好这一块的方法
     * 需要有获取当前字典内容数量的方法，获取字典数量的方法写到CommonTrait中
     */

    /**
     * IdentifyController constructor.
     * @param $config
     * @param string $dictionaryPath
     */
    public function __construct( $config )
    {
        $this->config     = $config;
        $this->dictionary = $this->getDictionary();
    }

//    abstract public function run( array $charArr , $returnStyle = 'default' );

    public function run( array $charArr , $returnStyle = 'default' )
    {
        $this->result = [];
        $charStrArr   = [
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
        if ($returnStyle)
        {
            return $this->result;
        }
        else
        {
            return $result;
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

    /**
     * @return mixed
     */
    public function getCharStrArr()
    {
        return $this->charStrArr;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }


}