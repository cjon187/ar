<style>
	.filters > div {
		padding-right:5px;
	}


	#calendar {width:100%;height:100%;}
	#calendar th {width:14.286%;line-height:0.9em;margin:0px;padding:5px;font-size:0.8em;border:1px solid #aaa;background-color:#555;color:white;}
	#calendar td {line-height:auto;border:1px solid #aaa;padding:0px;margin:0px;font-size:0.85em}
	#calendar td:first-child { font-weight: normal}


	.calendar_date {
		position:relative;
		min-height:100px;
		height:100%;
	}
	.calendar_date.nonactive {
		background-color:#eee
	}
	.calendar_date_of_month {
		padding:5px;
		cursor:pointer;
	}
	.calendar_event_wrapper {
		position:relative;
		margin:0px;
		height:22px;
	}
	.calendar_event {
		position:absolute;
		white-space:nowrap;
		overflow: hidden;
		padding:3px 5px;
		cursor:pointer;
		background-color:#3085d6;
		color:white;
		border-radius:3px;
		z-index:100;
	}
	.calendar_event.non_ps {
		background-color:yellow;color:black;
	}
	.calendar_event.non_ps .calendar_event_dealer {
		color:#3085d6;
	}
	.calendar_event.unconfirmed_ps {
		background-color:#82bcf3;
		color:white;
	}
	.calendar_event.confirmed_ps {
		background-color:#3085d6;
		color:white;
	}
	.calendar_event_dealer {
		display:inline-block;
		color:yellow
	}
</style>

<script>
	$(function() {

		$('[eventID]').click(function() {
			if($(this).attr('nation') == 'ca') nation = '';
			else nation = $(this).attr('nation');

			var win = window.open('?s1=' + nation + 'calendar&s2=Event&id=' + $(this).attr('eventID'),'calendarEvent','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
			win.focus();
		});
	});

	function addEvent(addDate)
	{
<?php
	if($isAdmin)
	{
?>
		var win = window.open('?s1=<?=$_GET['s1'] ?>&s2=Event&add=' + addDate,'calendarEvent','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
<?php
	}
?>
	}
	function viewEvent(eventID,nation)
	{
		if(nation == 'ca') nation = '';

		var win = window.open('?s1=' + nation + 'calendar&s2=Event&id=' + eventID,'calendarEvent','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
	}



</script>

<div id="ar-page-title">Global Calendar</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="row">
	<div class="col-md-12">
		<form method="POST" class="form form-inline">
			<div class="filters">
				<div class="pull-left"><a href="?s1=<?= $_GET['s1'] ?>&viewBack="><img src="images/calendarLeft.gif" border="0"></a></div>
				<div class="pull-left"><a href="?s1=<?= $_GET['s1'] ?>&viewForward="><img src="images/calendarRight.gif" border="0"></a></div>
				<div class="pull-left">
						<select class="form-control input-sm" name="viewMonth" style="font-size:12pt;font-weight:bold" onChange="this.form.submit()">
				<?php
					for($i = 1;$i<= 12;$i++)
					{
						$val = str_pad($i,2,'0',STR_PAD_LEFT);
				?>
							<option value="<?= $val ?>" <?= ($_SESSION['calendar']['viewMonth'] == $val ? 'SELECTED' : '') ?>><?= date("M",strtotime('2014-' . $val . '-1')) ?></option>
				<?php
					}
				?>
						</select>
						<select class="form-control input-sm" name="viewYear" style="font-size:12pt;font-weight:bold" onChange="this.form.submit()">
				<?php
					for($i = date("Y",strtotime("now + 1 year"));$i >= 2007 ;$i--)
					{
				?>
							<option value="<?= $i ?>" <?= ($_SESSION['calendar']['viewYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
						</select>
				</div>
				<div class="pull-right">
					<form method="POST" class="form form-inline">
						<input id="calendarSearch" class="form-control input-sm" type="text" name="search" value="<?= $_SESSION['calendar']['search'] ?>" placeholder="Search">
						<input type="button" value="Search" class="btn btn-primary btn-sm" onClick="this.form.submit()">
					</form>
				</div>

				<div class="pull-right">
					<select id="oem" class="form-control input-sm" name="oem" onChange="this.form.submit()">
						<option value="" <?= ($_SESSION['calendar']['oem'] == '' ? 'selected' : '') ?>>All</option>
				<?php foreach($oems as $oemID => $oemName) { ?>
						<option value="<?= $oemID ?>" <?= ($_SESSION['calendar']['oem'] == $oemID ? 'selected' : '') ?>><?= $oemName ?></option>
				<?php } ?>
					</select>
				</div>

				<div class="pull-right">
					<select id="region" class="form-control input-sm" name="region" onChange="this.form.submit()">
				<?php foreach($lf->getList() as $lfID => $lfName) { ?>
						<option value="<?= $lfID ?>" <?= ($_SESSION['calendar']['region'] == $lfID ? 'selected' : '') ?>><?= $lfName ?></option>
				<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div  style="padding-top:10px;font-weight:bold">
			<?= number_format($totals['privateSales']) ?> Private Sales : <?= number_format($totals['confirmed']) ?> Confirmed
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12" style="padding-top:10px">
		<table id="calendar">
			<thead>
				<tr>
					<th>S</th>
					<th>M</th>
					<th>T</th>
					<th>W</th>
					<th>T</th>
					<th>F</th>
					<th>S</th>
				</tr>
			</thead>
			<tbody>
	<?php for($i = 1;$i <= 6;$i++) { ?>
				<tr id="calendar_<?= $i ?>_tr">
		<?php
			for($j = 1;$j <= 7;$j++) {
				$date = $calendar_dates[$i][$j];
			?>
					<td>
						<div class="calendar_date <?= ($date['active'] ? '' : 'nonactive') ?>" id="calendar_<?= $i ?>_<?= $j ?>_date">
							<div class="calendar_date_of_month" onClick="addEvent('<?= $date['date'] ?>')"><?= $date['date_of_month'] ?></div>
			<?php
				if(is_array($event_dates[$date['date']])) {
					uasort($event_dates[$date['date']],'daySort');
					foreach($event_dates[$date['date']] as $item => $e)
					{
						?>
								<div class="calendar_event_wrapper">
						<?php
							if($e['start']) {

								$event = $events[$e['eventID']];
								$trainer = $trainers[$event['trainerID']];

								$salesType = 'other';
								if(!in_array($event['salesTypeID'],array(5))) $salesType = 'non_ps';
								else if($event['confirmed'] != 'confirmed') $salesType = 'unconfirmed_ps';
								else if($event['confirmed'] == 'confirmed') $salesType = 'confirmed_ps';
								//else if(in_array($dealer['province'],array('QC'))) $bc = 'qbc';
								//else $bc = 'ebc';

								$days_to_week_end = ($e['weekday'] == 7 ? 6 : 6-$e['weekday']);
								$length = min($days_to_week_end,$e['remaining_days'])+1;
								$pad = ($length-1);
						?>

									<div class="calendar_event <?= $salesType ?>" eventID="<?= $e['eventID'] ?>" nation="<?= $event['nation'] ?>" style="width:<?= ($length*100) ?>%;">
										<div class="calendar_event_dealer"><?= $event['dealerName'] ?></div><?= ($trainer != '' ?  ' - ' . $trainer : '') ?>
									</div>
						<?php
							}
						?>
								</div>
						<?php
					}
				}
			?>
						</div>
					</td>
		<?php } ?>
				</tr>

	<?php } ?>
			</tbody>
		</table>
	</div>
</div>