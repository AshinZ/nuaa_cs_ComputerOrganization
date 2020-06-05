<?php
		require_once 'database.php';
		$result = mysqli_query($con,"select work_name from work 
		order by work_number DESC");
		$rows=mysqli_num_rows($result);
		$row1=mysqli_fetch_all($result);
		if($rows)
		{
		$ret=array('code'=>'200','works'=>$row1);
		echo json_encode($ret);
		}
		else
		{
			$ret=array('code'=>'201');
		}
	
	mysqli_close($con);


?>