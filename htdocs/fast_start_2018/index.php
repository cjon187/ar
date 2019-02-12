<?php
	include_once('displayUtils.php');
	use Philo\Blade\Blade;

	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

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

		if(empty($error)) {

			$reg = new LaunchDeckRegistration();
			$reg->campaignID = LaunchDeckRegistration::USA_JANUARY_2018_FAST_START;
			$reg->dealership = $_POST['dealership'];
			$reg->name = $_POST['contactName'];
			$reg->phone = $_POST['contactPhone'];
			$reg->email = $_POST['contactEmail'];
			$reg->date = $_POST['preferredDate'];

			if($reg->save()) {
				$subject = 'January 2018 Fast Start';
				$body = $blade->view()->make('launchDecks.emails.usa_jan_2018_fast_start', $_POST)->render();

				$from = 'jrodney@absoluteresults.com';
				$to = [$reg->email];
				$bcc = ['jrodney@absoluteresults.com'];
				if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc)) {
					echo json_encode(['success'=>true]);
					exit;
				} else {
					$error = 'Unable to send email. Please contact us at 1.888.751.7171';
				}
			} else {
				$error = $reg->getFirstError();
			}
		}

		echo json_encode(['success'=>false,'error'=>$error]);

		exit;
	}

	$registrations  = LaunchDeckRegistration::where('campaignID',LaunchDeckRegistration::USA_JANUARY_2018_FAST_START)->get();


	echo $blade->view()->make('launchDecks.usa_jan_2018_fast_start', [])->render();
?>