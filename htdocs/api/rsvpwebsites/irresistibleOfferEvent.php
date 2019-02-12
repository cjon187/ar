<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('programs/irresistiblePackage.php');

	$json = array();


	if($_GET['service'] == 'get_dealer')
	{
		$sql = 'SELECT dealerID,dealerName FROM ps_dealers WHERE promoCode = "' . $_GET['code'] .'"';

		$results = mysqli_query($db_data,$sql);
		if(mysqli_num_rows($results) == 1) $json = mysqli_fetch_assoc($results);
	}
	else if($_GET['service'] == 'get_offers')
	{
		$json = getIrresistibleOffers($_GET['dealerID'],$_GET['lang']);
	}
	else if($_GET['service'] == 'get_cash_back_amount')
	{
		$json = getCashBackAmount($_GET['dealerID']);
	}
	else if($_GET['service'] == 'get_videos')
	{
		if($_GET['region'] == 'us') $json = $offerUSAVideos;
		else if($_GET['lang'] == 'fr') $json = $offerFrVideos;
		else $json = $offerVideos;
	}
	else if($_GET['service'] == 'log_teaser')
	{
		connectMySQL('log');
		$sql = 'INSERT INTO ps_irresistiblepackage_teaser (code,dealerID,logDate,IP) values ("' . $_GET['code'] . '","' . $_GET['dealerID'] . '","' . date("Y-m-d H:i:s") . '","' . $_GET['ip'] . '")';
		if(mysqli_query($db_log,$sql)) $json[] = 'Success';
		else $json[] = 'Error';
	}
	else if($_GET['service'] == 'validate_teaser_code')
	{
		$dealerCode = substr($_GET['code'],0,3);
		$cusCode = substr($_GET['code'],3);

		$sql = 'SELECT * FROM ps_dealers WHERE promoCode != "" AND promoCode = "' . $dealerCode . '"';
		$dealer = mysqli_fetch_assoc(mysqli_query($db_data,$sql));

		$sql = 'SELECT * FROM ps_dealerstaff WHERE status = 1 AND dealerID=' . $dealer['dealerID'] . ' AND dealerStaffID = ' . $cusCode;
		$staff = mysqli_fetch_assoc(mysqli_query($db_data,$sql));

		if($_GET['code'] == '123-456-789' || $_GET['code'] == '123456789' || $staff['dealerStaffID'] != '') $json['status'] = 'Success';
		else $json['status'] = 'Error';
	}
	else if($_GET['service'] == 'check_cus_code')
	{
		$sql = 'SELECT contactID,firstname,lastname,mainPhone,mobilePhone,email,city,province,postalCode,year,description,make,model
				FROM ps_dealer_485_contacts WHERE customerCode = "' . $_GET['code'] . '"';
		$cus = mysqli_fetch_assoc(mysqli_query($db_data,$sql));

		$results = mysqli_query($db_data,$sql);
		if(mysqli_num_rows($results) == 1) $json = mysqli_fetch_assoc($results);
		else $json['status'] = 'Error';
	}
	else if($_POST['service'] == 'register')
	{

		$info = $_POST;
		foreach($info['tradeIn'] as $key => $val) if($val == 'undefined') $info['tradeIn'][$key] = '';

		$websiteInfo = $info;
		$event = displayEventInfo($websiteInfo['eventID']);
		$dealer = displayDealerInfo($event['dealerID']);

		$webContactArray = array();
		$primaryContactArray = array();
		$leadFromContact = "";
		$sql = 'SELECT * FROM
				(SELECT dealerID FROM ps_events WHERE eventID=' . $websiteInfo['eventID'] . ') as a1
				INNER JOIN
				(SELECT * FROM ps_dealerstaff WHERE status = 1 AND (webContact = 1 OR primaryContact = 1 OR leadFromContact = 1) AND email is not null AND email != "") as a2
				USING
				(dealerID)';
		$webLeadResults = mysqli_query($db_data,$sql);
		while($webLead = mysqli_fetch_assoc($webLeadResults))
		{
			if($webLead['webContact'] == 1) $webContactArray[] = trim($webLead['email']);
			if($webLead['primaryContact'] == 1) $primaryContactArray[] = trim($webLead['email']);
			if($webLead['leadFromContact'] == 1) $leadFromContact = trim($webLead['email']);
		}
		if(count($webContactArray) > 0) $websiteInfo['emails'] = $webContactArray;
		else if(count($primaryContactArray) > 0) $websiteInfo['emails'] = $primaryContactArray;
		else $websiteInfo['emails'] = array('web@absoluteresults.com');

		/////////////////////////////////////
		//ROBOT CHECK
		/////////////////////////////////////
		$isRobot = false;
		$uc = preg_match_all("/[A-Z]/",$info['customer']['lastname']);
		$lc = preg_match_all("/[a-z]/",$info['customer']['lastname']);
		if($uc >= 4 && $lc >= 4) $isRobot = true;
		if(strlen($info['tradeIn']['kilometers']) >= 5 && strlen(preg_replace("/[^0-9]/", "",$info['tradeIn']['kilometers'])) <= 1)  $isRobot = true;
		//if(stripos($info['tradeIn']['kilometers'],' ') === false && strlen($info['tradeIn']['kilometers']) >= 8) $isRobot = true;
		if(stripos($info['tradeIn']['model'],' ') === false && strlen($info['tradeIn']['model']) > 10) $isRobot = true;
		/////////////////////////////////////
		/////////////////////////////////////

		if(!$isRobot)
		{
			$_GET = array();
			$_GET['promo'] = $websiteInfo['campaign'];
			$_GET['source'] = 'web';
			$_GET['eventID'] = $websiteInfo['eventID'];

			$_GET['lastName'] = $info['customer']['lastname'];
			$_GET['firstName'] = $info['customer']['firstname'];
			$_GET['mainPhone'] = $info['customer']['mainPhone'];
			$_GET['email'] = $info['customer']['email'];
			$_GET['postalCode'] = $info['customer']['postalCode'];
			$_GET['currentVehicleModel'] = $info['tradeIn']['model'];

			if($info['customer']['appointment'] != "") $_GET['notes'] .= "Preferred Time: " . $info['customer']['appointment'] . "\n";

			if($info['tradeIn']['models'] != "")
			{
				$_GET['currentVehicleYear'] = $info['tradeIn']['years'];
				$_GET['currentVehicleModel'] = $info['tradeIn']['makes'] . ' ' . $info['tradeIn']['models'];
				$_GET['currentVehicleKM'] = $info['tradeIn']['kilometers'];

				$_GET['notes'] .= 'Vehicle Appraised: ' . $info['tradeIn']['years'] . ' ' . $info['tradeIn']['makes'] . ' ' . $info['tradeIn']['models'] . ' ' . $info['tradeIn']['trims'] . ' ' . $info['tradeIn']['styles'] . ' ' . $info['tradeIn']['kilometers'] . "KM/Miles\n";
				if($info['tradeIn']['values']['tradeInLow'] != "" && $info['tradeIn']['values']['tradeInLow'] != 0) $_GET['notes'] .= 'Trade in Value: '. '$' . number_format($info['tradeIn']['values']['tradeInLow']) . ' - $' . number_format($info['tradeIn']['values']['tradeInHigh']) . "\n";
				else $_GET['notes'] .= "Trade in Value: N/A\n";
			}
			else
			{
				$_GET['currentVehicleYear'] = $info['customer']['year'];
				$_GET['currentVehicleModel'] = $info['customer']['model'];
			}

			if($info['nextVehicleModel'] != "") $_GET['notes'] .= 'Interested In: ' . $info['nextVehicleModel'] . "\n";
			if($info['customer']['nextVehicleAccessories'] != "") $_GET['notes'] .= 'Selected Accessories: ' . $info['customer']['nextVehicleAccessories'] . "\n";


			if($info['tradeIn']['payments'] != "") $_GET['currentPayment'] = $info['tradeIn']['payments'];


			if($info['customer']['sp_newUsed'] != "" || $info['customer']['sp_vehicleType'] != "" || $info['customer']['sp_employment'] != "" || $info['customer']['sp_monthlyIncome'] != "" || $info['customer']['sp_address'] != "" || $info['customer']['sp_city'] != "") $_GET['notes'] .= "Potential Non-Prime Customer\n";
			if($info['customer']['sp_newUsed'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -Interested in New/Used: " . $info['customer']['sp_newUsed'] . "\n";
			if($info['customer']['sp_vehicleType'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -Interested In Vehicle Type: " . $info['customer']['sp_vehicleType'] . "\n";
			if($info['customer']['sp_employment'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -Employed for last 24 Months: " . $info['customer']['sp_employment'] . "\n";
			if($info['customer']['sp_monthlyIncome'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -Monthly Income: $" . $info['customer']['sp_monthlyIncome'] . "\n";
			if($info['customer']['sp_address'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -Address: " . $info['customer']['sp_address'] . "\n";
			if($info['customer']['sp_city'] != "") $_GET['notes'] .= "&nbsp;&nbsp;&nbsp; -City: " . $info['customer']['sp_city'] . "\n";

			if($info['tradeIn']['drivetrain'] != "") $_GET['notes'] .= "Drive Train: " . $info['tradeIn']['drivetrain'] . "\n";
			if($info['tradeIn']['transmission'] != "") $_GET['notes'] .= "Transmission: " . $info['tradeIn']['transmission'] . "\n";
			if($info['tradeIn']['cylinder'] != "") $_GET['notes'] .= "Cylinder: " . $info['tradeIn']['cylinder'] . "\n";
			if($info['tradeIn']['balanceOwing'] != "") $_GET['notes'] .= "Balance Owing: " . $info['tradeIn']['balanceOwing'] . "\n";

			if($info['customer']['salesperson'] != "") $_GET['notes'] .= "Salesrep Selected: ".$info['customer']['salesperson'] . "\n";
			if($info['customer']['gift'] != "") $_GET['notes'] .= "Gift Selected: ".$info['customer']['gift'] . "\n";
			if($info['customer']['apptDate'] != "") $_GET['notes'] .= "Web Appointment Selected: " . $info['customer']['apptDate'] . ' ' . $info['customer']['apptTime'] . "\n";
			if($info['customer']['dCode'] != "") $_GET['notes'] .= "Code Entered: " . $info['customer']['dCode'] . "\n";
			if($info['customer']['dr_availablePurchase'] != "") $_GET['notes'] .= "Dealer Rewards: $" . number_format($info['customer']['dr_availablePurchase']) . "\n";

			if($info['customer']['exteriorColour'] != "") $_GET['notes'] .= "Exterior Colour: ".$info['customer']['exteriorColour'] . "\n";
			if($info['customer']['interiorColour'] != "") $_GET['notes'] .= "Interior Colour: ".$info['customer']['interiorColour'] . "\n";
			if($info['customer']['originalColour'] != "") $_GET['notes'] .= "Original Colour: ".$info['customer']['originalColour'] . "\n";
			if($info['customer']['registrationNumber'] != "") $_GET['notes'] .= "Registration #: ".$info['customer']['registrationNumber'] . "\n";
			if($info['customer']['transmission'] != "") $_GET['notes'] .= "Transmission: ".$info['customer']['transmission'] . "\n";
			if($info['customer']['configuration'] != "") $_GET['notes'] .= "Configuration: ".$info['customer']['configuration'] . "\n";
			if($info['customer']['bodyPaintCondition'] != "") $_GET['notes'] .= "Body Paint Condition: ".$info['customer']['bodyPaintCondition'] . "\n";
			if($info['customer']['interiorCondition'] != "") $_GET['notes'] .= "Interior Condition: ".$info['customer']['interiorCondition'] . "\n";
			if($info['customer']['transmissionCondition'] != "") $_GET['notes'] .= "Transmission Condition: ".$info['customer']['transmissionCondition'] . "\n";
			if($info['customer']['wheelTireCondition'] != "") $_GET['notes'] .= "Wheel Tire Condition: ".$info['customer']['wheelTireCondition'] . "\n";
			if($info['customer']['repairHistory'] != "") $_GET['notes'] .= "Repair History: ".$info['customer']['repairHistory'] . "\n";
			if($info['customer']['declarations'] != "") $_GET['notes'] .= "Declarations: ".$info['customer']['declarations'] . "\n";
			if($info['customer']['additionalInformation'] != "") $_GET['notes'] .= "Additional Information: ".$info['customer']['additionalInformation'] . "\n";

			if($info['selectedOffers'] != "")
			{
				$arr = explode(',',$info['selectedOffers']);
				foreach($arr as $o)
				{
					if($defaultOffers[$o] != '') $_GET['notes'] .= 'Selected Offer: ' . $defaultOffers[$o] . "\n";
					else if($optionalOffers[$o] != '')  $_GET['notes'] .= 'Selected Offer: ' . $optionalOffers[$o] . "\n";
				}
			}

			if($info['notes'] != "") $_GET['notes'] .= $info['notes'] . "\n";

			$_GET['webSource'] = $info['dealer'];
			if($info['customer']['dCode'] != "") $_GET['webCode'] = $info['customer']['dCode'];


			if($websiteInfo['campaign'] != "") $_GET['campaign'] = $websiteInfo['campaignDescription'];

			if($websiteInfo['url'] != "") $_GET['url'] = $websiteInfo['url'];
			if($websiteInfo['url'] != "") $_GET['webSource'] = $websiteInfo['url'];

			if($websiteInfo['code'] != "") $_GET['webCode'] = $websiteInfo['code'];

			if($websiteInfo['leadsToARC'] == 'yes') $_GET['leadsToARC'] = 'yes';

			if($info['customer']['optIn'] != '') $_GET['optIn'] = 'y';
			if($info['customer']['sp_optin'] != '') $_GET['sp_optin'] = 'y';

			$_GET['create'] = 'on';
			$_GET['arcUpdate'] = 'no';
			$_GET['origfirstname'] = $_GET['firstName'];
			$_GET['origlastname'] = $_GET['lastName'];

			if(isset($_POST['secondEmail'])) $_GET['secondEmail'] = $_POST['secondEmail'];
			else unset($_GET['secondEmail']);

			ob_start();
			include_once('../rsvp/index.php');
			//include_once('../customerUpdate/index.php');
			$re = ob_get_clean();

	    }

	   	include_once('displayUtils.php');
		include_once('Emailer/swift_required.php');

		//////////////////////////////////////////////
		//LANGUAGE //////////////////////////////////
		//////////////////////////////////////////////
		$iplang['irresistible_email_logo'] = 'irresistible_email_logo_fr';
		$iplang['The Irresistible Offer'] = 'Forfait Irrésistible';
		$iplang['Congratulations.'] = 'Félicitations.';
		$iplang['Thank you for claiming your Irresistible Offer.'] = 'Merci d’avoir réclamé votre Forfait Irrésistible.';
		$iplang['Thank you for registering for your Irresistible Offer.'] = "Merci de votre inscription pour votre Forfait Irrésistible.";
		$iplang['You have successfully claimed the following offers'] = 'Vous avez réclamé avec succès les offres suivantes';
		$iplang['Please redeem these special offers at the dealership'] = 'S.v.p. valider ces offres spéciales en magasin';

		if($info['lang'] == 'fr')
		{
			include_once('fr.php');
			foreach($iplang as $v => $t) $lang[$v] = $t;
		}
		else
		{
			include_once('en.php');
			foreach($iplang as $v => $t) $lang[$v] = $v;
		}

		try
		{
			$transport = Swift_SmtpTransport::newInstance('absoluteresults.smtp.com',2525)->setUsername('smtp@absoluteresults.com')->setPassword('@B5oLut3');
			$mailer = Swift_Mailer::newInstance($transport);

			/////////////////////////////////////
			//ROBOT CHECK EMAIL
			/////////////////////////////////////
			if($isRobot)
			{
				$message = Swift_Message::newInstance('Robot Detected - ' . $websiteInfo['name']);
				$body = implode(' <br> ',$info['customer']) . '<br>';
				$body .= implode(' <br> ',$info['tradeIn']);

				$message->setBody($body, 'text/html');
				$message->setTo(array('web@absoluteresults.com'));
				$message->setReturnPath('web@absoluteresults.com');
				$message->setFrom('web@absoluteresults.com');

				$mailer->send($message);
				exit;
			}
			/////////////////////////////////////
			/////////////////////////////////////


			$message = Swift_Message::newInstance($lang['The Irresistible Offer']);

			$body = '<html style="margin:0px;padding:0px;">
						<body style="background-color:#ebebeb;margin:0px;padding:10px;">
						<center>
						<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td style="width:600px;font-size:12pt;color:#333;font-family:arial;padding:10px 20px;background-color:white">';
			$body .= '<img src="' .$message->embed(Swift_Image::fromPath('../../rsvpwebsites/images/campaigns/IrresistibleOfferEvent/' . $lang['irresistible_email_logo'] . '.png')) . '" /><br><br><br>';
			$body .= '<font style="font-size:14pt;font-weight:bold">' . $lang['Congratulations.'] . '</font><br>';

			$offers = getIrresistibleOffers($dealer['dealerID'],$websiteInfo['lang']);

			if(!isset($_POST['secondEmail'])){
				$body .= '' . $lang['Thank you for registering for your Irresistible Offer.'] . '<br><br>';
				if(isset($offers['cbb']))
				{
					if($info['tradeIn']['values']['tradeInLow'] != "" && $info['tradeIn']['values']['tradeInLow'] != 0)
					{
						// Black Book Value
						$body .= '<b>
								 	' . $lang['appraisal1'] . '
								 	<br>
								 	<font style="font-size:13pt">
								 	' . $lang['appraisal2'] . ucwords($info['tradeIn']['years'] . ' ' . $info['tradeIn']['makes'] . ' ' . $info['tradeIn']['models']) . '  ' . $lang['appraisal3'] . '<u>$' . number_format($info['tradeIn']['values']['tradeInLow']) . ' - $' . number_format($info['tradeIn']['values']['tradeInHigh']) . '</u>

								 	</font>
								 	</b>
								 	<br>
								 	<div style="padding-top:10px;font-size:9pt;font-style:italics">
								 	' . $lang['appraisalDisclaimer'] . '
								 	</div>

								 	<br><br>';
					}
					else if($info['tradeIn']['years'] != "")
					{
						// Black Book Value
						$body .= '<b>
								 	<font>
								 	' . $lang['appraisalFail'] . '
								 	</font>

								 	</b><br><br>';
					}
				}

				$body .='				<font style="font-size:10pt">
							 			<b><u>'.$lang['info'].'</u></b><br>
							 			<b>'.$lang['firstname'].':</b> ' . $info['customer']['firstname']  . '<br>
							 			<b>'.$lang['lastname'].':</b> ' . $info['customer']['lastname']  . '<br>
							 			<b>'.$lang['email'].':</b> ' . $info['customer']['email'] . '<br>
							 			<b>'.$lang['phone'].':</b> ' . $info['customer']['mainPhone'] . '<br>';


				 if($websiteInfo['region'] == "US" || $websiteInfo['region'] == "us") {
								$body .= '<b>'.$lang['postalcode_US'].':</b> ' . $info['customer']['postalCode'] . '<br>';
				 } else if ($websiteInfo['eventID'] == 22721) {

				 } else if ($websiteInfo['region'] == "UK" || $websiteInfo['region'] == "uk") {
								$body .= '<b>'.$lang['postalcode_UK'].':</b> ' . $info['customer']['postalCode'] . '<br>'	;
				 } else {
								$body .= '<b>'.$lang['postalcode_CA'].':</b> ' . $info['customer']['postalCode'] . '<br>';
				 } //end IF region

				if($websiteInfo['blackbook'] != "no" && $websiteInfo['blackbook'] != "NO" && $websiteInfo['blackbook'] != "none" && $websiteInfo['blackbook'] != "NONE" && $websiteInfo['blackbook'] != "" && $info['tradeIn']['years'] != ""){
						 		$body .=	'<br><b>'.$lang['year'].':</b> ' . $info['tradeIn']['years'] . '<br>
								 			<b>'.$lang['make'].':</b> ' . $info['tradeIn']['makes'] . '<br>
								 			<b>'.$lang['model'].':</b> ' . $info['tradeIn']['models'] . '<br>
								 			<b>'.$lang['trim'].':</b> ' . $info['tradeIn']['trims'] . '<br>
								 			<b>'.$lang['style'].':</b> ' . $info['tradeIn']['styles'] . '<br>
								 			<b>'.$lang['km'].':</b> ' . $info['tradeIn']['kilometers'] . '<br>' ;
				} //end IF blackbook
				else
				{
					if($info['customer']['year'] != "") $body .=	'<br><b>' . $lang['year'] . ':</b> ' . $info['customer']['year'] . '<br>' ;
					if($info['customer']['model'] != "") $body .=	'<b>' . $lang['vehicle'] . ':</b> ' . $info['customer']['model'] . '<br>' ;
				}
				if($info['customer']['nextVehicleModel'] != "") $body .=	'<b>Interested In:</b> ' . $info['customer']['nextVehicleModel'] . '<br>' ;
				if($info['customer']['nextVehicleAccessories'] != "") $body .=	'<b>Selected Accessories:</b> ' . $info['customer']['nextVehicleAccessories'] . '<br>' ;

				if($info['customer']['appointment'] != "") $body .=	'<b>' . $lang['apptTime'] . ':</b> ' . $info['customer']['appointment'] . '<br>' ;
				$body .="<br>";
			}
			//SECOND EMAIL IS SET
			//SEND THE SELECTED OFFERS
			else{
				if($info['selectedOffers'] == ""){
					exit;
				}
				$body .= '' . $lang['Thank you for claiming your Irresistible Offer.'] . '<br><br>';
				$body .= '<b>' . $lang['You have successfully claimed the following offers'] . '</b><br><i>';


				$selectedOffers = explode(',',$info['selectedOffers']);
				if($info['selectedOffers'] != '') foreach($selectedOffers as $o) $body .= $offers[$o] . '<br>';
				else foreach($offers as $o) $body .= $o . '<br>';

				$body .= '</i><br>';

				if($info['lang'] == 'fr') $eventDate =  $websiteInfo['eventDate-fr'];
				else $eventDate =  $websiteInfo['eventDate'];

				$body .= '<font style="color:red;font-size:11pt;font-weight:bold">' . $lang['Please redeem these special offers at the dealership'] . ' ' . $eventDate . '</font><br><br>';
			}

			$body .= '
						 			<b>' . $websiteInfo['name'] . '</b><br>

						 			' . $websiteInfo['address'] . '<br>

						 			' . $websiteInfo['phone'] . '
									 <br><br>';


			$body .= '			</td>
								</tr>
							</table>
							</center>
						</body>
					</html>';

			$message->setBody($body, 'text/html');
			$message->setTo(array(trim($info['customer']['email'])));

			//see dealerEvent.php

			$message->setBcc(array('devteam@absoluteresults.com'));
			$message->setReturnPath('dave@absoluteresults.com');

			if($dealer['dealerID'] == 153) $message->setFrom('salesoffice@eastsidedodge.com');
			else if($leadFromContact != "") $message->setFrom($leadFromContact);
			else $message->setFrom($websiteInfo['emails'][0]);

			if($mailer->send($message)) $json[] = 'Success';
			else $json[] = 'Error';

		}
		catch (Swift_TransportException $e)
		{
				$json[] = 'Error';
		}
		catch (Swift_Message_MimeException $e)
		{
				$json[] = 'Error';
		}
		catch (Swift_RfcComplianceException $e)
		{
				$json[] = 'Error';
		}
	}
	else if($_POST['service'] == 'submit_offers')
	{
		$info = $_POST;
		if($info['eventID'] != '')
		{
			$event = displayEventInfo($info['eventID']);

			$notes = '';
			$selectedOffers = array();
			if($info['selectedOffers'] != "")
			{
				$arr = explode(',',$info['selectedOffers']);
				foreach($arr as $o)
				{
					if($defaultOffers[$o] != '') $selectedOffers[] = $defaultOffers[$o];
					else if($optionalOffers[$o] != '')  $selectedOffers[] = $optionalOffers[$o];
				}
			}
			$sqlArray = array();
			if(count($selectedOffers) > 0) $sqlArray[] = 'notes = concat("Selected Offers: ' . implode(' , ',$selectedOffers) . "\n" . '",ifnull("",notes))';
			if($info['nextVehicleModel'] != "")  $sqlArray[] = 'nextVehicleModel = "' . $info['nextVehicleModel'] . '"';
			//IF WE WANT TO UPDATE THE ADDRESS AND CITY PART OF THE RECORD IN PS_APPOINTMENTS
				//if($info['customer']['sp_address'] != "") $sqlArray[] .= 'address: ' . $info['customer']['sp_address'] . '"';
				//if($info['customer']['sp_city'] != "") $sqlArray[] .= 'city: ' . $info['customer']['sp_city'] . '"';

			$sql = 'UPDATE ' . $event['apptTbl'] . '
					SET ' . implode(',',$sqlArray) . '
					WHERE eventID = ' . $event['eventID'] . ' AND firstname = "' . $info['customer']['firstname'] . '" AND lastname = "' . $info['customer']['lastname'] . '" AND email = "' . $info['customer']['email'] . '"';

			$json[] = $sql;
			if(mysqli_query($db_data,$sql)) $json[] = 'Success';
			else $json[] = 'Error';
		}
	}

	//echo json_encode(array_map(utf8_encode,$json));
	echo json_encode($json);
?>