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

	$stats = array();
	$dispositions = array();
	$totals = array();
	$sql = 'SELECT arcStaffID,arcProspect,arrivedTime,sold1 FROM ' . $apptTbl . ' WHERE eventID = ' . $event->id . ' AND source like "%arc%" AND ((arrivedTime is not null AND arrivedTime != "") OR (sold1 = "y"))';
	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {
		$dispositions[$re['arcProspect']] = $re['arcProspect'];
		if(!empty($re['arrivedTime'])) {
			$stats[$re['arcStaffID']][$re['arcProspect']]['show']++;
			$totals[$re['arcProspect']]['show']++;
		}
		if(!empty($re['sold1'])) {
			$stats[$re['arcStaffID']][$re['arcProspect']]['sold']++;
			$totals[$re['arcProspect']]['sold']++;
		}
	}


	
	ini_set("auto_detect_line_endings", true);
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=' . str_replace([' ',','],'_',$event->eventName) . '.csv');
	
	$output = fopen('php://output', 'w');
	$enclosure = '';
	$enclosure = '"';
	$row = array();	
	$row[] = 'Staff';
	foreach($dispositions as $d){
		$row[] = $d;
	}
	fwritecsv($output,$row,',',$enclosure);
	
	foreach($stats as $staffID => $stat) {			
		$staff = Staff::byId($staffID);

		$row = array();
		$row[] = $staff->name;
		foreach($dispositions as $d){
			$row[] = number_format($stat[$d]['show'],0,".","") . ' \ ' . number_format($stat[$d]['sold'],0,".","");
		}

		fwritecsv($output,$row,',',$enclosure);
	}	

	$row = array();
	$row[] = 'Total';
	foreach($dispositions as $d){
		$row[] = number_format($totals[$d]['show'],0,".","") . ' \ ' . number_format($totals[$d]['sold'],0,".","");
	}

	fwritecsv($output,$row,',',$enclosure);

?>