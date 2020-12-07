<?php
	$server="";//主机
		$db_username="";//你的数据库用户名
		$db_password="";//你的数据库密码
		$con = mysqli_connect($server,"",$db_password,$db_username);//链接数据库
		if(!$con)
			{
				die("cannot connect".mysqli_error());
			}		
?>