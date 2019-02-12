<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	$five9 = new Five9Soap();
	$eventIDs = $five9->syncLeadsByDate(date("Y-m-d"));

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
?>