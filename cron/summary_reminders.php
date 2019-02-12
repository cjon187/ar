<?php

	include_once('defines.php');
	include_once('displayUtils.php');
	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	/*$db->join('ps_dealers d','(d.dealerID = e.dealerID)');
	$db->where('d.countryID',[COUNTRY_CA,COUNTRY_US],'IN');
	$db->where('e.trainingSummary IS NULL');
	$db->where('e.salesTypeID',SalesType::PRIVATE_SALE);
	$db->where('e.confirmed','confirmed');
	$db->where('e.eventStart',date('Y-m-d'));
	$results = $db->get('ps_events e',null,'e.eventID');

	if($results) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);
			$psc = new PrivateSaleController($event);
			$psc->sendDayOneEmail();
		}
	}*/


	$db->join('ps_dealers d','(d.dealerID = e.dealerID)');
	$db->where('d.countryID',[COUNTRY_CA,COUNTRY_US],'IN');
	$db->where('e.trainingSummary IS NULL');
	$db->where('e.salesTypeID',SalesType::PRIVATE_SALE);
	$db->where('e.confirmed','confirmed');
	$db->where('e.eventStart',date('Y-m-d',strtotime('now - 1 day')));
	$results = $db->get('ps_events e',null,'e.eventID');

	if($results) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);
			$psc = new PrivateSaleController($event);
			$psc->sendDayOneSummaryReminderEmail();
		}
	}


/*	$db->join('ps_dealers d','(d.dealerID = e.dealerID)');
	$db->where('d.countryID',[COUNTRY_CA,COUNTRY_US],'IN');
	$db->where('e.trainingSummary IS NOT NULL');
	$db->where('e.salesTypeID',SalesType::PRIVATE_SALE);
	$db->where('e.eventStart',date('Y-m-d',strtotime('now - 1 day')));
	$results = $db->get('ps_events e',null,'e.eventID');

	if($results) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);
			$psc = new PrivateSaleController($event);
			$psc->sendDayOneSummaryEmail();
		}
	}
*/

	$db->join('ps_dealers d','(d.dealerID = e.dealerID)');
	$db->where('d.countryID',[COUNTRY_CA,COUNTRY_US],'IN');
	$db->where('e.trainerSummarySent IS NULL');
	$db->where('e.salesTypeID',SalesType::PRIVATE_SALE);
	$db->where('e.confirmed','confirmed');
	$db->where('e.eventEnd',date('Y-m-d',strtotime('now - 3 days')));
	$results = $db->get('ps_events e',null,'e.eventID');

	if($results) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);
			$psc = new PrivateSaleController($event);
			$psc->sendTrainerSummaryReminderEmail();
		}
	}

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
?>