<?php include_once('crm3_header.php'); ?>

<!--<link href="<?= AR_SECURE_URL ?>scripts/bootstrap_slider/css/slider.css" rel="stylesheet" type="text/css">-->
<link href="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" rel="stylesheet" type="text/css">
<style>
	.fsaDiv {
		margin-bottom:5px;
	}
	.fsaDiv button{
		width:100%;
		text-align:left;
		font-size:0.9em;
	}
	.walksDiv {
		display:none;	
		padding:0px 20px;
	}
	.walksTbl {
		font-size:0.8em;
	}
	.walksTbl thead tr th:not(:first-child),.walksTbl tbody tr td:not(:first-child){
		text-align:center;
	}
	.walksTbl tbody tr td{
		padding-top:2px;
		padding-bottom:2px;
	}
	.walksTbl .glyphicon {
		font-size:0.8em;
	}

	.fsaDetails {
		padding:3px;
		font-size:1em;
		margin:0px;
		cursor:pointer;
		background-color:#4c9de0;
		color:white;
	}

	.fsaDetails.partialSelected {
		background-color:#3e85bc;
	}
	.fsaDetails.allSelected {
		background-color:#3278b4;
	}

	.fsaDetails div {
		display:inline-block;
		padding-right:5px;
		font-size:0.9em;
	}
	.fsaDetails > div {
	//	font-weight:bold;
	}
	.fsaDetails > div:nth-child(1) {
		width:40px;
	}
	.fsaDetails > div:nth-child(2) {
		width:120px;
	}

	.fsaDistributionDiv > div{
		font-weight:normal;
		display:inline-block;
		width:65px;
	}

	[route] {
		cursor:pointer;
	}
	[route]:not(.selected):hover {
		background-color:#daeeff;
	}
	[route].selected {
		background-color:#b2dcff;
		//color:white;
	}

	#total {
		text-align:center;
		margin:0px;
	}
	#totalCount {
		font-size:4em;
		font-weight:bold;
		color:yellow;
		line-height:1em;
		margin-bottom:10px;
	}
	#totalCountDescription {
		font-size:0.9em;
		font-weight:bold;
	}

	[toggleAll]{
		text-align:right;
	}
	[toggleAll] .glyphicon-ok{
		color:yellow;
	}
	[toggleAll] .glyphicon-remove{
		color:red;
	}
	[toggleAll] .glyphicon-minus{
		color:white;
	}

	#distributionBtns {
		margin:5px 0px;
		text-align:center;
		font-weight:normal;
		min-height:50px;
	}
	#distributionBtns .distributionBtnsDiv {
		margin:0px;
		width:100%;
		padding:0px 3px;
	}
	#distributionBtns .distributionBtnsDiv button {
		margin:0px 0px 5px 0px;
		width:100%;
	}
	#distributionBtns .count {
		display:inline-block;
	}

	.panel-heading {
		padding:5px;
	}
	
	.filter {
		margin-bottom:20px;
	}
	.filter:last-child {
		margin-bottom:0px;
	}
	.filter .description {
		font-weight:bold;
		margin-bottom:3px;
	}

	.filterBtns {
		margin-top:10px;
		padding-top:10px;
		border-top:1px #ccc solid;
	}

	#activityForm {
		opacity:0.2;
	}

	#filtersDiv {

	}
	.divider {
		border-top:1px solid #ccc;
		height:1px;
		margin:20px 0px;
	}

	.toolBtns {
		margin-bottom:5px;
	}
	.toolBtns button {
		min-width:85px;
	}

	#distance {
		width:100px;
		padding:3px;
		background-color:#f9f9f9;
		color:#333;
		border-radius:5px;
		font-size:0.7em;
		text-align:center;
	}

	#distanceDiv {
		margin-top:10px;
		text-align:center;
		width:300px;
	}
	#distanceSliderDiv > div{
		display:inline-block;
		margin-right:10px;
	}

	#distanceSlider {
		width:220px;
	}
	#previousConquests {
		overflow-y:auto;
		height:200px;
	}
	#previousConquests button{
		margin-bottom:3px;
		min-width:250px;
		text-align:left;
	}

	#filtersModal .modal-dialog {
	   min-width: 600px; 
	   max-width: 850px; 
	   width:60%;
	}
	.previousConquest {
		font-size:0.8em;
	}

	.panel_title{
		padding:5px 0px;
		display:inline-block;
	}
	.panel_button {
		float:right;
	}
