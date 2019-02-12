<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>

<link rel="stylesheet" type="text/css" href="<?= AR_SECURE_URL ?>scripts/DataTablesOne/dataTables.min.css">
<script type="text/javascript" src="<?= AR_SECURE_URL ?>scripts/DataTablesOne/dataTables.min.js"></script>

<style>
	.ui-datepicker {font-size: 12px}
	.formTbl td ,.formTbl th{vertical-align:middle;text-align:left;font-size:9pt}
	/*
	#resultsTbl {border-collapse:collapse;width:100%}
	#resultsTbl td ,#resultsTbl th{vertical-align:middle;text-align:center;font-size:9pt;border-bottom:1px solid #ccc;padding:5px 2px;white-space:nowrap}
	#resultsTbl th {color:white;background-color:#333}
	*/
	#resultsTbl {font-size:0.8em;}
	#resultsTbl td {padding:3px;white-space:nowrap;text-align:center;}
	#resultsTbl th {padding:5px 20px;color:white;background-color:#333;white-space:nowrap;}
	#resultsTbl .totals {
		padding:3px;
		color:white;
		background-color:#333;
		white-space:nowrap;
		border:0px !important;
	}

	.smallItalic{
		font-size: 0.9em;
		font-style: italic;
	}

	#reportTable th {
		width:150px;
	}
	#reportTable td {
		padding-bottom:5px;
	}
</style>
<script>
	var totalItems = 0;
	var totalRecords = 0;
	var resultsTableObj;
	$(function() {
		var provinces = new ARMultiSelect($('#provinceIDs'));
		provinces.create({width: "350px", placeholder:"All Provinces", minimumCountSelected: 4, allSelected:"All Provinces"});

		var countries = new ARMultiSelect($('#countryIDs'));
		countries.create({width: "350px", placeholder:"All Countries", minimumCountSelected: 4, allSelected:"All Countries"});

		var oems = new ARMultiSelect($('#oems'));
		oems.create({width: "350px", placeholder:"All OEMs", minimumCountSelected: 4, allSelected:"All OEMs"});

		var brands = new ARMultiSelect($('#brands'));
		brands.create({width: "350px", placeholder:"All Brands", minimumCountSelected: 4, allSelected:"All Brands"});

		var businessCenters = new ARMultiSelect($('#businessCenters'));
		businessCenters.create({width: "350px", placeholder:"All Business Centers", minimumCountSelected: 4, allSelected:"All Business Centers"});

		var autoGroups = new ARMultiSelect($('#autoGroups'));
		autoGroups.create({width: "350px", placeholder:"All Auto Groups", minimumCountSelected: 4, allSelected:"All Auto Groups"});

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

	function updateReportForm(reportTypeValue){
  		var selectedReportType = reportTypeValue.val();
  		/*console.log(selectedReportType);*/
  		$('.specificReport').remove();
  		/*$('.'+reportType).show();*/
   	    $.ajax({data:	{selectedReportType: selectedReportType},
				type:	'POST',
				dataType: 'script'
   	    });
  	}

	function generateReport()
	{
		if($('#reportType').val() != 'dealercontacts' || $('#reportType').val() != 'allStaff')
		{
			if($('#fromDate').val() == '' || $('#toDate').val() == '')
			{
				ARAlertError('Please select a date range.');
				return;
			}
			if($('#fromDate').val() > $('#toDate').val())
			{
				ARAlertError('Invalid date range.');
				return;
			}
		}

		$('#generateBtn').attr('disabled','disabled');
		$('#resultsDiv').html('');
		$('#statusDiv').html('Initializing...');

		$.ajax({data:	$('#tableForm').serializeArray() ,
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					totalItems = data.totalItems;
					$('#resultsDiv')
							.html($('<table>')
								.attr('id', 'resultsTbl')
								.addClass('table')
								.addClass('table-striped')
								.addClass('cell-border')
								.addClass('dataTable')
								.addClass('table_small_padding')
								.html($('<thead>')
									.html(data.headerHTML)
								)
								.append($('<tbody>'))
							);

					if(data.redirect != null && data.redirect != undefined && data.redirect != '') {
						location.href = data.redirect;
						$('#statusDiv').html('<b>Downloading...</b>');
						$('#generateBtn').removeAttr('disabled');
						return;
					}

					if(data.dataHTML != null && data.dataHTML != undefined && data.dataHTML != '') {
						$('#resultsDiv').find('tbody').append(data.dataHTML);
					}


					if(data.items > 0) {
						doNext();
					} else {
						addTotals();
						totalRecords = totalItems;
					}
				}
		   	});

	}


	function doNext()
	{
		$.ajax({data:	{doNext: ''},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					if(data.totalRecords != null && data.totalRecords != undefined && data.totalRecords != '') {
						totalRecords = data.totalRecords;
					} else {
						totalRecords = totalItems;
					}
					if(data.dataHTML != null && data.dataHTML != undefined && data.dataHTML != '') {
						$('#resultsTbl').find('tbody').append(data.dataHTML);
					}


					$('#statusDiv').html('Generating Report - <b>' + (100*((totalItems-data.items) / totalItems)).toFixed(2) + '%</b> Completed');

					if(data.items > 0) {
						doNext();
					} else {
						addTotals();
					}
		   		}

		   	});
	}
	function addTotals() {

		$.ajax({data:	{addTotals: ''},
				type:	'POST',
				dataType: 'html',
				success: function(html) {
					if(html != '') {
						$('#resultsTbl')
							.find('tbody')
								.append(html)

					}

					exportComplete();
		   		}

		   	});
	}
	function exportComplete() {

		$('#statusDiv').html('<b>Completed</b> - ' + totalRecords + ' Records');
		$('#generateBtn').removeAttr('disabled');

		resultsTableObj = $('#resultsTbl').DataTable({
			"paging": false,
			"ordering": true,
			"order": [],
			dom: 'Blpfrtp',
	        buttons: [
	            { extend: 'csv', title: 'Report Generator - CSV Export'},
	            { extend: 'excel', title: 'Report Generator - XLS Export'}
	        ],
			'iDisplayLength': 25,
			"searching": false
		});


	}
