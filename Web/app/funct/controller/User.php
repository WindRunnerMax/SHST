<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;
use think\Log;

class User extends Controller
{

    private $appid = "wx387c0e87230e4cc9"; 

    private $appSecret =  "";

    private function checkSession($value=''){
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['TOKEN'];
        else $this->error("未登录","/?status=E",3);
    }

    public function httpReq($params){
        $header = Conf::getHeader();
        $header['token'] = $_SESSION['TOKEN'];
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }

    public function signalGetOpenid($value=''){
        # code...
        $value = $_POST['code'];
        $APPID = $this->appid; 
        $APPSECRET =  $this->appSecret; 
        $openid = null;
        $exist = false;
        $initData = [
            "articalName" => "各位亲爱的小萌新们看过来嗷",
            "articalUrl" => "https://mp.weixin.qq.com/s/V0ez1xl4CE9jioWF6OX7wg"
        ];
        session_start();
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$APPID&secret=$APPSECRET&js_code=$value&grant_type=authorization_code";
        $openidObject =json_decode(Http::curlHTTP(['url' => $url, 'stopNext' => true, 'penetrate' => false ]),true);
        if ($openidObject && array_key_exists("openid", $openidObject)) {
            $openid = $openidObject['openid'];
            $_SESSION['openid'] = $openid;
            $exist = Db::table("wx_user") -> where("openid",$openid) -> find();
        }
        if(!$exist){
            return ["Message" => "Yes" , "openid" => $openid , "notify" => Conf::getWechatAppTips() , "initData" => $initData];
        }else{
            $application = Db::table("application_info") -> where("id",1) -> find();
            $_SESSION['TOKEN'] = $application['info'];
            $_SESSION['account'] = $exist['account'];
            $this -> updateUserInfo($exist['account']);
            return ["Message" => "Ex","openid" => $openid , "notify" => Conf::getWechatAppTips() , "initData" => $initData];
        }
    }

    private function checkLogin($account,$password){
        $params= ["method" => "authUser", "xh" => $account, "pwd" => $password];
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",Conf::getHeader(),false,true);
        if (!$info) return ['status' => 'No' , "info" => "响应超时"];
        $jsonInfo = json_decode($info,true);
        if($jsonInfo['flag'] === "1"){
            $r_Time=date("Y-m-d H:i:s",time());
            $exist = Db::table("user_info") -> where("username",$account) -> find();
            if ($exist) {
                $this -> updateUserInfo($account);
            }else{
                $nexRecord['username'] = $account;
                $nexRecord['name'] = $jsonInfo['userrealname'];
                $nexRecord['academy'] = $jsonInfo['userdwmc'];
                $nexRecord['use_time'] = $r_Time;
                $nexRecord['log_time'] = $r_Time;
                $nexRecord['access_type'] = 1;
                Db::table("user_info") -> insert($nexRecord);
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
        if (!isset($_POST['account']) || !isset($_POST['password']) || !isset($_POST['openid'])) return ["Message" => "No" , "info" => "数据有误"];
        session_start();
        $info = $this -> checkLogin($_POST['account'],$_POST['password']);
        if ($info['status'] === "Yes") {
            $exist = Db::table("wx_user") -> where("account",$_POST['account']) -> find();
            if(!isset($_SESSION['openid'])) $_SESSION['openid'] = $_POST['openid'];
            $r_Time = date("Y-m-d H:i:s",time());
            Db::table('application_info') -> where('id',1) -> update(['info' => $info['token'], 'update_time' => $r_Time]);
            Db::table('application_info') -> where('id',4) -> update(['pid' => $_POST['account'], 'info' => $_POST['password'], 'update_time' => $r_Time]);
            $wxUserRecord['openid'] = $_SESSION['openid'];
            if(!$exist){
                $wxUserRecord['account'] = $_POST['account'];
                Db::table("wx_user") -> insert($wxUserRecord);
            }else{
                Db::table("wx_user") -> where("account",$_POST['account']) -> update($wxUserRecord);
            }
            return ["Message" => "Yes"];
        }else return ["Message" => "No","info" => $info["info"]];
    }

    public function getUserInfo($value=''){
        $this->checkSession();
        $info = Db::table("user_info") -> field('academy,name,username,class_info') -> where("username",$_SESSION['account']) -> find();
        if(!$info) return ["info" => ["username" => $_SESSION['account'] , "name" => "Invalid" , "academy" => "Invalid"]];
        if ($info['class_info'] === "") {
            $params = ["method" => "getUserInfo","xh" => $_SESSION['account']];
            $SWInfo = json_decode($this->httpReq($params),true);
            if($SWInfo){
                $record['class_info'] = $SWInfo['bj'];
                Db::table("user_info") -> where("username",$_SESSION['account']) -> update($record);
            }
        }
        return ["info" => $info];
    }

    private function updateUserInfo($account){
        $r_Time=date("Y-m-d H:i:s",time());
        $exRecord['log_time'] = $r_Time;
        $exRecord['access_type'] = 1;
        Db::table("user_info") -> where("username",$account) -> exp("log_times","log_times + 1") -> limit(1) -> update($exRecord);
    }

    private function testToken(){
        session_start();
        $_SESSION['TOKEN'] = "1111";
    }

}
