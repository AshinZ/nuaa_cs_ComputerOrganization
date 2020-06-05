<?php
		require_once 'database.php';  
		$result = mysqli_query($con,"select class_id,class_name,class_teacher from classes ");
		$rows=mysqli_num_rows($result);
		$row1=mysqli_fetch_all($result);
		if($rows)
		{
		$ret=array('code'=>'200','classes'=>$row1);
		echo json_encode($ret);
		}
		else
		{
			$ret=array('code'=>'201');
			echo json_encode($ret);
		}
	mysqli_close($con);
?>