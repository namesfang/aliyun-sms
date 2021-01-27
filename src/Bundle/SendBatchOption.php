<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Bundle;

/**
 * 批量发送短信参数
 * https://help.aliyun.com/document_detail/102364.html
 */
class SendBatchOption extends Option
{
    /**
     * API的名称 必填
     * @param string $value
     */
    public function setAction()
    {
        $this->Action = 'SendBatchSms';
    }
    
    /**
     * 设置接收短信的手机号码 必选
     * @param string $phone_numbers 接收短信的手机号码
     * <p>
     * 国内短信：11位手机号码
     * 国际/港澳台消息：国际区号+号码
     * 支持对多个手机号码发送短信，手机号码之间以英文逗号（,）分隔。上限为1000个手机号码。批量调用相对于单条调用及时性稍有延迟。
     * </p>
     */
    public function setPhoneNumberJson($value)
    {
        $this->PhoneNumberJson = $this->array2json($value);
    }
    
    /**
     * 设置短信签名名称 必选
     * @param string $sign_name 短信签名名称
     */
    public function setSignNameJson($value)
    {
        $this->SignNameJson = $this->array2json($value);
    }
    
    /**
     * 短信模板ID 必选
     * @param string $template_code 短信模板ID
     */
    public function setTemplateCode($value)
    {
        $this->TemplateCode = $value;
    }
    
    /**
     * 短信模板变量对应的实际值，JSON格式（数组时自动转换）。
     * @param mixed $templateParam
     */
    public function setTemplateParamJson($value)
    {
        $this->TemplateParamJson = $this->array2json($value);
    }
    
    /**
     * 上行短信扩展码
     * @param string $smsUpExtendCode
     * 无特殊需要此字段的用户请忽略此字段
     */
    public function setSmsUpExtendCodeJson($value)
    {
        $this->SmsUpExtendCodeJson = $this->array2json($value);
    }
}