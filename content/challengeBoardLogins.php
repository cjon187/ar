<style>


	hr{
		margin: 0px 0px 10px 0;
		border-top: 1px solid #000;
	}
	
	.form-control{
		height: 24px;
		padding: 2px 4px;
	}

	#cbNav{
		border: 2px solid black;
	}

		#cbNav .listHref{
			padding: 3px 4px; 
			color:black;
			text-align:left;
		}

		#cbNav .listSelected{
			background-color: #ddd;
			font-size: 1.3em;
			font-weight: bold;
		}
		#cbNav .hideRow{
			display:none;
		}
		#cbNav .hideRowLanguage{
			display: none;
		}
		.sort{
			margin: 5px 0;
		}
	#cblNav{
		border: 2px solid black;
	}

		#cblNav .listHref{
			padding: 3px 4px; 
			color:black;
			text-align:left;
		}

		#cblNav .listSelected{
			background-color: #ddd;
			font-size: 1.3em;
			font-weight: bold;
		}
		#cblNav .hideRow{
			display:none;
		}
		#cblNav .hideRowLanguage{
			display: none;
		}

	#addAbroad{
		background-color: #1E90FF;
		padding: 5px 7px;
		width: 100%;
		color: white;
		font-weight: bold;
		border-radius: 5px;
		margin-top: 5px;
	}
		.abroadRow{
			margin-left: 15px;
			font-weight: bold;
		}
		.licenseRow{
			margin-left: 15px;
			font-size: 0.9em;
			font-weight:bold;
		}

	.searchDiv{
		margin: 1px 0;
	}
	.staffLevelSortDiv{
		margin: 1px 0;
	}
	.languageSortDiv{
		margin: 1px 0;
	}

	.required{
		background-color: #FFA07A;
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
		.newCB{
			background-color: #1E90FF;
		}

	.has-error{
		background-color: #FDD7E4 !important;
	}


	.form-group label{
		line-height: 0.80em;
	}

	

</style>

<!-- THIS IS FOR FORM VALIDATION -->
<script src="scripts/jquery.validate.min.js"></script>
<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>
<link rel="stylesheet" href="scripts/datetimepicker/jquery.datetimepicker.css" />
<script src="scripts/datetimepicker/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$("#dateFrom").datepicker({
	        numberOfMonths: 1,
	        changeMonth: true,
  			changeYear: true,
			dateFormat: "yy-mm-dd",
			onSelect: function(selected) {
				$("#dateTo").datepicker("option","minDate", selected);
				
	    	}
		});

		$("#dateTo").datepicker({
	        numberOfMonths: 1,
	        changeMonth: true,
  			changeYear: true,
			dateFormat: "yy-mm-dd",
	        onSelect: function(selected) {
	          	$("#dateFrom").datepicker("option","maxDate", selected)
	          	$('#dateTo').removeClass('required');
			}
		});

		<?php
		if(!$newCBL){
			if(isset($cb) && $cb instanceof ChallengeBoard ){
			?>
				$('#cbNav').scrollTop($('.listSelected').parent().position.top - $('.listSelected').height()-350);
			<?php
			}
			else{
				?>
				$('#cblNav').scrollTop($('.listSelected').parent().position.top - $('.listSelected').height() - 70);
				<?php
			}
		}


		?>

	});


	function removeChallengeBoardFromPivot(loginID, challengeBoardID){
		if(confirm("Are you sure you want to remove this challenge board from this login?")){
			$.ajax({data:	{removeChallengeBoard: '', removeLoginID: loginID, removeChallengeBoardID: challengeBoardID},
					type:	'POST',
					dataType: "json",
					success: function(data){
						if(data.success == "true"){
							location.reload();
						}
						else{
							alert('Delete failed');
						}		
					}
		   	    });	
		}
	}

	function sendLoginCredentials(id,challengeID){
		var username = $('#'+id+'_username').text();
		var password = $('#'+id+'_password').text();
		var email = $('#'+id+'_email').val();
		var challengeID = challengeID;

		$.ajax({data:	{sendCredentialsEmail: '', challengeID: challengeID, username: username, password: password, email:email},
					type:	'POST',
					dataType: "json",
					success: function(data){
						if(data.success == "true"){
							alert("Email sent to "+data.email);
						}
						else{
							alert("Email failed to send to"+data.email + "\n\nThis email address is not valid");
						}	
					}
		   	    });	
	}


