<?php
	require_once 'database.php';
	$work_name=$_POST['class_work_name'];
	$work_size=$_POST['class_work_size'];
	$work_type=$_POST['class_work_type'];
	$work_deadline=$_POST['class_work_deadline'];
	$class_id=$_POST['class_id'];
		
		//更新上次作业名放入到数据库中用来抽取repent
		$sql="select class_work_name from classes where class_id='$class_id'";
		$result=mysqli_query($con,$sql);
		$class_work_name=mysqli_fetch_array($result);
		$sql="update classes set last_class_work_name='$class_work_name[0]' where class_id=$class_id";
		$result=mysqli_query($con,$sql);

		$sql1="update classes set class_work_name='$work_name',class_work_size='$work_size',class_work_type='$work_type',class_work_deadline='$work_deadline' where class_id='$class_id'";
		$result1=mysqli_query($con,$sql1);//执行sql
        	
		if (!$result1){
                    $ret=array('code'=>'201');
		    echo json_encode($ret);//如果sql执行失败输出错误
		
           }else{
					$dir = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$class_id.'/'.$work_name);
					$dir1 = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name);
					$dir2 = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(补交)");
					$dir3 = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(md)");
					$dir4 = iconv("UTF-8", "GBK", '/www/wwwroot/ashinz.cn/nuaa/Submitlist/'.$class_id.'/'.$work_name.'/'.$work_name."(repent)");
					if (!file_exists($dir)){
					mkdir ($dir,0777,true);
					chmod($dir,0777);
					mkdir ($dir1,0777,true);
					chmod($dir1,0777);
					mkdir ($dir2,0777,true);
					chmod($dir2,0777);
					mkdir ($dir3,0777,true);
					chmod($dir3,0777);
					mkdir ($dir4,0777,true);
					chmod($dir4,0777);
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