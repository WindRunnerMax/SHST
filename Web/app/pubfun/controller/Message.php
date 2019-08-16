<?php
namespace app\pubfun\controller;
use think\Controller;
use think\Db;
use think\Log;

class Message extends Controller
{   

    public function getMessage(){
        // Token::verifyHost();
        try {
            $encodingAesKey = "";
            $token = "";
            $appId = "";
            $msgSignature = $_GET['msg_signature'];
            $timestamp = $_GET['timestamp'];
            $nonce = $_GET['nonce'];
            $xml = file_get_contents("php://input");
            $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
            $msg='';
            $errCode = $pc->decryptMsg($msgSignature, $timestamp, $nonce, $xml, $msg);
            // Log::write($msg);
            switch ($msg->MsgType) {
                case 'event':
                    return $this -> userEvent($msg);
                    break;
                case 'text':
                    return $this -> userMsg($msg);
                    break;
                default:
                    return "";
                    break;
            }
        } catch (Exception $e) {
            return ReturnMessage::text($msg,"数据处理错误，请重试");
        }
    }

    private function userEvent($msg){
        switch ($msg->Event) {
            case 'subscribe':
                return ReturnMessage::text($msg,MsessageMap::$subscribe."\n".MsessageMap::$funct);
                break;
            default:
                return "";
                break;
        }
    }

    private function userMsg($msg){
        switch ($msg->Content) {
            case '功能':
                return ReturnMessage::text($msg,MsessageMap::$funct);
            case '欢迎':
                return ReturnMessage::text($msg,MsessageMap::$subscribe."\n".MsessageMap::$funct);
            case '退出':
                return self::exitApp($msg);
            case '四六级':
                return Cet::getTips($msg);
            case '测试':
                return self::msgTest($msg);
        }
        $status = $this -> DbDispose($msg);
        if($status === 0){
            switch ($msg->Content) {
                case '1':
                    return Cet::getTips($msg);
            }
        }else{
            switch ((int)($status/10)) {
                case 1:
                    return Cet::msgDispose($msg,$status);
            }
        }
        return ReturnMessage::text($msg,MsessageMap::$funct);
    }

    private function DbDispose($msg){
        $exist = Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> find();
        if($exist){
            if ($exist['history'] === 0) return 0;
            if(time() - strtotime($exist['update_time']) >= 1800){
                $record['history'] = 0;
                Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> update($record);
                return 0;
            }
            return $exist['history'];
        }else{
            $record = [
                'openid' => $msg->FromUserName,
                'update_time' => date("Y-m-d H:i:s",time())
            ];
            Db::table("wx_pub") -> insert($record);
            return 0;
        }
    }

    public static function exitApp($msg){
        $record['history'] = 0;
        Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> update($record);
        return ReturnMessage::text($msg,"感谢您的使用");
    }

    public static function msgTest($msg){
        return ReturnMessage::text($msg,"<a href='https://mp.weixin.qq.com/s/NqflEYYDtUiXEvuDa7axmg'>详细教程</a>");
    }

}

