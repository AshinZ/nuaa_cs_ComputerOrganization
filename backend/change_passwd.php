<?php
header("Content-Type: text/json; charset=utf8");
	require_once 'database.php';
    $id=$_POST['id'];
    $old_passwd=$_POST['old_passwd'];
    $new_passwd=$_POST['new_passwd'];
    
    if ($id && $old_passwd&& $new_passwd){//����û��������붼��Ϊ��
             $sql = "select user_id from student where user_id = '$id' and user_passwd='$old_passwd'";
             //������ݿ��Ƿ��ж�Ӧ��username��password��sql
             $result = mysqli_query($con,$sql);//ִ��sql
             $rows=mysqli_num_rows($result);//����һ����ֵ
             if($rows){//0 false 1 true
					$sql1="update student set user_passwd='$new_passwd' where user_id='$id'";
					$result=mysqli_query($con,$sql1);
					$ret=array('code'=>'200');
					$log_state="success";
			 }
			 else
			 {
				$ret=array('code'=>'201');
				$log_state='error:passwd is wrong';
			 }
			 echo json_encode($ret);
	}
	$log_time=date("Y-m-d h:i:sa");
	$log_content=$id.':try change passwd('.$old_passwd.'->'.$new_passwd.')';
	$sql="insert into log (log_time,log_content,log_state) values ('$log_time','$log_content','$log_state')";
	$result=mysqli_query($con,$sql);

    mysqli_close($con);//�ر����ݿ�
?>