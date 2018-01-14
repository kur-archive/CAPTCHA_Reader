<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2018/01/13
 * Time: 1:03
 */

namespace CAPTCHAReader\src\App\Cutting;


use CAPTCHAReader\src\App\Abstracts\Load;
use CAPTCHAReader\src\App\Abstracts\Restriction;
use CAPTCHAReader\src\App\ResultContainer;
use CAPTCHAReader\src\Traits\CuttingZhengFangTrait;

class CuttingZhengFangFixed extends Load
{
    use CuttingZhengFangTrait;

    private $conf;
    private $resultContainer;
    private $cuttingRepository;

    public function __construct( Restriction $nextStep ){
        parent::__construct( $nextStep );
        $this->cuttingRepository = $this->getRepository( 'ZhengFangFixed' );
    }


    function run( ResultContainer $resultContainer ){
        $this->resultContainer = $resultContainer;
        $this->conf            = $this->resultContainer->getConf();


    }


}