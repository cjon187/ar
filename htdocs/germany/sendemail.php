<?php

	include_once('includes.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('defines.php');
	include_once('recaptchalib.php');




	/*if ($response != null && $response->success) {*/
		parse_str($_POST['data'], $params);
	$subject = $params['dealerName'] . ": Germany Dealer Booking Email";
	$body =  $params['dealerName'] . ' wants to book an event<br>
			 The contact Name is: '. $params['contactName'] .'<br>
			 The contact Phone is: '. $params['contactPhone'] .'<br>
			 The preferred month is: '. $params['eventMonth'] .'<br><br>
			New Cars Per Month: '. $params['newVehicles'] .'<br>
			Used Cars Per Month: '. $params['usedVehicles'] .'<br>
			Brands: '. $params['brands'] .'<br>
			Please follow up with this contact.';
	$to = array('keray@absoluteresults.com');
	$from = 'web@absoluteresults.com';
	$cc = array();
	$bcc = array('emeerstra@absoluteresults.com');

	sendEmail($subject,$body,$from,$to,$cc,$bcc);
		/*echo json_encode(array('captcha' => 'success'));*/

 	/*} else {
 		echo json_encode(array('captcha' => 'fail'));
 		exit;

	}*/


	exit;
?>