<script>	
	function setAsToday(obj)
	{		
		document.getElementById(obj + 'Year').value = "<?= date('Y') ?>";
		document.getElementById(obj + 'Month').value = "<?= date('n') ?>";
		document.getElementById(obj + 'Day').value = "<?= date('j') ?>";
	}
</script>
<div style="width:100%;font-family:arial;">
	<font style="font-size:15pt"><?= $sysEvent['eventName'] ?></font>
	<br><br>
<form method="POST">
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td>SMS</td>
			<td><input type="checkbox" id="hasSMS" name="hasSMS" style="width:20px" <?= ($event['hasSMS'] == 'on' ? 'CHECKED' : '') ?>></td>
			<td colspan="2">		
				<table cellspacing="0" cellpadding="0" class="dateTbl">
					<tr>
						<td style="font-size:9pt"><i>Deployed:</i></td>
						<td>
							<select id="smsDeployedMonth" name="smsDeployedMonth">
								<option value=""></option>
				<?php
					for($i = 1; $i <= 12; $i++)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['smsDeployedMonth'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime($i . '/1')) ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td>
							<select id="smsDeployedDay" name="smsDeployedDay">
								<option value=""></option>
				<?php
					for($i = 1; $i <= 31; $i++)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['smsDeployedDay'] == $i ? 'SELECTED' : '') ?>><?= date("j",strtotime('1/' . $i)) ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td>
							<select id="smsDeployedYear" name="smsDeployedYear">
								<option value=""></option>
				<?php
					for($i = date("Y")+1; $i >= 2008; $i--)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['smsDeployedYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td><input type="button" value="Set Today" onClick="setAsToday('smsDeployed')" style="width:100px"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width:60px">Email</td>
			<td><input type="checkbox" id="hasEmail" name="hasEmail" style="width:20px" <?= ($event['hasEmail'] == 'on' ? 'CHECKED' : '') ?>></td>
			<td colspan="2">		
				<table cellspacing="0" cellpadding="0" class="dateTbl">
					<tr>
						<td style="font-size:9pt"><i>Deployed:</i></td>
						<td>
							<select id="emailDeployedMonth" name="emailDeployedMonth">
								<option value=""></option>
				<?php
					for($i = 1; $i <= 12; $i++)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['emailDeployedMonth'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime($i . '/1')) ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td>
							<select id="emailDeployedDay" name="emailDeployedDay">
								<option value=""></option>
				<?php
					for($i = 1; $i <= 31; $i++)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['emailDeployedDay'] == $i ? 'SELECTED' : '') ?>><?= date("j",strtotime('1/' . $i)) ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td>
							<select id="emailDeployedYear" name="emailDeployedYear">
								<option value=""></option>
				<?php
					for($i = date("Y")+1; $i >= 2008; $i--)
					{
				?>
								<option value="<?= $i ?>" <?= ($event['emailDeployedYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
							</select>
						</td>
						<td><input type="button" value="Set Today" onClick="setAsToday('emailDeployed')" style="width:100px"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width:60px;vertical-align:top;">Tech Notes</td>
			<td colspan="3">
				<textarea name="techNotes" style="width:100%;height:100px"><?= $event['techNotes'] ?></textarea>
			</td>
		</tr>		
	</table>
	<br><br>
	<input type="submit" value="Save">
</form>	
</div>
<?php if(isset($_GET['saved'])) { ?>
<script>
	window.opener.location.reload();
</script>
<?php } ?>