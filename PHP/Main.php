<?php
namespace auxiliary;
require_once("Http.php");

# 强智教务管理系统
############################################
$accountSW = "";
$passwordSW = "";
$urlSW = "http://jwgl.sdust.edu.cn/app.do";
############################################

class Main
{
    private $token = "";

    private $headers = [
        "Expect" => "",
        "User-Agent" => "Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )",
        "Referer" => "http://www.baidu.com",
        "accept-encoding" => "deflate, br",
        "accept-language" => "zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2",
        "cache-control" => "max-age=0"
    ];

    private $url = "";

    private $account = "";

    private $password = "";

    function __construct($account,$password,$url){
        $this->url = $url;
        $this->account = $account;
        $this->password = $password;
        $params=array("method" => "authUser","xh" => $account,"pwd" => $password);
        $info = json_decode(Http::httpRequest($this->url,$params,"GET",$this->headers),true);
        if($info){
            echo $info['msg']."\n";
            if($info["flag"] !== "1") exit(0);
            else $this -> headers["token"] = $info['token'];
        }else exit(0);
    }

    private function getHandle($params){
        return json_decode(Http::httpRequest($this->url,$params,"GET",$this->headers),true);
    }

    public function getStudentInfo($value=''){
        # code...
        $params = array(
            "method" => "getUserInfo",
            "xh" => $this->account
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }

    public function getCurrentTime(){
        $params = array(
            "method" => "getCurrentTime",
            "currDate" => date("Y-m-d",time())
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }

    public function getTable($zc=-1){
        $s = $this->getCurrentTime();
        $zc = $zc === -1 ? $s['zc'] : $zc;
        $params=array(
            "method" => "getKbcxAzc",
            "xnxqid" =>  $s['xnxqh'],
            "zc" => $zc ,
            "xh" => $this->account
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }

    public function getGrade($sy=""){
        $params = array(
            "method" => "getCjcx",
            "xh" => $this->account,
            "xnxqid" => $sy
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }

    public function getClassroom($idleTime=""){
        $params = array(
            "method" => "getKxJscx",
            "time" => date("Y-m-d",time()),
            "idleTime" => $idleTime
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }

    public function getExam($idleTime=""){
        $params = array(
            "method" => "getKscx",
            "xh" => $this->account
        );
        $req = $this->getHandle($params);
        print_r($req);
        return ($req);
    }
}

$Q = new Main($accountSW,$passwordSW,$urlSW);
// $Q -> getStudentInfo(); #获取学生信息
// $Q -> getCurrentTime(); #获取学年信息
// $Q -> getTable(); #当前周次课表
// $Q -> getTable(3); #指定周次课表
// $Q -> getGrade("2018-2019-2"); #成绩查询 #无参数查询全部成绩
// $Q -> getClassroom("0102"); #空教室查询 "allday"：全天 "am"：上午 "pm"：下午 "night"：晚上 "0102":1.2节空教室 "0304":3.4节空教室
// $Q -> getExam(); #获取考试信息
