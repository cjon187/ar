<style>
	.calendarEventTbl {width:480px}
	.calendarEventTbl td,th {padding:3px;font-size:9pt;text-align:left;vertical-align:middle}
	.calendarEventTbl input,select,textarea {font-size:9pt;}
	.calendarEventTbl input,textarea {width:380px}
	.calendarEventTbl textarea {font-family:arial;font-size:9pt;height:150px}
</style>


<script>

	function pad(number, length)
	{
	    var str = '' + number;
	    while (str.length < length) {
	        str = '0' + str;
	    }
	    return str;
	}

	function loadDates(d)
	{
		var arr = d.split("-");
		document.getElementById('eventStartYear').value = arr[0];
		document.getElementById('eventStartMonth').value = arr[1];
		document.getElementById('eventStartDay').value = arr[2];
		document.getElementById('eventEndYear').value = arr[3];
		document.getElementById('eventEndMonth').value = arr[4];
		document.getElementById('eventEndDay').value = arr[5];
		updateAvailable();
	}

	function updateAvailable()
	{
		document.getElementById('sales').innerHTML = '<i>loading...</i>';
		document.getElementById('trainers').innerHTML = '<i>loading...</i>';
		document.getElementById('assigned').innerHTML = '<i>loading...</i>';
		document.getElementById('unavailable').innerHTML = '<i>loading...</i>';
		document.getElementById('available').innerHTML = '<i>loading...</i>';
		document.getElementById('unassignedSales').innerHTML = '<i>loading...</i>';
		document.getElementById('openSlots').innerHTML = '<i>loading...</i>';
		eventStart = document.getElementById('eventStartYear').value + '-' + pad(document.getElementById('eventStartMonth').value,2) + '-' + pad(document.getElementById('eventStartDay').value,2);
		eventEnd = document.getElementById('eventEndYear').value + '-' + pad(document.getElementById('eventEndMonth').value,2) + '-' + pad(document.getElementById('eventEndDay').value,2);

		$.ajax({data:	{updateAvailable: '',
						 eventStart: eventStart,
						 eventEnd: eventEnd},
				type:	'GET',
				dataType: 'script'
		   	    });
		return false;
	}
</script>
<table cellspacing="0" cellpadding="0">
	<tr>
		<th colspan="2">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td style="vertical-align:middle"><a href="?s1=<?=$calendarLink ?>calendar&s2=Available&viewBack="><img src="images/calendarLeft.gif" border="0"></a></td>
					<th style="padding:10px 10px;font-size:15pt"><?= date("F Y",strtotime($_SESSION['calendarAvailable']['viewYear'] . '-'.$_SESSION['calendarAvailable']['viewMonth'])) ?> - <?= strtoupper(($calendarLink == "" ? 'CA' : $calendarLink)) ?></td>
					<td style="vertical-align:middle"><a href="?s1=<?=$calendarLink ?>calendar&s2=Available&viewForward="><img src="images/calendarRight.gif" border="0"></a></td>
				</tr>
			</table>
		</th>
	</tr>
	<tr>
		<th>Suggested Sale Days</th>
		<td>
			<select id="suggestedSaleDays"  onChange="loadDates(this.value);">
				<option value=""></option>
	<?php
	foreach($saleDates as $dates => $desc)
	{
	?>
				<option value="<?= $dates ?>"><?= $desc ?></option>
	<?php
	}
	?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Start Time</th>
		<td>
			<table cellspacing="0" cellpadding="0" class="dateTbl">
				<tr>
					<td>
						<select id="eventStartMonth" name="eventStartMonth" <?= $disabled ?> onChange="updateAvailable();">
							<option value=""></option>
			<?php
				for($i = 1; $i <= 12; $i++)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventStartMonth'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime($i . '/1')) ?></option>
			<?php
				}
			?>
						</select>
					</td>
					<td>
						<select id="eventStartDay" name="eventStartDay" <?= $disabled ?> onChange="updateAvailable();">
							<option value=""></option>
			<?php
				for($i = 1; $i <= 31; $i++)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventStartDay'] == $i ? 'SELECTED' : '') ?>><?= date("j",strtotime('1/' . $i)) ?></option>
			<?php
				}
			?>
						</select>
					</td>
					<td>
						<select id="eventStartYear" name="eventStartYear" <?= $disabled ?> onChange="updateAvailable();">
							<option value=""></option>
			<?php
				for($i = date("Y")+1; $i >= 2008; $i--)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventStartYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
			<?php
				}
			?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>End Time</th>
		<td>
			<table cellspacing="0" cellpadding="0" class="dateTbl">
				<tr>
					<td>
						<select id="eventEndMonth" name="eventEndMonth" <?= $disabled ?> onChange="updateAvailable()">
							<option value=""></option>
			<?php
				for($i = 1; $i <= 12; $i++)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventEndMonth'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime($i . '/1')) ?></option>
			<?php
				}
			?>
						</select>
					</td>
					<td>
						<select id="eventEndDay" name="eventEndDay" <?= $disabled ?> onChange="updateAvailable()">
							<option value=""></option>
			<?php
				for($i = 1; $i <= 31; $i++)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventEndDay'] == $i ? 'SELECTED' : '') ?>><?= date("j",strtotime('1/' . $i)) ?></option>
			<?php
				}
			?>
						</select>
					</td>
					<td>
						<select id="eventEndYear" name="eventEndYear" <?= $disabled ?> onChange="updateAvailable()">
							<option value=""></option>
			<?php
				for($i = date("Y")+1; $i >= 2008; $i--)
				{
			?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendarAvailable']['eventEndYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
			<?php
				}
			?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<br><br>
<table cellspacing="0" cellpadding="0">
	<tr>
		<th style="padding-right:10px">Open Slots</th>
		<td><div id="openSlots"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Total Sales</th>
		<td><div id="sales"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Unassigned Sales</th>
		<td><div id="unassignedSales"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Total Trainers</th>
		<td><div id="trainers"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Trainers Assigned</th>
		<td><div id="assigned"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Trainers Available</th>
		<td><div id="available"></div></td>
	</tr>
	<tr>
		<th style="padding-right:10px">Trainers Not Available</th>
		<td><div id="unavailable"></div></td>
	</tr>
</table>
<script>updateAvailable()</script>