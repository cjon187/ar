<?php include_once('leads_header.php'); ?>
<style>

	.coordsDiv {
		font-size:0.8em;
		color:#555;
		margin-bottom:5px;
	}
	.section_title {
		font-size:2em;
	}

	.previous{
		background-color:#ddd;
		margin:2px;
		padding-top:5px;
	}

	.leadList {
		margin:10px 0px;
	}
	.leadList .row:first-child .leadListItem {
		border-top:3px solid #555;
	}
	.leadListItem {
		padding: 5px;
		margin:0px;
		border-bottom:1px solid #555;
		cursor:pointer;
	}
	.leadListItem:hover {
		background: #ccc;
	}
	.leadListItem .title {
		font-weight:bold;
		font-size:1.1em;
	}
</style>
<script>
	var provinceID = '<?= $lead->provinceID ?>';
	var previousProvinceID = '<?= $lead->previousProvinceID ?>';

	$(function() {
		provinceDropdown('');
		provinceDropdown('previous');

		$('[name=birthdate]').datepicker( "option", "changeMonth", true );
		$('[name=birthdate]').datepicker( "option", "changeYear", true );
		$('[name=birthdate]').datepicker( "option", "yearRange", "1900:<?= date('Y') ?>");

		
		$('[vehicleID]').click(function() {
			location.href='?s1=<?= $_GET['s1'] ?>&s2=Vehicles&s3=Edit&id=' + $(this).attr('vehicleID');
		})
		
		$('[coapplicantID]').click(function() {
			location.href='?s1=<?= $_GET['s1'] ?>&s2=Coapplicant&s3=Edit&id=' + $(this).attr('coapplicantID');
		})
	});
	function changeCountry(aType) {
		if(aType == 'previous')
			previousProvinceID = null;
		else
			provinceID = null;

		provinceDropdown(aType);
	}
	function provinceDropdown(aType) {
		if(aType == 'previous') {
			var cType = 'previousCountryID';
			var pType = 'previousProvinceID';
			var pVal = previousProvinceID;
		}
		else {
			var cType = 'countryID';
			var pType = 'provinceID';
			var pVal = provinceID;			
		}

		$.ajax({data: {
					    provinceLookup: $('[name='+ cType + ']').val()
					  },
			    type: 'POST',
			dataType: "json", 
			 success: function (data) {
		    	$('[name='+ pType + ']').html('');
		    	$('[name='+ pType + ']').append($('<option>'));
		    	$.each(data, function(k,v) {
					$('[name='+ pType + ']').append($('<option>', { 
					        value: k,
					        text : v.toUpperCase()
					    }));
		        });    	

		    	if(Object.keys(data).length > 0) 
		        	$('[name='+ pType + ']').removeAttr('disabled');
		       	else
		        	$('[name='+ pType + ']').attr('disabled',true);

		        $('[name='+ pType + ']').val(pVal);			 					 	
			 }
	    });
	}

	function deleteLead(id) {
		if(confirm('Are you sure you want to remove this lead?')) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=Edit&delete&id=' + id;
		}
	}
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
		<?php
			if($lead->id != '') {
		?>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>"><?= $lead->name ?></a></li>
				<li class="active">Edit <?= $lead->name ?></li>
		<?php
			} else {
		?>
				<li class="active">Add Lead</li>
		<?php
			}
		?>
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
		<input type="hidden" name="id" value="<?= $lead->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>'">Cancel</button>
			<!-- <div class="pull-right">
				<button type="button" class="btn btn-danger" onClick="deleteLead(<?= $lead->id ?>)">Delete</button>
			</div> -->
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Source</label>
				<?php
					if(!is_null($lead->source)) {
				?>
						<input type="hidden" name="leadSourceID" value="<?= $lead->leadSourceID ?>">
						<input type="text" class="form-control" readonly value="<?= $lead->source->name ?>">
				<?php
					}
					else {
				?>
						<select class="form-control" name="leadSourceID">
							<option></option>
					<?php foreach ($sources as $sourceID => $source) { ?>
							<option value="<?= $sourceID ?>" <?= ($sourceID == $lead->leadSourceID ? 'SELECTED' : '') ?>><?= $source ?></option>
					<?php } ?>
						</select>
				<?php
					}
				?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Dealership</label>
				<?php
					if(false && !is_null($lead->dealer)) {
				?>
						<input type="hidden" name="dealerID" value="<?= $lead->dealer->dealerID ?>">
						<input type="text" class="form-control" readonly value="<?= $lead->dealer->dealerName ?>">
				<?php
					}
					else {
				?>
						<select class="form-control" name="dealerID">
							<option></option>
					<?php foreach ($dealers as $did => $dealerName) { ?>
							<option value="<?= $did ?>" <?= ($did == $lead->dealer->dealerID ? 'selected' : '') ?>><?= $dealerName ?></option>
					<?php } ?>
						</select>
				<?php
					}
				?>
					</div>
				</div>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-7">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" class="form-control" name="firstName" value="<?= $lead->firstName ?>">
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label>Middle Name</label>
								<input type="text" class="form-control" name="middleName" value="<?= $lead->middleName ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" class="form-control" name="lastName" value="<?= $lead->lastName ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Home Phone</label>
								<input type="text" class="form-control" numeric name="homePhone" value="<?= $lead->homePhone ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Cell Phone</label>
								<input type="text" class="form-control" numeric name="cellPhone" value="<?= $lead->cellPhone ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Work Phone</label>
								<input type="text" class="form-control" numeric name="workPhone" value="<?= $lead->workPhone ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" value="<?= $lead->email ?>">
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>SIN #</label>
								<input type="text" numeric class="form-control" name="sin" value="<?= $lead->sin ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Marital</label>
								
								<select class="form-control" name="maritalStatus">
									<option value=""></option>
							<?php foreach (Lead::$maritalStatus as $mid => $m) { ?>
									<option value="<?= $mid ?>" <?= ($mid == $lead->maritalStatus ? 'SELECTED' : '') ?>><?= $m ?></option>
							<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Birthdate</label>
								<input type="text" datepicker class="form-control" name="birthdate" placeholder="yyyy-mm-dd" value="<?= $lead->birthdate ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Over 18?</label>								
								<select class="form-control" name="isOver18">
									<option value=""></option>
									<option value="0" <?= ($lead->isOver18 === 0 ? 'SELECTED' : '') ?>>No</option>
									<option value="1" <?= ($lead->isOver18 === 1 ? 'SELECTED' : '') ?>>Yes</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Address</label>
								<input type="text" class="form-control" name="address" value="<?= $lead->address ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" value="<?= $lead->city ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Postal Code</label>
								<input type="text" class="form-control" name="postalCode" value="<?= $lead->postalCode ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Country</label>
								
								<select class="form-control" name="countryID" onChange="changeCountry('')">
									<option></option>
							<?php foreach ($countries as $countryID => $country) { ?>
									<option value="<?= $countryID ?>" <?= ($countryID == $lead->countryID ? 'SELECTED' : '') ?>><?= $country ?></option>
							<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Province</label>
								
								<select class="form-control" name="provinceID" disabled></select>
							</div>
						</div>
						<div class="col-md-4">
							<label>Years & Months at Current Address</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">		
										<div class="input-group">
											<input type="text" class="form-control" numeric name="addressLengthYears" placeholder="#" value="<?= $lead->addressLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>								
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">	
										<div class="input-group">
											<input type="text" class="form-control" numeric name="addressLengthMonths" placeholder="#" value="<?= $lead->addressLengthMonths ?>">
											<span class="input-group-addon">Mths</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Mth. Payment</label>
								<input type="text" class="form-control" name="addressMonthlyPayment" value="<?= $lead->addressMonthlyPayment ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Rent / Own</label>
								
								<select class="form-control" name="addressRentOwn">
									<option></option>
									<option value="<?= Lead::ADDRESS_RENT  ?>" <?= ($lead->addressRentOwn == Lead::ADDRESS_RENT ? 'SELECTED' : '') ?>>Rent</option>									
									<option value="<?= Lead::ADDRESS_OWN  ?>" <?= ($lead->addressRentOwn == Lead::ADDRESS_OWN ? 'SELECTED' : '') ?>>Own</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Mortgage Amount</label>
								<input type="text" class="form-control" numeric name="mortgageAmount"  value="<?= $lead->mortgageAmount ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Mortgage Holder</label>
								<input type="text" class="form-control" name="mortgageHolder"  value="<?= $lead->mortgageHolder ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Market Value</label>
								<input type="text" class="form-control" numeric name="marketValue" value="<?= $lead->marketValue ?>">
							</div>
						</div>
					</div>
				<?php
					if(!is_null($lead->lat)) {
				?>
					<div class="coordsDiv">
						<?= $lead->lat ?>, <?= $lead->lng ?>
					</div>
				<?php
					}
				?>
				</div>
			</div>
			<div class="row previous">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Previous Address</label>
								<input type="text" class="form-control" name="previousAddress" value="<?= $lead->previousAddress ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Previous City</label>
								<input type="text" class="form-control" name="previousCity" value="<?= $lead->previousCity ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label> Postal Code</label>
								<input type="text" class="form-control" name="previousPostalCode" value="<?= $lead->previousPostalCode ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Previous Country</label>
								
								<select class="form-control" name="previousCountryID" onChange="changeCountry('previous')">
									<option></option>
							<?php foreach ($countries as $countryID => $country) { ?>
									<option value="<?= $countryID ?>" <?= ($countryID == $lead->previousCountryID ? 'SELECTED' : '') ?>><?= $country ?></option>
							<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Prev. Province</label>
								
								<select class="form-control" name="previousProvinceID" disabled></select>
							</div>
						</div><div class="col-md-4">
							<label>Years & Months at Previous Address</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">	
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousAddressLengthYears" placeholder="#" value="<?= $lead->previousAddressLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">	
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousAddressLengthMonths" placeholder="#" value="<?= $lead->previousAddressLengthMonths ?>">
											<span class="input-group-addon">Mths</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="formSection">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Employed?</label>								
								<select class="form-control" name="isEmployed">
									<option value=""></option>
									<option value="0" <?= ($lead->isEmployed === 0 ? 'SELECTED' : '') ?>>No</option>
									<option value="1" <?= ($lead->isEmployed === 1 ? 'SELECTED' : '') ?>>Yes</option>
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label>Occupation</label>
								<input type="text" class="form-control" name="occupation" value="<?= $lead->occupation ?>">
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label>Employer</label>
								<input type="text" class="form-control" name="employer" value="<?= $lead->employer ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Employer Address</label>
								<input type="text" class="form-control" name="employerAddress" value="<?= $lead->employerAddress ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Monthly Income</label>
								<input type="text" numeric class="form-control" name="monthlyIncome" value="<?= $lead->monthlyIncome ?>">
							</div>
						</div>
						<div class="col-md-4">
							<label>Years & Months at Employer</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
											
										<div class="input-group">
											<input type="text" class="form-control" numeric name="employerLengthYears" placeholder="#" value="<?= $lead->employerLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
											
										<div class="input-group">
											<input type="text" class="form-control" numeric name="employerLengthMonths" placeholder="#" value="<?= $lead->employerLengthMonths ?>">
											<span class="input-group-addon">Mths</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="row previous">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Previous Employer</label>
								<input type="text" class="form-control" name="previousEmployer" value="<?= $lead->previousEmployer ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Previous Employer Address</label>
								<input type="text" class="form-control" name="previousEmployerAddress" value="<?= $lead->previousEmployerAddress ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Previous Monthly Income</label>
								<input type="text" numeric class="form-control" name="previousMonthlyIncome" value="<?= $lead->previousMonthlyIncome ?>">
							</div>
						</div>
						<div class="col-md-4">
							<label>Years & Months at Previous Employer</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousEmployerLengthYears" placeholder="#" value="<?= $lead->previousEmployerLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousEmployerLengthMonths" placeholder="#" value="<?= $lead->previousEmployerLengthMonths ?>">
											<span class="input-group-addon">Mths</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="formSection">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label>Interested Vehicle Year</label>
						<input type="text" class="form-control" numeric name="interestedVehicleYear" placeholder="#" value="<?= $lead->interestedVehicleYear ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Make</label>
						<input type="text" class="form-control"  name="interestedVehicleMake"  value="<?= $lead->interestedVehicleMake ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Model</label>
						<input type="text" class="form-control"  name="interestedVehicleModel"  value="<?= $lead->interestedVehicleModel ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Time Frame</label>
						<input type="text" class="form-control"  name="interestedVehicleTimeFrame"  value="<?= $lead->interestedVehicleTimeFrame ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Notes</label>
						<textarea class="form-control" name="notes" rows="5"><?= $lead->notes ?></textarea>
					</div>
				</div>
			</div>
		</div>

	<?php 
		if(!empty($lead->id)) {
	?>
		<div class="formSection leadVehicles">
			<div class="row">
				<div class="col-md-12">
					<div class="pull-left">
						<label>Vehicles</label>
					</div>
					<div class="pull-right">
						<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Vehicles&s3=Edit&leadID=<?= $lead->id ?>'">Add Vehicle</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="leadList">
			<?php
				if(is_null($vehicles)) {
			?>
						<div class="row">
							<div class="col-md-12">
								<div class="leadListItem">
									No Vehicles Found
								</div>
							</div>
						</div>
						
			<?php
				}
				else {
					foreach ($vehicles as $vehicle) { 
			?>
						<div class="row">
							<div class="col-md-12">
								<div class="leadListItem" vehicleID="<?= $vehicle->id ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="title"><?= $vehicle->vehicleYear ?> <?= $vehicle->vehicleBrandName ?> <?= $vehicle->vehicleModelName ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
			<?php				
					}
				}
			?>
					</div>
				</div>
			</div>
		</div>


		<div class="formSection leadCoapplicants">
			<div class="row">
				<div class="col-md-12">
					<div class="pull-left">
						<label>Co-Applicants</label>
					</div>
					<div class="pull-right">
						<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Coapplicant&s3=Edit&leadID=<?= $lead->id ?>'">Add Co-Applicant</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="leadList">
			<?php
				if(is_null($coapplicants)) {
			?>
						<div class="row">
							<div class="col-md-12">
								<div class="leadListItem">
									No Co-Applicants Found
								</div>
							</div>
						</div>
						
			<?php
				}
				else {
					foreach ($coapplicants as $coapplicant) { 
			?>
						<div class="row">
							<div class="col-md-12">
								<div class="leadListItem" coapplicantID="<?= $coapplicant->id ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="title"><?= $coapplicant->name ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
			<?php				
					}
				}
			?>
					</div>
				</div>
			</div>
		</div>
	<?php
		}
	?>
	</form>	
</div>