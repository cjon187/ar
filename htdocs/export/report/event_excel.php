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

$grades = DealerContactsController::$grades;

require_once "Spreadsheet/Excel/Writer.php";
$xls =& new Spreadsheet_Excel_Writer();
$xls->setVersion(8);
$xls->send($event['eventName'] . ' - Sale Day Summary.xls');

////////////////////////////////////////////////////
//TOTAL SALE DAY REGISTRATIONS/////////////////////////////////////////////

$sheet =& $xls->addWorksheet('Total Sale Day Registrations');
$xls->setCustomColor(12, 146, 208, 80);

$bold =& $xls->addFormat();
$bold->setBold();

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
				 "grade"		=> "Grade",
				 "prospect"		=> "Prospect",
				// "dateRange"		=> "Interest Range",
				 "arrivedTime"	=> "Show",
				 //"demo1"		=> "Demo",
				// "demo2"		=> "",
				// "demo3"		=> "",
				// "demo4"		=> "",
				// "written1"		=> "Written",
				 //"written2"		=> "",
				// "written3"		=> "",
				// "written4"		=> "",
				 "appointmentTime"		=> "Appt",
				 "sold1"		=> "Sold",
				 "sold2"		=> "",
				 "sold3"		=> "",
				 "sold4"		=> "",
				 "notes"	=> "Notes");
				// "delivered1"		=> "Delivered",
				// "delivered2"		=> "",
				// "delivered3"		=> "",
				// "delivered4"		=> "");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','demo2','demo3','demo4','written2','written3','written4','sold2','sold3','sold4','delivered2','delivered3','delivered4','mainPhoneNDNC','mobilePhoneNDNC','within18');

$colCount = 0;
foreach($columns as $key => $val) {
	if(in_array($key,$ignoreArray)) {
		continue;
	}

	$sheet->write(0,$colCount,$val,$bold);
	$colCount++;
}

$sourceArray = array('invite' => 'Mailing List',
					 'walkIn' => 'Walk In',
					 'web' => 'Web RSVP',
					 'dealer' => 'Be-Backs',
					 'text' => 'Text',
					 'bdc' => 'BDC',
					 'other' => 'other');

$sql = 'SELECT
			' . implode(',',array_keys($columns)) . ',
			mainPhoneNDNC,
			mobilePhoneNDNC,
			coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18
		FROM ' . $event['apptTbl'] . '
		WHERE
			arrivedTime is not null AND eventID = ' . $event['eventID'] . '
		ORDER BY lastname';
$apptsResults = mysqli_query($db_data,$sql);

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
			$val = ($val == '' ? '' : $apptRow['arrivedTime']);
		else if($key == 'demo1')
			$val = ($apptRow['demo1'] == 'y' || $apptRow['demo2'] == 'y'  || $apptRow['demo3'] == 'y' || $apptRow['demo4'] == 'y' ? 'y' : '');
		else if($key == 'written1')
			$val = ($apptRow['written1'] == 'y' || $apptRow['written2'] == 'y'  || $apptRow['written3'] == 'y' || $apptRow['written4'] == 'y' ? 'y' : '');
		else if($key == 'sold1')
			$val = ($apptRow['sold1'] == 'y' || $apptRow['sold2'] == 'y'  || $apptRow['sold3'] == 'y' || $apptRow['sold4'] == 'y' ? 'y' : '');
		else if($key == 'delivered1')
			$val = ($apptRow['delivered1'] == 'y' || $apptRow['delivered2'] == 'y'  || $apptRow['delivered3'] == 'y' || $apptRow['delivered4'] == 'y' ? 'y' : '');
		else if($key == 'appointmentTime')
			$val = ($apptRow['appointmentTime'] != '' ? 'y' : '');
		 else if($key == 'grade') {
			$val = $grades[$val];
		}

		//else if($key == 'source')
		//	$val = $sourceArray[$val];
		$sheet->write($rowCount,$colCount,$val,$cellcolor);

		$colCount++;
	}
	$rowCount++;
}
////////////////////////////////////////////////////
//SALE DAY APPOINTMENTS////////////////////////////

