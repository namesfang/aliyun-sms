<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Log;

interface LoggerInterface
{
    public function info($message, $phrase=null);
    public function warn($message, $phrase=null);
    public function error($message, $phrase=null);
}