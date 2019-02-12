<?php
	include_once('arSession.php');

	include_once('loginUtils.php');
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	include_once('csvUtils.php');

	$db = new ARDB();

	if(isset($_GET['key']))
	{
		if($_GET['key'] != "9C941INMK6" && md5($_GET['eid']) != $_GET['key'])
		{
			echo 'Invalid Key';
			exit;
		}
	}
	else if(isset($_GET['ekey']))
	{
		if(!checkEncrypt($_GET['eid'],$_GET['ekey']))
		{
			echo 'Invalid E-Key';
			exit;
		}
	}
	else exit;

	$event = Event::byId($_GET['eid']);
	$apptTbl = $event->apptTbl;

	ini_set("auto_detect_line_endings", true);
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . str_replace(' ','_',$event->dealer->dealerName) . '_'. $event->saleStartDate . '_Export.csv');

	$output = fopen('php://output', 'w');


	$sqlArray = [];
	$sqlArray[] = '(a.eventID = ' . $event->id . ')';
	$sqlArray[] = '(a.source LIKE "%arc%")';

	if($_GET['unreachable']) {
		$sqlArray[] = '(a.arcProspect IN ("answer_machine","no_answer","try_again"))';
	}

	if($_GET['vehicles']) {
		$row = ['First Name','Last Name','Main Phone','Mobile Phone','Year','Make','Model','Description','ARC Staff','Disposition','Appointment Time','Arrival Time','Purchased'];

		$sql = 'SELECT a.firstName,a.lastName,a.mainPhone,a.mobilePhone,a.currentYear,a.currentMake,a.currentModel,a.currentDescription,s.name,a.arcProspect,a.appointmentTime,a.arrivedTime,a.sold1, a.mainPhoneNDNC,a.mobilePhoneNDNC,coalesce(a.lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18,optIn FROM
				' . $apptTbl . ' a
				LEFT JOIN ps_staff s ON (a.arcStaffID = s.staffID)
				WHERE ' . implode(' AND ',$sqlArray);
	} else {
		$row = ['First Name','Last Name','Main Phone','Mobile Phone','ARC Staff','Disposition','Appointment Time','Arrival Time','Purchased'];

		$sql = 'SELECT a.firstName,a.lastName,a.mainPhone,a.mobilePhone,s.name,a.arcProspect,a.appointmentTime,a.arrivedTime,a.sold1, a.mainPhoneNDNC,a.mobilePhoneNDNC,coalesce(a.lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18,optIn FROM
				' . $apptTbl . ' a
				LEFT JOIN ps_staff s ON (a.arcStaffID = s.staffID)
				WHERE ' . implode(' AND ',$sqlArray);
	}
	fwritecsv($output,$row,',',$enclosure);

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {

		foreach($re as $key => $val)
		{
			if($key == 'mainPhone' && $val != '') {
				$re[$key] = ($re['within18'] || $re['mainPhoneNDNC'] == 1 || $re['optIn'] == 'y' ? $val : '');
			} else if($key == 'mobilePhone' && $val != '') {
				$re[$key] = ($re['within18'] || $re['mobilePhoneNDNC'] == 1 || $re['optIn'] == 'y' ? $val : '');
			}
		}

		unset($re['mainPhoneNDNC']);
		unset($re['mobilePhoneNDNC']);
		unset($re['within18']);
		unset($re['optIn']);

		fwritecsv($output,$re,',',$enclosure);
	}
?>