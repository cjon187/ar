<script type="text/javascript" src="scripts/fusioncharts/js/fusioncharts.js"></script>
<script type="text/javascript" src="scripts/fusioncharts/js/themes/fusioncharts.theme.fint.js"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script src="scripts/moment.js"></script>
<link rel="stylesheet" href="scripts/daterangepicker/jquery.comiseo.daterangepicker.css" />
<script src="scripts/daterangepicker/jquery.comiseo.daterangepicker.js"></script>

<script>
	$(function() {

		$('[report_daterangepicker]').daterangepicker({
			presetRanges: [{
		         text: 'Current Week',
		         dateStart: function() { return moment().startOf('week') },
		         dateEnd: function() { return moment().endOf('week') }
		     }, {
		         text: 'Last Week',
		         dateStart: function() { return moment().subtract('week', 1).startOf('week') },
		         dateEnd: function() { return moment().subtract('week', 1).endOf('week') }
		     }],

		     datepickerOptions : {
		         numberOfMonths: 1,
		         minDate: null,
		         maxDate: null
		     }
		 });



		<?php
			if($_SESSION['reportFormula']['dateRange'] != '') {
		?>
			dateRange = jQuery.parseJSON('<?= $_SESSION['reportFormula']['dateRange'] ?>');
			$('[name=dateRange]').daterangepicker("setRange", {start: moment(dateRange.start).startOf('day').toDate(),end: moment(dateRange.end).startOf('day').toDate()});
		<?php
			}10.
		?>

		/*displayInvitationsGraph();
		displayConquestGraph();*/
	});

	/*function displayInvitationsGraph() {
		var iChart = new FusionCharts({
	        type: 'pie2d',
	        renderAt: 'invitationsChart',
	        id: 'invChart',
	        width: '100%',
	        height: '300',
	        dataFormat: 'json',
	        dataSource: {
			    "chart": {
        			"caption": "Invitations",
			        "paletteColors": "#00ea42,#faff00,#ff2121",
			        "bgColor": "#ffffff",
			        "showBorder": "0",
			        "use3DLighting": "0",
			        "showShadow": "0",
			        "enableSmartLabels": "0",
			        "startingAngle": "0",
			        "showPercentValues": "1",
			        "showPercentInTooltip": "0",
			        "decimals": "1",
			        "captionFontSize": "14",
			        "subcaptionFontSize": "14",
			        "subcaptionFontBold": "0",
			        "toolTipColor": "#ffffff",
			        "toolTipBorderThickness": "0",
			        "toolTipBgColor": "#000000",
			        "toolTipBgAlpha": "80",
			        "toolTipBorderRadius": "2",
			        "toolTipPadding": "5",
			        "showHoverEffect": "1",
			        "showLegend": "1",
			        "legendBgColor": "#ffffff",
			        "legendBorderAlpha": "0",
			        "legendShadow": "0",
			        "legendItemFontSize": "10",
			        "legendItemFontColor": "#666666",
			        "useDataPlotColorForLabels": "1"
			    },
			    "data": [
			        {
			            "label": "On Track",
			            "value": "<?= round($invitationGradeCounts[1]) ?>"
			        },
			        {
			            "label": "Out of Tolerance",
			            "value": "<?= intval($invitationGradeCounts[2]) ?>"
			        },
			        {
			            "label": "Unacceptable",
			            "value": "<?= intval($invitationGradeCounts[3]) ?>"
			        },
			        {
			            "label": "Missing",
			            "value": "<?= intval($invitationGradeCounts[4]) ?>"
			        }
			    ]
			}

		});

    	iChart.render();
	}


	function displayConquestGraph() {
		var cChart = new FusionCharts({
	        type: 'pie2d',
	        renderAt: 'conquestChart',
	        id: 'conChart',
	        width: '100%',
	        height: '300',
	        dataFormat: 'json',
	        dataSource: {
			    "chart": {
        			"caption": "Conquest Flyers",
			        "paletteColors": "#00ea42,#faff00,#ff2121",
			        "bgColor": "#ffffff",
			        "showBorder": "0",
			        "use3DLighting": "0",
			        "showShadow": "0",
			        "enableSmartLabels": "0",
			        "startingAngle": "0",
			        "showPercentValues": "1",
			        "showPercentInTooltip": "0",
			        "decimals": "1",
			        "captionFontSize": "14",
			        "subcaptionFontSize": "14",
			        "subcaptionFontBold": "0",
			        "toolTipColor": "#ffffff",
			        "toolTipBorderThickness": "0",
			        "toolTipBgColor": "#000000",
			        "toolTipBgAlpha": "80",
			        "toolTipBorderRadius": "2",
			        "toolTipPadding": "5",
			        "showHoverEffect": "1",
			        "showLegend": "1",
			        "legendBgColor": "#ffffff",
			        "legendBorderAlpha": "0",
			        "legendShadow": "0",
			        "legendItemFontSize": "10",
			        "legendItemFontColor": "#666666",
			        "useDataPlotColorForLabels": "1"
			    },
			    "data": [
			        {
			            "label": "Grade A",
			            "value": "<?= round($conquestGradeCounts[1]) ?>"
			        },
			        {
			            "label": "Grade B",
			            "value": "<?= intval($conquestGradeCounts[2]) ?>"
			        },
			        {
			            "label": "Grade C",
			            "value": "<?= intval($conquestGradeCounts[3]) ?>"
			        }
			    ]
			}

		});

    	cChart.render();
	}*/
