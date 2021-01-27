<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Format;

interface FormatterInterface
{
    /**
     * 将xml转成array
     * @param string $xml_string
     * @return array
     */
    static public function parse(string $string) : array;
    
    /**
     * 将数组转成 xml
     * @param array $data
     * @return string
     */
    static public function stringify(array $data) : string;
}