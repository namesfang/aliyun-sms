<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms;

use Namesfang\Aliyun\Sms\Log\LoggerInterface;
use Namesfang\Aliyun\Sms\Format\JsonFormatter;
use Namesfang\Aliyun\Sms\Format\XmlFormatter;
use Namesfang\Aliyun\Sms\Format\FormFormatter;

class Request
{
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
     * 默认编码类型
     */
    const CHARSET_UTF8      = 'UTF-8';
    
    // cURL参数
    protected $options          = [
        /**
         * 允许cURL函数执行的最长秒数
         */
        CURLOPT_TIMEOUT         => 30,
        
        /**
         * 将头文件的信息作为数据流输出
         */
        CURLOPT_HEADER          => true,
        
        /**
         * 获取的信息以字符串返回，而不是直接输出
         */
        CURLOPT_RETURNTRANSFER  => true,
        
        /**
         * 0 不检查
         * 1 检查服务器SSL证书中是否存在一个公用名(common name)
         * 2 检查公用名是否存在，并且是否与提供的主机名匹配
         */
        CURLOPT_SSL_VERIFYHOST  => 0,
        
        /**
         * 禁止cURL验证对等证书
         */
        CURLOPT_SSL_VERIFYPEER  => false,
    ];
    
    /**
     * 请求地址
     * @var string
     */
    protected $url;
    
    /**
     * http method
     * @var string
     */
    protected $method       = self::METHOD_GET;
    
    /**
     * 请求头
     * @var array
     */
    protected $header       = [];
    
    /**
     * 请求参数
     * @var array
     */
    protected $query        = [];
    
    /**
     * 请求字段
     * @var array
     */
    protected $fields       = [];
    
    /**
     * 批量上传使用
     * 标记每个上传索引
     * [
     *   'file'=> 0
     * ]
     * @var array
     */
    protected $fileIndexes  = [];
    
    /**
     * 请求/响应数据格式
     * @var string
     */
    protected $format       = self::FORMAT_JSON;
    
    /**
     * 日志
     * @var LoggerInterface
     */
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * 设置URL
     * @param string $value url
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function url($value)
    {
        $this->url = $value;
        return $this;
    }
    
    /**
     * 设置请求头
     * 支持批量设置
     * @param string | array $name
     * @param string $value
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function header($name, $value=null)
    {
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    $this->header[] = $value;
                } elseif (is_string($key)) {
                    $this->header[] = "{$key}: {$value}";
                }
            }
        } else if(is_string($name)) {
            if(is_string($value)) {
                $this->header[] = "{$name}: {$value}";
            }
            else if(is_null($value)) {
                $this->header[] = $name;
            }
        }
        return $this;
    }
    
    /**
     * 设置query参数 支持批量
     * @param string | array $name
     * @param string $value
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function query($name, $value=null)
    {
        $parsed = [];
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    // value = a=1&c=2
                    parse_str($value, $parsed);
                    $this->query = array_merge($this->query, $parsed);
                } elseif (is_string($key)) {
                    $this->query[ $key ] = $value;
                }
            }
        } else if(is_string($name)) {
            // value = a=1&c=2
            parse_str($value, $parsed);
            $this->query = array_merge($this->query, $parsed);
        }
        return $this;
    }
    
    /**
     * 设置请求字段
     * @param string | array $name
     * @param string $value
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function data($name, $value=null)
    {
        $parsed = [];
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    // value = a=1&c=2
                    parse_str($value, $parsed);
                    $this->fields = array_merge($this->fields, $parsed);
                } elseif (is_string($key)) {
                    $this->fields[ $key ] = $value;
                }
            }
        } else if(is_string($name)) {
            if(is_null($value)) {
                $this->fields = $name;
            } else {
                $this->fields[ $name ] = $value;
            }
        }
        return $this;
    }
    
    /**
     * 设置 User-Agent 头的字符串
     * @param string $value
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function setUserAgent($value)
    {
        $this->setCurlUserAgent($value);
        return $this;
    }
    
    /**
     * 设置允许跳转
     * @param boolean $auto_referer 是否自动添加来源
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function setAllowRedirect($auto_referer=true)
    {
        $this->setCurlAutoReferer($auto_referer);
        $this->setCurlFollowLocation(true);
        return $this;
    }
    
    /**
     * 超时时间
     * @param number $timeout
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function setTimeout($timeout=30)
    {
        $this->setCurlTimeout($timeout);
        return $this;
    }
    
    /**
     * 请求/响应数据格式
     * 用于程序判断使用哪种编码器
     * @param string $type 请求和响应类型（响应会优先从响应头中获得）
     * @param boolean $with_header 是否添加 content-type 头信息
     * @param string $charset 编码规范
     * @return \Namesfang\Aliyun\Sms\Request
     */
    public function format($type, $with_header=true, $charset=self::CHARSET_UTF8)
    {
        $this->format = $type;
        
        $formatter = $this->getFormatter($type);
        
        if($with_header) {
            $this->header("Content-Type: {$formatter['header']}; charset={$charset}");
            $this->header('Connection: Keep-Alive');
        }
        return $this;
    }
    
