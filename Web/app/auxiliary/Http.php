<?php
namespace app\auxiliary;
use think\Db;
use app\auxiliary\Conf;
use think\Log;
use copy\Copy;
use redis\Redis;

class Http
{

    public static function curlHTTP($arr = []){
        $options=[
          "url" => "127.0.0.1", 
          "data" => [] , 
          "method" => 'GET',
          "headers" => [] , 
          "cookieFlag" => false, 
          "stopNext" => false,
          "penetrate" => true
        ];
        $OP = Copy::sCopy($options,$arr);
        return self::httpRequest($OP['url'],$OP['data'],$OP['method'],$OP['headers'],$OP['cookieFlag'],$OP['stopNext'],$OP['penetrate']);
    }

    public static function httpRequest(
      $url, 
      $data=array(), 
      $method='GET',
      $headers = [] , 
      $cookieFlag = false, 
      $stopNext = false,
      $penetrate = true) {
        self::UrlHeadersDispose($url,$headers,$penetrate);
        $curl = self::getCurl($url,$data,$method,$headers,$cookieFlag);
        try {
          $result = curl_exec($curl);    //执行操作
          // if(curl_errno($curl)) Log::write(curl_error($curl)."\n".$url."\n".implode("\n", $headers),'ERROR');
          if($result && !$stopNext){ //更新token
            $jsonResult = json_decode($result,true);
            if (isset($jsonResult['token']) && $jsonResult['token'] === "-1") {
              $result = self::reGetToken($url,$data,$method,$headers,$cookieFlag);
            }
          }
          return self::resultDipose($curl,$cookieFlag,$result);
        } catch (Exception $e) {
          Log::write("HTTP异常:".(string)$e,'error');
        }finally{
          curl_close($curl); 
        } 
    }

    private static function UrlHeadersDispose(&$url,&$headers,$penetrate){
        $headerDisposeCurl = [];
        $ipArr = [mt_rand(1, 255),mt_rand(1, 255),mt_rand(1, 255),mt_rand(1, 255)];
        $headers["X-FORWARDED-FOR"] = $ipArr[0].".".$ipArr[1].".".$ipArr[2].".".$ipArr[3];
        $headers["CLIENT-IP"] = $ipArr[0].".".$ipArr[1].".".$ipArr[2].".".$ipArr[3];
        foreach ($headers as $key => $value) {
          array_push($headerDisposeCurl, $key.':'.$value);
        }
        $headers = $headerDisposeCurl;
      }


      private static function reGetToken($url,$data,$method,$headers,$cookieFlag){
        $exist = Db::table("application_info") -> where("id",4) -> find();
        $params=["method" => "authUser","xh" => $exist['pid'],"pwd" => $exist['info']];
        $info = self::httpRequest(Conf::getUrl(),$params,"GET",Conf::getHeader(),false,true);
        $jsonInfo = json_decode($info,true);
        if($jsonInfo['flag'] === "1") {
            $_SESSION['TOKEN'] = $jsonInfo['token'];
            $header = Conf::getHeader();
            $header['token'] = $jsonInfo['token'];
            $record['info'] = $jsonInfo['token'];
            $record['update_time'] = date("Y-m-d H:i:s",time());
            Db::table("application_info") -> where("id",1) -> update($record);
            return self::httpRequest($url,$data,$method,$header,$cookieFlag,true);
        }else return false;
      }

      private static function getCurl($url, $data, $method,$headers,$cookieFlag){
        $curl = curl_init();  // 启动一个CURL会话
        if (count($headers) >= 1) curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if($method=='GET') $url = self::urlDispose($url,$data);
        curl_setopt($curl, CURLOPT_URL, $url);  // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);  // 模拟用户使用的浏览器
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转 
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);  // 自动设置Referer
        if($method=='POST'){
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != '')  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);  // 设置超时限制防止死循环
        if($cookieFlag) curl_setopt($curl, CURLOPT_HEADER, 2);  // 显示返回的Header区域内容
        else curl_setopt($curl, CURLOPT_HEADER, 0);  // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // 获取的信息以文件流的形式返回
        return $curl;
      }

      private static function resultDipose($curl,$cookieFlag,$result){
        if(!$cookieFlag){
            return $result;
        }else{
          $responseHeader_size  = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
          $responseHeadersString  = substr($result, 0, $responseHeader_size);
          $responseBody   = substr($result, $responseHeader_size);
          $responseHeadersArr = explode("\n", $responseHeadersString);
          $responseHeaders = [];
          foreach ($responseHeadersArr as $value) {
            $info = explode(": ", $value);
            if (count($info) >= 2)  $responseHeaders[$info[0]] = $info[1];
          }
          return [$responseHeaders,$responseBody];
        }
      }

      private static function urlDispose($url,$arr){
        $ret = "";
        reset($arr);
        while (list($k, $v) = each($arr)){
            $tmp = "$k"."="."$v";
            $ret = $ret."&".$tmp;
        }
        if($ret !== "") $url = $url."?".$ret;
        return $url;
      }

      public static function socketTest($ip = '127.0.0.1',$port='80'){
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($socket);
        socket_connect($socket,$ip, $port);
        socket_set_block($socket);
        $r = $w = $f = array($socket);
        $status = socket_select($r, $w, $f, 1);
        socket_close($socket);
        switch($status){
            case 1:
                return true;
            default :
                return false;
        }
      }

      public static function getPenetrate(){
        Redis::initRedis();
        $res = Redis::get('penetrate');
        if($res === false){
            $status = Db::table("application_info") -> field("info") -> where("id",3) -> find();
            $res = $status['info'];
            Redis::set('penetrate',$res);
        }
        return $res === "1" ? true : false;
      }

}
