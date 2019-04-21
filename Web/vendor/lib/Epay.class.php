<?php

/***********************************
 *  Created by Epay Team.
 *  User: zhang.ding
 *  Version: 1.2
 *  Date: 16/6/19
 *  Time: 下午2:09
 *  Object: Epay Pay SDK
 ***********************************/
 



 class EpayClientAop
 {
	//E交易网关地址
	private static $gateway_url = "https://www.payonline.xin/create.request";

	//异步通知回调地址
	private static $notify_url;

	//签名类型
	private static $sign_type;

	//商户私钥地址
	private static $private_key;

	//应用id
	private static $appid;

	//签名保存数组
	private static $sign_data;

	//编码格式
	private static $charset = "UTF-8";
	
	//重试次数
	private static $MaxQueryRetry;
	
	//重试间隔
	private static $QueryDuration;

	//返回数据格式
	private static $type = 1;

	//商户自家订单号
 	private $order_no;

 	public $pay_url;

 	//是否手机设置
 	private $mobile = 0;

 	//商户该订单商品名称
 	private $subject;

 	//订单金额
 	private $order_amount;

 	//订单请求超时时间限制
 	private $time_expire = "5m";

	public function __construct($config){

		self::$appid = $config['app_id'];
		self::$sign_type = $config['sign_type'];
		self::$private_key = $config['merchant_private_key'];
		self::$charset = $config['charset'];
		self::$MaxQueryRetry = $config['MaxQueryRetry'];
		self::$QueryDuration = $config['QueryDuration'];
		self::$notify_url = $config['notify_url'];
		self::$sign_data = array(
			"app_id" => self::$appid,
			"sign_type" => self::$sign_type,
			"charset" => self::$charset
		);

		if(empty(self::$appid)||trim(self::$appid)==""){
			throw new Exception("appid should not be NULL!");
		}
		if(empty(self::$private_key)||trim(self::$private_key)==""){
			throw new Exception("private_key should not be NULL!");
		}
		if(empty(self::$charset)||trim(self::$charset)==""){
			throw new Exception("charset should not be NULL!");
		}
		if(empty(self::$QueryDuration)||trim(self::$QueryDuration)==""){
			throw new Exception("QueryDuration should not be NULL!");
		}
		if(empty(self::$gateway_url)||trim(self::$gateway_url)==""){
			throw new Exception("gateway_url should not be NULL!");
		}
		if(empty(self::$MaxQueryRetry)||trim(self::$MaxQueryRetry)==""){
			throw new Exception("MaxQueryRetry should not be NULL!");
		}
		if(empty(self::$sign_type)||trim(self::$sign_type)==""){
			throw new Exception("sign_type should not be NULL");
		}

	}

 	//创建下单
 	public function create($type = "wx") {

 		$param["app_id"] = self::$appid;
 		$param["charset"] = self::$charset;
 		$param["notify"] = self::$notify_url;
 		$param["type"] = $type;

 		$param["sign"] = $this->setSign();
 		$param["mobile"] = $this->mobile;
 		$param["order_no"] = $this->order_no;
 		$param["order_amount"] = $this->order_amount;
 		$param["time_expire"] = $this->time_expire;
 		$param["subject"] = $this->subject;


 		foreach($param as $key => $value)
 		{
 			if($value === null || $value === "" || $value === false)
 			{
 				throw new Exception("请求参数错误!".$key."=>".$value);
 			}
 		}

 		return $this->request_post(self::$gateway_url,$param);

 	}
 	//创建订单记录接口
 	public function order_log() {

 		$param["app_id"] = self::$appid;
 		$param["charset"] = self::$charset;
 		$param["order_no"] = $this->order_no;
 		$param["sign"] = $this->setSign();

 		foreach($param as $value)
 		{
 			if($value === null || $value === "" || $value === false)
 			{
 				throw new Exception("请求参数错误!");
 			}
 		}

 		return $this->request_post(self::$gateway_url,$param);
 	}

 	//微信H5支付特定方法
 	public function WxMobilePay() {

 		$param["notify"] = self::$notify_url;
 		$param["app_id"] = self::$appid;
 		$param["type"] = "wx";

 		$param["sign"] = $this->setSign();
 		$param["mobile"] = $this->mobile;
 		$param["order_no"] = $this->order_no;
 		$param["order_amount"] = $this->order_amount;
 		$param["time_expire"] = $this->time_expire;
 		$param["subject"] = $this->subject;

 		$url = "https://www.payonline.xin/create.request?";

 		foreach($param as $key => $value)
 		{
 			if($value === null || $value === "" || $value === false)
 			{
 				throw new Exception("请求参数错误!".$key."=>".$value);
 			}
 			else
 			{
 				$url .= (String)$key."=".(String)$value."&";
 			}
 		}


 		$url = substr($url,0,strlen($url)-1);

 		return $url;
 	}
 	//接收支付结果
 	public function acceptNotify($config) {

 		//接收参数范例: {"state":"SUCCESS","app":"RYD7pOnZrFeAXV5","subject":"www","order_no":"201808041027596404","order_amount":"2"}
 		$state = $_POST["state"];
 		$data["out_trade_no"] = $_POST["order_no"];
 		$data["order_amount"] = $_POST["order_amount"];

		$sign = md5("app_id=".$config["app_id"]."&order_amount=".$data["order_amount"]."&order_no=".$data["out_trade_no"]."&sign_type=md5&state=SUCCESS&key=".$config["merchant_private_key"]);

 		if($state == "SUCCESS" && $sign == $_POST["sign"]) {

 			return $data;
 		}
 		else {

 			throw new Exception("支付失败！");
 		}
 	}
 	//返回所有参数
 	public function getAllparam() {

 		$param["app_id"] = self::$appid;
 		$param["charset"] = self::$charset;
 		$param["notify"] = self::$notify_url;

 		$param["sign"] = $this->setSign();
 		$param["mobile"] = $this->mobile;
 		$param["order_no"] = $this->order_no;
 		$param["order_amount"] = $this->order_amount;
 		$param["time_expire"] = $this->time_expire;
 		$param["subject"] = $this->subject;

 		return $param;
 	}

 	//创建签名
 	protected function setSign() {

 		$sign = "";

 		self::$sign_data["order_no"] = $this->order_no;
 		self::$sign_data["order_amount"] = $this->order_amount;
 		
 		ksort(self::$sign_data);

 		foreach (self::$sign_data as $key => $value) {
 			
 			if($key != "sign") {
 				$sign .= $key."=".$value."&";
 			}
 		}

 		$sign .= "key=".self::$private_key;

 		return md5($sign);
 	}

 	//更改请求网关
 	public function setWayUrl($url) {
 		
 		self::$gateway_url = $url;
 	}

 	//更改请求实现
 	public function setTimeOut($time) {
 		
 		$this->time_expire = $time;
 	}

 	//设置下单订单号
 	public function setOutTradeNo($order_sn) {

 		$this->order_no = $order_sn;
 	}
 	//设置下单金额
 	public function setTradeAmount($order_amount) {

 		$this->order_amount = $order_amount;
 	}
 	//设置回调地址
 	public function setNotifyUrl($notify) {

 		self::$notify_url = $notify;
 	}
 	//设置请求支付
 	public function setCharset($charset) {

 		self::$charset = $charset;

 	}
 	//设置请求下单绑定名称
 	public function setSubject($subject) {

 		$this->subject = $subject;
 	}
 	//设置手机支付请求类型
 	public function setMobile($mobile) {

 		$this->mobile = $mobile;

 		if(!is_numeric($this->mobile))
 		{
 			throw new Exception("手机请求参数错误!");
 		}
 	}
 	// 调用库生成二维码
    public function imgCode($url=''){ 
         
        vendor('lib.phpqrcode.phpqrcode');
        $value = $url;                  //二维码内容  
          
        $errorCorrectionLevel = 'L';    //容错级别   
        $matrixPointSize = 25;           //生成图片大小    
          
        //生成二维码图片  
        $QR = '/temp/temp.png';

        QRcode::png($value,$QR, $errorCorrectionLevel, $matrixPointSize, 2);    

        return chunk_split(base64_encode(file_get_contents($QR)));     
    }
 	//POST发送工具函数
 	public  function request_post($url = '', $data = '') {

	    $curl = curl_init(); // 启动一个CURL会话

	    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址

	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查

	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在

	    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器

	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转

	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer

	    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求

	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包

	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环

	    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容

	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

	    $tmpInfo = curl_exec($curl); // 执行操作

	    if (curl_errno($curl)) {

	        echo 'Errno'.curl_error($curl);//捕抓异常

	    }

	    curl_close($curl); // 关闭CURL会话

	    return $tmpInfo; // 返回数据，json格式
    }

 }
