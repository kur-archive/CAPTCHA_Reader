<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/12/10
 * Time: 23:32
 */

namespace CAPTCHA_Reader\Training;

use CAPTCHA_Reader\CommonTrait as DistinguishCommonTrait;

trait CommonTrait
{
    use DistinguishCommonTrait;

    /**
     * @return mixed
     */
    public function getTestSamplesDir()
    {
        return $this->trainingConfig['testSamples']['dir'];
    }

    /**
     * @return mixed
     */
    public function getTestSamplesWhichSampleToUse()
    {
        return $this->trainingConfig['testSamples']['whichSampleToUse'];
    }

    /**
     * @return mixed
     */
    public function getTrainingDictionary()
    {
        return $this->trainingConfig['trainingDictionary'];
    }

    /**
     * @return mixed
     */
    public function getStudySamplesDir()
    {
        return $this->trainingConfig['studySamples']['dir'];
    }

    /**
     * @return mixed
     */
    public function getStudySampleCollectionName()
    {
        return $this->trainingConfig['studySampleCollectionName'];
    }

    /**
     * @return mixed
     */
    public function getLogPath()
    {
        return $this->trainingConfig['LogPath'];
    }

    /**
     * @return mixed
     */
    public function getDictionarySampleLimit()
    {
        return $this->trainingConfig['dictionarySampleLimit'];
    }

    /**
     * @return mixed
     */
    public function getTestSuccessRateLine()
    {
        return $this->trainingConfig['testSuccessRateLine'];
    }

    /**
     * @param $studyCollectionName
     * @return array
     */
    public function getStudySampleList( $studySampleCollectionName )
    {
        $studySampleDir = $this->getStudySamplesDir() . $studySampleCollectionName . '\\';
        $sampleList     = $this->getDictionaryAllFile( $studySampleDir );
        return $sampleList;
    }

    /**
     * @param $studySampleCollectionName
     * @return array
     */
    public function getTestSampleList( $whichSampleToUse )
    {
        $studySampleDir = $this->getTestSamplesDir() . $whichSampleToUse . '\\';
        $sampleList     = $this->getDictionaryAllFile( $studySampleDir );
        return $sampleList;
    }
}