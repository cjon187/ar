<?php
	include_once('includes.php');

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Send anniversary emails to Laura and Lu
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Sending Franchise Agreement Reminder Email");
	write_log("*************************");

	$today = \Carbon\Carbon::now()->startOfDay();
	$date = \Carbon\Carbon::now()->startOfDay()->addDays(45);
	$date20 = \Carbon\Carbon::now()->startOfDay()->addDays(20);

	$desc = [
		TrainerFranchiseDates::FRANCHISEE => 'Franchise',
		TrainerFranchiseDates::CONTRACTOR => 'Contractor'
	];

	$sql = 'SELECT
				s.name,
				d.franchiseType,
				d.end
			FROM trainer_franchise_dates d
			INNER JOIN ps_staff s ON (d.staffID = s.staffID)
			WHERE
				d.end = "' . $date->format("Y-m-d") . '" AND
				s.status = 1 AND
				d.franchiseType IN (' . TrainerFranchiseDates::FRANCHISEE . ',' . TrainerFranchiseDates::CONTRACTOR . ')';
	$franchiseResults = $db->rawQuery($sql);

	$sql = 'SELECT
				s.name,
				s.insuranceExpiryDate
			FROM ps_staff s
			WHERE
				(s.insuranceExpiryDate = "' . $date->format("Y-m-d") . '" OR s.insuranceExpiryDate = "' . $date20->format("Y-m-d") . '") AND
				s.status = 1
			ORDER BY s.insuranceExpiryDate';
	$insuranceResults = $db->rawQuery($sql);

	if(count($franchiseResults) || count($insuranceResults)) {
		$subject = "Franchise/Contract Agreement Reminder : ".$today->format('M d, Y');
		$from = "no-reply@absoluteresults.com";
		$to = [ "hr@absoluteresults.com","lkerr@absoluteresults.com"];

		$body = '<div style="font-family:Arial">';

		if(count($franchiseResults)) {
			$body .= '<h2 style="margin-bottom: 0px;">Upcoming Franchise/Contract Agreement Expiries</h2>
						<br>
						<table style="border:1px solid black; border-collapse: collapse;" >
							<tr>
								<th style="border:1px solid black; padding: 1px 5px;">Name</th>
								<th style="border:1px solid black; padding: 1px 5px;">Staff Type</th>
								<th style="border:1px solid black; padding: 1px 5px;">Expire</th>
							</tr>';


			foreach($franchiseResults as $re){
				$body .= '<tr>
							<td style="border:1px solid black; padding: 1px 5px;">'.$re['name'] .'</td>
							<td style="border:1px solid black; padding: 1px 5px;">' . $desc[$re['franchiseType']] . '</td>
							<td style="border:1px solid black; padding: 1px 5px;">'. $re['end'] . ' </td>
						 </tr>';
			}
			$body .= '</table>';
		}

		if(count($insuranceResults)) {
			$body .= '<h2 style="padding-top: 15px;margin-bottom: 0px;">Upcoming Insurance Expiries</h2>
						<br>
						<table style="border:1px solid black; border-collapse: collapse;" >
							<tr>
								<th style="border:1px solid black; padding: 1px 5px;">Name</th>
								<th style="border:1px solid black; padding: 1px 5px;">Expire</th>
							</tr>';


			foreach($insuranceResults as $re){
				$body .= '<tr>
							<td style="border:1px solid black; padding: 1px 5px;">'.$re['name'] .'</td>
							<td style="border:1px solid black; padding: 1px 5px;">'. $re['insuranceExpiryDate'] . ' </td>
						 </tr>';
			}
			$body .= '</table>';
		}

		$body .= '</div>';

		$ec = new EmailController();
		$ec->sendEmail($subject,$body,$from,$to,[],[],[],Email::TYPE_FRANCHISE_AGREEMENT_REMINDER);
	}
	//$db->rawQuery('DELETE FROM absoluteresults_log.rsvp_unsubscribe_uuids WHERE `timestamp` < "'. $date .'"');
	write_log("*************************");
	write_log("Sent Franchise Agreement Reminder Email");
	write_log("*************************");
?>