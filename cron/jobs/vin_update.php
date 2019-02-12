<?php

	include_once('includes.php');


	////////////////////////////////////////////////////////////////////
	//UPDATE ps_vin FROM DATA ONE SOFTWARE FTP
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Loading ps_vin | " . date("Y-m-d H:i:s"));
	write_log("*************************");


	$conn_id = ftp_connect('ftp.dataonesoftware.com');
	$login_result = ftp_login($conn_id, 'absolute_results', '2GYuYu0XgnCcMHUm');
	ftp_pasv($conn_id, true);
	$server_file = 'DataOne_IDP_absolute_results.csv';
	$local_file = ARFileController::tempFile('csv');
	//$local_file = SVDC_AR_DATA_SHARE . 'dataone.csv';

	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		if(mysqli_query($db_data,'TRUNCATE TABLE `ps_vin`')) {
	   		write_log('ps_vin truncated');
	   		if(mysqli_query($db_data,"LOAD DATA LOCAL INFILE '".$local_file."' INTO TABLE ps_vin FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES (VIN_PATTERN,VEHICLE_ID,OUT_OF_MARKET,MARKET,YEAR,MAKE,MODEL,TRIM,STYLE,VEHICLE_TYPE,BODY_TYPE,BODY_SUBTYPE,DOORS,MSRP,GVW_RANGE,DEF_ENGINE_ID,DRIVE_TYPE,FUEL_TYPE,DEF_ENGINE_BLOCK,DEF_ENGINE_CYLINDERS,DEF_ENGINE_SIZE,ENGINE_SIZE_UOM,DEF_ENGINE_ASPIRATION,DEF_TRANS_ID,DEF_TRANS_TYPE,DEF_TRANS_SPEEDS)")) {
	   			write_log('ps_vin loaded');
	   		} else {
	   			write_log('error loading ps_vin');
	   		}
		} else {
	   		write_log('error truncating ps_vin');
		}
	} else {
	   	write_log('Error Retrieving File from FTP');
	}

	write_log("");
	write_log("*************************");
	write_log("Complete Loading ps_vin | " . date("Y-m-d H:i:s"));
	write_log("*************************");
?>