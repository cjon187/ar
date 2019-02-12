<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="scripts/jquery.numeric.min.js"></script>
<script src="scripts/moment.js"></script>

<link rel="stylesheet" href="scripts/daterangepicker/jquery.comiseo.daterangepicker.css" />
<script src="scripts/daterangepicker/jquery.comiseo.daterangepicker.js"></script>

<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>

<script src="scripts/pagination/jquery.twbsPagination.min.js"></script>

<style>
	[daterangepicker] {
		font-size:0.8em;
	}
	.date-picker-wrapper {
		z-index:3;
	}

	#clientCareTbl {
		font-size:0.9em;
	}

	#clientCareTbl tbody{
  		border-bottom: 30px solid white;
	}
	#clientCareTbl thead{
		background-color:#333;
		color:white;
	}

	.groupName {
		font-size:1.3em;
		color:yellow;
	}

	#clientCareTbl thead {
		background-color:white;
		color:#333;
		font-size:0.9em;
	}

	.filters {
		margin-bottom:10px;
	}
	#region {
		max-width:200px;
	}
	#summary {
	}
	#summary .total  {
		border:1px solid #ddd;
		background-color:#eee;
		border-radius:5px;
		padding:20px 30px;
		line-height:1em;

		font-size:1em;
		text-align:center;
		display:inline-block;

	}
	#summary .total .num {
		line-height:1.3em;
	}
	#summary .num {
		color:#0082bb;
		padding-right:10px;
		font-size:1.5em;
	}
	.summaryYes {
		color:green;
		font-size:1.2em;
		font-weight:bold;
	}
	.summaryNo {
		color:red;
		font-size:1.2em;
		font-weight:bold;
	}

	[filterCategory] {
		cursor:pointer;
	}
	#summary .desc {
		color:#555;
		font-size:1.5em;
		font-weight:bold;
	}

	#summary .summaryTypes {
		margin-bottom:15px;
	}
	#summary .summaryTypes .title {
		font-size:1.5em;
		line-height:1.5em;
		color:#777;
	}
	#summary .breakdown  {
	}
	#summary .breakdown div {
		display:inline-block;
		font-weight:bold;
	}

	.daterangepicker {
		font-size:0.8em;
	}

	.questionTH {
		text-align:center;
	}
	.answerTD {
		text-align:center;
	}

	.answerTD.yes {
		background-color:#DFF0D8;
	}

	.answerTD.no {
		background-color:#F2DEDE;
	}

	[dealerID],[eventID] {
		cursor:pointer;
	}

	#clientCareNotesModal .alert {
		display:none;
	}
	#clientCareNotesModal #clientCareNotes {
		height:200px;
	}
	#clientCareNotesModal #clientCareLastUpdated {
		font-size:0.9em;
		font-style:italic;
	}
