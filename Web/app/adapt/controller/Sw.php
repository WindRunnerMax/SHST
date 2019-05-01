<?php
namespace app\adapt\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Sw extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录","/?status=E",3);
    }


    public function httpReq($params){
        $header = Conf::getHeader();
        array_push($header,"token:".$_SESSION['TOKEN']);
        $http = new Http();
        $info = $http->httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }

    public function getCurrentTime(){
        $params = array(
        "method" => "getCurrentTime",
        "currDate" => date("Y-m-d",time())
        );
        return $this->httpReq($params);
    }

    public function table($zc=-1){
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
	$zc = $zc === -1 ? $s['zc'] : $zc;
	$params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        $info = $this->httpReq($params);
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'zc' => $zc,
			"info" => json_decode($info,true)
                   ]);
        return $this->fetch();
    }

    public function grade($sy=""){
        $user = $this->checkSession();
	//$sy = $sy === "" ? json_decode($this->getCurrentTime(),true)['xnxqh'] : $sy ;
	$params = array(
            "method" => "getCjcx",
            "xh" => $_SESSION['account'],
            "xnxqid" => $sy
        );
        $info = $this->httpReq($params);
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'xnxqh' => $sy,
		       'info' => json_decode($info,true)
                   ]);
        return $this->fetch();
    }
  
}
