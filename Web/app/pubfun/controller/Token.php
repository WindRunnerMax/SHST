<?php
namespace app\pubfun\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;
use redis\Redis;

class Token extends Controller
{   

    public static function verifyHost(){
        echo $_GET["echostr"];
    }

    private static function getPubUrl(){
        $APPID = ""; 
        $APPSECRET =  ""; 
         $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
        return $url;
    }

    public static function signalGetPubAccessToken($value=''){
        # code...
        Redis::initRedis();
        $accessToken = Redis::get("PubAccessToken");
        if ($accessToken === false) {
            $url = self::getPubUrl();
            $accessTokenObject = json_decode(Http::curlHTTP(['url' => $url,'headers' =>Conf::getNormalHeader(), 'stopNext' => true, 'penetrate' => false ]),true);
            $accessToken = $accessTokenObject['access_token'];
            Redis::set("PubAccessToken",$accessTokenObject['access_token'],((int)$accessTokenObject['expires_in'] - 300));
        }
        return $accessToken;
    }
}
