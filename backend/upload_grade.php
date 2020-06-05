<?php
	header("Content-Type:text/html; charset=utf-8");
	if(is_uploaded_file($_FILES['file']['tmp_name'])) {   
        $uploaded_file=$_FILES['file']['tmp_name'];    
        $user_path=$_SERVER["DOCUMENT_ROOT"]."/grade";  
        $file_true_name=$_FILES['file']['name']; 
		$user_path="F:/phpstudy/PHPTutorial/WWW/grade/".$file_true_name;  		
        if(move_uploaded_file($uploaded_file,iconv("UTF-8", "GBK", $user_path))) {  
            echo $_FILES['file']['tmp_name']."上传成功，上传数据至数据库中";  
        } else {  
            echo "上传失败";  
        }  
    } else {  
        echo "上传失败";  
    }  
		require_once 'database.php';	
		require_once 'F:\phpstudy\PHPTutorial\WWW\phpExcelReader\Excel\reader.php';
		//创建 Reader
		$data = new Spreadsheet_Excel_Reader();
		//设置文本输出编码
		$data->setOutputEncoding('gb2312');
		//读取Excel文件
		$user_path="/phpstudy/PHPTutorial/WWW/grade/".$file_true_name;
		$data->read($user_path);
		$suffix=strrchr($_FILES['file']['name'], '.');
		if($suffix=='.xlsx')
			$work_name=basename($_FILES['file']['name'],".xlsx");
		else if ($suffix=='.xls')
			$work_name=basename($_FILES['file']['name'],".xls");
		//$data->sheets[0]['numRows']为Excel行数
		$q=0;  //判断是否写入成功
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
		//$data->sheets[0]['numCols']为Excel列数
		$stu_id=$data->sheets[0]['cells'][$i][1];
		$grade=$data->sheets[0]['cells'][$i][2];
		$sql="insert into stu_grade(stu_id,grade_name,grade) values ('$stu_id','$work_name','$grade')";
		$result=mysqli_query($con,$sql);//执行sql
		if(!$result)
			$q=1;
}
		if($q)
		{
			$ret=array('code'=>'201');
			echo json_encode($ret);
		}
		else
		{
			$ret=array('code'=>'200');
			echo json_encode($ret);
		}
	

?>