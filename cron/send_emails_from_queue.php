<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	$ec = new EmailController();
	$ec->sendNextInQueue();
	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>