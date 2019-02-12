<meta charset="utf-8" />
<link rel="stylesheet" href="<?= AR_SECURE_URL ?>css/leaflet/leaflet.css" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<link href='https://cdn.jsdelivr.net/leaflet.markercluster/1.0.0-rc.1/MarkerCluster.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/leaflet.markercluster/1.0.0-rc.1/MarkerCluster.Default.css' rel='stylesheet' />
<style>
	.ui-datepicker {font-size: 12px}
	.formTbl td ,.formTbl th{vertical-align:middle;text-align:left;font-size:9pt}
	#resultsTbl {border-collapse:collapse;width:100%}
	#resultsTbl td ,#resultsTbl th{vertical-align:middle;text-align:center;font-size:9pt;border-bottom:1px solid #ccc;padding:5px 2px;white-space:nowrap}
	#resultsTbl th {color:white;background-color:#333}

	.smallItalic{
		font-size: 0.9em;
		font-style: italic;
	}
}

</style>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>
<script src="<?= AR_SECURE_URL ?>scripts/leaflet/leaflet.js"></script>
<script src='https://cdn.jsdelivr.net/leaflet.markercluster/1.0.0-rc.1/leaflet.markercluster.js'></script>
<script>
//datepicker
	$(function() {

		$( "#fromDate" ).datepicker({
			changeMonth: true,
      		changeYear: true,
      		dateFormat: "yy-mm-dd"
      	});
		$( "#toDate" ).datepicker({
			changeMonth: true,
      		changeYear: true,
      		dateFormat: "yy-mm-dd"
      	});
      	$('#reportType').on("change", function(){
      		updateReportForm($(this));
      	});
      	$('#reportType').trigger("change");
	});
	//validate date value and format
	function formValidation(){
		var m = /^(19|20)\d\d(-)(0[1-9]|1[012])(-)(0[1-9]|[12][0-9]|3[01])$/;

		if($('#fromDate').val() != '' && $('#toDate').val() != ''){
			if ($('#fromDate').val().match(m) && $('#toDate').val().match(m)) {
				if($('#fromDate').val() < $('#toDate').val()){
					return true;
				}
				else{
					alert('Invalid date range.');
					return false;
				}
			}
			else{
				alert('Please enter correct date formart as YYYY-MM-DD');
				return false;
			}
		}
		else{
			alert('Please select a date range.');
			return false;
		}
	};
</script>

