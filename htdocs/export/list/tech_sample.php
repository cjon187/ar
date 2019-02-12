<?php

	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	if(checkEncrypt($_GET['id'],$_GET['ekey'],'masterTask'))  {
		$lec = new ListExportController();
		$masterTask = Task::byId($_GET['id']);
		$lec->setAddTimestampToFileName(true);
		$lec->techTaskSampleExport($masterTask->taskID, $masterTask->taskTypeID, LEC::CSV);
	}

?>