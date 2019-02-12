<?php

$myLang = 'en';
$_SESSION['registration']['myLang'] = $myLang;
$lang = array();

$lang['pagetitle'] = ' Private Sale Event RSVP'; //preceded by dealer name

//FORM
$lang['formHeader1'] = 'Please provide your information below to ';
$lang['formHeader2'] = 'receive your savings coupon.';
$lang['formHeader3'] = 'RSVP.';
$lang['firstname'] = "First Name";
$lang['lastname'] = 'Last Name';
$lang['postalcode_CA'] = 'Postal Code';
$lang['postalcode_US'] = 'Zip Code';
$lang['postalcode_UK'] = 'Postcode';
$lang['phone'] = 'Phone';
$lang['email'] = 'Email';
$lang['vehicle'] = 'Current Vehicle';
$lang['appointmentDate'] = "Pick your Preferred Appointment Date";

//CODE
$lang['code'] = 'Please tell us your Invitation Code to get started.';
$lang['codeBtn'] = 'Get Started';

//APPRAISAL
$lang['appraisalHeader'] = 'Fill out the information below to RSVP and see the value of your vehicle.';
$lang['year'] = 'Year';
$lang['make'] = 'Make';
$lang['model'] = 'Model';
$lang['trim'] = 'Trim';
$lang['style'] = 'Style';
$lang['km'] = 'KM';
$lang['miles'] = 'Miles';
$lang['accessCode'] = 'Access Code';
$lang['transmission'] = 'Transmission';
$lang['cylinder'] = 'Cylinder';
$lang['drivetrain'] = 'Drive Train';
$lang['payments'] = 'Payments';
$lang['balanceOwing'] = 'Balance Owing';

//VALIDATION
$lang['validationAppraisal'] = 'Please complete all the required fields to see your appraisal value.\nThank you.';
$lang['validationRSVP'] = 'Please complete all the required fields to RSVP.\nThank you.';
$lang['validationEmail'] = 'The email address appears to be invalid.';
$lang['validationCode'] = 'Invalid Code';

//PARKER's MOOD
$lang['parkersBlurb'] = 'You are invited to a Payment Match Event. Take advantage of exclusive access to a range of special offers.<BR><BR>Please RSVP in advance, as we expect high attendance.';
$lang['parkersSubmit'] = "Submit";
$lang['parkersDisclaimer'] = "By clicking " . $lang['parkersSubmit'] . ": (a) you acknowledge that you have read, understand and agree with the terms of the Absolute Results <a href='http://www.absoluteresults.com/downloads/Privacy%20Policy.pdf' target='_blank'>Privacy Policy</a>...";

//FORM FOOTER
$lang['submit'] = 'RSVP Now';
$lang['disclaimer'] = "By submitting this form: (a) you acknowledge that you have read, understand and agree with the terms of the Absolute Results <a href='http://www.absoluteresults.com/downloads/Privacy%20Policy.pdf' target='_blank'>Privacy Policy</a>...";
$lang['disclaimer2'] = "and (b) you authorize the Absolute Results (or 'us') to collect, use and disclose your personal information, as provided in this form or in any other manner, in accordance with the Absolute Results Privacy Policy and/or as permitted or required by law. Only members of our personnel who require access to your personal information for the above purposes will be given access to your information. Your personal information will be kept at our office at 104-2677 192 St, Surrey, BC V3S 3X1 and you may request access to, and/or rectification of, your information by contacting our privacy officer at <a href='mailto:info@absoluteresults.com'>info@absoluteresults.com</a>. You may also withdraw your consent to the foregoing by contacting us at 1.888.751.7171 , at <a href='http://www.absoluteresults.com/contactus.php' target='blank'>http://www.absoluteresults.com/contactus.php</a> or at the above mailing address or email address.";
$lang['submitting'] = 'Please wait...';

//FLOATER RIGHT 
$lang['formBlurb'] = 'You are invited to a Private Sales Event. Take advantage of exclusive access to a range of special offers.<BR><BR>Please RSVP in advance, as we expect high attendance.';
$lang['formBlurbNP'] = 'You are invited to a Special Sales Event. Take advantage of exclusive access to a range of special offers.<BR><BR>Please RSVP in advance, as we expect high attendance.';
$lang['formBlurbLE'] = 'You are invited to a Loyalty Bonus Event. Take advantage of exclusive access to a range of special offers.<BR><BR>Please RSVP in advance, as we expect high attendance.';
$lang['dateTitle'] = 'Private Sale Date';
$lang['dateTitleNP'] = 'Special Sale Date';
$lang['urlAltTxt'] = 'Click here to visit our website';
$lang['bilingual'] = 'VERSION FRANÇAISE';
$lang['appraisalBlurb'] = 'Right now we are offering top-dollar for all trade-in vehicles. Your current car, truck or SUV might be worth thousands more than you think! Fill out the form on the left to get the Canadian Black Book value on your trade-in!';
$lang['usAppraisalBlurb'] = 'Right now we are offering top-dollar for all trade-in vehicles. Your current car, truck or SUV might be worth thousands more than you think! Fill out the form on the left to get the Black Book value on your trade-in!';

//CONFIRMATION
$lang['thankyou'] = 'Thank you for your registration.';
$lang['submitted'] = 'Your information has been submitted successfully.';
$lang['certificate'] = 'A savings certificate has been emailed to you.';
$lang['visit'] = 'Please visit our dealership:';
$lang['alertThx'] = 'Thank you for your submission.\nPlease check your email for details.';
$lang['valueSent'] = 'The appraisal value of your vehicle has been emailed to you.';