</script>
<style>

	.daterangepicker {
		font-size:0.8em;
	}

	#reportTitle {
		font-size:1.8em;
		line-height:1.2em;
		margin-bottom:10px;
	}
	#chartDiv {
		margin-top:20px;
		background-color:#f7f7f7;
		border:1px solid #ddd;
		padding:20px 20px 20px 20px;
		border-radius:10px;
	}

	.summary {
		border:1px solid #ddd;
		background-color:#eee;
		border-radius:5px;
		padding:20px;
	}
	.summary .type {
		font-size:1.5em;
		color:#555;
		text-align:center;
		text-transform: uppercase;
		font-weight:bold;
	}
	.summary .total  {
		font-size:1em;
		text-align:center;
		display:inline-block;
		margin-top:20px;
		padding-right:20px;

	}
	.summary .total .num {
		line-height:1.3em;
		font-size:2em;
		font-weight:bold;
	}
	.summary .total .desc {
		font-weight:bold;
	}
	.summary .onTrack {
		color:green;
	}
	.summary .outOfTolerance {
		color:orange;
	}
	.summary .unacceptable {
		color:blue;
	}
	.summary .missing {
		color:red;
	}


	#table {
		background-color:white;
	}
	#table td:not(:first-of-type),#table th:not(:first-of-type) {
		text-align:center;
	}
	#table thead,.ytdTotals {
		background-color:#ddd;
		font-weight:bold;
	}
	.totals {
		background-color:#333;
		color:white;
		font-weight:bold;
	}
	.positive {
		color:green;
		font-weight:bold;
	}
	.negative {
		color:red;
		font-weight:bold;
	}

	[field="quantity"] {
		border-left:2px solid #333;
	}

	[grade="1"] {
		background-color:#c6eab6;
	}
	[grade="2"] {
		background-color:#fcfc8f;
	}
	[grade="3"] {
		background-color:#9eb9ff;
	}
	[grade="4"] {
		background-color:#fc8f8f;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="pull-left">
			<div id="reportTitle">
				FCA Canada Private Sale Formula Report
			</div>
		</div>
		<div class="pull-right">
			<a href="?s1=report" style="font-size: 18px; font-weight:bold; color: blue; ">
				Back to Reports
			</a>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="filtersDiv">
			<form method="POST">
				<div class="form-group form-inline">
					<select class="form-control input-sm" name="dealerFilter">
						<option value="<?= LogicalDealerFilters::CA_C ?>" <?= ($_SESSION['reportFormula']['dealerFilter'] == LogicalDealerFilters::CA_C ? 'SELECTED' : '') ?>>FCA Canada</option>
						<option value="<?= LogicalDealerFilters::ABC ?>" <?= ($_SESSION['reportFormula']['dealerFilter'] == LogicalDealerFilters::ABC ? 'SELECTED' : '') ?>>FCA ABC</option>
						<option value="<?= LogicalDealerFilters::EBC ?>" <?= ($_SESSION['reportFormula']['dealerFilter'] == LogicalDealerFilters::EBC ? 'SELECTED' : '') ?>>FCA EBC</option>
						<option value="<?= LogicalDealerFilters::QBC ?>" <?= ($_SESSION['reportFormula']['dealerFilter'] == LogicalDealerFilters::QBC ? 'SELECTED' : '') ?>>FCA QBC</option>
						<option value="<?= LogicalDealerFilters::WBC ?>" <?= ($_SESSION['reportFormula']['dealerFilter'] == LogicalDealerFilters::WBC ? 'SELECTED' : '') ?>>FCA WBC</option>
					</select>
					<select class="form-control input-sm" name="month">
					<?php
						for($i = 1;$i <= 12;$i++) {
					?>
						<option value="<?= $i ?>" <?= ($_SESSION['reportFormula']['month'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime("2015-".$i."-01")) ?></option>
					<?php
						}
					?>
					</select>
					<select class="form-control input-sm" name="year">
					<?php
						for($i = (date("Y") + 1) ;$i >= 2010 ;$i--) {
					?>
						<option value="<?= $i ?>" <?= ($_SESSION['reportFormula']['year'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
					<?php
						}
					?>
					</select>

					<input type="submit" class="btn btn-success btn-sm" value="Filter">
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div id="chartDiv">
			<!-- <div class="row">
				<div class="col-md-6">
					<div id="invitationsChart"></div>
				</div>
				<div class="col-md-6">
					<div id="conquestChart"></div>
				</div>
			</div> -->
			<div class="row">
				<div class="col-md-6">
					<center>
						<div class="summary">
							<div class="type">
								Invitations
							</div>
							<div class="total onTrack">
								<div class="num">
									<span>
										<?= number_format($invitationGradePercents[1]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($invitationGradeCounts[1]) ?> On Track</div>
							</div>
							<div class="total outOfTolerance">
								<div class="num">
									<span>
										<?= number_format($invitationGradePercents[2]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($invitationGradeCounts[2]) ?> Out of Tolerance</div>
							</div>
							<div class="total unacceptable">
								<div class="num">
									<span>
										<?= number_format($invitationGradePercents[3]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($invitationGradeCounts[3]) ?> Unacceptable</div>
							</div>
							<div class="total missing">
								<div class="num">
									<span>
										<?= number_format($invitationGradePercents[4]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($invitationGradeCounts[4]) ?> Missing</div>
							</div>
						</div>
					</center>
				</div>
				<div class="col-md-6">
					<center>
						<div class="summary">
							<div class="type">
								Conquest Flyers
							</div>
							<div class="total onTrack">
								<div class="num">
									<span>
										<?= number_format($conquestGradePercents[1]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($conquestGradeCounts[1]) ?> On Track</div>
							</div>
							<div class="total outOfTolerance">
								<div class="num">
									<span>
										<?= number_format($conquestGradePercents[2]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($conquestGradeCounts[2]) ?> Out of Tolerance</div>
							</div>
							<div class="total unacceptable">
								<div class="num">
									<span>
										<?= number_format($conquestGradePercents[3]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($conquestGradeCounts[3]) ?> Unacceptable</div>
							</div>
							<div class="total missing">
								<div class="num">
									<span>
										<?= number_format($conquestGradePercents[4]) ?>%
									</span>
								</div>
								<div class="desc"><?= number_format($conquestGradeCounts[4]) ?> Missing</div>
							</div>
						</div>
					</center>
				</div>
			</div>
			<br><br>
			<table class="table table-condensed" id="table">
				<thead>
					<tr>
						<th>Dealership</th>
						<th>Event Date</th>
						<th>Events in Month</th>
						<th>Salesreps</th>
						<th field="quantity">Invitations</th>
						<th>Formula</th>
						<th field="quantity">Conquests</th>
						<th>Formula</th>
					</tr>
				</thead>
				<tbody>
		<?php
			foreach($events as $eid => $e) {
		?>
					<tr>
						<td><?= $dealers[$e->dealerID]->name ?></td>
						<td><?= $e->displayEventDate() ?></td>
						<td><?= $dealerStats[$e->dealerID]->numEventsInMonth ?></td>
						<td><?= $dealerStats[$e->dealerID]->numSalesreps ?></td>
						<td group="invitations" field="quantity" grade="<?= $invitationGrades[$eid]['grade'] ?>"><?= $invitationGrades[$eid]['quantity'] ?></td>
						<td group="invitations" field="formula" grade="<?= $invitationGrades[$eid]['grade'] ?>"><?= $invitationGrades[$eid]['formula'] ?></td>
						<td group="conquests" field="quantity" grade="<?= $conquestGrades[$eid]['grade'] ?>"><?= $conquestGrades[$eid]['quantity'] ?></td>
						<td group="conquests" field="formula" grade="<?= $conquestGrades[$eid]['grade'] ?>"><?= $conquestGrades[$eid]['formula'] ?></td>
					</tr>
		<?php
			}
		?>

				</tbody>
			</table>
		</div>
	</div>
</div>