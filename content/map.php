
<!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble-compiled.js"></script> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>



<link rel="stylesheet" href="<?= AR_SECURE_URL ?>css/leaflet/leaflet.css" />
<script src="<?= AR_SECURE_URL ?>scripts/leaflet/leaflet.js"></script>
<script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.2.0/leaflet-omnivore.min.js'></script>

<script src="scripts/sweetalert/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="scripts/sweetalert/sweetalert.css">

<style>

    .tick {
        border: 1px solid transparent; /*follows slide handle style for sizing purposes*/
        position: absolute;
        margin-left: -.6em;
        text-align:center;
        left: 0;
        color:#555;
        font-size:0.9em
    }

    .mapPopUp .leaflet-popup-tip,
    .mapPopUp .leaflet-popup-content-wrapper {
    	background-color: #444;
    	color: white;
    	line-height:1em;
    }
</style>

<script>

	var redDot = L.icon({
	    iconUrl: 'images/map/red.png'
	});
	var blueDot = L.icon({
	    iconUrl: 'images/map/blue.png'
	});
	var blackDot = L.icon({
	    iconUrl: 'images/map/black.png'
	});

	var initialCoords;

	var initialRadius = <?= $_SESSION['map_distance']*1000 ?>;
	var circle;
	var bounds = [];
	var myLocFound = true;
	var osmUrl='https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiZGF2ZXBhbyIsImEiOiJlYTQ1M2EzNzg1NWYwM2JkYTAwMmNiYTI5MGVmZjg0YiJ9.7pJai-RQSTZjZk2Bo6G4jw';

	$(function() {

		navigator.geolocation.getCurrentPosition(setInitialCoords, function() {
			initialize();
		});
	});

	function initialize() {

		solds_map = L.map('map_canvas', { zoomControl:false });
		new L.Control.Zoom({ position: 'topright' }).addTo(solds_map);


	<?php
		foreach($markerData as $re) {

			if(empty($re['lat'])) continue;

			$dirParam = $re['dealerName'] . ', ' . $re['address'] . ', ' . $re['city'] . ', 	' . $re['province'] . ', 	' . $re['postalCode'];

			$popup = '<div class="mapPopup">';
			$popup .= '<font style="font-size:12pt;font-weight:bold;color:yellow">' . $re['dealerName'] . '</font><br>';
			$popup .= '<font style="font-size:10pt;">' . $re['address'] . ' ' . trim($re['city'] . ' ' . $re['county']) . '</font><br>';
			$popup .= '<font style="font-size:10pt;">' . $re['phone'] . '</font>';
			$popup .= '<div style="padding-top:10px;font-size:9pt;"><i>' . $re['brands'] . '</i></div>';
			$popup .= '<div style="padding-top:10px;font-size:9pt;color:yellow;cursor:pointer" onClick="getDirections(\'' . $dirParam. '\');"><i>Directions</i></div>';
			$popup .= '<div style="padding-top:10px;"><a href="?s1=dealershipBlade&page=information&dealerID=' . $re['dealerID'] . '" style="font-size:9pt;color:yellow;" target="_blank"><i>See Dealer</i></a></div>';
			$popup .= '</div>';

			if($re['isProspect']) $icon = 'redDot';
			else if(in_array($re['dealerID'],$recentDealers)) $icon = 'blueDot';
			else $icon = 'blackDot';

		?>
			ll = [<?= trim($re['lat']) ?>, <?= trim($re['lng']) ?>];
			marker = L.marker(ll, {icon: <?= $icon ?>}).addTo(solds_map);
			bounds.push(ll);

			popup = L.popup().setContent('<?= ajaxHTML($popup) ?>');
			popUpOptions = {
	    		'maxWidth': '200',
		        'className' : 'mapPopUp'
		    }
			marker.bindPopup(popup,popUpOptions);
	<?php
		}
	?>

		if(initialCoords != null) {
			solds_map.setView(initialCoords);
			solds_map.setZoom(10);
		} else {
			if(bounds.length != 0) {
				solds_map.fitBounds(bounds);
			} else {
				solds_map.setView(new L.LatLng(56.1304,-106.3468));
				solds_map.setZoom(4);
			}
		}


		circle = L.circle(solds_map.getCenter(),initialRadius);

		$( "#distance_slider" ).slider({

			value:<?= $_SESSION['map_distance'] ?>,
			min: 0,
			max: 30,
			slide: function( event, ui )
			{
		        circle.setRadius(ui.value*1000);
			},
			change: function( event, ui )
			{
		        changeCircle();
			}
	    });

		circle.on({
			mousedown: function () {
				solds_map.dragging.disable();
				solds_map.on('mousemove', function (e) {
					circle.setLatLng(e.latlng);
				});
			}
		});

		solds_map.on('mouseup',function(e){
			solds_map.dragging.enable();
			solds_map.removeEventListener('mousemove');
			changeCircle();
		})

		circle.addTo(solds_map);

	/*var osm = new L.TileLayer(osmUrl);
	solds_map.addLayer(osm);*/
		L.tileLayer(osmUrl).addTo(solds_map);
		changeCircle();
	}

	function zoomToMyLocation() {
		if(initialCoords != null) {
			solds_map.setView(initialCoords);
			solds_map.setZoom(10);
			circle.setLatLng(initialCoords);
		} else {

			swal({
				title: "Error!",
				text: "Unable to get current location.",
				type: "error",
				confirmButtonText: "Ok"
			});
		}
	}

	function setInitialCoords(position)
	{
		initialCoords= new L.LatLng(position.coords.latitude,position.coords.longitude);
		initialize();
    }

	function changeCircle()
	{
		var within = $( "#distance_slider" ).slider( "option", "value" );
		if(within == 0) within = 1;
		else if(within >= 30) within = 0;

		if(circle != undefined)
		{
			ll = circle.getLatLng();
			$.ajax({data:	{distance: within,
							 lat: ll.lat,
							 lng: ll.lng},
					type:	'POST',
					dataType: 'script'
			   	    });
		}
	}

	function recenterCircle() {
		circle.setLatLng(solds_map.getCenter());
		changeCircle();
	}

	function getDirections(param) {
		if(initialCoords != null) {
			window.open('https://www.google.com/maps/dir/' + initialCoords.lat + ',' + initialCoords.lng + '/' + param);
		} else {
			swal({
				title: "Error!",
				text: "Unable to get current location.",
				type: "error",
				confirmButtonText: "Ok"
			});
		}
	}

