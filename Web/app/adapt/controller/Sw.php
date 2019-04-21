<?php
namespace app\adapt\controller;
use think\Controller;
use think\Db;
use app\adapt\controller\Http;

class Sw extends Controller
{

	private $url = "http://jwgl.sdust.edu.cn/app.do";

    private $header = array(
        'User-Agent' => 'Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer' => 'http://www.baidu.com',
        'accept-encoding' => 'gzip, deflate, br',
        'accept-language' => 'zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control' => 'max-age=0'
    );

    private function checkSession($value='')
    {
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录","/?status=E",3);
    }

    private function getCtx()
    {
        $ctx="";
        if($_SERVER['SERVER_NAME']=="localhost") $ctx =  "/Sw" ;
        return $ctx;
    }

    public function login($value='')
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $params=array(
	        "method" => "authUser",
	        "xh" => $_POST['username'],
	        "pwd" => $_POST['password']
	        );
	        $http = new Http();
	        $info = $http->httpRequest($this->url,$params,"GET");
	        $jsonInfo = json_decode($info,true);
	        if ($jsonInfo['flag'] === "1") {
	        	session_start();
	        	$_SESSION['TOKEN'] = $jsonInfo['token'];
                $_SESSION['user'] = $jsonInfo['userrealname'];
	        	$_SESSION['account'] = $_POST['username'];
	        	return redirect('/index/sw/overview');
	        }else return $this->error($jsonInfo['msg'],"/?status=E",3);
        }else return "";
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

    public function overview()
    {
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $this->checkSession() ]);
        return $this->fetch();
    }

    public function info()
    {
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $this->checkSession() ]);
        return $this->fetch();
    }

    public function table($zc=-1)
    {
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
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'zc' => $zc,
			"info" => json_decode($info,true)
                   ]);
        return $this->fetch();
    }

    public function classroom($idleTime="")
    {
        $user = $this->checkSession();
        $info = "";
        if ($idleTime!=="") {
            $params = array(
                "method" => "getKxJscx",
                "time" => date("Y-m-d",time()),
                "idleTime" => $idleTime
            );
            $info = $this->httpReq($params);
        }
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'info' => $info === "" ? array() : json_decode($info,true)
                   ] );
        return $this->fetch();
    }

    public function grade($sy="")
    {
        $user = $this->checkSession();
	//$sy = $sy === "" ? json_decode($this->getCurrentTime(),true)['xnxqh'] : $sy ;
	$params = array(
            "method" => "getCjcx",
            "xh" => $_SESSION['account'],
            "xnxqid" => $sy
        );
        $info = $this->httpReq($params);
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'xnxqh' => $sy,
		       'info' => json_decode($info,true)
                   ]);
        return $this->fetch();
    }
    
}
