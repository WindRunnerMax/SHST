<?php
namespace app\mail;
class send
{
	public function sendMail($user,$value='')
    {
        # code...
        header("Content-type:text/html;charset=utf-8");
        require_once "PHPMailer/class.phpmailer.php";
        require_once "PHPMailer/class.smtp.php";
        try { 
                $mail = new \PHPMailer(true); 
                $mail->IsSMTP(); 
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码 
                $mail->SMTPAuth = true; //开启认证 
                $mail->SMTPSecure = "ssl";
                $mail->Port = 994; 
                $mail->Host = "smtp.163.com"; 
                $mail->Username = "streamerlightmail@163.com"; 
                $mail->Password = "DAIBO11123"; 
                $mail->From = "streamerlightmail@163.com"; 
                $mail->FromName = "Message"; 
                $mail->AddAddress($user); 
                $mail->Subject = "蚁合轰趴"; 
                $mail->Body = $value; 
                //$mail->AddAttachment("f:/test.png"); //可以添加附件 
                $mail->IsHTML(true); 
                $mail->Send(); 
                // echo '邮件已发送'; 
            } catch (phpmailerException $e) { 
                // echo "邮件发送失败：".$e->errorMessage(); 
        } 
    }
}
