<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');


	$deadlines = array();
	$events = array();
	$sql = 'SELECT * FROM
			(
				SELECT e.eventID,d.dealerName,e.eventStart,e.saleStartDate,s.name,"Invitations",if(blt.name != "",blt.name,"Database") as "Source",t.status,t.printBy FROM
				ps_events e
				INNER JOIN ps_tasks_invitations t USING (eventID)
				INNER JOIN ps_dealers d ON (e.dealerID = d.dealerID)
				LEFT JOIN ps_dealers td ON (t.dealerID = td.dealerID)
				LEFT JOIN absoluteresults_constants.bought_list_types blt ON (td.boughtListTypeID = blt.boughtListTypeID)
				INNER JOIN ps_staff s ON (e.accountManagerID = s.staffID)
				WHERE e.eventEnd >= CURDATE() AND (t.status is null OR t.status not in ("sent","printed","shipped","confirmed")) AND t.printBy is not NULL and t.printBy < CURDATE() AND d.countryID=234
				UNION
				SELECT e.eventID,d.dealerName,e.eventStart,e.saleStartDate,s.name,"Conquest","",t.status,t.printBy FROM
				ps_events e
				INNER JOIN ps_tasks_conquests t USING (eventID)
				INNER JOIN ps_dealers d ON (e.dealerID = d.dealerID)
				INNER JOIN ps_staff s ON (e.accountManagerID = s.staffID)
				WHERE e.eventEnd >= CURDATE() AND (t.status is null OR t.status not in ("sent","printed","shipped","confirmed")) AND t.printBy is not NULL and t.printBy < CURDATE() AND d.countryID=234
			) tbl
			ORDER BY printBy,dealerName
			';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {
		$deadlines[] = $re;
		$events[$re['eventID']] = 1;
	}

	if(count($deadlines) > 0) {
		$subject = count($events) . ' Events Past Production Deadlines';
		$body = '<div style="font-family:arial">
					Here are upcoming events that are past deadline to print invitations/conquests.
					<br><br>
					<table>
						<tr><td>Event ID</td><td>Dealer Name</td><td>Trainer In Date</td><td>Sale Start Date</td><td>Account Manager</td><td>Task Type</td><td>Data Source</td><td>Current Status</td><td>Print By Date</td></tr>';
		foreach($deadlines as $t)	$body .= '<tr><td>' . implode('</td><td>',$t) . '</td></tr>';

		$body .= '
					</table>
				</div>';

		$from = 'web@absoluteresults.com';
		$to = array('jrodney@absoluteresults.com');
		$bcc = array('dave@absoluteresults.com');
		EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,[],Email::TYPE_PRODUCTION_DEADLINE);
	}

?>