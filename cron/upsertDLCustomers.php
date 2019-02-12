<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('displayUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	$ps = new PostgresDLStats(null,null);
	$ps->updateDLOrganizationIDs();

	//$scrubber = new ARNDNCScrubber();

	//$beginningTime = new DateTime(date("Y-m-d H:i:s"));

	$arDealerIDs = [];
	$dealers = Dealer::where('DLOrganizationID is not null')->get();

	//$dealers = Dealer::where('DLOrganizationID',2453)->get();
	//$dealers = Dealer::where('dealerID',552)->get();
	//$loadAll = true;


	if($loadAll) {
		$fromDate = '1900-01-01';
	} else {
		$fromDate = date("Y-m-d",strtotime('now - 3 days'));
	}

	foreach($dealers as $dealer) {
		if(!empty($dealer->id)) {
			$arDealerIDs[] = $dealer->id;
			shell_exec('php -f "' . AR_ROOT . 'cron/upsertDLCustomersByDealer.php" "' . $fromDate . '" ' . $dealer->id);

		} else {
			echo 'INVALID DEALER ID';
		}
	}


	$ps = new PostgresDLStats(null,null);
	$ps->updateOpportunitiesCount($arDealerIDs);


	$fromDate = date("Y-m-d",strtotime('now'));
	ob_start();
		$call = '"' . AR_ROOT . 'drive_contacts_geolocator.exe" -live -type drive -since ' . $fromDate;
		echo $call;
		passthru($call);
		$output = ob_get_contents();
	ob_end_clean();

	echo PHP_EOL.PHP_EOL.$output;


	$arGlobalDncScrubber = new ArGlobalDncScrubber();
	$arGlobalDncScrubber->scrubArGlobalDNC();

	echo PHP_EOL.PHP_EOL.'DNC SCRUBBED';

	$arGlobalDneScrubber = new ArGlobalDneScrubber();
	$arGlobalDneScrubber->scrubArGlobalDNE();

	echo PHP_EOL.PHP_EOL.'DNE SCRUBBED';

	$scrubber = new TaargaPhoneScrubber();
	$scrubber->uploadCanadianPhoneNumbers();

	echo PHP_EOL.PHP_EOL.'Taarga Uploaded';

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>