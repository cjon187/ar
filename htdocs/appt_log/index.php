<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();
	$session = new ARSession('dealer_login');

	$login = DealerApptLogin::where('accessKey',$_GET['accessKey'])->where('status',1)->getOne();

	if(!$login instanceof DealerApptLogin) {
		http_response_code(404);
		exit;
	}

	$event = $login->event;
	if(!$event instanceof Event) {
		http_response_code(400);
		exit;
	}

	if($event->eventEnd < date("Y-m-d")) {
		http_response_code(400);
		exit;
	}

	if(isset($_POST['checkCode'])) {
		if($login->code == $_POST['code']) {
			$_SESSION['login']['code'] = $_POST['code'];
			
			$log = new DealerApptLoginsLog();
			$log->dealerApptLoginID = $login->id;
			$log->eventID = $login->eventID;
			$log->activityType = DealerApptLoginsLog::TYPE_LOGIN;
			$log->save();


			echo json_encode(['success' => 1]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'Invalid Code']);
		}
		exit;
	}

	if(isset($_GET['logout'])) {
		session_unset();
		header("location: ./{$_GET['accessKey']}");
		exit;
	}
	
	$dlc = new DealerLoginController($event->dealer,true);

	$data = [];
	$data['eventID'] = $event->id;
	$event = $dlc->init($data);
	if(!$event instanceof Event) {
		echo 'Error';
		exit;
	}
	if($login->code != $_SESSION['login']['code']) {

		$dlc->handleAppointmentsLogAjax();
		$dlc->buildDealerApptLoginCodePage(['dealerApptLogin'=>$login]);
	} else {
		$dlc->handleAppointmentsLogAjax();
		$dlc->buildAppointmentsLogPage(['dealerApptLogin'=>$login],$_SESSION['search']);	
	}

	exit;
?>