<style>

	.leadInfo {

		font-size:1em;
		line-height:1.2em;
		text-align:left;


		margin-top:5px;
		padding:10px 20px;
	}
	.leadInfo .name{
		font-weight:bold;
		font-size:1.2em;
		margin-bottom:20px;
	}
	.leadInfo > div {
		margin-bottom:5px;
	}

	.leadInfo .buttonDiv {
		margin-top:20px;
	}

	.leadInfo .panel-heading {
		padding:5px 10px;
		font-weight:bold;
	}
	.leadInfo .panel-title {
		font-weight:bold;
	}

	.leadInfo .panel-body {
		padding:10px;
	}
	.leadInfo .panel-body.no_padding {
		padding:0px;
	}

	.leadInfoTable {
		padding:0px;
		margin:0px;
	}

	.leadInfoTable thead tr th {
		font-size:0.9em;
		color:#999;
		font-weight:normal;
		padding:0px;
		margin:0px;
		border-bottom:1px solid #ccc;
	}
	.leadInfoTable tbody tr td {
		border:0px;
		padding:0px;
		line-height:1.1em;
	}

	.customerInfoTable tbody tr td:first-child {
		padding-right:5px;
		max-width:70px;
		min-width:30px;
	}

	#vehiclesDiv {
		overflow-x:auto;
		width:100%;
	}

	#vehiclesTable {
		min-width:700px;
		width:100%;
		margin-bottom:5px;
	}

	#vehiclesTable thead tr th,#vehiclesTable tbody tr td {
		text-align:center;
	}
	#vehiclesTable thead tr th:first-child,#vehiclesTable tbody tr td:first-child {
		text-align:left;
	}
	#vehiclesTable tbody tr:first-child td {
		padding-top:5px;
	}

	#vehiclesTable .moreVehicles {
		display:none;
	}

/* 	#vehiclesTable .noLongerOwn  {
	display:none;
} */

	#vehiclesTable .noLongerOwn td {
		text-decoration: line-through;
		color:#ccc;

	}

	.activity:nth-child(even) {
		background-color:#f3f3f3;
	}

	.activity {
		padding: 20px 10px;
		margin:0px;
		//border-bottom:1px solid #555;
		font-size:1em;
		cursor:pointer;
		text-align:left;
	}
	.activity > .title {
		margin-bottom:15px;
		display:inline-block;
		width:200px;
	}
	.activity > .details {
		display:inline-block;
		max-width:600px;
		width:100%;
		vertical-align:top;
	}

	.activity .title .activityDate {
		font-size:1.4em;
		margin-bottom:5px;
	}
	.activity .title .activityDate b {
		font-size:1.1em;
	}
	.activity .title .activityDesc {
		color:#aaa;
		font-size:1.1em;
		line-height:1em;
	}

	.activity .title .type {
	}
	.activity .title .desc {
		font-size:0.9em;
	}

	.leadInfoSection {
		margin:10px 0px;
	}

	.leadInfoSection .title{
		font-weight:bold;
	}
	.leadInfoSection em {
		color:#0082bb;
		font-weight:bold;
	}

	.activity .details .alert {
		padding:5px;
		margin-bottom:0px;
		font-size:1em;
		font-size:normal;
		line-height:1em;
	}
	.activity .details .alert .details {
		padding:10px;
		font-size:0.9em;
		line-height:1.1em;
	}

	.activity .details .alert:hover {
		background: #f7f7f7;
	}

	.activity .privateSaleActivities > div {
		font-weight:bold;
		margin-bottom:5px;
	}

	.activity .privateSaleActivities .psDetails {
		font-weight:normal;
		display:inline-block;
	}

	#contactFlags {
		margin-top:10px;
	}
	.marketing {
		margin-bottom:10px;
	}
</style>
<script src="scripts/sweetalert/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="scripts/sweetalert/sweetalert.css">
<script>
	$(function() {

		$('.seeMoreVehicles').click(function() {
			$('.moreVehicles').show();
			$(this).hide();
		})
	});

	function viewMarketing(mid,mtype) {
		window.open('?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&id=<?= $_GET['id'] ?>&viewMarketing&mid=' + mid + '&mtype=' + mtype);
	}

