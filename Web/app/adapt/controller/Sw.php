<?php
namespace app\adapt\controller;
use think\Controller;
use think\Db;
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

    public function table($zc=-1){
        $colorList = Conf::getColorList();
        $colorN = count($colorList);
        $user = $this->checkSession();
        $zc = $zc === -1 ? Conf::getCurWeek() : $zc;
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => Conf::getCurTerm(),
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        $info = json_decode($this->httpReq($params),true);
        if ($info) {
            $tableArr = array();
            foreach ($info as $value) {
                $arrInner = array();
                // $arrInner['day'] = (int)$value['kcsj'][0] - 1;
                // $arrInner['knot'] =  (int)((int)substr($value['kcsj'],1,2)/2);
                // $arrInner['teacher'] = $value['jsxm'];
                // $arrInner['classname'] = $value['kcmc'];
                // $arrInner['classroom'] = $value['jsmc'];

                $day = (int)$value['kcsj'][0] - 1;
                $knot = (int)((int)substr($value['kcsj'],1,2)/2);
                $md5Str = md5($value['kcmc']);
                $colorSignal = $colorList[abs((ord($md5Str[0]) - (ord($md5Str[3]))) % $colorN)] ;
                // array_push($arrInner, $day);
                // array_push($arrInner, $knot);
                // array_push($arrInner, $value['jsxm']);
                array_push($arrInner, explode("（", $value['kcmc'])[0]);
                array_push($arrInner, $value['jsmc']);
                array_push($arrInner, $colorSignal);
                if(array_key_exists($day,$tableArr) && array_key_exists($knot,$tableArr[$day])){
                     $tableArr[$day][$knot] = array_merge($tableArr[$day][$knot],$arrInner);
                }else{
                     $tableArr[$day][$knot] = $arrInner;
                }
            }
            $info = $tableArr;
        }
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'zc' => $zc,
                       "info" => $info, 
                       'tipsFlag' => Conf::getNewTips()
                   ]);
        return $this->fetch();
    }

    public function grade($sy=""){
        $user = $this->checkSession();
	   $params = array(
            "method" => "getCjcx",
            "xh" => $_SESSION['account'],
            "xnxqid" => $sy
        );
        $info = $this->httpReq($params);
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'xnxqh' => $sy,
		               'info' => json_decode($info,true), 
                       'tipsFlag' => Conf::getNewTips()
                   ]);
        return $this->fetch();
    }
  
}
