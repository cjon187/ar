<?php

include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
//include_once('agreementUtils.php');

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
		echo 'Invalid Key';
		exit;
	}
}
else
{
	checkPageAccess();
}

$event = displayEventInfo($_GET['eid']);

require_once "Spreadsheet/Excel/Writer.php";
$xls =& new Spreadsheet_Excel_Writer();
$xls->setVersion(8);
$xls->send($event['eventName'] . ' - Deal Hound Leads.xls');

////////////////////////////////////////////////////
//SALE DAY APPOINTMENTS////////////////////////////

$sheet =& $xls->addWorksheet('Sale day Appointments');

$bold =& $xls->addFormat();
$bold->setBold();


$columns = array('firstName' => "First Name",
				 'lastName' => "Last Name",
				 'mainPhone' => "Phone",
				 'mobilePhone' => "Mobile",
				 'email' => "Email",
				 'currentVehicleYear' => "Vehicle Year",
				 'currentVehicleModel' => "Vehicle",
				 'notes_from' => "From",
				 'notes_source' => "Source",
				 'notes_staff' => "Sales Staff",
				 'notes_appt' => "Appt?",
				 'notes_show' => "Show?",
				 'notes_notes' => "Notes");

$colCount = 0;
foreach($columns as $key => $val) {
	$sheet->write(0,$colCount,$val,$bold);
	$colCount++;
}


$sql = 'SELECT *,coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND source like "%dumpster%" ORDER BY lastname,firstname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,array('currentVehicleMake','currentVehicleModel'))) continue;
		else if($key == 'currentVehicleYear')
		{
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		}
		else if($key == 'notes')
		{
			$arr = explode("\n",$val);
			$apptRow['notes_from'] = $arr[0];
			$apptRow['notes_source'] = substr($arr[1],stripos($arr[1],':')+1);
			$apptRow['notes_staff'] = substr($arr[2],stripos($arr[2],':')+1);
			$apptRow['notes_appt'] = substr($arr[3],stripos($arr[3],':')+1);
			$apptRow['notes_show'] = substr($arr[4],stripos($arr[4],':')+1);
			$apptRow['notes_notes'] = substr($arr[5],stripos($arr[5],':')+1);
			/*for($i = 0;$i<5;$i++)
			{
				$v = array_shift($arr);
				if($i == 2 && stripos($v,'Sales Staff:') === false)  $colCount++;
				$sheet->write($rowCount,$colCount,$v,$cellcolor);
				$colCount++;
			}

			$val = implode("\n",$arr);*/
		}
		else if($key == 'mainPhone' && $apptRow[$key] != '') {
			$value = '';
			if($apptRow['doNotCall'] != 1 && ($apptRow['arrivedTime'] || $apptRow['appointmentTime'] || $apptRow['within18'] || $apptRow['mainPhoneNDNC'] == 1 || $apptRow['optIn'] == 'y')){
				$value = $apptRow[$key];
			}
			else if($apptRow['source'] != ""){
				$explodeSrc = explode(',',$apptRow['source']);
				if(count($explodeSrc) > 0){
					if(in_array('digital', $explodeSrc)){
						$value = 'Digital Reply - ' . $apptRow[$key];
					}
				}
			}
			$apptRow[$key] = $value;
		}
		else if($key == 'mobilePhone' && $apptRow[$key] != '') {
			$value = '';
			if($apptRow['doNotCall'] != 1 && ($apptRow['arrivedTime'] || $apptRow['appointmentTime'] || $apptRow['within18'] || $apptRow['mobilePhoneNDNC'] == 1 || $apptRow['optIn'] == 'y')) {
				$value = $apptRow[$key];
			}
			else if($apptRow['source'] != ""){
				$explodeSrc = explode(',',$apptRow['source']);
				if(count($explodeSrc) > 0){
					if(in_array('sms', $explodeSrc)){
						$value = $apptRow[$key];
					}
					if(in_array('digital', $explodeSrc)){
						$value = 'Digital Reply - ' . $apptRow[$key];
					}
				}
			}
			$apptRow[$key] = $value;
		}
	}

	foreach($columns as $key => $val) {
		$sheet->write($rowCount,$colCount,$apptRow[$key],$cellcolor);
		$colCount++;
	}

	$rowCount++;
}



$xls->close();

?>