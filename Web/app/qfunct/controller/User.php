<?php
namespace app\qfunct\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;
use think\Log;

class User extends Controller
{

	private $appid = "1109636712"; 

    private $appSecret =  "";

    private function checkSession($value=''){
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['TOKEN'];
        else $this->error("未登录","/?status=E",3);
    }

    public function httpReq($params){
        $header = Conf::getHeader();
        array_push($header,"token:".$_SESSION['TOKEN']);
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }


    public function signalGetOpenid($value=''){
        # code...
        $value = $_POST['code'];
        $APPID = $this->appid; 
        $APPSECRET =  $this->appSecret; 
        $url = "https://api.q.qq.com/sns/jscode2session?appid=$APPID&secret=$APPSECRET&js_code=$value&grant_type=authorization_code";
        $openid =json_decode(Http::httpRequest($url,[],"GET",Conf::getNormalHeader(),false,true),true)['openid'];
        $exist = Db::table("wx_user") -> where("openid",$openid) -> find();
        session_start();
        $_SESSION['openid'] = $openid;
        if(!$exist){
            // Log::write($openid." ".session_id(),'NOTICE');
            return ["Message" => "Yes" , "openid" => $openid , "PHPSESSID" => session_id() , "checkUpdate" => true];
        }else{
            $application = Db::table("application_info") -> where("id",1) -> find();
            $_SESSION['TOKEN'] = $application['info'];
            $_SESSION['account'] = $exist['account'];
            $this -> updateUserInfo($exist['account']);
            return ["Message" => "Ex","PHPSESSID" => session_id(),"openid" => $openid , "checkUpdate" => true];
        }
    }

    private function checkLogin($account,$password){
    	$params=array(
            "method" => "authUser",
            "xh" => $account,
            "pwd" => $password
            );
            $info = Http::httpRequest(Conf::getUrl(),$params,"GET",Conf::getHeader(),false,true);
            if (!$info) return ['status' => 'No' , "info" => "响应超时"];
            $jsonInfo = json_decode($info,true);
    	    if($jsonInfo['flag'] === "1"){
                $r_Time=date("Y-m-d H:i:s",time());
                $exist = Db::table("user") -> where("username",$account) -> find();
                if ($exist) {
                    $this -> updateUserInfo($account);
                }else{
                    $nexRecord['username'] = $account;
                    $nexRecord['name'] = $jsonInfo['userrealname'];
                    $nexRecord['academy'] = $jsonInfo['userdwmc'];
                    $nexRecord['use_time'] = $r_Time;
                    $nexRecord['log_time'] = $r_Time;
                    $nexRecord['access_type'] = 2;
                    Db::table("user") -> insert($nexRecord);
                } 
                $_SESSION['TOKEN'] = $jsonInfo['token'];
                $_SESSION['user'] = $jsonInfo['userrealname'];
                $_SESSION['account'] = $account;
                return ["status" => "Yes","token" => $jsonInfo['token']];
            }else{
                return ["status" => "No","info" => $jsonInfo['msg']];
            }
    }

    public function login(){
    	if (!isset($_POST['account']) || !isset($_POST['password']) || !isset($_POST['openid']))  return ["Message" => "No" , "info" => "数据有误"];
		session_start();
		$info = $this -> checkLogin($_POST['account'],$_POST['password']);
		if ($info['status'] === "Yes") {
            $exist = Db::table("wx_user") -> where("account",$_POST['account']) -> find();
            if(!isset($_SESSION['openid'])) {
                // Log::write($_SERVER,'NOTICE');
                $_SESSION['openid'] = $_POST['openid'];
            }
            $wxUserRecord['password'] = $_POST['password'];
            $wxUserRecord['openid'] = $_SESSION['openid'];
            $wxUserRecord['token'] = $info['token'];
            if(!$exist){
        	    $wxUserRecord['account'] = $_POST['account'];
                Db::table("wx_user") -> insert($wxUserRecord);
            }else{
                Db::table("wx_user") -> where("account",$_POST['account']) -> update($wxUserRecord);
            }
	        return ["Message" => "Yes"];
		}else return ["Message" => "No","info" => $info["info"]];
    }

    private function updateUserInfo($account){
        $r_Time=date("Y-m-d H:i:s",time());
        $exRecord['log_time'] = $r_Time;
        $exRecord['access_type'] = 2;
        Db::table("user") -> where("username",$account) -> exp("log_times","log_times + 1") -> limit(1) -> update($exRecord);
    }


}
