<style>
	.dashboard {width:1500px;border-collapse:collapse}
	.dashboard td {font-family:Tahoma}
	.boxTitle {position:absolute;padding:5px 10px;background-color:#000055;color:white;font-size:14pt;font-weight:bold}
	
</style>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="scripts/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="scripts/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>

<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?client=gme-absoluteresultsproductions&sensor=false"></script>
<script type="text/javascript" src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble-compiled.js"></script>
 -->
<link rel="stylesheet" href="<?= AR_SECURE_URL ?>css/leaflet/leaflet.css" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>
<script src="<?= AR_SECURE_URL ?>scripts/leaflet/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
<link rel="stylesheet" type="text/css" href="scripts/jqplot/jquery.jqplot.css" />
<script>
	$(function() {		
		loadBox('totals','<?= $date ?>');
		loadBox('apptPerSalesrep','<?= $date ?>');
		loadBox('eventSuccess','<?= $date ?>');
		loadBox('eventsCount','<?= $date ?>');
		loadBox('eventsMap','<?= $date ?>');
		loadBox('topEvents','<?= $date ?>');
		/*
		*/
	});
	
	function loadBox(box,date)
	{
		$('#' + box + 'Details').html('');
		$('#' + box).html('<img src="images/loading.gif">');
		process(box,date);
		/*
		$.ajax({data:	{loadBox: box,
						 date: date},
				type:	'GET',
				dataType: 'script'
		   	    });
		 */
	}
	
	function process(box,date)
	{
		$.ajax({data:	{process: box,
						 date: date},
				type:	'GET',
				dataType: 'script'
		   	    });
	}
</script>	
<table class="dashboard">
	<tr>
		<td style="border-bottom:1px dotted #aaa;width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle">Totals YTD</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;padding-top:5px;float:right;color:#aaa" id="totalsDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="totals">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
		<td style="border:1px dotted #aaa;border-top:none;width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle">Appts / Salesrep</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;padding-top:5px;float:right;color:#aaa" id="apptPerSalesrepDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="apptPerSalesrep">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
		<td style="border-bottom:1px dotted #aaa;width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle">Events Success</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;float:right;color:#aaa" id="eventSuccessDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="eventSuccess">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
	</tr>
	<tr>
		<td style=";width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle"># Private Sales</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;padding-top:5px;float:right;color:#aaa" id="eventsCountDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="eventsCount">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
		<td style="border-left:1px dotted #aaa;border-right:1px dotted #aaa;width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle">Geographic</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;padding-top:5px;float:right;color:#aaa" id="eventsMapDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="eventsMap">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
		<td style=";width:33%;height:350px;">
			<div style="position:relative;height:100%;">
				<div class="boxTitle">Top 15 Events</div>
				<center>
					<table style="height:100%">
						<tr>
							<td style="height:100%;text-align:center;vertical-align:middle">
								<div style="padding-right:10px;padding-top:5px;float:right;color:#aaa" id="topEventsDetails"></div>
								<div style="padding-top:40px;padding-left:10px" id="topEvents">
									<img src="images/loading.gif">
								</div>
							</td>
						</tr>
					</table>
				</center>
			</div>
		</td>
	</tr>
</table>

