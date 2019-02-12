<?php
	
	$dealerID = $_POST['dealerID'];
	$tempConID = $_POST['tempConID'];
	$missingField = $_POST['missingField'];
	echo "DealerID = " .$_POST['dealerID'] . "</br>";
	echo "Temp Container ID = " .$_POST['tempConID'] . "</br>";
	echo "Missing Field = " .$_POST['missingField']. "</br>";
	$sql = 'SELECT * FROM ps_dealer_'.$dealerID.'_contacts'; //change dealer ID here
	$re = mysqli_query($db_data,$sql);
	
	$dbArray = array();
	while($db = mysqli_fetch_assoc($re))
	{
		$dbArray[strtolower($db['firstname'].$db['lastname'])] = $db['id'];
	}
	
	$sql = 'SELECT * FROM ps_dealer_temp_contacts_'.$tempConID.''; //change temp container here here
	$re = mysqli_query($db_data,$sql);
	
	while($temp = mysqli_fetch_assoc($re))
	{
		$id = $dbArray[strtolower($temp['firstname'].$temp['lastname'])];
		
		if($id != "")
		{
			$sql = 'UPDATE ps_dealer_'.$dealerID.'_contacts SET ' . $missingField . ' = "' . $temp[$missingField] . '" WHERE id = ' . $id;
			$result = mysqli_query($db_data,$sql);
			echo sqlsrv_rows_affected($result);
			echo "hey";
		}
	}
	echo "Done!";
	//$sql = 'UPDATE ps_dealer_'.$dealerID.'_contacts SET '.$missingField.' = "' . $temp . [''.$missingField.''] . '" WHERE id = ' . $id; //change dealer ID here 
?>
