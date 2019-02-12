
<table cellspacing="0" cellpadding="0" style="width:550px">
	<tr>
		<td style="font-size:18pt;font-weight:bold">
			<?= $dealerInfo['dealerName'] ?>
		</td>
	</tr>
	<tr>
		<td style="font-size:12pt;font-weight:bold">
			<?= displayEventDate($eventRow) ?>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px">
			<a href="misc/summaryExport.php?sr=&eid=<?= $_SESSION['followUp']['eventID'] ?>" target="_blank">View Summary PDF</a>
		</td>
	</tr>
</table>
<style>
	th {font-size:12p;text-align:left;}
	td textarea {width:550px;height:100px;font-family:arial}
</style>
<br>
<form method="POST">
	<table cellspacing="0" cellpadding="0" style="width:550px">
		<tr>
			<th>Production Staff</th>
			<td style="text-align:right;font-size:8pt"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="productionStaff"><?= $_SESSION['followUp']['productionStaff'] ?></textarea></td>
		</tr>
		<tr>
			<th>Event Staff</th>
			<td style="text-align:right;font-size:8pt"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="eventStaff"><?= $_SESSION['followUp']['eventStaff'] ?></textarea></td>
		</tr>
		<tr>
			<th>Management Thoughts</th>
			<td style="text-align:right;font-size:8pt"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="management"><?= $_SESSION['followUp']['management'] ?></textarea></td>
		</tr>
		<tr>
			<th>AR Thoughts</th>
			<td style="text-align:right;font-size:8pt"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="company"><?= $_SESSION['followUp']['company'] ?></textarea></td>
		</tr>
		<tr>
			<th>Outcome</th>
			<td style="text-align:right;font-size:8pt"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px">
				<select onChange="$('#outcome').val($(this).val())">
					<option value=""></option>
					<option value="Everything Good - Rebooked">Everything Good - Rebooked</option>
					<option value="Everything Good - No Rebook">Everything Good - No Rebook</option>
					<option value="Everything Not Good - Won`t use AR again">Everything Not Good - Won`t use AR again</option>
					<option value="Everything Not Good - Heat Score - Management Follow Up Required">Everything Not Good - Heat Score - Management Follow Up Required</option>
				</select>
				<input type="text" id="outcome" name="outcome" value="<?= $_SESSION['followUp']['outcome'] ?>" style="width:100%">
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:15px">
				<table cellspacing="0" cellpadding="0" style="width:550px">
					<tr>
						<td><input type="submit" value="Save"></td>
						<td style="text-align:right;color:red;font-weight:bold"><?= $_SESSION['followUpStatus'] ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:15px">
				<table cellspacing="0" cellpadding="0" style="width:550px">
					<tr>
						<td>				
							<input type="button" onClick="this.disabled=true;this.value='Sending Email...Please Wait.';location.href='index.php?s1=followUp&sendEmail='" value="Email Summary">				
						</td>
						<td style="text-align:right;vertical-align:middle"><b>Email Sent:</b><i> <?= ($_SESSION['followUp']['emailSent'] == '' ? '-' : date("Y-m-d h:i a",strtotime($_SESSION['followUp']['emailSent']))) ?></i></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php unset($_SESSION['followUpStatus']) ?>