<style>
</style>

<div id="ar-page-title">Reports</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="panel panel-default">
	<div class="panel-heading">Report Generator</div>
	<div class="panel-body">
		<button class="btn btn-success" onClick="location.href='?s1=report&s2=Generator'">Go to Report Generator</button>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Management Reports</div>
			<div class="panel-body">
				<table class="table table-condensed table-striped">
					<thead>
						<tr>
							<th>Report</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								Monthly Trend
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=MonthlyTrend'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								Month Over Month
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=MonthOverMonth'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								2019 Forecast
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=Forecast'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								Private Sale Analysis
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=PrivateSaleAnalysis'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								FCA Canada Formula Report
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=Formula'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								New Lost Penetration Report
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=LostPenetration'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								Trainer's Report
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=TrainerAverages'">Go</button>
							</td>
						</tr>
						<tr>
							<td>
								Private Sale Images
							</td>
							<td>
								<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=EventSummaryImages'">Go</button>
							</td>
						</tr>
						<?php if(PageSection::getPageSectionBoolean($_SESSION['login']['staffID'],PageSection::REPORT_PRODUCTION_LOG)) { ?>
							<tr>
								<td>
									Production Board Report
								</td>
								<td>
									<button class="btn btn-success btn-xs" onClick="location.href='?s1=report&s2=ProductionLog'">Go</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>