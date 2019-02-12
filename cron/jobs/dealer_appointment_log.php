<?php

	include_once('includes.php');
	use Carbon\Carbon;

	////////////////////////////////////////////////////////////////////
	//Dealer Appointments Log
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Populating Dealer Appointments Log | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$now = Carbon::now();
	$inTwoDays = $now->copy()->addDay(2)->toDateString();
	$tomorrow = $now->copy()->addDay(1)->toDateString();
	$yesterday = $now->subDay(1)->toDateString();
	$twoDaysAgo = $now->subDay(1)->toDateString(); 
	$threeDaysAgo = $now->subDay(1)->toDateString(); 

	$events = Event::where("eventEnd IN (
		'{$inTwoDays}',
		'{$tomorrow}',
		'{$yesterday}',
		'{$twoDaysAgo}',
		'{$threeDaysAgo}'
	)")->get();

	if(!empty($events)) {
		foreach($events as $event) {

			write_log($event->eventName,false);
			$logger = new DealerAppointmentLogger($event->dealer);
			$logger->clearAppointmentLogs($event);

			try {
				$logger->insertAppointmentLog($event);
				write_log(' - Success');
			} catch (Exception $e) {
				write_log(" - Failed - {$logger->getFirstError()}");
			}
		}
	}

	write_log("");
	write_log("*************************");
	write_log("Complete Populating Dealer Appointments Log | " . date("Y-m-d H:i:s"));
	write_log("*************************");


?>