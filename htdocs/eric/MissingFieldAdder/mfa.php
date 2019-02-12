<?php

if(!empty($_POST)){
	include_once('mysqliUtils.php');
	$dealerID = $_POST['dealerID'];
	$tempConID = $_POST['tempConID'];
	$missingField = $_POST['missingField'];
	echo "Attempting to insert <b>" . $missingField . "</b> from Temp-Container_<b>" . $tempConID . "</b> into dealer table <b>" . $dealerID . "</b>" ;
	
	$dbArray = array();
	$sql = 'SELECT * FROM ps_dealer_'.$dealerID.'_contacts';
	$result = mysqli_query($db_data,$sql);
	
	if(!$result){
		echo "<br>The query failed, double check your numbers";
	}
	else {
		if(mysqli_num_rows($result) == 0){
			echo "<br>Failed to grab contacts from dealer: " . $dealerID;
		} 
		else {
			while($db = mysqli_fetch_assoc($result))
			{
				$dbArray[strtolower($db['firstname'].$db['lastname'])] = $db['id'];
			}
		
			$sql2 = 'SELECT * FROM ps_dealer_temp_contacts_'.$tempConID.''; 
			$result2 = mysqli_query($db_data,$sql2);
			if(mysqli_num_rows($result2) == 0){
				echo "<br>Failed to get the rows from the temp-container: ";
			} 
			else{
				$count = 0;
				while($temp = mysqli_fetch_assoc($result2))
				{
					$id = $dbArray[strtolower($temp['firstname'].$temp['lastname'])];
					if($id != "")
					{
						$sql3 = 'UPDATE ps_dealer_'.$dealerID.'_contacts SET ' . $missingField . ' = "' . $temp[$missingField] . '" WHERE id = ' . $id;
						$result3 = mysqli_query($db_data,$sql3);
						$count++;
					}
				}
				echo "<br><br>Success";
				echo "<br><b>" . $count ."/" . mysqli_num_rows($result) ."</b> rows of dealership table affected";
			}
		}
	}
	exit();
}
else{
	echo "
	<html>
	<head>
		<title>Missing Field Adder</title>
		<style>
			ol {
				margin: 0;
				padding: 0;
			}
			li{
				list-style:none;
				padding: 5px;
				margin: 0px;
			}
			label{
				display: inline-block;
				width: 125px;
			}
		</style>
	</head>
	<body>
	<h1>Add a missing Field</h1>
	<p>Instructions: Load the missing column into a temp container with first and last name</p>
	<form action='mfa.php' method='POST'>
	<ol>
		<li>
			<label for='dealerID'>Dealer ID:</label>
			<input type='text' name='dealerID'>
		</li>
		<li>
			<label for='tempConID'>Temp-Container ID:</label>
			<input type='text' name='tempConID'>
		</li>
		<li>
			<label for='missingField'>Missing Field:</label>
			<select name='missingField'>
				<option value='assignedSalesman' >Assigned Salesman</option>
				<option value='salesman' >Salesman</option>
				<option value='contactID' >Contact ID</option>
				<option value='firstname' >First Name</option>
				<option value='lastname' >Last Name</option>
				<option value='customerCode' >Customer Code</option>
				<option value='companyname' >Company Name</option>
				<option value='doNotCall' >Do Not Call</option>
				<option value='doNotMail' >Do Not Mail</option>
				<option value='doNotEmail' >Do Not Email</option>
				<option value='deceased' >Deceased</option>
				<option value='address1' >Address 1</option>
				<option value='address2' >Address 2</option>
				<option value='city' >City</option>
				<option value='province' >Province</option>
				<option value='postalCode' >Postal Code</option>
				<option value='distance' >Distance</option>
				<option value='mainPhone' >Main Phone</option>
				<option value='businessPhone' >Business Phone</option>
				<option value='mobilePhone' >Mobile Phone</option>
				<option value='email' >Email</option>
				<option value='lastUpdated' >Last Updated</option>
				<option value='lastUploaded' >Last Uploaded</option>
				<option value='list' >List Source</option>
				<option value='vin' >VIN</option>
				<option value='year' >Vehicle Year</option>
				<option value='description' >Make&Model</option>
				<option value='make' >Make</option>
				<option value='model' >Model</option>
				<option value='stock' >Vehicle Stock</option>
				<option value='term' >Term</option>
				<option value='monthlyPayment' >Payment</option>
				<option value='rate' >Rate</option>
				<option value='newUsed' >Vehicle New/Used</option>
				<option value='km' >KM</option>
				<option value='serviceDate' >Vehicle Service Date</option>
				<option value='deliveryDate' >Vehicle Delivery Date</option>
				<option value='lastPaymentDate' >Last Payment Date</option>
				<option value='ssFlag' >SS Flag</option>
			</select>
		</li>
		<li>				
			<input type='submit' value='Upload'>
		</li>
	</ol>
	</form>
	</body>
	</html>";
}
?>