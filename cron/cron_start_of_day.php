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

	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/stats_tech_sent.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/event_kpis.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/arc_notification.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/clear_rsvp_unsubsribe_uuids.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/clear_arcaptchakey.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/staff_status_update.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/birthday_emails.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/anniversary_emails.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/challenge_board_expire_disable.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/franchise_agreement_reminder.php"');
	system($cmd . ' -f "' . AR_ROOT . 'cron/jobs/dealer_appointment_log.php"');

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

?>