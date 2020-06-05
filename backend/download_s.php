<?php
		$file_name=$_POST['url'];
		$id=$_POST['id'];
$suffix=strrchr($file_name, '.');
$dir_name=basename($file_name,$suffix);
$dir=explode("-",$dir_name)[1]; 
$user_path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$dir."/".$file_name;
$user_path_return="/nuaa/Submitlist/".$dir."/".$file_name;
if (file_exists ( $user_path)) {    
    $ret=array('code'=>'200','url'=>$user_path_return);
	echo json_encode($ret);
}
else 
{
	$ret=array('code'=>'201');
	echo json_encode($ret);
}

?>