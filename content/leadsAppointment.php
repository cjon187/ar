<?php include_once('leads_header.php'); ?>

		
<style>

	.leadInfo .title{
		font-weight:bold;
		font-size:1.1em;
	}
</style>
<script>

</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=View&id=<?= $lead->id ?>"><?= $lead->name ?></a></li>
				<li class="active">
					<?= (is_null($transaction->id) ? 'Add' : 'Edit') ?> Appointment
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
					<div class="form-group">
						<label>Appointment Time</label>
						<input type="text" class="form-control" datetimepicker name="appointmentTime" placeholder="y-m-d h:i" value="<?= (is_null($appt->appointmentTime) ? '' : $appt->appointmentTime->format('Y-m-d H:i:s')) ?>">
					</div>
				</div>
			</div>
		</div>

	</form>
</div>