</style>
<script>
	$(function() {

		init = true;
		$('[report_daterangepicker]').daterangepicker({
			presetRanges: [{
		         text: 'Current Month',
		         dateStart: function() { return moment().startOf('month') },
		         dateEnd: function() { return moment().endOf('month') }
		     }, {
		         text: 'Last Month',
		         dateStart: function() { return moment().subtract('month', 1).startOf('month') },
		         dateEnd: function() { return moment().subtract('month', 1).endOf('month') }
		     }, {
		         text: 'Today',
		         dateStart: function() { return moment() },
		         dateEnd: function() { return moment() }
		     }, {
		         text: 'Last 7 Days',
		         dateStart: function() { return moment().subtract('days', 6) },
		         dateEnd: function() { return moment() }
		     }, {
		         text: 'All Time',
		         dateStart: function() { return moment("20110901", "YYYYMMDD") },
		         dateEnd: function() { return moment() }
		     }],

		     datepickerOptions : {
		         numberOfMonths: 1,
		         minDate: null,
		         maxDate: null
		     }
		 });

		<?php
			if($_SESSION['clientCare']['report']['dateRange'] != '') {
		?>
			dateRange = jQuery.parseJSON('<?= $_SESSION['clientCare']['report']['dateRange'] ?>');
			$('[name=dateRange]').daterangepicker("setRange", {start: moment(dateRange.start).startOf('day').toDate(),end: moment(dateRange.end).startOf('day').toDate()});
		<?php
			}
		?>


<?php
	if(is_array($responses)) {
?>
		$('#responses_pagination').twbsPagination({
	        totalPages: <?= $responsePageCount ?>,
	        startPage: <?= $responses_pagination ?>,
	        visiblePages: 10,
	        onPageClick: function (event, page) {
	            location.href='?s1=<?= $_GET['s1'] ?>&p=' + page;
	        }
	    });
<?php
	}
?>
		$('[data-toggle="popover"]').popover({
			html: true
		});

		$('[data-toggle="popover"]').click(function() {
			$(this).popover('toggle');
		});

		$('[dealerID]').click(function() {
			window.open('?s1=dealershipBlade&page=information&dealerID=' + $(this).attr('dealerID'));
		})
		$('[eventID]').click(function() {
			window.open('?s1=event&eventID=' + $(this).attr('eventID'));
		})

		$('[filterCategory]').click(function() {
			$('#results').val($(this).attr('filterCategory') + '_' + $(this).attr('filterValue'));
			$('#filterForm').submit();
		})
	});


	function displayClientCareNotes(eid) {

		$('#clientCareNotesSuccess').hide();
		$('#clientCareNotesError').hide();
		loadClientCareNotes(eid);
	}

	function loadClientCareNotes(eid) {
   		$.ajax({data: {loadClientCareNotes: true,
   						eventID: eid},
   				type:	'POST',
				dataType: 'json',
				success: function(data) {
					$('#clientCareNotesEventID').val(data.eventID);
					$('#clientCareNotesEventName').html(data.eventName);
					$('#clientCareNotes').html(data.notes);
					$('#clientCareLastUpdated').html(data.lastUpdated);
					$('#clientCareNotesModal').modal('show');
				}
		});
	}

	function saveClientCareNotes() {

   		$.ajax({data: {saveClientCareNotes: true,
   					   eventID: $('#clientCareNotesEventID').val(),
   					   clientCareNotes: $('#clientCareNotes').val()},
   				type:	'POST',
				dataType: 'json',
				success: function(data) {
					if(data.success == 1) {

						$('#clientCareNotesSuccess').show();
						$('#clientCareNotesError').hide();
						loadClientCareNotes($('#clientCareNotesEventID').val());
					} else {

						$('#clientCareNotesSuccess').hide();
						$('#clientCareNotesError').show();
					}
				}
		});
	}

	function viewResponse(eventID)
	{
		window.open('?s1=clientCare&s2=Response&eventID=' + eventID,'dealerRelationResponse','width=800,height=600,toolbar=0,resizable=1,scrollbars=1');
		return false;
	}

</script>