</script>
<div class="row">
	<div class="col-md-12">
		<div class="leadInfo">
			<div class="name"><?= $contact->fullname ?></div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Customer Information</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6">
									<table class="table table-condensed leadInfoTable customerInfoTable">
										<tbody>
									<?php
										if(is_array($contact->contactPhoneNumbers)) {
											foreach($contact->contactPhoneNumbers as $cpn) {
									?>
											<tr>
												<td><b><?= $cpn->getType() ?></b></td>
												<td><?= $cpn->phoneNumber->phoneNumber ?></td>
											</tr>
									<?php
											}
										}
									?>
									<?php
										if(!empty($contact->email)) {
									?>
											<tr>
												<td><b>Email</b></td>
												<td><?= $contact->email ?></td>
											</tr>
									<?php
										}
									?>
											<tr>
												<td><b>Address</b></td>
												<td>
													<div><?= $contact->address1 ?> <?= $contact->address2 ?></div>
													<div><?= $contact->city ?> <?= strtoupper($contact->province->provinceAbbr) ?> <?= $contact->postalCode ?></div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-sm-6">
									<table class="table table-condensed leadInfoTable customerInfoTable">
										<tbody>
											<tr>
												<td><b>Grade</b></td>
												<td><?= $contact->gradeDescription ?></td>
											</tr>
									<?php
										if(!empty($contact->dlCustomerID)) {
									?>
											<tr>
												<td><b>DL Customer ID</b></td>
												<td><?= $contact->dlCustomerID ?></td>
											</tr>
									<?php
										}
									?>
									<?php
										if(!is_null($contact->distance)) {
									?>
											<tr>
												<td><b>Dist. from Dealer</b></td>
												<td>
													<div><?= $contact->distance . 'KM' ?></div>
												</td>
											</tr>
									<?php
										}
									?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div id="contactFlags">
							<?php
								if(!$contact->validAddress) {
							?>
										<span class="label label-danger">Invalid Address</span>
							<?php
								}
							?>
							<?php
								if($contact->isDealership) {
							?>
										<span class="label label-danger">Dealership</span>
							<?php
								}
							?>
							<?php
								if($contact->doNotMail) {
							?>
										<span class="label label-danger">Do Not Mail</span>
							<?php
								}
							?>
							<?php
								if($contact->doNotCall) {
							?>
										<span class="label label-danger">Do Not Call</span>
							<?php
								}
							?>
							<?php
								if($contact->doNotEmail) {
							?>
										<span class="label label-danger">Do Not Email</span>
							<?php
								}
							?>
							<?php
								if($contact->doNotText) {
							?>
										<span class="label label-danger">Do Not Text</span>
							<?php
								}
							?>
							<?php
								if($contact->doNotArcCall) {
							?>
										<span class="label label-danger">Do Not Call - ARC</span>
							<?php
								}
							?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Vehicles</h3>
						</div>
						<div class="panel-body">
							<div id="vehiclesDiv">
						<?php
							$vehicles = $contact->vehicles;
							if(!empty($vehicles)) {
						?>
								<table class="table table-condensed leadInfoTable" id="vehiclesTable">
									<thead>
										<tr>
											<th>Vehicle</th>
											<th>Purchased</th>
											<th>New/Used</th>
											<th>Last RO</th>
											<th>Rate/Term</th>
											<th>Payment</th>
										</tr>
									</thead>
									<tbody>
							<?php
								$index = 0;
								foreach($vehicles as $v) {
									$serviceDate = ($v->lastServiceDate == '' ? '' : date("<b>Y</b> M j",strtotime($v->lastServiceDate)));
									$purchaseDate = ($v->deliveryDate == '' ? '' : date("<b>Y</b> M j",strtotime($v->deliveryDate)));
									$index++;

									$vInfo = $v->toArray();
									$newUsed = '';
									if($vInfo['isNew'] == 1) {
										$newUsed = 'New';
									} else if($vInfo['isNew'] == 2) {
										$newUsed = 'Used';
									}
							?>

								<tr class="<?= ($index >= 3 ? 'moreVehicles' : '') ?> <?= ($v->noLongerOwn ? 'noLongerOwn' : '') ?>">
									<td><?= $v->year ?> <?= $v->brand ?> <?= (empty($v->model) ? $v->description : $v->model) ?></td>
									<td><?= ($purchaseDate == '' ? '' : '<div class="vehicle_details">' . $purchaseDate . '</div>') ?></td>
									<td><?= $newUsed  ?></td>
									<td><?= ($serviceDate == '' || $serviceDate == $purchaseDate ? '' : '<div class="vehicle_details">' . $serviceDate . '</div>') ?></td>
									<td><?= (empty($v->rate) ? '' : number_format($v->rate,2) . '%') ?> <?= (empty($v->term) ? '' : '@' . $v->term) ?></td>
									<td><?= (empty($v->payment) ? '' : '$'.number_format($v->payment,2)) ?></td>
								</tr>
							<?php
								}
							?>
									</tbody>
								</table>
							<?php
								if(count($vehicles) >= 3) {
							?>
									<a class="seeMoreVehicles">+ <?= count($vehicles) - 2 ?> more</a>
							<?php
								}
							} else {
						?>
								<div>No Vehicles Found</div>
						<?php
							}
						?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Activities</h3>
						</div>
						<div class="panel-body no_padding">
							<div class="activities">
					<?php
						if(count($activities) == 0) {
					?>
								<div class="row">
									<div class="col-md-12">
										<div class="activity">
											No Activites Found
										</div>
									</div>
								</div>

					<?php
						} else {
							foreach ($activities as $date => $dateActivities) {
								foreach ($dateActivities as $type => $details) {
					?>
									<div class="activity" activityType="<?= $type ?>">
										<div class="title">
											<div class="activityDate">
												<?= date('<b>Y</b> M j',strtotime($date)) ?>
											</div>
											<div class="activityDesc">
												<div class="type"><?= $cc->activityTypeDescriptions[$type] ?></div>
											</div>
										</div>
										<div class="details">
								<?php
									switch($type) {
										case 'purchase':
											foreach($details as $purchase) {
										?>
											<div class="alert alert-success" role="alert">
												<div class="details">
													<div><b>Sales Rep</b> <?= (empty($purchase->salesperson) ? 'unknown' : $purchase->salesperson) ?></div>
													<?php if(!empty($purchase->term) && $purchase->term > 1) { ?>

														<div class="row">
															<div class="col-sm-6">
																<div><b>Term</b> <?= $purchase->term ?></div>
																<?php if(!empty($purchase->rate)) { ?>
																<div><b>Rate</b> <?= number_format($purchase->rate,2) ?></div>
																<?php } ?>
															</div>
															<div class="col-sm-6">
														<?php if(!empty($purchase->payment)) { ?>
														<div><b>Payment</b> $<?= number_format($purchase->payment,2) ?></div>
														<?php } ?>
															</div>
														</div>



													<?php } ?>

												</div>
											</div>
										<?php
											}
											break;
										case 'service':
											$vehicle = $details['vehicle_details'];
										?>
											<div class="alert alert-warning" role="alert" actionType="<?= $type ?>">
												<div class="details">
													<b><?= $vehicle->year ?> <?= $vehicle->brand ?> <?= $vehicle->model ?></b> brought in for service RO.
												</div>
											</div>
										<?php
											break;
										case 'drive_marketing':
											foreach($details as $m) {
												$vehicle = $m['vehicle_details'];
												$marketing = $m['marketing_details']->details;

												$pmtFreq = $marketing->paymentFrequency;
												$estCurPmt = $marketing->estimatedCurrentPayment;
												$estDiff = $marketing->estimatedDifference;

												if($marketing->dlGroupCode == 'cash'){
													$estCurPmt = 'Cash';
													$estDiff = 'NA';
												} else if($marketing->dlGroupCode == 'paid'){
													$estCurPmt = 'Paid Off Contract';
													$estDiff = 'NA';
												} else if($marketing->dlGroupCode != 'lower' && $marketing->dlGroupCode != 'higher'){
													$estCurPmt = 'NA';
													$estDiff = 'NA';
												} else if(empty($estDiff)) {
													$estDiff = 'NA';
												} else if($marketing->dlGroupCode == 'lower') {
													$estDiff = '-$' . number_format($estDiff,2);
												} else {
													$estDiff = '$' . number_format($estDiff,2);
												}


											?>
												<div class="marketing">
													<div class="alert alert-warning " role="alert" actionType="<?= $type ?>">
														<div class="details">
															Vehicle Quote generated for <b><?= $vehicle->year ?> <?= $vehicle->brand ?> <?= $vehicle->model ?></b>.
															<br>Current <?= $pmtFreq ?> Payment <b><?= $estCurPmt ?></b>
															<br><?= $pmtFreq ?> Payment Difference <b><?= $estDiff ?></b>

															<div style="margin-top:10px">
																<button class="btn btn-success btn-xs" onClick="viewMarketing('<?= $marketing->dlMarketingID ?>','summary')">View Summary PDF</button>
																<button class="btn btn-success btn-xs" onClick="viewMarketing('<?= $marketing->dlMarketingID ?>','desk')">View Desking PDF</button>
															</div>
													<?php
														if(stripos($marketing->dlCommunicationType,'Email') !== false) {
													?>
															<div style="margin-top:10px">
																<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Quote Emailed
															</div>
													<?php
														}
													?>
														</div>
													</div>
												</div>
										<?php
											}
											break;

										case 'private_sale':
											$event = $details['event_details'];
											$eventActivities = $details['event_activities'];

										?>
											<div class="alert alert-info" role="alert" actionType="<?= $type ?>">
												<div class="details privateSaleActivities">
											<?php
												if($eventActivities) {
													foreach($eventActivities as $aType => $privateSaleActivities) {
														foreach($privateSaleActivities as $psad) {
															switch($aType) {
																case CustomerActivityPrivateSaleType::INVITATION:
											?>
																<div>
																	<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Received Invitation
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::APPOINTMENT:
														?>
																<div>
																	<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Booked Appointment for <?= date("M j - h:iA",strtotime($psad->appointmentTime)) ?>
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::SHOW:
														?>
																<div>
																	<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Arrived on Sale Day
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::PURCHASE:
														?>
																<div>
																	<span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Purchased a <?= (empty($psad->year) ? 'Vehicle' : strtolower($psad->newUsed) . ' ' . $psad->year . ' ' . $psad->brand . ' ' . $psad->model)  ?>
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::TRADE:
														?>
																<div>
																	<span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Traded In a <?= (empty($psad->year) ? 'Vehicle' : strtolower($psad->newUsed) . ' ' . $psad->year . ' ' . $psad->brand . ' ' . $psad->model)  ?>
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::RSVP_WEBSITE
														?>
																<div>
																	<span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Registered on RSVP Website <div class="psDetails"><?= $psad->url ?></div>
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::ARC
														?>
																<div>
																	<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Contacted by ARC <div class="psDetails"><?= $psad->disposition->name ?> <?= (!empty($psad->staffID) ? ' by ' . $psad->staff->name : '') ?></div>
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::SMS
														?>
																<div>
																	<span class="glyphicon glyphicon-phone" aria-hidden="true"></span> Received SMS Campaign
																</div>
														<?php
																break;
															case CustomerActivityPrivateSaleType::EMAIL
														?>
														<div>
															<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Received Email Campaign
														</div>
														<?php
																break;
														?>

											<?php
															}
														}
													}
												}
											?>
												</div>
											</div>
										<?php
											break;
									}
								?>
										</div>
									</div>
					<?php
								}
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
