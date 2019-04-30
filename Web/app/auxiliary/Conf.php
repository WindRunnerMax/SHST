<?php
namespace app\auxiliary;

class Conf
{
    public static function getUrl(){
        return "http://192.168.113.130/app.do";
    }

    public static function getHeader(){
        return array(
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: gzip, deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0'
        );
    }
}