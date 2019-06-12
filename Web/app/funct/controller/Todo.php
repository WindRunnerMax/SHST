<?php
namespace app\funct\controller;
use think\Controller;
use think\Db;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Todo extends Controller
{

    private function checkSession($openid=''){
        session_start();
        if(isset($_SESSION['openid'])) return $_SESSION['openid'];
        else $this->error("未登录","/?status=E",3);
    }

    public function addEvent($value=''){
        if (isset($_POST['content']) && isset($_POST['date'])) {
            $r_Time=date("Y-m-d H:i:s",time());
            $record['openid'] = $this->checkSession();
            $record['event_content'] = $_POST['content'];
            $record['add_time'] = $r_Time;
            $record['todo_time'] = $_POST['date'];
            Db::table("todo_event") -> insert($record);
            return ["Message" => "Yes"];
        }else return ["Message" => "No"];
    }
    
    public function getEvent($value=''){
        $openid = $this->checkSession();
        return ["data" => Db::table("todo_event") 
        -> field("id,event_content,todo_time") 
        -> where("openid",$openid) 
        -> where("status",0)
        -> select()];
    }

    public function getFinEvent($value=''){
        $openid = $this->checkSession();
        return ["data" => Db::table("todo_event") 
        -> field("id,event_content,todo_time") 
        -> where("openid",$openid) 
        -> where("status",1)
        -> select()];
    }

    public function setStatus($value=''){
        $openid = $this->checkSession();
        if (isset($_POST['id'])) {
            $record['status'] = 1;
            Db::table("todo_event") -> where("openid",$openid) -> where("id",$_POST['id']) -> update($record);
            return ["Message" => "Yes"];
        }else return ["Message" => "No"];
        
    }

    public function setNoFinStatus($value=''){
        $openid = $this->checkSession();
        if (isset($_POST['id'])) {
            $record['status'] = 0;
            Db::table("todo_event") -> where("openid",$openid) -> where("id",$_POST['id']) -> update($record);
            return ["Message" => "Yes"];
        }else return ["Message" => "No"];
        
    }

    public function deleteUnit($value=''){
        $openid = $this->checkSession();
        if (isset($_POST['id'])) {
            Db::table("todo_event") -> where("openid",$openid) -> where("id",$_POST['id']) -> limit(1) -> delete();
            return ["Message" => "Yes"];
        }else return ["Message" => "No"];
        
    }
}
