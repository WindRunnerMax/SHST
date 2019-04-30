<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Sw extends Controller
{

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
        if($_SERVER['SERVER_NAME']=="localhost") $ctx =  "/Swisdom/Web" ;
        return $ctx;
    }

    public function login($value='')
    {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['flag'])) {
            $params=array(
            "method" => "authUser",
            "xh" => $_POST['username'],
            "pwd" => $_POST['password']
            );
            $http = new Http();
            $info = $http->httpRequest(Conf::getUrl(),$params,"GET");
            if (!$info) {
                return "<br>啊哦，可能出了点问题";
            }
            $jsonInfo = json_decode($info,true);
    	    if($jsonInfo['flag'] === "1"){
                session_start();
                $_SESSION['TOKEN'] = $jsonInfo['token'];
                $_SESSION['user'] = $jsonInfo['userrealname'];
                $_SESSION['account'] = $_POST['username'];
                if($_POST['flag'] === "0") return redirect('/index/sw/overview');
                else return redirect('/adapt/sw/overview');
    	     }else return $this->error($jsonInfo['msg'],"/?status=E",3);
        }else return "";
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
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'zc' => $s['zc']
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
        $s = json_decode($this->getCurrentTime(),true);
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'xnxqh' => $s['xnxqh']
                   ]);
        return $this->fetch();
    }
    
}
