<?php
namespace app\backstage\controller;
use think\Controller;
use think\Db;

class Shops extends Controller
{

    public function checkSession($value='')
    {
        # code...
        session_start();
        if(isset($_SESSION['user']) && $_SESSION['user']=="ADMIN") return $_SESSION['user'];
        else $this->error("未登录","/",3);
    }

    public function distance($la1=0 , $lo1 =0 , $la2 = 0 ,$lo2 = 0 )
    {
        # code...
        $radLat1=deg2rad($la1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($la2);
        $radLng1=deg2rad($lo1);
        $radLng2=deg2rad($lo2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;
        return $s;
    }

    public function shopCheck($value='')
    {
        # code...
        $this->checkSession();
        if(isset($_GET['page'])&&isset($_GET['limit'])){
            $pageStart = ($_GET['page']-1)*$_GET['limit'];
            $pageLimit = $_GET['limit'];
            $data = Db::table("shops_ower")->field("id,name,city,region,address")->where("status",0)->limit("$pageStart,$pageLimit")->select();
            $count = Db::table("shops_ower")->where("status",0)->count();
            return ['code'=>0,'count'=>$count,'data'=>$data];      
        }
        return ['code'=>0,'count'=>0,'data'=>''];
    }
    

    public function info(){
        $user = $this->checkSession();
        if(isset($_POST['id'])){
            $data = Db::table("shops_ower")->field("start_time,end_time,room,people,longitude,latitude,connect")->where("id",$_POST['id'])->find();
            $userinfo = Db::table("user_table")->field("account")->where("id",$_POST['id'])->find();
            $data["account"] = $userinfo['account'];
            return ["data" => $data];
        }
         ["data" => ""];
    }

    public function pass(){
        $user = $this->checkSession();
        if(isset($_POST['id'])){
            $info = Db::table("shops") -> where("id",$_POST['id']) ->find();
            $data = Db::table("shops_ower")->field("id,name,start_time,end_time,address,room,people,city,region,longitude,latitude,connect")->where("id",$_POST['id'])->find();
            if ($info) {
                # code...
                Db::table("shops") -> where("id",$_POST['id']) ->update($data);
            } else {
                # code...
                Db::table("shops") -> insert($data);
            }
            $record['tips'] = "审核通过";
            $record['status'] = 1;
             Db::table("shops_ower") -> where("id",$_POST['id']) ->update($record);
            return ["Message" => "Yes"];
        }
         ["Message" => "NO"];
    }

    public function rej(){
        $user = $this->checkSession();
        if(isset($_POST['id'])){
            $record['tips'] = "审核驳回，请检查是否符合要求";
            $record['status'] = -1;
             Db::table("shops_ower") -> where("id",$_POST['id']) ->update($record);
            return ["Message" => "Yes"];
        }
         ["Message" => "NO"];
    }

    public function upload()
    {
            if(!isset($_POST['id'])) return ['status' => 2, 'message' => "id"];
            if($_POST['id']==0) return ['status' => 2, 'message' => "0"];
            // 获取表单上传文件
            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'shops');
            if ($info) {
                $data = [
                    // 引号问题 可以服务器有问题
                    'path' => 'uploads/' . str_replace('\\', '/', $info->getSaveName()),
                    'create_time' => time(),
                    'size' => $info->getSize(),
                ];
                $imgPath = "https://www.liyanzuisha.cn/public/shops/" . $info->getSaveName();
                // $id = Db::name('works')->insertGetId($data);
                $record['id'] = $_POST['id'];
                $record['src'] = $imgPath;
                Db::table("img_info")->insert($record);
                return ['message' => $imgPath, 'status' => 1, 'id' => '1'];
            } else {

                // 上传失败获取错误信息

                return ['status' => 0, 'message' => $file->getError()];

            }

    }


}