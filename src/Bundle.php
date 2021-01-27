<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms;

use Namesfang\Aliyun\Sms\Bundle\Option;
use Namesfang\Aliyun\Sms\Log\LoggerInterface;

/**
 * Bundle基类
 */
class Bundle
{
    // 参数
    public $option;
    // 日志
    public $logger;
    
    // 发送短信
    const SMS_SEND_URL = 'https://dysmsapi.aliyuncs.com';
    // 短信接收
    const SMS_REC_URL = 'https://dybaseapi.aliyuncs.com';
    
    public function __construct(Option $option, LoggerInterface $logger)
    {
        // option
        $this->option = $option;
        // logger
        $this->logger = $logger;
    }
}