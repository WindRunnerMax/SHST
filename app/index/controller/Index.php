<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{
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
}
