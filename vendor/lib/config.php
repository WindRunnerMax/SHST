<?php
$config = array (
		//签名方式,目前只提供MD5签名
		'sign_type' => "md5",

		//商户APPID 具体参数请在我的App详情内应用参数查阅
		'app_id' => "D9snJltGugMWOHm",

		//商户私钥 具体参数请在我的App详情内应用参数查阅
		'merchant_private_key' => "VcETg9iyUFIQ3qX",

		//编码格式
		'charset' => "UTF-8",
		
		//异步通知地址 自行设置，请保持和应用详情配置里面的响应地址一致
		'notify_url' => "https://www.edu-bo.top/index.php/funct/pay/accept",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);