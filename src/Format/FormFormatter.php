<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Format;

class FormFormatter implements FormatterInterface
{
    /**
     * form-urlencoded 转 array
     * @param string $urlencoded
     * @return array
     */
    static public function parse($urlencoded) : array
    {
        $parsed = [];
        
        parse_str($urlencoded, $parsed);
        
        return $parsed;
    }
    
    /**
     * array 转 form-urlencoded
     * @param array $data
     * @param boolean $urlencode 是否使用 urlencode 编码
     * @return string
     */
    static public function stringify($data, $urlencode=true) : string
    {
        if($urlencode) {
            return http_build_query($data);
        }
        return urldecode(http_build_query($data));
    }
}