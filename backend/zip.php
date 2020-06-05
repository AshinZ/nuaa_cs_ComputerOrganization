<?php

function addFileToZip($path,$zip){
 $handler=opendir($path); //打开当前文件夹由$path指定。
 while(($filename=readdir($handler))!==false){
 if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
  if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
  addFileToZip($path."/".$filename, $zip);
  }else{ //将文件加入zip对象
  $zip->addFile($path."/".$filename);
  }
 }
 }
 @closedir($path);
}

$pathurl=$_POST['url1'];
$path="http://localhost/Submitlist/".$pathurl;
$zip=new ZipArchive();
if($zip->open('$path', ZipArchive::OVERWRITE)=== TRUE){
 addFileToZip('$path', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
 $zip->close(); //关闭处理的zip文件
}

$file_name = "work1.zip";
$fp=fopen($file_name,"r");//用只读方式打开需要下载的zip文件
    $file_size=filesize($file_name);//获取改文件的字节数
    Header("Content-type: application/octet-stream");
    Header("Accept-Ranges: bytes");
    Header("Accept-Length:".$file_size);
    Header("Content-Disposition: attachment; filename=$downname");//downname是用户下载后的文件名
    $buffer=1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
    $file_count=0; //读取的总字节数
    //向浏览器返回数据 如果下载完成就停止输出，如果未下载完成就一直在输出。根据文件的字节大小判断是否下载完成
    while(!feof($fp) && $file_count<$file_size){
        $file_con=fread($fp,$buffer);
        $file_count+=$buffer;
        echo $file_con;
    }
    fclose($fp);

    ?>