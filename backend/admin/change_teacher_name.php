<?php
	require_once '../database.php';
	$type=$_POST['type'];
    $id=$_POST['user_id'];
    $new_name=$_POST['user_name'];
    
    if ($id && $new_name){//如果用户名和密码都不为空
					$sql1="update $type set user_name='$new_name' where user_id='$id'";
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