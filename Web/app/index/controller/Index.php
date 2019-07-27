<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Conf;
use app\auxiliary\Http;

class Index extends Controller
{  


    public function index(){
        // if (!isset($_GET['p'])) {
        //     # code...
        //     return "维护调试图书馆功能，暂时关闭，明天应该能维护好";
        // }
        $status = "";
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
        }
        session_start();
        if (isset($_SESSION['TOKEN'])) {
            # code...
            unset($_SESSION['TOKEN']);
            unset($_SESSION['user']);
            unset($_SESSION['account']);
        }
		$this->assign(['ctx' => Conf::getCtx(),
            'status' => $status
    ]);
		return $this->fetch();
    }

	public function relogin(){
        session_start();
        if (isset($_SESSION['TOKEN'])) {
            # code...
            unset($_SESSION['TOKEN']);
            unset($_SESSION['user']);
            unset($_SESSION['account']);
        }
		$this->assign('ctx',Conf::getCtx());
		return $this->fetch();
    }

    public function info(){
        return $this->fetch();
    }

    // public function test(){
    //     print_r(Http::httpRequest("http://jwgl.sdust.edu.cn/app.do?method=authUser&xh=201601160202&pwd=Czy123...",[],"GET",Conf::getHeader(),true,true)); 
    // }

    public function testHTTP2(){
        foreach ($_SERVER as $key => $value) {
            echo $key."=>".$value."<br>";
        }
    }

}
