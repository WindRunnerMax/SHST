<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Sw extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }

    public function login($value='')
    {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['flag'])) {
            $params=array(
            "method" => "authUser",
            "xh" => $_POST['username'],
            "pwd" => $_POST['password']
            );
            $info = Http::httpRequest(Conf::getUrl(),$params,"GET");
            if (!$info) {
                return "<br>啊哦，可能出了点问题";
            }
            $jsonInfo = json_decode($info,true);
    	    if($jsonInfo['flag'] === "1"){
		#
                try {
                    $r_Time=date("Y-m-d H:i:s",time());
                    $exist = Db::table("user") -> where("username",$_POST['username']) -> find();
                    if ($exist) {
                        $exRecord['log_time'] = $r_Time;
                        $exRecord['access_type'] = 0;
                        Db::table("user")
                        -> where("username",$_POST['username'])
                        -> exp("log_times","log_times + 1")
                        -> limit(1) -> update($exRecord);
                    }else{
                        $nexRecord['username'] = $_POST['username'];
                        $nexRecord['name'] = $jsonInfo['userrealname'];
                        $nexRecord['academy'] = $jsonInfo['userdwmc'];
                        $nexRecord['use_time'] = $r_Time;
                        $nexRecord['log_time'] = $r_Time;
                        $nexRecord['access_type'] = 0;
                        Db::table("user") -> insert($nexRecord);
                    }
                } catch (Exception $e) {
                    Log::write($e,'notice');
                }  
                #
                session_start();
                $_SESSION['TOKEN'] = $jsonInfo['token'];
                $_SESSION['user'] = $jsonInfo['userrealname'];
                $_SESSION['account'] = $_POST['username'];
                return redirect(Conf::getCtx().'/index.php/index/sw/overview');
    	     }else return $this->error($jsonInfo['msg'],Conf::getCtx()."/?status=E",3);
        }else return "";
    }

    public function httpReq($params = array()){
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

    public function overview(){
        $ran = rand(1000000000,1000000000000);
        $url = "http://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=$ran";
        $weatherInfo = Http::httpRequest($url,array(),"GET",Conf::getHeader());
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(), 
                       'tipsFlag' => Conf::getNewTips(),
                       'weather' => json_decode($weatherInfo,true)
                        ]);
        return $this->fetch();
    }

    

    public function table($zc=-1){
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $this->assign(['ctx' => Conf::getCtx(),
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
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'info' => $info === "" ? array() : json_decode($info,true), 
                       'tipsFlag' => Conf::getNewTips()
                   ] );
        return $this->fetch();
    }

    public function grade($sy=""){
        $user = $this->checkSession();
        $s = json_decode($this->getCurrentTime(),true);
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'xnxqh' => $s['xnxqh']
                   ]);
        return $this->fetch();
    }

    public function library($value=''){
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession()
                        ]);
        return $this->fetch();
    }

    public function bookquery(){
        $infoArr = array();
        $page = -1;
        $q = "";
        $pageInfo = "";
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
            $header = Conf::getHeader();
            $http = new Http();
            $info = $http->httpRequest("http://interlib.sdust.edu.cn/opac/m/search",$params,"GET",$header);
            preg_match_all("/<li onclick.*?>[\s\S]*?<\/li>/",$info,$match);
            preg_match("/第[\S]*页/",$info,$pageMatch);
            $pageInfo = (isset($pageMatch[0]) ? $pageMatch[0] : "");
            foreach ($match[0] as $value) {
                $infoArrInner = array();
                preg_match_all("/<em>.*<\/em>/",$value,$singalMatch);
                $replace = array('<em>','</em>');
                foreach ($singalMatch[0] as  $value2) {
                    array_push($infoArrInner,str_replace($replace,"",$value2));
                }
                preg_match("/javascript:bookDetail(.)*;/",$value,$singalMatch);
                $url = Conf::getCtx()."/index.php/bookdetail/" . explode("/",explode(";", $singalMatch[0])[0])[4];
                array_push($infoArrInner, $url);
                array_push($infoArr,$infoArrInner);
            }
        }
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession() ,
                       'libInfo' => $infoArr,
                       'page' => $page,
                       'q' => $q,
                       'pageInfo' => $pageInfo
                    ]);
        return $this->fetch();
    }

    public function bookdetail($id){
        $isbn = "";
        $infoArr = array();
        $infoArrInner = array();
        $url = "http://interlib.sdust.edu.cn/opac/m/book/" . $id;
        $header = Conf::getHeader();
        $http = new Http();
        $info = $http->httpRequest($url,array(),"GET",$header);
        preg_match("/<table.*?>[\s\S]*?<\/table>/",$info,$pageMatch);
        $pageMatch[0] = (isset($pageMatch[0]) ? $pageMatch[0] : "");
        preg_match("/<h2>.*?<\/h2>/",$pageMatch[0],$pageMatchH2);
        array_push($infoArr,str_replace(array("<h2>","</h2>"),"",$pageMatchH2[0]));
        preg_match_all("/<tr><td>.*<[\/]?td><\/tr>/",$pageMatch[0],$pageMatchInfo);
        foreach ($pageMatchInfo[0] as  $value) {
            array_push($infoArr,str_replace(array("<tr><td>","</td></tr>","<td></tr>"),"",$value));
        }
        $isbn = str_replace("-","",explode(":", $infoArr[1])[1]);

        preg_match_all("/<li>[\s\S]*?<\/li>/",$info,$match);
        foreach ($match[0] as $value) {
            preg_match_all("/<p.*?>[\s\S]*?<\/p>/",$value,$allMatch);
            foreach ($allMatch[0] as $value2) {
                array_push($infoArrInner,$value2);
            }
        }
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(),
                       'infoArr' => $infoArr,
                       'infoArrInner' => $infoArrInner,
                       'isbn' => $isbn
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
            $replace = array('<p >','</p>','<p  >',"    ");
            foreach ($allMatch[0] as $value2) {
                array_push($infoArrInner,str_replace($replace,"",$value2));
            }
            array_push($infoArr,$infoArrInner);
        }
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user ,
                       'libInfo' => $infoArr
                        ]);
        return $this->fetch();
    }

    public function urlshare(){
        $data = Db::table("url_share") -> field("name,url") -> select();
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(),
                       'url' => $data
                        ]);
        return $this->fetch();
    }

    public function github(){
        return redirect('https://github.com/WindrunnerMax/SW');
    }

    public function updateHis(){
        return redirect('https://github.com/WindrunnerMax/SW/blob/SDUST/ChangeLog.md');
    }

    public function info(){
        $userCount = Db::table("user") -> count();
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(),
                       'tips' => Conf::getTips(), 
                       'tipsFlag' => Conf::getNewTips(),
                       'userCount' => $userCount
                        ]);
        return $this->fetch();
    }
    
}
