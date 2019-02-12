<?php

	include_once('arSession.php');

	include_once "Spreadsheet/Excel/Writer.php";
	include_once("loginUtils.php");
	include_once("mysqliUtils.php");
	include_once("displayUtils.php");
	include_once("taskUtils.php");
	$db = new ARDB();

	if(isset($_GET['key']))
	{
		if($_GET['key'] != md5($_GET['eid']))
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

	if($_GET['fromContainer'] != '')
	{
		$sql = 'SELECT *,contactID as pieceID FROM ps_container_' . $_GET['fromContainer'] . '
		WHERE exclude is null OR exclude != "y"
		ORDER BY lastname,firstname';
		$dealerInfo	= displayDealerInfo($_SESSION['container']['dealerID']);
	}
	else
	{
		$event = displayEventInfo($_GET['eid']);
		$dealerInfo = displayDealerInfo($event['dealerID']);
		if($_GET['taskIDs'] != "")
		{
			$arr = explode(',',$_GET['taskIDs']);
			$sqlArray = array();
			foreach($arr as $key => $tid)
			{
				$task = displayTaskInfo($tid,'invitations');

				$taskObj = TaskInvitations::byId($tid);
				$contactIDs = $taskObj->getContactIDs();
				if(!empty($contactIDs)) {
					$sqlArray[] = 'SELECT *,"' . ($key+1) . '" as pad FROM ps_dealer_' . $task['dealerID'] . '_contacts WHERE contactID in (' . implode(',',$contactIDs) . ')';
				}
			}
			$sql = 'SELECT *,concat(pad,contactID) as pieceID FROM (' . implode(' UNION ',$sqlArray) . ') as a1 ORDER BY lastname,firstname';
		}
		else
		{
			if($_GET['taskID'] != '') $task = displayTaskInfo($_GET['taskID'] ,'invitations');
			else $task = array_shift(getTasks($_GET['eid'],'invitations'));

			$taskObj = TaskInvitations::byId($task['taskID']);
			$contactIDs = $taskObj->getContactIDs();
			if(!empty($contactIDs)) {

				if($_GET['last18Months'] == 'yes')
				{
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $event['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ') AND ((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
					ORDER BY lastname,firstname';
				}
				else if($_GET['last18Months'] == 'no')
				{
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $event['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ') AND !((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
					ORDER BY lastname,firstname';
				}
				else
				{
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $event['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ')
					ORDER BY lastname,firstname';
				}
			}
		}
	}

	$xls =& new Spreadsheet_Excel_Writer();
	$xls->setVersion (8);

	$bold =& $xls->addFormat();
	$bold->setBold();
	$bold->setSize(11);
	$bold->setHAlign('left');

	$white =& $xls->addFormat();
	$white->setSize(11);
	$white->setHAlign('left');

	$grey =& $xls->addFormat();
	$grey->setSize(11);
	$grey->setFGColor(12);
	$grey->setHAlign('left');

	$red =& $xls->addFormat();
	$red->setSize(11);
	$red->setFGColor(10);
	$red->setHAlign('left');

	$blue =& $xls->addFormat();
	$blue->setSize(11);
	$blue->setFGColor('yellow');
	$blue->setHAlign('left');

	$xls->setCustomColor(12, 220, 220, 220);


	if(isset($_GET['columbia']))
	{
		//COLUMBIA SALEREP PHONE LIST////////////////////
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'deliveryDate','text' => 'Delivery Date'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'email','text' => 'Email'),
							  array('column' => 'rate','text' => 'Rates'));
					if(isset($_GET['email_to_mail'])){
							 $columnsArray[] = array('column' => 'assignedSalesman','text' => 'Sales Rep');
					} else {
							 $columnsArray[] = array('column' => 'salesman','text' => 'Sales Rep');
					}
		/////////////////////////////////////////
	}
	else if(isset($_GET['allcolumns']))
	{

		$columnsArray = array(array('column' => 'contactID','text' => 'Contact ID'),
							  array('column' => 'customerCode','text' => 'Customer Code'),
							  array('column' => 'companyName','text' => 'Company Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'address1','text' => 'Address 1'),
							  array('column' => 'address2','text' => 'Address 2'),
							  array('column' => 'city','text' => 'City'),
							  array('column' => 'province','text' => 'Province'),
							  array('column' => 'postalCode','text' => 'Postal Code'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'email','text' => 'Email'),
							  array('column' => 'salesman','text' => 'Salesperson'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Vehicle'),
							  array('column' => 'trim','text' => 'Trim'),
							  array('column' => 'style','text' => 'Style'),
							  array('column' => 'vehicle_type','text' => 'Type'),
							  array('column' => 'serviceDate','text' => 'Service Date'),
							  array('column' => 'deliveryDate','text' => 'Delivery Date'),
							  array('column' => 'vin','text' => 'VIN'),
							  array('column' => 'stock','text' => 'Stock'),
							  array('column' => 'regNum','text' => 'Reg Num'),
							  array('column' => 'newUsed','text' => 'New/Used'),
							  array('column' => 'financed','text' => 'Financed'),
							  array('column' => 'term','text' => 'Term'),
							  array('column' => 'rate','text' => 'Rate'),
							  array('column' => 'monthlyPayment','text' => 'Monthly Payment'),
							  array('column' => 'mainPhoneNDNC','text' => 'Main Phone NDNC'),
							  array('column' => 'businessPhoneNDNC','text' => 'Business Phone NDNC'),
							  array('column' => 'mobilePhoneNDNC','text' => 'Cell Phone NDNC'),
							  array('column' => 'doNotCall','text' => 'Do Not Call'),
							  array('column' => 'doNotEmail','text' => 'Do Not Email'),
							  array('column' => 'doNotMail','text' => 'Do Not Mail'));
	}
	else if(isset($_GET['stock']))
	{
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'city','text' => 'City'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'stock','text' => 'Stock'),
							  array('column' => 'salesman','text' => 'Sales Rep'));
	}
	else if(isset($_GET['reg']))
	{
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'city','text' => 'City'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'regNum','text' => 'Reg Number'),
							  array('column' => 'salesman','text' => 'Sales Rep'));
	}
	else if(isset($_GET['email_to_mail']))
	{
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'companyName','text' => 'Notes'),
							  array('column' => 'assignedSalesman','text' => 'Sales Rep'));
	}
	else if(isset($_GET['fni']))
	{
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'term','text' => 'Term'),
					 		  array('column' => 'rate','text' => 'Rate'),
					 		  array('column' => 'lastPaymentDate','text' => 'Last Payment Date'),
							  array('column' => 'companyName','text' => 'Notes'),
							  array('column' => 'salesman','text' => 'Sales Rep'));
	}
	else
	{
		$columnsArray = array(array('column' => 'lastname','text' => 'Last Name'),
							  array('column' => 'firstname','text' => 'First Name'),
							  array('column' => 'mainPhone','text' => 'Main Phone'),
							  array('column' => 'businessPhone','text' => 'Business Phone'),
							  array('column' => 'mobilePhone','text' => 'Mobile Phone'),
							  array('column' => 'year','text' => 'Year'),
							  array('column' => 'description','text' => 'Description'),
							  array('column' => 'companyName','text' => 'Notes'),
							  array('column' => 'salesman','text' => 'Sales Rep'));
	}


	$results = mysqli_query($db_data,$sql);

	$showNum = false;
	if(isset($_GET['showNum'])) $showNum = true;

	if(isset($_GET['showCount']))
	{
		echo mysqli_num_rows($results);
		exit;
	}

	/*if($_GET['fromContainer'] != '') $xls->send(str_replace(" ","",str_replace(" ","",$_SESSION['container']['dealerName'] . '-' . mysqli_num_rows($results)) . '-SalesRepPhoneList') . '.xls'); */
	if($_GET['fromContainer'] != '') $xls->send(str_replace(" ","",str_replace(" ","",$_SESSION['container']['dealerName'] . '-' . mysqli_num_rows($results)) . ($showNum ? '-s' : '') . (isset($_GET['email_to_mail']) ? '-E2M' : '') .  '-SalesRepPhoneList') . '.xls');

	$salesrepArray = array("0-orphan"=>array());
	while($row = mysqli_fetch_assoc($results))
	{
		if(isset($_GET['email_to_mail'])) $salesrep = mb_strtolower(substr(ltrim(trim($row['assignedSalesman']),'0'),0,31), 'UTF-8');
		else $salesrep = mb_strtolower(substr(ltrim(trim($row['salesman']),'0'),0,31), 'UTF-8');

		//Eric Added this line on Jan 7 2015. It is used to remove the Â from the salesman name. This caused an error in the tabs at the bottom of excel.
		$salesrep = iconv('UTF-8', 'ASCII//IGNORE', $salesrep);

		$salesrep = str_replace('*','_',$salesrep);
		$salesrep = str_replace('/','_',$salesrep);
		if($salesrep == '') $salesrep = '0-orphan';
		if(isset($_GET['orphansOnly'])) $salesrep = '0-orphan';
		$salesrepArray[ajaxHTML($salesrep)][] = $row;
	}

	/*
	//SALESREP WITH LESS THAN 20 IS ORPHANS
	foreach($salesrepArray as $salesrep => $list)
	{
		if($salesrep != 'orphan' && count($list) < 20)
		{
			$salesrepArray['orphan'] = array_merge($salesrepArray['orphan'],$salesrepArray[$salesrep]);
			unset($salesrepArray[$salesrep]);
		}
	}
	*/

	ksort($salesrepArray);
	foreach($salesrepArray as $salesrep => $list)
	{
		$sheet =& $xls->addWorksheet(stripcslashes(stripslashes($salesrep)));
		$sheet->setInputEncoding('utf-8');

		$colCount = 0;
		foreach($columnsArray as $column)
		{
			if($column['column'] == 'salesman')
				$sheet->write(0,$colCount,' ',$bold);
			else
				$sheet->write(0,$colCount,trim($column['text']),$bold);
			$colCount++;
		}

		$isGrey = false;
		$isRed = false;
		$rowCount = 1;
		foreach($list as $cus)
		{
			if($isGrey)
			{
				$font = $white;
				$isGrey = false;
			}
			else
			{
				$font = $grey;
				$isGrey = true;
			}

			$colCount = 0;
			foreach($columnsArray as $column)
			{
				$displayFont = $font;
				if(in_array($column['column'],array("mainPhone","businessPhone","mobilePhone")) && trim($cus[$column['column']]) != "" && $cus['doNotCall'] == 'yes')
				{
					//$displayFont = $red;
					//$isRed = true;
					if($showNum) $cus[$column['column']] = 'DNC-' . $cus[$column['column']];
					else $cus[$column['column']] = 'DNC';
				}
				else if(in_array($column['column'],array("mainPhone","businessPhone","mobilePhone")) && trim($cus[$column['column']]) != "" && ($cus[$column['column'].'NDNC'] == 'yes' || $cus[$column['column'].'NDNC'] == '2'))
				{
					//$displayFont = $blue;
					//$isDnc = true;

					if(!($cus['serviceDate'] >= date("Y-m-d",strtotime("now - 17 months")) || $cus['deliveryDate'] >= date("Y-m-d",strtotime("now - 17 months"))))
					{
						if($showNum) $cus[$column['column']] = 'NDNC-' . $cus[$column['column']];
						else $cus[$column['column']] = 'NDNC';
					}
				}

				if($column['column'] == 'salesman')
					$sheet->write($rowCount,$colCount,' ',$displayFont);
				else if($column['column'] == 'firstname')
					$sheet->write($rowCount,$colCount,($cus['salutation'] != "" ? $cus['salutation'] .' '. $cus['firstname'] : $cus['firstname'] ),$displayFont);
				else if($column['column'] == 'lastname')
					$sheet->write($rowCount,$colCount,($cus['lastname'] == "" ? $cus['companyName'] : $cus['lastname']),$displayFont);
				else if($column['column'] == 'companyName')
					$sheet->write($rowCount,$colCount,' ',$displayFont);
				else if($column['column'] == 'description')
					$sheet->write($rowCount,$colCount,$cus['description'] . ($cus['regNum'] == "" ? "" : ' Reg#' . $cus['regNum']),$displayFont);
				else
					$sheet->write($rowCount,$colCount,trim($cus[$column['column']]),$displayFont);

				$colCount++;
			}

			$rowCount++;
		}
		$sheet->setLandscape();
		$sheet->hideGridlines ();
		$sheet->setMargins (0);
		$sheet->setMarginTop (0.75);
		$sheet->setMarginBottom (0.4);

		$sheet->setHeader ('The Private Sale - ' . $agreementOwner['dealerName'] . ': ' . $salesrep,0.5);
		$sheet->setFooter (date("M Y"),0);
		$sheet->setColumn (0, 0, 20);
		$sheet->setColumn (1, 1, 12);

		if($showNum)$sheet->setColumn (2, 4, 20);
		else $sheet->setColumn (2, 4, 12);

		$sheet->setColumn (5, 5, 5);
		$sheet->setColumn (6, 6, 20);

		if($showNum) $sheet->setColumn (7, 7, 0);
		else $sheet->setColumn (7, 7, 25);

		$sheet->setColumn (8, 8, 6);
	}
	$xls->close();
?>