<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/16
 * Time: 1:49
 */

namespace CAPTCHA_Reader\Distinguish\GetImageInfo;

use CAPTCHA_Reader\CommonTrait;

trait GetImageInfoTrait
{
    use CommonTrait;

    /**
     * @return mixed
     */
    public function getImageInfo()
    {
        return $this->imageInfo;
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getVerifyImageMode()
    {
        return $this->config['verifyImageMode'];
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getSavePath()
    {
        return $this->config['imagePath']['online']['savePath'];

    }

    protected function getLocalImageDir()
    {
        return $this->config['imagePath']['local']['dir'];
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getLocalImgNumber()
    {
        return (int)$this->config['imagePath']['local']['number'];
    }

    /**
     * @return mixed
     */
    protected function getOnlineUrl()
    {
        return $this->config['imagePath']['online']['url'];
    }
}