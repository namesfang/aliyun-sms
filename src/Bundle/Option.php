<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Bundle;

use Namesfang\Aliyun\Sms\Format\JsonFormatter;
use Namesfang\Aliyun\Sms\Format\XmlFormatter;
use Namesfang\Aliyun\Sms\Format\FormFormatter;

/**
 * 公共请求参数
 * 官方文档 https://help.aliyun.com/document_detail/101341.html
 */
class Option
{
    /**
     * 所有接口参数
     * @var array
     */
    protected $option = [];
    
    /**
     * 密钥用于生成签名
     * @var string
     */
    protected $accessKeySecret;
    
    /**
     * 可用的请求方式
     * @var string
     */
    const METHOD_GET        = 'GET';
    const METHOD_HEAD       = 'HEAD';
    const METHOD_PUT        = 'PUT';
    const METHOD_POST       = 'POST';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_OPTIONS    = 'OPTIONS';
    const METHOD_DELETE     = 'DELETE';
    
    /**
     * 支持转/解码类型
     * @var string
     */
    const FORMAT_XML        = 'xml';
    const FORMAT_JSON       = 'json';
    const FORMAT_FORM       = 'form';
    
    /**
     * @param string $access_key_secret 密钥
     * @param array $option 批量设置参数
     */
    public function __construct($access_key_secret, array $option=[])
    {
        // 设置密钥
        $this->accessKeySecret = $access_key_secret;
        
        if($option) {
            foreach ($option as $name => $value) {
                $method_name = "set{$name}";
                if(method_exists($this, $method_name)) {
                    $this->$method_name($value);
                }
            }
        }
    }
    
    /**
     * 设置接口参数
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->option[ $name ] = $value;
    }
    
    /**
     * 获得接口参数
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if(isset($this->option[ $name ])) {
            return $this->option[ $name ];
        }
    }
    
    /**
     * 获得所有参数
     * @param string $format 格式化数据格式
     * @return string
     */
    public function getAll($format=null)
    {
        if(is_string($format)) {
            if($format == self::FORMAT_JSON) {
                return JsonFormatter::stringify($this->option);
            } else if($format == self::FORMAT_XML) {
                return XmlFormatter::stringify($this->option);
            }
            return FormFormatter::stringify($this->option, false);
        }
        return $this->option;
    }
    
    /**
     * 设置签名 必填
     * @param string $method
     */
    public function setSignature($method=self::METHOD_GET)
    {
        $this->Signature = $this->makeSignature($method);
    }
    
    /**
     * 访问密钥ID 必填
     * @param string $value
     */
    public function setAsscessKeyId($value)
    {
        $this->AccessKeyId = $value;
    }
    
    /**
     * 响应格式 非必填
     */
    public function setFormat($value=self::FORMAT_JSON)
    {
        $this->Format = $value;
    }
    
    /**
     * API支持的RegionID 非必填
     */
    public function setRegionId()
    {
        $this->RegionId = 'cn-hangzhou';
    }
    
    /**
     * 签名方式 必填
     */
    public function setSignatureMethod()
    {
        $this->SignatureMethod = 'HMAC-SHA1';
    }
    
    /**
     * 签名随机数 必填
     */
    public function setSignatureNonce()
    {
        $letter = array_merge(
            range('a', 'z'),
            range('A', 'Z'),
            range('0', '9')
        );
        
        shuffle($letter);
        
        $this->SignatureNonce = implode('', array_splice($letter, 0, mt_rand(20, 30)));
    }
    
    /**
     * 签名算法版本 必填
     */
    public function setSignatureVersion()
    {
        $this->SignatureVersion = '1.0';
    }
    
    /**
     * 请求的时间戳
     */
    public function setTimestamp()
    {
        $this->Timestamp = gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * API 的版本号 必填
     */
    public function setVersion()
    {
        $this->Version = '2017-05-25';
    }
    
    /**
     * 设置签名
     * @param string $http_method GET | POST
     */
    protected function makeSignature($method=self::HTTP_METHOD_GET)
    {
        $secret = $this->accessKeySecret;
        
        $data = $this->option;
        
        if(isset($data['Signature'])) {
            unset($data['Signature']);
        }
        
        ksort($data);
        
        /*
        echo '<pre>';
        print_r($data);
        die;
        //*/
        
        $pieces = [];
        foreach ($data as $key => $value)
        {
            $pieces[] = $key . '=' . $this->urlencode($value);
        }
        
        //die(implode('&', $pieces));
        
        $data = $method . '&%2F&' . $this->urlencode(implode('&', $pieces));
        
        //die($data);
        
        $hash_hmac = hash_hmac('sha1', $data, "{$secret}&", true);
        
        return $this->urlencode(base64_encode($hash_hmac));
    }
    
    /**
     * 签名转义
     * @param string $value
     * @return mixed
     */
    protected function urlencode($value)
    {
        return str_replace(['+', '*', '%7E'], ['%20', '%2A', '~'], urlencode($value));
    }
    
    /**
     * 数组转json
     * @param array | string $value
     * @return string
     */
    protected function array2json($value)
    {
        if(is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return $value;
    }
}