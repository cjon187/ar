<?php
	//include_once('includes.php');
	session_start('conference');
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	include_once('defines.php');
	?>
	<!doctype html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
		<title>Absolute Results Trainer Conference 2017</title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>

		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<link href='http://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="gala.css" type="text/css">

		<link rel="stylesheet" href="<?= AR_SECURE_URL?>bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= AR_SECURE_URL?>bootstrap/css/bootstrap-theme.min.css">


		<link rel="stylesheet" href="<?= AR_SECURE_URL ?>css/sweetalert2/ARsweetalert2.css">
		<script src="<?= AR_SECURE_URL ?>scripts/sweetalert2/sweetalert2.min.js"></script>
		<script src="<?= AR_SECURE_URL ?>scripts/ARSweetAlert.php"></script>

		<body>
	<?php

	$db = new ARDB();
	$errors = array();
	$complete = false;
	$values = [];

	if(isset($_POST['submitForm'])){
		$clean = ARFV::cleanArray($_POST);
		$tc = TrainerConference2017::where('firstname', $clean['firstname'])->where('lastname', $clean['lastname'])->getOne();

		if(!$tc instanceof TrainerConference2017){
			$tc = new TrainerConference2017();
		}

		$tc->firstname = $clean['firstname'];
		$tc->lastname = $clean['lastname'];
		$tc->mobile = $clean['mobile'];
		$tc->email = $clean['email'];
		$tc->territory = $clean['territory'];
		$tc->superbowl = $clean['superbowl'];
		$tc->sundayAccommodations = $clean['sundayAccommodations'];
		$tc->willShareRoom = $clean['willShareRoom'];
		$tc->notes = $clean['notes'];
		$tc->updatedAt = date('Y-m-d H:i:s');

		$values = $clean;

		if(!$tc->save()){
			$errors = $tc->errors;
			$errorsText = '';
			foreach($errors as $e){
				$errorsText .= $e[0]. "<br>";
			}
			?><script>ARAlertError('<?= $errorsText ?>',1);</script> <?php
		}
		else{
			$_SESSION['complete'] = true;
			header('location:http://10.21.0.238/rsvp2017');
			exit;
		}
	}
	//print_r2($data);

	if($_SESSION['complete']){
		?><script>ARAlertSuccessRefresh('You have successfully RSVP\'d <br><br>If you need to re-sumbit, enter the form with the same first and last name to overwrite your last RSVP');</script><?php
		unset($_SESSION['complete']);
	}

	$superbowlrsvps = TrainerConference2017::where('superbowl',1)->get();
	$superbowlCount = count($superbowlrsvps);
	?>

	<script>


		$(document).ready(function() {

		});

	</script>

	<style>
		body {
		  background: url(images/background-image4.JPG) no-repeat center center fixed;
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		}

		.table-curved {

		}
		.table-curved {

		    border-radius: 10px;
		    border-left:0px;
		}
		.table-curved td, .table-curved th {

		}
		.table-curved th {
		    border-top: none;
		}
		.table-curved th:first-child {
		    border-radius: 10px 0 0 0;
		}
		.table-curved th:last-child {
		    border-radius: 0 10px 0 0;
		}
		.table-curved th:only-child{
		    border-radius: 10px 10px 0 0;
		}
		.table-curved tr:last-child td:first-child {
		    border-radius: 0 0 0 10px;
		}
		.table-curved tr:last-child td:last-child {
		    border-radius: 0 0 10px 0;
		}
		.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			border:none;
		}

		@media (max-width: 992px) {
			.small-size-margin{
				margin: 5px 15px;
			}
		}
	</style>

<div style="background-color: black; padding: 15px 0px 15px 45px;">
	<img src="images/Absolute_Results_Favicon_2012.png" style="width: 100px;">
