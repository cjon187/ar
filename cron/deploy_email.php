<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();

	$emailID = $argv[1];
	if($emailID && is_numeric($emailID)) {
		$ec = new EmailController();
		$ec->sendEmailById($emailID);
	}
?>