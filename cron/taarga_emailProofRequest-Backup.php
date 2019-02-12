<?php

	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('agreementUtils.php');
	include_once('displayUtils.php');
	include_once('taskUtils.php');


	$tblArrays = array();

	$date = date("Y-m-d",strtotime("now + 5 days"));
	$esql = 'SELECT eventID FROM ps_events WHERE eventStart = "' . $date . '"';
	$tsql = 'SELECT eventID,taskID FROM ps_tasks_email';

	$sql = 'SELECT * FROM
			(' . $esql . ') as a1
			INNER JOIN
			(' . $tsql . ') as a2
			USING
			(eventID)';


	$re = mysqli_query($db_data,$sql);

	while($t = mysqli_fetch_assoc($re))
	{
		$tblArrays[] = $t;
	}
	if(count($tblArrays) == 0)
	{
		echo 'No entries found.';
		exit;
	}

	foreach($tblArrays as $info)
	{

		$dealerEvent = displayEventInfo($info['eventID']);
		$task = displayTaskInfo($info['taskID'],'email');

		$error = '';

		if($dealerEvent['eventID'] == '')
		{
			$error = 'No Dealer Event Found';
		}
		else
		{
			$eventDate = date("F j",strtotime($dealerEvent['saleStartDate'])) . (date("j",strtotime($dealerEvent['saleStartDate'])) ==  date("j",strtotime($dealerEvent['saleEndDate'])) ? '':'-' . date("j",strtotime($dealerEvent['saleEndDate'])));


			$dealerInfo = displayDealerInfo($dealerEvent['dealerID']);


			if($task['worksheetID'] == '')
			{
				$error = 'No Agreement Found';
			}
			else
			{
				$agreement = displayAgreementInfo($task['worksheetID']);

				$sql = 'SELECT * FROM ps_worksheets WHERE (website is not null and website != "") AND dealerID = ' . $dealerInfo['dealerID'] . ' AND eventEnd = "' . $dealerEvent['saleEndDate'] . '" AND (deleted is null OR deleted = "")';

				$webAgreementResults = mysqli_query($db_data,$sql);
				$webAgreement = mysqli_fetch_assoc($webAgreementResults);

				$artworkLinks = array();
				$docxLinks = array();

				$fileList = displayEventFiles($dealerEvent['eventID'],false,true);

				$fileLink = '';
				$modify = false;
				$includeDocx = false;
				$i = 1;
				foreach($fileList as $file)
				{
					if(strpos($file['filename'],"f.pdf") !== false)
					{
						if(stripos($file['filename'], 'CONQUEST') === false && stripos($file['filename'], '_c') === false) $type = 'Invitation';
						else $type = 'Conquest';

						$fileLink = $file;
						$info = pathinfo($file['filename']);
						$filename =  basename($file['filename'],'.'.$info['extension']);
						$fullpath = '//SVDC/absolute shared/absolute results/' . $fileLink['path'] . '/';

						$modifyType = 'nomodify';
						if(stripos($filename,'Crazy') === false &&
						   (stripos($filename,'PaymentMatch') !== false
						   || stripos($filename,'Upgrade Letter') !== false
						   || stripos($filename,'Payment Match') !== false
						   || stripos($filename,'Exchange Letter') !== false
						   || stripos($filename,'Manifest Letter') !== false
						   || stripos($filename,'Manifest VIP Pass') !== false)) $modifyType = 'modify';


						if(stripos($filename,'Email By Mail') !== false) $includeDocx = true;

						if(!file_exists($path . $filename . '-1.jpg'))
						{
							exec('gm convert -resize 660x5000 -density 480x480 -quality 100 -compress None "' . $fullpath . $filename . '.pdf[0]" "' . $fullpath . $filename . '-1.jpg"');
							exec('gm convert -resize 660x5000 -density 480x480 -quality 100 -compress None "' . $fullpath . $filename . '.pdf[1]" "' . $fullpath . $filename . '-2.jpg"');
							exec('gm convert -resize 660x5000 -density 480x480 -quality 100 -compress None "' . $fullpath . $filename . '.pdf[2]" "' . $fullpath . $filename . '-3.jpg"');
							exec('gm convert -resize 660x5000 -density 480x480 -quality 100 -compress None "' . $fullpath . $filename . '.pdf[3]" "' . $fullpath . $filename . '-4.jpg"');
							exec('gm convert -resize 660x5000 -density 480x480 -quality 100 -compress None "' . $fullpath . $filename . '.pdf[4]" "' . $fullpath . $filename . '-5.jpg"');

						}
						$j = 1;
						while(file_exists($fullpath . $filename . '-' . $j . '.jpg'))
						{
							$artworkLinks[$type][$modifyType][] = array('file' => $fullpath . $filename . '-' . $j . '.jpg',
															  			'name' => $type.$i.'-'.$j.'.jpg');
							$j++;
						}

						$artworkLinks[$type][$modifyType][] = array('file' => $fullpath . $fileLink['filename'],
														  			'name' => $type.$i.'.pdf');

						$i++;

					}


					if(stripos($file['filename'],".docx") !== false)
					{
						$docxLinks[] = array('file' => $file['fullpath'] . '/' . $file['filename'],
											 'name' => basename($file['filename']));
					}
				}

				$artwork = array();
				if(count($artworkLinks['Invitation']['nomodify']) > 0)	$artwork = $artworkLinks['Invitation']['nomodify'];
				else if(count($artworkLinks['Conquest']['nomodify']) > 0) $artwork = $artworkLinks['Conquest']['nomodify'];
				else if(count($artworkLinks['Invitation']['modify']) > 0)
				{
					$modify = true;
					$artwork = $artworkLinks['Invitation']['modify'];
				}
				else if(count($artworkLinks['Conquest']['modify']) > 0)
				{
					$modify = true;
					$artwork = $artworkLinks['Conquest']['modify'];
				}
				else $error = 'No Artwork Found';
			}

		}

		include_once('Emailer/swift_required.php');

		$transport = Swift_SmtpTransport::newInstance('absoluteresults.smtp.com',2525)->setUsername('smtp@absoluteresults.com')->setPassword('@B5oLut3');
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();

		if($error == '')
		{
			$body = '<div style="font-family:arial">
						<br>Hi Taarga,
						<br>
						<br>Please forward a proof for the following event:
						<br>
						<br>EventID: ' . $dealerEvent['eventID'] . '
						<br>Dealership: ' . $dealerInfo['dealerName'] . '
						<br>Country: ' . $dealerInfo['country'] . '
						<br>DA#: ' . displayWorksheetNum($agreement) . '
						<br>Event Date: ' . $eventDate . '
						<br>Trainer Date: ' . date("F j",strtotime($agreement['eventStart'])) . '
						<br>
						<br>Language: ' . ($dealerInfo['isFrench'] == 'on' ? '<font style="font-weight:bold;color:red">FRENCH</font>' : 'English') . '
						<br>URL: ' . ($webAgreement['website'] != "" ? '<font style="font-weight:bold;color:red">' . $webAgreement['website'] . '</font>' : 'none') . '
						<br>
						<br>Thank You!
					</div>
					  ';



			foreach($artwork as $art)
			{
				$attachment = Swift_Attachment::fromPath($art['file'])->setFilename($art['name']);
				$message->attach($attachment);
			}

			if($includeDocx)
			{
				foreach($docxLinks as $docx)
				{
					$attachment = Swift_Attachment::fromPath($docx['file'])->setFilename($docx['name']);
					$message->attach($attachment);
				}
			}
		// END: BODY OF MESSAGE


			if($modify)
			{
				$message->setSubject("REVISION REQUIRED - REQUEST FOR PROOF " . $dealerInfo['dealerName'] . ' #' . $dealerEvent['eventID']);
				$message->setTo(array('devteam@absoluteresults.com'));
				$body = 'Hi Ken, are you able send me a revised version of this letter for email campaign?<br>Thanks so much!!<br><br><br>' . $body;
			}
			else
			{
				/*$message->setSubject("REQUEST FOR PROOF " . $dealerInfo['dealerName'] . ' Event#' . $dealerEvent['eventID'] . ' : DA#'. $task['worksheetID']);*/
				$message->setSubject("REQUEST FOR PROOF " . $dealerInfo['dealerName'] . ' #' . $dealerEvent['eventID']);
				$message->setTo(array('ar@taarga.com'));
			}

			//$message->setTo(array('davepao@gmx.com'));

			$message->setBody($body, 'text/html');
			$message->setBcc(array('tech@absoluteresults.com'));
			$message->setReturnPath('tech@absoluteresults.com');
			$message->setFrom('tech@absoluteresults.com');

			if(!$mailer->send($message))
			{
				$error = 'Email Error';
			}
		}


		if($error != '')
		{
			$message->setSubject("Email Proof Error - " . $dealerEvent['dealerName'] . ' ' . $dealerEvent['eventStart']);
			$message->setTo(array('tech@absoluteresults.com'));
			$body = $error . ' - ' . $dealerEvent['dealerName'] . ' ' . $dealerEvent['eventStart'];
			$message->setBody($body, 'text/html');
			$message->setReturnPath('tech@absoluteresults.com');
			$message->setFrom('tech@absoluteresults.com');
			$mailer->send($message);
		}

		echo $dealerEvent['dealerName'] . ' ' . $dealerEvent['eventStart'] . ' - ' . ($error == '' ? 'SENT' : $error);

	}

?>
