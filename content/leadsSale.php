<?php include_once('leads_header.php'); ?>
<style>
</style>
<script>
	var types = {};
	types['year'] = '<?= $sale->vehicleYear ?>';
	types['make'] = '<?= $sale->vehicleBrandName ?>';
	types['model'] = '<?= $sale->vehicleModelName ?>';

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
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>"><?= $lead->name ?></a></li>
				<li class="active">
					<?= (is_null($transaction->id) ? 'Add' : 'Edit') ?> Vehicle Purchase
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
		<input type="hidden" name="id" value="<?= $transaction->id ?>">
		<input type="hidden" name="leadID" value="<?= $lead->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>'">Cancel</button>
			<div class="pull-right">
				<button type="button" class="btn btn-danger" onClick="deleteTransaction(<?= $transaction->id ?>)">Delete</button>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Year</label>
								<select class="form-control" name="year" onChange="changeVehicle('year')" disabled></select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Make</label>
								<select class="form-control" name="make" onChange="changeVehicle('make')" disabled></select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Model</label>
								<select class="form-control" name="model" onChange="changeVehicle('model')" disabled></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>VIN</label>
								<input type="text" class="form-control" name="vin" value="<?= $sale->vin ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Sold Date</label>
								<input type="text" class="form-control" datepicker name="delivery" placeholder="yyyy-mm-dd" value="<?= (is_null($sale->delivery) ? '' : $sale->delivery->format('Y-m-d')) ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>New/Used</label>
								<select class="form-control" name="newUsed">
									<option></option>
									<option value="1" <?= ($sale->newUsed == 1 ? 'SELECTED' : '') ?>>New</option>
									<option value="2" <?= ($sale->newUsed == 2 ? 'SELECTED' : '') ?>>Used</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Delivered?</label>
								<select class="form-control" name="isDelivered">
									<option value="0" <?= ($sale->isDelivered == 0 ? 'SELECTED' : '') ?>>No</option>
									<option value="1" <?= ($sale->isDelivered == 1 ? 'SELECTED' : '') ?>>Yes</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Front End Gross</label>
								<input type="text" numeric class="form-control" name="frontGross" value="<?= round($sale->frontGross,2) ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Back End Gross</label>
								<input type="text" numeric class="form-control" name="backGross" value="<?= round($sale->backGross,2) ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Applicant(s)</label>
								<select class="form-control" name="applicantType">
									<option value="0" <?= ($sale->applicantType == 0 ? 'SELECTED' : '') ?>><?= $lead->name ?></option>
						<?php
							if(is_array($lead->coapplicants) && count($lead->coapplicants) > 0) {
								$coapplicant = $lead->coapplicants[0];
						?>
									<option value="1" <?= ($sale->applicantType == 1 ? 'SELECTED' : '') ?>><?= $coapplicant->name ?></option>
									<option value="2" <?= ($sale->applicantType == 2 ? 'SELECTED' : '') ?>><?= $lead->name ?> & <?= $coapplicant->name ?></option>
						<?php
							}
						?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>