<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Lib extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['TOKEN'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }

    public function httpReq($params = array()){
        $header = Conf::getNormalHeader();
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }

    public function signalBookquery(){
        session_start();
        $page = -1;
        $q = "";
        $info = "";
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            $params = array(
                "q" => $_GET['q'],
                "searchType" => "standard",
                "isFacet" => "true",
                "view" => "standard",
                "rows" => "10",
                "displayCoverImg" => ""
            );
            if (isset($_GET['page'])) {
                $params['page'] = $_GET['page'];
                $page = $_GET['page'];
            }else{
                $page = 1;
            } 
            $header = Conf::getNormalHeader();
            $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/search",$params,"GET",$header,false,true);          
        }
        return ["Message" => "Yes" , 'page' => $page,'q' => $q,'info' => $info ];
    }

    public function signalBookdetail(){
        session_start();
        $id = $_GET['id'];
        $isbn = "";
        $infoArr = array();
        $infoArrInner = array();
        $url = "http://interlib.sdust.edu.cn/opac/m/book/" . $id;
        $header = Conf::getNormalHeader();
        $info = Http::httpRequest($url,array(),"GET",$header,false,true);
        return ["Message" => "Yes" , 'info' => $info];
    }

    public function signalLibquery(){
        $user = $this->checkSession();
        $account = substr($_SESSION['account'],2);
        $params = array(
            "rdid" => $account,
            "rdPasswd" => md5($account)
        );
        $header = Conf::getNormalHeader();
        $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/reader/doLogin",$params,"GET",$header,true,true);
        $cookie = explode(";", $info[0]['Set-Cookie'])[0];
        $header['Cookie'] = $cookie.'; libraryReaderRdid='.$account;
        $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/loan/renewList",[],"GET",$header,false,true);
        return ["Message" => "Yes" , 'info' => $info];
    }
    
}
