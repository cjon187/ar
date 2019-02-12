<?php
	require_once('classes/ARSession.class.php');
	$session = new ARSession('roadshow');

	include_once('defines.php');
	include_once('displayUtils.php');

	define('ROADSHOW_URL',AR_SECURE_URL . 'ebcroadshow/');
	define('ROADSHOW_PATH',AR_ROOT . 'htdocs/ebcroadshow/');
	use Philo\Blade\Blade;

	if(isset($_POST['dealerName'])) {

		$error = null;
		if(empty($_POST['dealerName'])) {
			$error = 'Please tell us the name of your dealership';
		} else if(empty($_POST['name'])) {
			$error = 'Please tell us your name';
		} else if(empty($_POST['email'])) {
			$error = 'Please tell us your email';
		} else if(empty($_POST['roadShowID'])) {
			$error = 'Please tell us your preferred road show date/time';
		}

		if(!empty($error)) {
			echo json_encode(['success'=>false,'error'=>$error]);
		} else {

			$rs = new RoadShowRegistration($_POST);
			if($rs->save()) {

				$subject = 'EBC Road Show Registration - ' . $rs->dealerName;
				$body = '<div style="font-family:arial;">
							Hi ' . $rs->name . ',
							<br>
							<br><b>Thank you for your registration.</b>
							<br>
							<div style="font-family:arial;font-size:0.9em">
							<br><u><i>Here are your registration details</i></u>
							<br><b>Dealership: </b> ' . $rs->dealerName . '
							<br><b>Name:</b> ' . $rs->name . '
							<br><b>Email: </b> ' . $rs->email;
				if(!empty($_POST['guest1'])) {
					$body .= '<br><b>Guest:</b>' . $_POST['guest1'];
				}
				if(!empty($_POST['guest2'])) {
					$body .= '<br><b>Guest:</b>' . $_POST['guest2'];
				}
				if(!empty($_POST['guest3'])) {
					$body .= '<br><b>Guest:</b>' . $_POST['guest3'];
				}
				if(!empty($_POST['guest4'])) {
					$body .= '<br><b>Guest:</b>' . $_POST['guest4'];
				}

				$body .= '	<br>
							<br><u><i>Road Show Information</i></u>
							<br><b>' . date('l, F j',strtotime($rs->roadShow->date)) . '</b>
							<br><b>' . date('g:ia',strtotime($rs->roadShow->startTime)) . ' - ' . date('g:ia',strtotime($rs->roadShow->endTime)) . '</b>
							<br>' . $rs->roadShow->city . '
							<br>' . $rs->roadShow->venue . '
							<br>' . $rs->roadShow->address;

				$body .= '	</div>
							<br>
							<br>We look forward to seeing you!
							<br>
							<br>Absolute Results';

				$from = 'no-reply@absoluteresults.com';
				$to = [];
				$to[] = $_POST['email'];

				$bcc = [];
				$bcc[] = 'jrodney@absoluteresults.com';
				$bcc[] = 'mkoole@absoluteresults.com';
				$bcc[] = 'al@absoluteresults.com';


				EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc);
				$_SESSION['registered'] = true;

				echo json_encode(['success'=>true]);
			} else {
				echo json_encode(['success'=>false,'error'=>$rs->getFirstError()]);
			}
		}

		exit;
	}

	$bladeParams = [];
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);
	$bladeFile = 'roadshow.ebc';
	echo $blade->view()->make($bladeFile, $bladeParams)->render();
	exit;