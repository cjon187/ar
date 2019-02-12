<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('displayUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();



	$beginningTime = new DateTime(date("Y-m-d H:i:s"));

	$dealers = Dealer::where('DLOrganizationID',[2455,2461,2450,2460,2449,2462],'IN')->get();
	$fromDate = '1900-01-01';

	foreach($dealers as $dealer) {
		if(!empty($dealer->id)) {

			$cl = new ContactsLoader($dealer);
			$cl->setShowMethod('echo');
			if(!$cl->loadData('dl',['date'=>$fromDate],false)) {
				$cl->showStatus($cl->errors);
				$cl->showStatus(array('success' => 0));
				exit;
			}

			$doneTime = new DateTime(date("Y-m-d H:i:s"));
			$totalInterval = $doneTime->diff($beginningTime);
		    $cl->showStatus('Total Time Used:' . $totalInterval->format('%hH %IM %SS') . PHP_EOL);
		    $cl->showStatus('----------------------------------------' . PHP_EOL);

		    $sql = 'DELETE FROM aris_dealer_modules WHERE dealerID=' . $dealer->id;
		    $db->rawQuery($sql);

		    $values = [];
		    $values[] = '(' . $dealer->id .',1,1,1)';
		    $values[] = '(' . $dealer->id .',2,1,1)';
		    $values[] = '(' . $dealer->id .',5,1,1)';
		    $values[] = '(' . $dealer->id .',7,1,1)';
		    $values[] = '(' . $dealer->id .',9,1,1)';
		    $sql = 'INSERT INTO aris_dealer_modules (dealerID,moduleID,level,status) VALUES ' . implode(',',$values);
		    $db->rawQuery($sql);

		} else {
			echo 'INVALID DEALER ID';
		}
	}

	$scrubber = new TaargaPhoneScrubber();
	$scrubber->uploadCanadianPhoneNumbers();
?>