<?php include_once('leads_header.php'); ?>
<style>


	.leadBtns {
		text-align:right;
		padding-top:5px;
	}
	.leadBtns > div {
		margin-bottom:5px;
	}

	.assignedEvent {
		color:#429742;
		font-weight:bold;
		font-size:1.1em;
		padding:10px 0px;
	}
</style>
<script>
	$(function() {
		$('[transactionID]').click(function() {
			editTransaction($(this).attr('transactionTypeID'),$(this).attr('transactionID'))
		})
	});

	function editTransaction(tType,id) {
		location.href="?s1=<?= $_GET['s1'] ?>&s2=Transaction&edit&id=" + id;		
	}

	function refundLead(id) {
		if(confirm('Are you sure you want to refund this lead?')) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=View&refund&id=' + id;
		}
	}
	function unrefundLead(id) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=View&unrefund&id=' + id;
	}
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li class="active"><?= $lead->name ?></li>
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
	<?php
		if($lead->inactiveDate != '') {	
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-danger" role="alert">This lead was refunded on <?= date("M j, Y",strtotime($lead->inactiveDate)) ?>.</div>
		</div>
	</div>
	<?php
		}
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="row">
					<div class="col-xs-8">
						<div class="title"><?= $lead->name ?></div>
						<div class="row">
							<div class="col-xs-4">
								<div>Created: <?= date_create($lead->created)->format('M j, Y') ?></div>
								<div><?= $lead->email ?></div>					
							</div>
							<div class="col-xs-4">
								<div><?= $lead->dealer->name ?></div>
								<div><?= $lead->source->name ?></div>
							</div>
							<div class="col-xs-4">
							<?php if($lead->homePhone != '') { ?><div>H: <?= $lead->homePhone ?></div><?php } ?>
							<?php if($lead->cellPhone != '') { ?><div>C: <?= $lead->cellPhone ?></div><?php } ?>
							<?php if($lead->workPhone != '') { ?><div>W: <?= $lead->workPhone ?></div><?php } ?>
							</div>
						</div>

				<?php
					if(!empty($assignedEvent->id)) {
				?>
						<div class="assignedEvent">Lead assigned to event <?= $assignedEvent->eventName ?></div>
				<?php
					}
				?>
					</div>
					<div class="col-xs-4">
						<div class="leadBtns">					
							<div>
								<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Edit&id=<?= $lead->id ?>'">Edit Lead Details</button>
							</div>
						<?php
							if($lead->inactiveDate != '') {	
						?>		
							<div>	
								<form method="POST">
									<input type="hidden" name="unrefundLead" value="<?= $lead->id ?>">
									<input type="submit" class="btn btn-success btn-xs" value="Un-refund Lead">
								</form>
							</div>
						<?php
							}
							else {
						?>
							<div>
								<div style="display:inline-block">
									<form method="POST">
										<input type="hidden" name="pushToFive9" value="<?= $lead->id ?>">
										<input type="submit" class="btn btn-success btn-xs" value="Push to Five 9">
									</form>
								</div>
								<!-- <div style="display:inline-block">
									<input <?= ($lead->cellPhone != '' && $lead->dealer->dedicatedPhone != '' ? '' : 'disabled="disabled"') ?> type="button" class="btn btn-success btn-xs" value="Message Center" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=SMS&leadID=<?= $lead->id ?>'">
								</div> -->
							</div>

						<?php
							if(!empty($lead->dealerID) && empty($assignedEvent->id)) {
						?>
							<div style="display:inline-block">
								<form method="POST">
									<input type="hidden" name="assignToEvent" value="<?= $lead->id ?>">
									<input type="submit" class="btn btn-success btn-xs" value="Assign to Event">
								</form>
							</div>
						<?php
							}
						?>
							<?php
								if($_SESSION['login']['section']['refund_admin']) {
							?>		

							<div>
								<button type="button" class="btn btn-danger btn-xs" onClick="$('#refund_modal').modal('show')">Refund Lead</button>
							</div>
						<?php
								}
							}
						?>
						</div>
					</div>
				</div><!-- 
				<div class="buttonDiv">					
					<button type="button" class="btn btn-success btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Call&leadID=<?= $lead->id ?>'">Add Call</button>
					<button type="button" class="btn btn-success btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=ApprovalUpdate&leadID=<?= $lead->id ?>'">Add Approval Update</button>
					<button type="button" class="btn btn-success btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Appointment&leadID=<?= $lead->id ?>'">Add Appointment</button>
					<button type="button" class="btn btn-success btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Show&leadID=<?= $lead->id ?>'">Add Show</button>
					<button type="button" class="btn btn-success btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Sale&leadID=<?= $lead->id ?>'">Add Vehicle Purchase</button>
				</div> -->
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="section">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left title">Activities</div>
						
						<div class="pull-right" style="padding-top:5px">					

							<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Call&leadID=<?= $lead->id ?>'">Add Call</button>
							<input <?= ($lead->cellPhone != '' && is_numeric($lead->cellPhone) && $lead->dealer->dedicatedPhone != '' ? '' : 'disabled="disabled"') ?> type="button" class="btn btn-primary btn-xs" value="Add SMS" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=SMS&leadID=<?= $lead->id ?>'">
				
							<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=ApprovalUpdate&leadID=<?= $lead->id ?>'">Add Approval Update</button>
							<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Appointment&leadID=<?= $lead->id ?>'">Add Appointment</button>
							<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Show&leadID=<?= $lead->id ?>'">Add Show</button>
							<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Sale&leadID=<?= $lead->id ?>'">Add Vehicle Purchase</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">

					<?php
						if(count($activities) > 0) {
					?>
						<table class="table table-condensed sectionTable">
						<?php
							foreach ($activities as $activity) { 		
								if($activity['type'] == 'transaction') {
									$transaction = $activity['transaction'];
									$detail = $transaction->details;
									$date = $transaction->date;
						?>
							<tr class="sectionTableRow" transactionTypeID="<?= $transaction->leadTransactionTypeID ?>" transactionID="<?= $transaction->id ?>">
								<td  class="bold"><?= $transaction->getLeadTransactionTypeDesc() ?></td>
								<td ><?= $date->format('M j, Y g:i A') ?></td>
								<td>								
						<?php 
							switch($transaction->leadTransactionTypeID) {
								case LeadTransaction::CALL:
									echo  $detail->disposition->name . ($detail->staff->name != '' ? ' by ' . $detail->staff->name : '');
									break;
								case LeadTransaction::APPOINTMENT:
									break;
								case LeadTransaction::SHOW:
									break;
								case LeadTransaction::SALE:								
									echo 'Purchased: ' . $detail->vehicleYear . ' ' .  $detail->vehicleBrandName . ' ' .  $detail->vehicleModelName;
									break;
								case LeadTransaction::APPROVALUPDATE:								
									echo 'Status: ' . $detail->status->name;
									break;
								default: 
									break;
							}
						?>
								</td>
							</tr>
						<?php
								} else if($activity['type'] == 'sms') {
									$message = $activity['sms'];
									if($message->fromNum == $cellPhone) {
										$desc = 'SMS Received';
									}
									else {
										$desc = 'SMS Sent';
										if($message->sentBy != '') {
											$sentStaff = Staff::byId($message->sentBy);
											$desc .= ' by ' . $sentStaff->name;
										}

									}
						?>
							<tr class="sectionTableRow" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=SMS&leadID=<?= $lead->id ?>'">
								<td  class="bold"><?= $desc ?></td>
								<td ><?= date('M j, Y g:i A',strtotime($activity['date'])) ?></td>
								<td>								
									<?= $message->message ?>
								</td>
							</tr>
						<?php
								}
							}
						?>
						</table>
					<?php
						}
						else {
					?>
						<table class="table table-condensed sectionTable">
							<tr class="sectionTableRow notFound">
								<td class="bold">No Activities Found</td>
							</tr>
						</table>
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
		</div>
	</div>
</div>
<form method="POST" onSubmit="return confirm('Are you sure you want to refund this lead?');">
	<input type="hidden" name="refundLead" value="<?= $lead->id ?>">
	<div class="modal fade" id="refund_modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Refund</h4>
				</div>
				<div class="modal-body">
					<div class="btn-group">
						<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Reasons<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a onClick="$('#refundReason').html($(this).html());">Duplicate Lead</a></li>
							<li><a onClick="$('#refundReason').html($(this).html());">Insufficient/Incomplete Information</a></li>
							<li><a onClick="$('#refundReason').html($(this).html());">Insufficient Income</a></li>		
							<li><a onClick="$('#refundReason').html('');">Other</a></li>		
						</ul>
					</div>
					<br><br>
					<div class="form-group">
						<textarea class="form-control" rows="5" id="refundReason" name="refundReason" placeholder="Reason"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</div><!-- /.modal-dialog -->
			</div>
		</div><!-- /.modal -->
	</div>
</form>