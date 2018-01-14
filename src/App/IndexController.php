<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/08
 * Time: 2:47
 */

namespace CAPTCHAReader\src\App;

use CAPTCHAReader\src\CommonTrait;

class IndexController
{
    use CommonTrait;

    public function entrance( $imagePath = null ){
        try {
            //异常检测
            if (empty( $imagePath )) {
                throw new \Exception( 'please enter path' );
            }

            //获取配置
            $conf       = $this->getConfig( 'app' );
            $useGroup   = $conf['useGroup'];
            $components = $conf['componentGroup'][$useGroup];

            $decorator       = $this->instantiationDecorator( $components );

            $resultContainer = new ResultContainer();
            $resultContainer = $decorator->run( $resultContainer );
            self::dd( $resultContainer );

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

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


