<?php
	require_once 'database.php';
	$work_name=$_POST['class_work_name'];
	$class_id=$_POST['class_id'];
	$user_name=$_POST['user_name'];
	$user_id=$_POST['user_id'];
	$personal_work=$_POST['file_name'];
	$save_way=$_POST['save_way'];
	$file_type=$_POST['file_type'];
	$file_name=$personal_work.'.'.$file_type;

	$sql="select user_class from student where user_id='$user_id'";
	$result=mysqli_query($con,$sql);
	$my_class_id=mysqli_fetch_array($result);

	if($save_way=='1'){
		$url='../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name.'('.$file_type.')'.'/'.$my_class_id[0].'/'.$file_name;
	}
	else if($save_way=='0')
		$url='../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name.'('.$file_type.')'.'/'.$file_name;
	else   if($save_way=='2'){
		$url='../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(补交)".'/'.$file_type.'/'.$my_class_id[0].'/'.'(3h)'.$file_name;
		$url=iconv("UTF-8","GBK",$url);
		if(file_exists($url))
	            $url='../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(补交)".'/'.$file_type.'/'.$my_class_id[0].'/'.'(3h)'.$file_name;		
		else
		   $url='../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(补交)".'/'.$file_type.'/'.$my_class_id[0].'/'.'(6h)'.$file_name;	
	}
	$url=iconv("UTF-8","GBK",$url);
	if(file_exists($url)){//文件存在
		$time = date("Y-m-d h:i:sa"); 
		$str = $time;
		$name= md5($str);
		$ext=substr(strrchr($file_name,'.'),1);
		$path='../temp/'.$user_id.$name.'.'.$ext;
		$down_url='temp/'.$user_id.$name.'.'.$ext;
		if(copy($url,$path)){  //取得路径
			$ret=array('code'=>'200','url'=>$down_url);
			$log_state='success';
		}
		else 
		{
			$ret=array('code'=>'201');
			$log_state='error:cannot copy and move file';

		}
	}
	else{
		$ret=array('code'=>'202');
		$log_state='error:file not find';
	}
	$log_time=date("Y-m-d h:i:sa");
	$log_content=$user_id.':try get work '.$class_id.'->'.$work_name;
	$sql="insert into log (log_time,log_content,log_state) values ('$log_time','$log_content','$log_state')";
	$result=mysqli_query($con,$sql);

	echo json_encode($ret);
?>