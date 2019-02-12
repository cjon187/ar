<?php

	include_once('defines.php');
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('displayUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();

	$load = DealerContactsLoad::byId($argv[1]);

	if($load instanceof DealerContactsLoad) {

		$dcl = new DealerContactsLoader($load->dealer,$load->staff,$load);
		$dcl->setMessageMethod('socket');
		$dcl->loadFromTempContacts();

	}

	$load->isMerging = 0;
	$load->mergingComplete = date("Y-m-d H:i:s");
	$load->save();
?>