    /**
     * 发起GET请求
     * @return \Namesfang\Aliyun\Sms\Response
     */
    public function get($format=self::FORMAT_JSON)
    {
        return $this->format($format)->send(self::METHOD_GET);
    }
    
    /**
     * 发起 POST 请求
     * @return \Namesfang\Aliyun\Sms\Response
     */
    public function post($format=self::FORMAT_JSON)
    {
        return $this->format($format)->send(self::METHOD_POST);
    }
    
    /**
     * 执行请求
     * @param string $method
     * @return \Namesfang\Aliyun\Sms\Response
     */
    protected function send($method)
    {
        $this->setCurlUrl($this->makeUrl());
        
        if($this->header) {
            $this->setCurlHeader($this->header);
        }
        
        if(self::METHOD_POST == $method) {
            $this->setCurlPostMethod();
            $this->setPostFields();
        }
        
        $ch = curl_init();
        
        curl_setopt_array($ch, $this->options);
        
        $transfer = curl_exec($ch);
        
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        $info = curl_getinfo($ch);
        
        curl_close($ch);
        
        return new Response($errno, $error, $transfer, $info, $this->format, $this->logger);
    }
    
    /**
     * 组装URL
     */
    protected function makeUrl()
    {
        $url = rtrim($this->url, '&');
        
        if($this->query) {
            if(strpos($url, '?') > 0) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= http_build_query($this->query);
        }
        
        return $url;
    }
    
    /**
     * 格式化数据
     * @return string
     */
    protected function setPostFields()
    {
        //$this->logger->print($this->fields, true);
        $fields = $this->fields;
        //
        // 当添加上传文件时不允许转码
        //
        if(0 == $this->fileIndexes) {
            if($this->format == self::FORMAT_JSON) {
                $fields = JsonFormatter::stringify($fields);
            } elseif($this->format == self::FORMAT_XML) {
                $fields = XmlFormatter::stringify($fields);
            } else {
                $fields = FormFormatter::stringify($fields);
            }
        }
        $this->setCurlPostFields($fields);
    }
    
    protected function setCurlUrl($value)
    {
        // @loggoer
        $this->logger->info($value, '请求地址');
        $this->options[ CURLOPT_URL ] = $value;
    }
    
    protected function setCurlHeader($value)
    {
        // @loggoer
        $this->logger->info($value, '请求头部');
        $this->options[ CURLOPT_HTTPHEADER ] = $value;
    }
    
    protected function setCurlTimeout($timeout=30)
    {
        $this->options[ CURLOPT_TIMEOUT ] = $timeout;
    }
    
    protected function setCurlUserAgent($value)
    {
        // @loggoer
        $this->logger->info($value, '请求UA');
        $this->options[ CURLOPT_USERAGENT ] = $value;
    }
    
    protected function setCurlFollowLocation($value)
    {
        $this->options[ CURLOPT_FOLLOWLOCATION ] = $value;
    }
    
    protected function setCurlAutoReferer($value)
    {
        $this->options[ CURLOPT_AUTOREFERER ] = $value;
    }
    
    protected function setCurlNoBody()
    {
        $this->options[ CURLOPT_NOBODY ] = true;
    }
    
    protected function setCurlPostMethod()
    {
        // @loggoer
        $this->logger->info('POST', '请求方式');
        $this->options[ CURLOPT_POST ] = true;
    }
    
    protected function setCurlCustomRequest($value)
    {
        // @loggoer
        $this->logger->info($value, '请求方式');
        $this->options[ CURLOPT_CUSTOMREQUEST ] = $value;
    }
    
    protected function setCurlPostFields($value)
    {
        // @loggoer
        $this->logger->info($value, '请求字段');
        $this->options[ CURLOPT_POSTFIELDS ] = $value;
    }
    
    /**
     * 格式化
     * @param string $type
     * @return string[]
     */
    protected function getFormatter($type)
    {
        $formatters = [
            'xml'=> [
                'header'=> 'application/json',
                'formatter'=> 'JsonFormatter',
            ],
            'json'=> [
                'header'=> 'application/json',
                'formatter'=> 'JsonFormatter',
            ],
            'form'=> [
                'header'=> 'application/x-www-form-urlencoded',
                'formatter'=> 'FormFormatter',
            ],
        ];
        
        return $formatters[ $type ] ?? $formatters['form'];
    }
}