<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\auxiliary\Http;
use app\auxiliary\Conf;

class Share extends Controller
{

    private function checkSession($value=''){
        # code...
        session_start();
        if(isset($_SESSION['TOKEN'])) return $_SESSION['user'];
        else $this->error("未登录",Conf::getCtx()."/?status=E",3);
    }

    public function httpReq($params){
        $header = Conf::getHeader();
        array_push($header,"token:".$_SESSION['TOKEN']);
        $info = Http::httpRequest(Conf::getUrl(),$params,"GET",$header);
        return $info;
    }

    private function tableDealWith($data){
        $tableArr = array();
        $colorList = Conf::getColorList();
        $colorN = count($colorList);
        if ($data) {
            foreach ($data as $value) {
                $arrInner = array();
                $day = (int)$value['kcsj'][0] - 1;
                $knot = (int)((int)substr($value['kcsj'],1,2)/2);
                // $colorSignal = $colorList[(ord(md5($value['kcmc'])[0]) % $colorN)] ;
                $colorSignal = "#9CB6E9";
                array_push($arrInner, $day);
                array_push($arrInner, $knot);
                array_push($arrInner, $value['jsxm']);
                array_push($arrInner, explode("（", $value['kcmc'])[0]);
                array_push($arrInner, $value['jsmc']);
                array_push($arrInner, $colorSignal);
                $tableArr[$day][$knot] = $arrInner;
            }
        }
        return $tableArr;
    }

    private function getTimeTable(){
        $zc = Conf::getCurWeek();
        $params=array(
        "method" => "getKbcxAzc",
        "xnxqid" => Conf::getCurTerm(),
        "zc" => $zc ,
        "xh" => $_SESSION['account']
        );
        $info = json_decode($this->httpReq($params),true);
        return [$zc,$info];
    }

    public function getCurrentTime(){
        $params = array(
        "method" => "getCurrentTime",
        "currDate" => date("Y-m-d",time())
        );
        return $this->httpReq($params);
    }

    public function tableshare($value=''){
    	$user = $this->checkSession();
    	$status = 1;
    	$msg = "";
    	$exist = Db::table("timetable_share") -> where("account",$_SESSION['account']) -> find();
    	if (!$exist) {
    		$insertRecord['account'] = $_SESSION['account'];
    		$insertRecord['name'] = $user;
    		Db::table("timetable_share")  -> insert($insertRecord);
    	}else{
    		$status = $exist['pair_status'];
    		if ($exist['pair_status'] === 2) {
    			$this->assign('pair_user' , [$exist['pair_account'],$exist['pair_name']]);
    		}else if ($exist['pair_status'] === 3) {
    			$msg = "您已被拒绝";
    			$refuseUpdateRecord['pair_status'] = 1;
				$refuseUpdateRecord['pair_account'] = "";
				$refuseUpdateRecord['pair_name'] = "";
				Db::table("timetable_share") -> where("account",$_SESSION['account']) -> update($refuseUpdateRecord);
    		}else if ($exist['pair_status'] === 0) {
                $tableInfo = $this -> getTimeTable();
                $updateRecord['week'] = $tableInfo[0];
                $updateRecord['timetable'] = json_encode($tableInfo[1]);
                Db::table("timetable_share") -> where("id",$exist['id']) -> update($updateRecord);
				$timeTable1 = Db::table("timetable_share") -> where("id",$exist['pair_point']) -> find();
				$this->assign([
								'timetable1' => $this -> tableDealWith(json_decode($timeTable1['timetable'],true)),
		                       'timetable2' => $this -> tableDealWith($tableInfo[1]),
                               'pair' => $exist['pair_name'],
                               'id' => $timeTable1['id'],
                               'pair_week' => $timeTable1['week'],
                               'my_week' => $exist['week']
                        ]);
            }

    	}
        $data = Db::table("timetable_share") -> where("pair_account",$_SESSION['account']) -> select();
        $this->assign('data' ,$data);
        $this->assign(['ctx' => Conf::getCtx(),
                       'user' => $user,
                       'msg' => (isset($_GET['msg']) ? $_GET['msg'] : $msg),
                       'status' => $status
                        ]);
        return $this->fetch();
    }

