<?php
	include_once('defines.php');
	$db = new ARDB();

	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;


	$results = $db->rawQuery('SELECT dealerID, dealerName, ndnc_reg_start_date,date_add(ndnc_reg_start_date, INTERVAL 12 MONTH ) as futureDate, to_days( date_add(ndnc_reg_start_date, INTERVAL 12 MONTH ) ) - to_days(current_date) as daysRemaining
 					from ps_dealers
 					WHERE to_days( date_add(ndnc_reg_start_date, INTERVAL 12 MONTH ) ) - to_days(current_date) between 0 and 30
 					AND status = 1
 					ORDER BY ndnc_reg_start_date ASC');



	$subject = "Expiring NDNC Registrations";
	$from = "no-reply@absoluteresults.com";
	$to = ["mkoole@absoluteresults.com","jrodney@absoluteresults.com","tholowchak@absoluteresults.com", "arc@absoluteresults.com"];

	$body = '<h2 style="margin-bottom: 5px; text-decoration:underline;">Expiring NDNC Registrations in the next 30 days</h2><br>';

	if(count($results) > 0){
		$body .= '<table style="font-family:calibri; font-size: 11pt; border-collapse: separate; " border="1" cellpadding= "8" >
	  		<thead>
	  			<tr>
	  				<th>Dealer Name</th>
	  				<th>NDNC Expiry</th>
	  				<th>Days Remaining</th>
	  			</tr>
	  		</thead>
	  		<tbody>';

		foreach($results as $re){
			$body .= '<tr><td><b>'.$re['dealerName'].'</b></td><td>'. $re['futureDate'] . '</td><td>'. $re['daysRemaining'] .' </td></tr>';
		}

		$body .= '</tbody></table>';
	}

	else{
		$body.= 'No Expiring registrations';
	}

	EmailController::sendEmail($subject,$body,$from,$to,[],[],[],Email::TYPE_NDNC_REGISTRATION_EXPIRATION_WARNING);


	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>