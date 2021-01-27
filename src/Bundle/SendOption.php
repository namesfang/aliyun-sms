<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Bundle;

/**
 * 发送短信参数
 * 官方文档 https://help.aliyun.com/document_detail/101414.html
 */
class SendOption extends Option
{
    /**
     * API的名称 必填
     * @param string $value
     */
    public function setAction()
    {
        $this->Action = 'SendSms';
    }
    
    /**
     * 设置接收短信的手机号码 必填
     * @param string $value 接收短信的手机号码
     * <p>
     * 国内短信：11位手机号码
     * 国际/港澳台消息：国际区号+号码
     * 支持对多个手机号码发送短信，手机号码之间以英文逗号（,）分隔。上限为1000个手机号码。批量调用相对于单条调用及时性稍有延迟。
     * </p>
     */
    public function setPhoneNumbers($value)
    {
        $this->PhoneNumbers = $value;
    }
    
    /**
     * 设置短信签名名称 必填
     * @param string $value 短信签名名称
     */
    public function setSignName($value)
    {
        $this->SignName = $value;
    }
    
    /**
     * 短信模板ID 必填
     * @param string $value 短信模板ID
     */
    public function setTemplateCode($value)
    {
        $this->TemplateCode = $value;
    }
    
    /**
     * 短信模板变量对应的实际值 非必填
     * @param mixed $value json字符串或数组
     */
    public function setTemplateParam($value)
    {
        if(is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->TemplateParam = $value;
    }
    
    /**
     * 上行短信扩展码 非必填
     * 无特殊需要此字段的用户请忽略此字段
     * @param string $value 上行短信扩展码
     */
    public function setSmsUpExtendCode($value)
    {
        $this->SmsUpExtendCode = $value;
    }
    
    /**
     * 外部流水扩展字段 非必填
     * @param string $value 外部流水扩展字段
     */
    public function setOutId($value)
    {
        $this->OutId = $value;
    }
}