</script>
<a href="?s1=report" style="float:right; margin-right: 15px; font-size: 18px; font-weight:bold; color: blue; ">
	Back to Reports
</a>
<font style="font-size:12pt">Report Generator</font>
<br><br>
<form id="tableForm" class="form-inline">
	<input class="form-control input-sm" type="hidden" name="initialize" value="initialize">
	<table class="formTbl" id="reportTable">
		<tr>
			<th>Report Type</th>
			<td>
				<select class="form-control input-sm" id="reportType" name="reportType">
		<?php foreach($reports as $category => $list) { ?>
					<optgroup label="<?= $category ?>">
			<?php foreach($list as $report => $desc) { ?>
						<option value="<?= $report ?>" <?= ($_SESSION['report']['reportType'] == $report ? 'SELECTED' : '') ?>><?= $desc ?></option>
			<?php } ?>
		<?php } ?>
					</optgroup>
				</select>
			</td>
		</tr>
		<tr>
			<th>Date Range</th>
			<td> <input class="form-control input-sm" type="text" id="fromDate" name="fromDate" value="<?= $_SESSION['report']['fromDate'] ?>"> to <input class="form-control input-sm" type="text" id="toDate" name="toDate" value="<?= $_SESSION['report']['toDate'] ?>"></td>
		</tr>
		<tr>
			<th>OEMs</th>
			<td>
				<select id="oems" name="oems" multiple="multiple">
					<!-- <option value="" <?= ($_SESSION['report']['oems'] == '' ? 'selected' : '') ?>>All</option> -->
			<?php foreach($oems as $oemID => $oemName) {
					$selectedOEMs = [];
					if(!empty($_SESSION['report']['oems'])) $selectedOEMs = $_SESSION['report']['oems'];
				?>
					<option value="<?= $oemID ?>" <?= (in_array($oemID, $selectedOEMs) ? 'selected' : '') ?>><?= $oemName ?></option>
			<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Brands</th>
			<td>
				<select id="brands" name="brands" multiple="multiple">
					<!-- <option value="" <?= ($_SESSION['report']['brands'] == '' ? 'selected' : '') ?>>All</option> -->
			<?php foreach($brands as $brandID => $brandName) {
					$selectedBrands = [];
					if(!empty($_SESSION['report']['oems'])) $selectedBrands = $_SESSION['report']['oems'];
				?>
					<option value="<?= $brandID ?>" <?= (in_array($brandID, $selectedBrands) == $brandID ? 'selected' : '') ?>><?= $brandName ?></option>
			<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Business Centers</th>
			<td>
				<select id="businessCenters" name="businessCenters" multiple>
				<?php
				$businessCenterArray = array();
				if($_SESSION['report']['businessCenters'] != ""){
					$businessCenterArray = $_SESSION['report']['businessCenters'];
				}
				foreach($bcs as $bc) { ?>
						<option value="<?= $bc['dealerGroupID'] ?>" <?= (in_array($bc['dealerGroupID'], $businessCenterArray) ? 'selected' : '') ?>><?= $bc['name'] ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Auto Groups</th>
			<td>
				<select id="autoGroups" name="autoGroups" multiple>
					<!-- <option value="" <?= ($_SESSION['report']['autoGroups'] == '' ? 'selected' : '') ?>>All</option> -->
			<?php
			$autoGroupArray = array();
			if(count($_SESSION['report']['autoGroups']) > 0){
				$autoGroupArray = $_SESSION['report']['autoGroups'];
			}
			foreach($autoGroups as $ag) { ?>
					<option value="<?= $ag['dealerGroupID'] ?>" <?= (in_array($ag['dealerGroupID'],$autoGroupArray) ? 'selected' : '') ?>><?= $ag['name'] ?></option>
			<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Countries</th>
			<td>
				<select id="countryIDs" name="countryIDs" multiple>
					<?php
					$countryArray = array();
					if($_SESSION['report']['countryIDs'] != ""){
						$countryArray = $_SESSION['report']['countryIDs'];
					}
					foreach(Country::getListActive('countryName') as $countryID => $name) { ?>
						<option value="<?= $countryID ?>" <?= (in_array($provinceID, $countryArray) ? 'selected' : '') ?>><?= $name ?></option>
					<?php }
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Provinces</th>
			<td>
				<select id="provinceIDs" name="provinceIDs" multiple>
					<?php
					$provinceArray = array();
					if($_SESSION['report']['provinceIDs'] != ""){
						$provinceArray = $_SESSION['report']['provinceIDs'];
					}
					foreach(Province::getList('provinceName',[],['provinceName'=>'ASC']) as $provinceID => $name) { ?>
						<option value="<?= $provinceID ?>" <?= (in_array($provinceID, $provinceArray) ? 'selected' : '') ?>><?= $name ?></option>
					<?php }
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Region</th>
			<td>
				<select class="form-control input-sm" id="region" name="region">
			<?php foreach($lf->getList() as $lfID => $lfName) { ?>
					<option value="<?= $lfID ?>" <?= ($_SESSION['report']['region'] == $lfID ? 'selected' : '') ?>><?= $lfName ?></option>
			<?php } ?>

				</select>
			</td>
		</tr>

		<tr>
			<th>Active Dealers Only</th>
			<td>
				<input class="form-control input-sm" type="hidden" name="activeDealers" value="0">
				<input class="form-control input-sm" type="checkbox" id = "activeDealers" name="activeDealers" value="1" <?= ($_SESSION['report']['activeDealers'] == 1 ? 'checked' : '') ?> >
				&nbsp; <font style="font-size:8pt"><i>(Check to select only dealers with status of "Active")</i></font>
			</td>
		</tr>
		<tr>
			<th>Search</th>
			<td>
				<input class="form-control input-sm" type="text" id="search" name="search" value="<?= $_SESSION['report']['search'] ?>"> <font style="font-size:8pt"><i>(Separate by comma for multiple keywords)</i></font>
			</td>
		</tr>

		<tr>
			<td colspan="2" style="padding-top:10px">
				<input class="form-control btn-sm btn-primary" type="button" id="generateBtn" value="Generate Report" onClick="generateReport()">
				<input class="form-control btn-sm btn-primary" type="button" value="Reset" onClick="location.reload();">
				<div id="statusDiv" style="padding-left:20px;display:inline"></div>
			</td>
		</tr>
	</table>
</form>

<div id="resultsDiv" style="margin-top:20px;width:100%;height:300px;overflow:auto"></div>






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