    public function startReq($value=''){
    	$user = $this->checkSession();
    	$message = "数据有误";
    	if (isset($_POST['account']) && isset($_POST['user'])) {
    		$exist = Db::table('user') 
    		-> where('username',$_POST['account']) 
    		-> where('name',$_POST['user'])
    		-> find();
    		if ($exist) {
    			$updateRecord['pair_status'] = 2;
    			/**
    			 * 1.默认状态
    			 * 2.发起请求
    			 * 3.拒绝
    			 * 0.成功
    			 */
    			$updateRecord['pair_account'] = $exist['username'];
    			$updateRecord['pair_name'] = $exist['name'];
    			Db::table("timetable_share") -> where("account",$_SESSION['account']) -> update($updateRecord);
    			$message = "已发起请求";
    		}else{
    			$message = "用户不存在";
    		}
    	}
    	return redirect(Conf::getCtx().'/index.php/index/share/tableshare?msg='.$message);
    }

    public function cancelReq($value=''){
    	$user = $this->checkSession();
    	$message = "撤销成功";
		$updateRecord['pair_status'] = 1;
		/**
		 * 1.默认状态
		 * 2.发起请求
		 * 3.拒绝
		 * 0.成功
		 */
		$updateRecord['pair_account'] = "";
		$updateRecord['pair_name'] = "";
		Db::table("timetable_share") -> where("account",$_SESSION['account']) -> update($updateRecord);
    	return redirect(Conf::getCtx().'/index.php/index/share/tableshare?msg='.$message);
    }

    public function agreereq($value=''){
    	$user = $this->checkSession();
    	$message = '数据有误';
    	if (isset($_GET['id'])) {
    		$data = Db::table("timetable_share") -> where('id',$_GET['id']) -> find();
            if ($data['pair_account'] === $_SESSION['account']) {
                $message = "成功";
                $updateRecord['pair_status'] = 0;
                $updateRecord['pair_account'] = $data['account'];
                $updateRecord['pair_name'] = $data['name'];
                $updateRecord['pair_point'] = $_GET['id'];
                Db::table("timetable_share") -> where("account",$_SESSION['account']) -> update($updateRecord);
                $data = Db::table("timetable_share") -> where("account",$_SESSION['account']) -> find();
                $updateRecord['pair_status'] = 0;
                $updateRecord['pair_account'] = $_SESSION['account'];
                $updateRecord['pair_name'] = $user;
                $updateRecord['pair_point'] = $data['id'];
                Db::table("timetable_share") -> where("id",$_GET['id']) -> update($updateRecord);
            }
    	}
    	return redirect(Conf::getCtx().'/index.php/index/share/tableshare?msg='.$message);
    }

    public function refusereq($value=''){
    	$user = $this->checkSession();
    	$message = '数据有误';
    	if (isset($_GET['id'])) {
            $data = Db::table("timetable_share") -> where('id',$_GET['id']) -> find();
            if ($data['pair_account'] === $_SESSION['account']) {
                $message = "成功";
    			$updateRecord['pair_status'] = 3;
    			$updateRecord['pair_account'] = "";
    			$updateRecord['pair_name'] = "";
    			Db::table("timetable_share") -> where("id",$_GET['id']) -> update($updateRecord);
            }
    	}
    	return redirect(Conf::getCtx().'/index.php/index/share/tableshare?msg='.$message);
    }

    public function lifting($value=''){
        $user = $this->checkSession();
        $message = '数据有误';
        if (isset($_GET['id'])) {
            $data = Db::table("timetable_share") -> where('id',$_GET['id']) -> find();
            echo $data['pair_account'];
            if ($data['pair_account'] === $_SESSION['account']) {
                $message = "成功";
                $updateRecord['pair_status'] = 1;
                $updateRecord['pair_account'] = "";
                $updateRecord['pair_name'] = "";
                Db::table("timetable_share") -> where("id",$_GET['id']) -> update($updateRecord);
                Db::table("timetable_share") -> where("account",$_SESSION['account']) -> update($updateRecord);
            }
        }
        return redirect(Conf::getCtx().'/index.php/index/share/tableshare?msg='.$message);
    }
    
}
