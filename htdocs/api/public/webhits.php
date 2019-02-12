<?php
	include_once('mysqliUtils.php');
	if($_GET['url'] == "") exit;
	
	if($_GET['eventID'] == "") exit;
	
	$sql = 'SELECT * FROM rsvp_websites where url = "' . $_GET['url'] . '" AND eventID="' . $_GET['eventID'] . '"';
	$results = mysqli_query($db_data,$sql);
		
	if(mysqli_num_rows($results) >= 1)
	{
		$task = mysqli_fetch_assoc($results);	
		$sql = 'UPDATE rsvp_websites SET hits = ' . ($task['hits']+1) . ' WHERE rsvpWebsiteID = ' . $task['rsvpWebsiteID'];			
	}		
								
	if(mysqli_query($db_data,$sql))
	{
		echo 'true';	
	}
	
?>