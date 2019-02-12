<?php

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////
	//stats_events
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Investments Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$statColumns = [
					'investments',
					'investments_writtenPerCar'];

	$columns = [];
	foreach($statColumns as $col) {
		$columns[] = $col;
	}

	$fieldTypes = '';
    foreach($columns as $col) {
    	$sets[] = '`'.$col.'`=?';
    	$fieldNames[] = '`'.$col.'`';
    	$insertParams[] = '?';
    	$fieldTypes .= 'i';
    }
    $fieldTypes .= 'i';

	$stmt = $db->mysqli()->prepare('UPDATE ps_stats_events SET ' . implode(',',$sets) . ' WHERE eventID=?');
	echo $fieldTypes;
	if (!$stmt) {
	    $error = "Prepare failed: (" . $db->mysqli()->errno . ") " . $db->mysqli()->error;
	    EmailController::sendEmail('CRON JOB Failed','Stats Events: ' . $error,'devteam@absoluteresults.com','devteam@absoluteresults.com',[],[],[],Email::TYPE_CRON_ERROR);

		write_log($error,false);
		exit;
	}

	$start = '2016-01';
	//$start = '2017-04';
	$end = date("Y-m");
	$month = $start;

	$ac = new AgreementController();

	while($month <= $end) {
		$appt = 'ps_appointments_' . date("my",strtotime($month));

		$events = array();
		$sql = 'SELECT * FROM ps_events WHERE salesTypeID in (1,2,5,6,8) AND saleStartDate >= "' . $month . '" AND saleStartDate <= "' . date("Y-m-t",strtotime($month)) . '" AND eventStart <= "' . date("Y-m-d") . '"';

		$results = mysqli_query($db_data,$sql);
		while($re = mysqli_fetch_assoc($results)) {
			$events[$re['eventID']] = $re;
		}
		$stats = [];
		$eids = array_keys($events);
		if(!empty($eids)) {

			$investments = $ac->getInvestmentByEventIDs($eids);
			if(!empty($investments)) {
				foreach($investments as $eid => $total) {
					$stats[$eid]['investments']  = $total;
				}
			}


			$investments = $ac->getInvestmentByEventIDs($eids,['useWrittenSold' => 1]);
			if(!empty($investments)) {
				foreach($investments as $eid => $total) {
					$stats[$eid]['investments_writtenPerCar'] = $total;
				}
			}

		}

		$db->startTransaction();

		foreach($stats as $eid => $stat)
		{
			$valArr = [];
	    	$valArr[] = & $fieldTypes;
			foreach($statColumns as $col) {
				$valArr[] = & $stat[$col];
			}
			$valArr[] = & $eid;

	    	call_user_func_array(array($stmt, 'bind_param'), $valArr);

	    	if(!$stmt->execute()) {
	    		write_log("ERROR Event #" . $eid . " | ",false);
				$db->rollback();
				exit;
			} else {
				write_log("Event #" . $eid . " | ",false);
			}

			/*$sql = 'REPLACE INTO ps_stats_events (' . implode(',',$columns) . ') VALUES (' . implode(',',$valArr) . ')';
			mysqli_query($db_data,$sql);

			write_log("Event #" . $eid . " | ",false);
			*/
		}

		$db->commit();
		$month = date('Y-m',strtotime($month . '-28 + 10 days'));
	}

	write_log("");
	write_log("*************************");
	write_log("Complete Investements Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");


?>