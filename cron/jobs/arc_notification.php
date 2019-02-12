<?php

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//SEND ARC THE LIST OF CALLS STARTING TODAY - ONLY IF THE CALLS HAVE A SPECIFIED CALL START DATE
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Sending ARC Reminders");
	write_log("*************************");

	$arcTasks = TaskArc::where('startDate',date("Y-m-d"))->get();
	if(!empty($arcTasks)) {

		$subject = 'ARC Calls Scheduled - ' . date("M j, Y");
		$body = '<div style="font-family:arial">
					ARC Calls for <b>' . date("M j, Y") . '</b> - only calls with a specified CALL START DATE will appear
					<br>
					<br>';

		foreach($arcTasks as $t) {
			$body .= $t->event->dealer->dealerName . ' Sale - ' . $t->event->displayEventDate() . '<br>';
		}

		$body .= '</div>';

		EmailController::sendEmail($subject,$body,'no-reply@absoluteresults.com',['arc@absoluteresults.com'],[],[],[],Email::TYPE_ARC_NOTIFICATION);


	}
	write_log("");
	write_log("*************************");
	write_log("Complete Sending ARC Reminders");
	write_log("*************************");

?>