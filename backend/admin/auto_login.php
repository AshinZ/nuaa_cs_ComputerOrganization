<?php
	require_once '../database.php';
    $token=$_POST['user_token'];
	$id=$_POST['user_id'];

	$sql = "select user_token,user_name from admin where user_id = '$id'";
	$result = mysqli_query($con,$sql);
	$user=mysqli_fetch_array($result);
	if($user[0]==$token){	//可以登录
		$time = date("Y-m-d h:i:sa"); //更新token 记得要返回该token
		$str = $time;
		$token1 = md5($str);
		$update_token_sql="update admin set user_token = '$token1' where user_id='$id'";
		$result = mysqli_query($con,$update_token_sql);//执行sql
				//管理员登录进入界面  返回 当前系统的课程和教师信息
				$sql="select class_id,class_name,class_number,class_teacher from classes ";
				$result = mysqli_query($con,$sql);
				$return_info = mysqli_fetch_all($result);
				$sql="select user_id,user_name from teacher ";
				$result = mysqli_query($con,$sql);
				$return_info1 = mysqli_fetch_all($result);
				$ret=array('code'=>'200','user_name'=>$user[1],'user_token'=>$token1,'classes_info'=>$return_info,'teacher_info'=>$return_info1);
	}
	else{
		$ret=array('code'=>'201');
	}
	echo json_encode($ret); 
    mysqli_close($con);//关闭数据库
?>