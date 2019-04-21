<?php
	/******************/
	/*	核心配置文件	*/
	/*	版本：V2.0	*/
	/*  By:新艺支付	*/
	/******************/
	
	header('Content-Type:text/html;charset=utf8');
	date_default_timezone_set('Asia/Shanghai');

	$userid='10000';//商户ID

	$userkey='z54i8u9yuytluoxck6t44nxs6jykkxuoh46lrhm2';//商户KEY

	$apiurl='https://www.payxinyi.com/pay/api.php';//网关地址
	
	$checkurl='https://www.payxinyi.com/pay/order.php';//查单地址

	$refundurl='https://www.payxinyi.com/pay/refund.php';//退款地址

	$notify='http://'.$_SERVER['HTTP_HOST'].'/demo/php/notify.php';//异步通知地址

	$return='http://'.$_SERVER['HTTP_HOST'].'/demo/php/return.php';//同步跳转地址
	
	/******************/


?>
