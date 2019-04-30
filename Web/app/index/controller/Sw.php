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

    public function overview(){
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $this->checkSession() ]);
        return $this->fetch();
    }

    public function info(){
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $this->checkSession(),
                       'tips' => Conf::getTips()
                        ]);
        return $this->fetch();
    }

    public function table($zc=-1){
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user,
                       'zc' => $s['zc']
                   ]);
        return $this->fetch();
    }

    public function classroom($idleTime=""){
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

    public function libquery(){
        $user = $this->checkSession();
        $account = substr($_SESSION['account'],2);
        $params = array(
            "rdid" => $account,
            "rdPasswd" => md5($account),
            "returnUrl" => "/m/loan/renewList",
            "view" => "action"
        );
        $header = Conf::getHeader();
        $http = new Http();
        $info = $http->httpRequest("http://interlib.sdust.edu.cn/opac/m/reader/doLogin",$params,"POST",$header);
        preg_match_all("/<li>[\s\S]*?<\/li>/",$info,$match);
        $infoArr = array();
        foreach ($match[0] as $value) {
            $infoArrInner = array();
            preg_match("/<h3>.*?<\/h3>/",$value,$singalMatch);
            $replace = array('<h3>','</h3>');
            if (count($singalMatch) === 0) {
                break;
            }
            array_push($infoArrInner,str_replace($replace,"",$singalMatch[0]));
            preg_match_all("/<p.*?>[\s\S]*?<\/p>/",$value,$allMatch);
            $replace = array('<p >','</p>','<p  >',"	");
            foreach ($allMatch[0] as $value2) {
                array_push($infoArrInner,str_replace($replace,"",$value2));
            }
            array_push($infoArr,$infoArrInner);
        }
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $user ,
                       'libInfo' => $infoArr
                        ]);
        return $this->fetch();
    }

    public function bookquery(){
        $infoArr = array();
        $page = -1;
        $q = "";
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $params = array(
                "q" => $_POST['q'],
                "searchType" => "standard",
                "isFacet" => "true",
                "view" => "standard",
                "rows" => "10",
                "displayCoverImg" => ""
            );
            if (isset($_POST['page'])) {
                $params['page'] = $_POST['page'];
                $page = $_POST['page'];
            }else{
                $page = 1;
            } 
            $header = Conf::getHeader();
            $http = new Http();
            $info = $http->httpRequest("http://interlib.sdust.edu.cn/opac/m/search",$params,"GET",$header);
            preg_match_all("/<li onclick.*?>[\s\S]*?<\/li>/",$info,$match);
            foreach ($match[0] as $value) {
                $infoArrInner = array();
                preg_match_all("/<em>.*<\/em>/",$value,$singalMatch);
                $replace = array('<em>','</em>');
                foreach ($singalMatch[0] as  $value2) {
                    array_push($infoArrInner,str_replace($replace,"",$value2));
                }
                preg_match("/javascript:bookDetail(.)*;/",$value,$singalMatch);
                $url = "http://interlib.sdust.edu.cn" . explode(";",explode("'",$singalMatch[0])[1])[0];
                array_push($infoArrInner, $url);
                array_push($infoArr,$infoArrInner);
            }
        }
        $this->assign(['ctx' => $this->getCtx(),
                       'user' => $this->checkSession() ,
                       'libInfo' => $infoArr,
                       'page' => $page,
                       'q' => $q
                    ]);
        return $this->fetch();
    }
    
}
