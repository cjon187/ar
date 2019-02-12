<?php
	require_once('defines.php');
	include_once('displayUtils.php');

	$db = new ARDB();
	$lec = new ListExportController();
	$lec->processExportQueue();

	exit;
?>