$sheet =& $xls->addWorksheet('Sale day Appointments');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "mainPhone"		=> "Phone",
				 "mobilePhone"		=> "Mobile",
				 "email"		=> "Email",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "nextVehicleYear"		=> "Next Vehicle",
				 "nextVehicleMake"		=> "",
				 "nextVehicleModel"		=> "",
				 "nextVehicleDescription"		=> "",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "prospect"	=> "Prospect",
				 "source"	=> "Source",
				 "grade"	=> "Grade",
				 "appointmentTime"	=> "Appt Date",
				 "arrivedTime"	=> "Kept",
				 "sold1"	=> "Sold",
				 "notes"	=> "Notes");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','nextVehicleMake','nextVehicleModel','nextVehicleDescription','mainPhoneNDNC','mobilePhoneNDNC','within18');


$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);

	$colCount++;

	if($key == 'appointmentTime')
	{
		$sheet->write(0,$colCount,'Appt Time',$bold);
		$colCount++;
	} else if($key == 'grade') {
		$val = $grades[$val];
	}

}

$sql = 'SELECT ' . implode(',',array_keys($columns)) . ', mainPhoneNDNC,mobilePhoneNDNC, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND (appointmentTime != "" OR arcProspect = "hand_raiser" OR arcProspect = "warm" OR arcProspect = "hot") ORDER BY salesperson,prospect desc,lastname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'nextVehicleYear')
			$val = trim($apptRow['nextVehicleYear'] . ' ' . $apptRow['nextVehicleMake'] . ' ' .$apptRow['nextVehicleModel'] . ' ' . $apptRow['nextVehicleDescription']);
		else if($key == 'appointmentTime')
		{
			if($val != "") $val = date("Y-m-d",strtotime($val));
		}
		else if($key == 'salesperson' && $_SESSION['login']['dealerID'] == 246 && is_numeric($val))
			$val = $mayfieldSalespersonArray[$val]['name'];
		else if($key == 'arrivedTime')
			$val = trim($apptRow['arrivedTime'] == "" ? '' : 'y');
		else if($key == 'sold1')
			$val = trim($apptRow['sold1'] == "" ? '' : 'y');
		 else if($key == 'grade') {
			$val = $grades[$val];
		}


		$sheet->write($rowCount,$colCount,$val,$cellcolor);
		$colCount++;

		if($key == 'appointmentTime')
		{
			if($val == "") $time = '';
			else
			{
				$time = date("H:i:s",strtotime($apptRow[$key]));
				if($time == '00:00:00') $time = '';
			}
			$sheet->write($rowCount,$colCount,$time,$cellcolor);
			$colCount++;
		}
	}
	$rowCount++;
}
////////////////////////////////////////////////////
//TECH LEADS////////////////////////////////////////

$sheet =& $xls->addWorksheet('Tech Leads');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "mainPhone"		=> "Phone",
				 "mobilePhone"		=> "Mobile",
				 "email"		=> "Email",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "nextVehicleYear"		=> "Next Vehicle",
				 "nextVehicleMake"		=> "",
				 "nextVehicleModel"		=> "",
				 "nextVehicleDescription"		=> "",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "prospect"	=> "Prospect",
				 "source"	=> "Source",
				 "appointmentTime"	=> "Appt Date",
				 "arrivedTime"	=> "Kept",
				 "sold1"	=> "Sold",
				 "notes"	=> "Notes");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','nextVehicleMake','nextVehicleModel','nextVehicleDescription','mainPhoneNDNC','mobilePhoneNDNC','within18');


$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);

	$colCount++;

	if($key == 'appointmentTime')
	{
		$sheet->write(0,$colCount,'Appt Time',$bold);
		$colCount++;
	}

}

$sql = 'SELECT ' . implode(',',array_keys($columns)) . ', mainPhoneNDNC,mobilePhoneNDNC, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND (source like "%web%" OR source like "%sms%" or source like "%email%") ORDER BY salesperson,prospect desc,lastname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'nextVehicleYear')
			$val = trim($apptRow['nextVehicleYear'] . ' ' . $apptRow['nextVehicleMake'] . ' ' .$apptRow['nextVehicleModel'] . ' ' . $apptRow['nextVehicleDescription']);
		else if($key == 'appointmentTime')
			$val = ($val == "" ? "" : date("Y-m-d",strtotime($val)));
		else if($key == 'arrivedTime')
			$val = trim($apptRow['arrivedTime'] == "" ? '' : 'y');
		else if($key == 'sold1')
			$val = trim($apptRow['sold1'] == "" ? '' : 'y');

		$sheet->write($rowCount,$colCount,$val,$cellcolor);
		$colCount++;

		if($key == 'appointmentTime')
		{
			$time = ($val == "" ? "" : date("H:i:s",strtotime($apptRow[$key])));
			if($time == '00:00:00') $time = '';
			$sheet->write($rowCount,$colCount,$time,$cellcolor);
			$colCount++;
		}
	}
	$rowCount++;
}


