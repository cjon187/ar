<?php

	include_once('includes.php');



	////////////////////////////////////////////////////////////////////
	//customerUpdate
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Update Customer | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$sql = 'SELECT * FROM ps_updatequeue';
	$re = mysqli_query($db_data,$sql);
	while($queue = mysqli_fetch_assoc($re))
	{
		$sql = 'DELETE FROM ps_updatequeue WHERE queueID = ' . $queue['queueID'];
		mysqli_query($db_data,$sql);

		$xml = file_get_contents($queue['api']);
	}
	write_log("*************************");
	write_log("Complete Update Customer | " . date("Y-m-d H:i:s"));
	write_log("*************************");
	write_log("");
	write_log("");

?>