<div id="ar-page-title">Dealer Relations</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="container-fluid">
	<form method="POST" class="form-inline" id="filterForm">
		<div class="row">
			<div class="col-md-12">
				<div class="filters">
					<div class="form-group" style="padding-right:10px">
						<label>Date</label>
						<div class="daterangepicker">
							<input report_daterangepicker name="dateRange" value="<?= $_SESSION['clientCare']['report']['dateRange'] ?>">
						</div>
					</div>
					<div class="form-group">
						<label>Region</label>
						<div>
							<select class="form-control input-sm" id="region" name="region">
						<?php foreach($lf->getList() as $lfID => $lfName) { ?>
								<option value="<?= $lfID ?>" <?= ($_SESSION['clientCare']['report']['region'] == $lfID ? 'selected' : '') ?>><?= $lfName ?></option>
						<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>OEM</label>
						<div>
							<select class="form-control input-sm" id="oem" name="oem">
								<option value="" <?= ($_SESSION['clientCare']['report']['oem'] == '' ? 'selected' : '') ?>>All</option>
						<?php foreach($oems as $oemID => $oemName) { ?>
								<option value="<?= $oemID ?>" <?= ($_SESSION['clientCare']['report']['oem'] == $oemID ? 'selected' : '') ?>><?= $oemName ?></option>
						<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>MEC</label>
						<div>
							<select class="form-control input-sm" id="staffID" name="staffID">
								<option value="" <?= ($_SESSION['clientCare']['report']['staffID'] == '' ? 'selected' : '') ?>>All</option>
						<?php foreach($staff as $s) { ?>
								<option value="<?= $s->id ?>" <?= ($_SESSION['clientCare']['report']['staffID'] == $s->id ? 'selected' : '') ?>><?= $s->name ?></option>
						<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Trainer</label>
						<div>
							<select class="form-control input-sm" id="trainerID" name="trainerID">
								<option value="" <?= ($_SESSION['clientCare']['report']['trainerID'] == '' ? 'selected' : '') ?>>All</option>
						<?php foreach($trainers as $s) { ?>
								<option value="<?= $s->id ?>" <?= ($_SESSION['clientCare']['report']['trainerID'] == $s->id ? 'selected' : '') ?>><?= $s->name ?></option>
						<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Results</label>
						<div>
							<select class="form-control input-sm" id="results" name="results">
								<option value="" <?= ($_SESSION['clientCare']['report']['results'] == '' ? 'selected' : '') ?>>All</option>
						<?php
							foreach($questionCategories as $q) {
								if($q->id == ClientCareQuestionCategory::NOTES) {
						?>
								<option value="<?= $q->id ?>_1" <?= ($_SESSION['clientCare']['report']['results'] == ($q->id . '_1') ? 'selected' : '') ?>><?= $q->name ?> - Have Notes</option>
								<option value="<?= $q->id ?>_0" <?= ($_SESSION['clientCare']['report']['results'] == ($q->id . '_0') ? 'selected' : '') ?>><?= $q->name ?> - No Notes</option>
						<?php
								} else  {
						?>
								<option value="<?= $q->id ?>_1" <?= ($_SESSION['clientCare']['report']['results'] == ($q->id . '_1') ? 'selected' : '') ?>><?= $q->name ?> - YES</option>
								<option value="<?= $q->id ?>_0" <?= ($_SESSION['clientCare']['report']['results'] == ($q->id . '_0') ? 'selected' : '') ?>><?= $q->name ?> - NO</option>
						<?php
								}
							}
						?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Sort By</label>
						<div>
							<select class="form-control input-sm" id="sort" name="sort">
								<option value="" <?= ($_SESSION['clientCare']['report']['sort'] == '' ? 'selected' : '') ?>>Dealer Name</option>
								<option value="response" <?= ($_SESSION['clientCare']['report']['sort'] == 'response' ? 'selected' : '') ?>>Date Responded</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Search</label>
						<div>
							<input type="text" class="form-control input-sm" id="textSearch" name="textSearch" value="<?= $_SESSION['clientCare']['report']['textSearch']  ?>">
						</div>
					</div>
					<div class="form-group">
						<label>&nbsp;</label>
						<div>
							<input type="submit" class="btn btn-primary btn-sm" value="Filter">
						</div>
					</div>
					<!--
					<div class="pull-right">
						<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Report&exportRefundedLeads'">Export Refunded Leads</button>
					</div> -->
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-md-12">
			<div id="summary">

		<?php
			foreach($questionCategories as $q) {
				if($q->id == ClientCareQuestionCategory::NOTES) {
		?>

				<div class="total">
					<div class="num">
						<span class="summaryYes" filterCategory="<?= $q->id ?>" filterValue="1">
							<?= number_format($responseSummary[$q->id]['count']) ?>
						</span>
					</div>
					<div class="desc"><?= $q->name ?></div>
				</div>
		<?php
				} else  {
		?>

				<div class="total">
					<div class="num">
						<span class="summaryYes" filterCategory="<?= $q->id ?>" filterValue="1">
							<?= number_format($responseSummary[$q->id][1]) ?>
						</span> /
						<span class="summaryNo" filterCategory="<?= $q->id ?>" filterValue="0">
							<?= number_format($responseSummary[$q->id][0]) ?>
						</span>
					</div>
					<div class="desc"><?= $q->name ?></div>
				</div>
		<?php
				}
			}
		?>
			</div>
		</div>
	</div>
<?php
	if(is_array($responses)) {
?>
	<div class="row paginationRow">
		<div class="col-xs-9">
			<ul id="responses_pagination" class="pagination pagination-sm"></ul>
		</div>
		<div class="col-xs-3">
			<div style="text-align:right;padding-top:10px">
				<button class="btn btn-xs btn-success" onClick="location.href='?s1=<?= $_GET['s1'] ?>&export'">Export</button>
				<b>Total Responses:</b> <?= number_format($responsesCount) ?>
			</div>
		</div>
	</div>
<?php
	}
?>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed" id="clientCareTbl">
				<thead>
					<tr>
						<th>Dealer</th>
						<th>Event Date</th>
						<th>MEC</th>
						<th>Trainer</th>
						<th>Responder</th>
				<?php
					foreach($questionCategories as $q) {
				?>
						<th class="questionTH"><?= $q->name ?></th>
				<?php
					}
				?>
						<th class="questionTH">Dealer Relations Notes</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
					if(!empty($responses)) {
						foreach($responses as $eventID => $r) {
							$event = $events[$eventID];
				?>
					<tr>
						<td dealerID=<?= $event->dealer->id ?>><?= $r['dealerName'] ?></td>
						<td eventID=<?= $eventID ?>><?= $r['saleStartDate'] ?></td>
						<td><?= $r['accountManager'] ?></td>
						<td><?= $r['trainer'] ?></td>
						<td><?= (!empty($r['dealerStaffNames']) ? implode('<br>',$r['dealerStaffNames']) : '') ?></td>
				<?php
					foreach($questionCategories as $q) {
						if($q->id != ClientCareQuestionCategory::NOTES) {
				?>
						<td class="answerTD <?= (isset($r['answers'][$q->id]) ? ($r['answers'][$q->id] ? 'yes' : 'no') : '') ?>">
							<div
								data-toggle="popover" 
								data-trigger="manual" 
								data-placement="top"
								title="Answered On" 
								data-content="<?= $r['answered_date'][$q->id] ?>">
								<?= (isset($r['answers'][$q->id]) ? ($r['answers'][$q->id] ? 'Y' : 'N') : '') ?>
							</div>
						</td>
				<?php
						} else {
				?>
						<td class="answerTD">
						<?php
							if(!empty($r['answers'][$q->id])) {
						?>
							<button type="button" class="btn btn-primary btn-xs" data-toggle="popover" data-trigger="manual" data-container="body" data-placement="top" data-content="<?= str_replace('"','',nl2br($r['answers'][$q->id])) ?><br>Answered On <?= $r['answered_date'][$q->id] ?>">
								View
							</button>
						<?php
							}
						?>
						</td>
				<?php
						}
					}
				?>
						<td>
							<center>
						<?php
							$clientCareNotes = $event->clientCareNotes;
							if(empty($clientCareNotes)) {
						?>
								<button type="button" class="btn btn-success btn-xs" onClick="displayClientCareNotes(<?= $event->id ?>)">
									Add Notes
								</button>
						<?php
							} else {
						?>
								<button type="button" class="btn btn-danger btn-xs" onClick="displayClientCareNotes(<?= $event->id ?>)">
									Edit Notes
								</button>
						<?php
							}
						?>

							</center>
						</td>
						<td>
							<button type="button" class="btn btn-primary btn-xs" onClick="viewResponse(<?= $event->id ?>)">
								View Response
							</button>
						</td>
					</tr>

				<?php
						}
					}
					else {
				?>
					<tr>
						<th colspan="3">No Responses Found</th>
					</tr>
				<?php
					}
				?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="clientCareNotesModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Dealer Relations - <span id="clientCareNotesEventName"></span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success" id="clientCareNotesSuccess" role="alert">Successfully Saved</div>
						<div class="alert alert-danger" id="clientCareNotesError" role="alert"></div>
						<div class="form-group">
							<label for="comment">Notes</label>
							<textarea class="form-control" id="clientCareNotes"></textarea>
							<input type="hidden" id="clientCareNotesEventID" value="">
						</div>
						<div id="clientCareLastUpdated"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="saveClientCareNotes();">Save Notes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->