////////////////////////////////////////////////////
//DIGITAL LEADS////////////////////////////////////////

$sheet =& $xls->addWorksheet('Digital Leads');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "postalCode"	=> "Postal Code",
				 "mainPhone"		=> "Phone",
				 "mobilePhone"		=> "Mobile",
				 "email"		=> "Email",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "nextVehicleYear"		=> "Next Vehicle",
				 "nextVehicleMake"		=> "",
				 "nextVehicleModel"		=> "",
				 "nextVehicleDescription"		=> "",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "prospect"	=> "Prospect",
				 "source"	=> "Source",
				 "arcProspect"	=> "Disposition",
				 "appointmentTime"	=> "Appt Date",
				 "arrivedTime"	=> "Kept",
				 "sold1"	=> "Sold",
				 "createdAt"	=> "Registered On",
				 "notes"	=> "Notes");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','nextVehicleMake','nextVehicleModel','nextVehicleDescription','mainPhoneNDNC','mobilePhoneNDNC','within18');


$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);

	$colCount++;

	if($key == 'appointmentTime')
	{
		$sheet->write(0,$colCount,'Appt Time',$bold);
		$colCount++;
	}
}
$digitalColumns = array_keys($columns);
foreach($digitalColumns as $i => $c) {
	if($c == 'createdAt') {
		$digitalColumns[$i] = 'r.'.$c;
	} else {
		$digitalColumns[$i] = 'a.'.$c;
	}
}
$sql = 'SELECT 
			' . implode(',',$digitalColumns) . ', 
			a.mainPhoneNDNC,
			a.mobilePhoneNDNC, 
			coalesce(a.lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18
		FROM ' . $event['apptTbl'] . ' a
		LEFT JOIN rsvp_website_registration r ON (a.eventID = r.eventID AND a.appointmentID = r.appointmentID)
		WHERE 
			a.eventID = ' . $event['eventID'] . ' AND 
			(a.source like "%digital%") 
		ORDER BY 
			a.salesperson,
			a.prospect desc,
			a.lastname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'nextVehicleYear')
			$val = trim($apptRow['nextVehicleYear'] . ' ' . $apptRow['nextVehicleMake'] . ' ' .$apptRow['nextVehicleModel'] . ' ' . $apptRow['nextVehicleDescription']);
		else if($key == 'appointmentTime')
			$val = ($val == "" ? "" : date("Y-m-d",strtotime($val)));
		else if($key == 'arrivedTime')
			$val = trim($apptRow['arrivedTime'] == "" ? '' : 'y');
		else if($key == 'sold1')
			$val = trim($apptRow['sold1'] == "" ? '' : 'y');

		$sheet->write($rowCount,$colCount,$val,$cellcolor);
		$colCount++;

		if($key == 'appointmentTime')
		{
			$time = ($val == "" ? "" : date("H:i:s",strtotime($apptRow[$key])));
			if($time == '00:00:00') $time = '';
			$sheet->write($rowCount,$colCount,$time,$cellcolor);
			$colCount++;
		}
	}
	$rowCount++;
}


////////////////////////////////////////////////////
//OPT IN LEADS////////////////////////////////////////

$sheet =& $xls->addWorksheet('Opt-In');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "mainPhone"		=> "Phone",
				 "mobilePhone"		=> "Mobile",
				 "email"		=> "Email",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "nextVehicleYear"		=> "Next Vehicle",
				 "nextVehicleMake"		=> "",
				 "nextVehicleModel"		=> "",
				 "nextVehicleDescription" => "",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "prospect"	=> "Prospect",
				 "source"	=> "Source",
				 "appointmentTime"	=> "Appt Date",
				 "arrivedTime"	=> "Kept",
				 "sold1"	=> "Sold",
				 "notes"	=> "Notes");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','nextVehicleMake','nextVehicleModel','nextVehicleDescription','mainPhoneNDNC','mobilePhoneNDNC','within18');


