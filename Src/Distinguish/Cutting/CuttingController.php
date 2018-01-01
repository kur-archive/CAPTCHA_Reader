<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:24
 */

namespace CAPTCHA_Reader\Distinguish\Cutting;

abstract class CuttingController
{
    use CuttingTrait;

    protected $charArr = [
        'char1' => [] ,
        'char2' => [] ,
        'char3' => [] ,
        'char4' => [] ,
    ];
    protected $coordinate = [
        'x'  => [] ,
        'x_' => [] ,
        'y'  => [] ,
        'y_' => [] ,
    ];


    protected $config;
    protected $captchaStringNum;

    /**
     * 这里用于切割出单个的字符块，
     * 大量的方法全部由子类去实现，父类声明一个run的抽象方法让子类实现用于获取结果
     */

    /**
     * CuttingController constructor.
     * @param array $config
     */
    public function __construct( array $config )
    {
        $this->config           = $config;
        $this->captchaStringNum = $config['captchaStringNum'];
    }

    /**
     * @param array $noiseCancelArr
     * @param array $imageInfo
     * @return mixed
     */
    abstract public function run( array $noiseCancelArr , array $imageInfo );

    /**
     * @param $imageArr
     * @param $charArr
     * @param $coordinate
     * @param $captchaStringNum
     * @return mixed
     */
    public function Cut( $imageArr , $charArr , $coordinate , $captchaStringNum )
    {
        for($i = 1; $i <= $captchaStringNum; $i++)
        {
            for($y = $coordinate['y'][$i] , $_y = 0; $y <= $coordinate['y_'][$i]; $y++ , $_y++)
            {
                for($x = $coordinate['x'][$i] , $_x = 0; $x <= $coordinate['x_'][$i]; $x++ , $_x++)
                {
                    $charArr['char' . $i][$_y][$_x] = $imageArr[$y][$x];
                }
            }
        }

        return $charArr;
    }

    /**
     * @return array
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }

}