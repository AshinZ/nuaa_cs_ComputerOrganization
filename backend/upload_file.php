<?php
	require_once 'database.php';
	$user_name=$_POST['user_name'];
	$user_id=$_POST['user_id'];
	$work_name=$_POST['work_name'];
	$work_personal_name=$_POST['work_personal_name'];
	$file_type=$_POST['work_type'];
	$upload_type=$_POST['upload_type'];
	$time=$_POST['work_time'];
	$class_id=$_POST['class_id'];
	$save_way=$_POST['save_way'];
	if(is_uploaded_file($_FILES['file']['tmp_name'])) {
		//查找学生的班级
		$sql=" select user_class from student where user_id='$user_id'";
		$result=mysqli_query($con,$sql);
		$class=mysqli_fetch_array($result);		
        //把文件转存到你希望的目录（不要使用copy函数）  
        $uploaded_file=$_FILES['file']['tmp_name'];   
		$file_upload_name=$work_personal_name.'.'.$file_type; 
		if($upload_type=='repent'){
			$file_path="../Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."(repent)"."/".$class[0];
			$user_path=$file_path.'/'.$file_upload_name;  		
		}
		else if($upload_type=='normal'){  //正常提交根据作业类型分类
			if($save_way==1)//分班存储
				$file_path="../Submitlist/".$class_id.'/'.$work_name.'/'.$work_name.'('.$file_type.')'."/".$class[0];
			else if($save_way==0)
				$file_path="../Submitlist/".$class_id.'/'.$work_name.'/'.$work_name.'('.$file_type.')';
			$user_path=$file_path.'/'.$file_upload_name;  		
		}
		else {
			$file_path="../Submitlist/".$class_id.'/'.$work_name.'/'.$work_name."(补交)"."/".$file_type.'/'.$class[0];
			if($upload_type=='abnormal3')
			$user_path=$file_path.'/'.'(3h)'.$file_upload_name;
			else 
			$user_path=$file_path.'/'.'(6h)'.$file_upload_name;
		}
		//上面主要是进行路径和文件名的选择与重置
		//判断是否存在相应的文件夹
		$file_path=iconv("UTF-8", "GBK", $file_path);
		if(!file_exists($file_path)){
			mkdir ($file_path,0777,true);//不存在就建立
			chmod($file_path,0777);
		} 		
        	if(move_uploaded_file($uploaded_file,iconv("UTF-8", "GBK", $user_path))) {  
		/*	if($work_type!='repent'&&$file_type=='pdf'){//不是忏悔 所以要计算上交人数 以md格式为主
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
			}*/
			$sql="insert into submit_list (user_id, work_name,work_time,class_id) values('$user_id','$file_upload_name','$time','$class_id')";
			$result=mysqli_query($con,$sql);//执行sql
			if($result)
			{
			$ret=array('code'=>'200');
			$log_state='success';
			}
			else
			{
				$ret=array('code'=>'203'); //传到数据库失败
				$log_state='error:mysql error';
				
			}
        } else {  
			$ret=array('code'=>'201');  //移动文件夹失败
			$log_state='error:file move fail';
        }  
    } else {
		$ret=array('code'=>'202');
		$log_state='error:file not upload';
    }  
		$log_time=date("Y-m-d h:i:sa");
		$log_content=$user_id.':try upload file'.$class_id.'->'.$work_name;
		$sql="insert into log (log_time,log_content,log_state) values ('$log_time','$log_content','$log_state')";
		$result=mysqli_query($con,$sql);

		echo json_encode($ret);
		mysqli_close($con);
?>  