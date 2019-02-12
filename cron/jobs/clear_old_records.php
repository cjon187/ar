<?php

	include_once('includes.php');


	////////////////////////////////////////////////////////////////////
	//update_vehicle_lookup
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Clearing Old AR Uploader Keys | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$sql = '
		DELETE FROM ar_uploader_hash
		WHERE createdAt <= NOW() - INTERVAL 30 MINUTE
	';
	$db->rawQuery($sql);

	write_log("");
	write_log("*************************");
	write_log("Complete Clearing Old AR Uploader Keys | " . date("Y-m-d H:i:s"));
	write_log("*************************");
	write_log("");
	write_log("");



?>