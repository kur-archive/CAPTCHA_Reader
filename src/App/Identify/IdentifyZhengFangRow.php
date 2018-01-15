<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:11
 */

namespace CAPTCHAReader\src\App\Identify;


use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\App\ResultContainer;
use CAPTCHAReader\src\Traits\IdentifyTrait;

class IdentifyZhengFangRow extends Restriction
{
    use IdentifyTrait;

    private $conf;
    private $resultContainer;

    private $identifyRepository;
    private $dictionary;

    public function __construct(){
        $this->identifyRepository = $this->getRepository( 'ZhengFangRow' );
    }

    function run( ResultContainer $resultContainer ){
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();
        $this->dictionary = $this->getDictionary( $this->conf['componentGroup'][$this->conf['useGroup']] );



        self::dd( $this->conf );



    }
}