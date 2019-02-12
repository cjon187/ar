<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('dataUtils.php');

	$db = new ARDB();

	$rsvp_json = array();
    $logger = new ApptLogger($_GET['eventID'],$_GET['dealerID']);
    $rsvp_json['lead_email'] = $logger->log($_GET);

	echo json_encode($rsvp_json);
?>