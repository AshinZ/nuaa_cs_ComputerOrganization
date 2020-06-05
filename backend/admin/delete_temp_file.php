<?php
	$dir="../../temp";
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
	}
	$ret=array('code'=>'200');
	echo json_encode($ret);







?>