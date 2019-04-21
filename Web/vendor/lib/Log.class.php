<?php

/***********************************
 *  Created by Epay Team.
 *  User: zhang.ding
 *  Version: 1.2
 *  Date: 16/6/19
 *  Time: 下午2:09
 *  Object: Epay Pay SDK
 ***********************************/
 



 class LogClientAop
 {
 	//针对所有的当前请求进行记录
 	public function request_log($parma) {

 		if(!file_put_contents("./log/epay.log","\n-------------Request--------------\n".Date("Y-m-d H:i:s")."\n",FILE_APPEND))
 		{
 			throw new Exception("记录错误！检查权限设置");	
 		}

 		foreach ($parma as $key => $value) {
 			
 		   file_put_contents("./log/epay.log",$key."=>".$value."\n",FILE_APPEND);
 		}

 		file_put_contents("./log/epay.log","\n------------Request---------------\n",FILE_APPEND);
 		
 	}

 	//针对所有回复进行记录
 	public function reply_log($data) {

 		if(!file_put_contents("./log/epay.log","\n-------------Response--------------\n".Date("Y-m-d H:i:s")."\n",FILE_APPEND))
 		{
 			throw new Exception("记录错误！检查权限设置");	
 		}

	    file_put_contents("./log/epay.log", json_encode($data),FILE_APPEND);

 		file_put_contents("./log/epay.log","\n------------Response---------------\n",FILE_APPEND);
 	}


 	//异步通知后结果记录
 	public function success_log($data) {

 		if(!file_put_contents("./log/success.log",$data))
 		{
 			throw new Exception("记录错误！检查权限设置");	
 		}
        
 	}

 }