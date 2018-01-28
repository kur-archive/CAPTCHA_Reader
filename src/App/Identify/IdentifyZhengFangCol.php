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

class IdentifyZhengFangCol extends Restriction
{
    use IdentifyTrait;

    private $conf;
    private $resultContainer;

    private $identifyRepository;
    private $dictionary;

    private $charPixedCollection;

    public function __construct()
    {
        $this->identifyRepository = $this->getRepository('ZhengFangCol');
    }

    function run(ResultContainer $resultContainer)
    {
        $this->resultContainer = $resultContainer;
        $this->conf = $this->resultContainer->getConf();
        $this->charPixedCollection = $this->resultContainer->getCharPixedCollection();

        $this->dictionary = $this->getDictionary($this->conf['componentGroup'][$this->conf['useGroup']]['dictionary']);

        //空字典处理
        if (!count($this->dictionary)) {
            $this->resultContainer->setResultStr(null);
            return $this->resultContainer;
        }

        //将 数组 转为 字符串
        foreach ($this->charPixedCollection as $charPixed) {
            $oneDCharStrArr[] = $this->twoD2oneDArrayCol($charPixed);
        }

        $this->resultContainer->setOneDCharStrArr($oneDCharStrArr);

        //在 字典中 寻找 相似度 最高的 样本
        $result = '';
        foreach($oneDCharStrArr as $oneDChar){
            //是否记录识别详情
            if ($this->conf['noteDetailJudgeProcess']) {
                $result .= $this->identifyRepository->getHighestSimilarityResultNoteDetail( $oneDChar , $this->dictionary , $this->resultContainer );
            } else {
                $result .= $this->identifyRepository->getHighestSimilarityResult( $oneDChar , $this->dictionary );
            }
        }

        $this->resultContainer->setResultStr($result);

        return $this->resultContainer;
    }
}