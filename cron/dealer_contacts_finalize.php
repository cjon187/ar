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
		if($dcl->finalizeLoad()) {

			$load->isComplete = 1;
			$load->completedAt = date("Y-m-d H:i:s");
			$load->save();
		}

	}

	$load->isFinalizing = 0;
	$load->save();

?>