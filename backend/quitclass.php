<?php
	header("Content-Type: text/json; charset=utf8");
	require_once 'database.php';
	$stu_id=$_POST['user_id'];
	$class_num=$_POST['class_id'];
	
	$sql="select user_id from choice_classes_list  where user_id=$stu_id&&class_id=$class_num";
	$result=mysqli_query($con,$sql);
	if (!mysqli_num_rows($result))
	{
		$ret=array('code'=>'201');  //先查询是否有这么一条数据
		$log_state='error:not chosen this class';
	}
	else{
		$sql1="delete from choice_classes_list where user_id=$stu_id&&class_id=$class_num";
		$result1=mysqli_query($con,$sql1);
		
		
		$sql2="select class_number from classes where class_id=$class_num";
		$result2=mysqli_query($con,$sql2);
		$number=mysqli_fetch_array($result2);
		$num=$number[0]-1;
		$sql3="update classes set class_number=$num where class_id=$class_num";
		$result3=mysqli_query($con,$sql3);
		if($result1){
			$ret=array('code'=>'200');  //删除成功
			$log_state='success';
			}
		else{
		$ret=array('code'=>'202');  //删除失败
		$log_state='error:delete failed(mysql)';
		}
	}
	$log_time=date("Y-m-d h:i:sa");
	$log_content=$stu_id.':try quit class'.$class_num;
	$sql="insert into log (log_time,log_content,log_state) values ('$log_time','$log_content','$log_state')";
	$result=mysqli_query($con,$sql);

	echo json_encode($ret);
?>