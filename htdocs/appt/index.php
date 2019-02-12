<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	require_once('classes/ARSession.class.php');
	$session = new ARSession('ar_consumer_portal');

	$db = new ARDB();
	$apptRC = new AppointmentReminderController();

	$param = rtrim($_GET['param'],'/');
	$param2 = rtrim($_GET['param2'],'/');
	$page = rtrim($_GET['page'],'/');
	if(empty($page)) {
		$page = 'main';
	}
       

	if($page == 'l') {
		if($_POST['code']) {
			$hash = $_POST['code'];
			$returnJSON = true;
			$viewSource = AppointmentReminderLog::VIEW_SOURCE_CODE;
		} else {
			$hash = $param;
			$viewSource = $param2;
		}
		session_unset ($_SESSION['reminderID']);
		session_unset ($_SESSION['viewLogged']);
		$_SESSION['viewSource'] = $viewSource;

	} else if($page == 'create') {
		
		if($rid = $apptRC->createAppointmentReminder($_POST)) {
			echo json_encode(['reminderID' => $rid]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => $apptRC->getFirstError()]);
		}
		exit;

	} else if($_SESSION['reminderID']) {
		$reminder = AppointmentReminder::byID($_SESSION['reminderID']);
	}

	//CHECK IF THE AR CODE IS GIVEN AS THE HASH
	if($hash && (!$reminder instanceof AppointmentReminder)) {
		if($reminder = $apptRC->getAppointmentReminderByHash($hash)) {
			$_SESSION['reminderID'] = $reminder->id;
		}

		if($_SESSION['reminderID']) {

			//TEMPORARY TURN OFF OFFER POPUP FOR ALL
			//$_SESSION['showMyOffer'] = 1;

			//SET HASH IN COOKIES
			if($reminder->arLongCode) {
				$cookieValue = $reminder->arLongCode;
			} else if($reminder->arCode) {
				$cookieValue = $reminder->arCode;
			} else {
				$cookieValue = $hash;
			}
			setcookie('myvipsale_code',$cookieValue,time()+(60*60*24*365*5),'/');

			if($returnJSON) {
				echo json_encode(['success' => 1]);
			} else {
				header("location: " . AR_APPT_REMINDER_URL);
			}
		} else {
			if($returnJSON) {
				http_response_code(401);
				echo json_encode(['failed' => 1]);
			} else {
				$apptRC->buildCodePage();
			}
		}

		exit;
	}
	
	if(!$reminder instanceof AppointmentReminder && $_COOKIE['myvipsale_code']) {
		$reminder = AppointmentReminder::where('arLongCode',$_COOKIE['myvipsale_code'])->getOne();
		if(!$reminder instanceof AppointmentReminder) {
			$reminder = AppointmentReminder::where('arCode',$_COOKIE['myvipsale_code'])->getOne();
		}
		if(!$reminder instanceof AppointmentReminder) {
			$reminder = AppointmentReminder::where('hash',$_COOKIE['myvipsale_code'])->getOne();
		}
	}

	if($reminder instanceof AppointmentReminder) {
		$_SESSION['reminderID'] = $reminder->id;
		$dealer = $reminder->dealer;

		if(!$_SESSION['viewLogged']) {
			$reminder = $apptRC->updateReminderCurrentEvent($reminder);
			$apptRC->setAppointmentReminder($reminder);
			$apptRC->logViewed($_SESSION['viewSource']);

			if($_SESSION['viewSource'] == AppointmentReminderLog::VIEW_SOURCE_SMS_CAMPAIGN) {
				$log = $reminder->getAppointmentLog();
				if($log instanceof AppointmentsLog) {
					$logger = new ApptLogger($reminder->eventID);
					$logData = $log->toArray();
					$logData['source'] = 'sms';
					$logData['notes'] = 'Engaged Consumer Portal';
					$logger->log($logData);
					//$log->addSource('sms');
				}
			} else if($_SESSION['viewSource'] == AppointmentReminderLog::VIEW_SOURCE_EMAIL_CAMPAIGN) {
				$log = $reminder->getAppointmentLog();
				if($log instanceof AppointmentsLog) {
					$log->addSource('email');
				}
			}
			$_SESSION['viewLogged'] = true;

		} else {
			$apptRC->setAppointmentReminder($reminder);
		}

		//USE OLD APPOINTMENT REMINDER PAGE FOR ANYWHERE OUTSIDE AUS AND O'CONNOR CHRYSLER
		if(
			!(
				$dealer->countryID == COUNTRY_AU ||
				($dealer->countryID == COUNTRY_CA && $dealer->language->major == 'en')
			)
		) {
			$dlc = new DealerLoginController($reminder->event->dealer);

			if(isset($_GET['ics'])) {
				$dlc->buildICS($reminder);
			}
			
			$dlc->buildAppointmentReminderPage($reminder);
			exit;
		}





		if(!$apptRC->validatePage($page)) {
			$page = 'no_permission';
		}

		switch($page) {
			case('appraisal'):
				if($_POST['saveAppraisal']) {
					if($apptRC->saveAppraisal($_POST)) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => $apptRC->getFirstError()]);
					}
					exit;
				}

				$apptRC->buildAppraisalPage($param == 'edit');
				break;
			case('appraisal_request'):
				if($_POST['saveAppraisalRequest']) {
					if($apptRC->saveAppraisalRequest($_POST)) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => $apptRC->getFirstError()]);
					}
					exit;
				}

				$apptRC->buildAppraisalRequestPage($param == 'edit');
				break;
			case('upgrade'):
				if($_POST['saveEnhancement']) {
					if($apptRC->saveEnhancement($_POST)) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => $apptRC->getFirstError()]);
					}
					exit;
				}

				$apptRC->buildUpgradePage($param == 'edit');
				break;
			case('vehicle_offers'):
				if($param == 'download') {
					$apptRC->buildOfferCoupon($param2);
					exit;
				}
				$apptRC->buildVehicleOffersPage();
				break;
			case('map'):
				$apptRC->buildMapPage();
				break;
			case('ics'):
				$apptRC->buildICS();
				break;
			case('main'):

				if($_POST['logOfferScratched']) {
					$_SESSION['offerScratched'] = 1;
					$logger = new AppointmentReminderLogger($reminder);
					$logger->logScratchedOffer();
					exit;
				}


				if($_POST['enterContest']) {
					if($apptRC->enterContest()) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => 'Unable to set appointment. Please contact the dealership.']);
					}
					exit;
				}

				$showMyOffer = $_SESSION['showMyOffer'];
				$_SESSION['showMyOffer'] = false;

				$apptRC->buildMainPage([
					'showMyOffer' => $showMyOffer,
					'viewSource' => $_SESSION['viewSource'],
					'offerScratched' => $_SESSION['offerScratched']
				]);
				break;
			case('maximize_trade'):
				$apptRC->buildMaximizeTradePage();
				break;
			case('appointment'):
				if($_POST['updateAppointment']) {
					if($apptRC->updateAppointment($_POST['appointmentTime'])) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => 'Unable to set appointment. Please contact the dealership.']);
					}

					exit;
				}
				if($_POST['cancelAppointment']) {
					if($apptRC->cancelAppointment()) {
						echo json_encode(['success' => 1]);
					} else {
						http_response_code(400);
						echo json_encode(['error' => 'Unable to cancel appointment. Please contact the dealership.']);
					}

					exit;
				}
				$apptRC->buildAppointmentPage();
				break;
			default:

				if($page) {
					$arCode = $page;
					$cusInfo = ARCodeController::getCustomerInfo($arCode);
					if($cusInfo) {
						header("location: " . AR_APPT_REMINDER_URL . 'l/' . $arCode);
						exit;
					}
				}
				
				$apptRC->build404Page();
				break;
		}

		exit;
	}

	if($page) {
		$arCode = $page;
		$cusInfo = ARCodeController::getCustomerInfo($arCode);
		if($cusInfo) {
			header("location: " . AR_APPT_REMINDER_URL . 'l/' . $arCode);
			exit;
		}
	}

  	$domainArr=explode('.', $_SERVER['HTTP_HOST']);
  	if(count($domainArr) > 2) {
  		$subdomain = $domainArr[0];
  	}
	$apptRC->buildCodePage($subdomain);
?>