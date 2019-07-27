<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Sw extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return true;
        else $this->error("未登录","/?status=E",3);
    }

    public function httpReq($params){
        $header = Conf::getHeader();
        $header['token'] = $_SESSION['TOKEN'];
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }


    public function grade($sy=""){
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


    public function signalTable($zc=-1){
        $user = $this->checkSession();
        if(isset($_GET['week']) && isset($_GET['term'])){
            $zc = $zc === -1 ? Conf::getCurWeek() : $zc;
            if($zc <= 0) return ["Message" => "Yes" , "week" => $zc , "data" => []];
            $params=array(
            "method" => "getKbcxAzc",
            "xnxqid" => Conf::getCurTerm(),
            "zc" => $zc ,
            "xh" => $_SESSION['account']
            );
            $info = json_decode($this->httpReq($params),true);
            if(!$info) return ["Message" => "No" , "week" => $zc , "data" => []];
            return ["Message" => "Yes" , "week" => $zc ,  "data" => $info];
        }else return ["Message" => false , "week" => $zc , "data" => []];
        
    }

    public function classroomExt(){
        $user = $this->checkSession();
        if (isset($_GET['searchData']) && isset($_GET['searchTime']) && isset($_GET['searchFloor'])) {
            $params = array(
                "method" => "getKxJscx",
                "time" => $_GET['searchData'],
                "idleTime" => $_GET['searchTime'],
                "xqid" => 1,
                "jxlid" => $_GET['searchFloor'],
                "classroomNumber" => "%2B50"
            );
            $info = $this->httpReq($params);
            return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
        }
        return ["MESSAGE" => "No"];
    }

    public function ExamArrange(){
        $user = $this->checkSession();
        $params = array(
            "method" => "getKscx",
            "xh" => $_SESSION['account']
        );
        $info = $this->httpReq($params);
        if($info) return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
        else return ["MESSAGE" => "No"];
    }
}
