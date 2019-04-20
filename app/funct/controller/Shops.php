<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;

class Shops extends Controller
{

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

    public function near($value='')
    {
        # code...
        $limit = $_POST['limit'];
        $area = json_decode($_POST['area']); //地区
        $location = json_decode($_POST['location']);//经纬
        if($area[0]=="0") return ["Message" => "NoLocattion"];
        try {
            $data = Db::table("shops") -> where("city",$area[1]) -> where("region",$area[2]) -> limit($limit) -> select();
            return ["Message" => "Yes" , "info" => $data ];
        } catch (Exception $e) {
            return ["Message" => "No"];
        }
        
    }

    public function load($value='')
    {
        # code...
        $limit = $_POST['limit']*5;
        $limitEnd = 5;         /*********************/
        $limit2 = "$limit,$limitEnd";
        $area = json_decode($_POST['area']); //地区
        $location = json_decode($_POST['location']);//经纬
        if($area[0]=="0") return ["Message" => "NoLocattion"];
        try {
            $data = Db::table("shops") -> where("city",$area[1]) -> where("region",$area[2]) -> limit($limit2) -> select();
            if(count($data)==0) return ["Message" => "DHav"];
            return ["Message" => "Yes" , "info" => $data ];
        } catch (Exception $e) {
            return ["Message" => "No"];
        }
    }

    public function search($value='')
    {
        # code...
        $limit = $_POST['limit'];
        $data = array();
        $name = $_POST["name"];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $nameSearch = "%$name%";
        $date = $_POST['date'];
        $area = json_decode($_POST['area']); //地区
        $location = json_decode($_POST['location']);//经纬
        try {
            $data = Db::table("shops") -> where("city",$area[1]) -> where("region",$area[2]) -> where('name','like',$nameSearch) -> where('start_time','<=',$startTime) -> where('end_time' , '>=' ,$endTime) -> limit($limit) -> select();
            // $count = Db::table("shops") -> where('name','like',$nameSearch) -> where('start_time','<=',$startTime) -> where('end_time' , '>=' ,$endTime) -> count();
            // foreach ($nameData as $value) {
            //     if($value['start_time'] && $value['end_time']){
            //         if ($value['start_time'] <= $startTime && $value['end_time'] >= $endTime) {
            //             # code...
            //             array_push($data,$value);
            //         }
            //     }
            // }
        } catch (Exception $e) {
            return ["Message" => "No"];
        }
        return ["Message" => "Yes" ,"info" => $data ];

    }

    public function searchLoad($value='')
    {
        # code...
        $limit = $_POST['limit']*5;
        $limitEnd = 5;
        $limit2 = "$limit,$limitEnd";
        $data = array();
        $name = $_POST["name"];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $nameSearch = "%$name%";
        $date = $_POST['date'];
        $area = json_decode($_POST['area']); //地区
        $location = json_decode($_POST['location']);//经纬
        try {
            $data = Db::table("shops") -> where("city",$area[1]) -> where("region",$area[2]) -> where('name','like',$nameSearch) -> where('start_time','<=',$startTime) -> where('end_time' , '>=' ,$endTime)->limit($limit2) -> select();
            if(count($data)==0) return ["Message" => "DHav"];
        } catch (Exception $e) {
            return ["Message" => "No"];
        }
        return ["Message" => "Yes" ,"info" => $data ];

    }

    public function shopsInfo($value='')
    {
        # code...
        if(isset($_POST['id'])){
            $status = Db::table("shops_ower")->field("name,start_time,end_time,address,room,people,connect,tips") -> where("id" , $_POST['id']) -> find();
            if($status) return ["Message" => "Yes","data" => $status];
        }
        return ["Message" => "ERR"];
    }

    public function subShopsInfo($value='')
    {
        # code...
        if(!isset($_POST['id'])) return ["Message" => "No"];
        try{
            $area = json_decode($_POST['area']); //地区
            $location = json_decode($_POST['location']);//经纬
            $record["start_time"]=$_POST['start_time'];
            $record["end_time"]=$_POST['end_time'];
            $record["name"]=$_POST['name'];
            $record["room"]=$_POST['room'];
            $record["people"]=$_POST['people'];
            $record["address"]=$_POST['address'];
            $record["connect"]=$_POST['connect'];
            $record["city"]=$area[1];
            $record["region"]=$area[2];
            $record["longitude"] = $location[0];
            $record["latitude"] = $location[1];
            $record["tips"]="等待审核";
            $record["status"]=0;     
            Db::table("shops_ower") -> where("id",$_POST['id']) -> update($record); 
            return ["Message" => "Yes"];
        }catch(Exception $e){
            echo $e;
            return ["Message" => "No"];
        }
        return ["Message" => "No"];
    }

    public function detail($value='')
    {
        # code...
        $imgData="";
        if(isset($_POST["id"])){
            $data = Db::table("shops")->where("id",$_POST["id"])->find();
            $img = Db::table("img_info")->field('src')->where("id",$_POST["id"])->select();
            if($img) $imgData = $img;
            if($data) return ["Message" => "Yes" , "data" => $data , "img" => $imgData];
        }
        return ["Message"=>"No"];
    }


    public function orderList(){
       if(!isset($_POST['id'])) return ["Message" => "No"];
        $data = Db::table("order_info")->where("shop_id",$_POST['id'])->select();
        return ["Message" => "Yes" , "info" => $data];
    }
}
