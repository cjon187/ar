<?php

	include_once('includes.php');




	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//CLEAR ALL OLD RECORDS FROM rsvp_unsubsribe_uuids older than a day.
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Clearing RSVP_unsubscripe_uuids");
	write_log("*************************");
	$date = \Carbon\Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
	$db->rawQuery('DELETE FROM absoluteresults_log.rsvp_unsubscribe_uuids WHERE `timestamp` < "'. $date .'"');
	write_log("*************************");
	write_log("RSVP_unsubscribe_uuids Cleared");
	write_log("*************************");
?>