</script>

<div id="ar-page-title">Map</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<form method="POST" class="form-inline">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Region</label>

				<select class="form-control input-sm" name="businessCenter" onChange="this.form.submit()">
					<option value="">-- Please select a region --</option>
			<?php
				foreach($ldf->getList() as $id => $name) {
					if(empty($id)) continue;
			?>
					<option value="<?= $id ?>" <?= ($_SESSION['map']['businessCenter'] == $id ? 'selected' : '') ?>><?= $name ?></option>
			<?php
				}
			?>
				</select>
			</div>
			<div class="form-group">
				<label>Prospect</label>

				<select class="form-control input-sm" name="prospect" onChange="this.form.submit()">
					<option value="" <?= ($_SESSION['map']['prospect'] == '' ? 'SELECTED' : '') ?>>Clients & Prospects</option>
					<option value="allclients" <?= ($_SESSION['map']['prospect'] == 'allclients' ? 'SELECTED' : '') ?>>All Clients</option>
					<option value="recentclients" <?= ($_SESSION['map']['prospect'] == 'recentclients' ? 'SELECTED' : '') ?>>Recent Clients</option>
					<option value="pastclients" <?= ($_SESSION['map']['prospect'] == 'pastclients' ? 'SELECTED' : '') ?>>Past Clients</option>
					<option value="prospects" <?= ($_SESSION['map']['prospect'] == 'prospects' ? 'SELECTED' : '') ?>>Prospects</option>
				</select>
			</div>
			<div class="form-group">
				<label>OEM</label>

				<select class="form-control input-sm" name="oem" onChange="this.form.submit()">
					<option value="">All OEMs</option>
			<?php
				foreach($oems as $id => $name) {
			?>
					<option value="<?= $id ?>" <?= ($_SESSION['map']['oem'] == $id ? 'selected' : '') ?>><?= $name ?></option>
			<?php
				}
			?>
				</select>
			</div>
			<!-- <div class="form-group">
				<label>Popup</label>

				<select class="form-control input-sm" name="popup" onChange="this.form.submit()" style="width:80px">
					<option value="" <?= ($_SESSION['map']['popup'] == '' ? 'SELECTED' : '') ?>>Click</option>
					<option value="mouseover" <?= ($_SESSION['map']['popup'] == 'mouseover' ? 'SELECTED' : '') ?>>Mouse Over</option>
				</select>
			</div> -->

		</div>
	</div>

</form>

<div style="<?= (empty($_SESSION['map']['businessCenter']) ? 'display:none' : '') ?>">
	<div class="row" style="margin-top:10px;margin-bottom:10px;">
		<div class="col-md-6">
			<div style="float:left;width:300px;padding-bottom:20px">
				<div id="distance_slider"></div>
				<div style="position:relative;width:100%;">
					<span class="tick">1</span>
				    <span class="tick" style="left:16.666%;">5</span>
				    <span class="tick" style="left:33.333%;">10</span>
				    <span class="tick" style="left:49.998%;">15</span>
				    <span class="tick" style="left:66.664%;">20</span>
				    <span class="tick" style="left:83.333%;">25</span>
				    <span class="tick" style="left:100%;white-space:nowrap">&#8734;</span>
				</div>
			</div>
			<!--<div style="position:absolute;top:-40px"><div style="color:red;font-size:0.8em" id="searchError"></div>-->
		</div>
		<div class="col-md-6">
			<div style="text-align:right">
				<b><?= number_format(count($markerData)) ?></b> Dealers
			</div>
		</div>
	</div>


	<div id="map_canvas" style="width:100%; height:450px;"></div>

	<div class="row" style="margin-top:5px">
		<div class="col-md-8">
			<div class="pull-left" style="padding-right:10px"><img src="images/map/blue.png"></div>
			<div class="pull-left" style="padding-right:10px">Recent Clients</div>
			<div class="pull-left" style="padding-right:10px"><img src="images/map/black.png"></div>
			<div class="pull-left" style="padding-right:10px">Past 90 Days Clients</div>
			<div class="pull-left" style="padding-right:10px"><img src="images/map/red.png"></div>
			<div class="pull-left" style="padding-right:10px">Prospects</div>
		</div>
		<div class="col-md-4">
			<div style="text-align:right">
				<button class="btn btn-primary btn-xs" onClick="recenterCircle()">Recenter</button>
				<button class="btn btn-primary btn-xs" onClick="zoomToMyLocation()">Center On My Location</button>
			</div>
		</div>
	</div>

	<br>
	<div class="row" class="margin-top:30px">
		<div class="col-md-12">
			<div id="dealersList"></div>
		</div>
	</div>
</div>