<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function checkSession($value='')
    {
        # code...
        session_start();
        if(isset($_SESSION['user']) && $_SESSION['user']=="ADMIN") return $_SESSION['user'];
        else $this->error("未登录","/",3);
    }
    public function getCtx()
    {
        $ctx="";
        if($_SERVER['SERVER_NAME']=="localhost")
            $ctx =  "/swarm" ;
        return $ctx;
    }

    public function index()
    {
        session_start();
        if(isset($_SESSION["user"])) unset($_SESSION["user"]);
        $this->assign('ctx',$this->getCtx());
        return $this->fetch();
    }

    public function busininfo()
    {
        $ctx = $this->getCtx();
        $this->assign(['ctx' => $ctx
            ,"user" => $this->checkSession(),
            "url" => "$ctx/index.php/backstage/shops/shopCheck"
        ]);
        return $this->fetch();
    }

    public function uploadImg()
    {
        $ctx = $this->getCtx();
        $this->assign(['ctx' => $ctx
            ,"user" => $this->checkSession()
        ]);
        return $this->fetch();
    }

    public function userShift()
    {
    	$status = -1;
    	$data = "";
    	$user = "";
    	$shops_ower = "未成为商户";
    	if (isset($_POST['account'])) {
    		# code...
    		$user = $_POST['account'];
    		$data = Db::table("user_table")->where("account",$_POST['account'])->find();
    		if ($data) {
    			# code...
    			$status = 1;
    			if($data['type']===1) $shops_ower="该用户已为商户";
    		} else {
    			# code...
    			$status = 0;
    		}
    		
    	}
        $ctx = $this->getCtx();
        $this->assign(['ctx' => $ctx
            ,"user" => $this->checkSession(),
            "url" => "$ctx/index.php/backstage/shops/shopCheck",
            "data" => $data,
            "status" => $status,
            "user_shop" => $user,
            "shops_ower" => $shops_ower
        ]);
        return $this->fetch();
    }

}