$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);

	$colCount++;

	if($key == 'appointmentTime')
	{
		$sheet->write(0,$colCount,'Appt Time',$bold);
		$colCount++;
	}

}

$sql = 'SELECT ' . implode(',',array_keys($columns)) . ', mainPhoneNDNC,mobilePhoneNDNC, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND (optIn = "y") ORDER BY salesperson,prospect desc,lastname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'nextVehicleYear')
			$val = trim($apptRow['nextVehicleYear'] . ' ' . $apptRow['nextVehicleMake'] . ' ' .$apptRow['nextVehicleModel'] . ' ' . $apptRow['nextVehicleDescription']);
		else if($key == 'appointmentTime')
			$val = ($val == "" ? "" : date("Y-m-d",strtotime($val)));
		else if($key == 'arrivedTime')
			$val = trim($apptRow['arrivedTime'] == "" ? '' : 'y');
		else if($key == 'sold1')
			$val = trim($apptRow['sold1'] == "" ? '' : 'y');

		$sheet->write($rowCount,$colCount,$val,$cellcolor);
		$colCount++;

		if($key == 'appointmentTime')
		{
			$time = ($val == "" ? "" : date("H:i:s",strtotime($apptRow[$key])));
			if($time == '00:00:00') $time = '';
			$sheet->write($rowCount,$colCount,$time,$cellcolor);
			$colCount++;
		}
	}
	$rowCount++;
}

////////////////////////////////////////////////
//PRESALES & SALE DAY SOLDS/////////////////////

$sheet =& $xls->addWorksheet('Presales & Sale Day Solds');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("salesrep"		=> "Sales Rep",
				 "firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "mainPhone"	=> "Main Phone",
				 "postalCode"	=> "Postal Code",
				 "source"		=> "Source",
				 "salesperson"	=> "Salesperson",
				 "vin"		=> "VIN",
				 "stock"		=> "Stock #",
				 "vehicleYear"	=> "Year",
				 "vehicleMake"	=> "Make",
				 "vehicleModel"	=> "Model",
				 "vehicleNewUsed"	=> "New/Used",
				 "tradeYear"	=> "Trade Vehicle Year",
				 "tradeMake"	=> "Trade Vehicle Make",
				 "tradeModel"	=> "Trade Vehicle Model",
				 "currentVehicleYear"	=> "Replaced Vehicle Year",
				 "currentVehicleModel"	=> "Replaced Vehicle",
				 "arrivedTime"	=> "Date",
				 "notes"	=> "Notes",
				 "manifestSource"	=> "Manifest");
				 //"demo"			=> "Demo",
				 //"written"		=> "Written",
				// "sold"			=> "Sold",
				 //"delivered"	=> "Delivered");

$colCount = 0;
foreach($columns as $key => $val)
{
	$sheet->write(0,$colCount,$val,$bold);
	$colCount++;
}

$sql = 'SELECT salesrep1,salesrep2,salesrep3,salesrep4,firstName,lastName,mainPhone,postalCode,source,salesperson,
			   stock1,year1,make1,model1,newUsed1,demo1,written1,sold1,delivered1,tradeYear1,tradeMake1,tradeModel1,
			   stock2,year2,make2,model2,newUsed2,demo2,written2,sold2,delivered2,tradeYear2,tradeMake2,tradeModel2,
			   stock3,year3,make3,model3,newUsed3,demo3,written3,sold3,delivered3,tradeYear3,tradeMake3,tradeModel3,
			   stock4,year4,make4,model4,newUsed4,demo4,written4,sold4,delivered4,tradeYear4,tradeMake4,tradeModel4,currentVehicleYear,currentVehicleModel,arrivedTime,notes,manifestSource
			   , mainPhoneNDNC,mobilePhoneNDNC, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18
		FROM ' . $event['apptTbl'] . '
		WHERE eventID = ' . $event['eventID'] . ' AND sold1 = "y"';

