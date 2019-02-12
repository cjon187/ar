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
					<?= (is_null($transaction->id) ? 'Add' : 'Edit') ?> Call
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
						<label>Call Time</label>
						<input type="text" class="form-control" datetimepicker name="callTime" placeholder="y-m-d h:i" value="<?= (is_null($call->callTime) ? date('Y-m-d H:i:s') : $call->callTime->format('Y-m-d H:i:s')) ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Disposition</label>
						
						<select class="form-control" name="dispositionID">
							<option value=""></option>
				<?php
					foreach($dispositions as $did => $dName) {
				?>
							<option value="<?= $did ?>" <?= ($call->dispositionID == $did ? 'SELECTED' : '') ?>><?= $dName ?></option>
				<?php
					}
				?>							
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Notes</label>
						<textarea class="form-control" name="notes" style="height:200px"><?= $call->notes ?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="completed" value="1" <?= ($call->completed ? 'checked' : '') ?> > Completed?
						</label>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>