<?php
	include_once('db/ARDB.php');
	include_once('emailUtils.php');
	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	include_once('Emailer/swift_required.php');


	$dateStart = date("Y-m-d",strtotime("now - " . ((date("N")-1)+8) . " days"));
	$dateEnd = date("Y-m-d",strtotime($dateStart . " + 7 days"));
	$subject = "Weekly Summary Report - " . date("M j, Y",strtotime($dateStart)) . date(" - M j, Y",strtotime($dateEnd));

	$body = '<font style="font-size:11pt;font-family:Arial">
			 	Weekly Summary Attached
			 </font>
			';

	$to = ['bcouto@absoluteresults.com',
		   'dbrigham@absoluteresults.com',
		   'jeff@absoluteresults.com',
		   'keray@absoluteresults.com',
		   'lorne@absoluteresults.com',
		   'lsmith@absoluteresults.com',
		   'pke@absoluteresults.com',
		   'rst-germain@absoluteresults.com',
		   'tholowchak@absoluteresults.com',
		   'al@absoluteresults.com'];
	$cc = [];
	$bcc = ['devteam@absoluteresults.com'];
	$from = 'devteam@absoluteresults.com';
	$attachments = array(array('path' => AR_SECURE_URL.'export/report/weekly.php','name' => "Weekly Summary Report - " . date("M j, Y",strtotime($dateStart)) . date(" - M j, Y",strtotime($dateEnd)) . '.pdf', "type" => 'application/pdf'));

	if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,$attachments,Email::TYPE_WEEKLY_SUMMARY) ) {
		echo 'Weeky Summary Sent';
	}
	else
	{
		echo 'Error in sending weekly summary';
	}
	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>