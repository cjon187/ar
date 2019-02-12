<?php

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////
	//stats_events
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Event Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$statColumns = ['apptGoal',
					'soldGoal',
					'appt',
					'show',
					'sold',
					'apptShow',
					'apptSold',
					'upShow',
					'upSold',
					'demo',
					'apptSMS',
					'salesrepCount',
					'notWalkInShow',
					'usedSold',
					'newSold',
					'up',
					'noApptShow',
					'walkInShow',
					'completeShow',
					'invites',
					'conquest',
					'addressedConquest',
					'closing',
					'apptClosing',
					'upClosing',
					'apptKeptRate',
					'apptPace',
					'soldPace',
					'newToDealerSold',
					'newToDealerTrackbackSold',
					'ocApptShow',
					'ocApptSold',
					'ocShow',
					'ocSold',
					'ocUnits',
					'offMakeTrades',
					'trackback_appt',
					'trackback_web',
					'trackback_show',
					'trackback_sold',
					'digitalLeads',
					'digitalAppt',
					'digitalShow',
					'digitalSold',
					'webLeads',
					'webAppt',
					'webShow',
					'webSold',
					'consumer_portalLeads',
					'consumer_portalAppt',
					'consumer_portalShow',
					'consumer_portalSold',
					'dumpsterLeads',
					'dumpsterContacted',
					'dumpsterAppt',
					'dumpsterShow',
					'dumpsterSold',
					'emailSent',
					'emailLeads',
					'emailAppt',
					'emailShow',
					'emailSold',
					'driveLeads',
					'driveAppt',
					'driveShow',
					'driveSold',
					'smsSent',
					'smsLeads',
					'smsAppt',
					'smsShow',
					'smsSold',
					'smsAPILeads',
					'smsAPIAppt',
					'smsAPIShow',
					'smsAPISold',
					'arcCalls',
					'arcHandRaiser',
					'arcHot',
					'arcWarm',
					'arcLeads',
					'arcAppt',
					'arcShow',
					'arcSold',
					'inviteWeb',
					'nonInviteWeb',
					'investments',
					'investments_writtenPerCar'];

	$columns = [];
	$columns[] = 'eventID';
	$columns[] = 'dealerID';
	foreach($statColumns as $col) {
		$columns[] = $col;
	}

	$fieldTypes = '';
    foreach($columns as $col) {
    	$fieldNames[] = '`'.$col.'`';
    	$insertParams[] = '?';
    	$fieldTypes .= 'i';
    }
	$stmt = $db->mysqli()->prepare('REPLACE INTO ps_stats_events (' . implode(',',$fieldNames) . ') VALUES (' . implode(',',$insertParams) . ')');

	if (!$stmt) {
	    $error = "Prepare failed: (" . $db->mysqli()->errno . ") " . $db->mysqli()->error;
	    EmailController::sendEmail('CRON JOB Failed','Stats Events: ' . $error,'devteam@absoluteresults.com','devteam@absoluteresults.com',[],[],[],Email::TYPE_CRON_ERROR);

		write_log($error,false);
		exit;
	}

	//$start = '2008-01';
	$start = '2013-10';
	$start = date("Y-m",strtotime('now - 3 months'));
	$end = date("Y-m");
	$month = $start;

	$ac = new AgreementController();

	while($month <= $end) {
		$appt = 'ps_appointments_' . date("my",strtotime($month));

		$events = array();
		$sql = 'SELECT * FROM ps_events WHERE salesTypeID in (1,2,5,6,8) AND saleStartDate >= "' . $month . '" AND saleStartDate <= "' . date("Y-m-t",strtotime($month)) . '" AND eventStart <= "' . date("Y-m-d") . '"';
		//$sql = 'SELECT * FROM ps_events WHERE eventID=59088';
		$results = mysqli_query($db_data,$sql);
		while($re = mysqli_fetch_assoc($results)) {
			$events[$re['eventID']] = $re;
		}

		$stats = displayStats($events,array('mailed','salesrepCount','completeShow','ocSummary','event_rank','trackback','offMakeTrades','web','digital','email','sms','arc','dumpster','drive','consumer_portal'),false);
		unset($stats['totals']);
		$eids = array_keys($stats);
		if(!empty($eids)) {
			$smsTasks = TaskSms::where('eventID',$eids,'IN')->arraybuilder()->get();
			if(!empty($smsTasks)) {
				foreach($smsTasks as $t) {
					if(!empty($t['quantity_sent'])) {
						$stats[$t['eventID']]['smsSent'] += $t['quantity_sent'];
					}
				}
			}

			$emailTasks = TaskEmail::where('eventID',$eids,'IN')->arraybuilder()->get();
			if(!empty($emailTasks)) {
				foreach($emailTasks as $t) {
					if(!empty($t['quantity_sent'])) {
						$stats[$t['eventID']]['emailSent'] += $t['quantity_sent'];
					}
				}
			}

			foreach($stats as $eid => $t) {
				$stats[$eid]['arcCalls'] = $t['arc_calls'];
				$stats[$eid]['arcLeads'] = $t['arc_leads'];
				$stats[$eid]['arcHandRaiser'] = $t['arc_hand_raiser'];
				$stats[$eid]['arcHot'] = $t['arc_hot'];
				$stats[$eid]['arcWarm'] = $t['arc_warm'];
				$stats[$eid]['arcAppt'] = $t['arc_appt'];
				$stats[$eid]['arcShow'] = $t['arc_show'];
				$stats[$eid]['arcSold'] = $t['arc_sold'];
			}

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
			$valArr[] = & $eid;
			$valArr[] = & $events[$eid]['dealerID'];
			foreach($statColumns as $col) {
				$valArr[] = & $stat[$col];
			}


	    	call_user_func_array(array($stmt, 'bind_param'), $valArr);

	    	if(!$stmt->execute()) {
	    		write_log("ERROR Event #" . $eid . " | ",false);
				$db->rollback();
				exit;
			} else {
				write_log("Event #" . $eid . " | ",false);
			}
		}

		$db->commit();
		$month = date('Y-m',strtotime($month . '-28 + 10 days'));
	}
	//exit;

	$sql = 'UPDATE
			ps_stats_events se
			INNER JOIN ps_events e ON (se.eventID = e.eventID)
			SET se.numSaleDays = datediff(saleEndDate,e.saleStartDate) + 1
			WHERE se.numSaleDays IS NULL AND e.confirmed = "confirmed" AND e.salesTypeID = ' . SalesType::PRIVATE_SALE;
	$db->rawQuery($sql);

	$sc = new StatsController();
	$sc->updateSoldRank();
	$sc->updateSoldPercent();

	write_log("");
	write_log("*************************");
	write_log("Complete Event Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");


?>