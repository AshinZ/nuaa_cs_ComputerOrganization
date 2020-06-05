<?php 
	require_once 'database.php';
    $regid=$_POST['user_id'];
    $regpasswd=$_POST['user_passwd'];
    $regname=$_POST['user_name'];
	$mail=$_POST['user_mail'];
	$user_class=$_POST['user_class'];
    $sql="select user_id from student where user_id = '$regid'";
	$result=mysqli_query($con,$sql);
	if (mysqli_num_rows($result))
	{
		$ret=array('code'=>'201');
		echo json_encode($ret);
	}
	else{
    $q="insert into student(user_id,user_passwd,user_name,user_token,user_mail,user_class) values ('$regid','$regpasswd','$regname','$regid','$mail','$user_class')";//向数据库插入表单传来的值的sql
    $result=mysqli_query($con,$q);//执行sql
    if (!$result){
        die('Error: ' . mysqli_error($con));//如果sql执行失败输出错误
    }else{
	$ret=array('code'=>'200');
					echo json_encode($ret);
    }
	}
    mysqli_close($con);//关闭数据库
?>