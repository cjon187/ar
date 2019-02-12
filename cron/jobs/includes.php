<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('dataSQLUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('taskUtils.php');

	$db = new ARDB();

	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}

?>