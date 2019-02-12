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


$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "address"		=> "Address",
				 "city"			=> "City",
				 "province"		=> "Province",
				 "postalCode"	=> "Postal Code",
				 "mainPhone"	=> "Main Phone",
				 "mobilePhone"	=> "Mobile Phone",
				 "email"		=> "Email",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "currentVehicleKM"			=> "Current Vehicle KM",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "source"		=> "Source",
				 "arcProspect"		=> "Prospect",
				 "dateRange"		=> "Interest Range",
				 "appointmentTime"	=> "Appt",
				 "arrivedTime"		=> "Show",
				 "sold1"			=> "Sold",
				 "sold2"			=> "",
				 "sold3"			=> "",
				 "sold4"			=> "",
				 "notes"			=> "Notes");
				// "delivered1"		=> "Delivered",
				// "delivered2"		=> "",
				// "delivered3"		=> "",
				// "delivered4"		=> "");

$ignoreArray = array('arrivedTime','currentVehicleMake','currentVehicleModel','demo2','demo3','demo4','written2','written3','written4','sold2','sold3','sold4','delivered2','delivered3','delivered4');

$sourceArray = array('invite' => 'Mailing List',
					 'walkIn' => 'Walk In',
					 'web' => 'Web RSVP',
					 'dealer' => 'Be-Backs',
					 'text' => 'Text',
					 'bdc' => 'BDC',
					 'other' => 'other');

$sql = 'SELECT ' . implode(',',array_keys($columns)) . ' FROM ' . $event['apptTbl'] . ' WHERE eventID=' . $event['eventID'] . ' AND source like "%arc%" AND arcProspect in ("appointment","hot","warm","hand_raiser") ORDER BY lastname';

$apptsResults = mysqli_query($db_data,$sql);

if(isset($_GET['get_count']))
{
	echo mysqli_num_rows($apptsResults);
	exit;
}

require_once "Spreadsheet/Excel/Writer.php";
$xls =& new Spreadsheet_Excel_Writer();
$xls->setVersion(8);
$xls->send($event['eventName'] . ' - ARC Leads.xls');

////////////////////////////////////////////////////
//TOTAL SALE DAY REGISTRATIONS/////////////////////////////////////////////

$sheet =& $xls->addWorksheet('ARC Leads');
$xls->setCustomColor(12, 146, 208, 80);

$bold =& $xls->addFormat();
$bold->setBold();

$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);
	$colCount++;
}



$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['sold1'] == 'y') $cellcolor->setFgColor(12);

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'arrivedTime')
			$val = ($val == '' ? '' : 'y');
		else if($key == 'demo1')
			$val = ($apptRow['demo1'] == 'y' || $apptRow['demo2'] == 'y'  || $apptRow['demo3'] == 'y' || $apptRow['demo4'] == 'y' ? 'y' : '');
		else if($key == 'written1')
			$val = ($apptRow['written1'] == 'y' || $apptRow['written2'] == 'y'  || $apptRow['written3'] == 'y' || $apptRow['written4'] == 'y' ? 'y' : '');
		else if($key == 'sold1')
			$val = ($apptRow['sold1'] == 'y' || $apptRow['sold2'] == 'y'  || $apptRow['sold3'] == 'y' || $apptRow['sold4'] == 'y' ? 'y' : '');
		else if($key == 'delivered1')
			$val = ($apptRow['delivered1'] == 'y' || $apptRow['delivered2'] == 'y'  || $apptRow['delivered3'] == 'y' || $apptRow['delivered4'] == 'y' ? 'y' : '');
		else if($key == 'appointmentTime')
			$val = $apptRow['appointmentTime'];
		//else if($key == 'source')
		//	$val = $sourceArray[$val];
		$sheet->write($rowCount,$colCount,utf8_decode($val),$cellcolor);

		$colCount++;
	}
	$rowCount++;
}



$xls->close();

?>