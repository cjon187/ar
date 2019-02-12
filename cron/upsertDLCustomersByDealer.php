<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('displayUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();
	$fromDate = $argv[1];
	$dealerID = $argv[2];


	$dealer = Dealer::byID($dealerID);


	if(!empty($dealer->id)) {

		$beginningTime = new DateTime(date("Y-m-d H:i:s"));

		$cl = new ContactsLoader($dealer);
		$cl->setShowMethod('echo');

	    $cl->showStatus('----------------------------------------' . PHP_EOL);
	    $cl->showStatus('Updating Set Up Status ' . $dealer->name . PHP_EOL);

		$dealer->updateDLSetupStatus();
		$dealer->updateHardDeletedCustomers();


		if(ContactController::hasContacts($dealer->id)) {
			$date = $fromDate;
		} else {
			$date = '1900-01-01';
		}

		$doneTime = new DateTime(date("Y-m-d H:i:s"));
		$totalInterval = $doneTime->diff($beginningTime);

	    $cl->showStatus('Done Seting Up Status:' . $totalInterval->format('%hH %IM %SS') . PHP_EOL);
		if(!$cl->loadData('dl',['date'=>$date],false)) {
			$msg = 'Error loading ' . $dealer->name . ' ' . $dealer->id;
			EmailController::sendEmail('Error Loading DL Contacts',$msg,'devteam@absoluteresults.com','devteam@absoluteresults.com',[],[],[],Email::TYPE_UPSERT_DL_ERROR);
			$cl->showStatus($msg . PHP_EOL);
			$cl->showStatus($cl->errors);
			$cl->showStatus(array('success' => 0));
			exit;
		}

		$scrubber = new ARNDNCScrubber();
		$scrubber->scrubDealerNDNC($dealer->id,$dealer->countryID);

		$doneTime = new DateTime(date("Y-m-d H:i:s"));
		$totalInterval = $doneTime->diff($beginningTime);
		$cl->showStatus($dealer->name . ' '. $dealer->id . PHP_EOL);
	    $cl->showStatus('Total Time Used:' . $totalInterval->format('%hH %IM %SS') . PHP_EOL);
	    $cl->showStatus('----------------------------------------' . PHP_EOL . PHP_EOL . PHP_EOL);



	} else {
		echo 'INVALID DEALER ID';
	}
?>