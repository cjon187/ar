<?php

	use Carbon\Carbon;

	include_once('includes.php');

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//SEND ARC THE LIST OF CALLS STARTING TODAY - ONLY IF THE CALLS HAVE A SPECIFIED CALL START DATE
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Inserting Private Sale Sold");
	write_log("*************************");

	$date = Carbon::now()->subMonth(1)->firstOfMonth();
	$lastDate = Carbon::now()->firstOfMonth();


	while($date->lte($lastDate)) {

		$apptTbl = 'ps_appointments_' . $date->format("my");
		$sql = 'SELECT eventID FROM ' . $apptTbl . ' GROUP BY eventID';
		$results = $db->rawQuery($sql);
		$eventIDs = [];
		if(count($results)) {
			foreach($results as $re) {
				$eventIDs[] = $re['eventID'];
			}
			$sql = 'DELETE FROM private_sale_solds WHERE eventID in (' . implode(',',$eventIDs) . ')';
			$db->rawQuery($sql);
		}


		for($i = 1;$i<=4;$i++) {
			$sql = 'REPLACE INTO private_sale_solds
					(eventID,appointmentID,salesrepID,contactID,year,make,model,tradeYear,tradeMake,tradeModel,newUsed,postalCode,firstName,lastName,currentYear,currentMake,currentModel,lat,lng)
					SELECT
					eventID,appointmentID,salesrepID,contactID,year' . $i . ',make' . $i . ',model' . $i . ',tradeYear' . $i . ',tradeMake' . $i . ',tradeModel' . $i . ',newUsed' . $i . ',postalCode,firstName,lastName,currentYear,currentMake,currentModel,lat,lng
					FROM ' . $apptTbl . '
					WHERE sold' . $i . '="y"';
			$db->rawQuery($sql);
		}

		echo 'Done ' . $date->format("M Y") . PHP_EOL;


		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				SET s.dealerID = e.dealerID
				WHERE s.dealerID IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating DealerIDs' . PHP_EOL;
		write_log('Done Updating DealerIDs');

		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN absoluteresults_geo.prizm5_2016_postal p ON (REPLACE(s.postalCode," ","") = p.FSALDU)
				SET s.lat = p.LATITUDE,s.lng=p.LONGITUDE
				WHERE
				d.countryID=' . COUNTRY_CA . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating CA Lat/lng' . PHP_EOL;
		write_log('Done Updating CA Lat/lng');

		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_zipcodes p ON (LEFT(s.postalCode,5) = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.countryID=' . COUNTRY_US . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating US Lat/lng' . PHP_EOL;
		write_log('Done Updating US Lat/lng');

		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_postalcodes_fr p ON (REPLACE(s.postalCode," ","") = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.countryID=' . COUNTRY_FR . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating FR Lat/lng' . PHP_EOL;
		write_log('Done Updating FR Lat/lng');


		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_postalcodes_de p ON (REPLACE(s.postalCode," ","") = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.countryID=' . COUNTRY_DE . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating DE Lat/lng' . PHP_EOL;
		write_log('Done Updating DE Lat/lng');


		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_postalcodes_ch p ON (REPLACE(s.postalCode," ","") = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.countryID=' . COUNTRY_CH . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating CH Lat/lng' . PHP_EOL;
		write_log('Done Updating CH Lat/lng');


		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_postalcodes_au p ON (REPLACE(s.postalCode," ","") = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.nationID=' . NATION_AU . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating AU Lat/lng' . PHP_EOL;
		write_log('Done Updating AU Lat/lng');

		$sql = 'UPDATE
				private_sale_solds s
				INNER JOIN ps_events e ON (s.eventID = e.eventID)
				INNER JOIN ps_dealers d ON (s.dealerID = d.dealerID)
				INNER JOIN ps_postcodes p ON (REPLACE(s.postalCode," ","") = p.postalCode)
				SET s.lat = p.lat,s.lng=p.lng
				WHERE
				d.nationID=' . NATION_UK . ' AND
				coalesce(s.postalCode,"") != "" AND
				s.lat IS NULL AND e.eventID in (' . implode(',',$eventIDs) . ')';
		$db->rawQuery($sql);
		echo 'Done Updating UK Lat/lng' . PHP_EOL;
		write_log('Done Updating UK Lat/lng');

		write_log("Complete " . $date->format("M Y"));
		$date->addMonth(1);
	}



	write_log("");
	write_log("*************************");
	write_log("Complete Inserting Private Sale Sold");
	write_log("*************************");

?>