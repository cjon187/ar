<?php

	include_once('includes.php');

	include_once('emailUtils.php');


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Send birthday email to Laura
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Sending Birthday Reminder Email");
	write_log("*************************");

	$dateFrom = \Carbon\Carbon::now()->startOfDay();
	$dateTo = \Carbon\Carbon::now()->startOfDay()->addDays(6);

	/*$results = $db->rawQuery('SELECT staffID, name, birthdate from ps_staff
 					WHERE to_days( date_add(birthdate, interval  year("current_date") -year(birthdate)    year ) ) - to_days("current_date") between 0 and 0
 					AND status = 1
 					ORDER BY MONTH(birthdate), DAYOFMONTH(birthdate) ASC');

	$results2 = $db->rawQuery('SELECT staffID, name, birthdate from ps_staff
 					WHERE to_days( date_add(birthdate, interval  year("current_date") -year(birthdate)    year ) ) - to_days("current_date") between 0 and 6
 					AND status = 1
 					ORDER BY MONTH(birthdate), DAYOFMONTH(birthdate) ASC');

	$results3 = $db->rawQuery('SELECT staffID, name, birthdate from ps_staff
 					WHERE to_days( date_add(birthdate, interval  year("current_date") -year(birthdate)    year ) ) - to_days("current_date") between 0 and 30
 					AND status = 1
 					ORDER BY MONTH(birthdate), DAYOFMONTH(birthdate) ASC');*/

	$results = $db->rawQuery('SELECT staffID, name, birthdate,(366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff
								WHERE status = 1
									AND birthdate is not null
									AND (366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 <= 0
								ORDER BY left_days;');

	$results2 = $db->rawQuery('SELECT staffID, name, birthdate,(366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff
								WHERE status = 1
									AND birthdate is not null
									AND (366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 <= 6
								ORDER BY left_days;');

	$results3 = $db->rawQuery('SELECT staffID, name, birthdate,(366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 as left_days
								FROM ps_staff
								WHERE status = 1
									AND birthdate is not null
									AND (366 + DAYOFYEAR(birthdate) - DAYOFYEAR(NOW())) % 366 <= 30
								ORDER BY left_days;');



	$subject = "Birthday Email Reminder : ".$dateFrom->format('M d, Y');
	$from = "no-reply@absoluteresults.com";
	$to = ["hr@absoluteresults.com","lkerr@absoluteresults.com","kwong@absoluteresults.com"];

	$body = '<h2 style="margin-bottom: 5px;">Staff Birthdays</h2>
			<br>
			<h3 style="text-decoration:underline; margin:0;">Today</h3>';

	if(count($results) > 0){
		foreach($results as $re){
			$body .= $re['name'] . ' - '. $re['birthdate'] . ' <br>';
		}
	}

	$body .= '<br><h3 style="text-decoration:underline; margin:0;">Next 7 Days</h3>';
	if(count($results2) > 0){
		foreach($results2 as $re){
			$body .= $re['name'] . ' - '. $re['birthdate'] . ' <br>';
		}


	}

	$body .= '<br><h3 style="text-decoration:underline; margin:0;">Next 30 Days</h3>';
	if(count($results3) > 0){
		foreach($results3 as $re){
			$body .= $re['name'] . ' - '. $re['birthdate'] . ' <br>';
		}
	}

	EmailController::sendEmail($subject,$body,$from,$to,[],[],[],Email::TYPE_BIRTHDAY);

	//$db->rawQuery('DELETE FROM absoluteresults_log.rsvp_unsubscribe_uuids WHERE `timestamp` < "'. $date .'"');
	write_log("*************************");
	write_log("Sent Birthday Reminder Email");
	write_log("*************************");
?>