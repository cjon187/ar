<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	$results = TaskVehicleImageFileQueue::where('queuedAt IS NULL')->where('completedAt IS NULL')->get();
	if(!empty($results)) {
		foreach($results as $re) {
			$re->queuedAt = date("Y-m-d H:i:s");
			$re->save();
		}

		$controller = new TaskVehicleImageFileController();
		foreach($results as $re) {
			$controller->pushTaskVehicleImagesToFTP($re->masterTask);
			$re->completedAt = date("Y-m-d H:i:s");
			$re->save();
		}
	}
	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
?>