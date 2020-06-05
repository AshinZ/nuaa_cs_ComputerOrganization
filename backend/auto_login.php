<?php
	require_once 'database.php';
    	$token=$_POST['user_token'];
	$id=$_POST['user_id'];
	$place=$_POST['user_place'];
	$type=$_POST['user_type'];
	$class_id=$_POST['class_id'];	
	
	$sql = "select user_token,user_name from $type where user_id = '$id'";
	$result = mysqli_query($con,$sql);
	$user=mysqli_fetch_array($result);
	if($user[0]==$token){	//可以登录
		$time = date("Y-m-d h:i:sa"); //更新token 记得要返回该token
		$str = $time;
		$token1 = md5($str);
		$update_token_sql="update $type set user_token = '$token1' where user_id='$id'";
		$result = mysqli_query($con,$update_token_sql);//执行sql
		//接下来根据我们接收到的用户所处的网页进行返回值的修改
		switch($place)
		{
			case 'teacher_login': //教师登录进入界面  返回  当前发布课程 每个课程的选择人数 和作业提交数
				$sql="select class_id,class_name,class_number,class_work_submit_number,class_work_name,last_class_work_name from classes where class_teacher = '$user[1]'";
				$result = mysqli_query($con,$sql);
				$return_info = mysqli_fetch_all($result);
				$ret=array('code'=>'200','user_name'=>$user[1],'user_token'=>$token1,'myClasses'=>$return_info);
				break;
			case 'student_login': //学生登陆进入界面  返回  当前已选课程 
				$sql = "select class_id,class_name,class_teacher from choice_classes_list where user_id = '$id'";
				$result = mysqli_query($con,$sql);
				$return_info = mysqli_fetch_all($result);
				$ret=array('code'=>'200','user_name'=>$user[1],'user_token'=>$token1,'myClasses'=>$return_info);
				break;
			case 'student_class_login': //学生选择课程以后的登陆界面 返回 当前已选课程 当前进入课程的作业 通知情况
				$sql = "select class_id,class_name,class_teacher from choice_classes_list where user_id = '$id'";
				$result = mysqli_query($con,$sql);
				$return_info0 = mysqli_fetch_all($result);
				$sql="select class_note,class_work_name,class_work_type,class_work_size,class_work_deadline,class_work_name_form,
				class_work_file_type from classes where class_id='$class_id'";
				$result = mysqli_query($con,$sql);
				$return_info1 = mysqli_fetch_array($result);
				$ret=array('code'=>'200','user_name'=>$user[1],'user_token'=>$token1,'myClasses'=>$return_info0,
				'class_note'=>$return_info1[0],'class_work_name'=>$return_info1[1],'class_work_type'=>$return_info1[2],
				'class_work_size'=>$return_info1[3],'class_work_deadline'=>$return_info1[4],'class_work_name_form'=>$return_info1[5],
				'class_work_file_type'=>$return_info1[6]);
				break;

		
		}
		$log_state='success';
	}
	else{
		$ret=array('code'=>'201');
		$log_state='error:passwd is wrong';
	}
	$log_time=date("Y-m-d h:i:sa");
	$log_content=$id.':try auto_login';
	$sql="insert into log (log_time,log_content,log_state) values ('$log_time','$log_content','$log_state')";
	$result=mysqli_query($con,$sql);
	echo json_encode($ret); 
    mysqli_close($con);//关闭数据库
?>