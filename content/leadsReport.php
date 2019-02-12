<?php include_once('leads_header.php'); ?>
<style>
	#leadsTbl {
		font-size:0.9em;
	}

	#leadsTbl tbody{
  		border-bottom: 30px solid white;
	}
	#leadsTbl thead{
		background-color:#333;
		color:white;
	}

	.groupName {
		font-size:1.3em;
		color:yellow;
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
		         dateStart: function() { return moment("20150901", "YYYYMMDD") },
		         dateEnd: function() { return moment() }
		     }],

		     datepickerOptions : {
		         numberOfMonths: 1,
		         minDate: null,
		         maxDate: null
		     }
		 });

		<?php 
			if($_SESSION['leads']['report']['dateRange'] != '') {
		?>
			dateRange = jQuery.parseJSON('<?= $_SESSION['leads']['report']['dateRange'] ?>');
			$('[name=dateRange]').daterangepicker("setRange", {start: moment(dateRange.start).startOf('day').toDate(),end: moment(dateRange.end).startOf('day').toDate()});
		<?php
			}
		?>
	});
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li class="active">
					Report
				</li>
			</ol>
		</div>
	</div>
	<form method="POST" class="form-inline">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group" style="padding-right:10px">
					<label>Date</label>
					<div class="daterangepicker">
						<input report_daterangepicker name="dateRange" value="<?= $_SESSION['leads']['report']['dateRange'] ?>" onChange="if(!init) this.form.submit(); else init = false;">
					</div>
				</div>
				<div class="form-group">
					<label>Group By</label>
					<div>						
						<select class="form-control input-sm" name="groupBy" onChange="this.form.submit(); ">
							<option value="manager" <?= ($_SESSION['leads']['report']['groupBy'] == 'manager' ? 'SELECTED' : '') ?>>Manager</option>
							<option value="source" <?= ($_SESSION['leads']['report']['groupBy'] == 'source' ? 'SELECTED' : '') ?>>Lead Source</option>
						</select>
					</div>
				</div>
				<div class="pull-right">
					<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Report&exportRefundedLeads'">Export Refunded Leads</button>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed" id="leadsTbl">
			<?php
				foreach($groups as $info) {
			?>
				<thead>
					<tr>
						<th><div class="groupName"><?= $info['name'] ?></div></th>
						<th>Lost Leads</th>
						<th>Acquired Leads</th>
						<th>Refunded Leads</th>
						<th>Call Attempts</th>
						<th>Approvals</th>
						<th>Appointments</th>
						<th>Shows</th>
						<th>Solds</th>
						<th>Sold New</th>
						<th>Sold Used</th>
						<th>Delivered</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach($info['list'] as $group) {
				?>
					<tr>
						<th><?= $group['info'] ?></th>
						<th><?= number_format($group['stats']['lostLeads']) ?></th>
						<th><?= number_format($group['stats']['acquiredLeads']) ?></th>
						<th><?= number_format($group['stats']['refundedLeads']) ?></th>
						<th><?= number_format($group['stats']['Call']) ?></th>
						<th><?= number_format($group['stats']['approvals']) ?></th>
						<th><?= number_format($group['stats']['Appointment']) ?></th>
						<th><?= number_format($group['stats']['Show']) ?></th>
						<th><?= number_format($group['stats']['Sale']) ?></th>
						<th><?= number_format($group['stats']['sold_new']) ?></th>
						<th><?= number_format($group['stats']['sold_used']) ?></th>
						<th><?= number_format($group['stats']['sold_delivered']) ?></th>
					</tr>

				<?php
					} 
				?>

				</tbody>
			<?php
				}
			?>
			</table>
		</div>
	</div>
</div>