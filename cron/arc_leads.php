<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('defines.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}


	$sql = 'SELECT eventID,eventName FROM
			(
				SELECT 
					eventID,
					eventName,
					dealerID 
				FROM ps_events 
				WHERE 
					confirmed="confirmed" and 
					trainerID IS NOT NULL AND
					eventStart = "' . date("Y-m-d") . '"
			) as b1
			INNER JOIN
			(
				SELECT * FROM ps_tasks_arc
			) as b2
			USING
			(eventID)';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {
		$event = Event::ById($re['eventID']);
		$dealer = Dealer::ById($event->dealerID);
		$trainer = Staff::ById($event->trainerID);

		$sql = 'SELECT *
				FROM ' . $event->apptTbl . '
				WHERE
					eventID=' . $event->id . ' AND
					source like "%arc%" AND
					arcProspect in ("appointment","hot","warm","hand_raiser")
				ORDER BY lastname';

		$apptsResults = $db->rawQuery($sql);
		$leads_count = count($apptsResults);
		//$leads_count = file_get_contents('https://ar.absoluteresults.com/export/list/arc_leads.php?eid=' . $re['eventID'] . '&ekey=' . encrypt($re['eventID']) . '&get_count');
		if($leads_count >= 1) {
			$adf = AdfEmail::where('dealerID',$dealer->id)->getOne();
			if($adf instanceof AdfEmail) {
				$apptLogger = new ApptLogger($event->id);
				foreach($apptsResults as $cus) {
					$rsvpInfo = $apptLogger->parseInfo($cus);
					$apptLogger->adfEmail($rsvpInfo);
				}
			}

			$staff_results = DealerStaff::where('dealerID', $dealer->id)->where('status',1)->get();
			$webLeadContacts = array();
			$primaryContact = array();

			if(count($staff_results) > 0){
				foreach($staff_results as $ds){
					if($ds->webContact == 1 && $ds->email != "") $webLeadContacts[] = trim($ds->email);
					if($ds->primaryContact == 1 && $ds->email != "") $primaryContact[] = trim($ds->email);
				}
			}



			if(count($webLeadContacts) > 0) $to = $webLeadContacts;
			else if(count($primaryContact) > 0) $to = $primaryContact;
			else $to = $trainer->email;

			$subject = 'ARC Leads - ' . $event->eventName;

			if($dealer->language->major == 'fr')
			{
				$body = '<div style="font-family:arial">
							Allo ' . $dealer->dealerName . ',
							<br>
							<br>Vous trouverez ci-joint vos clients <prospects> pour votre prochain événement provenant du Centre de Communication d’Absolute Results. Si d’autres clients <prospects> s’ajoutent, ils vous seront acheminés dès leur arrivée.
							<br>La majorité des appels seront effectués 1-3 jours avant votre événement.
							<br>Votre formateur désigné reçoit aussi les clients <prospects> et a déjà commencé le suivi de ces clients. Veuillez SVP discuter avec votre formateur avant de prendre des mesures concernant ces clients <prospects>.
							<br>
							<br>Que votre événement soit un succès !

							</div>';
			}
			else
			{
				$body = '<div style="font-family:arial">
							Hello ' . $dealer->dealerName . ',
							<br><br>
							<br>Attached are your <b>Absolute Results Call Centre leads</b> to date for your upcoming event. If any more leads are forth coming they will be forwarded to you as they come in. The majority of calls are made 1-2 days before your event.
							<br><br>Your assigned trainer will also be getting the leads and following up, either to firm up an appointment time or confirm the time the appointment has been set for.
							<br><br>Have a great event!
							</div>';
			}
			$from = $trainer->email;
			//$to = array('dave@absoluteresults.com');
			$cc = array();
			if($trainer->email != '')
				$cc = $trainer->email;
			$bcc = [];
			$attachment = array();
			$attachment['path'] = AR_SECURE_URL . 'export/list/arc_leads.php?eid=' . $event->eventID . '&ekey=' . encrypt($event->eventID);
			$attachment['name'] = 'ARC Leads - ' . $event->eventName . '.xls';
			$attachment['type'] = 'application/vnd.ms-excel';

			$sent_success = EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,array($attachment),Email::TYPE_ARC_LEADS);
			write_log($event->eventName . ' - Sent');





		}
		else
		{
			write_log($re['eventName'] . ' - Not Sent (' . $leads_count . ' Leads Only)');
		}
	}
	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>