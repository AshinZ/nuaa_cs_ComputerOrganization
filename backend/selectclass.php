<?php
	require_once 'database.php';
	$stu_id=$_POST['user_id'];
	$class_num=$_POST['class_id'];
	
	$sql="select user_id from choice_classes_list where user_id=$stu_id&&class_id=$class_num";
	$result=mysqli_query($con,$sql);
	if (mysqli_num_rows($result))
	{
		$ret=array('code'=>'201');  //先查询是否有这么一条数据  有说明已经选过
		echo json_encode($ret);
	}
	else{
		$sql1="select class_name ,class_teacher from classes where class_id=$class_num";
		$result1=mysqli_query($con,$sql1);
		$course=mysqli_fetch_array($result1);
		$class_name=$course[0];$teacher_name=$course[1];
		$sql2="insert into choice_classes_list (user_id,class_id,class_name,class_teacher) values ('$stu_id','$class_num','$class_name','$teacher_name')";
		$result2=mysqli_query($con,$sql2);
		
		
		$sql3="select class_number from classes where class_id=$class_num";
		$result3=mysqli_query($con,$sql3);
		$number=mysqli_fetch_array($result3);
		$num=$number[0]+1;
		$sql3="update classes set class_number=$num where class_id=$class_num";
		$result3=mysqli_query($con,$sql3);
		if($result2){
			$ret=array('code'=>'200');  //选课成功
			echo json_encode($ret);
		}
		else{
		$ret=array('code'=>'202');  //选课失败
		echo json_encode($ret);
		}
	}