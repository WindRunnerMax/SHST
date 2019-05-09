<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Overview extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }


    public function infoview(){
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(), 
                       'tipsFlag' => Conf::getNewTips() ]);
        return $this->fetch();
    }

    public function swview(){
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $this->checkSession(), 
                       'tipsFlag' => Conf::getNewTips() ]);
        return $this->fetch();
    }
}
