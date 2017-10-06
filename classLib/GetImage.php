<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 2017/09/29
 * Time: 11:10
 */

/**
 * Class GetImage
 * 实例化时下载文件
 */
class GetImage
{
    protected $uri;
    protected $num;
    protected $imagePath;
    const FILETYPE = '.png';

    /**
     * GetImage constructor.
     * @param $url需要下载的文件的URI ,以
     * @param int $num需要下载的图片的数量
     */

    public function __construct( string $uri , int $num = 1 )
    {
        $this->url = $uri;
        $this->num = $num;

        if ($num < 1)
        {
            echo "\n";
            echo 'Please confirm the value of \$num';
            echo "\n";
            die;
        }

        $save_to = dirname(__DIR__) . '\tmp\\' . $this->getMicrontimeName() . self::FILETYPE;


        $this->imagePath = $save_to;

        echo $save_to . "\n";
        //TODO::这里可以加一个验证是否是正确的uri地址

        $this->downloadImg( $save_to );


    }


    public function getUrl()
    {
        return $this->uri;
    }


    public function setUrl( $uri )
    {
        return $this->uri = $uri;
    }


    public function getImagePath()
    {
        return $this->imagePath;
    }


    public function downloadImg( string $name )
    {
        $content = file_get_contents( $this->url );
        $save    = file_put_contents( $name , $content );
    }


    /**
     * 获取当前的微秒时间，并添加一个两位的随机数
     * @return string $time
     */
    public function getMicrontimeName()
    {
        $time = explode( ' ' , microtime() );
        $time = str_replace( '.' , '-' , $time );
        $time =
            $time[1] .
            substr( $time[0] , 1 , strlen( $time[0] ) ) .
            rand( 10 , 99 );
        return $time;
    }

    public static function getLocalImg()
    {
        $nums = rand( 0 , 999 );
        $path = dirname(__DIR__).'\imgs\\'.$nums.'.png';
        return $path;
    }


}