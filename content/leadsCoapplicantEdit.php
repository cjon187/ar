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

</style>
<script>
	var provinceID = '<?= $coapplicant->provinceID ?>';
	var previousProvinceID = '<?= $coapplicant->previousProvinceID ?>';

	$(function() {
		provinceDropdown('');
		provinceDropdown('previous');

		$('[name=birthdate]').datepicker( "option", "changeMonth", true );
		$('[name=birthdate]').datepicker( "option", "changeYear", true );
		$('[name=birthdate]').datepicker( "option", "yearRange", "1900:<?= date('Y') ?>");

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

	function deleteCoapplicant(id) {
		if(confirm('Are you sure you want to remove this co-applicant?')) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&s3=Edit&delete&id=' + id;
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
		<?php
			if($coapplicant->id != '') {
		?>
				<li class="active">Edit Co-Applicant <?= $coapplicant->name ?></li>
		<?php
			} else {
		?>
				<li class="active">Add Co-Applicant</li>
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
		<input type="hidden" name="id" value="<?= $coapplicant->id ?>">
		<input type="hidden" name="leadID" value="<?= $lead->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Edit&id=<?= $lead->id ?>'">Cancel</button>
			<div class="pull-right">
				<button type="button" class="btn btn-danger" onClick="deleteCoapplicant(<?= $coapplicant->id ?>)">Delete</button>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Primary Applicant</label>
						<input type="text" class="form-control" readonly value="<?= $lead->name ?>">
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
								<input type="text" class="form-control" name="firstName" placeholder="First Name" value="<?= $coapplicant->firstName ?>">
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label>Middle Name</label>
								<input type="text" class="form-control" name="middleName" placeholder="Middle Name" value="<?= $coapplicant->middleName ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" class="form-control" name="lastName" placeholder="Last Name" value="<?= $coapplicant->lastName ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Home Phone</label>
								<input type="text" class="form-control" numeric name="homePhone" placeholder="Home Phone" value="<?= $coapplicant->homePhone ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Cell Phone</label>
								<input type="text" class="form-control" numeric name="cellPhone" placeholder="Cell Phone" value="<?= $coapplicant->cellPhone ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Work Phone</label>
								<input type="text" class="form-control" numeric name="workPhone" placeholder="Work Phone" value="<?= $coapplicant->workPhone ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" placeholder="Email" value="<?= $coapplicant->email ?>">
					</div>
					<div class="form-group">
						<label>SIN #</label>
						<input type="text" numeric class="form-control" name="sin" placeholder="SIN Number" value="<?= $coapplicant->sin ?>">
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Birthdate</label>
								<input type="text" datepicker class="form-control" name="birthdate" placeholder="yyyy-mm-dd" value="<?= $coapplicant->birthdate ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Over 18?</label>								
								<select class="form-control" name="isOver18">
									<option value=""></option>
									<option value="0" <?= ($coapplicant->isOver18 === 0 ? 'SELECTED' : '') ?>>No</option>
									<option value="1" <?= ($coapplicant->isOver18 === 1 ? 'SELECTED' : '') ?>>Yes</option>
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
								<input type="text" class="form-control" name="address" placeholder="Street Address" value="<?= $coapplicant->address ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" placeholder="City" value="<?= $coapplicant->city ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Postal Code</label>
								<input type="text" class="form-control" name="postalCode" placeholder="Postal Code" value="<?= $coapplicant->postalCode ?>">
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
									<option value="<?= $countryID ?>" <?= ($countryID == $coapplicant->countryID ? 'SELECTED' : '') ?>><?= $country ?></option>
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
											<input type="text" class="form-control" numeric name="addressLengthYears" placeholder="#" value="<?= $coapplicant->addressLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>								
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">	
										<div class="input-group">
											<input type="text" class="form-control" numeric name="addressLengthMonths" placeholder="#" value="<?= $coapplicant->addressLengthMonths ?>">
											<span class="input-group-addon">Mths</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Mth. Payment</label>
								<input type="text" class="form-control" name="addressMonthlyPayment" placeholder="Monthly Payment" value="<?= $coapplicant->addressMonthlyPayment ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Rent / Own</label>
								
								<select class="form-control" name="addressRentOwn">
									<option></option>
									<option value="<?= Lead::ADDRESS_RENT  ?>" <?= ($coapplicant->addressRentOwn == Lead::ADDRESS_RENT ? 'SELECTED' : '') ?>>Rent</option>									
									<option value="<?= Lead::ADDRESS_OWN  ?>" <?= ($coapplicant->addressRentOwn == Lead::ADDRESS_OWN ? 'SELECTED' : '') ?>>Own</option>
								</select>
							</div>
						</div>
					</div>
				<?php
					if(!is_null($coapplicant->lat)) {
				?>
					<div class="coordsDiv">
						<?= $coapplicant->lat ?>, <?= $coapplicant->lng ?>
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
								<input type="text" class="form-control" name="previousAddress" placeholder="Street Address" value="<?= $coapplicant->previousAddress ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Previous City</label>
								<input type="text" class="form-control" name="previousCity" placeholder="City" value="<?= $coapplicant->previousCity ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label> Postal Code</label>
								<input type="text" class="form-control" name="previousPostalCode" placeholder="Postal Code" value="<?= $coapplicant->previousPostalCode ?>">
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
									<option value="<?= $countryID ?>" <?= ($countryID == $coapplicant->previousCountryID ? 'SELECTED' : '') ?>><?= $country ?></option>
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
											<input type="text" class="form-control" numeric name="previousAddressLengthYears" placeholder="#" value="<?= $coapplicant->previousAddressLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">	
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousAddressLengthMonths" placeholder="#" value="<?= $coapplicant->previousAddressLengthMonths ?>">
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
									<option value="0" <?= ($coapplicant->isEmployed === 0 ? 'SELECTED' : '') ?>>No</option>
									<option value="1" <?= ($coapplicant->isEmployed === 1 ? 'SELECTED' : '') ?>>Yes</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Occupation</label>
								<input type="text" class="form-control" name="occupation" placeholder="Occupation" value="<?= $coapplicant->occupation ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Employer</label>
								<input type="text" class="form-control" name="employer" placeholder="Employer Name" value="<?= $coapplicant->employer ?>">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Monthly Income</label>
								<input type="text" numeric class="form-control" name="monthlyIncome" placeholder="Monthly Income" value="<?= $coapplicant->monthlyIncome ?>">
							</div>
						</div>
						<div class="col-md-4">
							<label>Years & Months at Employer</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
											
										<div class="input-group">
											<input type="text" class="form-control" numeric name="employerLengthYears" placeholder="#" value="<?= $coapplicant->employerLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
											
										<div class="input-group">
											<input type="text" class="form-control" numeric name="employerLengthMonths" placeholder="#" value="<?= $coapplicant->employerLengthMonths ?>">
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
						<div class="col-md-3">
							<div class="form-group">
								<label>Previous Employer</label>
								<input type="text" class="form-control" name="previousEmployer" placeholder="Previous Employer" value="<?= $coapplicant->previousEmployer ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Previous Monthly Income</label>
								<input type="text" numeric class="form-control" name="previousMonthlyIncome" placeholder="Previous Income" value="<?= $coapplicant->previousMonthlyIncome ?>">
							</div>
						</div>
						<div class="col-md-4">
							<label>Years & Months at Previous Employer</label>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousEmployerLengthYears" placeholder="#" value="<?= $coapplicant->previousEmployerLengthYears ?>">
											<span class="input-group-addon">Yrs</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<div class="input-group">
											<input type="text" class="form-control" numeric name="previousEmployerLengthMonths" placeholder="#" value="<?= $coapplicant->previousEmployerLengthMonths ?>">
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
					<div class="form-group">
						<label>Notes</label>
						<textarea class="form-control" name="notes" rows="5"><?= $coapplicant->notes ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</form>	
</div>