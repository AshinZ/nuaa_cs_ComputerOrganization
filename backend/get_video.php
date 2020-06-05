<?php
    $class_id=$_POST['class_id'];
	require_once 'database.php';
    $sql = "SELECT resourse_name,resourse_url FROM resourse_list where resourse_type='video'&&class_id='$class_id'";
    $result=mysqli_query($con,$sql);
	if(!$result) $ret=array('code'=>'201');
	else{
    $video=mysqli_fetch_all($result);
	$ret=array('code'=>'200','video'=>$video);
	}
	echo json_encode($ret);
    mysqli_close($con);//关闭数据库
?>