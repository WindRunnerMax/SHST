<?php
namespace app\pubfun\controller;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Service
{
    
    public static function sendMsg($openid,$type,$content){
        $access_token = Token::signalGetPubAccessToken();
        $data = [
                "touser" => $openid,
                "msgtype" => $type,
                $type =>
                [
                     "content" => $content
                ]
            ];
        $data = json_encode($data);
        return Http::curlHTTP([
            'url' => 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,
            'method' => 'POST',
            'data' => $data,
            'cookieFlag' => true,
            'stopNext' => true,
            'penetrate' => false
        ]);
    }
}