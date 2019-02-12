<?php	
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('smsUtils.php');
		
	receiveSMS(strip_tags(trim($_GET['num'])), strip_tags(trim($_GET['cnum'])), strip_tags(urldecode(trim($_GET['message']))));	
	exit;
		
?>