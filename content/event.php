<?php include_once('crm3_header.php'); ?>
<style>
	#summary {
	}
	#summary .total  {
		text-align:center;
		font-weight:bold;
	}
	#summary .total .num {
		font-size:4em;
		line-height:1em;
	}
	#summary .num {
		color:#0082bb;
	}
	#summary .desc {
		color:#555;
	}
	#breakdownSummary {
		margin-top:10px;
	}
	#summary .breakdown  {
		display:inline-block;
		min-width:200px;
		font-size:1.2em;
	}

	#summary .breakdown div {
		display:inline-block;
		font-weight:bold;
	}

	#summary .breakdown .desc {
		font-size:0.8em;
	}

	.sectionDetailsDiv {
		overflow-y:auto;
		height:150px;
		background-color:white;
	}
</style>
<script>
	$(function() {
		$('[worksheetID]').click(function() {
			editWorksheet($(this).attr('worksheetID'));
		})
	})
	function openCalendarWindow() {
		var win = window.open('?s1=<?= ($dealer->nation->nationAbbr == 'ca' ? '' : $dealer->nation->nationAbbr ) ?>calendar&s2=Event&id=<?= $event->id ?>','calendarEvent','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
	}
	function openReport(link) {
		var win = window.open(link,'report');
		win.focus();
	}

	function editWorksheet(worksheetID)
	{
		var win = window.open('?s1=worksheet&s2=Add&id=' + worksheetID,'editWorksheet','width=550,height=750,toolbar=0,scrollbars=1,resizable=1');
		win.focus();
	}

	function openKits(eid,taskID)
	{
		var win = window.open('?s1=kits&eventID=' + eid + '&taskID=' + taskID,'seeKits','width=400,height=800,toolbar=0,scrollbars=1');
		win.focus();
	}
</script>
<div class="container-fluid" id="crm">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=crm3&s2=Dealer&id=<?= $event->dealer->id ?>"><?= $dealer->name ?></a></li>
				<li class="active">
					<?= $event->name ?>
				</li>
			</ol>
		</div>
	</div>



	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="row">
					<div class="col-xs-6">
						<div class="title"><?= $event->name ?></div>
						<div><b>Trainer</b> <?= $event->trainer->name ?></div>
						<div><b>MEC</b> <?= $event->accountManager->name ?></div>
						<div style="margin-top:10px">
							<button type="button" class="btn btn-primary btn-xs" onClick="openCalendarWindow()">Edit Event</button>
							<button type="button" class="btn btn-success btn-xs" onClick="location.href='?s1=dealerLogin&did=<?= $event->dealer->id ?>&ekey=<?= encrypt($event->dealer->id,'dealer') ?>&eventID=<?= $event->id ?>&page=followUp'">Go to Event</button>
						</div>
					</div>
					<div class="col-xs-6">
						<div id="summary">
							<div class="row">
								<div class="col-xs-5">
									<div class="total">
										<div class="num">
											<?= number_format($stats['sold']) ?>
										</div>
										<div class="desc">VEHICLES SOLD</div>
									</div>
								</div>
								<div class="col-xs-7">
									<div id="breakdownSummary">
										<div class="breakdown">
											<div class="num"><?= number_format($stats['appt']) ?></div>
											<div class="desc">APPT</div>
										</div>
										<div class="breakdown">
											<div class="num"><?= number_format($stats['show']) ?></div>
											<div class="desc">SHOWS</div>
										</div>
										<div class="breakdown">
											<div class="num"><?= number_format($stats['salesrepCount']) ?></div>
											<div class="desc">SALESREPS</div>
										</div>
										<div class="breakdown">
											<div class="num"><?= number_format($stats['closing'],1) ?>%</div>
											<div class="desc">CLOSING RATIO</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="section">
				<div class="title">
					Agreements
				</div>
				<div class="sectionDetailsDiv">
					<table class="table table-condensed sectionTable">
						<tbody>
				<?php
					if(!empty($worksheets)) {
						foreach($worksheets as $ws) {
				?>
							<tr class="sectionTableRow" worksheetID=<?= $ws['worksheetID'] ?>>
								<td><?= displayWorksheetNum($ws) ?></td>
								<td><?= getWorksheetType($ws) ?></td>
							</tr>
				<?php
						}
					}
				?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="section">
				<div class="title">
					Reports
				</div>
				<div class="sectionDetailsDiv">
					<table class="table table-condensed sectionTable">
						<tbody>
							<tr class="sectionTableRow" onClick="openReport('<?= $event->getPreSaleURL() ?>')">
								<td>Pre Sale</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('<?= $event->getPDFSummaryURL(false) ?>')">
								<td>Short Summary</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('<?= $event->getPDFSummaryURL() ?>')">
								<td>Long Summary</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('export/report/event_excel.php?eid=<?= $event->id ?>')">
								<td>Excel</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('export/report/brag.php?eid=<?= $event->id ?>')">
								<td>Brag</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('export/report/brag.php?full&eid=<?= $event->id ?>')">
								<td>Full-Brag</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('export/report/history.php?eid=<?= $event->id ?>')">
								<td>Historical</td>
							</tr>
							<tr class="sectionTableRow" onClick="openReport('export/report/trackback.php?eid=<?= $event->id ?>')">
								<td>TrackBack</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="section">
				<div class="title">
					Tasks
				</div>
				<div class="sectionDetailsDiv">
					<table class="table table-condensed sectionTable">
						<tbody>
				<?php
					if(!empty($tasks)) {
						foreach($tasks as $task) {
							if(empty($task->id)) {
								continue;
							}
							$masterTask = $task->getMasterTask();
							$taskType = $task->getTaskName($masterTask->taskTypeID);
				?>
							<tr class="sectionTableRow">
								<td><?= $all_tasks[$taskType] ?></td>
								<td><?= displayWorksheetNum($worksheets[$task->worksheetID]) ?></td>
								<td>
						<?php
							switch($taskType) {
								case('conquests'):
								?>
									<button type="button" class="btn btn-success btn-xs" onClick="location.href='?s1=event&s2=Conquest&taskID=<?= $masterTask->id ?>'">View</button>
								<?php
									break;
								case('kits'):
								?>
									<button type="button" class="btn btn-success btn-xs" onClick="openKits(<?= $event->id ?>,<?= $task->id ?>)">View</button>
								<?php
									break;
								case('rsvpwebsites'):
								?>
									<button type="button" class="btn btn-success btn-xs" onClick="window.open('http://<?= $task->url ?>')">View</button>
								<?php
									break;
							}
						?>
								</td>
							</tr>
				<?php
						}
					}
				?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="section">
				<div class="title">
					Tools
				</div>

				<div class="sectionDetailsDiv">

					<table class="table table-condensed sectionTable">
						<tbody>
							<tr class="sectionTableRow" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=PreEventCall&eventID=<?= $event->id ?>'">
								<td>Pre Event Call Log</td>
								<td><?= (!empty($preEventCall->call_time) ? 'Called <b>' . date("M j g:iA",strtotime($preEventCall->call_time)) . '</b>' : '<b>Add</b>') ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once('managementReview.php') ?>