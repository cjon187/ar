<?php
	
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('agreementUtils.php');
	
	if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['id'],$_GET['ekey'],'agreement')) 
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else
	{
		checkPageAccess();
	}

	$ag = displayAgreementInfo($_GET['id']);
	
	header("Content-Type: image/png");
	echo base64_decode(explode('base64,',$ag['signature'])[1]);
?>