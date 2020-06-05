<?php
	require_once 'database.php';
    $name=$_POST['new_class_name'];
	$teacher_name=$_POST['user_name'];
	$stu_id=$_POST['user_id'];
		$sql="select class_id  from classes order by class_id DESC ";
		$result=mysqli_query($con,$sql);
		if($result)
		{
			$class=mysqli_fetch_array($result);
			$number=$class[0];
			$number=(int)$number+1;
		}
		else{
			$number=10001;
		}
		$sql1="insert into classes (class_teacher,class_name,class_id) values ('$teacher_name','$name',$number)";
		$result1=mysqli_query($con,$sql1);//执行sql
		if (!$result1){
                    $ret=array('code'=>'201');
		echo json_encode($ret);//如果sql执行失败输出错误
		
           }else{
			$dir = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$number);
			if (!file_exists($dir)){
			mkdir ($dir,0777,true);
			chmod($dir,0777);
			$src = '/www/wwwroot/ashinz.cn/nuaa/backend/get_student_work.php';
			$dst = $dir.'/get_student_work.php';
			copy($src, $dst);
			}

	                $ret=array('code'=>'200');
			echo json_encode($ret);
          }
?>