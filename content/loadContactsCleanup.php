<script src="scripts/sweetalert2/sweetalert2.min.js"></script>
<link rel="stylesheet" href="css/sweetalert2/sweetalert2.min.css"/>
<script src="<?= AR_SECURE_URL ?>scripts/websocket/jquery.simple.websocket.js"></script>
<script src="<?= AR_SECURE_URL ?>scripts/arsocket.js"></script>
<script>
<?php
	if(is_array($_SESSION['portalgeocoding'])) {
		$doingGeo = 'true';
	} else {
		$doingGeo = 'false';
	}
	echo <<< JS
//var isDoingGeo = {$doingGeo};
	var isDoingGeo = false;
JS;
?>
	var webSocket;
	var geoJobID;

	function findCompanies(){
		$('#statusDiv').html('Find Companies Processing...');

		$.ajax({data:	{findCompanies: ''},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});
		return false;
	}

	function formatNames(){
		$('#statusDiv').html('Format Names Processing...');

		$.ajax({data:	{formatNames: '',
						 format: $('#nameFormat').val()},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});
		return false;
	}

	function stripMiddleName(){
		$('#statusDiv').html('Strip Middle Name Processing...');

		$.ajax({data:	{stripMiddleName: ''},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});

		return false;
	}

	function frenchNamesToSalutations(overwrite){
		console.log(overwrite);
		$('#statusDiv').html('Calculating Salutation based on first name...');
		$.ajax({data:	{frenchNamesToSalutations: '', overwrite: overwrite},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});

		return false;
	}

	function frenchSalutationAliasStandardization(){
		$('#statusDiv').html('Standardizing Salutations...');

		$.ajax({data:	{frenchSalutationAliases: ''},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});

		return false;
	}

	function formatVehicles(){
		$('#statusDiv').html('Format Vehicles Processing...');

		$.ajax({data:	{formatVehicles: ''},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});

		return false;
	}

	function addAreaCode(){
		areaCode = $('#areacode').val();
		if(areaCode.length != 3){
			ARAlert("Area code needs to be 3 digits long");
			return false;
		}

		$('#statusDiv').html('Add Area Code Processing...');

		$.ajax({data:	{addAreaCode: '',
						 areaCode: areaCode},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});
		return false;
	}

	function formatDates(){
		$('#statusDiv').html('Format Dates Processing...');

		$.ajax({data:	{formatDates: '',
						 dateFormat: $('#dateFormat').val()},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});
		return false;
	}

	function vinDecode()
	{
		$.ajax({data:	{vinDecode: ''},
				type:	'GET',
				dataType: 'json',
				success: function(data) {
					if(data.success == true){
						$('#statusDiv').html(data.message);
					}else{
						$('#statusDiv').html('MySql Error: '+data.error);
					}
		   	    }
		   	});
	}

	function startGeocode() {
		//first check the socket works
		if(!webSocket.isConnected()) {
			ARAlertError("There is a problem with the web socket.<br/>Please contact DEV to restart<br/>Geolocating is working in the background");
			//return false; //by setting this false we stop the background geo process.
		} else {
			showGeocodingAlert();
		}
		$.ajax({
			data: {
				startgeocode: 'yup'
			},
			type:'GET',
			dataType:'json',
			success: function(resp) {
				if(resp.hasOwnProperty('errors')) {
					swal.close(geocodeAlert);
					ARAlertError(resp.errors[0]);
				} else {
					geoJobID = resp.data.jobID;
					$('#bar').text('Starting.....');
				}
			},
			error:function(req,status,err) {

			},
			complete: function(req,status) {
			}
		});
		return false;
	}

	function stopGeocode() {

		swal.close(geocodeAlert);
		ARAlertLoading('Updating database...');

		$.ajax({
			data: {
				stopgeocode: 'yup'
			},
			type:'GET',
			dataType:'json',
			success: function(resp) {
				//console.log(resp);
				ARAlertClose();
				swal({
					title: 'Geocoding',
					text: 'ALL DONE!',
					showCancelButton: false,
					confirmButtonText: 'YAY!',
					type: 'success'
				});
			},
			error:function(req,status,err) {
				console.log(err);
			}
		});

	}

var geocodeAlert;

function showGeocodingAlert() {
	var title = 'Geocoding';
	if(isDoingGeo) {
		title = title+' (resuming)';
	}
	geocodeAlert = new swal({
		title: title,
		type: 'info',
		html:
			'<h3 id="process-type"></h3><div id="r" class="meter" style="width: 100%; border:2px solid #3fc3ee; height: 60px; position: relative; background: none;"><h2 id="bar" style="display: block; height: 100%; position: relative; overflow: hidden; background: #3fc3ee; width: 0;"></h2></div>',
		showCloseButton: false,
		showCancelButton: false,
		allowOutsideClick: false,
		showConfirmButton: false,
		allowEscapeKey: false
	});
}


$(function() {
	if(isDoingGeo) {
		showGeocodingAlert();
	}

	webSocket = new ARWebSocket({
		url: 'wss://<?= WEBSOCKET_HOST  ?>:<?= WEBSOCKET_SECURE_PORT ?>',
		onMessage: function(e) {
			var data = e;
			var jobID = data.jobID;
			handleGeocodeProcess(data,jobID);
		}
	});

});

