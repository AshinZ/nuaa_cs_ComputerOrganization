<?php
	require_once '../database.php';
	$type=$_POST['type'];
    $id=$_POST['user_id'];
    $new_passwd=$_POST['user_passwd'];
	$user_name=$_POST['user_name'];
	$user_mail=$_POST['user_mail'];
	$user_class=$_POST['user_class'];
    
    if ($id && $new_passwd){//如果用户名和密码都不为空
					$sql1="update $type set user_passwd='$new_passwd',user_name='$user_name',user_mail='$user_mail',user_class='$user_class' 
					where user_id='$id'";
					$result=mysqli_query($con,$sql1);
					$ret=array('code'=>'200');
			 }
			 else
			 {
				$ret=array('code'=>'201');
			 }
			 echo json_encode($ret);
    mysqli_close($con);//关闭数据库
?>