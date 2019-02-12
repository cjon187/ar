<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');


	$db = new ARDB();
	$staff = new Staff();
	$staff = $staff->get();
	$today = new DateTime(date('Y-m-d'));
	echo 'Trainer License Expiry Start - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	foreach($staff as $key=> $s){
		if(count($s->trainerLicenses) > 0){
			foreach($s->trainerLicenses as $license){

				$expiryDate = new DateTime($license->expiryDate);
				$difference = $expiryDate->diff($today);
				$days = ($difference->format('%R%a') * (-1));

				//echo $days . "\n";
				if($license->licenseID == TRAINERLICENSE_OMVIC) {
					$daysPrior = 45;
				} else {
					$daysPrior = 30;
				}

				if($days == $daysPrior){
					if($s->email != ""){
						$licenseName = $license->name;
						$subject = $licenseName . " License Renewal - Expiry in " . $daysPrior . " days";
						$to = $s->email;
						$from = 'trainerlicensing@absoluteresults.com';
						$cc = 'trainerlicensing@absoluteresults.com';
						$bcc = [];


						$body = 'This is just a quick reminder that your '. $license->licenseName .' license will expire in ' . $daysPrior . ' days.
								 If you could please take care of this 2 weeks before your expiry date, it would be very helpful and appreciated,
								 otherwise it could cause issues with any '. strtoupper($license->province->provinceAbbr) .' events you may be assigned to. ';


						if($license->licenseID == TRAINERLICENSE_VSA){
							$body .= '<br><br>You can contact VSA at '. $license->phone .'.';
						}
						else if($license->licenseID == TRAINERLICENSE_AMVIC){
							$body .= '<br><br>I\'ve included the link for your convenience, and  if you experience any problems you can contact AMVIC by phone at '. $license->phone .'.';
							$body .= '<br><a href="https://portal.amvic.org/Pages/en_US/Forms/Public/Home/Default.aspx"> https://portal.amvic.org/Pages/en_US/Forms/Public/Home/Default.aspx</a>';
						}


						$body .= '<br><br>Thank you for taking care of this so quickly.';

						$body .= '<br><br><br>AR Trainer Licensing
									<br>604-282-7977
									<br>trainerlicensing@absoluteresults.com';

						EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,[],Email::TYPE_TRAINER_LICENSE_REMINDER);
					}
				} else if($days < 0){
					//DELETE THE ENTRY IF IT HAS BEEN EXPIRED FOR OVER 30 DAYS or 45 DAYS for OMVIC
					$db->where('licenseID', $license->id);
					$db->where('staffID', $s->id);
					$db->delete('ps_trainer_licenses');

				}

				echo $s->staffID . ' : ' . $s->name . PHP_EOL;
				//echo $s->staffID . ' = ' . $license->licenseID . ' ' . $expiryDate->format('Y-m-d') . ' Difference: ' .  ($difference->format('%R%a') * (-1))  . "\n";
			}
		}
	}

	echo 'Trainer License Expiry End - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>




