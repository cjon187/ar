<style>
	.summaryTbl {width:100%}
	.summaryTbl td {padding-bottom:10px}
</style>

<div id="ar-page-title">Mentor Summary Log</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div style="padding:0px 5px;width:520px">
	<table cellspacing="0" cellpadding="0" style="width:100%">
		<tr>
			<td style="font-weight:bold">
				<b>Trainer</b>
				<form method="POST">
					<select name="jrTrainerID" onChange="this.form.submit()">
						<option></option>
		<?php
			while($tr = mysqli_fetch_assoc($trainerResults))
			{
		?>
						<option value="<?= $tr['staffID'] ?>" <?= ($staff['staffID'] == $tr['staffID'] ? 'SELECTED' : '') ?>><?= $tr['name'] ?></option>
		<?php
			}
		?>
					</select>
				</form>
			</td>
		</tr>
	</table>
	<?php if($summaryResults == null or mysqli_num_rows($summaryResults) == 0) { ?>
	<br>
	<i>No Summaries Found</i>
	<?php } else { ?>
	<br>
	<table cellspacing="0" cellpadding="0" style="width:100%">
		<?php
			$i = 0;
			while($info = mysqli_fetch_assoc($summaryResults))
			{
				$i++;
				$mentorInfo = displayStaffInfo($info['staffID']);
				$typeDesc = ($info['mentorType'] == 'jrTrainer' ? 'Jr. Trainer Mentoring' : 'Trainer Development');
		?>
			<tr>
				<td style="padding:10px;background-color:<?= ($i % 2 == 0 ? 'white' : '#efefef') ?>">
					<table cellspacing="0" cellpadding="0" class="summaryTbl">
						<tr>
							<td style="font-size:11pt">
								<u><b><?= $typeDesc ?></b></u>
							</td>
						</tr>
						<tr>
							<td>
								<b><?= $info['dealerName'] ?></b> by <i><?= $mentorInfo['name'] ?></i> on <?= displayEventDate($info) ?> <?= date("Y",strtotime($info['eventStart'])) ?>
							</td>
						</tr>
				<?php if($info['trainerRating'] != "") { ?>
						<tr><td><b>Rating</b> <?= $info['trainerRating'] ?> / 10</td></tr>
				<?php } ?>
				<?php if($info['technology'] != "") { ?>
						<tr><td><b>Technology</b><br><?= $info['technology'] ?></td></tr>
				<?php } ?>
				<?php if($info['leadership'] != "") { ?>
						<tr><td><b>Trainer / Leader</b><br><?= $info['leadership'] ?></td></tr>
				<?php } ?>
				<?php if($info['phoneskills'] != "") { ?>
						<tr><td><b>Phone Skills</b><br><?= $info['phoneskills'] ?></td></tr>
				<?php } ?>
				<?php if($info['management'] != "") { ?>
						<tr><td><b>Sale Day Management Skills</b><br><?= $info['management'] ?></td></tr>
				<?php } ?>
				<?php if($info['hygiene'] != "") { ?>
						<tr><td><b>Hygiene, Prompt, Personality</b><br><?= $info['hygiene'] ?></td></tr>
				<?php } ?>
				<?php if($info['comments'] != "") { ?>
						<tr><td><b>Comments</b><br><?= $info['comments'] ?></td></tr>
				<?php } ?>
					</table>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php } ?>
</div>