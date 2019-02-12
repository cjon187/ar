<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	//include_once('Emailer/swift_required.php');

   	/**************************************************************************************
	NOTIFY ACCOUNTING TO AUTO-CONFIRM PER CAR AMOUNT FOR TRAINER EMPLOYEES
   	***************************************************************************************/
	$sql = 'SELECT e.eventID,e.perCarSold
			FROM ps_events e
			INNER JOIN ps_dealers d ON (e.dealerID = d.dealerID)
			INNER JOIN ps_staff s ON (e.trainerID = s.staffID)
			LEFT JOIN absoluteresults_acct.ps_contracts c ON (e.eventID = c.eventID AND c.isVoid = "false")
			WHERE 	e.salesTypeID=5 AND
					e.confirmed="confirmed" AND
		            e.perCarSold IS NOT NULL AND
		            e.perCarConfirmed IS NULL AND
		            DATE(e.perCarLogged) = "' . date("Y-m-d",strtotime("now - 14 days")) . '" AND
		            c.contractID IS NULL';

	$results = $db->rawQuery($sql);
	if(!empty($results)) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);

			if(!empty($event->id) && is_numeric($re['perCarSold'])) {
				$event->confirmedPerCarSold = $re['perCarSold'];
				$event->perCarConfirmed = date("Y-m-d H:i:s");
				$event->perCarAutoConfirmed = 1;
				$event->save();
			}

			$subject = 'Auto Per Car Confirmation - ' . $event->dealer->dealerName . ' ' . $event->eventStart;
			$body = '
					 <font style="font-size:10pt;font-family:Arial">
					 	Hi Accounting Team,
					 	<br><br>
					 	Our records show that the following "PER CAR" have not been confirmed after 14 days of submitting the "PER CAR" amount.
					 	<br>
					 	Please go ahead and <b><i><u>INVOICE</b></i></u> the dealer as per below.
					 	<br>
					 	<br>
					 	<b><font style="color:red">NOTE: This is a trainer employee - NO TRAINER INVOICE IS REQUIRED</font></b>
					 	<br>
					 	<br>
					 	Trainer: ' . $event->trainer->name . '<br>
					 	Dealership: ' . $event->dealer->dealerName . '<br>
					 	Event Date: ' . displayEventDate($event->toArray()) . '<br>
					 	Per Car: ' . $re['perCarSold'] . '<br>
						<br>
						<br>
						Thank you.
				 </font>';

			if($event->dealer->countryID == COUNTRY_CA) {
				$from = 'confirmation@absoluteresults.com';
			} else {
				$from = 'confirmation-intl@absoluteresults.com';
			}

			$to = array($from);
			$bcc = [];

			if(EmailController::sendEmail($subject,$body,$from,$to,$cc = array(),$bcc,[],Email::TYPE_PERCAR_AUTO_CONFIRMATION_EMPLOYEE))
				echo 'true';
			else
				echo 'false';
	    }
	}

    /**************************************************************************************
	NOTIFY ACCOUNTING TO AUTO-CONFIRM PER CAR AMOUNT FOR TRAINER CONTRACTORS
   	***************************************************************************************/


	$sql = "
		SELECT 
		e.eventName,
		i.invoiceID,
		i.staffID,
		i.invoiceNum,
		i.perCarConfirmed,
		e.eventID,
		e.perCarLogged,
		e.perCarSold,
		ii.quantity,
		e.perCarConfirmed,
		e.perCarAutoConfirmed,
		d.dealerName,
		e.eventStart,
		s.name,
		d.countryID
		FROM absoluteresults_acct.ps_invoices i
		INNER JOIN absoluteresults_acct.invoice_items ii ON (i.invoiceID = ii.invoiceID)
		INNER JOIN absoluteresults_acct.ps_contracts c ON (i.contractID = c.contractID)
		INNER JOIN absoluteresults_data.ps_events e ON (c.eventID = e.eventID)
		INNER JOIN absoluteresults_data.ps_dealers d ON (e.dealerID = d.dealerID)
		INNER JOIN absoluteresults_data.ps_staff s ON (i.staffID = s.staffID)
		WHERE 
		(i.hasPerCar =1 OR i.hasFlatPerCar=1 OR i.hasPerCarPayPlan=1) AND 
		i.invoiceType IS NULL AND 
		i.perCarConfirmed IS NULL AND
		e.perCarAutoConfirmed IS NULL AND
		i.isVoid != 'true' AND 
		i.date >= '" . date("Y-m-d",strtotime("now - 14 days")) . "' AND 
		i.date <= '" . date("Y-m-d 23:59:59",strtotime("now - 14 days")) . "'
	";

	$results = $db->rawQuery($sql);
	if(!empty($results)) {
		foreach($results as $re) {
			$event = Event::byId($re['eventID']);

			if(!empty($event->id) && is_numeric($re['quantity'])) {
				$event->confirmedPerCarSold = $re['quantity'];
				$event->perCarConfirmed = date("Y-m-d H:i:s");
				$event->perCarAutoConfirmed = 1;
				$event->save();
			}

			$subject = 'Auto Per Car Confirmation - ' . $re['dealerName'] . ' ' . $re['eventStart'];
			$body = '
					 <font style="font-size:10pt;font-family:Arial">
					 	Hi Accounting Team,
					 	<br><br>
					 	Our records show that the following "PER CAR" invoice have not been confirmed after 14 days of submitting the "PER CAR" amount.
					 	<br>
					 	Please go ahead and <b><i><u>CONFIRM</b></i></u> this invoice as per below.
					 	<br>
					 	<br>
					 	Trainer: ' . $re['name'] . '<br>
					 	Dealership: ' . $re['dealerName'] . '<br>
					 	Event Date: ' . $event->displayEventDate(true,true) . '<br>
					 	Per Car: ' . $re['quantity'] . '<br>
						<br>
						<br>
						Thank you.
				 </font>';

			if($re['countryID'] == COUNTRY_CA) {
				$from = 'confirmation@absoluteresults.com';
			} else {
				$from = 'confirmation-intl@absoluteresults.com';
			}

			$to = array($from);
			$bcc = [];

			if(EmailController::sendEmail($subject,$body,$from,$to,$cc = array(),$bcc,[],Email::TYPE_PERCAR_AUTO_CONFIRMATION))
				echo 'true';
			else
				echo 'false';
	    }
	}



   	/*********************************************
	REMIND TRAINERS TO FOLLOW UP WITH DEALER
   	*********************************************/
   	$day = date('N');
   	if($day == 2 || $day == 5) {
   		$staff = array();
	    $sql = 'SELECT * FROM ps_invoices i WHERE (i.hasPerCar =1 OR i.hasFlatPerCar=1 OR i.hasPerCarPayPlan=1) AND  isVoid != "true" AND (perCarConfirmed is null or perCarConfirmed = "") AND date >= "' . date("Y-m-d",strtotime("now - 1 month")) . '" AND date <= "' . date("Y-m-d 23:59:59",strtotime("now - 2 days")) . '"';



		$sql = "
			SELECT 
			e.eventName,
			i.invoiceID,
			i.staffID,
			i.invoiceNum,
			i.perCarConfirmed,
			e.eventID,
			e.perCarLogged,
			e.perCarSold,
			ii.quantity,
			e.perCarConfirmed,
			e.perCarAutoConfirmed,
			d.dealerName,
			e.eventStart,
			e.eventEnd,
			s.name,
			d.countryID,
			d.phone
			FROM absoluteresults_acct.ps_invoices i
			INNER JOIN absoluteresults_acct.invoice_items ii ON (i.invoiceID = ii.invoiceID)
			INNER JOIN absoluteresults_acct.ps_contracts c ON (i.contractID = c.contractID)
			INNER JOIN absoluteresults_data.ps_events e ON (c.eventID = e.eventID)
			INNER JOIN absoluteresults_data.ps_dealers d ON (e.dealerID = d.dealerID)
			INNER JOIN absoluteresults_data.ps_staff s ON (i.staffID = s.staffID)
			WHERE 
			(i.hasPerCar =1 OR i.hasFlatPerCar=1 OR i.hasPerCarPayPlan=1) AND 
			i.invoiceType IS NULL AND 
			i.perCarConfirmed IS NULL AND
			e.perCarAutoConfirmed IS NULL AND
			i.isVoid != 'true' AND 
			i.date >= '" . date("Y-m-d",strtotime("now - 1 month")) . "' AND 
			i.date <= '" . date("Y-m-d 23:59:59",strtotime("now - 2 days")) . "'
		";


		$results = $db->rawQuery($sql);
		if(!empty($results)) {
			foreach($results as $re) {
				$staff[$re['staffID']][] = $re;
			}
		}

		foreach($staff as $staffID => $invoices) {
			//$invoice = mysqli_fetch_assoc(mysqli_query($db_acct,'SELECT * FROM ps_invoices WHERE invoiceID=' . $re['invoiceID']));

			$staff = displayStaffInfo($staffID);
			if($staff['email'] == '')
				continue;

			$subject = 'Per Car Reminder';
			$body = '
					 <font style="font-size:10pt;font-family:Arial">
					 	Hi ' . $staff['name'] . ',
					 	<br>
					 	<br>Here is your list of dealers that have not confirmed the number of vehicles delivered for their sales event.
					 	<br>
						<br>Please follow up with each dealer to encourage responses to the per car confirmation request.
						<br>Your follow-up is necessary to facilitate billing to the dealer as well as timely payment for your per car commissions.
						<br>If confirmed within the last 24 hours and you do not see that on the portal, please allow another 24 hours for accounting to process.
						<br>Any questions regarding your per car submissions and confirmations should be directed to Diane in accounting.
						<br>
						<br>
						<table style="font-size:10pt;font-family:Arial" border=1 cellpadding=5>
						<tr><td>Dealership</td><td>Phone Number</td><td>Event Date</td><td>Per Car Submitted</td></tr>';

			foreach($invoices as $invoice)	{

				$body .= '<tr><td>' . $invoice['dealerName'] . '</td><td>' . $invoice['phone'] . '</td><td>' . displayEventDate($invoice,false,false,true) . '</td><td>' . $invoice['quantity'] . '</td></tr>';
			}
			$body .= '	</table>
						<br>
						<br>•	Please note that events not confirmed by the dealer are auto confirmed in accounting with your original per car submissions.
						<br>•	This could result in future clawbacks if the dealer disputes or short pays our billings.
						<br>•	Please ensure the accuracy of your per car submissions.

						Thank you.
				 </font>';

			$from = 'confirmation@absoluteresults.com';
			$to = array($staff['email']);
			$bcc = [];

			/*$to = array('dave@absoluteresults.com');
			$bcc = array('dave@absoluteresults.com');*/

			if(EmailController::sendEmail($subject,$body,$from,$to,$cc = array(),$bcc,[],Email::TYPE_PERCAR_REMINDER))
				echo 'true';
			else
				echo 'false';
		}
   	}

   	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>