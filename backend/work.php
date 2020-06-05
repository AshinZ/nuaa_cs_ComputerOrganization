<?php
	require_once 'database.php';
	$work_name=$_POST['class_work_name'];
	$work_name_form=$_POST['class_work_name_form'];
	$work_size=$_POST['class_work_size'];
	$work_file_type_array=$_POST['class_work_file_type']; //上传的文件类型对应的是否按照班级存储
	$work_type_array=$_POST['class_work_type'];  //上传的文件类型
	$work_deadline=$_POST['class_work_deadline'];
	$class_id=$_POST['class_id'];
	
		$work_file_type=$work_file_type_array[0];
		$work_type=$work_type_array[0];
		$i=1;
		for(;$i<count($work_file_type_array);++$i){
			$work_file_type=$work_file_type.'+'.$work_file_type_array[$i];
			$work_type=$work_type.'+'.$work_type_array[$i];
		}
		//更新上次作业名放入到数据库中用来抽取repent
		$sql="select class_work_name from classes where class_id='$class_id'";
		$result=mysqli_query($con,$sql);
		$class_work_name=mysqli_fetch_array($result);
		$sql="update classes set last_class_work_name='$class_work_name[0]' where class_id=$class_id";
		$result=mysqli_query($con,$sql);

		$sql1="update classes set class_work_name='$work_name',class_work_size='$work_size',class_work_type='$work_type',
		class_work_file_type='$work_file_type',class_work_deadline='$work_deadline',class_work_name_form='$work_name_form' 
		where class_id='$class_id'";
		$result1=mysqli_query($con,$sql1);//执行sql
        	
		if (!$result1){
                    $ret=array('code'=>'201');
		    echo json_encode($ret);//如果sql执行失败输出错误
		
           }else{
					$dir = iconv("UTF-8", "GBK", '../Submitlist/'.$class_id.'/'.$work_name);
					if (!file_exists($dir)){
					mkdir ($dir,0777,true);
					chmod($dir,0777);
					$i=0;
					for(;$i<count($work_type_array);++$i){
						$dir = iconv("UTF-8", "GBK", '../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name.'('.$work_type_array[$i].')');
						mkdir ($dir,0777,true);
						chmod($dir,0777);
					}
					$dir = iconv("UTF-8", "GBK", '../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name.'(补交)');
					mkdir ($dir,0777,true);
					chmod($dir,0777);
					$dir = iconv("UTF-8", "GBK", '../Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name.'(repent)');
					mkdir ($dir,0777,true);
					chmod($dir,0777);
					$ret=array('code'=>'200');
					echo json_encode($ret);//如果sql执行失败输出错误
					} 
					else {
					$ret=array('code'=>'202');
					echo json_encode($ret);//如果sql执行失败输出错误
					}
          }
    mysqli_close($con);//关闭数据库
?>