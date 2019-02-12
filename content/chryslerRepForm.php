<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<style>
	.container{
		padding: 0px;
	}
	.newContainer{
		margin:10px 10px;
		/* background-color: #DDD; */
		border: 1px solid black;
		border-radius: 5px;
		text-align: left;
	}

	.updateButton{
		font-size: 1.2em;
		font-weight: bold;
		color: white;
		background-color: green;
		padding: 7px;
		border-radius: 5px;
	}
	.updateButton2{
		font-size: 1.1em;
		color: white;
		background-color: blue;
		padding: 4px;
		border-radius: 5px;
	}
	.listContainer{
		border: 1px solid black;
		height: 550px;
		overflow:auto;
	}
	.columnContainer{
		/* border: 1px solid black; */
		height: 550px;
		overflow:auto;
	}
		.pad5{
			padding: 5px;
		}
		.columnContainer .columns{
			text-align: left;
		}

	.mainSelectDiv{
		/* background-color: yellow; */

	}
		.mainSelectRowOpt{
			text-align: left;
			font-size: 1.2em;
			padding: 3px 10px;
			padding-left: 2px;
			font-weight :bold;
			background-color: black;
			color: white;
		}
		.mainSelectRow{
			text-align: left;
			font-size: 1.0em;
			padding: 3px 10px;
			padding-left: 20px;
			border-bottom: 1px solid #ccc;
			cursor: pointer;
			min-height: 20px;
		}
			/* .mainSelectRow:first-child{
				border-top: 1px solid #ccc;
			} */
			.mainSelectDiv .SELECTED{
				font-size: 1.2em;
				font-weight:bold;
				background-color: #ddd;
				padding-left: 20px;

			}

	.listHref{
			color:black;
			text-decoration: none;
		}
		.listHref:hover{
			color:black;
			text-decoration: none;
		}
		.listHref:active{
			color:black;
			text-decoration: none;
		}

	/* FORM CLASSES */
	.label{
		text-align: left;
		padding-left: 0;
		color: black;
	}

	input[type="text"]:disabled {
	    background-color: #ddd;
	}
	input[type="select"]:disabled {
	    background-color: #ddd;
	}
	.field select[disabled] {
	    background-color: #ddd;
	}

	.repInfo{
		display:none;
		background-color: #eee;
		text-align: left;
		width: 500px;
		float: left;
		margin-left: 5px;
		border: 1px solid black;
		padding: 5px;
		border-radius: 5px;
		margin-bottom: 5px;
	}

	.submitButton{
		color:white;
		padding: 8px 13px;
		border:none;
		border-radius: 5px;
		font-weight: bold;
		font-size: 1.1em;
		margin-right: 15px;
	}
		 .icon-input-btn{
		        display: inline-block;
		        position: relative;
		    }
		    .icon-input-btn input[type="submit"]{
		        padding-left: 2em;
		    }
		     .icon-input-btn input[type="button"]{
		        padding-left: 2em;
		    }
		    .icon-input-btn .glyphicon{
		        display: inline-block;
		        position: absolute;
		        left: 0.65em;
		        top: 27%;
		        color: white;
		    }
		.deleteSubmit{
			background-color: red;
		}
		.updateSubmit{
			background-color: green;
		}
		.createSubmit{
			background-color: green;
		}
		.newStaff{
			background-color: #1E90FF;
		}




</style>



