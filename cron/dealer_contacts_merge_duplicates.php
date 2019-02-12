<?php

	include_once('defines.php');
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('displayUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();

	$log = DealerContactsLoaderLog::byId($argv[1]);

	if($log instanceof DealerContactsLoaderLog) {

		$dcl = new DealerContactsLoader($log->dealer,$log->staff,$log);
		$dcl->setMessageMethod('socket');
		$dcl->loadContactsFromTempContacts();

	}

	$log->isComplete = 1;
	$log->completedAt = date("Y-m-d H:i:s");
	dev_log($log->save());
	dev_log($log->getFirstError());

?>