<div id="ar-page-title">Event Map Generator</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<form id="tableForm" method="POST">
	<input type="hidden" name="submitForm" >
	<table class="formTbl" id="eventMapTable">
		<tr>
			<th>Date Range</th>
			<td> <input type="text" id="fromDate" name="fromDate" value="<?= $_SESSION['eventMap']['fromDate'] ?>"> to <input type="text" id="toDate" name="toDate" value="<?= $_SESSION['eventMap']['toDate'] ?>"></td>
		</tr>
		<tr>
		<tr>
			<th>Area Filter</th>
			<td>
				<select id="ldf" name="ldf">
			<?php foreach($ldf->getList() as $lfID => $lfName) { ?>
					<option value="<?= $lfID ?>" <?= ($_SESSION['eventMap']['ldf'] == $lfID ? 'selected' : '') ?>><?= $lfName ?></option>
			<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top:10px">
				<input type="radio" name="clusterSwitch" value="0"
				<?= ($_SESSION['eventMap']['clusterSwitch'] == 0 ? 'checked' : '') ?>
				>
				Markers Only</input>
  				<input type="radio" name="clusterSwitch" value="1"
  				<?= ($_SESSION['eventMap']['clusterSwitch'] == 1 ? 'checked' : '') ?>
				>
				With Clusters</input>
			</tr>
		<tr>
			<td colspan="2" style="padding-top:10px">
				<button type="summit"name="generatebtn" onclick="return formValidation();">Generate Event Map</button>
				<input type="submit" name="reset" value="reset"/>
			</td>
		</tr>
	</table>
</form>
<!-- leeflet map -->
<div id="eventsMap" style="width: 1200x; height: 500px;z-index: 0;"></div>
<script type="text/javascript">
var clusterSwitch = "<?= $_SESSION['eventMap']['clusterSwitch'] ?>";

// display map with marker cluster
if (clusterSwitch == 1) {
	//geoJson
	var geoJsonData = {
		"type": "FeatureCollection",
		"features": [
			<?php
	    	if ($results != '') {
	    	foreach ($results as $v) {
	    		$events = 'Event';
	    		if(!empty(round(intval($v['lng']))) && !empty(round(intval($v['lat'])))){
	    	?>
		    	{
		    		<?php
		    		if($v['eventNum'] > '1'){
	    				$events = 'Events';
	    			}

		    		?>
		            "geometry": {
		                "type": "Point",
		                //Longitute first, then latitute. important!
		              	"coordinates": [<?=$v['lng']?>,<?=$v['lat']?>]
		            },
		            "type": "Feature",
		            "properties": {
		            	//popup messages by left clicking markers
		                "popupContent": "Dealer: <?=$v['dealerName'] ?> <br><div align='center'><?=$v['eventNum'] ?>&nbsp&nbsp<?=$events?></div>"
		            }
		        },
	  		<?php
	  		}
	  	}
	  	}
	  		 ?>
		]
	};
	var tiles = L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZGF2ZXBhbyIsImEiOiJlYTQ1M2EzNzg1NWYwM2JkYTAwMmNiYTI5MGVmZjg0YiJ9.7pJai-RQSTZjZk2Bo6G4jw', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
				'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="http://mapbox.com">Mapbox</a>',
			id: 'mapbox.streets'
		});
	var eventsMap = L.map('eventsMap')
			.addLayer(tiles);
	var markers = L.markerClusterGroup();
	var geoJsonLayer = L.geoJson(geoJsonData, {
		onEachFeature: function (feature, layer) {
			layer.bindPopup(feature.properties.popupContent);
		},
		pointToLayer: function (feature, latlng) {
			return L.circleMarker(latlng, {
				radius: 4,
				fillColor: "#ff1a1a",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.8
			});
		}
	});
	markers.addLayer(geoJsonLayer);
	eventsMap.addLayer(markers);
	eventsMap.fitBounds(markers.getBounds());
}
//display with markers only
else{
	var dealerloc = {
	    "type": "FeatureCollection",
	    "features": [
	    	<?php
	    	if ($results != '') {
	    	foreach ($results as $v) {
	    		$events = 'Event';
	    		if(!empty(round(intval($v['lng']))) && !empty(round(intval($v['lat'])))){
	    	?>
		    	{
		    		<?php
		    		IF($v['eventNum'] > '1'){
	    				$events = 'Events';
	    			}

		    		?>
		            "geometry": {
		                "type": "Point",
		                //Longitute first, then latitute. important!
		              	"coordinates": [<?=$v['lng']?>,<?=$v['lat']?>]
		            },
		            "type": "Feature",
		            "properties": {
		            	//popup messages by left clicking markers
		                "popupContent": "Dealer: <?=$v['dealerName'] ?> <br><div align='center'><?=$v['eventNum'] ?>&nbsp&nbsp<?=$events?></div>"
		            }
		        },
	  		<?php
	  		}
	  	}
	  	}
	  		 ?>
	    ]
	};
	var eventsMap = L.map('eventsMap',{zoomcontrol:false}).setView([25,22], 2);

	L.tileLayer('https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZGF2ZXBhbyIsImEiOiJlYTQ1M2EzNzg1NWYwM2JkYTAwMmNiYTI5MGVmZjg0YiJ9.7pJai-RQSTZjZk2Bo6G4jw', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
				'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="http://mapbox.com">Mapbox</a>',
			id: 'mapbox.streets'
		}).addTo(eventsMap);

	function onEachFeature(feature, layer) {
		var popupContent = "";
		if (feature.properties && feature.properties.popupContent) {
			popupContent += feature.properties.popupContent;
		}
		layer.bindPopup(popupContent);
	}

	L.geoJson([dealerloc], {
		style: function (feature) {
			return feature.properties && feature.properties.style;
		},
		onEachFeature: onEachFeature,
		pointToLayer: function (feature, latlng) {
			return L.circleMarker(latlng, {
				radius: 4,
				fillColor: "#ff1a1a",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.8
			});
		}
	}).addTo(eventsMap);
}
</script>
<?php
//THIS JQUERY SCRIPT WAS ADDED BY ERIC ON APRIL 2, 2015. IT IS CUSTOM JQUERY FOR DEALERSALESWEEKLYSUMMARY.PHP REPORT
?>
<script>
	function dealerSizeSelected(){
		var dealerSize = $('#dealerSize').val();
		//var dealerSalesrepArray =
		if(dealerSize == "huge"){
			$('#salesrepFrom').val('31');
			$('#salesrepTo').val('100');
		}
		else if(dealerSize == "large"){
			$('#salesrepFrom').val('16');
			$('#salesrepTo').val('30');
		}
		else if(dealerSize == "medium"){
			$('#salesrepFrom').val('6');
			$('#salesrepTo').val('15');
		}
		else if(dealerSize == "small"){
			$('#salesrepFrom').val('1');
			$('#salesrepTo').val('5');
		}
		else{
			$('#salesrepFrom').val('n/a');
			$('#salesrepTo').val('n/a');
		}
	}
</script>

