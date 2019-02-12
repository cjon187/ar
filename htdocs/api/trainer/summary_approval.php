<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('trainerTeamUtils.php');
	$db = new ARDB();

	if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['eid'],$_GET['ekey']))
		{
			echo 'Invalid Key';
			exit;
		}
	}

	if(isset($_POST['disapprove']))
	{
		$event = displayEventInfo($_GET['eid']);
		$dealer = displayDealerInfo($event['dealerID']);
		$trainer = displayStaffInfo($event['trainerID']);
		$leader = displayStaffInfo(getLeader($trainer['staffID']));

		$subject = trim('Event Summary Disapproved - ' . $dealer['dealerName'] . ' - ' . displayEventDate($event));

		$body = '<font style="font-size:10pt;font-family:Arial">
				 	Your trainer summary has been disapproved.
				 	<br><br>
				 	<b>Reason</b>
				 	<br>
				 	' . $_POST['disapprove'] . '
				 </font>
				';

		$to = array($trainer['email']);
		$bcc = array('dave@absoluteresults.com');
		if($leader['email'] != '') $from = $leader['email'];
		else $from = 'al@absoluteresults.com';
		$cc = $from;



		mysqli_query($db_data,'UPDATE ps_events SET trainerSummaryApproved = null,trainerSummarySent = null WHERE eventID = ' . $_GET['eid']);

		if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,[],Email::TYPE_EVENT_SUMMARY_DISAPPROVAL))
		{
			?>
			alert('Disapproval Email Sent.');
			location.reload();
			<?php
		}
		else
		{
			?>
			alert('Error.');
			location.reload();
			<?php
		}
		exit;
	}

	if($_GET['action'] == 'a')
	{
		$approved = true;
		$sql = 'UPDATE ps_events SET trainerSummaryApproved="' . date("Y-m-d H:i:s") . '" WHERE eventID= ' . $_GET['eid'];
		if(mysqli_query($db_data,$sql))
		{
			$event = Event::byId($_GET['eid']);
			if($event->salesTypeID == 5 && $event->eventEnd >= date("Y-m-d",strtotime('now - 7 days'))) {
				$psc = new PrivateSaleController($event);
				$psc->sendSummaryEmail();
			}

			include_once('surveyUtils.php');
			if(!checkForSurvey($_GET['eid'])) emailSurveyFromTrainerSummary($_GET['eid']);
			$msg = 'Summary Approved.<br>Thank You';
		}
		else $msg = 'Error Occurred.';
	}
	else $approved = false;
?>
<!doctype html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title>Summary Approval</title>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="../../gumby/js/libs/modernizr-2.6.2.min.js"></script>
	<script gumby-touch="../../gumby/js/libs" src="../../gumby/js/libs/gumby.min.js"></script>
	<link rel="stylesheet" href="../../gumby/css/gumby.css">
	<link rel="stylesheet" href="../../gumby/css/gumby_helper.css">

	<link href="//vjs.zencdn.net/4.4/video-js.css" rel="stylesheet">
	<script src="//vjs.zencdn.net/4.4/video.js"></script>

	<script>
		function sendDisapproval()
		{
			$('#btn').prop('class','medium info btn pretty');
			$('#btn').attr('disabled','disabled');
			$('#btn > input').val('Sending...');

			$.ajax({data:	{disapprove: $('#reason').val()},
					type:	'POST',
					dataType: 'script'
	   	    });
			return false;

		}
	</script>

	<style>
		.row {max-width:900px;}
	</style>
</head>
<body>
	<div class="row" style="padding-top:20px">
<?php if($approved) { ?>
		<h4><?= $msg ?></h4>
<?php } else { ?>
		<h4>Disapproval Reason</h4>
		<div class="field"><textarea class="input textarea" type="text" id="reason" name="reason" style="height:100px"></textarea></div>
		<div style="float:left;padding-right:5px"><div class="medium primary btn pretty" id="btn"><input type="submit" value="Send" onClick="sendDisapproval()" /></div></div>
<?php } ?>
	</div>
</body>
</html>