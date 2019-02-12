<?php
	
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	
	if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['id'],$_GET['ekey'],'quote')) 
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else
	{
		checkPageAccess();
	}

	$db = new ARDB();
	$quote = Quote::byId($_GET['id']);
	
	header("Content-Type: image/png");
	echo base64_decode(explode('base64,',$quote->signature)[1]);
?>