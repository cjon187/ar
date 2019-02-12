<?php
	include_once('includes.php');
	include_once('emailUtils.php');

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Send anniversary emails to Laura and Lu
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Sending Anniversiry Reminder Email");
	write_log("*************************");

	$dateFrom = \Carbon\Carbon::now()->startOfDay();
	$dateTo = \Carbon\Carbon::now()->startOfDay()->addDays(6);

	$results = $db->rawQuery('SELECT staffID, sl.name as staffLevelName, s.staffLevelID, s.name,
								staffStartDate, ROUND(DATEDIFF(NOW(), staffStartDate)/365) as diff,
								(366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff s
								INNER JOIN ps_staff_levels sl ON(s.staffLevelID = sl.staffLevelID)
								WHERE s.status = 1
								AND staffStartDate is not null
								AND (366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 <= 0
								ORDER BY left_days ASC');

	$results2 = $db->rawQuery('SELECT staffID, sl.name as staffLevelName, s.staffLevelID, s.name,
								staffStartDate, ROUND(DATEDIFF(NOW(), staffStartDate)/365) as diff,
								(366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff s
								INNER JOIN ps_staff_levels sl ON(s.staffLevelID = sl.staffLevelID)
								WHERE s.status = 1
								AND staffStartDate is not null
								AND (366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 <= 6
								ORDER BY left_days ASC');

	$results3 = $db->rawQuery('SELECT staffID, sl.name as staffLevelName, s.staffLevelID, s.name,
								staffStartDate, ROUND(DATEDIFF(NOW(), staffStartDate)/365) as diff,
								(366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff s
								INNER JOIN ps_staff_levels sl ON(s.staffLevelID = sl.staffLevelID)
								WHERE s.status = 1
								AND staffStartDate is not null
								AND (366 + DAYOFYEAR(staffStartDate) - DAYOFYEAR(NOW())) % 366 <= 30
								ORDER BY left_days ASC');




	if(count($results) > 0 || count($results2) > 0 || count($results3) > 0){
		$subject = "Anniversary Email Reminder : ".$dateFrom->format('M d, Y');
		$from = "no-reply@absoluteresults.com";
		$to = ['hr@absoluteresults.com',"lkerr@absoluteresults.com",'kwong@absoluteresults.com'];

		$body = '<h2 style="margin-bottom: 5px;">Staff Start Date Anniversaries</h2>
				<br>
				<h3 style="text-decoration:underline; margin:0;">Today</h3>
				<table style="border:1px solid black; border-collapse: collapse;" >
				<tr><th style="border:1px solid black; padding: 1px 5px;">Name</th><th style="border:1px solid black; padding: 1px 5px;">Staff Level</th><th style="border:1px solid black; padding: 1px 5px;">Start Date</th><th style="border:1px solid black; padding: 1px 5px;">Years</th></tr>';


		foreach($results as $re){
			$body .= '<tr>
						<td style="border:1px solid black; padding: 1px 5px;">'.$re['name'] .'</td>
						<td style="border:1px solid black; padding: 1px 5px;">' . $re['staffLevelName'] . '</td>
						<td style="border:1px solid black; padding: 1px 5px;">'. $re['staffStartDate'] . ' </td>
						<td style="border:1px solid black; padding: 1px 5px;">'. $re['diff'] . ' </td>
					 </tr>';
		}
		$body .= '</table>';

		$body .= '<br><h3 style="text-decoration:underline; margin:0;">Next 7 Days</h3>
				  <table style="border:1px solid black; border-collapse: collapse;" >
				  <tr><th style="border:1px solid black; padding: 1px 5px;">Name</th><th style="border:1px solid black; padding: 1px 5px;">Staff Level</th><th style="border:1px solid black; padding: 1px 5px;">Start Date</th><th style="border:1px solid black; padding: 1px 5px;">Years</th></tr>';

		foreach($results2 as $re){
			$body .= '<tr>
						<td style="border:1px solid black; padding: 1px 5px;">'.$re['name'] .'</td>
						<td style="border:1px solid black; padding: 1px 5px;">' . $re['staffLevelName'] . '</td>
						<td style="border:1px solid black; padding: 1px 5px;">'. $re['staffStartDate'] . ' </td>
						<td style="border:1px solid black; padding: 1px 5px;">'. $re['diff'] . ' </td>
					 </tr>';
		}
		$body .= '</table>';

		$body .= '<br><h3 style="text-decoration:underline; margin:0;">Next 30 Days</h3>
					<table style="border:1px solid black; border-collapse: collapse;" >
					<tr><th style="border:1px solid black; padding: 1px 5px;">Name</th><th style="border:1px solid black; padding: 1px 5px;">Staff Level</th><th style="border:1px solid black; padding: 1px 5px;">Start Date</th><th style="border:1px solid black; padding: 1px 5px;">Years</th></tr>';

		foreach($results3 as $re){
			$body .= '<tr>
					<td style="border:1px solid black; padding: 1px 5px;">'.$re['name'] .'</td>
					<td style="border:1px solid black; padding: 1px 5px;">' . $re['staffLevelName'] . '</td>
					<td style="border:1px solid black; padding: 1px 5px;">'. $re['staffStartDate'] . ' </td>
					<td style="border:1px solid black; padding: 1px 5px;">'. $re['diff'] . ' </td>
				 </tr>';
		}
		$body.="</table>";


		EmailController::sendEmail($subject,$body,$from,$to,[],[],[],Email::TYPE_ANNIVERSARY);
	}
	//$db->rawQuery('DELETE FROM absoluteresults_log.rsvp_unsubscribe_uuids WHERE `timestamp` < "'. $date .'"');
	write_log("*************************");
	write_log("Sent Anniversary Reminder Email");
	write_log("*************************");
?>