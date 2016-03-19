<?php
namespace colee\oss;
use yii\base\Component;
require_once __DIR__.'/aliyun-php-sdkv2-20130815/aliyun.php';
/**
 * 
 * @author CoLee
 * @use eg: 'oss'=>[
 *           'class'=>'common\extensions\AliOss',
 *           'bucket'=>'99n9',
 *           'prefix'=>'chisheng/',
 *           'AccessKeyId' => '',
 *           'AccessKeySecret' => '',
 *           'domain'=>'http://99n9.oss-cn-hangzhou.aliyuncs.com/',
 *           'imageHost' => 'http://99n9.img-cn-hangzhou.aliyuncs.com/'
 *       ],
 */
class AliOss extends Component
{
    public $bucket = '99n9';
    public $prefix = 'didawang/';   //路径前缀
    public $AccessKeyId = '';
    public $AccessKeySecret = '';
    public $domain = 'http://99n9.oss-cn-hangzhou.aliyuncs.com/';
    public $imageHost = 'http://99n9.img-cn-hangzhou.aliyuncs.com/';
    
    private $client;
    public function init()
    {
        $this->client = \Aliyun\OSS\OSSClient::factory([
            'AccessKeyId' => $this->AccessKeyId,
            'AccessKeySecret' => $this->AccessKeySecret,
        ]);
    }
    public function upload2oss($tempName, $path=null)
    {
        try {
            $stream  = file_get_contents($tempName);
            if (empty($path)){
                $path = date('Ymd').mb_substr(md5($stream), -8);
            }
            $this->client->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $this->prefix.$path,
                'Content' => $stream,
            ));
            return $path;
        } catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
            throw new \ErrorException( "Error: " . $ex->getErrorCode() . "\n");
        } catch (\Aliyun\Common\Exceptions\ClientException $ex) {
            throw new \ErrorException( "ClientError: " . $ex->getMessage() . "\n" );
        }
    }
    /**
     * 上传文件流到OSS
     */
    public function uploadStream2oss($stream,$path=null)
    {
        try {
            if (empty($path)){
                $path = date('Ymd').mb_substr(md5($stream), -8);
            }
            return $this->client->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $this->prefix.$path,
                'Content' => $stream,
            ));
        } catch (\Aliyun\OSS\Exceptions\OSSException $ex) {
            throw new \ErrorException( "Error: " . $ex->getErrorCode() . "\n");
        } catch (\Aliyun\Common\Exceptions\ClientException $ex) {
            throw new \ErrorException( "ClientError: " . $ex->getMessage() . "\n" );
        }
    }
    /**
     * 获取图片地址
     * @param string $path 路径
     * @param string $style 样式
     */
    public function getImageUrl($path, $style='m')
    {
        if (empty($path)){
            return '';
        }
        return $this->imageHost.$this->prefix.$path.'@!'.$style;
    }
    /**
     * 获取资源
     * @param unknown $path
     * @return string
     */
    public function getItem($path)
    {
        if (empty($path)){
            return '';
        }
        return $this->domain.$this->prefix.$path;
    }
    
    public function __call($method_name, $args)
    {
        if(empty($args['Bucket'])){
            $args['Bucket'] = $this->bucket;
        }
        return call_user_func_array([$this->client, $method_name],$args);
    }
}