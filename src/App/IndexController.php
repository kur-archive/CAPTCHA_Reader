<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:47
 */

namespace CAPTCHAReader\src\App;

use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\Traits\CommonTrait;

class IndexController
{
    use CommonTrait;

    /**
     * @param $imagePath
     * @param $mode string  local|online
     * @return string
     *
     */
    public function entrance( $imagePath , $mode ){
        try {
            self::dd( $this->getConfig( 'sample' ) );
            //获取 配置
            $conf = $this->getConfig( 'app' );
            //获取 装饰器
            $decorator = $this->getDecorator( $conf );
            //设置 结果容器
            $resultContainer = new ResultContainer();
            $resultContainer->setConf( $conf );
            $resultContainer->setImagePath( $imagePath );
            $resultContainer->setMode( $mode );

            $resultContainer = $decorator->run( $resultContainer );

            self::dd( $resultContainer );

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $config
     * @return null
     */
    public function getDecorator( $conf ){
        $useGroup   = $conf['useGroup'];
        $components = $conf['componentGroup'][$useGroup];

        $decorator = $this->instantiationDecorator( $components );
        return $decorator;
    }

    /**
     * @param $components
     * @return Restriction
     */
    public function instantiationDecorator( $components ){
        $components = array_reverse( $components );
        $tmp        = null;

        foreach($components as $component){
            if (empty( $tmp )) {
                $tmp = new $component();
            } else {
                $tmp = new $component( $tmp );
            }
        }

        return $tmp;
    }
}


