<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('dataSQLUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('taskUtils.php');
	use \Carbon\Carbon;
	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;


	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}


	$cmd = 'php';

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/stats_events.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/stats_dealers.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/stats_sold.php"');

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/customer_update.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/vin_update.php"');

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/private_sale_solds.php"');

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/vehicle_lookup_update.php"');

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/clear_old_records.php"');

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/sync_vimeo.php"');

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;



?>