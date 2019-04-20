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
            ->field('id,password,type')->find();
            if($password)
            {
               // echo $password;
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
         if($exists) return ["Message" => "EX"];

         if(isset($_POST['username']) && isset($_POST['password']))
        {
            $record['account'] = $_POST['username'];
            $record['password'] = $_POST['password'];
            $record['connect'] = $_POST['connect'];
            date_default_timezone_set('PRC');
            $r_Time=date("Y-m-d H:i:s",time());
            $record['reg_time'] = $r_Time;
            $id=Db::table("user_table") -> insertGetId($record) ;
            $info['id'] = $id;
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
            $userM = "";
            $shopM = "";
            // echo $userId." ".$shopId;
            $record['order_no'] = $order_no;
            $record['shop_name'] = $name;
            $record['nick_name']=$_POST['username'];
            $record['order_date']=$_POST['order_date'];
            $record['start_time']=$_POST['start_time'];
            $record['end_time']=$_POST['end_time'];
            $record['connect']=$_POST['userconnect'];
            $record['remark']=$_POST['userremark'];
            date_default_timezone_set('PRC');
            $r_Time=date("Y-m-d H:i:s",time());
            $record['order_time'] = $r_Time;
            $infoDetail = implode("<br>", $record);
            $record['user_id'] = $userId;
            $record['shop_id'] = $shopId;
            Db::table("order_info") -> insert($record);

            $userMail = Db::table("user_table") -> field("account,connect") -> where("id",$userId) -> find();
            if($userMail) {
                $text = "<h2>您的订单号为：$order_no 预定商家为: $name <h2><br><h3>稍后会有工作人员与您联系</h3><br>$infoDetail";
                $userM = $userMail['account'];
                $mail -> sendMail($userMail['account'],$text);
            }

            $shopMail = Db::table("user_table") -> field("account,connect") -> where("id",$shopId) -> find();
            if($shopMail) {
                $text = "<h2>用户预定<h2><br><h2>订单号：$order_no<h2><br>$infoDetail";
                $shopM = $shopMail['account'];
                $mail -> sendMail($shopMail['account'],$text);
            }

            $phone = $_POST['userconnect'];
            $phone2 = $shopMail['connect'];
            $text ="<h2>订单号为：$order_no 预定商家为: $name <h2><h3>用户：$userM 联系方式：$phone <br> 商户：$shopM 联系方式：$phone2 </h3><br>$infoDetail";
            $mail -> sendMail("805174670@qq.com",$text);
            return ["Message" => "Yes"];
        }
        return ["Message" => "No"];
    }

    public function orderList(){
        if(!isset($_POST['id'])) return ["Message" => "No"];
        $data = Db::table("order_info")->where("user_id",$_POST['id'])->select();
        return ["Message" => "Yes" , "info" => $data];
    }

}