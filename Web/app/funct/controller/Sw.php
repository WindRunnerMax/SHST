<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use app\index\controller\Http;

class Sw extends Controller
{
    private $url = "http://jwgl.sdust.edu.cn/app.do";

    private $header = array(
        'User-Agent:'.'Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer:'.'http://www.baidu.com',
        'accept-encoding:'.'gzip, deflate, br',
        'accept-language:'.'zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control:'.'max-age=0'
    );

    private function checkSession($value='')
    {
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录","/?status=E",3);
    }

    public function httpReq($params){
        array_push($this->header,"token:".$_SESSION['TOKEN']);
        $http = new Http();
        $info = $http->httpRequest($this->url,$params,"GET",$this->header);
        return $info;
    }

    public function getCurrentTime(){
        $params = array(
        "method" => "getCurrentTime",
        "currDate" => date("Y-m-d",time())
        );
        return $this->httpReq($params);
    }


    public function table($zc=-1)
    {
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc === -1 ? $s['zc'] : $zc ,
        "xh" => $_SESSION['account']
        );
        $info = $this->httpReq($params);
        return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
    }

    public function classroom($idleTime="")
    {
        $user = $this->checkSession();
        $info = [];
        if ($idleTime!=="") {
            $params = array(
                "method" => "getKxJscx",
                "time" => date("Y-m-d",time()),
                "idleTime" => $idleTime
            );
            $info = $this->httpReq($params);
        }
        return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
    }

    public function grade($sy="")
    {
        $user = $this->checkSession();
        $params = array(
            "method" => "getCjcx",
            "xh" => $_SESSION['account'],
            "xnxqid" => $sy
        );
        // print_r($params);
        $info = $this->httpReq($params);
        return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
    }
}
