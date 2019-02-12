<?php
	//DO NOT ACTIVATE UNTIL FURTHER NOTICE
	exit;

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('defines.php');

	$db = new ARDB();


	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}


	//$sql = 'SELECT eventID FROM ps_events WHERE salesTypeID=5 and confirmed="confirmed" AND eventID = 42947';

	$sql = 'SELECT eventID FROM ps_events WHERE salesTypeID=5 AND confirmed="confirmed" AND saleStartDate = "' . date("Y-m-d",strtotime("now + 1 days")) . '" AND dealerID NOT IN(11500, 1850)';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results))
	{
		$event = displayEventInfo($re['eventID']);
		$dealer = displayDealerInfo($event['dealerID']);
		$dealerObj = Dealer::ById($event['dealerID']);
		$dealer_phone = ($dealer['marketingPhone'] == ''? $dealer['phone'] : $dealer['marketingPhone']);
		$dealer_lang = displayLanguage($dealer['languageID'])['major'];
		$emails_sent = 0;
		$emails_failed = 0;
		$webContactArray = array();
		$primaryContactArray = array();
		$leadFromContact = "";
		$sql = 'SELECT * FROM
				(SELECT dealerID FROM ps_events WHERE eventID=' . $event['eventID'] . ') as a1
				INNER JOIN
				(SELECT * FROM ps_dealerstaff WHERE status = 1 AND (webContact = 1 OR primaryContact = 1 OR leadFromContact = 1) AND email is not null AND email != "") as a2
				USING
				(dealerID)';
		$staff_results = mysqli_query($db_data,$sql);
		while($staff = mysqli_fetch_assoc($staff_results))
		{
			if($staff['webContact'] == 1) $webContactArray[] = $staff;
			if($staff['primaryContact'] == 1) $primaryContactArray[] = $staff;
			if($staff['leadFromContact'] == 1) $leadFromContact = $staff;
		}

		$from_emails = array();
		if(count($webContactArray) > 0) $from_emails = $webContactArray;
		else if(count($primaryContactArray) > 0) $from_emails = $primaryContactArray;
		else $from_emails = array(array('email' => 'web@absoluteresults.com'));


		if($leadFromContact != "") $from_staff = $leadFromContact;
		else $from_staff = $from_emails[0];


		$sql = 'SELECT * FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND email != "" AND (appointmentTime != "" OR source like "%web%" OR source like "%digital%")';

		$lead_results = mysqli_query($db_data,$sql);
		while($lead = mysqli_fetch_assoc($lead_results))
		{
			$subject = $dealer['dealerName'] . ' Event Reminder';
			if($dealer_lang == 'fr')
			{
				$body = '<div style="font-family:arial;font-size:11pt;">
							' . trim($lead['firstName'] . ' ' . $lead['lastName']) . ',
							<br>
							<br>Nous avons hâte de vous voir à notre événement de Vente Privée au ' . $dealer['dealerName'] . '!
							<br>
							<br><b>Adresse de l’événement</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer['address'] . ', ' . $dealer['city'] . '
							<br>
							<br><b>Date de l’événement </b>
							<br>' . displayEventDate($event,false,false,true) . '
							<br>';
				if($lead['appointmentTime'] != '')
				{
					$body .= '<br><b>Votre rendez-vous</b>
							  <br>' . date("M j, Y",strtotime($lead['appointmentTime'])) . ' ' . (stripos($lead['appointmentTime'],'00:00:00') === false ? date("g:i A",strtotime($lead['appointmentTime'])) : '') . '
							  <br>';
				}
				$body .= '	<br>
							<br>Si vous avez des questions à propos de cet événement, merci de nous contacter au ' . $dealer_phone . ' .
							<br>Nous avons hâte de vous voir à notre événement de Vente Privée.
							<br>
							<br><b>' . $from_staff['name'] . '</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer_phone . '
							</div>';
			}

			else if($dealer_lang == 'de')
			{
				$subject = $dealer['dealerName'] . ' Event Erinnerung';

				if($event['eventStart'] != $event['eventEnd']){
					$date = date('j.', strtotime($event['eventStart'])) . ' - ' . date('j.m.Y.', strtotime($event['eventEnd']));
				}
				else{
					$date = date('j.m.Y.', strtotime($event['eventEnd']));
				}

				$body = '<div style="font-family:arial;font-size:11pt;">
							Hallo ' . trim($lead['firstName'] . ' ' . $lead['lastName']) . ',
							<br>
							<br>Wir freuen uns sehr Sie zu unserem exklusiven Verkaufsevent bei ' . $dealer['dealerName'] . ' zu dürfen!
							<br>
							<br><b>Standort</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer['address'] . ', ' . $dealer['city'] . '
							<br>
							<br><b>Datum</b>
							<br>' . $date . '
							<br>';
					if($lead['appointmentTime'] != '')
					{
				$body 	.= '<br><b>Ihr Termin:</b>
								  <br>' . date("j.m.Y.",strtotime($lead['appointmentTime'])) . ' ' . (stripos($lead['appointmentTime'],'00:00:00') === false ? date("g:i A",strtotime($lead['appointmentTime'])) : '') . '
								  <br>';
					}
				$body .= '	<br>
							<br>Sollten Sie weitere Fragen haben, zögern Sie nicht uns zu kontaktieren unter: ' . $dealer_phone . ' .
							<br>Wir freuen uns auf Sie!
							<br>
							<br><b>' . $from_staff['name'] . '</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer_phone . '
							</div>';
			}


			else
			{
				$body = '<div style="font-family:arial;font-size:11pt;">
							Hi ' . trim($lead['firstName'] . ' ' . $lead['lastName']) . ',
							<br>
							<br>We look forward to seeing you at our '. ( $dealerObj->hasOem(OEM_BRP) ? '' : 'Private ' ) .'Sale Event at ' . $dealer['dealerName'] . '!
							<br>
							<br><b>Event Location</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer['address'] . ', ' . $dealer['city'] . '
							<br>
							<br><b>Event Date</b>
							<br>' . displayEventDate($event,false,false,true) . '
							<br>';
				if($lead['appointmentTime'] != '')
				{
					$body .= '<br><b>Your Appointment</b>
							  <br>' . date("M j, Y",strtotime($lead['appointmentTime'])) . ' ' . (stripos($lead['appointmentTime'],'00:00:00') === false ? date("G:i",strtotime($lead['appointmentTime'])) : '') . '
							  <br>';
				}
				$body .= '	<br>
							<br>Should you have any questions about this event, please contact us at ' . $dealer_phone . ' .
							<br>We look forward to seeing you at our event.
							<br>
							<br><b>' . $from_staff['name'] . '</b>
							<br>' . $dealer['dealerName'] . '
							<br>' . $dealer_phone . '
							</div>';
			}

			$from = trim($from_staff['email']);
			//$to = array('dave@absoluteresults.com');
			$to = array($lead['email']);
			//$cc = $trainer['email'];
			$bcc = array('dave@absoluteresults.com');

			if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,$attachments,Email::TYPE_APPT_REMINDER)) $emails_sent++;
			else $emails_failed++;
		}

		write_log($event['eventName'] . ' - ' . $emails_sent . ' Sent - ' . $emails_failed . ' Failed');

	}

?>