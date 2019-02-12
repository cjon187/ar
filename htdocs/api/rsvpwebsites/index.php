<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('rsvpWebsiteUtils.php');
	include_once('defines.php');
	$db = new ARDB();

	$json = array();

	if($_GET['service'] == 'get_website')
	{
		$json = getWebsiteInfo($_GET['url'],$_GET['code']);
	}
	else if($_GET['service'] == 'lookup_coords')
	{
		if(isset($_GET['lat']))
		{
			$json['lat'] = $_GET['lat'];
			$json['lng'] = $_GET['lng'];
		}
		else if(isset($_GET['add']))
		{
			?>
			<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?client=gme-absoluteresultsproductions&sensor=false"></script>
			<script>
				geocoder = new google.maps.Geocoder();
				geocoder.geocode({'address' : '<?= ajaxHTML($_GET['add']) ?>'}, function(results, status){

					if(status == google.maps.GeocoderStatus.OK) location.href='https://ar.absoluteresults.com/api/rsvpwebsites/?service=lookup_coords&lat=' + results[0].geometry.location.lat() + '&lng=' + results[0].geometry.location.lng();
					else location.href='https://ar.absoluteresults.com/api/rsvpwebsites/?service=lookup_coords&lat=&lng=';
				});
			</script>
			<?php
			exit;
		}
	}
	else if($_GET['service'] == 'get_customer')
	{
		$json = getCustomer($_GET['e'],$_GET['c']);
		/*if(strtoupper($_GET['c']) == 'AR123')
		{
			$json['contactID'] = '123';
			$json['firstname'] = 'John';
			$json['lastname'] = 'Smith';
			$json['mainPhone'] = '1.888.751.7171';
			$json['mobilePhone'] = '604-630-9740';
			//$json['email'] = 'dave@absoluteresults.com';
			$json['city'] = 'Surrey';
			$json['province'] = 'BC';
			$json['postalCode'] = 'V3Z3X1';
			$json['year'] = '2014';
			$json['description'] = 'Chrysler 200 LX';
			$json['make'] = 'Chrysler';
			$json['model'] = '200';
			$json['trim'] = 'LX';
		}
		else
		{
			$event = displayEventInfo($_GET['e']);

			$sql = 'SELECT contactID,firstname,lastname,mainPhone,mobilePhone,email,city,province,postalCode,year,description,make,model,`trim`,style
					FROM ps_dealer_' . $event['dealerID'] . '_contacts
					WHERE concat(LEFT(concat(ifnull(lastname,""),ifnull(companyName,"")),1),contactID) = "' . $_GET['c'] . '"';

			$results = mysqli_query($db_data,$sql);
			if(mysqli_num_rows($results) == 1) $json = mysqli_fetch_assoc($results);
		}*/
	}
	else if($_GET['service'] == 'web_hit')
	{
		if($_GET['masterTaskID'] != "")
		{

			$sql = 'SELECT * FROM rsvp_websites where masterTaskID="' . $_GET['masterTaskID'] . '"';
			$results = mysqli_query($db_data,$sql);

		}
		else if($_GET['url'] != "" && $_GET['eventID'] != "")
		{

			$sql = 'SELECT * FROM rsvp_websites where url = "' . $_GET['url'] . '" AND eventID="' . $_GET['eventID'] . '"';
			$results = mysqli_query($db_data,$sql);

		}

		if(mysqli_num_rows($results) >= 1)
		{
			$task = mysqli_fetch_assoc($results);
			$sql = 'UPDATE rsvp_websites SET hits = ' . ($task['hits']+1) . ' WHERE rsvpWebsiteID = ' . $task['rsvpWebsiteID'];
		}

		mysqli_query($db_data,$sql);
	}
	else if($_GET['service'] == 'register')
	{
		$info = $_POST;
		foreach($info as $key => $val) if($val == 'undefined') $info[$key] = '';

		if($info['masterTaskID'] != '') $websiteInfo = getWebsiteInfo('','',$info['masterTaskID']);
		else $websiteInfo = getWebsiteInfo($info['url'],$info['code']);

		if($info['eventID'] != '') $event = displayEventInfo($info['eventID']);
		else $event = displayEventInfo($websiteInfo['eventID']);


		if($event['dealerID'] != '') $dealer = displayDealerInfo($event['dealerID']);



		if($info['fullname'] != '')
		{
			if(stripos($info['fullname']," ") === false)
			{
				$info['firstname'] = $info['fullname'];
			}
			else
			{
				$info['lastname'] = substr($info['fullname'],strrpos($info['fullname']," ")+1);
				$info['firstname'] = substr($info['fullname'],0,strrpos($info['fullname']," "));
			}
		}

		/////////////////////////////////////
		//RSVP
		/////////////////////////////////////
	    $rsvp_data = array();
		$rsvp_data['promo'] = $websiteInfo['campaign'];
		$rsvp_data['campaign'] = $websiteInfo['campaignDescription'];
		if($info['source'] != '') $rsvp_data['source'] = $info['source'];
		else $rsvp_data['source'] = 'web';
		$rsvp_data['url'] = $websiteInfo['url'];
		$rsvp_data['webSource'] = $websiteInfo['url'];
		$rsvp_data['eventID'] = $event['eventID'];
		if($websiteInfo['leadsToARC'] == 'yes') $rsvp_data['leadsToARC'] = $websiteInfo['leadsToARC'];
		$rsvp_data['webCode'] = $websiteInfo['code'];

		foreach($info as $key => $val)
		{
			$val = strip_tags($val);
			if($key == 'firstname') $rsvp_data['firstName'] = $val;
			else if($key == 'lastname') $rsvp_data['lastName'] = $val;
			else if($key == 'model') $rsvp_data['currentVehicleModel'] = $val;
			else if(in_array($key,array('year','years'))) $rsvp_data['currentVehicleYear'] = $val;
			else if(in_array($key,array('makes','models'))) $rsvp_data['currentVehicleModel'] = trim($info['makes'] . ' ' . $info['models']);
			else if(in_array($key,array('tradeInLow'))) $rsvp_data['notes'] .= 'Vehicle Appraised: ' . $info['years'] . ' ' . $info['makes'] . ' ' . $info['models'] . ' ' . $info['trims'] . ' ' . $info['styles'] . ' ' . $info['kilometers'] . "KM/Miles\nTrade in Value: ". '$' . number_format($info['tradeInLow']) . ' - $' . number_format($info['tradeInHigh']) . "\n";
			else if($key == 'kilometers') $rsvp_data['currentVehicleKM'] = $val;
			else if($key == 'dCode') $rsvp_data['webCode'] = $val;
			else if($key == 'url') $rsvp_data['url'] = $val;
			else $rsvp_data[$key] = $val;
		}

		/////////////////////////////////////
		//TRANSUNION DECISION CENTER
		/////////////////////////////////////
		if($info['tu_consent'] != '')
		{
			include_once('transunionUtils.php');
			$cus = array('ApplicantFirstName' => $info['firstname'],
						'ApplicantMiddleName' => '',
						'ApplicantLastName' => $info['lastname'],
						'ApplicantDOB' => date("m/d/Y",strtotime($info['birthdate_month'] . '/' .$info['birthdate_day'] . '/'.$info['birthdate_year'])),
						'ApplicantSIN' => '',
						'ApplicantUnparsedStreetAddress' => $info['address'],
						'ApplicantCity' => $info['city'],
						'ApplicantProvince' => $info['province'],
						'ApplicantPostalCode' => $info['postalCode'],
						'ApplicantPhoneNumber' => $info['mobilePhone'],
						'ApplicantIncome' => $info['income'],
						'ApplicantEmploymentOver2Years' => ($info['employment_year'] < 2 ? 'N':'Y'));

			$tu_results = transunion_decision($cus);
			$rsvp_data['tu_success'] = $tu_results['tu_success'];
			$rsvp_data['tu_decision'] = $tu_results['tu_decision'];
			$json['tu']['tu_success'] = $tu_results['tu_success'];
			$json['tu']['tu_decision'] = $tu_results['tu_decision'];
		}

		$json['rsvp'] = json_decode(file_get_contents('https://ar.absoluteresults.com/api/rsvp/?' . http_build_query($rsvp_data)),true);
		//include_once('../rsvp/index.php');

		/////////////////////////////////////
		//OPT IN
		/////////////////////////////////////
		if($rsvp_data['optIn'] == 'y')
		{
			$rsvp_data['create'] = 'on';
			$rsvp_data['arcUpdate'] = 'no';
			$rsvp_data['origfirstname'] = $rsvp_data['firstName'];
			$rsvp_data['origlastname'] = $rsvp_data['lastName'];

			$json['customerUpdate'] = file_get_contents('https://ar.absoluteresults.com/api/customerUpdate/?' . http_build_query($rsvp_data));
		}

		////////////////////////////////
		// LANGUAGE
		////////////////////////////////

		/*if(isset($_GET['setLang'])) {
			$_SESSION['myLang'] = $_GET['setLang'];
		}

		if(!isset($_SESSION['myLang'])) {
	    	 $_SESSION['myLang'] = $websiteInfo['lang'];
	    }*/

	   	if(isset($_GET['setLang'])) {
			$_SESSION['myLang'] = $_GET['setLang'];
		}
		else{
			 $_SESSION['myLang'] = $websiteInfo['lang'];
		}

		$lang = array();


		/////////////////////////////////////
		//LEADBRIDGE
		/////////////////////////////////////

		if($websiteInfo['leadbridge'] == 'yes')
		{
			include_once('leadBridgeUtils.php');


			$lead = array();
			$lead['emailAddr'] = $info['email'];
			$lead['lang'] = 'EN';
			$lead['mainNum'] = $info['mainPhone'];
			$lead['name'] = $info['firstname'] . ' ' . $info['lastname'];
			$lead['postalOrZipCode'] = $info['postalCode'];
			$lead['whisper'] .= 'Dealership ' . $dealer['dealerName'] . ' ';
			if($_GET['currentVehicleModel'] != "") $lead['whisper'] .= $lang['This customer owns a'].' ' . trim($_GET['currentVehicleYear'] . ' ' . $_GET['currentVehicleModel']);
			connectLeadBridge($lead,$event['dealerID'],$websiteInfo['leadsToARC']);
		}


		/////////////////////////////////////
		//EMAIL
		/////////////////////////////////////

		include_once('emailUtils.php');

		// OLD LANGUAGE VERSION
		/*$frext = '';
		if($websiteInfo['lang'] == 'fr')
		{
			include_once('fr.php');
			$frext = '-fr';
		}
		else include_once('en.php');*/

		$dealerObj = Dealer::ById($event['dealerID']);
		if($dealerObj instanceof Dealer){
			if($dealerObj->hasOEM(OEM_BRP)){
				$lang['title'] = 'SALE CONFIRMATION';
			}
		}

		if($websiteInfo['name'] != '') $subject = $websiteInfo['name'] . ' ' . ($websiteInfo['noPrivate'] == 'yes' ? $lang['titleNP'] : $lang['title']);
		else $subject = trim($websiteInfo['name'] . ' ' . $websiteInfo['campaignDescription']);

		if($event['dealerID'] == 6489) $subject = str_replace("Conferma di vendita privata","",$subject);

		if($websiteInfo['overrideDate'])
			$eventDate =  $websiteInfo['eventDate'];
		else
			$eventDate = displayEventDate($event);

		if($_SESSION['myLang'] == 'fr') {
			$websiteInfo['gift'] = $websiteInfo['gift-fr'];
			$websiteInfo['bonus'] = $websiteInfo['bonus-fr'];
			$websiteInfo['extra'] = $websiteInfo['extra-fr'];
		}

		$dateDisplay = $lang[date("F",$eventDate)].' '.date("j",$eventDate);
		if ( $_SESSION['myLang'] == 'it' ||  $_SESSION['myLang'] == 'fr' ||  $_SESSION['myLang'] == 'sp')
			echo date("j",$eventDate).' '.$lang[date("F",$eventDate)];
		else if ( $_SESSION['myLang'] == 'en')
			$lang[date("F",$eventDate)].' '.date("j",$eventDate);


		$gift1 = $lang['gift1'];
		if($event['dealerID'] == 6221) $gift1 = '<b><font style="color:blue;">On vous attend le ';
		// Gift
		if($websiteInfo['gift'] != '' && $websiteInfo['gift'] != 'none' && $websiteInfo['gift'] != 'blank') $gift = $gift1.' '.$eventDate.' '.$lang['gift2'].' <font style="color:blue;">'.$websiteInfo['gift'].'</font>'.$lang['gift3'].'<br><br>';
		else if($websiteInfo['gift'] != 'blank') $gift = $gift1 . $eventDate.' ' . $lang['giftDefault'];

		if($websiteInfo['bonus'] != '' && $websiteInfo['bonus'] != 'none') $bonus = $lang['pbon1'].' '. $eventDate.' '.$lang['gift2'].' <font style="color:blue;">'.$websiteInfo['bonus'].'</font> '.$lang['gift3'].'<br><br>';
		if($websiteInfo['extra'] != '' && $websiteInfo['extra'] != 'none') $extra = nl2br($websiteInfo['extra']).'<br>';

		$images = array();
		if(file_exists('../../rsvp/' . $websiteInfo['campaign'] .'/images/email/EmailHeader.png'))
		{
			$images['header'] = '../../rsvp/' . $websiteInfo['campaign'] .'/images/email/EmailHeader.png';
		}

		$body = '<html>
					<body style="background-color:#ebebeb;margin:0px;padding:10px;">
						<table>
							<tr>
								<td style="padding:20px;background-color:white;vertical-align:top;height:100%;font-size:1em;font-family:arial;box-shadow: 0 0 10px #aaa;">
									' . ($images['header'] != '' ? '[IMG_header]<br><br>' : '') . '
									<br>';
									if ($_SESSION['myLang'] == "fr") {
										$body .= $lang['salutation2'].'<br><br>';
									}
									else if ($_SESSION['myLang'] != "it") {
										$body .= $lang['salutation'] .' '. $info['firstname'] . ',
										<br><br>';
									}
									$body .= $lang['thankyou2'];



		if($rsvp_data['tu_decision'] != '')
		{
			$body .= '<br>'.$lang['reviewInfoShortly'];
			if($json['rsvp']['appointmentID'] != '') $body .= '<br>'.$lang['Your confirmation number is'].' <b>' . $json['rsvp']['appointmentID'] . '</b>.';
		}

		$body .= '					<br><br>';


		$attachments = array();
		if($websiteInfo['voucher'] != "none")
		{
				$body .= $lang['attachment'];
				/*
				if(file_exists('../../rsvp/' . $websiteInfo['campaign'] .'/images/email/'  .$websiteInfo['voucher'] . '.jpg'))
				{
					$images['coupon_thumb'] = '../../rsvp/' . $websiteInfo['campaign'] .'/images/email/'  .$websiteInfo['voucher'] . '.jpg';
					$body .= '[IMG_coupon_thumb]<br>';
				}
				*/
				$coupon_param = array();
				$coupon_param['tid'] = $websiteInfo['masterTaskID'];
				$coupon_param['fn'] = $info['firstname'];
				$coupon_param['ln'] = $info['lastname'];
				if($info['bonus_amount'] != '') $coupon_param['bonus_amount'] = $info['bonus_amount'];

				$attach = array();
				$attach['path'] = 'https://ar.absoluteresults.com/api/rsvpwebsites/coupon.php?' . http_build_query($coupon_param);

				$attach['name'] = $lang['couponName'];
				$attach['type'] = 'image/jpeg';
				$attachments[] = $attach;
		}

		if($gift != "") $body .= '<b><font style="color:blue;">' . $gift . '</font></b><br><br>';
		if($bonus != "") $body .= '<b><font style="color:blue;">' . $bonus . '</font></b><br><br>';
		if($extra != "") $body .= '<b><font style="font-weight:bold;">' . $extra . '</font></b><br><br>';


		if($info['tradeInLow'] != "" && $info['tradeInLow'] != 0)
		{

			$body .= '<b>
					 	' . $lang['appraisal1'] . '
					 	<br>
					 	<font style="font-size:13pt">
					 	' . $lang['appraisal2'] . ucwords($info['years'] . ' ' . $info['makes'] . ' ' . $info['models']) . '  ' . $lang['appraisal3'] . '<u>$' . number_format($info['tradeInLow']) . ' - $' . number_format($info['tradeInHigh']) . '</u>
						</font>
					 	</b>
					 	<br>
					 	<div style="padding-top:10px;font-size:9pt;font-style:italics">
					 	' . $lang['appraisalDisclaimer'] . '
					 	</div>

					 	<br><br>';
		}
		else if(isset($info['tradeInLow']))
		{
			// Black Book Value
			$body .= '<b>
					 	<font>
					 	' . $lang['appraisalFail'] . '
					 	</font>

					 	</b><br><br>';
		}


		if($websiteInfo['name'] != '')
		{
			$body .= '
						 			<br>
						 			<b>' . $websiteInfo['name'] . '</b><br>

						 			' . $websiteInfo['address'] . '<br>

						 			' . $websiteInfo['phone'] . '
									 <br><br>';
		}
		else if($dealer['dealerID'] != '')
		{
			$body .= '
						 			<br>
						 			<b>' . $dealer['dealerName'] . '</b><br>

						 			' . $dealer['address'] . '<br>

						 			' . $dealer['phone'] . '
									 <br><br>';
		}

		$body .= '				</td>
							</tr>
							<tr>
								<td style="text-align:right;font-family:arial;font-size:0.7em;color:#777">Absolute Results.</td>
							</tr>
						</table>
					</body>
				</html>';

				// Will add the contact and vehicle information to the email
		/*
		$body .='				<font style="font-size:10pt">
					 			<b><u>'.$lang['info'].'</u></b><br>
					 			<b>'.$lang['First Name'].':</b> ' . $info['customer']['firstname']  . '<br>
					 			<b>'.$lang['Last Name'].':</b> ' . $info['customer']['lastname']  . '<br>
					 			<b>'.$lang['Email'].':</b> ' . $info['customer']['email'] . '<br>
					 			<b>'.$lang['Main Phone'].':</b> ' . $info['customer']['mainPhone'] . '<br>';


		 if($websiteInfo['region'] == "US" || $websiteInfo['region'] == "us") {
						$body .= '<b>'.$lang['postalcode_US'].':</b> ' . $info['customer']['postalCode'] . '<br>';
		 } else if ($websiteInfo['eventID'] == 22721) {

		 } else if ($websiteInfo['region'] == "UK" || $websiteInfo['region'] == "uk") {
						$body .= '<b>'.$lang['postalcode_UK'].':</b> ' . $info['customer']['postalCode'] . '<br>'	;
		 } else {
						$body .= '<b>'.$lang['postalcode_CA'].':</b> ' . $info['customer']['postalCode'] . '<br>';
		 } //end IF region

		if($websiteInfo['blackbook'] != "no" && $websiteInfo['blackbook'] != "NO" && $websiteInfo['blackbook'] != "none" && $websiteInfo['blackbook'] != "NONE" && $websiteInfo['blackbook'] != "" && $info['tradeIn']['years'] != ""){
				 		$body .=	'<br><b>'.$lang['Year'].':</b> ' . $info['tradeIn']['years'] . '<br>
						 			<b>'.$lang['Make'].':</b> ' . $info['tradeIn']['makes'] . '<br>
						 			<b>'.$lang['Model'].':</b> ' . $info['tradeIn']['models'] . '<br>
						 			<b>'.$lang['Trim'].':</b> ' . $info['tradeIn']['trims'] . '<br>
						 			<b>'.$lang['Style'].':</b> ' . $info['tradeIn']['styles'] . '<br>
						 			<b>'.$lang['km'].':</b> ' . $info['tradeIn']['kilometers'] . '<br>' ;
			} //end IF blackbook
		*/
		//echo json_encode(urlencode($body));
		//exit;
		$webContactArray = array();
		$primaryContactArray = array();
		$leadFromContact = "";
		$sql = 'SELECT * FROM
				(SELECT dealerID FROM ps_events WHERE eventID=' . $event['eventID'] . ') as a1
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

		if($event['dealerID'] == 153) $from = 'salesoffice@eastsidedodge.com';
		else if($leadFromContact != "") $from = $leadFromContact;
		else $from = $websiteInfo['emails'][0];

		$to = array(trim($info['email']));
		$bcc = array('devteam@absoluteresults.com');
		if(sendEmail($subject,$body,$from,$to,array(),$bcc,$attachments,null,$images)) $json['email'] = 'success';
		else $json['email'] = 'failed';
	}

	echo json_encode($json);
?>