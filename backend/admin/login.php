<?php
	require_once '../database.php';
	$action=$_POST['action'];
    $logid=$_POST['user_id'];
    $logpasswd=$_POST['user_passwd'];
    if ($logid && $logpasswd){//如果用户名和密码都不为空
             $sql = "select user_id,user_passwd,user_token,user_name from admin where user_id = '$logid' ";//检测数据表是否有对应的userid的记录
             $result = mysqli_query($con,$sql);//执行sql
		/*	 if(!$result)
				 printf("Error: %s\n", mysqli_error($con));*/
             $rows=mysqli_num_rows($result);//返回一个数值
             if($rows){//如果有记录 判断密码
				$user=mysqli_fetch_array($result);
				if($user[1]==$logpasswd) //密码正确
				{
					$time = date("Y-m-d h:i:sa"); //获取时间 md5加密 然后再反转 作为token
					$str = $time;
					$token = md5($str);
					$update_token_sql="update admin set user_token = '$token' where user_id='$logid'";
					$result = mysqli_query($con,$update_token_sql);//执行sql
					$ret = array('code'=>'200','token'=>$token);
				}
				else  //密码不正确
				{
					$ret=array('code'=>'201');
				}
			 }//没有找到记录 说明不存在用户
			else
			{
				$ret=array('code'=>'202');  //不存在用户
			}	
			echo json_encode($ret);
	}
    mysqli_close($con);//关闭数据库
?>