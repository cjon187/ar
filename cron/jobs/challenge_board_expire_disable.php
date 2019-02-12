<?php
	include_once('includes.php');
	include_once('emailUtils.php');

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Check to see if any challenge boards have expired on their expiry date.
	//Set the challenge board to disabled and clear the expiry date.
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Disabling any challenge boards past their expiry date");
	write_log("*************************");

	$today = \Carbon\Carbon::now()->startOfDay();
	$cbs = ChallengeBoard::where('status', ChallengeBoard::STATUS_LIVE)->where('disableDate is not null')->get();
	if(!empty($cbs)){
		foreach($cbs as $cb){
			$disableDate = \Carbon\Carbon::parse($cb->disableDate)->startOfDay();
			if($disableDate instanceof \Carbon\Carbon && $disableDate->lte($today)){
				$cb->status = 3;
				$cb->save();
			}
		}
	}

	write_log("*************************");
	write_log("Done disabling challenge boards.");
	write_log("*************************");

?>