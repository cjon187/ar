
<?php

	if($error != '') echo $error;
	else
	{
?>
		<div style="text-align:left">
			<?php
			if($task['proof'] == ""){
			?>
				<div style="float:right; padding: 5px; background-color: #dddddd; cursor: pointer;" onClick="window.opener.updateStatus(<?= $taskObj->id ?>,'email','proof'); window.close(); ">
					Confirm Proof and Close
				</div>
			<?php
			}
			?>
			<div style="font-weight:bold;font-size:1.2em">EMAIL CAMPAIGN</div>
			<br><b>Master Task ID: <?= $taskObj->getMasterTask()->id ?></b>
			<br>EventID: <?= $dealerEvent['eventID'] ?>
			<br>Dealership: <?= $dealerInfo['dealerName'] ?>
			<br>Country: <?= $dealerInfo['country'] ?>
			<br>DA#: <?= displayWorksheetNum($agreement) ?>
			<br>Event Date: <?= $eventDate ?>
			<br>Trainer Date: <?= date("F j",strtotime($agreement['eventStart'])) ?>
			<br>
			<br>Language: <?= ($dealerInfo['isFrench'] == 'on' ? '<font style="font-weight:bold;color:red">FRENCH</font>' : 'English') ?>
			<br>URL: <?= ($url != "" ? '<font style="font-weight:bold;color:red">http://' . $url . '</font>' : 'none') ?>
			<br>List:
				<a href="<?= AR_SECURE_URL ?>index.php?s1=techSchedule&s2=Artwork&techTaskExport=&taskID=<?= $task['taskID'] ?>&taskTypeID=<?= Task::EMAIL ?>">Download</a>
		</div>
		<br>
		<div>
	<?php

		if(!empty($taskObj->templateID)) {
		?>
			<br><br>
			<div style="background-color:#eee;padding:10px">
				<b>Subject</b>
				<br>
				<?= $taskObj->subject ?>
				<br>
				<br>
				<b>Email Body</b>
				<br>
				<?= nl2br($taskObj->body) ?>
			  </div>
		<?php
		} else {
			foreach($artwork as $art)
			{
				if($art['extension'] == 'jpg') $thumbnail = $art['url'];
				else if($art['extension'] == 'pdf') $thumbnail = 'images/pdf_icon.png';
				else $thumbnail = 'images/file_icon.png';
		?>
				<div style="display:inline-block;padding:5px;cursor:pointer;" onClick="window.open('<?= $art['url'] ?>')">
					<div style="display:table-cell;vertical-align:middle;padding:10px;background-color:#efefef;width:200px;height:200px;">
						<img src="<?= $thumbnail ?>" style="max-width:180px;max-height:180px">
					</div>
					<div style="padding:5px;background-color:#ccc;font-weight:bold;">
						<?= strtoupper($art['extension']) ?>
					</div>
				</div>
		<?php
			}

			if($includeDocx)
			{
				foreach($docxLinks as $docx) print_r2($docx);
			}
		}
	?>
		</div>

<?php
	}
?>