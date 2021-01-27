<?php
// +-----------------------------------------------------------
// | 发送短信 示例
// | 官方参考文档 https://help.aliyun.com/product/44282.html
// +-----------------------------------------------------------
use Namesfang\Aliyun\Sms\Bundle\Send;
use Namesfang\Aliyun\Sms\Bundle\SendOption;
use Namesfang\Aliyun\Sms\Log\Logger;

define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH',  sprintf('%s/logs', ROOT_PATH));

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    $className = str_replace('Namesfang/Aliyun/Sms/', '', $className);
    require_once sprintf('%s/src/%s.php', ROOT_PATH, $className);
});

//
// 日志记录
// 也可以自行封装日志
// 实现 LoggerInterface 即可
//
$logger = new Logger(LOG_PATH, true);

/*
// +-----------------------------------------------------------
// | 第1种 配置参数方式
// | 直接在实例化时传入一个关联数组(非接口参数会自动过滤)
// | 每个 key 对应接口中的参数
// | 当值为数组时自动转义为json字符串
// +-----------------------------------------------------------
$option = new SendOption('AccessKeySecret', [
    'AsscessKeyId'=> 'AsscessKeyId',
    'SignName'=> '签名',
    'PhoneNumbers'=> '15212341234,15312341234',
    'TemplateCode'=> 'SMS_2050000388',
    'TemplateParam'=> '{"code":"232322"}',
]);
//*/

// +-----------------------------------------------------------
// | 第2种 配置参数方式
// | 调用相应的设置函数分别设置
// | 当值为数组时自动转义为json字符串
// +-----------------------------------------------------------
$option = new SendOption('AccessKeySecret');
$option->setAsscessKeyId('AsscessKeyId');
$option->setSignName('签名');
$option->setPhoneNumbers('15212341234');
$option->setTemplateCode('SMS_2050000388');
$option->setTemplateParam([
    'code'=> '123456',
]);

// 实例化发送类
$send = new Send($option, $logger);

// 发送
$res = $send->request();

// 判断请求是否异常
if($res->error) {
    $logger->print($res->error, true);
}

// 打印接口返回的原始数据
// $logger->print($res->original, true);

// 打印接口返回状态
$logger->print($res->Code, true);

// 打印接口返回信息
$logger->print($res->result, true);