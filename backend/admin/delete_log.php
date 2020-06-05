<?php
		require_once '../database.php';
		$time = date("Y-m-d h:i:sa"); //获取时间 然后我们删除三天前的记录
		$timestamp = strtotime($time);//转换成时间戳 
		$timestamp=$timestamp-24*3*3600;
		$time=date("Y-m-d h:i:sa",$timestamp);
		$result = mysqli_query($con,"delete from log where log_time<'$time'");
		if($result)
		{
		$ret=array('code'=>'200');
		}
		else
		{
			$ret=array('code'=>'201');
		}
	echo json_encode($ret);
	mysqli_close($con);


?>