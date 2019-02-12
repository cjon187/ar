<style>
	.formTbl th {padding-right:10px;color:#333333;text-align:left;font-size:9pt;padding-bottom:5px;vertical-align:middle;}
	.formTbl td {color:#333333;padding-bottom:5px;vertical-align:middle;font-size:10pt}
	.formTbl td select,input {font-size:9pt}
	.formTbl textarea {font-family:arial}
	.dateTbl td {padding-right:3px}
	.responseTD {width:100px;background-color:#efefef;height:30px;font-weight:bold;text-align:center}
</style>
<script>
	function selectContact()
	{
		val = document.getElementById('contactSelect').value.split('|');

		if(val[0] == undefined) document.getElementById('sentName').value = '';
		else document.getElementById('sentName').value = val[0];

		if(val[1] == undefined) document.getElementById('sentEmail').value = '';
		else document.getElementById('sentEmail').value = val[1];
	}
</script>
<div style="padding:0px 10px">
<table cellspacing="0" cellpadding="0" class="formTbl" style="width:660px">
	<tr>
		<td style="font-size:14pt"><b><?= $_SESSION['survey']['dealer']['dealerName'] ?></b></td>
		<td style="text-align:right;vertical-align:top" rowspan="2">
			<b>Requested: </b><i><?= ($_SESSION['survey']['linkEmailed'] == '' ? 'N/A' : date('M j, Y g:i a',strtotime($_SESSION['survey']['linkEmailed']))) ?></i>
			<br>
			<b>Dealer Responded: </b><i><?= ($_SESSION['survey']['dealerResponded'] == '' ? 'N/A' : date('M j, Y g:i a',strtotime($_SESSION['survey']['dealerResponded']))) ?></i>
		</td>
	</tr>
	<tr>
		<td style="font-size:12pt">
			<?= displayEventDate($_SESSION['survey'],false,false,true) ?>
		</td>
	</tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" class="formTbl" style="border:1px solid #ccc;width:660px">
	<tr>
		<td style="background-color:#eee;padding:5px;">
			<b>Link</b>
		</td>
		<td style="background-color:#eee;padding:5px;text-align:right;font-weight:bold;color:red;font-size:9pt">
			<?= $_SESSION['surveyError'] ?>
			<?php unset($_SESSION['surveyError']) ?>
		</td>
	<tr>
		<td colspan="2" style="padding:10px;">
			<a href="<?= AR_URL ?>survey/?key=<?= md5($_SESSION['survey']['eventID'])?>&eid=<?= $_SESSION['survey']['eventID'] ?>" target="_blank"><?= AR_URL ?>survey/?key=<?= md5($_SESSION['survey']['eventID'])?>&eid=<?= $_SESSION['survey']['eventID'] ?></a>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-left:10px;">
			<form method="POST" onSubmit="document.getElementById('sendButton').disabled = true">
		<?php
			$sql = 'SELECT * FROM ps_dealerStaff WHERE status = 1 AND dealerID = ' . $_SESSION['survey']['dealer']['dealerID'];
			$contactResults = mysqli_query($db_data,$sql);
		?>

				<select id="contactSelect" onChange="selectContact()">
					<option></option>
		<?php
			while($contact = mysqli_fetch_assoc($contactResults))
			{
		?>
					<option value="<?= $contact['name'] . '|' . $contact['email'] ?>" <?= ($_SESSION['survey']['sentName'] == $contact['name'] ? 'selected' : '') ?>><?= $contact['name'] ?></option>
		<?php
			}
		?>
				</select>
				<input type="hidden" id="sentName" name="sentName" value="<?= $_SESSION['survey']['sentName'] ?>" style="width:40%">
				<input type="text" id="sentEmail" name="sentEmail" value="<?= $_SESSION['survey']['sentEmail'] ?>" style="width:200px">
				<input type="submit" id="sendButton" value="Send Survey Request">
			</form>
		</td>
	</tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" class="formTbl">
	<tr>
		<th style="padding-right:10px">Name</th>
		<td ><?= $_SESSION['survey']['surveyName'] ?></td>
		<th style="padding-left:30px;padding-right:10px">Phone</th>
		<td><?= $_SESSION['survey']['surveyPhone'] ?></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Trainer</th>
		<td><?= $_SESSION['survey']['trainerName'] ?></td>
		<th style="padding-left:30px;padding-right:10px">Account Manager</th>
		<td><?= $_SESSION['survey']['accountManager']['name'] ?></td>
		<th style="padding-left:30px;padding-right:10px">Language</th>
		<td><?= ($_SESSION['survey']['dealer']['isFrench'] == 'on' ? 'French' : 'English') ?></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" class="formTbl">
	<tr>
		<th colspan="2" style="padding-top:10px;padding-right:10px">Please rate our production staff / account manager on the following...</th>
	</tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionContact'] ?></th>
		<td style="padding-left:10px;"><i>Were you contacted 3 weeks in advance?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionResponse'] ?></th>
		<td style="padding-left:10px"><i>Was our team prompt in responding to you in the production / proofing process?</i></td>
	</tr>
	<!--
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionCreativity'] ?></th>
		<td style="padding-left:10px"><i>How would you rate the creativity of the mailer and the selection offered?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionEffective'] ?></th>
		<td style="padding-left:10px"><i>How would you rate the effectiveness of the invitations?</i></td>
	</tr>
	-->
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionProcess'] ?></th>
		<td style="padding-left:10px"><i>How well were you informed of the process/flow of the event and the importance of participation within your dealership?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionPortal'] ?></th>
		<td style="padding-left:10px"><i>Were you directed to your personal dealership portal to review your database and choice of invitation?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['productionConquest'] ?></th>
		<td style="padding-left:10px"><i>Did your account manager discuss strategy to send conquest flyers?</i></td>
	</tr>
	<tr>
		<th colspan="2" style="padding-top:20px;padding-right:10px">Please rate our trainer on the following...</th>
	</tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerEnergy'] ?></th>
		<td style="padding-left:10px;"><i>How would you rate your trainer's energy and enthusiasm?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerTimely'] ?></th>
		<td style="padding-left:10px;"><i>Was your trainer timely for all meetings and the sale days?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerQuality'] ?></th>
		<td style="padding-left:10px;"><i>How would you rate the quality of the training sessions?</i></td>
	</tr>
	<!--
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerStaff'] ?></th>
		<td style="padding-left:10px;"><i>How would you rate the trainers ability to relate to your staff?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerCustomer'] ?></th>
		<td style="padding-left:10px;"><i>How would you rate the trainer’s ability to relate to your customers on sale day?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerMakeDeals'] ?></th>
		<td style="padding-left:10px;"><i>How involved was the trainer in helping "make deals" during the event?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerVolume'] ?></th>
		<td style="padding-left:10px;"><i>Did we sell a week's volume at your sale?</i></td>
	</tr>
	-->
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerInteract'] ?></th>
		<td style="padding-left:10px;"><i>How would you rate the trainer's ability to interact with your customer's on sale day?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<td class="responseTD"><?= $_SESSION['survey']['trainerBack'] ?></th>
		<td style="padding-left:10px;"><i>Would you like this trainer back for a future event?</i></td>
	</tr>
	<tr><td colspan="2" style="height:5px"></td></tr>
	<tr>
		<th colspan="2" style="padding-top:20px;padding-right:10px">Additional Comments</th>
	</tr>
	<tr>
		<td style="width:100%;background-color:#efefef;height:100px;vertical-align:top;padding:10px" colspan="2"><?= str_replace("\r","<br>",$_SESSION['survey']['surveyComments']) ?></th>
	</tr>
	<tr><td colspan="2" style="height:25px"></td></tr>
</table>
</div>
<script>
	if(window.opener.location.href.indexOf("production") != -1)
	{
		window.opener.seeProduction('<?= $_SESSION['survey']['calendarEvent']['eventID'] ?>');
	}
</script>