function handleGeocodeProcess(data,jobID) {

	if(jobID == geoJobID) {
		var totalRows = parseInt(data.totalRows);
		var finishedRows = parseInt(data.finishedRows);
		var msgType = data.msgType;
		var processType = data.processType;
		var pctDone = 0;
		if(totalRows > 0) {
			pctDone = Math.round((finishedRows/totalRows) * 100);
		}
		if(msgType == 'error') {
			swal.close(geocodeAlert);
			if(processType == "addressVerifyErrorUnfound") {
				ARAlertError("The maximum number of unfounds has been reached<br/>Please check your list and when re-importing check the proper column types have been checked");
			} else {
				ARAlertError(data.message);
			}
		}

		if(msgType == 'complete') {
			//alert('done!');
			stopGeocode();
			return;
		}
		if(processType == 'addressVerify') {
			$('#process-type').text('Verifying....'+finishedRows+' of '+totalRows);
			if(msgType == 'done') {
				$('#bar').css({width:'0%'}).text();
			}
		} else {
			$('#process-type').text('Updating DB....'+finishedRows+' of '+totalRows);
		}
		if(msgType == 'update') {
			$('#bar').css({width:pctDone+'%'}).text(pctDone+'%');
		}
	} else {
		//these messages are someone elses
		//console.log(jobID+' not for me');
	}
}

function showSocketError(msg) {
	swal.close(geocodeAlert);
	ARAlertError(msg);
}

function doSocketError(msg) {
	if(typeof msg === 'undefined') {
		msg = "There is a problem with the web socket<br/>Please contact DEV to get it restarted.<br/>Geolocating will work in the background";
	}
	showSocketError(msg);
	clearInterval(socketTimeout);
}
</script>
<style>
	.cleanupTbl {border-collapse:collapse}
	.cleanupTbl td {padding:10px 5px;border-top:1px solid #cccccc;border-bottom:1px solid #cccccc;vertical-align:middle}
</style>

<?php
	$dealerInfo = displayDealerInfo($_SESSION['loadContacts']['dealerID']);
?>

<div class="row">
	<div class="col-xs-6">
		<div>
			<h3>Dealer: <?= $dealerInfo['dealerID'] ?> - <?= $dealerInfo['dealerName'] ?> </h3>
		</div>
		<table cellspacing="0" cellpadding="0" class="cleanupTbl">
		<?php if($_SESSION['loadContacts']['canAddressVerify']) { ?>
			<tr>
				<td><a href="#" onClick="startGeocode()">Start Geocode</a></td>
			</tr>
		<?php } ?>
			<tr>
				<td><a href="#" onClick="formatNames()">Format Names</a></td>
				<td>
					<select id="nameFormat">
						<option value="l_f">Lastname, Firstname</option>
						<option value="f_l">Firstname, Lastname</option>
						<option value="lf">Lastname Firstname</option>
						<option value="fl">Firstname Lastname</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><a href="#" onClick="findCompanies()">Separate Companies</a></td>
			</tr>
			<tr>
				<td colspan="2"><a href="#" onClick="stripMiddleName()">Strip Middle Name</a></td>
			</tr>
			<tr>
				<td><a href="#" onClick="frenchNamesToSalutations(false)">French Names To Salutations</a></td>
				<td><a href="#" onClick="frenchNamesToSalutations(true)">Overwrite Salutations</a></td>
			</tr>
			<tr>
				<td><a href="#" onClick="frenchSalutationAliasStandardization()">French Salutation Alias Standardization</a></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">Manually Check Addresses</td>
			</tr>
			<tr>
				<td><a href="#" onClick="addAreaCode()">Add Area Code</a></td>
				<td><input type="text" id="areacode" style="width:50px"></td>
			</tr>
			<tr>
				<td colspan="2"><a href="#" onClick="formatVehicles()">Format Vehicle Year</a></td>
			</tr>
			<tr>
				<td><a href="#" onClick="formatDates()">Format Dates</a></td>
				<td>
					<select id="dateFormat">
						<option value="%m/%d/%Y">mm/dd/yyyy</option>
						<option value="%d/%m/%Y">dd/mm/yyyy</option>
						<option value="%Y/%m/%d">yyyy/mm/dd</option>
						<option value="%Y/%d/%m">yyyy/dd/mm</option>
						<option value="%m/%d/%y">mm/dd/yy</option>
						<option value="%d/%m/%y">dd/mm/yy</option>
						<option value="%e-%b-%y">dd-mmm-yy</option>
						<option value="%e-%b-%Y">dd-mmm-yyyy</option>
						<option value="excelDate">ddddd</option>
					</select>
				</td>
			</tr>
			<?php if(in_array($dealerInfo['countryID'],[COUNTRY_CA,COUNTRY_US])) { ?>
				<tr>
					<td colspan="2"><a href="#" onClick="document.getElementById('statusDiv').innerHTML = 'Decoding VIN...Initializing<br><br>';vinDecode()">Decode VIN</a></td>
				</tr>
			<?php } ?>

		</table>
	</div>
	<div class="col-xs-6">
		<div>
			<h3 style="text-decoration:underline;">Clean up Status</h3>
		</div>
		<div id="statusDiv" style="font-weight:bold;color:red;overflow-wrap: break-word; word-wrap: break-word;"><?= $status ?></div>
	</div>
</div>

<!--
<br><br>
<a href="?s1=loadContacts&s2=Loadv2">Continue V2</a>
 -->
<?php
	if($_SESSION['login']['staffLevelID'] == STAFFLEVEL_MASTER ||in_array($_SESSION['login']['staffID'],[355])) {
?>
	<br><br>
	<a href="?s1=loadContacts&s2=Loadv2_1">Continue - Single Pass - Name Match Only</a>
	<br><br>
	<a href="?s1=loadContacts&s2=Loadv4">Continue - Multiple Passes - Name&VIN / Name&Email / Name&Address</a>
<?php
	} else {
?>
	<br><br>
	<a href="?s1=loadContacts&s2=Loadv2_1">Continue</a>
<?php
	}
?>

<!--
<div style="float:right">
	<a href="?s1=loadContacts&s2=Loadv3">Continue V3</a>
</div>
-->
