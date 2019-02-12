<?php

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////
	//stats_events
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Setting Event KPIs | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$sql = "
		SELECT * FROM
		(
			SELECT 
				eventID,
				dealerID
			FROM ps_events e
			WHERE 
				e.salesTypeID = " . SalesType::PRIVATE_SALE . " AND
				e.confirmed = 'confirmed' AND
				e.eventStart > NOW()
			ORDER BY eventStart ASC
		) t
		GROUP BY dealerID
	";

	$results = $db->rawQuery($sql);

	$eventIDs = [];
	if(!empty($results)){
		foreach($results as $re) {
			$eventIDs[] = $re['eventID'];
		}


		$ekc = new EventKpiController();
		$events = Event::where('eventID',$eventIDs,'IN')->get();
		foreach($events as $event) {
			write_log("Calculating {$event->eventName}");
			$ekc->saveEventKPIs($event);
		}
	}


	write_log("");
	write_log("*************************");
	write_log("Complete Setting Event KPIs | " . date("Y-m-d H:i:s"));
	write_log("*************************");


?>