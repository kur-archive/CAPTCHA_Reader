<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/09/28
 * Time: 17:30
 */

class Identify
{
    protected $char1;
    protected $char2;
    protected $char3;
    protected $char4;

    protected $result_arr1 = [];
    protected $result_arr2 = [];
    protected $result_arr3 = [];
    protected $result_arr4 = [];


    public function __construct( array $afterCutArr )
    {
        //给char1 to char4 赋值
        foreach($afterCutArr as $key => $char)
        {
            $this->$key = $char;
        }

        $char1_str = $this->twoDArr2oneD( $this->char1 );
        $char2_str = $this->twoDArr2oneD( $this->char2 );
        $char3_str = $this->twoDArr2oneD( $this->char3 );
        $char4_str = $this->twoDArr2oneD( $this->char4 );

        $models = file_get_contents( dirname( __DIR__ ) . '\\model2.json' );
        $models = json_decode( $models );

        $percent1 = 0;
        $percent2 = 0;
        $percent3 = 0;
        $percent4 = 0;
        foreach($models as $key=>$model)
        {
            if ($percent1 < 99)
            {
                similar_text( $model->str , $char1_str , $percent1 );
                $this->result_arr1[$model->char .'-'. $key] = $percent1;
            }

            if ($percent2 < 99)
            {
                similar_text( $model->str , $char2_str , $percent2 );
                $this->result_arr2[$model->char .'-'. $key] = $percent2;
            }

            if ($percent3 < 99)
            {
                similar_text( $model->str , $char3_str , $percent3 );
                $this->result_arr3[$model->char .'-'. $key] = $percent3;
            }

            if ($percent4 < 99)
            {
                similar_text( $model->str , $char4_str , $percent4 );
                $this->result_arr4[$model->char .'-'. $key] = $percent4;
            }

            if ($percent1 + $percent2 + $percent3 + $percent4 > 398)
            {
                break;
            }
        }

        arsort( $this->result_arr1 );
        arsort( $this->result_arr2 );
        arsort( $this->result_arr3 );
        arsort( $this->result_arr4 );

        var_dump( each( $this->result_arr1 ) );
        var_dump( each( $this->result_arr2 ) );
        var_dump( each( $this->result_arr3 ) );
        var_dump( each( $this->result_arr4 ) );





    }

    public function getCharArrs()
    {
        return array_merge(
            ["char1" => $this->char1] ,
            ["char2" => $this->char2] ,
            ["char3" => $this->char3] ,
            ["char4" => $this->char4] );
    }

    public function twoDArr2oneD( $targetArr )
    {
        $str = '';
        foreach($targetArr as $targetRow)
        {
            foreach($targetRow as $targetNum)
            {
                $str .= $targetNum;
            }
        }
        return $str;

    }

}