<?php
	if(isset($_GET['regID']))
	{
    	$info = unserialize(stripslashes(urldecode($_GET['regID'])));

		if(stripos($info['name'],'@youtube.com') !== false) exit;
    	include_once('displayUtils.php');
		include_once('Emailer/swift_required.php');

		try
		{
			$transport = Swift_SmtpTransport::newInstance('absoluteresults.smtp.com',2525)->setUsername('smtp@absoluteresults.com')->setPassword('@B5oLut3');
			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance('Absolute Results Website Contact Us Form Submission');

			$body = '<table cellspacing="0" cellpadding="0">
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Name</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['name'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Dealership</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['dealership'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Email</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['email'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Phone</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['phone'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">City</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['city'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Prov/State</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['province'] . '</td>
					 	</tr>
					 </table>
					 <br><br>
					 <table cellspacing="0" cellpadding="0">
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">New Vehicles / Month</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['new'] . '</td>
					 	</tr>
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Used Vehicles / Month</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['used'] . '</td>
					 	</tr>
					 </table>
					 <br><br>
					 <table cellspacing="0" cellpadding="0">
					 	<tr>
					 		<td style="font-size:10pt;font-family:Arial;padding-right:10px">Referral</td>
					 		<td style="font-size:10pt;font-family:Arial;font-weight:bold">' . $info['hear'] . '</td>
					 	</tr>
					 </table>
					';



			$message->setBody($body, 'text/html');

			if(in_array($info['province'],array('QC')))	$message->setTo(array('keray@absoluteresults.com','rst-germain@absoluteresults.com'));
			else if(in_array($info['province'],array('AB','BC','MB','NB','NL','NS','NT','NU','ON','PE','QC','SK','YT')))	$message->setTo(array('jeff@absoluteresults.com','keray@absoluteresults.com','al@absoluteresults.com'));
			else $message->setTo(array('jeff@absoluteresults.com','keray@absoluteresults.com','al@absoluteresults.com'));


			$message->setBcc(array('dave@absoluteresults.com'));

			$message->setReturnPath('info@absoluteresults.com');
			$message->setFrom('info@absoluteresults.com');

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