<script>
	$(document).ready(function(){

		<?php
		if(!$newRep){
			?>
				console.log($('.SELECTED').position().top);
				console.log($('.SELECTED').height());


				//console.log($('.columnContainer').scrollTop(870));
				$('.listContainer').scrollTop($('.SELECTED').parent().prevAll('.mainSelectRowOpt:first').position().top - 20);
				//$('.columnContainer').scrollTop($('.SELECTED').position().top - $('.SELECTED').height() - 75);
			<?php
		}
		?>
		updateDropdown();

		$('#repManagerID').on('change', function(){
			$('#repManagerStatus').val("changed");
		});

		$('#toggleRepDealers').on('click', function(){

			if($('#repDealers').is(':visible')){
				$('#repDealers').hide(500);
				$(this).children("span").html("&#9658;");
			} else {
				$('#repDealers').show(500);
				$(this).children("span").html("&#9660;");
			}
			return false;
		});
		$('#toggleRepTeam').on('click',function(){
			if($('#repTeam').is(':visible')){
				$('#repTeam').hide(500);
				$(this).children("span").html("&#9658;");

			} else {
				$('#repTeam').show(500);
				$(this).children("span").html("&#9660;");
			}
			return false;
		});
	});


	function updateDropdown(){
			<?php
			if(!$newRep){
				?>
					//$('.mainSelectDiv').scrollTop($('.SELECTED').parent().position().top - $('.SELECTED').height() - 110);
				<?php
			}
			?>
		}

	function deletePrompt(){
		if($('#repReassignDiv').length || $('#repManagerReassignDiv').length ){
			showReassignWindow(repID);
		}
		else {
			deleteRep();
		}
	}

	function showReassignWindow(repID){
		$('#reassignStatus').html('');
		$('#reassignDelete').hide();

		$('#dialog').attr('title', 'New title');
		//$('#reassignID').val(repID);

		$('#dialog').dialog({
					width: 700,
					height: 600,
					close: function(){
						location.reload();
					}

				});
	}

	function reassignRep(){
		var repID = $('#repID').val();
		var $dealerRows = $('.repDropdown');

		$dealerRows.each(function(){
			var dealerID = $(this).attr('id').split('_')[0];
			var replaceRepID = $(this).val();
			if(repID != replaceRepID){
				$.ajax({
					data:	{reassignDealerRep: '',reassignDealerID: dealerID, reassignRepID: replaceRepID},
					type: 'POST',
					dataType: 'script'
				});
			}
		});
	}

	function reassignRepManager(){
		var managerRepID = $('#repID').val();
		var $managerRows = $('.repManagerDropdown');

		$managerRows.each(function(){
			var repID = $(this).attr('id').split('_')[0];
			var replaceRepID = $(this).val();
			if(managerRepID != replaceRepID){
				$.ajax({
					data:	{reassignManagerRep: '',repID: repID, newManagerID: replaceRepID},
					type: 'POST',
					dataType: 'script'
				});
			}
		});
	}

	function deleteRep(){
		var deleteID = $('#repID').val();
		if(confirm("Are you sure you want to delete this Rep?")){
			$.ajax({
				data: {deleteRep: deleteID},
				type: 'POST',
				dataType: 'script'
			});
		}
	}

	function reinstateRep(){
		var name = $('#name').val();
		$.ajax({
				data: {reinstateRep: name, formData: $('#repForm').serialize()},
				type: 'POST',
				dataType: 'script'
			});
	}

</script>

<?php
	//print_r2($oemRep->businessCenter->businessCenterName);
	//print_r2($oemRep->manager);
	//print_r2($oemRep->team);
?>

