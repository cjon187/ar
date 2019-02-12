<?php

	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('agreementUtils.php');
	include_once('displayUtils.php');
	include_once('taskUtils.php');
	include_once('emailUtils.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}

	$now = date("Y-m-d");
	//$now = "2018-10-23";

	$tblArrays = array();
	$dayOfWeek = date("w",strtotime($now));
	$bounds = [];

	switch($dayOfWeek) {
		case(0):
			echo 'Not run on a sunday.';
			echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
			exit;
			break;
		case(6):
			echo 'Not run on a saturday.';
			echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
			exit;
			break;
		case(4):
			$bounds = [7,10];
			break;
		case(2):
			$bounds = [6,8];
			break;
	}

	if(!empty($bounds)){
		$sqlArgs = [];
		for($i = $bounds[0]; $i <= $bounds[1]; $i++){
			$sqlArgs[] = 'eventStart = "' . date("Y-m-d",strtotime($now . " + ". $i ." days")) . '"';
		}
	}

	$results = $db->rawQuery('
			SELECT te.eventID, te.taskID FROM ps_tasks_email te
	        INNER JOIN ps_events e ON(te.eventID = e.eventID)
	        WHERE
	        	(e.eventEnd >= "'. date('Y-m-d') .'")
	        	AND
	        	(
					(pushedToTaargaDate is null AND pushedToTaargaLastAttemptDate is not null)
					'. (!empty($sqlArgs) ? 'OR ('. implode(' OR ', $sqlArgs ). ')' : '') .
				')');


	if(empty($results)){
		write_log('No entries found.');
	}
	else{
		$tep = new TaargaEmailProof();
		foreach($results as $re){
			$task = TaskEmail::byId($re['taskID']);
			$success = $tep->sendProofRequest($task);

			$error = '';
			if(!$success) {
				$error = $tep->error;
				$task->pushedToTaargaLastAttemptDate = \Carbon\Carbon::now()->toDateTimeString();
				$task->save();
			}
			else{
				$task->pushedToTaargaDate = \Carbon\Carbon::now()->toDateTimeString();
				$task->pushedToTaargaLastAttemptDate = \Carbon\Carbon::now()->toDateTimeString();
				$task->save();
			}
			write_log($task->event->dealer->name . ' ' . $task->event->eventStart . ' - ' . ($error == '' ? 'SENT' : $error));
		}
	}

	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
?>
