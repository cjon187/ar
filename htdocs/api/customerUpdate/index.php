<?php
	$xml = new SimpleXMLElement('<CustomerUpdate/>');
	$input = array();
	foreach($_GET as $key => $val)
	{	    	              
		$input[stripslashes($key)] = strip_tags(stripslashes(urldecode($val)));		
	}
	
	if(count($input) == 6 && $input['firstname'] == $input['origfirstname'] && $input['lastname'] == $input['origlastname'])
	{
		$xml->Status->StatusCode = 'SUCCESS';
		$xml->Status->StatusMessage = 'No Update Necessary';
		echo trim($xml->asXML());
		exit;
	}
	
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	$db = new ARDB();
	$error = array();
	
	if(isset($input['dealerID'])) $dealerID = $input['dealerID'];
	else if(isset($input['eventID']))
	{
		$event = displayEventInfo($input['eventID']);
		$dealerID = $event['dealerID'];		
	}
	
	if($dealerID == "")
	{
		$error[] = 'Invalid Dealership';
	}
	
	$dealer = displayDealerInfo($dealerID);
	if($dealer['lockedBy'] != "")
	{
		$error[] = 'Dealership Database Locked';
		
		$sql = 'INSERT INTO ps_updatequeue (api) VALUES ("http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '")';
		mysqli_query($db_data,$sql);
	}
	

	if(count($error) > 0) {
		$xml->Status->StatusCode = 'ERROR';
		$xml->Status->StatusMessage = $error[0];
	} else {
		$dc = new DealerContactsController($dealerID);
		if($dc->updateCustomer($input)) {
			$xml->Status->StatusCode = 'SUCCESS';
			$xml->Status->StatusMessage = 'Record Successfully Updated';
		} else {
			$xml->Status->StatusCode = 'ERROR';
			$xml->Status->StatusMessage = $dc->errors;
		}
	}
	
	echo trim($xml->asXML());
	exit;
	/*
	if($input['contactID'] == "")
	{
		$error[] = 'Invalid Contact ID';
	}
	*/
	
	$tbl = 'ps_dealer_' . $dealerID . '_contacts';
	$sql = 'SELECT * FROM ' . $tbl . ' WHERE contactID = ' . $input['contactID'];
	$cus = mysqli_fetch_assoc(mysqli_query($db_data,$sql));
	if($cus['contactID'] == "")
	{		
		$sql = 'SELECT * FROM ' . $tbl . ' WHERE firstname = "' . $input['origfirstname'] . '" AND lastname = "' . $input['origlastname'] . '"';
		$cus = mysqli_fetch_assoc(mysqli_query($db_data,$sql));
	}
	
	if($cus['contactID'] == "" && isset($input['create']))
	{
		$sql = 'INSERT INTO ' . $tbl . ' (dealerID,firstname,lastname) VALUES (' . $dealerID . ',"' . $input['origfirstname'] . '","' . $input['origlastname'] . '")';
		if(mysqli_query($db_data,$sql))
		{
			$newID = mysqli_insert_id($db_data);
			$sql = 'UPDATE ' . $tbl . ' SET contactID=' . $newID . ',owner_contactID=' . $newID . ' WHERE id = ' . $newID;
			if(mysqli_query($db_data,$sql))
			{
				$sql = 'SELECT * FROM ' . $tbl . ' WHERE id = ' . $newID;
				$cus = mysqli_fetch_assoc(mysqli_query($db_data,$sql));
			}
		}
	}
	
	if($cus['contactID'] == "")
	{
		$error[] = 'No Contact Found';
	}
	
	if(count($error) > 0)
	{
		$xml->Status->StatusCode = 'ERROR';
		$xml->Status->StatusMessage = $error[0];
		echo trim($xml->asXML());
	}
	else
	{
	
		$addressChange = false;
		$sqlArray = array();
		$sqlArray[] = 'lastUpdated = "' . date("Y-m-d") . '"';
		if($input['arcUpdate'] != 'no') $sqlArray[] = 'arcUpdate = "yes"';
		
		if(isset($input['firstname'])) 	$sqlArray[] = 'firstname = "' . $input['firstname'] . '"';
		if(isset($input['lastname'])) 	$sqlArray[] = 'lastname = "' . $input['lastname'] . '"';
		
		if((isset($input['address1']) || isset($input['address2'])) && isset($input['city']) && isset($input['province']) && isset($input['postalCode'])) $sqlArray[] = 'ssFlag = "V"';
		if(isset($input['address1']))	$sqlArray[] = 'address1 = "' . $input['address1'] . '"';
		if(isset($input['address2']))	$sqlArray[] = 'address2 = "' . $input['address2'] . '"';
		if(isset($input['city']))		$sqlArray[] = 'city = "' . $input['city'] . '"';
		if(isset($input['province']))	$sqlArray[] = 'province = "' . $input['province'] . '"';
		if(isset($input['postalCode']))	$sqlArray[] = 'postalCode = "' . $input['postalCode'] . '"';
		if(isset($input['mobilePhone']))	$sqlArray[] = 'mobilePhone = "' . preg_replace("/[^0-9]/", "",$input['mobilePhone']) . '"';
		if(isset($input['email']))	$sqlArray[] = 'email = "' . $input['email'] . '"';
		if(isset($input['postalCode']))	$sqlArray[] = 'postalCode = "' . $input['postalCode'] . '"';
		if($input['dnc'] == "yes")
		{
			if($input['arcUpdate'] != 'no') 
			{
				$sqlArray[] = 'doNotCall_arc = "yes"';
			}
			else
			{
				$sqlArray[] = 'doNotMail = "yes"';
				$sqlArray[] = 'doNotCall = "yes"';
				$sqlArray[] = 'doNotEmail = "yes"';
			}
		}
		if($input['optIn'] == "yes" || $input['optIn'] == "y") $sqlArray[] = 'optIn = "yes"';
		/*
		if(isset($input['year']) && $input['year'] != $cus['year'])
		{
			$sqlArray[] = 'vin = ""';
			$sqlArray[] = 'stock = ""';
			$sqlArray[] = 'regNum = ""';
			$sqlArray[] = 'year = null';
			$sqlArray[] = 'description = ""';
			$sqlArray[] = 'color = ""';
			$sqlArray[] = 'make = ""';
			$sqlArray[] = 'model = ""';
			$sqlArray[] = 'trim = ""';
			$sqlArray[] = 'style = ""';
			$sqlArray[] = 'vehicle_type = ""';
			$sqlArray[] = 'drive_type = ""';
			$sqlArray[] = 'fuel_type = ""';
			$sqlArray[] = 'def_engine_cylinders = ""';
			$sqlArray[] = 'def_trans_speeds = ""';
			$sqlArray[] = 'def_engine_size = ""';
			$sqlArray[] = 'msrp = ""';
			$sqlArray[] = 'newUsed = ""';
			$sqlArray[] = 'deliveryDate = null';
			$sqlArray[] = 'km = null';
			$sqlArray[] = 'financed = ""';
			$sqlArray[] = 'term = null';
			$sqlArray[] = 'rate = ""';
			$sqlArray[] = 'monthlyPayment = ""';
			$sqlArray[] = 'salesman = ""';
			$sqlArray[] = 'assignedSalesman = ""';
			$sqlArray[] = 'serviceContractNum = ""';
			$sqlArray[] = 'serviceContractType = ""';
			$sqlArray[] = 'serviceContractExpire = null';
			$sqlArray[] = 'serviceContractMile = ""';
			$sqlArray[] = 'cbb_exclean = ""';
			$sqlArray[] = 'cbb_clean = ""';
			$sqlArray[] = 'cbb_average = ""';
			$sqlArray[] = 'cbb_rough = ""';
			$sqlArray[] = 'price = ""';
			$sqlArray[] = 'totalAfterSales = ""';
			$sqlArray[] = 'totalProtection = ""';
			$sqlArray[] = 'serviceContract = ""';
			$sqlArray[] = 'life = ""';
			$sqlArray[] = 'leaseLifePremium = ""';
			$sqlArray[] = 'dealLifePremium = ""';
			$sqlArray[] = 'accidental = ""';
			$sqlArray[] = 'leaseAccidentalPremium = ""';
			$sqlArray[] = 'dealAccidentalPremium = ""';
			$sqlArray[] = 'bank = ""';
			$sqlArray[] = 'financedAmount = ""';
			$sqlArray[] = 'balloonAmount = ""';
			$sqlArray[] = 'leaseMonthlyPayment = ""';
			$sqlArray[] = 'leaseResidualAmount = ""';
			$sqlArray[] = 'lastPaymentDate = null';
			$sqlArray[] = 'cashDown = ""';
			$sqlArray[] = 'milesPerYear = ""';
			$sqlArray[] = 'buyout = ""';
			$sqlArray[] = 'netTradeIn = ""';
		}
		
		if(isset($input['year']) && $input['year'] != "")	$sqlArray[] = 'year = "' . $input['year'] . '"';
		else if(isset($input['year']) && $input['year'] = "")	$sqlArray[] = 'year = null';

		if(isset($input['description']))	$sqlArray[] = 'description = "' . $input['description'] . '"';
		else if(isset($input['make']))	$sqlArray[] = 'description = "' . trim($input['make'] . ' ' . $input['model']) . '"';

		if(isset($input['salesman']))	$sqlArray[] = 'salesman = "' . $input['salesperson'] . '"';

		if(isset($input['term']) && $input['term'] != "")	$sqlArray[] = 'term = "' . $input['term'] . '"';
		else if(isset($input['term']) && $input['term'] = "")	$sqlArray[] = 'term = null';

		if(isset($input['rate']))	$sqlArray[] = 'rate = "' . $input['rate'] . '"';
		if(isset($input['monthlyPayment']))	$sqlArray[] = 'monthlyPayment = "' . $input['monthlyPayment'] . '"';
		*/
		
		$sql = 'UPDATE ' . $tbl . ' SET ' . implode(',',$sqlArray) . ' WHERE contactID = ' . $cus['contactID'];
		
		
		if(mysqli_query($db_data,$sql))
		{
			$xml->Status->StatusCode = 'SUCCESS';
			$xml->Status->StatusMessage = 'Record Successfully Updated';
		}
		else
		{
			$xml->Status->StatusCode = 'ERROR';
			$xml->Status->StatusMessage = 'Update Query Invalid';
		}
		
		echo trim($xml->asXML());
	}
?>