<?php
	include_once('mysqliUtils.php');
	if($_GET['url'] == "") exit;
	
	if($_GET['eventID'] == "") exit;
	
	$sqlArray = array();
	$sqlArray[] = '(url = "' . $_GET['url'] . '")';
	$sqlArray[] = '(eventID = "' . $_GET['eventID'] . '")';
	if($_GET['code'] != "") $sqlArray[] = '(code = "' . $_GET['code'] . '")';
	
	$sql = 'SELECT * FROM rsvp_websites WHERE ' . implode(' AND ',$sqlArray);
	$results = mysqli_query($db_data,$sql);
		
	if(mysqli_num_rows($results) >= 1)
	{
		$task = mysqli_fetch_assoc($results);	
		$sql = 'UPDATE ps_tasks_rsvpwebsites SET hits = ' . ($task['hits']+1) . ' WHERE taskID = ' . $task['taskID'];			
	}		
								
	if(mysqli_query($db_data,$sql))
	{
		echo 'true';	
	}
	
?>