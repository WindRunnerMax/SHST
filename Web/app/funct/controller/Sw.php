<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
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
        $colorList = Conf::getColorList();
        $colorN = count($colorList);
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => $s['xnxqh'],
        "zc" => $zc === -1 ? $s['zc'] : $zc ,
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
        
        return ["Message" => "Yes" , "data" => $info];
    }

    public function weather(){
        $ran = rand(1000000000,1000000000000);
        $url = "http://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=$ran";
        $weatherInfo = Http::httpRequest($url,array(),"GET",Conf::getHeader());
        return  ["data" => json_decode($info,true)];
    }

    public function classroom($idleTime=""){
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
}
