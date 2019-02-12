<?php
	include_once('includes.php');

	////////////////////////////////////////////////////////////////////
	//stats_dealers
	////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin Dealer Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");

	$sql = 'SELECT dealerID FROM ps_dealers';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results)) {
		$dealerStats = new DealerStats();
		$dealerStats->dealerID = $re['dealerID'];
		$dealerStats->reCalculate();

		write_log("Dealer #" . $re['dealerID'] . " | ",false);
	}
	write_log("");
	write_log("*************************");
	write_log("Complete Dealer Stats | " . date("Y-m-d H:i:s"));
	write_log("*************************");

?>