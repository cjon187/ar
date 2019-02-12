<?php include_once('leads_header.php'); ?>
<style>
</style>
<script>
	var types = {};
	types['year'] = '<?= $vehicle->vehicleYear ?>';
	types['make'] = '<?= $vehicle->vehicleBrandName ?>';
	types['model'] = '<?= $vehicle->vehicleModelName ?>';

	$(function() {
		vehicleDropdown('year');
	
		if(types['year'] != '')
			vehicleDropdown('make');
	
		if(types['make'] != '')
			vehicleDropdown('model');

	});
	function changeVehicle(vType) {
		if(vType == 'year') {			
	    	$('[name=make]').html('');
	    	$('[name=model]').html('');

			types['year'] = $('[name=year]').val();
			types['make'] = '';
			types['model'] = '';
			$('[name=make]').attr('disabled',true);
			$('[name=model]').attr('disabled',true);

	    	if(types['year'] != '')
	    		vehicleDropdown('make');
		}
		else if(vType == 'make') {			
	    	$('[name=model]').html('');

			types['make'] = $('[name=make]').val();			
			types['model'] = '';
			$('[name=model]').attr('disabled',true);

	    	if(types['make'] != '')
	    		vehicleDropdown('model');
		}
		else if(vType == 'model') {		    	
			types['model'] = $('[name=model]').val();
		}
	}
	function vehicleDropdown(vType) {
	
		$.ajax({data: {
					    vehicleLookup: vType,			
				       	make: types['make']
					  },
			    type: 'POST',
			dataType: "json", 
			 success: function (data) {
			 	
		    	$('[name=' + vType + ']').html('');
		    	$('[name=' + vType + ']').append($('<option>'));
		    	$.each(data, function(k,v) {
					$('[name=' + vType + ']').append($('<option>', { 
					        value: v,
					        text : v
					    }));
		        });    	
		        
		    	if(data.length > 0) 
		        	$('[name=' + vType + ']').removeAttr('disabled',false);
		       	else
		        	$('[name=' + vType + ']').attr('disabled',true);

		        $('[name=' + vType + ']').val(types[vType]);			 					 	
			 }
	    });
	}

	function deleteVehicle(id) {
		if(confirm('Are you sure you want to remove this vehicle from this lead?')) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=Vehicles&&s3=Edit&delete&id=' + id;
		}
	}
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>"><?= $lead->name ?></a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=Edit&id=<?= $lead->id ?>">Edit <?= $lead->name ?></a></li>
				<li class="active">
					<?= (is_null($vehicle->id) ? 'Add' : 'Edit') ?> Vehicle
				</li>
			</ol>
		</div>
	</div>
	<?php
		if(!is_null($errors)) {	
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="errors">
		<?php
			foreach($errors as $errorType => $e) {
		?>
				<div><?= $e[0] ?></div>
		<?php
			}
		?>
			</div>
		</div>
	</div>
	<?php
		}
	?>
	<form method="POST">
		<input type="hidden" name="id" value="<?= $vehicle->id ?>">
		<input type="hidden" name="leadID" value="<?= $lead->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Edit&id=<?= $lead->id ?>'">Cancel</button>
			<div class="pull-right">
				<button type="button" class="btn btn-danger" onClick="deleteVehicle(<?= $vehicle->id ?>)">Delete</button>
			</div>
		</div>
		<div class="formSection">
				<?= $vehicle->vehicleYear ?> <?= $vehicle->vehicleBrandName ?> <?= $vehicle->vehicleModelName ?>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Year</label>
						<select class="form-control" name="year" onChange="changeVehicle('year')" disabled></select>
					</div>
					<div class="form-group">
						<label>Make</label>
						<select class="form-control" name="make" onChange="changeVehicle('make')" disabled></select>
					</div>
					<div class="form-group">
						<label>Model</label>
						<select class="form-control" name="model" onChange="changeVehicle('model')" disabled></select>
					</div>

					<div class="form-group">
						<label>Trim</label>
						<input type="text" class="form-control" name="vehicleTrim" value="<?= $vehicle->vehicleTrim ?>">
					</div>
					<div class="form-group">
						<label>Style</label>
						<input type="text" class="form-control" name="vehicleStyle" value="<?= $vehicle->vehicleStyle ?>">
					</div>
					<div class="form-group">
						<label>Purchase Price</label>
						<div class="input-group">
							<input type="number" class="form-control" numeric name="purchasePrice" value="<?php echo sprintf("%0.2f",$vehicle->purchasePrice); ?>"/>
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Payout</label>
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" numeric class="form-control" name="payout" placeholder="Payout" value="<?= $vehicle->payout ?>">
						</div>
					</div>
					<div class="form-group">
						<label>Rate</label>

						<div class="input-group">
							<input type="text" numeric class="form-control" name="rate" placeholder="Rate" value="<?= $vehicle->rate ?>">
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group">
						<label>Lien Payout</label>
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" numeric class="form-control" name="lienPayout" placeholder="Lien Payout" value="<?= $vehicle->lienPayout ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Trade-In Low</label>
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" numeric class="form-control" name="tradeInLow" value="<?= $vehicle->tradeInLow ?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Trade-In High</label>
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" numeric class="form-control" name="tradeInHigh" value="<?= $vehicle->tradeInHigh ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Mileage</label>
						<div class="input-group">
							<input type="text" numeric class="form-control" name="mileage" placeholder="Mileage" value="<?= $vehicle->mileage ?>">
							<span class="input-group-addon">KM</span>
						</div>
					</div>
					<div class="form-group">
						<label>Delivery Date</label>
						<div class="input-group">
							<input id="delivery-date-calendar" type="text" datepicker class="form-control" name="deliveryDate" placeholder="yyyy-mm-dd" value="<?php echo $vehicle->deliveryDate; ?>">
							<span id="delivery-date-icon" class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>

			</div>
			<div class="row">
				<h4>Financing</h4>
				<div class="col-md-3">
					<div class="form-group">
						<label>Term</label>
						<div class="input-group">
							<input type="number" numeric class="form-control" name="term" placeholder="36" value="<?php echo sprintf("%d",$vehicle->term); ?>"/>
							<span class="input-group-addon">months</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Monthly Payment</label>
						<div class="input-group">
							<input type="number" numeric class="form-control" name="monthlyPayment" placeholder="123.45" value="<?php echo sprintf("%0.2f",$vehicle->monthlyPayment); ?>"/>
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Financed Amount</label>
						<div class="input-group">
							<input type="number" numeric class="form-control" name="financedAmount" placeholder="7890.00" value="<?php echo sprintf("%0.2f",$vehicle->financedAmount); ?>"/>
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Cash Down Amount</label>
						<div class="input-group">
							<input type="number" numeric class="form-control" name="cashDownAmount" placeholder="29.33" value="<?php echo sprintf("%0.2f",$vehicle->cashDownAmount); ?>"/>
							<span class="input-group-addon">$</span>
						</div>
					</div>
				</div>
			</div>
		</div>
				<?= $vehicle->vehicleYear ?> <?= $vehicle->vehicleBrandName ?> <?= $vehicle->vehicleModelName ?>

	</form>
</div>
<script>
$(function() {
		var calOptions = {
			"changeMonth": true,
			"changeYear": true,
			"yearRange": "1900:<?php echo date('Y'); ?>"
		}
		$calendarInp = $('#delivery-date-calendar');
		$calendarInp.datepicker(calOptions);

		$('#delivery-date-icon').on('click',function(e) {
			$calendarInp.datepicker().datepicker('show');
		});
});
</script>