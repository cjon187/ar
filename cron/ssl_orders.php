<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('defines.php');

	$db = new ARDB();

	$rwc = new RsvpWebsiteController();
	$rwc->processPendingSSLOrders();

?>