<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />



<script language="javascript" type="text/javascript" >

	function onlyNumbersDDD(e)
	{
		var key = window.event ? e.keyCode : e.which;
		var keychar = String.fromCharCode(key);
		reg = /[^0-9]/g;
		return !reg.test(keychar);
	}

</script>

<script language="javascript" type="text/javascript" >

	$(document).ready(function() {
		$("txt_name[maxlength]").bind("keyup input paste", function() {
			var limit = parseInt($(this).attr('maxlength'));
			var text = $(this).val();
			var chars = text.length;

			if(chars > limit){
				var new_text = text.substr(0, limit);
				$(this).val(new_text);
			}
		});




	/*function exportListTask(exportInfo, exportColumns, exportSQL){

		$.ajax({data: 	{exportListTask: exportInfo, exportColumns: exportColumns, exportSQL: exportSQL },
				type: 	'GET',
				dataType: 'script'
			});

	}*/


	});

	function exportListTask(taskID, taskTypeID){
		$.ajax({data: 	{exportListTask: 'test', taskID: taskID, taskTypeID: taskTypeID},
				type: 	'GET',
				dataType: 'script'
			});

	}



</script>

<style>
	body {
		margin:0;
		padding:10px 40px;
		background:#fff;
		font-family:Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	p {margin:0; padding:0;}

	h2{

		}

	img { border:none; }

	.hide {display: none}
	.bold {font-weight:bold; font-size: 130%}

	a {text-decoration: none;}
	a:hover {background: #FFC488}

	input[type="text"] {padding: 12px 2px; text-align: center;}
	.large_label {font-weight: bold;font-size: 24px;}

	.hidden-submit {
    	border: 0 none;
    	height: 0;
    	width: 0;
    	padding: 0;
    	margin: 0;
    	overflow: hidden;
	}

	.firstColumn{
		width: 120px; font-weight:bold;
	}

</style>


</head>
<body>

<h1 style="text-decoration:underline;">Tech Campaign Downloads</h1>

<p>&nbsp;</p>
<label class="large_label">Search Master TaskID: </label><br />
<form method="POST">
	<input type="text" name="masterTaskID" id="masterTaskID" maxlength="10" />
	<div class="hidden-submit"><input type="submit" tabindex="-1"/></div>
	<hr />
</form>

<?php
if(isset($_SESSION['techScheduleList']['masterTaskID']) && $masterTask instanceof Task){
	$type = Task::getName($masterTask->taskTypeID);
	$task = $masterTask->getTask();
	$contactIDs = $task->getContactIDs();
	$taskList = "Saved List";
	if(empty($contactIDs)) {
		$taskList = "Mailed List";
	}
	?>
	<h3>Selected Master Task Details</h3>
	 <table class="table table-striped">
		<tbody>
	      	<tr>
	        	<td class="firstColumn">Master TaskID</td>
	        	<td><?= $masterTask->id ?></td>
	      	</tr>
	      	<tr>
	        	<td class="firstColumn">Event ID</td>
	        	<td><?= $masterTask->event->eventID ?></td>

	      	</tr>
	      	<tr>
	      		<td class="firstColumn">DA #</td>
				<td><?= $masterTask->worksheet->da_num ?></td>
	      	</tr>
	      	<tr>
	      		<td class="firstColumn">Sale Start</td>
				<td><?= $masterTask->event->saleStartDate ?></td>
	      	</tr>
	      	<tr>
	      		<td class="firstColumn">Sale End</td>
	      		<td><?= $masterTask->event->saleEndDate ?></td>
	      	</tr>
	      	<tr>
	      		<td class="firstColumn">List Type</td>
				<td><?= $type ?></td>
	      	</tr>
	      	<tr>
	      		<td class="firstColumn">Download Link</td>
				<td>
					<a href="<?= AR_SECURE_URL ?>index.php?s1=techSchedule&s2=Lists&techTaskExport=&taskID=<?= $masterTask->taskID ?>&taskTypeID=<?= $masterTask->taskTypeID ?>">
						<?= $masterTask->id . '_' . $masterTask->event->dealer->dealerName .'_'. $masterTask->worksheet->da_num .'_' . $type . '_' . $taskList ?>
					</a>
				</td>
	      	</tr>
	    </tbody>
	 </table>

	<?php
}

?>

<div style="height: 100px;"></div>
<label>Search Event ID: (Deprecated)</label><br />
<form method="POST">
	<input type="text" name="eventIDText" id="eventIDText" maxlength="5" />
	<div class="hidden-submit"><input type="submit" tabindex="-1"/></div>
	<hr />
</form>
<?php
if(isset($_SESSION['techScheduleList']['eventID'])){
	echo '<h2 style="margin-bottom: 0px;">Current Event ID: '.$_SESSION['techScheduleList']['eventID'].'</h2>';
	echo  "Sale Start: " .$event['saleStartDate'] . '<br>Sale End :  '. $event['saleEndDate'];

	foreach($allTasks as $key => $typeTask){
		?> <h2 class="bold"><?= strtoupper($key) ?> Lists</h2> <?php
		foreach($typeTask as $task){

			if($task['type'] == "voicecasts") $taskTypeID = Task::VOICECASTS;
			if($task['type'] == "sms") $taskTypeID = Task::SMS;
			if($task['type'] == "email") $taskTypeID = Task::EMAIL;

			$taskList = " - Saved List";

			$mt = new Task();
			$masterTask = $mt->byTaskIDAndType($task['taskID'],$taskTypeID);
			if($masterTask) {
				$taskObj = $masterTask->getTask();
				if(empty($taskObj->getQuantity())){
					//AS OF MAY 21, WE ARE NO LONGER DEFAULTING CANADA EMAIL LISTS TO THE ENTIRE DATABASE.
					//if($dealerInfo['nation'] == 'ca' && $task['type'] == "email"){
						//$taskList = " - Database List";
					//}
					//else{
						$taskList = " - Mailed List";
					//}
				}
			}



			?>
			<a href="<?= AR_SECURE_URL ?>index.php?s1=techSchedule&s2=Lists&techTaskExport=&taskID=<?= $task['taskID'] ?>&taskTypeID=<?= $taskTypeID ?>"><?= $dealerInfo['dealerName'] .' - '. $masterTask->worksheet->da_num .' - ' . $task['type'] . ' - ' . $taskList ?></a>
			<br>

			<?php
		}
	}
}

?>


</body>
</html>
