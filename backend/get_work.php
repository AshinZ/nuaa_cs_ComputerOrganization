<?php
//学生查已经提交
		require_once 'database.php';
		$user_id=$_POST['user_id'];
		$token=$_POST['user_token'];
		$class_id=$_POST['class_id'];
		$sql="select user_id from student where user_token='$token'&&user_id='$user_id'";
		$result = mysqli_query($con,$sql);
		if(!$result)
		{
			$ret=array('code'=>'201');
		}
		else{
			$sql="select work_name,work_time from submit_list where class_id='$class_id'&&user_id='$user_id' order by work_time DESC";
			$result = mysqli_query($con,$sql);
			$work=mysqli_fetch_all($result);
			$ret=array('code'=>'200','work'=>$work);
		}
		echo json_encode($ret);
	mysqli_close($con);
?>