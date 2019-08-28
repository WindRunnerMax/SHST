<?php
namespace app\pubfun\controller;
use app\auxiliary\Http;
use app\auxiliary\Conf;
use think\Db;
use think\Log;

class Cet
{
    public static function getTips($msg){
        $record['history'] = 11;
        $record['update_time'] = date("Y-m-d H:i:s",time());
        Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> update($record);
        return ReturnMessage::text($msg,"四六级准考证号找回功能(山东省)\n小站将向您发送一个验证码，请按照以下格式向我发送您的信息\n姓名\n身份证号\n验证码\n===================\n例如：\n风\n311111111111111111\nabcd\n===================\n发送‘我知道了’获取验证码\n若验证码不清晰请发送‘刷新’\n退出查询回复‘退出’\n本次会话有效期30分钟\n\n<a href='https://mp.weixin.qq.com/s/NqflEYYDtUiXEvuDa7axmg'>点我查看详细教程</a>\n\n");
    }

    public static function msgDispose($msg,$status){
        switch ($msg->Content) {
            case '我知道了':
            case '刷新':
            return self::getImg($msg);
        }
        if($status % 10 === 2) return self::getCetInfo($msg);
    }

    public static function getImg($msg){
        $headers = Conf::getNormalHeader();
        $getCookie = Http::curlHTTP([
            'url' => 'http://cet-bm.neea.edu.cn/Home/QuickPrintTestTicket',
            'headers' => $headers,
            'cookieFlag' => true,
            'stopNext' => true,
            'penetrate' => false
        ]);
        $headers['Cookie'] = $getCookie[0]['Set-Cookie'];
        $imgInfo = Http::curlHTTP([
            'url' => 'http://cet-bm.neea.edu.cn/Home/VerifyCodeImg',
            'headers' => $headers,
            'cookieFlag' => true,
            'stopNext' => true,
            'penetrate' => false
        ]);
        $headers['Cookie'] = $headers['Cookie'].";".$imgInfo[0]['Set-Cookie'];
        $access_token = Token::signalGetPubAccessToken();
        $wx_url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type=image";
        $tmpfname = "/tmp/".(string)time().".png";
        $fhandle = fopen($tmpfname, "w");
        fwrite($fhandle, $imgInfo[1]);
        fclose($fhandle);
        $result = self::http_post($wx_url, $tmpfname);
        // Log::write($result);
        $media = json_decode($result,true);
        unlink($tmpfname);
        if(isset($media['media_id'])){
            $record['history'] = 12;
            $record['cookies'] = $headers['Cookie'];
            Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> update($record);
            return ReturnMessage::img($msg,$media['media_id']);
        }else{
            return ReturnMessage::text($msg,"数据处理错误，请重试");
        }
        
    }

    public static function getCetInfo($msg){
        $data =  explode("\n", $msg->Content);
        if(count($data) !== 3) return ReturnMessage::text($msg,"数据格式有误\n===============\n请检查数据格式 :\n姓名\n身份证号\n验证码");
        $headers = Conf::getNormalHeader();
        $exist = Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> find();
        $headers['Cookie'] = str_replace("\r", "", $exist['cookies']);
        $userInfo = Http::curlHTTP([
            'url' => 'http://cet-bm.neea.edu.cn/Home/ToQuickPrintTestTicket',
            'method' => 'POST',
            'data' => ['provinceCode' => "37" ,
            "IDTypeCode" => "1",
            "IDNumber" => $data[1],
            "Name" => $data[0],
            "verificationCode" => $data[2]],
            'headers' => $headers,
            'cookieFlag' => false,
            'stopNext' => true,
            'penetrate' => false
        ]);
        $userInfo = json_decode($userInfo,true);
        if($userInfo['ExceuteResultType'] !== 1) return ReturnMessage::text($msg,$userInfo['Message']."\n==================\n1.请检查验证码\n2.请检查姓名与身份证号(18位)是否正确\n3.请确定您是山东地区考生");
        $userInfo = json_decode($userInfo['Message'],true);
        $info = Http::curlHTTP([
            'url' => 'http://106.12.90.18:8765/api/pdf/',
            'method' => 'POST',
            'data' => ['SID' => $userInfo[0]['SID']],
            'headers' => $headers,
            'cookieFlag' => false,
            'stopNext' => true,
            'penetrate' => false
        ]);
        $info = json_decode($info,true);
        // Log::write($info);
        if($info['status'] === '0'){
            $record['history'] = 0;
            Db::table("wx_pub") -> where("openid",$msg->FromUserName) -> update($record);
            return ReturnMessage::text($msg,$info['exam_name']."\n".$info['school']."\n".$info['number']);
        }else{
            return ReturnMessage::text($msg,"数据处理错误");
        }
    }


    private static function http_post($url ='' , $fileurl = '' ){
        $curl = curl_init();
        if(class_exists('\CURLFile')){
            curl_setopt ( $curl, CURLOPT_SAFE_UPLOAD, true); 
            $data = array('media' => (new \CURLFile($fileurl)));
        }else{
            if (defined ( 'CURLOPT_SAFE_UPLOAD' )) {  
                curl_setopt ( $curl, CURLOPT_SAFE_UPLOAD, false );  
            }  
            $data = array('media' => '@' . realpath($fileurl));
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
