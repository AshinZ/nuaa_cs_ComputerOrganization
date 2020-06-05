<?php

	$work_name=$_POST['work_name'];
	$class_id=$_POST['class_id'];
	$work_type=$_POST['work_type'];
	if($work_type=='2'&&$work_name=='undefined')
	{
		$ret=array('code'=>'202');
		echo json_encode($ret);
		return ;
	}
       	$flag=0;
	//根据类别进行下载
	if($work_type=='1'){
	$path=$work_name;
	$path=iconv("UTF-8","GBK",$path);

//	$sql="select class_work_id from work_list where class_type='work'";
//	$result=mysqli_query($con,$sql);
//	$id=mysqli_fetch_array($result);

	if(file_exists("../../Submitlist/".$class_id.'/work.zip')) 
	unlink("../../Submitlist/".$class_id.'/work.zip');//删除上次的文件
//	$id[0]++;

	$url="../../Submitlist/".$class_id.'/work.zip';
	$url1="Submitlist/".$class_id.'/work.zip';
	
//	$sql="update work_list set class_work_id='$id[0]' where class_type='work'";
//	$result=mysqli_query($con,$sql);

	}
	else {
	$path=$work_name.'(repent)'; //上次作业的文件夹
	$path=iconv("UTF-8","GBK",$path);


//	$sql="select class_work_id from work_list where class_type='repent'";
//	$result=mysqli_query($con,$sql);
//	$id=mysqli_fetch_array($result);

	if(file_exists("../../Submitlist/".$class_id.'/repent.zip'))
	unlink("../../Submitlist/".$class_id.'/repent.zip');
//	$id[0]++;

	$url="../../Submitlist/".$class_id.'/repent.zip';
	$url1="Submitlist/".$class_id.'repent.zip';


//	$sql="update work_list set class_work_id='$id[0]' where class_type='repent'";
//	$result=mysqli_query($con,$sql);

	}

function addFileToZip($path,$zip){
 $handler=opendir($path); //打开当前文件夹由$path指定。
 while(($filename=readdir($handler))!==false){
if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
  if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
  addFileToZip($path."/".$filename, $zip);
  }else{ //将文件加入zip对象
  $zip->addFile($path."/".$filename);  
  $GLOBALS['flag']=1;
}
 }
 }
 @closedir($path);
}

$zip=new ZipArchive();
if($zip->open($url,ZipArchive::CREATE)=== TRUE){
 addFileToZip($path, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
 $zip->close(); //关闭处理的zip文件
}
	if($GLOBALS['flag']==1)
	$ret=array('code'=>'200','url'=>$url1);
	else
	$ret=array('code'=>'201');
	echo json_encode($ret);
?>