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
        return $this->config['VerifyImageMode'];
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getSavePath()
    {
        return $this->config['ImagePath']['online']['savePath'];

    }

    protected function getLocalImageDir()
    {
        return $this->config['ImagePath']['local']['dir'];
    }

    /**
     * @param array $config
     * @return mixed
     */
    protected function getLocalImgNumber()
    {
        return (int)$this->config['ImagePath']['local']['number'];
    }

    /**
     * @return mixed
     */
    protected function getOnlineUrl()
    {
        return $this->config['ImagePath']['online']['url'];
    }
}