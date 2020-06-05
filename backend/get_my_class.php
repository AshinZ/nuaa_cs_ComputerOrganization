<?php
		require_once 'database.php';    
		$name=$_POST['name'];
		$result = mysqli_query($con,"SELECT class_number,class_name,class_des	FROM class_list where teacher_name=$name");
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