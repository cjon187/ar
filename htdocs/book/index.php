<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();

	$psbc = new PrivateSaleBookerController();
	$psbc->setBookerBySlug($_GET['booker']);

	if(isset($_POST['saveRegistration'])) {
		if($psbc->saveRegistration($_POST)) {
			echo json_encode(['success' => 1]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => $psbc->getFirstError()]);
		}
		exit;
	} else {
		$psbc->buildPage(['dealerStaffHash' => $_GET['ds']]);
	}


?>