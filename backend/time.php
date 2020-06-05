<?php
	$time=date("Y-m-d H:i:s");
	$ret=array('time'=>$time,"code"=>'200');
	echo json_encode($ret);
?>
