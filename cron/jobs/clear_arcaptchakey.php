<?php

	include_once('includes.php');




	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//CLEAR ALL OLD RECORDS FROM ARCaptchaKey table older than a day
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Clearing ARCaptchaKeys");
	write_log("*************************");
	$date = \Carbon\Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
	$db->rawQuery('DELETE FROM absoluteresults_data.ar_captcha WHERE `timestamp` < "'. $date .'"');
	write_log("*************************");
	write_log("Old ARCaptchaKeys Cleared");
	write_log("*************************");
?>