</div>
<div class="container2" >
	<div style="margin-top: 20px;">
		<div class="row">
			<div class="col-md-4 col-md-push-8" style="min-height: 580px;">
				<div class="small-size-margin" style="padding: 15px; border-radius: 15px; background-color: rgba(0,0,0,0.75); color: white; height: 100%; ">
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">What’s happening</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								We are excited to announce the first annual Absolute Results Trainers Conference happening February 6 – 8, 2017.
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">Location</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								Semiahmoo Resort 9565 Semiahmoo Pkwy, <br>Blaine, WA <br><a style="color:#2BABD2;" href="http://semiahmoo.com">www.semiahmoo.com</a>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">Costs</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								Absolute Results will cover all conference costs. You will be responsible for travel to and from the conference.
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">Who’s coming</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								Please note this is a trainer-only conference with three full days of training
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">Questions</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								Contact Priya at <a style="color:#2BABD2;" href="mailto:psami@absoluteresults.com">psami@absoluteresults.com</a>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div style="margin-bottom: 20px">
						<div style="display:inline-block; width: 14%; vertical-align:top;">
							<img src="images/qmwhite.png" width="40px">
						</div>
						<div style="display:inline-block; width: 83%">
							<h4 style="margin: 0px 0 5px 5px; padding-top: 5px;">What if</h3>
							<div style="padding-left: 10px; font-size: 0.9em;">
								<span style="color: #E2293A;">The Trainer Conference is mandatory! </span>
								<br>
								However if for some reason you are not able to attend please explain in the comments.
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-md-pull-4" style="min-height: 580px;">
				<div class="small-size-margin add-extra-top-margin" style="padding: 15px; border-radius: 15px; background-color: rgba(0,0,0,0.75); color: white; ">
					<h2 style="margin-top: 10px;border-bottom: 2px solid white;  ">ABSOLUTE <span style="color: red">RESULTS</span> 2017 Trainer Conference</h2>
					<form method="POST" id="rsvpForm">
						<input type="hidden" name="submitForm" value="1">
						<div class="row" style="margin-top: 10px;">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="firstname">First Name</label>
									<input type="text" class="form-control" id="firstname" name="firstname" value="<?= $values['firstname'] ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="lastname">Last Name</label>
									<input type="text" class="form-control" id="lastname" name="lastname" value="<?= $values['lastname'] ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="mobile">Mobile</label>
									<input type="text" class="form-control" id="mobile" name="mobile" value="<?= $values['mobile'] ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="text" class="form-control" id="email" name="email" value="<?= $values['email'] ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<select id="superbowl" class="form-control" name="superbowl" style="width: 150px; display:inline;">
										<option value="0" <?= ($values['superbowl'] == 0 ? 'SELECTED' : '') ?>>No</option>
										<?php
										if($superbowlCount <= 80){ ?>
											<option value="1" <?= ($values['superbowl'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
										<?php
										} else { ?>
											<option value="1" <?= ($values['superbowl'] == 1 ? 'SELECTED' : '') ?> disabled>Yes - No Space Remaining</option>
										<?php
										}
										?>
									</select>
									&nbsp;&nbsp; Will you attend the optional Superbowl party on Sunday, February 5?
								</div>

							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<select id="sundayAccommodations" class="form-control" name="sundayAccommodations" style="width: 150px; display:inline;">
										<option value="0" <?= ($values['sundayAccommodations'] == 0 ? 'SELECTED' : '') ?>>No</option>
										<option value="1" <?= ($values['sundayAccommodations'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
									</select>
									&nbsp;&nbsp; Will you require accommodations Sunday evening?
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<select id="willShareRoom" class="form-control" name="willShareRoom" style="width: 150px; display:inline;">
										<option value="0" <?= ($values['willShareRoom'] == 0 ? 'SELECTED' : '') ?>>No</option>
										<option value="1" <?= ($values['willShareRoom'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
									</select>
									&nbsp;&nbsp; I am willing to share a room.
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<p style="font-style:italic">* Maximum 80 attendents for the Superbowl Party</p>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<label for="notes">Comments / Concerns / Severe Food Allergies:</label>
									<textarea rows="3" class="form-control" id="notes" name="notes" placeholder=""><?= $values['notes'] ?></textarea>
								</div>
							</div>
						</div>


						<div class="row" style="margin-top: 10px;">
							<div class="col-sm-12" style="text-align:center;">
								<button type="submit" class="btn btn-info" style="width: 150px; font-size: 1.5em;">RSVP NOW</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row" style="margin-top: 15px;">
			<div class="col-xs-12">
				<div class="small-size-margin">
					<table class="table table-curved" style="overflow:hidden; font-weight:bold;">
						<thead>
							<tr style="background-color: rgba(0,0,0,1); color: white; font-size: 1.2em; "><th colspan="3">Agenda</th>
						</thead>
						<tbody>
							<tr style="background-color: rgba(10,10,40,0.75); color: white; ">
								<td>Sunday, February 5</td>
								<td>3:00 PM</td>
								<td>Optional Superbowl Party</td>
							</tr>
							<tr style="background-color: rgba(0,0,0,0.8); color: white; ">
								<td>Monday, February 5</td>
								<td>10:00 AM</td>
								<td>Continental Breakfast Before Welcome</td>
							</tr>
							<tr style="background-color: rgba(10,10,40,0.75); color: white; ">
								<td>Wednesday, February 8</td>
								<td>12:00 PM</td>
								<td>Conference Wrap Up</td>
							</tr>
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- <div class="container2">
	<div class="innerContainer">
		<div class="row">
			<div class="col-sm-12" style="text-align:center; margin-top: 20px;">
				<img src = "images/trainingbanner.png" style="width: 100%;">
			</div>
		</div>
	<?php if($complete) { ?>
			<div class="row">
				<div class="col-xs-12">
					<p style="font-size: 1.4em; text-decoration:underline">
						Submission completed successfully.
					</p>
					<p>
						If you have any additional inquiries you can ask Tim Wong at twong@absoluteresults.com
					</p>
					<p>
						If you wish to re-submit your RSVP, fill it out with the same firstname and lastname and it will replace the exisiting RSVP.
					</p>
				</div>
			</div>
	<?php
		} else {
	?>
		<hr>
		<div class="row">
			<div class="col-xs-12">
				<p>We are excited to announce the first annual Absolute Results Trainers Conference happening February 6 – 8, 2017 at Semiahmoo Resort in Blaine, Washington.</p>
				<p>Absolute Results will cover all conference costs. You will be responsible for travel to and from the conference.</p>
				<p>Please note this is a trainer-only conference; no spouses or guests.</p>
				<p style="font-weight:bold">The Trainer Conference is mandatory!  However if for some reason you are not able to attend please explain in the comments below.
				</p>
			</div>
		</div>
		<hr>
		<?php
		if(!empty($errors) > 0){
		?>
			<div class="row">
				<div class="col-xs-12">
					<h4 style="color: red; text-decoration:underline; margin-bottom: 2px;">Errors</h4>
				</div>
			</div>
			<div class="row" >
				<div class="col-xs-12">
					<?php
					foreach($errors as $key => $e) {
						echo '<p style="color: red; margin-bottom: 2px; margin-left: 15px;">'.$key.': '. $e[0] .'</p>';
					} ?>
				</div>
			</div>
		<?php } ?>

		<form method="POST" id="rsvpForm">
			<input type="hidden" name="submitForm" value="1">
			<div class="row" style="margin-top: 10px;">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="firstname">First Name</label>
						<input type="text" class="form-control" id="firstname" name="firstname" value="<?= $values['firstname'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="lastname">Last Name</label>
						<input type="text" class="form-control" id="lastname" name="lastname" value="<?= $values['lastname'] ?>">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="mobile">Mobile</label>
						<input type="text" class="form-control" id="mobile" name="mobile" value="<?= $values['mobile'] ?>">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="email">Email</label>
						<input type="text" class="form-control" id="email" name="email" value="<?= $values['email'] ?>">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4">
					<div class="form-group">
						<label for="name">Trainer Territory</label>
						<select id="territory" class="form-control" name="territory">
							<option value="Canada" <?= ($values['territory'] == "Canada" ? 'SELECTED' : '') ?>>Canada</option>
							<option value="USA" <?= ($values['territory'] == "USA" ? 'SELECTED' : '') ?>>USA</option>
							<option value="Europe" <?= ($values['territory'] == "Europe" ? 'SELECTED' : '') ?>>Europe</option>
							<option value="Other" <?= ($values['territory'] == "Other" ? 'SELECTED' : '') ?>>Other</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6" style="margin-top: 5px;">
					Will you attend the optional Superbowl party on Sunday, February 5?
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<select id="superbowl" class="form-control" name="superbowl" style="width: 150px;">
							<option value="0" <?= ($values['superbowl'] == 0 ? 'SELECTED' : '') ?>>No</option>
							<option value="1" <?= ($values['superbowl'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6" style="margin-top: 5px;">
					Will you require accommodations Sunday evening?
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<select id="sundayAccommodations" class="form-control" name="sundayAccommodations" style="width: 150px;">
							<option value="0" <?= ($values['sundayAccommodations'] == 0 ? 'SELECTED' : '') ?>>No</option>
							<option value="1" <?= ($values['sundayAccommodations'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6" style="margin-top: 5px;">
					I am willing to share a room.
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<select id="willShareRoom" class="form-control" name="willShareRoom" style="width: 150px;">
							<option value="0" <?= ($values['willShareRoom'] == 0 ? 'SELECTED' : '') ?>>No</option>
							<option value="1" <?= ($values['willShareRoom'] == 1 ? 'SELECTED' : '') ?>>Yes</option>
						</select>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<table class="table table-striped">
						<thead style="background-color: #48949B; color: white;">
							<tr>
								<th>Agenda</th>
								<th>Time</th>
								<th>Title</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Sunday, February 5 </td>
								<td>3:00 pm</td>
								<td>Optional Superbowl Party</td>
							</tr>
							<tr>
								<td>Monday, February 6 </td>
								<td>10:00 am</td>
								<td>Continental breakfast</td>
							</tr>
							<tr>
								<td>Monday, February 6 </td>
								<td>10:30 am</td>
								<td>Conference begins with a Welcome Reception</td>
							</tr>
							<tr>
								<td>Wednesday, February 8</td>
								<td>12:00 pm</td>
								<td>Conference wrap up</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label for="notes">Comments/Questions:</label>
						<textarea rows="2" class="form-control" id="notes" name="notes" placeholder=""><?= $values['notes'] ?></textarea>
					</div>
				</div>
			</div>

			<div class="row" style="margin-top: 10px;">
				<div class="col-xs-12" style="font-style: italic;">
					* The first 100 people to register will get to stay the Sunday night at the resort. Everyone is invited and assistance with alternative Sunday night hotels will be provided upon request. Hotel expenses covered by Absolute Results as part of the conference
				</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div class="col-xs-12" style="font-style: italic;">
					** Direct all questions to Priya Sami at <a href="mailto:psami@absoluteresults.com">psami@absoluteresults.com</a>
				</div>
			</div>

			<div class="row" style="margin-top: 10px;">
				<div class="col-sm-12" style="text-align:center;">
					<button type="submit" class="btn btn-success" style="width: 150px; font-size: 1.5em;">RSVP NOW</button>
				</div>
			</div>
		</form>

		<div class="row" style="height: 50px;">&nbsp;</div>
	</div>
	<?php
		}
	?>
</div> -->

</body>
</html>
