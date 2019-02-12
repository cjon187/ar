<?php
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$json = array();

	if($_GET['key'] != '9C941INMK6')
	{
		$json['Error']['Message'] = 'Invalid API Key';
	}
	else if($_GET['service'] == 'arc_followup')
	{
		$error_message = array();
		$arr = array();
		$cols = array();
		$sql = 'SHOW COLUMNS FROM ps_arc_followup';
		$results = mysqli_query($db_data,$sql);
		while($re = mysqli_fetch_assoc($results)) $cols[$re['Field']] = '""';
		array_shift($cols);

		if($_POST['eventID'] == '') $error_message[] = 'Missing Event ID';
		else if($_POST['appointmentID'] == '') $error_message[] = 'Missing Appointment ID';
		else
		{
			foreach($_POST as $key => $val)
			{
				if(!in_array($key,array_keys($cols))) $error_message[] = 'Invalid Parameter: ' . $key;
				else
				{
					if($key == 'date_called') $val = date("Y-m-d H:i:s",strtotime($val));
					$cols[$key] = '"' . strip_tags(mysqli_real_escape_string($db_data,$val)) . '"';
				}
			}
		}

		if(count($error_message) > 0)
		{
			$json['Error']['Message'] = implode(' ; ',$error_message);
		}
		else
		{
			$sql = 'INSERT INTO ps_arc_followup (' . implode(',',array_keys($cols)) . ') VALUES (' . implode(',',$cols) . ')';
			if(mysqli_query($db_data,$sql)) $json['Status'] = 'Success';
			else $json['Error']['Message'] = 'MySQL Error: ' . $sql;
		}
	}

	echo json_encode($json);
?>