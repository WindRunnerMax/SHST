<?php
header("Content-type:text/html;charset=utf-8");
include "PHPMailer/class.PHPMailer.php";
include "PHPMailer/class.SMTP.php";
try { 
        $mail = new PHPMailer(true); 
        $mail->IsSMTP(); 
        $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码 
        $mail->SMTPAuth = true; //开启认证 
        $mail->SMTPSecure = "ssl";
        $mail->Port = 994; 
        $mail->Host = "smtp.163.com"; 
        $mail->Username = "streamerlightmail@163.com"; 
        $mail->Password = "DAIBO11123"; 
        $mail->From = "StreamerLight"; 
        $mail->FromName = "Message"; 
        $mail->AddAddress("651525974@qq.com"); 
        $mail->AddAddress("318339270@qq.com"); 
        $mail->Subject = "邮件提示"; 
        $mail->Body = "<h1>邮件发送</h1>"; 
        //$mail->AddAttachment("f:/test.png"); //可以添加附件 
        $mail->IsHTML(true); 
        $mail->Send(); 
        echo '邮件已发送'; 
    } catch (phpmailerException $e) { 
        echo "邮件发送失败：".$e->errorMessage(); 
} 

// require_once("PHPMailer/email.class.php");
// $smtpServer="smtp.163.com";
// $smtpServerPort="25";
// $smtpUserMail="streamerlightmail@163.com";
// $mailTo="651525974@qq.com";
// $user="streamerlightmail@163.com";
// $mailPwd="DAIBO11123";
// $mailTitle="邮箱标题";
// $mailContent='<h1>测试邮件 001</h1>';
// // 邮件格式 （HTML/TXT）
// $mailType="HTML";
// // true表示是否身份验证
// $smtp=new \smtp($smtpServer,$smtpServerPort,true,$user,$mailPwd);
// // 是否显示调试信息
// $smtp->debug=true;
// // 返回 bool
// $state=$smtp->sendmail($mailTo,$smtpUserMail,$mailTitle,$mailContent,$mailType);
// var_dump($state);