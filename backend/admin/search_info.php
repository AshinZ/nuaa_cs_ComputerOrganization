<?php
		require_once '../database.php';
		$table=$_POST['type'];
		$user_id=$_POST['user_id'];
		if($table=='student')
		$result = mysqli_query($con,"select user_id,user_name,user_mail,user_class,user_passwd from $table where user_id=$user_id");
		else
		$result = mysqli_query($con,"select user_id,user_name from $table where user_id=$user_id");	
		$row1=mysqli_fetch_array($result);
		if($result)
		{
		$ret=array('code'=>'200','user_id'=>$row1[0],'user_name'=>$row1[1],'user_mail'=>$row1[2],'user_class'=>$row1[3],'user_passwd'=>$row1[4]);
		}
		else
		{
			$ret=array('code'=>'201');
		}
	echo json_encode($ret);
	mysqli_close($con);


?>