<script>
	function maxLimit(maxChar,obj)
	{
		if(obj.value.length >= maxChar) obj.value = obj.value.substring(0,maxChar);
		document.getElementById(obj.name+"Char").innerHTML = obj.value.length + ' ';
	}
	function viewMentorSummaryLog()
	{
		window.open('?s1=mentorSummary&s2=Log','mentorySummaryLog','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		return false;
	}
</script>
<table cellspacing="0" cellpadding="0" style="width:550px">
	<tr>
		<td style="font-size:18pt;font-weight:bold">
			<?= $jrTrainerInfo['name'] ?>
		</td>
		<td style="text-align:right">
			<a href="#" onClick="viewMentorSummaryLog();return false">View History</a>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size:11pt">
			<b><?= $typeDesc ?></b> at <?= $_SESSION['mentorSummary']['dealerName'] ?> by <i><?= $mentorInfo['name'] ?></i>
		</td>
	</tr>
	<tr>
		<td style="font-size:11pt;">
			<?= displayEventDate($_SESSION['mentorSummary']) ?> <?= date("Y",strtotime($_SESSION['mentorSummary']['eventStart'])) ?>
		</td>
	</tr>
</table>
<style>
	th {font-size:12p;text-align:left;}
	td textarea {width:550px;height:70px;font-family:arial}
</style>
<br>
<form method="POST">
	<table cellspacing="0" cellpadding="0" style="width:550px">
		<tr>
			<th colspan="2" style="padding-bottom:20px">Overall Rating&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="trainerRating" style="width:200px;font-size:14pt">
					<option value="" <?= ($_SESSION['mentorSummary']['trainerRating'] == "" ? 'selected' : '') ?>></option>
					<option value="10" <?= ($_SESSION['mentorSummary']['trainerRating'] == "10" ? 'selected' : '') ?>>10 - Best</option>
					<option value="9" <?= ($_SESSION['mentorSummary']['trainerRating'] == "9" ? 'selected' : '') ?>>9</option>
					<option value="8" <?= ($_SESSION['mentorSummary']['trainerRating'] == "8" ? 'selected' : '') ?>>8</option>
					<option value="7" <?= ($_SESSION['mentorSummary']['trainerRating'] == "7" ? 'selected' : '') ?>>7</option>
					<option value="6" <?= ($_SESSION['mentorSummary']['trainerRating'] == "6" ? 'selected' : '') ?>>6</option>
					<option value="5" <?= ($_SESSION['mentorSummary']['trainerRating'] == "5" ? 'selected' : '') ?>>5</option>
					<option value="4" <?= ($_SESSION['mentorSummary']['trainerRating'] == "4" ? 'selected' : '') ?>>4</option>
					<option value="3" <?= ($_SESSION['mentorSummary']['trainerRating'] == "3" ? 'selected' : '') ?>>3</option>
					<option value="2" <?= ($_SESSION['mentorSummary']['trainerRating'] == "2" ? 'selected' : '') ?>>2</option>
					<option value="1" <?= ($_SESSION['mentorSummary']['trainerRating'] == "1" ? 'selected' : '') ?>>1 - Worst</option>
				</select>
			</th>
		</tr>
		<tr>
			<th colspan="2">Technology <font style="text-align:right;font-size:8pt">(AR Portal)</font></th>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="technology"><?= $_SESSION['mentorSummary']['technology'] ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2">Trainer / Leader</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="leadership"><?= $_SESSION['mentorSummary']['leadership'] ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2">Phone Skills</th>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="phoneskills"><?= $_SESSION['mentorSummary']['phoneskills'] ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2">Sale Day Management Skills <font style="text-align:right;font-size:8pt">(Customer Contact)</font></th>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="management"><?= $_SESSION['mentorSummary']['management'] ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2">Hygiene, Prompt, Personality</th>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="hygiene"><?= $_SESSION['mentorSummary']['hygiene'] ?></textarea></td>
		</tr>
		<tr>
			<th colspan="2">Comments</th>
		</tr>
		<tr>
			<td colspan="2" style="padding-bottom:20px"><textarea name="comments"><?= $_SESSION['mentorSummary']['comments'] ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:15px">
				<table cellspacing="0" cellpadding="0" style="width:550px">
					<tr>
						<td><input type="submit" value="Save"></td>
						<td style="text-align:right;color:red;font-weight:bold"><?= $_SESSION['trainerSummaryStatus'] ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:15px">
	<?php if($_SESSION['mentorSummary']['trainerRating'] != "" &&
			 strlen($_SESSION['mentorSummary']['technology']) >= 20 &&
			 strlen($_SESSION['mentorSummary']['leadership']) >= 20 &&
			 strlen($_SESSION['mentorSummary']['phoneskills']) >= 20 &&
			 strlen($_SESSION['mentorSummary']['management']) >= 20 &&
			 strlen($_SESSION['mentorSummary']['hygiene']) >= 20) { ?>
				<table cellspacing="0" cellpadding="0" style="width:550px">
					<tr>
						<td>
							<input type="button" onClick="this.disabled=true;this.value='Sending Email...Please Wait.';location.href='index.php?s1=mentorSummary&sendEmail='" value="Email Summary">
						</td>
						<td style="text-align:right;vertical-align:middle"><b>Email Sent:</b><i> <?= ($_SESSION['mentorSummary']['mentorSummarySent'] == '' ? '-' : date("Y-m-d h:i a",strtotime($_SESSION['mentorSummary']['mentorSummarySent']))) ?></i></td>
					</tr>
				</table>
	<?php } ?>
			</td>
		</tr>
	</table>
</form>
<?php unset($_SESSION['mentorSummaryStatus']) ?>