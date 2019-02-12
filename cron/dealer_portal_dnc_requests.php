<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('defines.php');

	$db = new ARDB();


	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}

	$dlc = new DncLogController();
	var_dump($dlc->processDNCLog());

?>