<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Format;

class JsonFormatter implements FormatterInterface
{
    /**
     * json 转 array
     * @param string $json
     * @return array
     */
    static public function parse(string $json) : array
    {
        return json_decode($json, true);
    }
    
    /**
     * array 转 json
     * @param array $data
     * @return string
     */
    static public function stringify(array $data) : string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES, 10);
    }
}