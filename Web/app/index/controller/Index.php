<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{   
    private $header = array(
        'User-Agent:'.'Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer:'.'http://www.baidu.com',
        'accept-encoding:'.'gzip, deflate, br',
        'accept-language'.'zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control:'.'max-age=0',
        'X-FORWARDED-FOR:'.'51.36.15.76',
        'CLIENT-IP:'.'51.36.15.76'
    );

	public function getCtx()
	{
		$ctx="";
		if($_SERVER['SERVER_NAME']=="localhost")
			$ctx =  "/snackbox" ;
		return $ctx;
	}

    public function index()
    {
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
		$this->assign(['ctx' => $this->getCtx(),
            'status' => $status
    ]);
		return $this->fetch();
    }

	public function relogin()
    {
        session_start();
        if (isset($_SESSION['TOKEN'])) {
            # code...
            unset($_SESSION['TOKEN']);
            unset($_SESSION['user']);
            unset($_SESSION['account']);
        }
		$this->assign('ctx',$this->getCtx());
		return $this->fetch();
    }

    private $url = "http://jwgl.sdust.edu.cn/app.do";

}
