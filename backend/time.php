<?php
	$time=date("Y-m-d h:i:sa");
	$ret=array('time'=>$time,"status"=>'200');
	echo json_encode($ret);
?>
