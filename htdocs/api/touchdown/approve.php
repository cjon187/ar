<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	
	if($_GET['id'] != "" & checkEncrypt($_GET['id'],$_GET['ekey'],'touchdown'))
	{		
		$sql = 'SELECT * FROM ps_touchdowns WHERE touchdownID = ' . $_GET['id'] . ' AND dateApproved != ""';
		if(mysqli_num_rows(mysqli_query($db_data,$sql)) == 1) echo 'Touch Down story already approved.';
		else
		{
			$sql = 'UPDATE ps_touchdowns SET dateApproved = "' . date("Y-m-d H:i:s") . '" WHERE touchdownID = ' . $_GET['id'];		
			if(mysqli_query($db_data,$sql)) echo 'Touch Down story approved.';
			else echo 'Error. Please try again.';
		}
	}


?>