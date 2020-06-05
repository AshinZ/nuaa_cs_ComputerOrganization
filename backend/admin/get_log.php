<?php
		require_once '../database.php';
		$result = mysqli_query($con,"select log_time,log_content,log_state from log order by log_time DESC");
		$rows=mysqli_num_rows($result);
		$row1=mysqli_fetch_all($result);
		if($rows)
		{
		$ret=array('code'=>'200','log'=>$row1);
		}
		else
		{
			$ret=array('code'=>'201');
		}
	echo json_encode($ret);
	mysqli_close($con);


?>