$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	for($v = 1; $v <= 4; $v++)
	{
		if($apptRow['sold' . $v] != '')
		{
			$colCount = 0;

			foreach($apptRow as $key => $val)
			{
				if(in_array($key,array('salesrep' . $v,
									   'firstName',
									   'lastName',
									   'mainPhone',
									   'postalCode',
									   'source',
									   'salesperson',
									   'vin' . $v,
									   'stock' . $v,
									   'year' . $v,
									   'make' . $v,
									   'model' . $v,
									   'newUsed' . $v,
									   'manifestSource',
									   'arrivedTime',
									   'tradeYear' . $v,
									   'tradeMake' . $v,
									   'tradeModel' . $v,
									   'currentVehicleYear',
									   'currentVehicleModel',
									   'notes')))
				{

					$sheet->write($rowCount,$colCount,$val);
					$colCount++;
				}
			}
			$rowCount++;
		}
	}
}
///////////////////////////////////////////////
//FOLLOW UPS////////////////////////////

$sheet =& $xls->addWorksheet('Follow Ups');

$bold =& $xls->addFormat();
$bold->setBold();

$columns = array("firstName" 	=> "First Name",
				 "lastName"		=> "Last Name",
				 "mainPhone"		=> "Phone",
				 "currentVehicleYear"		=> "Current Vehicle",
				 "currentVehicleMake"		=> "",
				 "currentVehicleModel"		=> "",
				 "nextVehicleYear"		=> "Next Vehicle",
				 "nextVehicleMake"		=> "",
				 "nextVehicleModel"		=> "",
				 "nextVehicleDescription"		=> "",
				 "salesperson"	=> "Salesperson",
				 "trainerAppointment"	=> "Trainer Appt?",
				 "trainerConfirmed"	=> "Trainer Cnfrm?",
				 "prospect"	=> "Prospect",
				 "appointmentTime"	=> "Appt Date",
				 "arrivedTime"	=> "Arrived",
				 "notes"	=> "Notes");

$ignoreArray = array('currentVehicleMake','currentVehicleModel','nextVehicleMake','nextVehicleModel','nextVehicleDescription','arrivedTime','mainPhoneNDNC','mobilePhoneNDNC','within18');


$colCount = 0;
foreach($columns as $key => $val)
{
	if(in_array($key,$ignoreArray)) continue;
	$sheet->write(0,$colCount,$val,$bold);

	$colCount++;

	if($key == 'appointmentTime')
	{
		$sheet->write(0,$colCount,'Appt Time',$bold);
		$colCount++;
	}

}

$sql = 'SELECT ' . implode(',',array_keys($columns)) . ', mainPhoneNDNC,mobilePhoneNDNC, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' . $event['apptTbl'] . ' WHERE eventID = ' . $event['eventID'] . ' AND arrivedTime != "" AND (sold1 != "y" OR sold1 is null) AND (prospect = "hot" or prospect = "warm" OR prospect = "" OR prospect is null) ORDER BY salesperson,prospect desc,lastname';
$apptsResults = mysqli_query($db_data,$sql);

$rowCount = 1;
while($apptRow = mysqli_fetch_assoc($apptsResults))
{
	$colCount = 0;

	$cellcolor =& $xls->addFormat();
	if($apptRow['arrivedTime'] != '') $cellcolor->setFgColor('yellow');

	foreach($apptRow as $key => $val)
	{
		if(in_array($key,$ignoreArray)) continue;
		else if($key == 'currentVehicleYear')
			$val = $apptRow['currentVehicleYear'] . ' ' . $apptRow['currentVehicleMake'] . ' ' .$apptRow['currentVehicleModel'];
		else if($key == 'nextVehicleYear')
			$val = trim($apptRow['nextVehicleYear'] . ' ' . $apptRow['nextVehicleMake'] . ' ' .$apptRow['nextVehicleModel']. ' ' . $apptRow['nextVehicleDescription']);
		else if($key == 'appointmentTime' && $val != '')
			$val = date("Y-m-d",strtotime($val));

		$sheet->write($rowCount,$colCount,$val,$cellcolor);
		$colCount++;

		if($key == 'appointmentTime')
		{
			$time = date("H:i:s",strtotime($apptRow[$key]));
			if($time == '00:00:00') $time = '';
			$sheet->write($rowCount,$colCount,$time,$cellcolor);
			$colCount++;
		}
	}
	$rowCount++;
}




$xls->close();

?>