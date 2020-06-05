<?php
	require_once 'database.php';
	header("Content-Type:text/html; charset=utf-8");
	$file_des=$_POST['des'];
	if(is_uploaded_file($_FILES['file']['tmp_name'])) {  
        //把文件转存到你希望的目录（不要使用copy函数）  
        $uploaded_file=$_FILES['file']['tmp_name'];  
        //我们给每个用户动态的创建一个文件夹  
        $user_path=$_SERVER["DOCUMENT_ROOT"]."/resourse"; 
		$suffix=strrchr($_FILES['file']['name'], '.');
		$file_true_name=$_FILES['file']['name'];
		if($suffix==".rm"||$suffix==".rmvb"||$suffix==".wmv"||$suffix==".avi"||$suffix==".3gp"||$suffix==".mkv")
		{
			$user_path="F:/phpstudy/PHPTutorial/WWW/resourse"."/video/".$file_true_name;  
		}	
		else
		{
			$user_path="F:/phpstudy/PHPTutorial/WWW/resourse"."/text/".$file_true_name;  
		}
        if(move_uploaded_file($uploaded_file,iconv("UTF-8", "GBK", $user_path))) 
		{  
            echo $_FILES['file']['tmp_name']."上传成功";  
			$sql="insert into resourse (file_name,file_type,file_des) values ('$file_true_name','$suffix','$file_des')";
			$result=mysqli_query($con,$sql);
        } 
		else 
		{  
            echo "上传失败";  
        }  
    } 
	else {  
        echo "上传失败";  
    }  
?>  