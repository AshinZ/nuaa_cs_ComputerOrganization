<?php
	require_once 'database.php';
	$user_name=$_POST['user_name'];
	$user_id=$_POST['user_id'];
	$work_name=$_POST['work_name'];
	$file_type=$_POST['work_type'];
	$upload_type=$_POST['upload_type'];
	$time=$_POST['work_time'];
	$class_id=$_POST['class_id'];
	if(is_uploaded_file($_FILES['file']['tmp_name'])) {
		//查找学生的班级
		$sql=" select user_class from student where user_id='$user_id'";
		$result=mysqli_query($con,$sql);
		$class=mysqli_fetch_array($result);		
        //把文件转存到你希望的目录（不要使用copy函数）  
        $uploaded_file=$_FILES['file']['tmp_name'];   
		$file_true_name=$work_name.'.'.$file_type;//实际文件名
		$file_upload_name=$user_name.' - '.$user_id." - ".$file_true_name; 
		if($file_type=='md'&&$upload_type!='repent')
		{
			$file_path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."(md)"."/".$class[0];
			$user_path=$file_path.'/'.$file_upload_name;  		
		}
		else if($upload_type=='normal')
        {	
			$file_path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."/".$class[0];
			$user_path=$file_path.'/'.$file_upload_name;  		
		}
		else if($upload_type!='repent')
		{
			$file_path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."(补交)"."/".$class[0];
			if($upload_type=='abnormal')
			$user_path=$file_path.'/'.'(6h)'.$file_upload_name;
			else 
			$user_path=$file_path.'/'.'(3h)'.$file_upload_name;
			
		}
		else{
			$file_path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."(repent)";
			echo $file_path;
			$user_path=$file_path.'/'.'(repent)'.$file_upload_name;
		}
		//判断是否存在相应的文件夹
		$file_path=iconv("UTF-8", "GBK", $file_path);
		if(!file_exists($file_path)){
			mkdir ($file_path,0777,true);//不存在就建立
			chmod($file_path,0777);
		} 		
        if(move_uploaded_file($uploaded_file,iconv("UTF-8", "GBK", $user_path))) {  
			if($work_type!='repent'&&$file_type=='pdf'){//不是忏悔 所以要计算上交人数 以md格式为主
			$sql="select work_name from submit_list where work_name='$file_upload_name'&& user_id='$user_id'&&class_id='$class_id'";
			$result=mysqli_query($con,$sql);//执行sql
			if(mysqli_num_rows($result)==0) {//说明未存在
					$sql="select class_work_submit_number from classes where class_id='$class_id'";
					$result=mysqli_query($con,$sql);
					$number=mysqli_fetch_array($result);
					$number[0]++;
					$sql="update classes set class_work_submit_number='$number[0]' where class_id='$class_id'";
					$result=mysqli_query($con,$sql);//更新数据
				}
			}
			$sql="insert into submit_list (user_id, work_name,work_time,class_id) values('$user_id','$file_upload_name','$time','$class_id')";
			$result=mysqli_query($con,$sql);//执行sql
			if($result)
			{
			$ret=array('code'=>'200');
			echo json_encode($ret);
			}
			else
			{
				$ret=array('code'=>'203'); //传到数据库失败
				echo json_encode($ret);
			}
        } else {  
            echo "上传失败"; 
			$ret=array('code'=>'201');  //移动文件夹失败
			echo json_encode($ret);			
        }  
    } else {
		$ret=array('code'=>'202');
		echo json_encode($ret); //上传失败 
    }  
		mysqli_close($con);
?>  