</script>


<div class="container">
	<div class="row">
		<div class="col-sm-3" style="">
			<h4 style="text-decoration: underline;">Challenge Board Username Search:</h4>
			<nav id="cblNav" style="clear:both; height: 250px; overflow: scroll; background-color: #eee; color: black; font-size: 0.9em; ">
			  <ul class="nav nav-pills nav-stacked span2">
			  	<?php
			  		$challengeBoardLogins = ChallengeBoardLogin::orderBy('username', 'ASC')->get();
			  		if(count($challengeBoardLogins) > 0){
				  		foreach($challengeBoardLogins as $login){
				  			?>
								<li class="cbLI" style="border-bottom: 1px solid #ccc;">
									<a id="<?= $login->loginID ?>" href="?s1=challengeBoardLogins&loginid=<?= $login->loginID ?>" class="listHref <?= ( $login->loginID == $cbl->loginID ? 'listSelected' : '' ) ?>"  >
										<?= $login->username ?>
									</a>
								</li>
				  			<?php
				  		}
				  	}
			  	?>
			  </ul>
			</nav>

			<h4 style="text-decoration: underline;">Challenge Board Search:</h4>
			<nav id="cbNav" style="clear:both; height: 250px; overflow: scroll; background-color: #eee; color: black; font-size: 0.9em; ">
			  <ul class="nav nav-pills nav-stacked span2">
			  	<?php
			  		$challengeBoards = ChallengeBoard::where('status', 5)->where('challengeURL', '', '!=')->orderBy('challengeURL', 'ASC')->get();
			  		if(count($challengeBoards) > 0){
				  		foreach($challengeBoards as $challengeBoard){
				  			?>
								<li class="cbLI" style="border-bottom: 1px solid #ccc;">
									<a id="<?= $challengeBoard->challengeID ?>" href="?s1=challengeBoardLogins&cbid=<?= $challengeBoard->challengeID ?>" class="listHref <?= ( $challengeBoard->challengeID == $cb->challengeID ? 'listSelected' : '' ) ?>"  >
										<?= ($challengeBoard->challengeURL != "" ? $challengeBoard->challengeURL : AR_URL.'challengeBoard/?name='.$challengeBoard->challengeName )?>
									</a>
								</li>
				  			<?php
				  		}
				  	}
			  	?>
			  </ul>
			</nav>

			
		</div>


		<div class="col-sm-9">
			<div id="errorsDiv">
				<?php
				if(count($errors) > 0){
					?>
					<h3 style="color:red; margin:5px 0; text-decoration:underline;">Errors</h3>
					<?php
					foreach($errors as $error){
						?>
							<div class="errorRow" style="color: red;">- <?= $error[0] ?></div>
						<?php
					}
				}
				?>
			</div>
				
			<?php 
				if(isset($cb) && $cb instanceof ChallengeBoard ){
					?>
					<h3><span style="text-decoration: underline;">Challenge Board:</span> <?= ($cb->challengeURL != "" ? $cb->challengeURL : AR_URL.'challengeBoard/?name='.$cb->challengeName ) ?></h3>

					<h5 style="margin-top: 30px;" >This Challenge Board can be accessed by these logins:</h5>

					<div style='margin-left: 10px; '>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Challenge Username</th>
								<th>Challenge Password</th>
								<th>Send Login to Email:</th>
								<th>Remove</th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($cb->challengeBoardLogins as $chb){
							if($chb->loginID != 1){
							?>
								<tr>
									<td id="<?= $chb->loginID?>_username"><?= $chb->username ?></td>
									<td id="<?= $chb->loginID?>_password"><?= $chb->password ?></td>
									<td><input type="text" id="<?= $chb->loginID?>_email" name="emailAddress" value=""><button onclick="sendLoginCredentials(<?= $chb->loginID .','. $cb->challengeID ?>)" value="Send">Send</button> </td>
									<td><img src="<?= AR_SECURE_URL ?>images/xdark.png" /></td>
								</tr>
							<?php
							}
						}?>
						</tbody>
					</table>
					<h4 style="text-decoration: underline; margin-top: 40px;">Logins Explained:</h4>
					<div>
						<dl>
						<dt>1) Rep Login: (This page)</dt>
						<dd style="margin-left: 20px;">This login (above) is to be given to reps, because they are not tied to a dealer. </dd>
						<dt>2) Staff Login:</dt>
						<dd style="margin-left: 20px;">All AR staff will be able to log into any challenge board with their normal login credentials. </dd>
						<dt>3) Dealer Login: </dt>
						<dd style="margin-left: 20px;">Dealers will be able to log in using their AR username and password. Do not give them other credentials. </dd>
						
					</div>
					<?php
				}



				else if(isset($cbl) && $cbl instanceof ChallengeBoardLogin ){
					?>
					<h3><span style="text-decoration: underline;">Challenge Board Username:</span> <?= $cbl->username ?></h3>
					<div class="row" style="margin-top: 10px; margin-left: 0px; ">
						<div class="col-sm-2">
							<div class="form-group">
								<label for="text1">Username</label>
								<input type="text" class="form-control" id="text1" name="text1" value="<?= $cbl->username ?>" />
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label for="text2">Password</label>
								<input type="text" class="form-control" id="text2" name="text2" value="<?= $cbl->password ?>" />
							</div>
						</div>
					</div>

					


					<h4 style="text-decoration: underline; margin-top: 20px;">This login will work for these challenge boards:</h4>

					<div style='margin-left: 10px; '>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Challenge Name</th>
									<th>Challenge URLs</th>
									<th>Remove</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$dealerBoards = array();
							foreach($cbl->challengeBoards as $chb){
								$dealerBoards[] = $chb->challengeID;
								?>
									<tr>
										<td><?= $chb->challengeName ?></td>
										<td><?= ($chb->challengeURL != "" ? $chb->challengeURL : AR_URL.'challengeBoard/?name='.$chb->challengeName ) ?></td>
										<td><a href="#" onclick="removeChallengeBoardFromPivot(<?= $cbl->loginID.','. $chb->challengeID ?>)" ><img src="<?= AR_SECURE_URL ?>images/xdark.png" /></a></td>
									</tr>
								<?php
							}?>
							</tbody>
						</table>
					</div>
					<h4 style="text-decoration: underline; margin-top: 40px;">Add Challenge Board:</h4>
					<div style='margin-left: 10px; '>
						<form action="" method="POST">
							<input type="hidden" name="challengeLoginID" value="<?= $cbl->loginID ?>">
							<div class="row" >
								<div class="col-sm-3">
									<div class="form-group">
										<label for="challengeBoardID">Challenge Board</label>
										<select style="padding-top: 1px;" id="challengeBoardID" class="form-control" name="challengeBoardID">
											<option value="0"></option>
											<?php
											if(count($challengeBoards) > 0){
										  		foreach($challengeBoards as $challengeBoard){
										  			if(!in_array($challengeBoard->id, $dealerBoards)){
											  		?>
														<option value="<?= $challengeBoard->challengeID ?>" ><?= ($challengeBoard->challengeURL != "" ? $challengeBoard->challengeURL : AR_URL.'challengeBoard/?name='.$challengeBoard->challengeName )  ?></option>
											  		<?php
											  		}
										  		}
										  	}
											?> 
										</select>
									</div>
								</div>
								<input type="submit" name="addCB" class="updateSubmit submitButton" value="Add" style="margin-top: 10px;">
							</div> 
						</form>
					</div>
					<?php

				}
				else{
					?>
					<h3 style="text-decoration: underline;">Choose Challenge Board or Challenge User</h3>
					<?php
				}
			?>
		</div>
	</div>
	
</div>



<script>
	<?php
		if(count($errors) > 0){
			foreach($errors as $key => $error){
				if($key == "username") $key = "text1";
				if($key == "password") $key = "text2";
			?>
				$('#<?= $key ?>').addClass('has-error');	
			<?php
			}
		}
	?>
</script>