//FOOTER
$lang['privacy'] = 'Your privacy is important to us. ';
$lang['privacy2'] = 'By providing your personal information, you consent to its use and 
			        disclosure in accordance with our <a href="http://www.absoluteresults.com/downloads/Privacy%20Policy.pdf" target="_blank" style="color:yellow">Privacy Policy</a>. Therefore, it is 
			        important that you read and understand our privacy policy prior to 
			        providing any personal information about yourself.';
					
$lang['readmore'] = 'Read More';
$lang['copy'] = 'Copyright © ' . date("Y") . ' Absolute Results Productions Ltd.';

//EMAIL
$lang['title'] = " Private Sale Confirmation"; //email subject, preceded by dealer name
$lang['titleNP'] = " Special Sale Confirmation"; //email subject, preceded by dealer name
$lang['gift1'] = '<b><font style="color:blue;">Come in for a test drive '; //followed by event date
$lang['gift2'] = ' and receive <span style="color:red">'; //preceded by event date, followed by $gift
$lang['gift3'] = '</span> !!</font></b><BR><BR>'; //preceded by $gift
$lang['gift4'] = '<b><font style="color:blue;">Come in for a test drive during the '; //followed by event date which should be written as "Month of (Month)"
$lang['giftDefault'] = ' and save big when you purchase a new vehicle!</font></b><BR><BR>'; //preceded by event date
$lang['pbon1'] = '<b><font style="color:blue;">Purchase a vehicle '; //followed by date
$lang['salutation'] = 'Dear ';
$lang['thankyou2'] = 'Thank you for your registration!';
$lang['attachment'] = 'Your Savings Certificate is attached to this email.<br>Please print and bring with you to the dealership.<br><br>';
$lang['attachment2'] = 'Your Prize Ticket is attached to this email.<br>Please print and bring with you to the dealership.<br><br>';
$lang['info'] = 'Your Information';
$lang['couponName'] = 'Savings Certificate.jpg';
$lang['ticketName'] = 'Prize Ticket.jpg';
$lang['apptDate'] = 'Appointment Date';
$lang['apptTime'] = 'Preferred Time';

//EMAIL APPRAISAL
$lang['appraisal1'] = 'According to Black Book,';
$lang['appraisal2'] = 'Your ';
$lang['appraisal3'] = 'is worth ';
$lang['appraisalFail'] = 'We were not able to correctly appraise your vehicle, please contact the dealer for details.';
$lang['appraisalDisclaimer'] = 'For information purposes only. Actual market value may be higher.<br>Dealership appraisal required.';

//VOUCHER
$lang['validon'] = 'Valid ';
$lang['upto'] = 'You will receive up to';
$lang['loyaltyBonus'] = 'LOYALTY BONUS';
$lang['plusUpTo'] = 'plus up to';
$lang['concash'] = 'CONSUMER CASH';
$lang['withpurchase'] = 'with your new vehicle purchase!';

//SMS
$lang['smsConfirm'] = "Please reply 'Y' to this message to confirm your registration!";
$lang['smsThanks'] = "Thank you for your confirmation. You are now registered. We look forward to seeing you at "; //followed by dealer name


//PULL AHEAD
$lang['pullAheadSubmit'] = 'ACTIVATE NOW';
$lang['pullAheadBlurb'] = '<font style="font-size:1.1em;color:yellow">CHRYSLER CANADA LOYALTY INCENTIVE</font>
					<br>
					<font style="font-size:1.0em">CONSUMER CASH PROGRAM P1446B</font>
					<br><br>
					<font style="font-size:1.4em">
						<font style="color:red">EXPIRES</font> 
						FEBRUARY 28th, 2014
					</font>
					</div>
					<br>
					<font style="font-size:1em">
						You qualify to receive an extra <font style="color:yellow"><u>$1000</u></font> towards the purchase of ANY new
						Chrysler, Dodge, Jeep or RAM product from Chrysler Canada.
						<br><br>This <font style="color:red">limited time</font> offer <b><u>CAN BE COMBINED</u></b> with ALL current
						incentives. Please register here to activate this offer and visit your
						dealership to claim your savings.
					</font>
					<br><br>
					<a href="images/ChryslerPullAhead.pdf" style="color:yellow" target="_blank">Click here to see Official Program Details</a>';
$lang['pullAheadFeb'] = 'FEB';					
$lang['pullAheadTitle'] = 'Please confirm your information below to<br><font style="color:red;">ACTIVATE your Program Offer.</font>';
$lang['pullAheadComplete'] = '<font style="font-size:1.8em;font-weight:bold">Congratulations</font>
								<br><br>
								Your Loyalty Incentive Bonus has been activated.
								<br>
								You will be contacted by a representative to confirm your preferred appointment time.
							 	<br><br>
							 	Hurry in to our dealership for a complimentary TEST DRIVE and a hassle free QUOTE on the new vehicle of your choice.';

$lang['pullAheadEmailSubject'] = 'RE: CHRYSLER CANADA LOYALTY INCENTIVE PROGRAM P1446B - ';
$lang['pullAheadEmailBlurb'] = 'Congratulations! You have successfully activated your $1,000 Pull-Ahead Bonus.
							 	<br><br>
							 	You will be contacted by a representative to confirm your preferred appointment time.
							 	<br><br>
							 	Attached to this email is your Activation Coupon, please print and bring with you to the dealership for a complimentary TEST DRIVE and a hassle free QUOTE on the new vehicle of your choice.
							 	<br><br>
							 	<b><u>This offer expires February 28th, 2014.</u></b>';			

$lang['pullAheadEmailCoupon'] = 'Pull-Ahead Loyal Program Activation';							 					 	
?>