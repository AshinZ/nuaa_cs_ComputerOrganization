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
		echo json_encode($ret);
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
			echo json_encode($ret);
		}
		else{
		$ret=array('code'=>'202');  //删除失败
		echo json_encode($ret);
		}
	}