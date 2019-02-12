<?php

	include_once('includes.php');


	////////////////////////////////////////////////////////////////////
	//update_vehicle_lookup
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Vehicle Lookup | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$sql = '
		INSERT IGNORE INTO ps_vehicle_lookup (make,model,segment,minYear,maxYear) 
		SELECT make,model,vehicle_type,min(year),IF(max(year) = YEAR(NOW()),null,max(year)) FROM ps_vin GROUP BY make,model';
	mysqli_query($db_data,$sql);
	
	mysqli_query($db_data,'alter table maintable auto_increment=1');

	write_log("");
	write_log("*************************");
	write_log("Complete Vehicle Lookup | " . date("Y-m-d H:i:s"));
	write_log("*************************");
	write_log("");
	write_log("");



?>