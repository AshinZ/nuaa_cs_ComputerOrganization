<?php
	require_once 'database.php';
	$content=$_POST['new_note'];
	$class_id=$_POST['class_id'];
	$teacher_id=$_POST['user_id'];
		$sql="update classes set class_note = $content where class_id=$class_id";
		$result=mysqli_query($con,$sql);
		if (!$result){
                    $ret=array('code'=>'201');
					echo json_encode($ret);//如果sql执行失败输出错误
		
           }else{
	                $ret=array('code'=>'200');
					echo json_encode($ret);
          }
	$sql="select class_name from classes where class_id='$class_id' ";
	$result=mysqli_query($con,$sql);
	$class_name=mysqli_fetch_array($result);
	$sql="select user_mail from student ";
	$result=mysqli_query($con,$sql);
	$name=mysqli_fetch_all($result);
	
	//send email
	require_once "Smtp.class.php";
    //******************** 配置信息 ********************************
    $smtpserver = "smtp.163.com";//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = "nxf161810331@163.com";//SMTP服务器的用户邮箱
  //  $smtpemailto = $_POST['toemail'];//发送给谁
    $smtpuser = "nxf161810331";//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
    $smtppass = "qwer123";//SMTP服务器的授权码
    $mailtitle = $class_name[0]."的新公告:";//邮件主题
    $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ***************************
    $smtp = new Smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false;//是否显示发送的调试信息
	for($i=0;$i<count($name);$i++)
	{
    $state = $smtp->sendmail('$name[$i]', $smtpusermail, $mailtitle, $content, $mailtype);
	}
    mysqli_close($con);//关闭数据库
?>