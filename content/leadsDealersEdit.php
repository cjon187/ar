<?php include_once('leads_header.php'); ?>
<style>
	.signed {
		color:green;
		font-weight:bold;
		font-size:0.9em;
	}
</style>
<script>
	function deleteDealer(id) {
		if(confirm('Are you sure you want to remove this dealer from the Non Prime project?')) {
			location.href = '?s1=<?= $_GET['s1'] ?>&s2=Dealers&s3=Edit&delete&id=' + id;
		}
	}
</script>
<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li><a href="?s1=<?= $_GET['s1'] ?>&s2=Dealers">Active Dealers</a></li>
		<?php
			if($leadDealer->id != '') {
		?>
				<li class="active">Edit <?= $leadDealer->dealer->name ?></li>
		<?php
			} else {
		?>
				<li class="active">Add Dealer</li>
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
		<input type="hidden" name="id" value="<?= $leadDealer->id ?>">
		<div class="btnsDiv">
			<button type="submit" class="btn btn-success">Save</button>
			<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Dealers'">Cancel</button>
			<div class="pull-right">
				<!-- <button type="button" class="btn btn-danger" onClick="deleteDealer(<?= $leadDealer->id ?>)">Delete</button> -->
				<button type="button" class="btn btn-primary" onClick="location.href='?s1=crm3&s2=Dealer&id=<?= $leadDealer->dealerID ?>'">View Dealer</button>
			</div>
		</div>
		<div class="formSection">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Dealership</label>
				<?php
					if(!is_null($leadDealer->dealer)) {
				?>
						<input type="hidden" name="dealerID" value="<?= $leadDealer->dealerID ?>">
						<input type="text" class="form-control" readonly value="<?= $leadDealer->dealer->dealerName ?>">
				<?php
					}
					else {
				?>
						<select class="form-control" name="dealerID">
							<option></option>
					<?php foreach ($dealers as $dealerID => $dealerName) { ?>
							<option value="<?= $dealerID ?>"><?= $dealerName ?></option>
					<?php } ?>
						</select>
				<?php
					}
				?>
					</div>
					<div class="form-group">
						<label>Staff</label>
						<select class="form-control" name="staffID">
							<option></option>
					<?php foreach ($staff as $staffID => $staffName) { ?>
							<option value="<?= $staffID ?>" <?= ($staffID == $leadDealer->staffID ? 'SELECTED' : '') ?>><?= $staffName ?></option>
					<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">

				<?php
					if(!is_null($leadDealer->created)) {
				?>
					<div class="form-group">
						<label>Quote</label>
						<div>

						<?php
							$quote = $leadDealer->quote;
							if(!empty($quote->quoteID)) {
						?>
								<button type="button" class="btn btn-success btn-xs" onClick="window.open('export/quote/?id=<?= $quote->id ?>&ekey=<?= encrypt($quote->id,'quote') ?>')">View Quote</button>
							
							<?php
								if($quote->quoteSigned != "") { 
							?>
								<div class="signed">Quote signed on <?= date("M j, Y",strtotime($quote->quoteSigned)) ?>.</div>
							<?php
								}
								else {
							?>
								<button type="button" class="btn btn-primary btn-xs" onClick="window.open('?s1=quote&s2=Add&qid=<?= $quote->id ?>','editquote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1')">Edit Quote</button>
							<?php
								}
							?>
						<?php
							}
							else {
						?>
							<button type="button" class="btn btn-primary btn-xs" onClick="var win = window.open('?s1=quote&s2=Add&new&type=<?= WorksheetType::NONPRIME_PACKAGE_3_EVENTS ?>&dealerID=<?= $leadDealer->dealer->id ?>','addquote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1');win.focus();">Create Quote</button>

						<?php
							}
						?>
						</div>
					</div>
				<?php
					}
				?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Radius</label>
								<input class="form-control" numeric name="radius" value="<?= $leadDealer->radius ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control" name="status">
									<option></option>
									<option value="1" <?= (1 == $leadDealer->status ? 'SELECTED' : '') ?>>Active</option>
									<option value="0" <?= (0 === $leadDealer->status ? 'SELECTED' : '') ?>>In-Active</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>