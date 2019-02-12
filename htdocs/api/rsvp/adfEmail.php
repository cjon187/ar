<?php	
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	
	$sql = 'SELECT * FROM ps_adfemails WHERE dealerID = ' . $_GET['adf_dealerID'] . ' ORDER BY adfID DESC';
	$results = mysqli_query($db_data,$sql);
	
	$isCapitalSMS = ($rsvpInfo['source'] == 'sms' && in_array($_GET['adf_dealerID'],array(183,23,271,5610)));
	
	if(mysqli_num_rows($results) > 0 || $isCapitalSMS)
	{
		if($isCapitalSMS)
		{
			$adf['dealerID'] = $_GET['adf_dealerID'];
			
			if($adf['dealerID'] == '183') $adf['email'] = 'textleads@capitalgmc.ca';
			else if($adf['dealerID'] == '23') $adf['email'] = 'textleads@capitalgmcbuick.ca';
			else if($adf['dealerID'] == '271') $adf['email'] = 'textleads@capitalfordlincoln.ca';
			else if($adf['dealerID'] == '5610') $adf['email'] = 'textleads@capitalfordwinnipeg.ca';
		}
		else
		{
			$adf = mysqli_fetch_assoc($results);
		}
		
		$dealerInfo = displayDealerInfo($adf['dealerID']);
		
		if($adf['dealerID'] == 257) $rsvpInfo['promo'] = 'TOW-RSVP';
		
		if($rsvpInfo['arrivedTime'] != '') $action = 'IN STORE - ';
		else $action = '';
		 	
		include_once('Emailer/swift_required.php');	
		
		try 
		{			
			$transport = Swift_SmtpTransport::newInstance('absoluteresults.smtp.com',2525)->setUsername('smtp@absoluteresults.com')->setPassword('@B5oLut3');
			$mailer = Swift_Mailer::newInstance($transport);
			
			$message = Swift_Message::newInstance($action . '' . $dealerInfo['dealerName'] . " Absolute Results Lead");	
			
			$body = '<?xml version="1.0" encoding="UTF-8"?>
					<?adf version="1.0"?>
					<adf>
					    <prospect>
					        <requestdate>' . date("Y-m-d").'T'.date("H:i:s") . '-08:00</requestdate>
					        <vehicle interest="trade-in">
					            <year>' . $rsvpInfo['currentVehicleYear'] . '</year>
					            <make>' . $rsvpInfo['currentVehicleMake'] . '</make>
					            <model>' . $rsvpInfo['currentVehicleModel'] . '</model>				            	
					        </vehicle>
					        <vehicle interest="buy">
					            <year></year>
					            <make></make>
					            <model>' . $rsvpInfo['nextVehicleModel'] . '</model>
					        </vehicle>
					        <customer>
					            <contact>
					                <name part="full">' . $rsvpInfo['firstName'] . ' ' . $rsvpInfo['lastName'] . '</name>
					                <phone>' . ($rsvpInfo['mobilePhone'] == '' ? $rsvpInfo['mainPhone'] : $rsvpInfo['mobilePhone']) . '</phone>
					                <email>' . $rsvpInfo['email'] . '</email>
					                <address>
										<street line="1">' . $rsvpInfo['address'] . '</street>
										<postalcode>' . $rsvpInfo['postalCode'] . '</postalcode>
										<country></country>
									</address>
					            </contact>
					        </customer>
					        <vendor>
					            <contact>
					                <name part="full">' . $dealerInfo['dealerName'] . '</name>
					            </contact>
					        </vendor>
							<provider>
							    <name part="full">Absolute Results</name>
							    <service>' . $rsvpInfo['promo'] . '</service>
							</provider>				    
						</prospect>
					</adf>';
			
			if($_GET['adf_dealerID'] == 723)
			{
				$attachment = Swift_Attachment::newInstance($body, "ADF.xml", 'application/xml');  				
				$message->attach($attachment);
			}
			
			$message->setBody($body, 'text/plain');
			$message->setTo(array(trim($adf['email'])));
				
			$message->setBcc([]);
			$message->setReturnPath('web@absoluteresults.com');
			$message->setFrom('web@absoluteresults.com');
			
			if($mailer->send($message))
			{
				echo 'true';
			}
			else
			{
				echo 'false';	
			}
		
		} 
		catch (Swift_TransportException $e) 
		{
				echo 'false';	
		} 
		catch (Swift_Message_MimeException $e) 
		{
				echo 'false';	
		}
    }
    
?>

