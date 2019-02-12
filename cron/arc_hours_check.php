<?php
	use \Carbon\Carbon;

	require_once('defines.php');
	include_once('displayUtils.php');
	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
	include_once('Emailer/swift_required.php');

	Carbon::setWeekStartsAt(Carbon::SUNDAY);
	Carbon::setWeekEndsAt(Carbon::SATURDAY);
	$date = Carbon::now()->startofweek()->startofday();

	$results = ArcWeeklySchedule::where('weekStart', $date->format("Y-m-d"))->get();
	if(empty($results)){
		echo 'No hours found. Duplicating last week.... Sending email...'. PHP_EOL;

		$pastDate = $date->copy()->subweeks(1);
		$pastWeek = ArcWeeklySchedule::where('weekStart', $pastDate->format("Y-m-d"))->get();
		if(!empty($pastWeek)){
			foreach($pastWeek as $day){
				$aws = new ArcWeeklySchedule();
				$aws->weekStart = $date->format("Y-m-d");
				$aws->dayOfWeek = $day->dayOfWeek;
				$aws->startTime = $day->startTime;
				$aws->endTime   = $day->endTime;
				$aws->save();
			}
		}

		echo 'Duplicated. Sending email...'. PHP_EOL;

		$subject = "ARC Hours Reminder and Auto Update - " . $date->format('Y-m-d') . ' - ' . $date->copy()->endofweek()->format("Y-m-d");

		$body = 'No hours were found for ARC for the upcoming week. The system has copied last weeks hours into the scheduler.
				 If this is incorrect please go the ARC scheduler and fix it.
				 <br>
				 <br>
				 Ideally you should make sure you manually setting up the upcoming weeks before, do not leave it to the system.
				';

		$from = 'noreply@absoluteresults.com';
		$to = 'arc@absoluteresults.com';
		$cc = [];
		$bcc = [];

		if(EmailController::sendEmail($subject,$body,$from,$to,$cc,$bcc,null,Email::TYPE_NO_ARC_HOURS) ) {
			echo 'ARC Hour Reminder sent'. PHP_EOL;
		}
		else
		{
			echo 'Error in sending weekly summary'. PHP_EOL;
		}
	}
	else{
		echo 'AR Hours found for the next week. No action taken.'. PHP_EOL;
	}

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
exit;






?>