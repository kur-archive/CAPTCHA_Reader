<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/11/01
 * Time: 23:29
 */

namespace CAPTCHA_Reader;

class IndexController
{
    use CommonTrait;

    private $config;
    private $getImageInfoProvider;
    private $pretreatmentProvider;
    private $cuttingProvider;
    private $identifyProvider;

    /**
     * 增加获取各个对象实例的方法，方便删除暂存图片以及其他应用
     * 获取config的方法写到CommonTrait中，方便其他类调用
     */

    /**
     * 这个类作为入口类，需要去config里读哪个步骤需要实例化哪个类，使用哪些策略
     * 识别的方法作为一个类，返回的结果根据传入的参数而采用不同的style，方便调试和训练
     */

    //tips 不应该在这里传path，应该在下面getResult方法中传path
    public function __construct()
    {
        //完成各种类的初始化
        $this->config = $this->getConfig( 'app' );
        $plan      = $this->config['plan'];
        $providers = $this->buildProvider( $plan , $this->config );

        $this->getImageInfoProvider = $providers['GetImageInfo'];
        $this->pretreatmentProvider = $providers['Pretreatment'];
        $this->cuttingProvider      = $providers['Cutting'];
        $this->identifyProvider     = $providers['Identify'];
        unset( $providers );
    }

    /**
     * @return string
     */
    public function getResult( $path = null , $returnStyle = null )
    {
        $imageArr       = $this->getImageInfoProvider->getResult( $path );
        $imageInfo      = $imageArr['imageInfo'];
        $imageBinaryArr = $imageArr['imageBinaryArr'];

        $noiseCancelArr = $this->pretreatmentProvider->run( $this->config , $imageInfo , $imageBinaryArr );
        $charArr        = $this->cuttingProvider->run( $noiseCancelArr , $imageInfo );
        $result         = $this->identifyProvider->getResult( $charArr , $returnStyle );

        return $result;
    }

    /**
     * @param $plan
     * @param $config
     * @return array
     * @throws \Exception
     */
    public function buildProvider( $plan , $config )
    {
        $namespaces = [
            'GetImageInfo' => '\CAPTCHA_Reader\Distinguish\GetImageInfo\GetImageInfo' . ucfirst( $plan ) ,
            'Pretreatment' => '\CAPTCHA_Reader\Distinguish\Pretreatment\Pretreatment' . ucfirst( $plan ) ,
            'Cutting'      => '\CAPTCHA_Reader\Distinguish\Cutting\Cutting' . ucfirst( $plan ) ,
            'Identify'     => '\CAPTCHA_Reader\Distinguish\Identify\Identify' . ucfirst( $plan ) ,
        ];

        $isAllClassExists = 0;
        foreach($namespaces as $namespace)
        {
            class_exists( $namespace ) ? : ++$isAllClassExists;
        }

        if ($isAllClassExists)
        {
            $namespaces       = $this->addNamespaceToClass( $config['provider'] );
            $isAllClassExists = 0;
            foreach($namespaces as $namespace)
            {
                class_exists( $namespace ) ? : ++$isAllClassExists;
                if ($isAllClassExists)
                {
                    throw new \Exception( 'Class ' . $namespace . ' is not exists' );
                }
            }
        }

        $serviceProviders = [];
        foreach($namespaces as $key => $namespace)
        {
            $serviceProviders[$key] = new $namespace( $config );
        }
        return $serviceProviders;
    }

    /**
     * @param $providers
     * @return mixed
     */
    public function addNamespaceToClass( $providers )
    {
        foreach($providers as $key => $provider)
        {
            $providers[$key] = '\CAPTCHA_Reader\Distinguish\\' . $key . '\\' . $provider;
        }
        return $providers;
    }

    /**
     * @return mixed
     */
    public function getGetImageInfoProvider()
    {
        return $this->getImageInfoProvider;
    }

    /**
     * @return mixed
     */
    public function getPretreatmentProvider()
    {
        return $this->pretreatmentProvider;
    }

    /**
     * @return mixed
     */
    public function getCuttingProvider()
    {
        return $this->cuttingProvider;
    }

    /**
     * @return mixed
     */
    public function getIdentifyProvider()
    {
        return $this->identifyProvider;
    }

}