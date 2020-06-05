<?php
//学生查成绩
	require_once 'database.php';
	$id=$_POST['user_id'];      
		$result = mysqli_query($con,"SELECT grade_name, grade_number  FROM grade
		WHERE user_id=$id");
		$rows=mysqli_num_rows($result);
		$row1=mysqli_fetch_all($result);
		if($rows)
		{
		$i=0;
		$n=count($row1);
		$grade=array();
		for ($i;$i<$n;$i=$i+1)
		{
			$grade[$row1[$i][0]]=$row1[$i][1];
		}
		$ret=array('code'=>'200','grade'=>$grade);
		echo json_encode($ret);
		}
		else
		{
			$ret=array('code'=>'201');
		}
	
	mysqli_close($con);
?>