<div id="ar-page-title">OEM Reps</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class = "container">
	<div>
		<div class="row" style="padding: 0 10px;">
			<div class="col-sm-3">

				<div class="listContainer" id="columnContainerThree">
					<div class="pad5">
						<div class="mainSelectDiv">
							<?php
							$oemAreas = getActiveOemRepArray();
							if(count($oemAreas) > 0){
								foreach($oemAreas as $key=>$area){
									?>
										<div class="mainSelectRowOpt" <?= (count($area) == 0 ? 'style="background-color: #888;"' : '') ?>>
											<?= strtoupper($key) ?>
										</div>
										<?php
											foreach($area as $key=> $rep){
												?>
												<a href="<?= AR_URL ?>index.php?s1=chryslerRepForm&id=<?= $rep['repID'] ?>" class="listHref" >
													<div id ="<?= $rep['repID'] ?>_select" class="mainSelectRow <?=($rep['repID'] == $oemRep->repID ? ' SELECTED' : '' ) ?>">

													<?= $rep['name'] . ($rep['isRepManager'] == 1 ? ' <b>[Manager]</b>' : '') ?>

													</div>
												</a>
												<?php
											}
										?>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-9">
				<div class="columnContainer">
					<div class="pad5">
						<div class="errorsDiv">
							<?php
							if(count($errors) > 0){
								?>
								<h3 style="color:red; margin:5px 0; text-decoration:underline;">Validation Errors</h3>
								<div style="margin-bottom: 10px;">
								<?php
								foreach($errors as $error){
									?>
										<div class="errorRow" style="color: red;">- <?= $error[0] ?></div>
									<?php
								}
								?> </div> <?php
							}
							if(count($_SESSION['chryslerRepForm']['errors']) > 0){
								?>
								<h3 style="color:red; margin:5px 0; text-decoration:underline;">Sql Errors</h3>
								<div style="margin-bottom: 10px;">
								<?php
								foreach($_SESSION['chryslerRepForm']['errors'] as $error){
									?>
									<div class="errorRow" style="color: red;">- <?= $error ?></div>
									<?php
								}
								?> </div> <?php
							}
							unset($_SESSION['chryslerRepForm']['errors']);
							?>
						</div>
						<div >
							<?php
							if($oemRep->name != ""){
								echo '<h3 style="margin-top: 0; text-decoration: underline;" id = "mainDataHeader"> '. $oemRep->name .' </h3>';
							}
							?>

							<form method="POST" id="repForm">
								<input name="repID" id="repID" type="hidden" value="<?= ($oemRep->repID != 0 ? $oemRep->repID : 0 ) ?>" />
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="name">Name</label>
											<input id="name" class="form-control" type="text" name="name" value="<?= $oemRep->name ?>" placeholder="Oem Rep Name" />
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="phone">Phone</label>
											<input id="phone" class="form-control" type="text" name="phone" value="<?= $oemRep->phone ?>" placeholder = "Phone" />
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="email">Email</label>
											<input id="email" class="form-control" type="text" name="email" value="<?= $oemRep->email ?>" placeholder = "Email" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="businessCenterID">BusinessCenterID</label>
											<select id="businessCenterID" class="form-control" name="businessCenterID" style="width: 99%;">
												<?php
													$bcs = BusinessCenter::getList();
													foreach($bcs as $key=>$bc){
														if($key == "1"){}
														else{
														?>

															<option value = "<?= $key ?>" <?= ($oemRep->businessCenterID == $key ? 'SELECTED' : '' )?>> <?= $bc ?></option>
														<?php
														}
													}
												?>
											</select>
										</div>
										<div>
											If you are adding a Rep for an area or brand that does not exist yet. Please contact Dev.
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<input type="hidden" id = "repManagerStatus" name="repManagerStatus" value="noChange">
											<label for="repManagerID">Rep Manager</label>
											<select id="repManagerID" class="form-control" name="repManagerID" style="width: 99%;">
												<option value=""></option>
												<?php
												$oemManagerAreas = getActiveOemRepManagerArray();
												if(count($oemManagerAreas) > 0){
													foreach($oemManagerAreas as $key=>$area){
														?>
														<optgroup label="<?= strtoupper($key) ?>">
														<?php
														foreach($area as $key=> $rep){
															?>
															<option value="<?= $rep['repID'] ?>" <?= ($rep['repID']  == $oemRep->manager->id ? 'SELECTED' : '' ) ?> ><?= $rep['name'] ?></option>
															<?php
														}
														?>
														</optgroup>
														<?php
													}
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="isRepManager">Is a Rep Manager</label>
											<select style="padding-top: 1px;" id="isRepManager" class="form-control" name="isRepManager">
												<option value="0" <?= ($oemRep->isRepManager == 0 ? 'SELECTED' : '' ) ?>>No</option>
												<option value="1" <?= ($oemRep->isRepManager == 1 ? 'SELECTED' : '' ) ?>>Yes</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row" style="margin-top: 10px;">
									<div class="eight columns">
										<div style="float:right; margin-right: 15px;">
											<?php
											if($newRep){
												?>
												<span class="icon-input-btn" style="float:right;">
													<span class="glyphicon glyphicon-plus"></span>
													<input type="submit" name="action" class="createSubmit submitButton" value="Create">
												</span>
												<?php
											} else {
												?>
												<span class="icon-input-btn" style="float:right;">
													<span class="glyphicon glyphicon-remove"></span>
													<input type="submit" name="action" class="deleteSubmit submitButton" onClick = "deletePrompt(); return false;" value="Remove">
												</span>
												<span class="icon-input-btn" style="float:right;">
													<span class="glyphicon glyphicon-pencil"></span>
													<input type="submit" name="action" class="updateSubmit submitButton" value="Update">
												</span>
												<span class="icon-input-btn" style="float:right;">
													<span class="glyphicon glyphicon-plus"></span>
													<input type="button" name="newStaff" class="newStaff submitButton" onClick="window.location.href='<?= AR_URL ?>index.php?s1=chryslerRepForm'" value="New Rep">
												</span>

												<?php
											}
											?>
										</div>

									</div>
								</div>
							</form>
							<div style="font-size: 1.2em; margin-bottom: 5px;">
								<a id="toggleRepDealers" href="#" style="text-decoration:none;">
									<span >&#9658; </span>
									<?= count($oemRep->dealers) ?> Dealers
								</a>

							</div>
							<div id = "repDealers" class="repInfo" style="display:none;">
								<?php
									if(count($oemRep->dealers) > 0){
										foreach($oemRep->dealers as $dealer){
											echo '<div>'.$dealer->dealerID. ' - '. $dealer->dealerName.'</div>';
										}
									}
								?>
							</div>

							<div style="font-size: 1.2em; margin-bottom: 5px; clear:both; ">
								<a id="toggleRepTeam" href="#" style="text-decoration:none;" >
									<span >&#9658; </span>
									<?= count($oemRep->team) ?> Members on Team
								</a>
							</div>
							<div id = "repTeam" class="repInfo" style="display:none;">
								<?php
								if(count($oemRep->team) > 0){
									foreach($oemRep->team as $member){
										echo '<div>'.$member->repID. ' - '. $member->name.'</div>';
									}
								}
								?>
							</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="dialog" title="Reassign Manufacturer Rep!" style="display:none;">
<!-- <div class="modal" id="reassignIDDiv"> -->
	<input type="hidden" id="reassignID" value="<?= ($oemRep->repID != 0 ? $oemRep->repID : 0 ) ?>">


	<?php
	$oemDealers = $oemRep->dealers;
	if(count($oemDealers) > 0){
	?>
		<p>This chrysler Rep is currently assigned to the following dealers </p>
		<p> Please reassign new Manufacturer Reps to these dealers if you want to continute with Delete.</p>

		<div id= "repReassignDiv" style="padding: 10px; height: 350px; overflow: scroll;">
		<?php
		//$oemAreas = getActiveOemRepManagerArray();
		$bcReps = new OemRep();
		$bcReps = $bcReps->where('businessCenterID', $oemRep->businessCenterID)->where('status', 1)->get();
		if(count($bcReps) > 0){
			foreach($bcReps as $rep){
				$repDropdown .= '<option value="'. $rep->id.'" '. ($rep->id == $oemRep->id ? 'SELECTED' : '' ) .'>'. $rep->name .'</option>';
			}
		}

		foreach($oemDealers as $dealer){
			echo '<div id="'. $dealer->id .'_dealerRepRow">';
				echo '<select class="repDropdown" id="'. $dealer->id .'_dealerOption"><option value=""></option>' . $repDropdown .'</select>';
				echo '<div style="display:inline-block; margin-left: 10px;">'. $dealer->id . ' - ' . $dealer->name.'</div>';
			echo '</div>';
		}

		?>
		</div>
		<input type="submit" name="action" class="updateSubmit submitButton" value="Reassign" onClick = "reassignRep(); return false;" >
	<?php
	}
	?>


	<?php
	$oemTeam = $oemRep->team;
	if(count($oemTeam) > 0){
	?>
		<p>This chrysler Rep is currently managing the following Reps </p>
		<p> Please assign these reps to a new Manager before deleting.</p>

		<div id= "repManagerReassignDiv" style="padding: 10px; height: 350px; overflow: scroll; clear:both;">
		<?php
		$repDropdown = '';
		$managerReps = new OemRep();
		//$oem = $oemRep->businessCenter->oem->id;
		$managerReps = $managerReps->where('isRepManager', 1)->where('status', 1)->get();
		if(count($managerReps) > 0){
			foreach($managerReps as $rep){
				$repDropdown .= '<option value="'. $rep->id.'" '. ($rep->id == $oemRep->id ? 'SELECTED' : '' ) .'>'. $rep->name .'</option>';
			}
		}

		foreach($oemTeam as $rep){
			echo '<div id="'. $rep->repID .'_managerRepRow">';
				echo '<select class="repManagerDropdown" id="'. $rep->repID .'_managerOption"><option value=""></option>' . $repDropdown .'</select>';
				echo '<div style="display:inline-block; margin-left: 10px;">'. $rep->repID . ' - ' . $rep->name.'</div>';
			echo '</div>';
		}
		?>
		</div>
		<input type="submit" name="action2" class="updateSubmit submitButton" value="Reassign" onClick = "reassignRepManager(); return false;" >
	<?php
	}
	?>


	<span id="reassignDelete" class="icon-input-btn" style="float:right; margin-top: 10px; display: none;">
		<span class="glyphicon glyphicon-remove"></span>
		<input type="submit" name="action" class="deleteSubmit submitButton" onClick = "deleteRep(); return false;" value="Delete">
	</span>
	<div id="reassignStatus" style="color: red;"></div>
</div>





