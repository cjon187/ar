<?php
	include_once('displayUtils.php');

	if(isset($_POST['dealership'])) {

		if(empty($_POST['dealership'])) {
			$error = 'Please tell us the name of your dealership.';
		} else if(empty($_POST['contactName'])) {
			$error = 'Please tell us your name.';
		}
		else if(empty($_POST['contactPhone'])) {
			$error = 'Please tell us your phone number.';
		}
		else if(empty($_POST['contactEmail'])) {
			$error = 'Please tell us your email.';
		}
		else if(empty($_POST['preferredDate'])) {
			$error = 'Please tell us your preferred date.';
		}

		if(empty($error)) {

			$subject = 'Alfa Romeo Giulia 2017 Launch Event Request';
			$body = '<div style="font-family:arial">';
			$body .= '<b>Dealership</b> ' . $_POST['dealership'] . '<br>';
			$body .= '<b>Contact Name</b> ' . $_POST['contactName'] . '<br>';
			$body .= '<b>Contact Phone</b> ' . $_POST['contactPhone'] . '<br>';
			$body .= '<b>Contact Email</b> ' . $_POST['contactEmail'] . '<br>';
			$body .= '<b>Preferred Date</b> ' . $_POST['preferredDate'] . '<br>';
			$from = 'no-reply@absoluteresults.com';
			$to = ['keray@absoluteresults.com'];
			$cc = [];
			$bcc = ['devteam@absoluteresults.com'];
			if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc)) {
				echo json_encode(['success'=>true]);
				exit;
			} else {
				$error = 'Unable to send email. Please contact us at 470-334-8691';
			}
		}

		echo json_encode(['success'=>false,'error'=>$error]);

		exit;
	}

	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);
	$dates = [
		'March 9 - March 12',
		'March 16 - March 19',
		'March 23 - March 26',
		'March 30 - April 2',
		'April 6 - April 9',
		'April 20 - April 23',
		'April 27 - April 30'
	];

	$params['dates'] = array_combine($dates,$dates);

	echo $blade->view()->make('launchDecks.giulia2017', $params)->render();
?>