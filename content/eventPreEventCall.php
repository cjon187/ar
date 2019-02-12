<?php include_once('crm3_header.php'); ?>

<script src="scripts/jquery.form.min.js"></script>
<style>

	.status_alert {
		display:none;
	}

	#eventDetails {
		padding:10px;
	}

<?php
	if(isset($_SESSION['crm']['saved'])) {
		unset($_SESSION['crm']['saved']);
?>
	#alert_success {
		display:block;
	}
<?php
	}
?>
</style>
<script>
	$(function() {
		$('#activityForm').ajaxForm();
	})

	function submitForm(myForm) {
		myForm.ajaxSubmit({
			dataType: 'json',
			success: function(data) {
				if(data.success) {
					location.href='?s1=<?= $_GET['s1'] ?>&s2=PreEventCall&id=' + data.activityID;
				}
				else {
					$('#alert_errors').html('');
					$.each(data.errors, function(errorType,errors) {
						$.each(errors, function(i,error) {
							$('#alert_errors').append('<div>' + error + '</div>');
						});
					});
					$('#alert_errors').show();
					$('#alert_success').hide();
				}
			}
		});
	}

	function openPreSaleSummary() {
		var win = window.open('<?= $event->getPreSaleURL() ?>','preSaleSummary');
		win.focus();
	}

	///////////////////////////////////////
	//OUTCOME JS HANDLED IN CRM_OUTCOME.PHP
	///////////////////////////////////////
</script>
<div class="container-fluid" id="crm">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=crm3&s2=Dealer&id=<?= $event->dealer->id ?>"><?= $event->dealer->name ?></a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&eventID=<?= $event->id ?>"><?= $event->name ?></a></li>
				<li class="active">
					<?= (is_null($activity->id) ? 'Add' : 'Edit') ?> Pre Event Call
				</li>
			</ol>
		</div>
	</div>

	<div id="alert_errors" class="alert alert-danger status_alert" role="alert"></div>
	<div id="alert_success" class="alert alert-success status_alert" role="alert">
			Successfully <strong>Saved</strong>
	</div>
	<form method="POST" id="activityForm" onSubmit="submitForm($(this));return false;">
		<input type="hidden" name="activity_id" value="<?= $activity->id ?>">
		<input type="hidden" name="details_eventID" value="<?= $event->id ?>">
		<input type="hidden" name="details_dealerID" value="<?= $dealer->id ?>">
		<input type="hidden" name="activity_communicationTypeID" value="<?= $activity->communicationTypeID ?>">
		<input type="hidden" id="activity_outcomeID" name="activity_outcomeID" value="<?= CRMOUTCOME::TYPE_NONE ?>">
		<input type="hidden" name="staff_staffID[]" value="<?= $event->trainer->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href=location.href">Cancel</button>
		</div>
		<div class="section">
			<div class="row" id="eventDetails">
				<div class="col-md-6">
					<div class="form-group">
						<label>Dealer</label>
						<?= $event->dealer->name ?>
					</div>
					<div class="form-group">
						<label>Date</label>
						<?= displayEventDate($event->toArray()) ?>
					</div>
					<div class="form-group">
						<label>Trainer</label>
						<?= $event->trainer->name ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="pull-right">
						<button type="button" class="btn btn-primary btn-xs" onClick="openPreSaleSummary()">View Pre Sale Summary</button>
					</div>
				</div>
			</div>
		</div>
		<div class="formSection">

			<div class="row">
				<!-- <div class="col-md-3">
					<div class="form-group">
						<label>Staff</label>
						<input class="form-control" readonly type="text" value="<?= $preEventCall->staff->name ?>">
					</div>
				</div> -->
				<div class="col-md-3">
					<div class="form-group">
						<label>Call Date</label>
						<input class="form-control" datetimepicker type="text" name="details_date" value="<?= $preEventCall->date ?>">
					</div>
				</div>
				<div class="col-md-6">
				<?php include_once('crm3_communications.php'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Comments</label>
						<textarea name="details_comments" class="form-control" style="height:100px"><?= $preEventCall->comments ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php include_once('managementReview.php') ?>