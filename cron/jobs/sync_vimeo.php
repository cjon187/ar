<?php

	include_once('includes.php');


	////////////////////////////////////////////////////////////////////
	//update_vehicle_lookup
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Syncing Vimeo Videos | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	
	$vc = new VideosController();
	$vc->syncVideos();

	write_log("");
	write_log("*************************");
	write_log("Complete Syncing Vimeo Videos | " . date("Y-m-d H:i:s"));
	write_log("*************************");
	write_log("");
	write_log("");

?>