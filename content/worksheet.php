<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="<?= AR_SECURE_URL ?>scripts/moment.js"></script>

<link rel="stylesheet" href="<?= AR_SECURE_URL ?>scripts/daterangepicker/jquery.comiseo.daterangepicker.css" />
<script src= "<?= AR_SECURE_URL ?>scripts/daterangepicker/jquery.comiseo.daterangepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?= AR_SECURE_URL ?>scripts/DataTablesOne/dataTables.min.css">
<script type="text/javascript" src="<?= AR_SECURE_URL ?>scripts/DataTablesOne/dataTables.min.js"></script>


<style>
	.ms-choice{
		height: 34px;
	}
	.ms-choice > span {
		top: 4px;
	}
	table.dataTable thead th{
		background-color: #373a3c;
		color: white;
	}

	table.dataTable thead .filterType, .filterTypeColors{
		background-color: #458B00;
		color: white;
	}

/* 	table.dataTable tbody td{
	font-size: 9pt;
} */
	.table_small_padding thead th{
		padding: 8px 4px !important;
	}
	.table_small_padding tbody td{
		padding: 4px 4px !important;
	}
	.table_small_padding tfoot th{
		padding: 4px 4px !important;
	}
	table.dataTable.cell-border tbody td {
		border-color: #CCC;
	}
	.dataTables_wrapper .dataTables_paginate {
		padding-top: 0px;
		margin-bottom: 3px;
	}

	.invoiceDone{
		background-color: #99ff66;
		color: blue;
		text-align:center;
	}
	.lineThrough{
		text-decoration:line-through;
	}
	.greyOut{
		background-color:#ccc;
	}

