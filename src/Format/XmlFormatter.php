<?php
// +-----------------------------------------------------------
// | 阿里云短信服务
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\Aliyun\Sms\Format;

class XmlFormatter implements FormatterInterface
{
    /**
     * xml转数组
     * {@inheritDoc}
     * @see FormatterInterface::parse()
     */
    static public function parse($xml) : array
    {
        self::checkExtension();
        
        $xml = str_replace('<![CDATA[]]>', '`CDATA`', $xml);
        
        $xml_object = new \SimpleXMLElement($xml, LIBXML_NOCDATA);
        
        $json_string = json_encode($xml_object);
        
        return json_decode(str_replace('`CDATA`', '', $json_string), true);
    }
    
    /**
     * 数组转成xml
     * {@inheritDoc}
     * @see FormatterInterface::stringify()
     */
    static public function stringify($data, $root='xml') : string
    {
        $xml = sprintf('<?xml version="1.0" encoding="UTF-8"?><%s></%s>', $root, $root);
        
        $xml_object = new \SimpleXMLElement($xml);
        
        self::array2xml($data, $xml_object);
        
        return $xml_object->asXML();
    }
    
    /**
     * 数组转xml
     * @param array $data
     * @param \SimpleXMLElement $xml_object
     */
    static protected function array2xml(array $data, &$xml_object)
    {
        foreach ($data as $name => $value) {
            if(is_numeric($name)) {
                $name = 'item';
            }
            if (is_array($value)) {
                self::array2xml($value, $xml_object->addChild($name));
            } else {
                $xml_object->addChild($name, htmlspecialchars($value));
            }
        }
    }
}