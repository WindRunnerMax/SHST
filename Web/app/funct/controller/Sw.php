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
        array_push($header,"token:".$_SESSION['TOKEN']);
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }

    public function getCurrentTime(){
        $params = array(
        "method" => "getCurrentTime",
        "currDate" => date("Y-m-d",time())
        );
        return $this->httpReq($params);
    }

    public function tableToday($zc=-1){
        $colorList = Conf::getColorList();
        $colorN = count($colorList);
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $zc = $zc === -1 ? $s['zc'] : $zc;
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        date_default_timezone_set('PRC');//将时区设置为北京时区
        $weekDay = (int)date("w");
        $info = json_decode($this->httpReq($params),true);
        if ($info) {
            $tableArr = array();
            foreach ($info as $value) {
                $day = (int)$value['kcsj'][0];
                if ($day !== $weekDay) continue;
                $arrInner = array();
                $knot = (int)((int)substr($value['kcsj'],1,2)/2);
                $md5Str = md5($value['kcmc']);
                $colorSignal = $colorList[abs((ord($md5Str[0]) - (ord($md5Str[3]))) % $colorN)] ;
                array_push($arrInner, $day);
                array_push($arrInner, $knot);
                array_push($arrInner, explode("（", $value['kcmc'])[0]);
                array_push($arrInner, $value['jsxm']);
                array_push($arrInner, $value['jsmc']);
                array_push($arrInner, $colorSignal);
                array_push($tableArr, $arrInner);
            }
            $info = $tableArr;
        }
        
        return ["Message" => "Yes" , "year" => $s['xnxqh'] , "week" => $zc , "data" => $info];
    }


    public function table($zc=-1){
        $colorList = Conf::getColorList();
        $colorN = count($colorList);
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $zc = $zc === -1 ? $s['zc'] : $zc;
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        $info = json_decode($this->httpReq($params),true);
        if ($info) {
            $tableArr = array();
            foreach ($info as $value) {
                $arrInner = array();
                $day = (int)$value['kcsj'][0] - 1;
                $knot = (int)((int)substr($value['kcsj'],1,2)/2);
                $md5Str = md5($value['kcmc']);
                $colorSignal = $colorList[abs((ord($md5Str[0]) - (ord($md5Str[3]))) % $colorN)] ;
                array_push($arrInner, $day);
                array_push($arrInner, $knot);
                array_push($arrInner, explode("（", $value['kcmc'])[0]);
                array_push($arrInner, $value['jsxm']);
                array_push($arrInner, $value['jsmc']);
                array_push($arrInner, $colorSignal);
                // if(array_key_exists($day,$tableArr) && array_key_exists($knot,$tableArr[$day])){
                //      $tableArr[$day][$knot] = array_merge($tableArr[$day][$knot],$arrInner);
                // }else{
                     $tableArr[$day][$knot] = $arrInner;
                // }
            }
            $info = $tableArr;
        }
        
        return ["Message" => "Yes" , "year" => $s['xnxqh'] , "week" => $zc , "data" => $info];
    }

    public function signalTable($zc=-1){
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        if(!$s) return ["Message" => "No" , "year" => "" , "week" => $zc , "data" => []];
        $zc = $zc === -1 ? $s['zc'] : $zc;
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        $info = json_decode($this->httpReq($params),true);
        if(!$info) return ["Message" => "No" , "year" => $s['xnxqh'] , "week" => $zc , "data" => []];
        return ["Message" => "Yes" , "year" => $s['xnxqh'] , "week" => $zc , "data" => $info];
    }

    public function weather(){
        $ran = rand(1000000000,1000000000000);
        $url = "http://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=$ran";
        $weatherInfo = Http::httpRequest($url,array(),"GET",Conf::getHeader());
        return  ["data" => json_decode($info,true)];
    }

    public function classroom(){
        $user = $this->checkSession();
        $info = null;
        if (isset($_GET['query'])) {
            $params = array(
                "method" => "getKxJscx",
                "time" => date("Y-m-d",time()),
                "idleTime" => $_GET['query']
            );
            $info = $this->httpReq($params);
        }
        return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
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

    public function signalGgetCurrentTime(){
        $params = array(
        "method" => "getCurrentTime",
        "currDate" => date("Y-m-d",time())
        );
        $info = $this->httpReq($params);
        if($info) return ["Message" => true , "info" => $info];
        else return ["Message" => false];
    }

    public function signalTable2($zc=-1){
        $user = $this->checkSession();
        if(isset($_GET['week']) && isset($_GET['term'])){
            $zc = $zc === -1 ? Conf::getCurWeek() : $zc;
            $params=array(
            "method" => "getKbcxAzc",
            "xnxqid" => Conf::getCurTerm(),
            "zc" => $zc ,
            "xh" => $_SESSION['account']
            );
            $info = json_decode($this->httpReq($params),true);
            if(!$info) return ["Message" => "No" , "year" => Conf::getCurTerm() , "week" => $zc , "data" => []];
            return ["Message" => "Yes" , "year" => Conf::getCurTerm() , "week" => $zc , "data" => $info];
        }else return ["Message" => false , "year" => "2020-2021-1" , "week" => 0 , "data" => []];
        
    }

    public function classroomExt(){
        $user = $this->checkSession();
        $info = null;
        if (isset($_GET['searchData']) && isset($_GET['searchTime'])) {
            $params = array(
                "method" => "getKxJscx",
                "time" => $_GET['searchData'],
                "idleTime" => $_GET['searchTime']
            );
            $info = $this->httpReq($params);
        }
        return ["MESSAGE" => "Yes" , "data" => json_decode($info,true)];
    }

    public function classroomExt2(){
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
}
