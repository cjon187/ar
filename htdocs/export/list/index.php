<?php
	//echo 'LOC: ' . setlocale(LC_ALL,"0");exit;
	include_once('arSession.php');

	include_once "Spreadsheet/Excel/Writer.php";
	include_once("loginUtils.php");
	include_once("mysqliUtils.php");
	include_once("displayUtils.php");
	include_once("taskUtils.php");
	include_once("paymentUtils.php");
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
	else if(isset($_GET['forARC'])){  //pass in DEalerID
		echo 'List no longer available';
		exit;
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
				$taskObj = TaskInvitations::byId($task['taskID']);
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
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $task['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ') AND ((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
					ORDER BY lastname,firstname';
				}
				else if($_GET['last18Months'] == 'no')
				{
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $task['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ') AND !((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
					ORDER BY lastname,firstname';
				}
				else
				{
					$sql = 'SELECT *,contactID as pieceID FROM ps_dealer_' . $task['dealerID'] . '_contacts
					WHERE contactID in (' . implode(',',$contactIDs) . ')
					ORDER BY lastname,firstname';
				}
			}
		}
	}

	$dealerObj = Dealer::byId($dealerInfo['dealerID']);

	$nameTag = 'Mailed';
	$columnsArray = array();
	$columnsArray[] = array('column' => 'pieceID','text' => (isset($_GET['csv']) ? 'PieceID' : 'Contact ID'));
	$columnsArray[] = array('column' => 'seqNum','text' => 'Seq. #');
	//$columnsArray[] = array('column' => 'dCode','text' => 'Dynamic Code');
	$columnsArray[] = array('column' => 'arCode','text' => 'AR Code');
	$columnsArray[] = array('column' => 'customerCode','text' => 'Customer Code');
	$columnsArray[] = array('column' => 'companyName','text' => 'Company Name');
	$columnsArray[] = array('column' => 'gender','text' => 'Gender');
	$columnsArray[] = array('column' => 'salutation','text' => 'Salutation');
	$columnsArray[] = array('column' => 'firstname','text' => 'First Name');
	$columnsArray[] = array('column' => 'lastname','text' => 'Last Name');
	$columnsArray[] = array('column' => 'address1','text' => 'Address 1');
	$columnsArray[] = array('column' => 'address2','text' => 'Address 2');
	if($dealerInfo['nation'] == 'uk')
	{
		$columnsArray[] = array('column' => 'address3','text' => 'Address 3');
		$columnsArray[] = array('column' => 'address4','text' => 'Address 4');
		$columnsArray[] = array('column' => 'address5','text' => 'Address 5');
		$nameTag = 'UK';
	}
	$columnsArray[] = array('column' => 'city','text' => 'City');
	$columnsArray[] = array('column' => 'province','text' => 'Province');
	$columnsArray[] = array('column' => 'postalCode','text' => 'Postal Code');
	$columnsArray[] = array('column' => 'country', 'text' => 'Country');
	$columnsArray[] = array('column' => 'mainPhone','text' => 'Main Phone');
	$columnsArray[] = array('column' => 'businessPhone','text' => 'Business Phone');
	$columnsArray[] = array('column' => 'mobilePhone','text' => 'Mobile Phone');
	$columnsArray[] = array('column' => 'email','text' => 'Email');

	if(isset($_GET['email_to_mail']))
	{
		$columnsArray[] = array('column' => 'assignedSalesman','text' => 'Assigned Salesperson');
		$columnsArray[] = array('column' => 'assignedSalesmanFirstName','text' => 'Assigned Salesperson First Name');
		$nameTag = 'E2M-Mailed';
	}
	else
	{
		if(!isset($_GET['csv'])){
			$columnsArray[] = array('column' => 'salesman','text' => 'Salesperson');
			$columnsArray[] = array('column' => 'salesmanFirstName','text' => 'Salesperson First Name');
		}
	}

	if(isset($_GET['serviceContract'])){
		$columnsArray[] = array('column' => 'serviceContract', 'text' => 'Service Contract');
		$columnsArray[] = array('column' => 'serviceContractDollars', 'text' => 'Service Contract Dollars');
		$columnsArray[] = array('column' => 'serviceContractCents', 'text' => 'Service Contract Cents');
	}

	$columnsArray[] = array('column' => 'year','text' => 'Year');
	$columnsArray[] = array('column' => 'description','text' => 'Vehicle');
	$columnsArray[] = array('column' => 'trim','text' => 'Trim');
	$columnsArray[] = array('column' => 'style','text' => 'Style');
	$columnsArray[] = array('column' => 'vehicle_type','text' => 'Type');
	$columnsArray[] = array('column' => 'serviceDate','text' => 'Service Date');
	$columnsArray[] = array('column' => 'deliveryDate','text' => 'Delivery Date');
	$columnsArray[] = array('column' => 'vin','text' => 'VIN');
	$columnsArray[] = array('column' => 'stock','text' => 'Stock');
	$columnsArray[] = array('column' => 'regNum','text' => 'Reg Number');
	$columnsArray[] = array('column' => 'newUsed','text' => 'New/Used');
	$columnsArray[] = array('column' => 'financed','text' => 'Financed');
	$columnsArray[] = array('column' => 'term','text' => 'Term');
	$columnsArray[] = array('column' => 'rate','text' => 'Rate');
	$columnsArray[] = array('column' => 'firstnamePossess','text' => 'First Name Possess');
	$columnsArray[] = array('column' => 'descriptionPlural','text' => 'Vehicle Plural');
	$columnsArray[] = array('column' => 'monthlyPayment','text' => 'Payment');
	$columnsArray[] = array('column' => 'mainPhoneNDNC','text' => 'Main Phone NDNC');
	$columnsArray[] = array('column' => 'businessPhoneNDNC','text' => 'Business Phone NDNC');
	$columnsArray[] = array('column' => 'mobilePhoneNDNC','text' => 'Cell Phone NDNC');
	$columnsArray[] = array('column' => 'doNotCall','text' => 'Do Not Call');
	$columnsArray[] = array('column' => 'doNotEmail','text' => 'Do Not Email');
	$columnsArray[] = array('column' => 'doNotMail','text' => 'Do Not Mail');
	$columnsArray[] = array('column' => 'doNotText','text' => 'Do Not Text');


	if(isset($_GET['allCaps'])){
		$nameTag .= '-AC';
		$columnsArray[] = array('column' => 'companyNameAllCaps','text' => 'Company Name All Caps');
		$columnsArray[] = array('column' => 'firstNameAllCaps','text' => 'First Name All Caps');
		$columnsArray[] = array('column' => 'lastNameAllCaps','text' => 'Last Name All Caps');
		$columnsArray[] = array('column' => 'descriptionAllCaps','text' => 'Description All Caps');
	}

	if(isset($_GET['salesmanCaps'])){
		if(isset($_GET['email_to_mail'])){
			$columnsArray[] = array('column' => 'assignedSalesmanAllCaps','text' => 'Assigned Salesman All Caps');
			$columnsArray[] = array('column' => 'assignedSalesmanFirstNameAllCaps','text' => 'Assigned Salesman First Name All Caps');
		}
		else
		{
			$columnsArray[] = array('column' => 'salesmanAllCaps','text' => 'Salesman All Caps');
			$columnsArray[] = array('column' => 'salesmanFirstNameAllCaps','text' => 'Salesman First Name All Caps');
		}

	}
	/*
	if(isset($_GET['pullAhead']))
	{
		if($dealerInfo['promoCode'] == "")
		{
			?>
			<script>
				alert('Two-Digit Dealer Promo Code is missing.');
				location.href="../index.php?s1=container";
			</script>
			<?php
			exit;
		}


		include_once( "class.amort.php");
		$columnsArray[] = array('column' => 'NewYear','text' => 'New Year');
		$columnsArray[] = array('column' => 'pullAheadVehicle','text' => 'Pull-Ahead Vehicle');
		$columnsArray[] = array('column' => 'pullAheadImage','text' => 'Pull-Ahead Image');
		$nameTag = 'PullAhead';
	}
	*/

	if(isset($_GET['cbb']) || isset($_GET['vee']))
	{
		$columnsArray[] = array('column' => 'cbb_exclean','text' => 'CBB Extra Clean');
		$columnsArray[] = array('column' => 'cbb_clean','text' => 'CBB Clean');
		$columnsArray[] = array('column' => 'cbb_average','text' => 'CBB Average');
		$columnsArray[] = array('column' => 'cbb_rough','text' => 'CBB Rough');
		$columnsArray[] = array('column' => 'cbb_exclean_text','text' => 'CBB Extra Clean Text');
		$columnsArray[] = array('column' => 'cbb_clean_text','text' => 'CBB Clean Text');
		$columnsArray[] = array('column' => 'cbb_average_text','text' => 'CBB Average Text');
		$columnsArray[] = array('column' => 'cbb_rough_text','text' => 'CBB Rough Text');
		$columnsArray[] = array('column' => 'price','text' => 'Price');
		$columnsArray[] = array('column' => 'financedAmount','text' => 'Financed Amount');
		$columnsArray[] = array('column' => 'cashDown','text' => 'Cash Down');
		$columnsArray[] = array('column' => 'bank','text' => 'Bank');
		$columnsArray[] = array('column' => 'lastPaymentDate','text' => 'Last Payment Date');
		$columnsArray[] = array('column' => 'buyout','text' => 'Buyout');
		$columnsArray[] = array('column' => 'netTradeIn','text' => 'Equity');
		$columnsArray[] = array('column' => 'cbb_value','text' => 'CBB Value');
		$columnsArray[] = array('column' => 'cbb_text','text' => 'CBB Text');

		$nameTag = 'Equity';
	}
	if(isset($_GET['vee']))
	{
		include_once( "class.amort.php");
		$columnsArray[] = array('column' => 'make','text' => 'Make');
		$columnsArray[] = array('column' => 'model','text' => 'Model');
		$columnsArray[] = array('column' => 'milesPerYear','text' => 'Estimated Mileage');
		$columnsArray[] = array('column' => 'NewYear','text' => 'New Year');
		$columnsArray[] = array('column' => 'NewMake','text' => 'New Make');
		$columnsArray[] = array('column' => 'NewModel','text' => 'New Model');
		$columnsArray[] = array('column' => 'NewBiWeekly0Down','text' => 'New BiWeekly Zero Down');
		$columnsArray[] = array('column' => 'NewBiWeekly2500CashBack','text' => 'New BiWeekly 2500 Cash Back');
		$columnsArray[] = array('column' => 'NewBiWeekly1000Down','text' => 'New BiWeekly 1000 Deposit');
		$nameTag = 'VEE';
	}

	if(isset($_GET['suly']))
	{
		$columnsArray = array(array('column' => 'pieceID','text' => 'NoClient'),
							  array('column' => 'companyName','text' => 'Compagnie'),
							  array('column' => 'lastname','text' => 'Nom'),
							  array('column' => 'firstname','text' => 'Prenom'),
							  array('column' => 'address1','text' => 'Adresse'),
							  array('column' => 'city','text' => 'Ville'),
							  array('column' => 'province','text' => 'Province'),
							  array('column' => 'postalCode','text' => 'CodePostal'),
							  array('column' => 'mainPhone','text' => 'TelResidence'),
							  array('column' => 'businessPhone','text' => 'TelBureau'),
							  array('column' => 'color','text' => 'ExtBureau'),
							  array('column' => 'email','text' => 'Email'),
							  array('column' => 'color','text' => 'Langue'),
							  array('column' => 'vin','text' => 'NoSerie'),
							  array('column' => 'make','text' => 'Marque'),
							  array('column' => 'description','text' => 'Modele'),
							  array('column' => 'year','text' => 'Annee'),
							  array('column' => 'color','text' => 'A/L/S'),
							  array('column' => 'newUsed','text' => 'EtatVehicule'),
							  array('column' => 'deliveryDate','text' => 'DateAchat'),
							  array('column' => 'term','text' => 'Terme'),
							  array('column' => 'lastPaymentDate','text' => 'DateFinTerme'),
							  array('column' => 'salesman','text' => 'Representant'),
							  array('column' => 'serviceDate','text' => 'DerniereVisite'),
							  array('column' => 'mobilePhone','text' => 'TelMobile'),
							  array('column' => 'mainPhoneNDNC','text' => 'Main Phone NDNC'),
							  array('column' => 'businessPhoneNDNC','text' => 'Business Phone NDNC'),
							  array('column' => 'mobilePhoneNDNC','text' => 'Cell Phone NDNC'),
							  array('column' => 'doNotCall','text' => 'Do Not Call'));

		$nameTag = 'SULY';
	}

	if(isset($_GET['forARC']))
	{
		$nameTag = 'ARCData-GradeC';
		unset($columnsArray[33]);
		unset($columnsArray[32]);
		unset($columnsArray[31]);
		unset($columnsArray[30]);
		unset($columnsArray[29]);
		unset($columnsArray[27]);
		unset($columnsArray[17]);
		unset($columnsArray[3]);
		unset($columnsArray[1]);
		unset($columnsArray[0]);
	}

	if(isset($_GET['showDNM'])) $nameTag = 'DNM-Mailed';

	$results = mysqli_query($db_data,$sql);


	$xls =& new Spreadsheet_Excel_Writer();
	$xls->setVersion(8);

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

	if(isset($_GET['showCount']))
	{
		echo mysqli_num_rows($results);
		exit;
	}

	$filename = str_replace(" ","",str_replace(" ","",$dealerInfo['dealerName']) . '-'. mysqli_num_rows($results)) . '-' . $nameTag;
	if($_GET['fromContainer'] != '') $xls->send($filename . '.xls');
	else if(isset($_GET['forARC'])) $xls->send($filename . '.xls');
	$sheet =& $xls->addWorksheet('List');
	$sheet->setInputEncoding('utf-8');

	$colCount = 0;
	foreach($columnsArray as $column)
	{
		$sheet->write(0,$colCount,trim($column['text']));
		$rows[0][] = trim($column['text']);
		$colCount++;
	}

	$white =& $xls->addFormat();

	$updated =& $xls->addFormat();
	$updated->setFGColor('yellow');

	$dnc =& $xls->addFormat();
	$dnc->setFGColor('red');

	$ndnc =& $xls->addFormat();
	$ndnc->setFGColor('yellow');

	$rowCount = 1;

	$seqNum = 10000;
	$tArray = array();

	$lastID = '';
	while($row = mysqli_fetch_assoc($results))
	{
		$row['seqNum'] = $seqNum;
		$seqNum++;

		$tArray[] = $row;
		$lastID = max($lastID,$row['pieceID']);
	}

	$johnSmith = array();
	if($dealerInfo['nation'] == 'us') $johnSmith = array('address1'=>'P.O. Box 998', 'city'=> 'Lynden', 'province'=> 'WA', 'postalCode'=> '98264');
	else $johnSmith = array('address1'=>'104 - 2677 192nd Street', 'city'=> 'Surrey', 'province'=> 'BC', 'postalCode'=> 'V3S 3X1');

	if(isset($_GET['csv']))
	{
		$numberArray = ['999981','999982','999983','999984','999985'];
		for($i = 1;$i <= 5;$i++) array_unshift($tArray, array('pieceID' => $numberArray[$i-1], 'seqNum' => '', 'firstname' => 'John', 'lastname' => 'Smith', 'address1' => $johnSmith['address1'], 'city' => $johnSmith['city'], 'province' => $johnSmith['province'], 'postalCode' => $johnSmith['postalCode'], 'year' => date("Y"), 'description' => 'MyVehicle'));
	}
	if(  ($_GET['plusFive']=="yes")   ||   ($dealerInfo['nation'] == 'us' && isset($_GET['email_to_mail']) && isset($_GET['csv']))   ){
		$numberArray = ['999986','999987','999988', '999989','999990'];
		for($i = 1;$i <= 5;$i++) array_unshift($tArray, array('pieceID' => $numberArray[$i-1], 'seqNum' => '', 'firstname' => 'John', 'lastname' => 'Smith', 'address1' => $johnSmith['address1'], 'city' => $johnSmith['city'], 'province' => $johnSmith['province'], 'postalCode' => $johnSmith['postalCode'], 'year' => date("Y"), 'description' => 'MyVehicle'));
	}
	if($dealerInfo['nation'] == 'ca')
	{
		$tArray[] = array('pieceID' => '999991', 'seqNum' => $seqNum, 'firstname' => 'Absolute Results', 'address1' => '104 - 2677 192nd Street', 'city' => 'Surrey', 'province' => 'BC', 'postalCode' => 'V3S 3X1', 'year' => date("Y"), 'description' => 'MyVehicle');
		$seqNum++;
	}

	$dealerStaff = mysqli_fetch_assoc(mysqli_query($db_data,'SELECT * FROM ps_dealerstaff WHERE status = 1 AND dealerID=' . $dealerInfo['dealerID'] . ' AND email not like "%@chrysler.com" ORDER BY primaryContact DESC, dealerStaffID ASC'));
	if($dealerStaff != "")
	{
		$tArray[] = array('pieceID' => '999992','seqNum' => $seqNum, 'firstname' => $dealerStaff['name'], 'address1' => $dealerInfo['address'], 'city' => $dealerInfo['city'], 'province' => $dealerInfo['province'], 'postalCode' => $dealerInfo['postalCode'], 'year' => date("Y"), 'description' => 'MyVehicle');
		$seqNum++;
	}

	if($dealerInfo['dealerID'] == 519)
	{
		$tArray[] = array('pieceID' => '999993', 'seqNum' => $seqNum, 'firstname' => 'Christine', 'lastname' => 'Barisheff', 'address1' => '3 Glenellen Dr E', 'city' => 'Toronto', 'province' => 'ON', 'postalCode' => 'M8Y 2G4');
		$seqNum++;
	}
	//Added by Eric on Oct 16 2014. Add andrea UK to get mailed from all UK lists
	if($dealerInfo['nation'] == 'uk'){
		$tArray[] = array('pieceID' => '999994', 'seqNum' => $seqNum, 'firstname' => 'Andrea', 'lastname' => 'Owen', 'address1' => '191 Penstone Court', 'city' => 'Cardiff', 'postalCode' => 'CF10 5NQ');
	}

	//Added by Eric on FEB 10 2015. Capital Dodge Ottowa requested a second contact recieves the mail
	if($dealerInfo['dealerID'] == 319)
	{
		$tArray[] = array('pieceID' => '999995', 'seqNum' => $seqNum, 'firstname' => 'Bianca', 'lastname' => 'McCartney', 'address1' => '2500 Palladium Drive Unit 1200', 'city' => 'Kanata', 'province' => 'ON', 'postalCode' => 'K2V 1E2', 'year' => date("Y"), 'description' => 'MyVehicle');
	}

	//Added by Eric on JUN 23 2015. Unique Chrysler & Parkway Chrysler requested a second contact recieves the mail
	if($dealerInfo['dealerID'] == 496 || $dealerInfo['dealerID'] == 460)
	{
		$tArray[] = array('pieceID' => '999995', 'seqNum' => $seqNum, 'firstname' => 'Andrew', 'lastname' => 'Slomka', 'address1' => '4315 North Service Road', 'city' => 'Burlington', 'province' => 'ON', 'postalCode' => 'L7L 4X7', 'year' => date("Y"), 'description' => 'MyVehicle');
	}

	//Added by Eric On March 25 2015
	if($dealerInfo['nation'] == 'us'){
		$tArray[] = array('pieceID' => '999996', 'seqNum' => $seqNum, 'firstname' => 'Absolute Results Marketing', 'address1' => 'P.O. Box 998', 'city' => 'Lynden', 'province' => 'WA', 'postalCode' => '98264', 'year' => date("Y"), 'description' => 'MyVehicle');
	}

	if($dealerObj->countryID == COUNTRY_DE && $dealerObj->oems[0]->id == OEM_FCA) {
		$tArray[] = array('pieceID' => '999996', 'seqNum' => $seqNum, 'firstname' => 'Stephan', 'lastname' => 'Kammerer', 'address1' => 'Willhorner heide 28', 'city' => 'Ellerbek', 'postalCode' => '25474', 'year' => date("Y"), 'description' => 'MyVehicle');
	}

	if($dealerObj->countryID == COUNTRY_FR) {
		$fca = false;
		foreach($dealerObj->oems as $oem){
			if($oem->id == OEM_FCA){
				$fca = true;
			}
		}
		if($fca){
			$tArray[] = array('pieceID' => '9999980', 'seqNum' => $seqNum, 'firstname' => 'Thomas', 		'lastname' => 'Bauchet', 		'address1' => '6 rue de l\'île de France', 		'city' => 'Saint Germain de la Grange', 'postalCode' => '78640', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999983', 'seqNum' => $seqNum, 'firstname' => 'Arnaud', 		'lastname' => 'Dorard', 		'address1' => '10 rue Ernest Laval', 			'city' => 'Vanves', 					'postalCode' => '92170', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999985', 'seqNum' => $seqNum, 'firstname' => 'Anne', 			'lastname' => 'Masclaux', 		'address1' => '6 rue Nicolas Copernic', 		'city' => 'Trappes', 					'postalCode' => '78190', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999986', 'seqNum' => $seqNum, 'firstname' => 'Sébastien', 		'lastname' => 'Perrais', 		'address1' => '21 rue de l\'ancienne Mairie',   'city' => 'Boulogne', 					'postalCode' => '92100', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999987', 'seqNum' => $seqNum, 'firstname' => 'Jean-Philippe',  'lastname' => 'Vautier', 		'address1' => '6 place de la cimballe', 		'city' => 'Jouars Pontchartrain',		'postalCode' => '78760', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999988', 'seqNum' => $seqNum, 'firstname' => 'Matthieu', 		'lastname' => 'Berne',			'address1' => '131, rue de Fontenay ', 			'city' => 'Vincennes', 					'postalCode' => '94300', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999989', 'seqNum' => $seqNum, 'firstname' => 'Olivier', 		'lastname' => 'Bouelharrag', 	'address1' => '76 Rue Jules Guesde', 			'city' => 'Levallois Perret', 			'postalCode' => '92300', 'year' => date("Y"), 'description' => 'MyVehicle');
			$tArray[] = array('pieceID' => '9999990', 'seqNum' => $seqNum, 'firstname' => 'Etienne',		'lastname' => 'Levoye', 		'address1' => '63 rue Nationale', 				'city' => 'Boulogne',					'postalCode' => '92100', 'year' => date("Y"), 'description' => 'MyVehicle');
		}
	}


	foreach($tArray as $row)
	{
		$colCount = 0;
		$font = $white;

		$row['companyName'] = mb_convert_case(mb_strtolower($row['companyName'],'UTF-8'), MB_CASE_TITLE, "UTF-8");
		$row['firstname'] = formatNames($row['firstname']);
		$row['lastname'] = formatNames($row['lastname']);


		$row['description'] = mb_convert_case(mb_strtolower($row['description'],'UTF-8'), MB_CASE_TITLE, "UTF-8");
		$row['city'] = mb_convert_case(mb_strtolower($row['city'],'UTF-8'), MB_CASE_TITLE, "UTF-8");
		$row['address1'] = mb_convert_case(mb_strtolower($row['address1'],'UTF-8'), MB_CASE_TITLE, "UTF-8");
		//$row['address1'] = iconv('UTF-8', 'ASCII//TRANSLIT', $row['address1']);


		$row['salesman'] = mb_convert_case(mb_strtolower($row['salesman'],'UTF-8'), MB_CASE_TITLE, "UTF-8");
		$row['assignedSalesman'] = mb_convert_case(mb_strtolower($row['assignedSalesman'],'UTF-8'), MB_CASE_TITLE, "UTF-8");

		if($row['make'] != "" && substr_count(' ' . $row['description'] , ' ' . $row['make']) > 1)
		{
			$pos = strpos(' ' . $row['description'],' ' . $row['make']);
			if($pos !== false)  $row['description'] = trim(substr_replace(' ' . $row['description'],'',$pos,strlen(' ' . $row['make'])));
		}

		$cap = array('RR','NE','SE','SW','NW');
		foreach($cap as $txt)
		{
			$row['address1'] = trim(str_replace ( ucwords(strtolower(' ' . $txt . ' ')) , strtoupper(' ' . $txt . ' ') , ' ' . $row['address1'] . ' '));
			$row['address2'] = trim(str_replace ( ucwords(strtolower(' ' . $txt . ' ')) , strtoupper(' ' . $txt . ' ') , ' ' . $row['address2'] . ' '));
		}

		if(isset($_GET['pbi']))
		{
			if($row['firstname'] == '' && $row['lastname'] == '') $row['firstname'] = $row['companyName'];
		}

		if(!isset($_GET['showDNM']) && $row['doNotMail'] == 'yes')
		{
			$row['address1'] = 'DNM';
			$row['address2'] = 'DNM';
			$row['city'] = 'DNM';
			$row['province'] = 'DNM';
			$row['postalCode'] = 'DNM';
			$row['country'] = 'DNM';
		}

		if($dealerInfo['nation'] == 'uk')
		{
			$tArray = explode(',',$row['address1']);

			$row['address1'] = $tArray[0];
			$row['address2'] = trim($row['address2'] . ' ' . $tArray[1]);
			$row['address3'] = $tArray[2];
			$row['address4'] = $tArray[3];
			$row['address5'] = $tArray[4];
		}

		foreach($columnsArray as $column)
		{
			$displayFont = $font;
			if(in_array($column['column'],array("mainPhone","businessPhone","mobilePhone")) && trim($row[$column['column']]) != "" && $row['doNotCall'] == 'yes')
			{
				$row[$column['column']] = 'DNC - ' . $row[$column['column']];
				$displayFont = $dnc;
				$isDnc = true;
			}
			else if(in_array($column['column'],array("mainPhone","businessPhone","mobilePhone")) && trim($row[$column['column']]) != "" && ($row[$column['column'].'NDNC'] == 'yes' || $row[$column['column'].'NDNC'] == '2'))
			{
				if(!($row['serviceDate'] >= date("Y-m-d",strtotime("now - 18 months")) || $row['deliveryDate'] >= date("Y-m-d",strtotime("now - 18 months"))))
				{
					$row[$column['column']] = 'NDNC - ' . $row[$column['column']];
					$displayFont = $ndnc;
					$isDnc = true;
				}
			}

			if($column['column'] == 'firstnamePossess')
			{
				if(trim($row['firstname']) == '')
				{
					if(substr(trim($row['companyName']),-1) == 's')	$row[$column['column']] = trim($row['companyName']) . "'";
					else $row[$column['column']] = trim($row['companyName']) . "'s";
				}
				else if(substr(trim($row['firstname']),-1) == 's')	$row[$column['column']] = trim($row['firstname']) . "'";
				else $row[$column['column']] = trim($row['firstname']) . "'s";
			}

			if($column['column'] == 'descriptionPlural')
			{
				if(trim($row['description']) == '') $row[$column['column']] = '';
				else if(substr(trim($row['description']),-1) == 's')	$row[$column['column']] = trim($row['description']) . 'es';
				else $row[$column['column']] = trim($row['description']) . 's';
			}

			if($column['column'] == 'salesmanFirstName')
			{
				if(stripos($row['salesman'],',') !== false) $row[$column['column']] = array_shift(explode(' ',trim(array_pop(explode(',',$row['salesman'])))));
				else $row[$column['column']] = array_shift(explode(' ',trim($row['salesman'])));
			}

			if($column['column'] == 'assignedSalesmanFirstName')
			{
				if(stripos($row['assignedSalesman'],',') !== false) $row[$column['column']] = array_shift(explode(' ',trim(array_pop(explode(',',$row['assignedSalesman'])))));
				else $row[$column['column']] = array_shift(explode(' ',trim($row['assignedSalesman'])));
			}

			if($column['column'] == 'img')
			{
				$oldFolder = '//SVDC/absolute shared/Absolute Results ARTWORK/Creative/Variable Images/Old Vehicles';
				$newFolder = '//SVDC/absolute shared/Absolute Results ARTWORK/Creative/Variable Images/2013 New Vehicles';

				if(!isset($_SESSION['container']['vimage']))
				{
					$_SESSION['container']['vimage']['old'] = listFolderFiles($oldFolder);
					$_SESSION['container']['vimage']['new'] = listFolderFiles($newFolder);
				}

				if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'1500') !== false) $model = 'ram1500';
				else if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'2500') !== false) $model = 'ram2500';
				else if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'3500') !== false) $model = 'ram3500';
				else $model = $row['model'];

				$row['img'] .= $row['year'] . '_' . $model;

				if(stripos(strtolower($row['style']),'4dr') !== false) $row['img'] .= '_4dr';
				else if(stripos(strtolower($row['style']),'2dr') !== false) $row['img'] .= '_2dr';
				else if(stripos(strtolower($row['style']),'4 dr') !== false) $row['img'] .= '_4dr';
				else if(stripos(strtolower($row['style']),'2 dr') !== false) $row['img'] .= '_2dr';
				else $row['img'] .= '_4dr';

				$row['img'] = strtolower(str_replace(' ','',$row['img']).'.jpg');

				if(in_array($row['img'],array_keys($_SESSION['container']['vimage']['old'])))
				{
					$row['img'] = substr($_SESSION['container']['vimage']['old'][$row['img']],73,strlen($_SESSION['container']['vimage']['old'][$row['img']]));
				}
				else
				{
					$sql = 'SELECT replace(concat(path,"jpeg/",media_file_prefix,"_",lower(left(mfg_color_name,locate("|",mfg_color_name)-1)))," ","") as img FROM
							    (SELECT * FROM
							    (SELECT vin_pattern,vehicle_id FROM ps_vin WHERE vin_pattern = "' . $row['vin_pattern'] . '") as a1
							    INNER JOIN
							    (SELECT izmo_image_id,vehicle_id FROM ps_dataone_vehicleimage) as a2
							    USING
							    (vehicle_id)) as b1
							INNER JOIN
							    (SELECT* FROM ps_dataone_image) as b2
							USING
							    (izmo_image_id)';
					$stockImg = mysqli_fetch_assoc(mysqli_query($db_data,$sql));

					if($stockImg['img'] == '' || !file_exists('//SVDC/absolute shared/Absolute Results ARTWORK/Creative/Variable Images/stock vehicles/20' . substr($stockImg['img'],1,2) . $stockImg['img'] . '.jpg')) $row['img'] = '/old vehicles/beater/jpeg/1970_coronet_2dr.jpg';
					else $row['img'] = '/stock vehicles/20' . substr($stockImg['img'],1,2) . $stockImg['img'] . '.jpg';

				}
				$row['img'] = basename($row['img']);
			}
			if($column['column'] == 'newimg')
			{
				if(stripos(strtolower($row['description']),'caravan') !== false) $model = 'grandcaravan';
				else if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'1500') !== false) $model = 'ram1500';
				else if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'2500') !== false) $model = 'ram2500';
				else if(stripos(strtolower($row['description']),'ram') !== false && stripos(strtolower($row['description']),'3500') !== false) $model = 'ram3500';
				else $model = $row['model'];

				$row['newimg'] .= date("Y") . '_' . $model;

				if(stripos(strtolower($row['style']),'4dr') !== false) $row['newimg'] .= '_4dr';
				else if(stripos(strtolower($row['style']),'2dr') !== false) $row['newimg'] .= '_2dr';
				else if(stripos(strtolower($row['style']),'4 dr') !== false) $row['newimg'] .= '_4dr';
				else if(stripos(strtolower($row['style']),'2 dr') !== false) $row['newimg'] .= '_2dr';
				else $row['newimg'] .= '_4dr';
				$row['newimg'] = strtolower(str_replace(' ','',$row['newimg']).'.jpg');

				if(in_array($row['newimg'],array_keys($_SESSION['container']['vimage']['new'])))
				{
					$row['newimg'] = substr($_SESSION['container']['vimage']['new'][$row['newimg']],73,strlen($_SESSION['container']['vimage']['new'][$row['newimg']]));
				}
				else
				{

					if((stripos($row['description'],'caravan') !== false) || (stripos($row['description'],'country') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_grandcaravan_4dr.jpg';
					else if((stripos($row['description'],'wrangler') !== false) || (stripos($row['description'],'patriot') !== false) || (stripos($row['description'],'liberty') !== false) || (stripos($row['description'],'compass') !== false) || (stripos($row['description'],'nitro') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_Wrangler_2dr.jpg';
					else if((stripos($row['description'],'200') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_Wrangler_2dr.jpg';
					else if((stripos($row['description'],'journey') !== false) || (stripos($row['description'],'durango') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_Journey_4dr.jpg';
					else if((stripos($row['description'],'ram') !== false) || (stripos($row['description'],'dakota') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_ram1500_4dr.jpg';
					else if((stripos($row['description'],'cherokee') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_cherokee_4dr.jpg';
					else if((stripos($row['description'],'dart') !== false))
						$row['newimg'] = '/2013 new vehicles/2013_dart_4dr.jpg';
					else if($row['vehicle_type'] == 'Car') $row['newimg'] = '/2013 new vehicles/2013_dart_4dr.jpg';
					else if($row['vehicle_type'] == 'Van') $row['newimg'] = '/2013 new vehicles/2013_grandcaravan_4dr.jpg';
					else if($row['vehicle_type'] == 'Truck') $row['newimg'] = '/2013 new vehicles/2013_ram1500_4dr.jpg';
					else if($row['vehicle_type'] == 'SUV') $row['newimg'] = '/2013 new vehicles/2013_Journey_4dr.jpg';
					else $row['newimg'] = '/2013 new vehicles/2013_ram1500_4dr.jpg';
				}

				$row['newimg'] = basename($row['newimg']);
			}


			if(in_array($column['column'],array("NewYear")))
			{
				$newVehicle = findNewPayment($row,$dealerInfo['dealerID']);
				foreach($newVehicle as $key => $val) $row[$key] = $val;
			}

			if(in_array($column['column'],array("cbb_exclean_text","cbb_clean_text","cbb_average_text","cbb_rough_text")))
			{
				$valColumn = str_replace("_text","",$column['column']);
				$row[$column['column']] = ucwords(convert_number_to_words($row[$valColumn]));
			}

			if(in_array($column['column'], array('serviceContractDollars', 'serviceContractCents', 'serviceContract'))){
				if($column['column'] == "serviceContractDollars"){
					$row[$column['column']] = ucwords(convert_number_to_words(explode('.',str_replace(',','',$row['serviceContract']))[0] ) );
				}
				else if($column['column'] == "serviceContract"){
					$row[$column['column']] = number_format($row['serviceContract'],2) ;
				}
				else{
					$row[$column['column']] = "'" .explode('.',number_format(str_replace(',','',$row['serviceContract']),2))[1] ;
				}

			}

			if(in_array($column['column'],array("cbb_value")))
			{
				if($row['cbb_clean_text'] != "")
				{
					$row['cbb_value'] = $row['cbb_clean'];
					$row['cbb_text'] = $row['cbb_clean_text'];
				}
				else if($row['cbb_average_text'] != "")  {
					$row['cbb_value'] = $row['cbb_average'];
					$row['cbb_text'] = $row['cbb_average_text'];
				}
				else if($row['cbb_rough_text'] != "")  {
					$row['cbb_value'] = $row['cbb_rough'];
					$row['cbb_text'] = $row['cbb_rough_text'];
				}
			}

			if(in_array($column['column'],array("dCode")))
			{
				if($row['lastname'] == '') $row[$column['column']] = strtoupper(substr($row['companyName'],0,1).$row['contactID']);
				else $row[$column['column']] = strtoupper(substr($row['lastname'],0,1).$row['contactID']);
			}
			if(in_array($column['column'],array("arCode")))
			{
				if($row['lastname'] == '') $row[$column['column']] = mb_strtoupper($dealerInfo['promoCode'] . substr($row['companyName'],0,1).$row['contactID'],'UTF-8');
				else $row[$column['column']] = mb_strtoupper($dealerInfo['promoCode'] . mb_substr($row['lastname'],0,1,'UTF-8').$row['contactID'],'UTF-8');
			}

			if(isset($_GET['allCaps']))
			{
				$row['firstNameAllCaps'] = mb_strtoupper($row['firstname'],'UTF-8');
				$row['lastNameAllCaps'] = mb_strtoupper($row['lastname'],'UTF-8');
				$row['companyNameAllCaps'] = mb_strtoupper($row['companyName'],'UTF-8');
				$row['descriptionAllCaps'] = mb_strtoupper($row['description'],'UTF-8');
			}
			if(isset($_GET['salesmanCaps']))
			{
				if(isset($_GET['email_to_mail']))
				{
					$row['assignedSalesmanAllCaps'] = mb_strtoupper($row['assignedSalesman'],'UTF-8');
					$row['assignedSalesmanFirstNameAllCaps'] = mb_strtoupper($row['assignedSalesmanFirstName'],'UTF-8');
				}
				else
				{
					$row['salesmanAllCaps'] = mb_strtoupper($row['salesman'],'UTF-8');
					$row['salesmanFirstNameAllCaps'] = mb_strtoupper($row['salesmanFristName'],'UTF-8');
				}
			}

			if(in_array($column['column'],array("country"))){
				$row['country'] = $dealerInfo['country'];
			}

			if(!isset($_GET['csv'])){
				$sheet->write($rowCount,$colCount,trim($row[$column['column']]),$displayFont);
			}
			$rows[$rowCount][] = trim($row[$column['column']]);

			$colCount++;
		}

		$colCount++;
		$rowCount++;
	}


	if(isset($_GET['csv']))
	{
		ini_set("auto_detect_line_endings", true);
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $filename . '-CSV.csv');

		$output = fopen('php://output', 'w');
		$enclosure = '';
		//$enclosure = '"';


		include_once('csvUtils.php');
		foreach($rows as $row)
		{
			fwritecsv($output,$row,',',$enclosure);
		}
		exit;
	}


	$xls->close();
?>