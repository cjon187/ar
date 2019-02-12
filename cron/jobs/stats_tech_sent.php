<?php

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////
	//stats_events
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Tech Sent | " . date("Y-m-d H:i:s"));
	write_log("*************************");


	$eids = [];
	$sql = 'SELECT
				eventID
			FROM
				ps_events
			WHERE
				salesTypeID in (1,2,5,6,8)
				AND eventStart = "' . date("Y-m-d") . '"';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {
		$eids[] = $re['eventID'];
	}

	if(!empty($eids)) {
		$smsTasks = TaskSms::where('eventID',$eids,'IN')->get();
		if(!empty($smsTasks)) {
			foreach($smsTasks as $t) {
				write_log("SMS Tech #" . $t->id . " | ",false);
				$t->quantity_sent = $t->getCount();
				$t->save();
			}
		}

		$emailTasks = TaskEmail::where('eventID',$eids,'IN')->get();
		if(!empty($emailTasks)) {
			foreach($emailTasks as $t) {
				write_log("Email Tech #" . $t->id . " | ",false);
				$t->quantity_sent = $t->getCount();
				$t->save();
			}
		}
	}

	write_log("");
	write_log("*************************");
	write_log("Complete Tech Sent | " . date("Y-m-d H:i:s"));
	write_log("*************************");


?>