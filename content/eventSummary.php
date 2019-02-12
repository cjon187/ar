<style>
	.eventSummaryTbl {border-collapse:collapse;width:100%;background-color:white}
	.eventSummaryTbl th {padding:5px;border:1px solid #cccccc;text-align:left;font-size:9pt;vertical-align:bottom;background-color:#555;color:white}
	.eventSummaryTbl td {padding:5px;border:1px solid #cccccc;vertical-align:top;font-size:9pt}
	.eventSummaryTbl td a {text-decoration:none}
	.eventSummaryTbl td select {font-size:8pt;width:50px}
	.eventSummaryTbl td input {font-size:8pt;width:70px}
	.searchTbl td {border:0px;padding:0px;height:19px;vertical-align:middle}
	.searchTbl td input {font-size:8pt;}
	.commentsTbl td {border:0px;padding:0px 10px 10px 0px}
</style>

<script>
	function updateField(eid,field,val)
	{
		$.ajax({data:	{eid: eid,
						 field: field,
						 val: encodeURIComponent(val)},
				type:	'GET',
				dataType: 'script'
		   	    });

	}
</script>

<table cellspacing="0" cellpadding="0" style="width:100%">
	<tr>
		<td style="font-size:12pt;font-weight:bold">
			<table cellspacing="0" cellpadding="0" style="width:100%">
				<tr>
					<td style="width:100%" nowrap>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td><a href="?s1=eventSummary&viewBack="><img src="images/calendarLeft.gif" border="0"></a></td>
								<td style="padding:0px 5px">
									<form method="POST">
										<select name="viewStartMonth">
				<?php
					for($i=1;$i<=12;$i++)
					{
				?>
											<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['eventSummary']['viewStartMonth'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= date("M",strtotime("2011-" . $i)) ?></option>
				<?php
					}
				?>
										</select>
										<select name="viewStartDay">
				<?php
					for($i=1;$i<=31;$i++)
					{
				?>
											<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['eventSummary']['viewStartDay'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
										</select>
										<select name="viewStartYear">
				<?php
					for($i=2008;$i<=date("Y");$i++)
					{
				?>
											<option value="<?= $i ?>" <?= ($_SESSION['eventSummary']['viewStartYear'] == $i ? 'selected' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
										</select>
										 -

										<select name="viewEndMonth">
				<?php
					for($i=1;$i<=12;$i++)
					{
				?>
											<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['eventSummary']['viewEndMonth'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= date("M",strtotime("2011-" . $i)) ?></option>
				<?php
					}
				?>
										</select>
										<select name="viewEndDay">
				<?php
					for($i=1;$i<=31;$i++)
					{
				?>
											<option value="<?= str_pad($i,2,"0",STR_PAD_LEFT) ?>" <?= ($_SESSION['eventSummary']['viewEndDay'] == str_pad($i,2,"0",STR_PAD_LEFT) ? 'selected' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
										</select>
										<select name="viewEndYear">
				<?php
					for($i=2008;$i<=date("Y");$i++)
					{
				?>
											<option value="<?= $i ?>" <?= ($_SESSION['eventSummary']['viewEndYear'] == $i ? 'selected' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
										</select>
										<input type="submit" value="Filter">
									</form>
								</td>
								<td style="padding:0px 5px"><a href="?s1=eventSummary&viewForward="><img src="images/calendarRight.gif" border="0"></a></td>
							</tr>
						</table>
					</td>
					<td style="text-align:right;float:right;padding-right:10px">
						<form method="POST">
							<select name="filterBrand" onChange="this.form.submit()">
								<option value="" <?= ($_SESSION['eventSummary']['filterBrand'] == "" ? 'selected' : '') ?>>All</option>
								<option value="chrysler" <?= ($_SESSION['eventSummary']['filterBrand'] == "chrysler" ? 'selected' : '') ?>>Chrysler</option>
								<option value="nonchrysler" <?= ($_SESSION['eventSummary']['filterBrand'] == "nonchrysler" ? 'selected' : '') ?>>Other</option>
							</select>
						</form>
					</td>
					<td style="text-align:right;float:right;padding-right:10px">
						<form method="POST">
							<select name="filterLocation" onChange="this.form.submit()">
								<option value="" <?= ($_SESSION['eventSummary']['filterLocation'] == "" ? 'selected' : '') ?>>All</option>
								<option value="EBC" <?= ($_SESSION['eventSummary']['filterLocation'] == "EBC" ? 'selected' : '') ?>>EBC</option>
								<option value="WBC" <?= ($_SESSION['eventSummary']['filterLocation'] == "WBC" ? 'selected' : '') ?>>WBC</option>
								<option value="QBC" <?= ($_SESSION['eventSummary']['filterLocation'] == "QBC" ? 'selected' : '') ?>>QBC</option>
								<option value="USA" <?= ($_SESSION['eventSummary']['filterLocation'] == "USA" ? 'selected' : '') ?>>USA</option>
							</select>
						</form>
					</td>
					<td style="text-align:right;float:right">
						<form method="POST">
							<table cellspacing="0" cellpadding="0" class="searchTbl">
								<tr>
									<td><input type="image" src="images/searchLeft.png"></td>
									<td><input id="eventSummarySearch" style="padding:3px;border:0px;height:19px;width:150px;background-image:url('images/searchBG.png')" type="text" name="search" value="<?= $_SESSION['eventSummary']['search'] ?>"></td>
									<td><input type="image" src="images/searchRight.png"></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<?php
	foreach($eventsArray as $eventID => $event)
	{
	?>
		<br>
		<table cellspacing="0" cellpadding="0" class="eventSummaryTbl">
			<tr>
				<th style="border:0px">
					<?= $event['dealerName'] ?> (<?= date("M j",strtotime($event['saleStartDate'])) . (date("j",strtotime($event['saleStartDate'])) != date("j",strtotime($event['saleEndDate'])) ? '-' .  date("j",strtotime($event['saleEndDate'])) : '') ?>)
				</th>
				<th style="text-align:right;border:0px">
					<?= $calArray[$event['saleEndDate']][$event['dealerID']]['trainers'] ?>
				</th>
			</tr>
			<tr>
				<td style="height:50px" colspan="2">

					<table cellspacing="0" cellpadding="0" class="commentsTbl" style="width:100%">
						<tr>
							<td nowrap><b>Appts:</b> <?= $event['appt'] ?> <b>Show:</b> <?= $event['show'] ?> <b>Sold:</b> <?= $event['sold'] ?></td>
							<td style="text-align:right;padding-right:0px"><a href="misc/summaryExport.php?eID=<?= $event['eventID'] ?>" target="_blank">PDF Summary</a></td>
						</tr>
					</table>
					<table cellspacing="0" cellpadding="0" class="commentsTbl">
					<?php if($event['comments'] != "") { ?>
						<tr>
							<td nowrap><b>Public Comments</b></td>
							<td><?= $event['comments'] ?></td>
						</tr>
					<?php } ?>
					<?php if($event['privateComments'] != "") { ?>
						<tr>
							<td nowrap><b>Private Comments</b></td>
							<td><?= $event['privateComments'] ?></td>
						</tr>
					<?php } ?>
					</table>
				</td>
			</tr>
		</table>
	<?php
	}
?>