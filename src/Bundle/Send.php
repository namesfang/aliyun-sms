<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Bundle;

use Namesfang\Aliyun\Sms\Bundle;
use Namesfang\Aliyun\Sms\Request;

/**
 * 发送短信
 * 官方文档 https://help.aliyun.com/document_detail/101414.html
 */
class Send extends Bundle
{
    /**
     * 发送短信
     * @return \Namesfang\Aliyun\Sms\Response
     */
    public function request()
    {
        $this->option->setAction();
        $this->option->setFormat();
        $this->option->setRegionId();
        $this->option->setSignatureMethod();
        $this->option->setSignatureNonce();
        $this->option->setSignatureVersion();
        $this->option->setTimestamp();
        $this->option->setVersion();
        $this->option->setSignature($this->option::METHOD_POST);
        
        $data = $this->option->getAll($this->option::FORMAT_FORM);
        
        //$this->logger->print($data, true);
        
        $request = new Request($this->logger);
        $request->url(self::SMS_SEND_URL);
        $request->data($data);
        
        return $request->post($request::FORMAT_FORM);
    }
}