</style>
<script>
	 $(document).ready(function() {
	  	initializeDatepicker();

	  	var mecSelect = new ARMultiSelect($('#mecIDs'));
		mecSelect.create({width: "250px", placeholder:"All Staff", minimumCountSelected: 3, allSelected:"All Staff"});

		var agreementSelect = new ARMultiSelect($('#agreementTypes'));
		agreementSelect.create({width: "250px", placeholder:"All Agreement Types", minimumCountSelected: 3, allSelected:"All Agreement Types"});

		var agreementTable_obj = $('#agreementTable').DataTable({
			"paging": true,
			"ordering": true,
			"order": [],
			dom: 'lpfrtp',
			'iDisplayLength': 25
		});
	 });

	 function initializeDatepicker(){
		$('#datepicker').daterangepicker({
			presetRanges: [{
		         text: 'Current Month',
		         dateStart: function() { return moment().startOf('month') },
		         dateEnd: function() { return moment().endOf('month') }
		     }, {
		         text: 'Next Month',
		         dateStart: function() { return moment().add('month', 1).startOf('month') },
		         dateEnd: function() { return moment().add('month', 1).endOf('month') }
		     }, {
		         text: 'Last Month',
		         dateStart: function() { return moment().subtract('month', 1).startOf('month') },
		         dateEnd: function() { return moment().subtract('month', 1).endOf('month') }
		     }, {
		         text: 'Year To Date',
		         dateStart: function() { return moment().startOf('year') },
		         dateEnd: function() { return moment() }
		     }, {
		         text: 'Previous Year',
		         dateStart: function() { return moment().subtract('year',1).startOf('year') },
		         dateEnd: function() { return moment().subtract('year',1).endOf('year') }
		     }
		     ],

		     datepickerOptions : {
		         numberOfMonths: 3,
		         linkedCalendars: true,
		         maxDate: new Date(moment().add(12,'month'))
		     },
		 });

		<?php
			if($_SESSION['worksheet']['dateFrom'] != '' && $_SESSION['worksheet']['dateTo'] != "") {
		?>
			$('#datepicker').daterangepicker("setRange", {start: moment('<?= $_SESSION['worksheet']['dateFrom'] ?>').startOf('day').toDate(),end: moment('<?= $_SESSION['worksheet']['dateTo'] ?>').startOf('day').toDate()});
		<?php
			}
		?>

		$('#datepicker').on('change', function(event) { console.log('input element changed'); $('#updateSearch').submit(); });
	}


	function editWorksheet(worksheetID)
	{
		window.open('?s1=worksheet&s2=Add&id=' + worksheetID,'editWorksheet','width=550,height=750,toolbar=0,scrollbars=1');
		return false;
	}

	function updateInvoiced(wid){
		var status = "done";
		if($('#invoiced_'+wid).hasClass('invoiceDone')){
			status = "";
		}
		$.ajax({data:	{updateInvoiced: wid, status: status},
				type:	'POST',
				dataType: 'json',
				success: function(data){
					if(data.success){
						if(status == "done"){
							$('#invoiced_'+wid).addClass('invoiceDone');
							$('#invoiced_'+wid).text('done');
						}
						else{
							$('#invoiced_'+wid).removeClass('invoiceDone');
							$('#invoiced_'+wid).text('');
						}
					}
				}
		});
	}
	function updatePCInvoiced(wid){
		var status = 1;
		if($('#pcInvoiced_'+wid).hasClass('invoiceDone')){
			status = 0;
		}
		$.ajax({data:	{updatePCInvoiced: wid, status: status},
				type:	'POST',
				dataType: 'json',
				success: function(data){
					if(data.success){
						if(status == 1){
							$('#pcInvoiced_'+wid).addClass('invoiceDone');
							$('#pcInvoiced_'+wid).text('done');
						}
						else{
							$('#pcInvoiced_'+wid).removeClass('invoiceDone');
							$('#pcInvoiced_'+wid).text('');
						}
					}
				}
		});
	}

	function openInvoicedSetter() {

		var win = window.open('?s1=worksheet&s2=InvoicedSetter','invoicedSetter','width=600,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
	}
</script>

<div id="ar-page-title">Agreements</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<form id="updateSearch" class="form-inline" method="POST">
	<div class="row">
		<input type="hidden" name="updateSearch" value="1">
		<div class="col-xs-6">
			<div class="form-group">
				<input class="form-control input-sm" id="datepicker" name="dateRange" value="">
			</div>
		</div>
		<div class="col-xs-6">
			<div>
				<div class="pull-right">
					<button type="button" onClick="openInvoicedSetter()" class="btn btn-success">Set Invoiced Tool</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>

	</div>

	<div class="row" style="margin-top: 10px;">
		<div class="col-xs-12" style="text-align:left;">
			<div class="form-group" style="text-align:left; margin-right: 10px;">
				<select name="mecIDs[]" id="mecIDs" multiple>
					<?php
					if(count($_SESSION['worksheet']['mecIDs']) > 0){
						$mecArray = $_SESSION['worksheet']['mecIDs'];
					}
					else{
						$mecArray = array();
					}
					$mecs = Staff::where('status', 1)->orderBy('name', 'ASC')->arrayBuilder()->get();
					$mecs = $db->join('ps_worksheets w', 'w.staffID = s.staffID', 'INNER')->arrayBuilder()->orderBy('s.name', 'ASC')->get('ps_staff s', null, 'distinct w.staffID, s.name');
					foreach($mecs as $mec){
						echo '<option value="'. $mec['staffID'] .'" '. (in_array($mec['staffID'],$mecArray) ? 'SELECTED' : '' )  .'>' . $mec['name'] . '</option>';
					}
					?>
				</select>
			</div>

			<div class="form-group" style="text-align:left; margin-right: 10px;">
				<select name="agreementTypes[]" id="agreementTypes" multiple>
					<?php
					if(count($_SESSION['worksheet']['agreementTypes']) > 0){
						$agreementArray = $_SESSION['worksheet']['agreementTypes'];
					}
					else{
						$agreementArray = array();
					}
					?>

					<option value="invitation" 	<?= (in_array("invitation",$agreementArray) ? 'SELECTED' : '') ?>>Invitation Agreements</option>
					<option value="conquest" 	<?= (in_array("conquest",$agreementArray) 	? 'SELECTED' : '') ?>>Conquest Agreements</option>
					<option value="digital" 	<?= (in_array("digital",$agreementArray) 	? 'SELECTED' : '') ?>>Digital Agreements</option>
					<option value="arc" 		<?= (in_array("arc",$agreementArray) 		? 'SELECTED' : '') ?>>ARC Agreements</option>
					<option value="training" 	<?= (in_array("training",$agreementArray) 	? 'SELECTED' : '') ?>>Training Agreements</option>
					<option value="perCar" 		<?= (in_array("perCar",$agreementArray) 	? 'SELECTED' : '') ?>>Per Car Agreements</option>
					<option value="nonprime" 	<?= (in_array("nonprime",$agreementArray) 	? 'SELECTED' : '') ?>>Non Prime Agreements</option>
					<option value="drive" 	    <?= (in_array("drive",$agreementArray) 		? 'SELECTED' : '') ?>>Drive Agreements</option>
					<option value="other" 		<?= (in_array("other",$agreementArray) 		? 'SELECTED' : '') ?>>Other Agreements</option>
				</select>
			</div>

			<div class="form-group" style="text-align:left; margin-right: 10px;">
				<select name="ldf" class="form-control" id ="ldf" style="width:220px">
					<?php
						$ldfList = $ldf->getList();
						if(!empty($ldfList)) {
							foreach($ldfList as $key => $value) {
					?>
							<option value="<?= $key ?>" <?= ($_SESSION['worksheet']['ldf'] == $key ? 'selected' : '') ?>><?= $value ?></option>
					<?php
							}
						}
					?>
				</select>
			</div>

			<div class="form-group" style="text-align:left; margin-right: 10px;">
			    <div class = "input-group" style="width: 220px; ">
		           <input type = "text" name="search" placeholder="Dealer Search" class = "form-control" value="<?= $_SESSION['worksheet']['search'] ?>">

		           <span class = "input-group-btn">
		              <button class = "btn btn-default" type = "button"  onClick="this.form.submit()" style="height: 34px;">
		                 <span class="glyphicon glyphicon-search" ></span>
		              </button>
		           </span>

		        </div>
		    </div>

		    <div class="form-group" style="text-align:left; margin-right: 10px;">
			    <div class = "input-group" style="width: 230px; ">
			    	 <span style="width: 50px;" class = "input-group-addon" >
		             	Order By
		           </span>
			        <select name="order" id="order" class="form-control">
						<option style="color:black;" value="1" <?= ($_SESSION['worksheet']['order'] == 1 ? 'SELECTED' : '' )?>>DA#</option>
						<option style="color:black;" value="2" <?= ($_SESSION['worksheet']['order'] == 2 ? 'SELECTED' : '' )?>>Dealer Name</option>
						<option style="color:black;" value="3" <?= ($_SESSION['worksheet']['order'] == 3 ? 'SELECTED' : '' )?>>Event Date</option>
						<!-- <option style="color:black;" value="4" <?= ($_SESSION['worksheet']['order'] == 4 ? 'SELECTED' : '' )?>>Agreement Type</option> -->
						<option style="color:black;" value="5" <?= ($_SESSION['worksheet']['order'] == 5 ? 'SELECTED' : '' )?>>Created</option>
					</select>
		        </div>
		    </div>

			<button type="button" class="btn btn-primary" style="width: 110px; font-weight:bold; font-size: 1.1em;" onClick="this.form.submit()">Filter</button>
		</div>

	</div>
</form>

<hr>

<div class="row">
	<div class="col-xs-12">
		<div class="agreementTable">
			<table id="agreementTable" class="table table-striped cell-border dataTable table_small_padding" style="width: 100%;" >
				<thead>
					<tr style="font-size: 0.7em; font-weight: normal;">
						<th>DA#</th>
						<th>Dealer</th>
						<th>New/Old</th>
						<th>Event</th>
						<th>Agreement Type</th>
						<th>Created</th>
						<th>Event MEC</th>
						<th>PDF</th>
						<th>Artwork</th>
						<th>Posted?</th>
						<th>Invoiced?</th>
						<th>PC Invoiced?</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(count($worksheets) > 0){
						foreach($worksheets as $worksheetArray) {
							$worksheetType = array();
							$invoiceDone = false;
							$pcInvoiceDone = false;
							$pcInvoiceRequired = $worksheetArray['hasPerCarCharge'];
							if($worksheetArray['invoiced'] == "done"){
								$invoiceDone = true;
							}
							if($worksheetArray['pcInvoiced']){
								$pcInvoiceDone = true;
							}

							if($worksheetArray['hasNewSave'] == 0){
								if($worksheetArray['invitationChecked'] == 'on')                                         $worksheetType [] = 'I';
								if($worksheetArray['conquestChecked'] == 'on')                                           $worksheetType [] = 'C';
								if($worksheetArray['digitalChecked'] == 'on')                                            $worksheetType [] = 'D';
								if($worksheetArray['trainingChecked'] == 'on' || $worksheetArray['travelChecked'] == 'on') $worksheetType [] = 'T';
								if($worksheetArray['perCarChecked'] == 'on')                                             $worksheetType [] = 'P';
								if($worksheetArray['arcChecked'] == 'on')                                                $worksheetType [] = 'A';
								if(count($worksheetType ) == 0)  													   $worksheetType [] = 'O';
							}
							else{
								$worksheetType[] =$worksheetArray['typeAbbr'];
							}

							$lineThrough = false;
							if($worksheetArray['deleted'] != '') $lineThrough = 'text-decoration:line-through;';

							?>
							<tr style="background-color:<?= ($isGrey ? '#ffeeff' : 'white') ?>">
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>"><?= $worksheetArray['da_num'] ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>"><?= $worksheetArray['dealerName'] ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>"><?= ($worksheetArray['hasNewSave'] ? "New" : "Old") ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>text-align:center"><?= date("M j",strtotime($worksheetArray['eventStart'])) . (date("j",strtotime($worksheetArray['eventStart'])) != date("j",strtotime($worksheetArray['eventEnd'])) ? '-' .  date("j",strtotime($worksheetArray['eventEnd'])) : '') ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>text-align:center"><?= implode(' ',$worksheetType) ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="editWorksheet(<?= $worksheetArray['worksheetID'] ?>)" nowrap style="<?= $lineThrough ?>text-align:center"><?= $worksheetArray['name'] ?></td>
								<td style="<?= $lineThrough ?>"><?= $worksheetArray['mecName'] ?></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="window.open('<?= AR_SECURE_URL ?>export/agreement/<?= $worksheetArray['hash'] ?>')" nowrap style="<?= $lineThrough ?>text-align:center"><img src="images/pdfIcon.png"></td>
								<td onMouseover="this.style.cursor='pointer'" onClick="window.open('?s1=<?=$_GET['s1']?>&seeArtwork&da=<?= $worksheetArray['worksheetID'] ?>')" nowrap style="<?= $lineThrough ?>text-align:center">
							<?php
								if(stripos($worksheetArray['typeAbbr'],'i') !== false) {
							?>
									<img src="images/artworkIcon.png">
							<?php
								}
							?>
								</td>
								<td <?= ($worksheetArray['posted'] ? 'class="invoiceDone"' : '') ?>>
									<?php
										if($worksheetArray['deleted']){
											echo '<font style="'. $lineThrough .'">N/A</font>';
										}
										else if($worksheetArray['posted']){
											echo '&#10004;';
										}
									?>
								</td>
								<td id="invoiced_<?= $worksheetArray['worksheetID'] ?>"
									class="<?php if($lineThrough) echo "lineThrough"; ?><?php if($invoiceDone) echo " invoiceDone"; ?>"
									style="cursor:pointer;"
									<?php if(in_array($_SESSION['login']['staffLevelID'],[STAFFLEVEL_ACCOUNTING, STAFFLEVEL_MASTER])) echo 'onClick="updateInvoiced('. $worksheetArray['worksheetID'] .')"'; ?>>
									<?php if($invoiceDone) echo "done"; ?>
								</td>
								<?php
									if($pcInvoiceRequired) {
								?>
									<td id="pcInvoiced_<?= $worksheetArray['worksheetID'] ?>"
										class="<?php if($lineThrough) echo "lineThrough"; ?><?php if($pcInvoiceDone) echo " invoiceDone"; ?>"
										style="cursor:pointer;"
										<?php if(in_array($_SESSION['login']['staffLevelID'],[STAFFLEVEL_ACCOUNTING, STAFFLEVEL_MASTER])) echo 'onClick="updatePCInvoiced('. $worksheetArray['worksheetID'] .')"'; ?>>
										<?php if($pcInvoiceDone) echo "done"; ?>
									</td>
								<?php } else { ?>
									<td class="greyOut"></td>
								<?php } ?>
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
