<?php
namespace app\backstage\controller;
use think\Controller;
use think\Db;
use think\Config;

class User extends Controller
{
    public function login($value='')
    {
    	# code...
    	Config::set(['default_return_type' => 'html']);
    	// $data = Db::table("user_table") -> select();
    	// print_r($data);
    	if(isset($_POST['username']) && isset($_POST['password'])){
    		if($_POST['username']=="ADMIN"){
    			$data = Db::table("user_table")->field("password")->where("account" , $_POST['username'])->find();
    			if(!$data) return "登录失败";
    			if($data['password'] === $_POST['password']){
    				session_start();
    				$_SESSION['user'] = "ADMIN";
					return redirect('/index.php/busininfo');
					}

    		}
    	}
    	return $this->error("登陆失败","/",3);
    }

    public function turn(){
        if (isset($_POST['id'])) {
            # code...
            $record['type'] = 1;
            $status = Db::table("user_table") -> where("id",$_POST['id']) ->update($record);
            $ower['id'] = $_POST['id'];
            $ower['start_time'] = "08:00:00";
            $ower['end_time'] = "21:00:00";
            $ower['tips'] = "请尽快完善信息";
            $ower['status'] = -2;
            Db::table("shops_ower") -> insert($ower);
            if ($status)  return ["Message" => "Yes"];
        }
         return ["Message" => "No"];
    }

    public function pointerShop($value='')
    {
        # code...
        if(isset($_POST['account'])){
            $account = $_POST['account'];
            //$account = '651525974@qq.com';
            $id = Db::table('user_table')->field("id,type")->where('account',$_POST['account'])->find();
            if($id){
                if($id['type'] !== 1) return ["Message" => "Normal"];
                $data = Db::table('img_info')->where('id',$id['id'])->select();
                return ["Message" => "Yes" , "id" => $id['id'] , "data" => $data];
            }
            
        }
        return ["Message" => "No"];
    }


    public function test($value='')
    {
        # code...
        return Db::table('img_info')->alias('a')->join('user_table b','a.id=b.id')->field("a.id,a.src,b.type")->where('b.account','65152574@qq.com')->select();
    }

}