<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use app\funct\model\UserTable;

class User extends Controller
{
    public function sessionTest($value='')
    {
        # code...
        session_start();
        $_SESSION['test'] = "test";
        return session_id();
    }

    public function login()
    {
        # code...
        $user = new UserTable;
        // $userList = $user -> where('status',0)
        // ->select();
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $password = $user -> where('account', $_POST['username'])
            ->field('id,check_pass,password,type')->find();
            if($password)
            {
               // echo $password;
                if($password['check_pass']==0) return  ["Message" => "No"];
                if($password->password==$_POST['password']){
                    session_start();
                    $_SESSION['user']=$_POST['username'];
                     return ["Message" => "Yes" , "PHPSESSID" => session_id(),"id" => $password->id ,"type" => $password->type ];
                }
            }
        }else{
           return  ["Message" => "No"];
        }
        return ["Message" => "No"];
    }

    public function info($value='')
    {
        # code...
        if (isset($_POST['id'])) {
            # code...
            return Db::table("user_info") -> where("id" , $_POST['id']) -> find();
        }
        return ["money" => 0 , "voucher" =>0 , "coin" => 0 , "integral" => 0];
    }

    public function reg($value='')
    {
        # code...
         $exists = Db::table("user_table") -> where("account" , $_POST['username']) ->find();
         if($exists) {
                if($exists['check_pass'] == 1)
                    return ["Message" => "EX"];
                if($exists['code_check'] != $_POST['code']){
                    return ["Message" => "CER"];
                } 
            }else{
                return ["Message" => "UNER"];
            }

         if(isset($_POST['username']) && isset($_POST['password']))
        {
            $record['account'] = $_POST['username'];
            $record['password'] = $_POST['password'];
            $record['connect'] = $_POST['connect'];
            $record['check_pass'] = 1;
            date_default_timezone_set('PRC');
            $r_Time=date("Y-m-d H:i:s",time());
            $record['reg_time'] = $r_Time;
            Db::table("user_table") -> where("id",$exists['id']) -> update($record) ;
            $info['id'] = $exists['id'];
            Db::table("user_info") -> insert($info);
            return ["Message" => "Yes"];
        }
        return ["Message" => "No"];
    }

    public function regMail()
    {
        $rand = rand('100000','999999');
        if(isset($_POST['username'])){
            $data = Db::table("user_table") -> where("account" , $_POST['username']) ->find();
            if($data){
                if ($data['check_pass'] == 1) {
                    # code...
                    return ["Message" => "EX"];
                }else{
                    $record['code_check'] = $rand;
                    $status = Db::table("user_table") ->where("account" , $_POST['username']) -> update($record);
                }
            }else{
                $record['account'] = $_POST['username'];
                $record['code_check'] = $rand;
                $status = Db::table("user_table") -> insertGetId($record);
            }
            $text = "您的验证码为：$rand";
            $mail = new \app\mail\send();
            $mail -> sendMail($_POST['username'],$text);
            return ["Message" => "Yes"];
        }
        return ["Message" => "No"];
    }

    public function ordered($value='')
    {
        # code...
        if(isset($_POST['userId']) && isset($_POST['shopId'])){
            $order_no = Date('YmdHis').mt_rand(1000,9999);
            $userId = $_POST['userId'];
            $shopId = $_POST["shopId"];
            $name = $_POST['name'];
            $mail = new \app\mail\send();

            $userMail = Db::table("user_table") -> field("account,connect") -> where("id",$userId) -> find();
            if($userMail) {
                $text = "<h2>您的订单号为：$order_no 预定商家为: $name <h2><br><h3>稍后会有工作人员与您联系</h3>";
                $mail -> sendMail($userMail['account'],$text);
            }

            $shopMail = Db::table("user_table") -> field("account") -> where("id",$shopId) -> find();
            if($shopMail) {
                $text = "<h2>用户预定<h2><br><h2>订单号：$order_no<h2>";
                $mail -> sendMail($shopMail['account'],$text);
            }
            
            $record['order_no'] = $order_no;
            $record['user_id'] = $userId;
            $record['shop_id'] = $shopId;
            Db::table("order_info") -> insert($record);

            $phone = $userMail['connect'];
            $text ="<h2>订单号为：$order_no 预定商家为: $name <h2><br><h3>用户：$userMail 联系方式：$phone <br> 商户：$shopId</h3>";
            $mail -> sendMail("805174670@qq.com",$text);
            return ["Message" => "Yes"];
        }
        return ["Message" => "No"];
    }
}