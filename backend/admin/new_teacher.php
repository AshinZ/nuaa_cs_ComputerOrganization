<?php
//增加
		require_once '../database.php';
		$user_id=$_POST['user_id'];
		$user_name=$_POST['user_name'];
		$user_passwd=$_POST['user_passwd'];
		$sql="select user_id from teacher where user_id='$user_id'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result))
		{
			$ret=array('code'=>'201');
		}
		else{
			$sql="insert into teacher(user_id,user_name,user_passwd) values ('$user_id','$user_name','$user_passwd')";
			$result = mysqli_query($con,$sql);
			$ret=array('code'=>'200');
		}
		echo json_encode($ret);
	mysqli_close($con);
?>