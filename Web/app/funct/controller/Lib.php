<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Lib extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }

    public function httpReq($params = array()){
        $header = Conf::libGetHeader();
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
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
            $header = Conf::libGetHeader();
            $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/search",$params,"GET",$header);
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
                $url = explode("/",explode(";", $singalMatch[0])[0])[4];
                array_push($infoArrInner, $url);
                array_push($infoArr,$infoArrInner);
            }
        }
        return ['libInfo' => $infoArr,'page' => $page,'q' => $q,'pageInfo' => $pageInfo ];
    }

    public function signalBookquery(){
        $page = -1;
        $q = "";
        $info = "";
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
            $header = Conf::libGetHeader();
            $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/search",$params,"GET",$header);          
        }
        return ["Message" => "Yes" , 'page' => $page,'q' => $q,'info' => $info ];
    }

    public function bookdetail(){
        $id = $_GET['id'];
        if ($id === "undefined") return ['infoArr' => ["出错了","请将此错误提交开发者","QQ:651525974",""],'infoArrInner' => ["ERROR"],'isbn' => "0"];
        $isbn = "";
        $infoArr = array();
        $infoArrInner = array();
        $url = "http://interlib.sdust.edu.cn/opac/m/book/" . $id;
        $header = Conf::libGetHeader();
        $info = Http::httpRequest($url,array(),"GET",$header);
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
                array_push($infoArrInner,str_replace(array("</p>",'<p class="holdingState">',"<p>"),"",$value2));
            }
        }
        return ['infoArr' => $infoArr,'infoArrInner' => $infoArrInner,'isbn' => $isbn];
    }

    public function signalBookdetail(){
        $id = $_GET['id'];
        $isbn = "";
        $infoArr = array();
        $infoArrInner = array();
        $url = "http://interlib.sdust.edu.cn/opac/m/book/" . $id;
        $header = Conf::libGetHeader();
        $info = Http::httpRequest($url,array(),"GET",$header);
        return ["Message" => "Yes" , 'info' => $info];
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
        $header = Conf::libGetHeader();
        $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/reader/doLogin",$params,"POST",$header);
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
            $replace = array('<p >','</p>','<p  >',"    ",'<p class="remind">');
            foreach ($allMatch[0] as $value2) {
                array_push($infoArrInner,str_replace($replace,"",$value2));
            }
            array_push($infoArr,$infoArrInner);
        }
        return ['libInfo' => $infoArr];
    }

    public function signalLibquery(){
        $user = $this->checkSession();
        $account = substr($_SESSION['account'],2);
        $params = array(
            "rdid" => $account,
            "rdPasswd" => md5($account),
            "returnUrl" => "/m/loan/renewList",
            "view" => "action"
        );
        $header = Conf::libGetHeader();
        $info = Http::httpRequest("http://interlib.sdust.edu.cn/opac/m/reader/doLogin",$params,"POST",$header);
        return ["Message" => "Yes" , 'info' => $info];
    }
    
}