</style>
<script src="<?= AR_SECURE_URL ?>scripts/jquery.form.min.js"></script>
<script src="<?= AR_SECURE_URL ?>scripts/jquery.number.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!--<script src="<?= AR_SECURE_URL ?>scripts/bootstrap_slider/js/bootstrap-slider.js"></script>-->
<script>
	$(function() {
		$('#activityForm').ajaxForm(); 
		$('[route]').click(function() {
			$('#changesAlert').show();
			$('#savedAlert').hide();
			if($(this).hasClass('selected')) {
				$(this).removeClass('selected');
			} else {
				$(this).addClass('selected');
			}
			calculateTotals(true);
			updateToggleAllBtns($(this).attr('fsa'));
		});

		$('[toggle]').click(function() {
			$('#' + $(this).attr('toggle') + 'Walks').toggle(400);
		});

		$('[toggleAll]').click(function() {
			if($('#' + $(this).attr('toggleAll') + 'Walks [route].selected').length == 0) {
				$('#' + $(this).attr('toggleAll') + 'Walks [route]').addClass('selected');
			} else {
				$('#' + $(this).attr('toggleAll') + 'Walks [route]').removeClass('selected');
			}
			
			calculateTotals(true);
			updateToggleAllBtns($(this).attr('toggleAll'));
		});


		$('[filter]').click(function() {

			if($(this)[0].hasAttribute("exclusive")) {
				$('[filter='+ $(this).attr('filter') + ']').removeClass('selected');
				$(this).removeClass('btn-default').addClass('selected');

			} else {
				if($(this).hasClass('btn-primary')) {
					$(this).removeClass('selected');
				} else {
					$(this).addClass('selected');
				}
			}

			updateFilterBtns($(this).attr('filter'));

			if($(this).attr('filter') == '<?= ConquestFilterType::DISTRIBUTION ?>') {
				calculateTotals(true);
			}

		});

		calculateTotals(<?= (isset($_POST['applyFilters']) ? 'true' : 'false') ?>);
		updateToggleAllBtns('all');

		//$('#distanceSlider').slider();
		$("#distanceSlider").slider({
			range:true,
			min: 0,
			max: <?= ConquestFilterType::MAX_DISTANCE + ConquestFilterType::DISTANCE_STEP ?>,
			values: [<?= (is_array($filters[ConquestFilterType::DISTANCE_FROM]) ? current($filters[ConquestFilterType::DISTANCE_FROM]) : 0) ?>,<?= (is_array($filters[ConquestFilterType::DISTANCE_TO]) ? current($filters[ConquestFilterType::DISTANCE_TO]) : ConquestFilterType::MAX_DISTANCE + ConquestFilterType::DISTANCE_STEP) ?>],
			step: <?= ConquestFilterType::DISTANCE_STEP ?>,
			change: function(event, ui) {
				updateDistance();
			},
			slide: function(event, ui) {
				if(ui.values[0] == ui.values[1]) {
					return false;
				}
				updateDistanceDisplay();
			}
		});
		
		updateDistance();
	})

	function updateDistanceDisplay() {		
		distanceFrom = $( "#distanceSlider" ).slider( "values", 0 );
		distanceTo = $( "#distanceSlider" ).slider( "values", 1 );

		if(distanceTo == <?= ConquestFilterType::MAX_DISTANCE + ConquestFilterType::DISTANCE_STEP ?>) {
			$('#distance').html(distanceFrom + ' - No Limit');
		} else {
			$('#distance').html(distanceFrom + ' - ' + distanceTo + ' KM');
		}
	}

	function updateDistance(){

		distanceFrom = $( "#distanceSlider" ).slider( "values", 0 );
		distanceTo = $( "#distanceSlider" ).slider( "values", 1 );

		updateDistanceDisplay();

		$('[filter=<?= ConquestFilterType::DISTANCE_FROM ?>]').removeClass('selected').val('');
		$('[filter=<?= ConquestFilterType::DISTANCE_TO ?>]').removeClass('selected').val('');

		if(distanceFrom != 0) {
			$('[filter=<?= ConquestFilterType::DISTANCE_FROM ?>]').addClass('selected').val(distanceFrom);
		}
		if(distanceTo <= <?= ConquestFilterType::MAX_DISTANCE ?>) {
			$('[filter=<?= ConquestFilterType::DISTANCE_TO ?>]').addClass('selected').val(distanceTo);
		}
	}

	function calculateTotals(updateRequested) {
		totals = 0;
		housesTotal = 0;
		apartmentsTotal = 0;
		farmsTotal = 0;
		businessesTotal = 0;
		$('[route].selected').each(function() {
			housesTotal += parseInt($(this).attr('houses'));
			apartmentsTotal += parseInt($(this).attr('apartments'));
			farmsTotal += parseInt($(this).attr('farms'));
			businessesTotal += parseInt($(this).attr('businesses'));
		})


		$('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_HOUSES ?>] .count').html($.number(housesTotal));
		$('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_APARTMENTS ?>] .count').html($.number(apartmentsTotal));
		$('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_FARMS ?>] .count').html($.number(farmsTotal));
		$('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_BUSINESSES ?>] .count').html($.number(businessesTotal));

		if($('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_HOUSES ?>]').hasClass('selected')) {
			totals += housesTotal;
		}
		if($('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_APARTMENTS ?>]').hasClass('selected')) {
			totals += apartmentsTotal;
		}
		if($('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_FARMS ?>]').hasClass('selected')) {
			totals += farmsTotal;
		}
		if($('[filter=<?= ConquestFilterType::DISTRIBUTION ?>][value=<?= ConquestFilterTypeValue::DISTRIBUTION_BUSINESSES ?>]').hasClass('selected')) {
			totals += businessesTotal;
		}

		$('#totalCount').html($.number(totals));

		if(updateRequested) {
			$('#quantity').val(totals);
		}

		$('#activityForm').css('opacity',1);
	}

	function updateToggleAllBtns(fsa) {
		if(fsa == 'all') {
			selector = '[toggleAll]';
		} else {
			selector = '[toggleAll=' + fsa + ']';
		}

		$(selector).each(function() {
			numRoute = $('#' + $(this).attr('toggleAll') + 'Walks [route]').length;
			numSelected = $('#' + $(this).attr('toggleAll') + 'Walks [route].selected').length;

			$('#' + $(this).attr('toggleAll') + 'Details').removeClass('partialSelected').removeClass('allSelected');

			if(numSelected == 0) {
				$('[toggleAll=' + $(this).attr('toggleAll') + ']').html('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
			} else if(numRoute == numSelected) {
				$('#' + $(this).attr('toggleAll') + 'Details').addClass('allSelected');
				$('[toggleAll=' + $(this).attr('toggleAll') + ']').html('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
				/*if(fsa == 'all') {
					$('#' + $(this).attr('toggleAll') + 'Walks').show();
				}*/
			} else {				
				$('#' + $(this).attr('toggleAll') + 'Details').addClass('partialSelected');
				$('[toggleAll=' + $(this).attr('toggleAll') + ']').html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>');
				/*if(fsa == 'all') {
					$('#' + $(this).attr('toggleAll') + 'Walks').show();
				}*/
			}


		})
	}

	function updateFilterBtns(filter) {
		if(filter == 'all') {
			selector = '[filter]';
		} else {
			selector = '[filter=' + filter + ']';
		}

		$(selector + ':not(.selected)').removeClass('btn-primary').removeClass('btn-default').addClass('btn-default');
		$(selector + '.selected').removeClass('btn-primary').removeClass('btn-default').addClass('btn-primary');
	}

	function updateDistributionTypeBtns() {
		
		selector = '[filter=<?= ConquestFilterType::DISTRIBUTION ?>]';

		$(selector + ':not(.selected)').removeClass('btn-primary').removeClass('btn-default').addClass('btn-default');
		$(selector + '.selected').removeClass('btn-primary').removeClass('btn-default').addClass('btn-primary');
	}


	function clearFilters() {
		
		$('#filtersModal [filter].selected:not([filter=<?= ConquestFilterType::AREA ?>])').removeClass('selected');
		updateFilterBtns('all');
		$('#distance_from').val(0);
		$('#distance_to').val(<?= ConquestFilterType::MAX_DISTANCE + ConquestFilterType::DISTANCE_STEP ?>);
		$('#distanceSlider').slider( "values", [ 0, <?= ConquestFilterType::MAX_DISTANCE + ConquestFilterType::DISTANCE_STEP ?> ] );
	}

	function selectAllRoutes() {
		$('[route]:not(.selected)').addClass('selected');
		calculateTotals(true);
		updateToggleAllBtns('all');
	}
	function selectAllTARoutes() {
		$('[route][ta]:not(.selected)').addClass('selected');
		calculateTotals(true);
		updateToggleAllBtns('all');
	}

	function unselectAllRoutes() {
		$('[route].selected').removeClass('selected');
		calculateTotals(true);
		updateToggleAllBtns('all');
	}

	function collapseAll() {
		$('.walksDiv').hide();
	}

	function expandAll() {
		$('.walksDiv').show();
	}
	/*function submitForm(myForm) {
		myForm.ajaxSubmit({
			dataType: 'json',
			success: function(data) {
				if(data.success) {
					location.href='?s1=<?= $_GET['s1'] ?>&s2=DealerVisit&id=' + data.activityID;
				}
				else {
					$('#alert_errors').html('');
					$.each(data.errors, function(errorType,errors) {
						$.each(errors, function(i,error) {
							$('#alert_errors').append('<div>' + error + '</div>');
						});
					});
					$('#alert_errors').show();
					$('#alert_success').hide();
				}
			}
		});
	}*/

	function applyFilters() {
		submitForm('filterForm');
	}
	function saveConquest() {
		submitForm('saveForm');
	}

	function submitForm(formID) {
		$('<input>').attr({
			    type: 'hidden',
			    name: 'quantity',
			    value: $('#quantity').val()
			}).appendTo('#' + formID);

		$('[route].selected').each(function() {
			$('<input>').attr({
			    type: 'hidden',
			    name: 'routes[]',
			    value: $(this).attr('route')
			}).appendTo('#' + formID);
		})

		$('[filter].selected').each(function() {
			$('<input>').attr({
			    type: 'hidden',
			    name: $(this).attr('filter') + '[]',
			    value: $(this).attr('value')
			}).appendTo('#' + formID);
		})

		$('#' + formID).submit();
	}

</script>
<div class="container-fluid" id="crm">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=crm3&s2=Dealer&id=<?= $conquest->event->dealer->id ?>"><?= $conquest->event->dealer->name ?></a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&eventID=<?= $conquest->event->id ?>"><?= $conquest->event->name ?></a></li>
				<li class="active">
					Conquest
				</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
		</div>
	</div>
	<div id="changesAlert" class="alert alert-warning" <?= (isset($_POST['applyFilters']) ? '' : 'style="display:none"') ?> role="alert">
		Filters applied - Postal Routes not saved yet
	</div>
<?php
	if(isset($error)) {
?>
	<div class="alert alert-danger" role="alert">
		<?= $error ?>
	</div>
<?php
	} else if($saved) {
?>
	<div id="savedAlert" class="alert alert-success" role="alert">
		Successfully Saved
	</div>
<?php
	}
?>
	<form method="POST" id="filterForm">
		<input type="hidden" name="applyFilters" value="1">
	</form>	

	<form method="POST" id="saveForm">
		<input type="hidden" name="saveMasterTaskID" value="<?= $conquest->id ?>">
	</form>

	<div class="btnsDiv">
		<button type="button" class="btn btn-success" onClick="saveConquest()">Save</button>
		<button type="button" class="btn btn-default" onClick="location.href=location.href">Cancel</button>
		<div class="pull-right">
			<div><b>Print By:</b> <?= (empty($conquest->printBy) ? 'N/A' : date("Y M j",strtotime($conquest->printBy))) ?></div>
			<div><b>Mailing File:</b> <?= (empty($walkFile) ? 'N/A' : date("Y M j",strtotime($walkFile->start)) . ' - ' . date("Y M j",strtotime($walkFile->end))) ?></div>	
		</div>
		<div class="pull-right" style="padding-right:10px">
			<select class="form-control input-sm" onChange="location.href='?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&taskID=' + $(this).val()">
			<?php
				foreach($previousConquests as $tid => $name) {
			?>
				<option value="<?= $tid ?>" <?= ($_GET['taskID'] == $tid ? 'SELECTED' : '') ?>><?= $name ?></option>
			<?php
				}
			?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">		
			<div class="panel panel-primary" id="total">
				<div class="panel-heading">
					<div id="totalCount">-</div>
					<div id="totalCountDescription">TOTAL DISTRIBUTION</div>
				</div>
			</div>	
			<div id="distributionBtns">
				<!-- <div class="pull-left distributionBtnsDiv">d</div>
				<div class="pull-left distributionBtnsDiv">a</div>
				<div class="pull-left distributionBtnsDiv">b</div>
				<div class="pull-left distributionBtnsDiv">c</div> -->
				<div class="row">	
			<?php
				foreach($distributions->values as $i => $v) {
					if($i % 2 == 0 && $i != 0) {
						echo '</div><div class="row">';
					}

					$selected = is_array($filters[ConquestFilterType::DISTRIBUTION]) && in_array($v->id,$filters[ConquestFilterType::DISTRIBUTION]);
			?>
					<div class="col-md-6">	
						<div class="distributionBtnsDiv"><button class="btn btn-xs <?= ($selected ? 'selected btn-primary' : 'btn-default') ?>" filter="<?= ConquestFilterType::DISTRIBUTION ?>" value="<?= $v->id ?>"><div class="count">-</div> <?= ucwords($v->name) ?></button></div>
					</div>
			<?php
				}
			?>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<div class="form-group form-inline" style="margin-bottom:0px">
						<label>Total Requested</label>
						<input type="text" class="form-control input-sm" numeric style="width:80px" id="quantity" value="<?= $conquest->quantity ?>">
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel_title">
						Filters
					</div>
					<div class="panel_button">
						<button class="btn btn-success btn-xs" onClick="$('#filtersModal').modal('show')">Change Filters</button>
					</div>
				</div>
				<div class="panel-body">
			<?php
				foreach($filters as $typeID => $values) {
					if(empty($values) || !in_array($typeID,array_keys($filterTypeLists)) || $typeID == ConquestFilterType::DISTRIBUTION) {
						continue;
					}


					switch ($typeID) {					
						case(ConquestFilterType::DISTANCE_FROM):
						case(ConquestFilterType::DISTANCE_TO):
						case(ConquestFilterType::EXCLUDE_EVENTS):
							break;
						default:		
							$c = new ConquestFilterTypeValue();
							$c->where('filterTypeID',$typeID);
							$c->where('value',$values,'IN');
							$filterValues = $c->getList();
							?>
								<div class="filter">
									<div class="description"><?= $filterTypeLists[$typeID] ?></div>
									<div class="options"><?= implode(' , ',$filterValues) ?></div>
								</div>
							<?php
							break;

					}
				}
			?>
		<?php
			if(is_array($filters[ConquestFilterType::DISTANCE_FROM]) || is_array($filters[ConquestFilterType::DISTANCE_TO])) {
				$from = (is_array($filters[ConquestFilterType::DISTANCE_FROM]) ? current($filters[ConquestFilterType::DISTANCE_FROM])  : 0);
				$to = (is_array($filters[ConquestFilterType::DISTANCE_TO]) ? current($filters[ConquestFilterType::DISTANCE_TO]) : 'No Limit');						

		?>
					<div class="filter">
						<div class="description">Distance</div>
						<div class="options"><?= $from . ' - ' . $to . ' KM' ?></div>
					</div>
		<?php
			}

			if(is_array($filters[ConquestFilterType::EXCLUDE_EVENTS])) {		

		?>
					<div class="filter">
						<div class="description">Exclude Event</div>
						<div class="options">
				<?php
					foreach($filters[ConquestFilterType::EXCLUDE_EVENTS] as $e) {
				?>
							<div class="previousConquest"><?= $previousConquests[$e] ?></div>
				<?php
					}
				?>
						</div>
					</div>
		<?php
			}
		?>
				</div>
			</div>	
			<div>
				<div class="toolBtns">
					<button class="btn btn-success btn-xs" onClick="selectAllRoutes();">Select All</button>
					<button class="btn btn-success btn-xs" onClick="unselectAllRoutes()">Unselect All</button>
					<button class="btn btn-success btn-xs" onClick="selectAllTARoutes();">Select All TAs</button>
				</div>
				<div class="toolBtns">
					<button class="btn btn-success btn-xs" onClick="collapseAll();">Collapse All</button>
					<button class="btn btn-success btn-xs" onClick="expandAll()">Expand All</button>
				</div>
			</div>
		</div>
		<div class="col-md-7">
	<?php
		foreach($fsas as $fsa => $walks) {
	?>
			<div class="fsaDiv">
				<div class="alert fsaDetails"  id="<?= $fsa ?>Details">
					<div toggle="<?= $fsa ?>"><?= $fsa ?></div>
					<div toggle="<?= $fsa ?>"><?= $walks['details']['city'] ?></div>
					<div toggle="<?= $fsa ?>" class="fsaDistributionDiv">
						<div>H <?= number_format($walks['details']['DA_houses']) ?></div>
						<div>A <?= number_format($walks['details']['DA_apartments']) ?></div>
						<div>F <?= number_format($walks['details']['DA_farms']) ?></div>
						<div>B <?= number_format($walks['details']['DA_businesses']) ?></div>
					</div>
					<div class="pull-right" toggleAll="<?= $fsa ?>"></div>
				</div>
			</div>
			<div class="walksDiv" id="<?= $fsa ?>Walks">
				<table class="table table-condensed walksTbl">
					<thead>
						<tr>
							<th>Route</th>
							<th>Depot</th>
							<th>TA</th>
							<th>Houses</th>
							<th>Apartments</th>
							<th>Farms</th>
							<th>Businesses</th>
						</tr>
					</thead>
					<tbody>
			<?php
				foreach($walks['walks'] as $walk) {
					$walkInfo = explode(' ',$walk['Postal_Station']);
					$route = array_shift($walkInfo);
					$routeDesc = implode(' ',$walkInfo);
					$isTA = in_array($walk['Route'],$taWalks);
			?>
						<tr class="<?= (in_array($walk['Route'],$selectedWalks) ? 'selected' : '') ?>" route="<?= $walk['Route'] ?>" <?= ($isTA ? 'ta' : '') ?> fsa="<?= $fsa ?>" houses="<?= $walk['DA_houses'] ?>" apartments="<?= $walk['DA_apartments'] ?>" farms="<?= $walk['DA_farms'] ?>" businesses="<?= $walk['DA_businesses'] ?>">
							<td><?= $route ?></td>
							<td><?= $routeDesc ?></td>
							<td><?= ($isTA ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : '') ?></td>
							<td><?= $walk['DA_houses'] ?></td>
							<td><?= $walk['DA_apartments'] ?></td>
							<td><?= $walk['DA_farms'] ?></td>
							<td><?= $walk['DA_businesses'] ?></td>
						</tr>
			<?php
				}
			?>
					</tbody>
				</table>
			</div>
	<?php
		}
	?>
		</div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="filtersModal">
	<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Filters</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-5">
<?php
	foreach($filterTypes as $type) {
		switch($type->id) {		
			case(ConquestFilterType::AREA):
			case(ConquestFilterType::GRADE):
			?>
				<div class="filter">
					<div class="description"><?= $type->name ?></div>
					<div class="options">
				<?php
					foreach($type->values as $v) {
						$selected = is_array($filters[$type->id]) && in_array($v->id,$filters[$type->id]);
				?>
						<button class="btn btn-xs <?= ($selected ? 'selected btn-primary' : 'btn-default') ?>" filter="<?= $type->id ?>" value="<?= $v->id ?>" <?= ($type->multiple ? '' : 'exclusive') ?>><?= $v->name ?></button>
				<?php
					}
				?>
					</div>
				</div>
			<?php
				break;
			case(ConquestFilterType::DISTANCE_TO):
			?>
				<div class="filter">
					<div class="description">Distance</div>	
					<div id="distanceDiv">	
						<center>			
							<div id="distanceSliderDiv">
								<div><b>0</b> </div>
								<div><div id="distanceSlider"></div></div>
								<div><b>&infin;</b></div>
								<input type="hidden" filter="<?= ConquestFilterType::DISTANCE_FROM ?>" value="">
								<input type="hidden" filter="<?= ConquestFilterType::DISTANCE_TO ?>" value="">
							</div>
							<div id="distance"></div>
						</center>
					</div>
				</div>

			<?php
				break;
			case(ConquestFilterType::EXCLUDE_EVENTS):
			?>
				<div class="filter">
					<div class="description">Exclude Events</div>
					<div id="previousConquests">
				<?php
					foreach($previousConquests as $pcid => $pc) {
						$selected = is_array($filters[ConquestFilterType::EXCLUDE_EVENTS]) && in_array($pcid,$filters[ConquestFilterType::EXCLUDE_EVENTS]);
				?>
						<div>
							<button class="btn btn-xs <?= ($selected ? 'selected btn-primary' : 'btn-default') ?>" filter="<?= ConquestFilterType::EXCLUDE_EVENTS ?>" value="<?= $pcid ?>"><?= $pc ?></button>
						</div>
				<?php
					}
				?>
					</div>
				</div>

			<?php
				break;
		}
	}
?>
			</div>
			<div class="col-md-7">
<?php
	foreach($filterTypes as $type) {
		switch($type->id) {			
			case(ConquestFilterType::AREA):
			case(ConquestFilterType::GRADE):
			case(ConquestFilterType::DISTRIBUTION):
			case(ConquestFilterType::PRIZM):
			case(ConquestFilterType::DISTANCE_FROM):
			case(ConquestFilterType::DISTANCE_TO):
			case(ConquestFilterType::EXCLUDE_EVENTS):
				break;
			default:
			?>
				<div class="filter">
					<div class="description"><?= $type->name ?></div>
					<div class="options">
				<?php
					foreach($type->values as $v) {
						$selected = is_array($filters[$type->id]) && in_array($v->id,$filters[$type->id]);
				?>
						<button class="btn btn-xs <?= ($selected ? 'selected btn-primary' : 'btn-default') ?>" filter="<?= $type->id ?>" value="<?= $v->id ?>" <?= ($type->multiple ? '' : 'exclusive') ?>><?= $v->name ?></button>
				<?php
					}
				?>
					</div>
				</div>
			<?php
		}
	}
?>
			</div>
		</div>

		<div class="filterBtns">
			<button class="btn btn-success btn-sm" onClick="applyFilters()">Apply Filters</button>
			<button class="btn btn-default btn-sm" onClick="clearFilters()">Clear Filters</button>
		</div>
	</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->