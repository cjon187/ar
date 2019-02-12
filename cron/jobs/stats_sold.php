<?php

	include_once('includes.php');


	////////////////////////////////////////////////////////////////////
	//stats_sold
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Sold Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");


	mysqli_query($db_data,'TRUNCATE TABLE ps_stats_solds');

	$vLookup = array();
	$sql = 'SELECT * FROM ps_vehicle_lookup ORDER BY make,model';
	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results))
	{
		$vLookup[$re['make']][$re['model']] = $re['vehicleID'];
	}

	$start = '2008-01';
	$end = date("Y-m");
	$month = $start;

	while($month <= $end)
	{
		$appt = 'ps_appointments_' . date("my",strtotime($month));

		$sql = 'SELECT * FROM '  . $appt . ' WHERE sold1="y"';
		$results = mysqli_query($db_data,$sql);
		while($re = mysqli_fetch_assoc($results))
		{
			$setArray = array();
			$setArray['eventID'] = $re['eventID'];
			if($re['salesrepID'] != "") $setArray['salesrepID'] = $re['salesrepID'];

			for($i = 1;$i <= 4;$i++){
				if($re['sold'.$i] == 'y') {
					$vid = $vLookup[$re['make'.$i]][$re['model'.$i]];
					if($vid != '') $setArray['sold_vehicleID'] = $vid;

					$setArray['sold_description'] = $re['make' . $i] . ' ' . $re['model' . $i] . ' ' . $re['description' . $i];

					$setArray['sold_year'] = $re['year'.$i];
				}
				if($re['tradeYear'.$i] != '') {
					$vid = $vLookup[$re['tradeMake'.$i]][$re['tradeModel'.$i]];
					if($vid != '') $setArray['trade_vehicleID'] = $vid;

					$setArray['trade_description'] = $re['tradeMake' . $i] . ' ' . $re['tradeModel' . $i] . ' ' . $re['tradeDescription' . $i];

					$setArray['trade_year'] = $re['tradeYear'.$i];
				}
			}

			if($re['postalCode'] != "") $setArray['postalCode'] = str_replace(' ','',$re['postalCode']);

			$sql = 'INSERT INTO ps_stats_solds (' . implode(',',array_keys($setArray)) . ') VALUES ("' . implode('","',$setArray)  . '")';
			mysqli_query($db_data,$sql);

		}
		write_log("Month " . $month . " | ",false);

		$month = date('Y-m',strtotime($month . '-28 + 10 days'));
	}

	unset($vLookup);

	write_log("");
	write_log("*************************");
	write_log("Complete Sold Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");
	write_log("");
	write_log("");
?>