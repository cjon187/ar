<?php include_once('leads_header.php'); ?>

		
<style>

	.leadInfo .title{
		font-weight:bold;
		font-size:1.1em;
	}
</style>
<script>

	var types = {};
	types['year'] = '<?= $approval->vehicleYear ?>';
	types['make'] = '<?= $approval->vehicleBrandName ?>';
	types['model'] = '<?= $approval->vehicleModelName ?>';

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
					<?= (is_null($transaction->id) ? 'Add' : 'Edit') ?> Approval Update
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
				<div class="col-md-6">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" name="statusID">
									<option></option>
							<?php foreach ($statuses as $statusID => $status) { ?>
									<option value="<?= $statusID ?>" <?= ($statusID == $approval->statusID ? 'SELECTED' : '') ?>><?= $status ?></option>
							<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Reason</label>
								<select class="form-control" name="reason">
									<option></option>
									<option value="Does Not Qualify" <?= ($approval->reason == 'Does Not Qualify' ? 'SELECTED' : '') ?>>Does Not Qualify</option>
									<option value="Unemployed" <?= ($approval->reason == 'Unemployed' ? 'SELECTED' : '') ?>>Unemployed</option>
									<option value="FRO" <?= ($approval->reason == 'FRO' ? 'SELECTED' : '') ?>>FRO</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Lender</label>
						<input type="text" class="form-control" name="lender" value="<?= $approval->lender ?>">
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<label>Amount</label>
								<input type="text" class="form-control" numeric name="amount" value="<?= $approval->amount ?>">
							</div>
							<div class="col-md-6">
								<label>Monthly Payment</label>
								<input type="text" class="form-control" numeric name="monthlyPayment" value="<?= $approval->monthlyPayment ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<label>Rate</label>
								<input type="text" class="form-control" numeric name="rate" value="<?= $approval->rate ?>">
							</div>
							<div class="col-md-6">
								<label>Term</label>
								<input type="text" class="form-control" numeric name="term" value="<?= $approval->term ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Updated</label>
						<input type="text" class="form-control" datetimepicker name="updated" placeholder="y-m-d h:i" value="<?= (is_null($approval->updated) ? date('Y-m-d H:i:s') : $approval->updated->format('Y-m-d H:i:s')) ?>">
					</div>
				</div>
				<div class="col-md-6">
					<label>Approved Vehicle Year / Make</label>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<select class="form-control" name="year" onChange="changeVehicle('year')" disabled></select>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<select class="form-control" name="make" onChange="changeVehicle('make')" disabled></select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Approved Vehicle Model</label>
						<select class="form-control" name="model" onChange="changeVehicle('model')" disabled></select>
					</div>
					<div class="form-group">
						<label>Notes</label>
						<textarea class="form-control" name="notes" style="height:155px"><?= $approval->notes ?></textarea>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>