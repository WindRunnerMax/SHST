<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Ext extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }

    public function urlshare(){
        $data = Db::table("url_share") -> field("name,url") -> select();
        return ['url' => $data];
    }

    public function announce(){
        $data = Db::table("announce_info") -> field("announce") -> where("is_show",1) -> select();
        return ['info' => $data];
    }

    public function rewardList(){
        $data = Db::table("reward_info") -> field("name,amount,reward_time") -> select();
        return ['info' => $data];
    }
}
