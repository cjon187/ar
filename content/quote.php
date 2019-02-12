<style>
	#nonprime > .row h1 {
		line-height:1em;
		margin-bottom:0px;
	}
	.filters {
		margin-top:5px;
		padding:10px;
		background-color:#f7f7f7;
	}

	.filters label{
		width:100px;
	}

	.quotes {
		margin:10px 0px;
	}

	[quoteID] {
		cursor:pointer;
	}

	[quoteID]:hover {
		background: #f7f7f7;
	}

	.pagination {
		margin:0px;
	}

	.paginationRow {
		margin-top:15px;
		margin-bottom:5px;
	}
	.sectionTableRow td {
		font-size:0.9em;
	}
	.sectionTableRow td.bold {
		font-weight:bold;
	}
	.sectionTableRow td.small {
		font-size:0.9em;
	}

	.sectionTableRow.quoteSigned {
		background-color:#96ff9b;
	}
</style>

<script src="scripts/pagination/jquery.twbsPagination.min.js"></script>
<script>
	$(function() {
		$('[quoteID]').click(function() {
			editQuote($(this).attr('quoteID'));
		})

		<?php
			if($_SESSION['quotes']['search']['created'] != '') {
		?>
			created = jQuery.parseJSON('<?= $_SESSION['quotes']['search']['created'] ?>');
			$('[name=created]').daterangepicker("setRange", {start: moment(created.start).startOf('day').toDate(),end: moment(created.end).startOf('day').toDate()});
		<?php
			}
		?>


		$('#pagination').twbsPagination({
	        totalPages: <?= $quotesPageCount ?>,
	        startPage: <?= $pagination ?>,
	        visiblePages: 20,
	        onPageClick: function (event, page) {
	            location.href='?s1=<?= $_GET['s1'] ?>&p=' + page;
	        }
	    });
	});

	function clearFilters() {
		$("#filterForm input,select").val('');
		$("#filterForm").submit();
	}

	function addQuote() {
		if($('#addQuoteDealerID').val() != '') {
			var win = window.open('?s1=quote&s2=Add&new&dealerID=' + $('#addQuoteDealerID').val() + '&typeID=' + $('#addQuoteTypeID').val(),'addQuote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1');
			win.focus();
		} else if($('#addQuoteDealerGroupID').val() != '') {
			var win = window.open('?s1=quote&s2=Add&new&dealerGroupID=' + $('#addQuoteDealerGroupID').val() + '&typeID=' + $('#addQuoteTypeID').val(),'addQuote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1');
			win.focus();
		}
		else {
			alert('Please select a dealer or an dealer group');
		}
	}
	function editQuote(qid) {
		var win = window.open('?s1=quote&s2=Add&qid=' + qid,'addQuote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1');
		win.focus();
	}
</script>

<div id="ar-page-title">Sales Orders</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-xs-3">
		</div>
		<div class="col-xs-9">
			<div style="text-align:right;padding-top:10px">
				<button type="button" class="btn btn-primary btn-sm" onClick="location.href='?s1=package'">Packages Setup</button>
				<button type="button" class="btn btn-primary btn-sm" onClick="$('#add_modal').modal('show');">Add Sales Order</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="filters">
				<form method="POST" id="filterForm" class="form-inline">
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<input type="text" style="width:250px" class="form-control input-sm" name="textSearch" value="<?= $_SESSION['quotes']['search']['textSearch'] ?>" placeholder="Search Dealership / Dealer Group Name">
							</div>

							<div class="form-group">
								<select class="form-control input-sm" name="worksheetTypeID">
										<option value="">All Types</option>
								<?php foreach ($defaults as $groupName => $types) { ?>
									<optgroup label="<?= $groupName ?>">
									<?php foreach ($types as $id => $name) { ?>
										<option value="<?= $id ?>" <?= ($_SESSION['quotes']['search']['worksheetTypeID'] == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
									<?php } ?>
									</optgroup>
								<?php } ?>
								</select>
							</div>

							<div class="form-group">
								<select class="form-control input-sm" name="staffID">
									<option value="">All Staff</option>
								<?php foreach ($staff as $id => $name) { ?>
									<option value="<?= $id ?>" <?= ($_SESSION['quotes']['search']['staffID'] == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
								<?php } ?>
								</select>
							</div>
							<!--
							<div class="form-group form-inline">
								<label>OEM</label>
								<select class="form-control input-sm" name="oemID">
									<option value="">Any OEM</option>
							<?php foreach ($oems as $oemID => $oemName) { ?>
									<option value="<?= $oemID ?>" <?= ($_SESSION['quotes']['search']['oemID'] == $oemID ? 'SELECTED' : '') ?>><?= $oemName ?></option>
							<?php } ?>
								</select>
							</div>
							<div class="form-group form-inline">
								<label>Location</label>
								<select class="form-control input-sm" name="locationID">
							<?php foreach($lf->getList() as $lfID => $lfName) { ?>
									<option value="<?= $lfID ?>" <?= ($_SESSION['quotes']['search']['locationID'] == $lfID ? 'selected' : '') ?>><?= $lfName ?></option>
							<?php } ?>
								</select>
							</div>		 -->
						</div>
						<div class="col-md-3">
							<div style="text-align:right">
								<div class="form-group">
									<button type="submit" class="btn btn-success btn-sm">Apply Filter</button>
									<button type="button" class="btn btn-default btn-sm" onClick="clearFilters()">Clear</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

	<?php
		if(is_array($quotes) && count($quotes) > 0) {
	?>
		<div class="row paginationRow">
			<div class="col-xs-9">
				<ul id="pagination" class="pagination pagination-sm"></ul>
			</div>
			<div class="col-xs-3">
				<div style="text-align:right;padding-top:10px"><b>Total Results:</b> <?= $quotesCount ?></div>
			</div>
		</div>

		<div class="quotes">
			<table class="table table-condensed sectionTable">
				<thead>
					<tr class="sectionTableRow <?= ($quote->quoteSigned ? 'quoteSigned' : '') ?>" quoteID=<?= $quote->id ?>>
						<th class="bold">Deal Name</th>
						<th>Dealer/Dealergroup Name</th>
						<th>Package Type</th>
						<th>AR Owner Name</th>
						<th>Created At</th>
					</tr>
				</thead>
			<?php
				foreach ($quotes as $quote) {
					$owner = $quote->owner;
			?>
				<tr class="sectionTableRow <?= ($quote->quoteSigned ? 'quoteSigned' : '') ?>" quoteID=<?= $quote->id ?>>
					<td class="bold"><?= $quote->dealName ?></td>
					<td><?= $owner->name ?></td>
					<td><?= $quote->type->name ?></td>
					<td><?= $quote->staff->name ?></td>
					<td class="small"><b>Created</b> <?= date('M j, Y',strtotime($quote->created_date)) ?></td>
				</tr>
			<?php
				}
			?>
			</table>
	<?php
		} else {
	?>
			<table class="table table-condensed sectionTable">
				<tr class="sectionTableRow notFound">
					<td class="bold">No Sales Orders Found</td>
				</tr>
			</table>
	<?php
		}
	?>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="add_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add a Sales Order</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="comment">Dealership</label>
							<select class="form-control input-sm" id="addQuoteDealerID">
								<option value=""></option>
							<?php foreach ($dealers as $dealerID => $dealerName) { ?>
								<option value="<?= $dealerID ?>"><?= $dealerName ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div style="padding:20px 0px;text-align:center">
							- or -
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="comment">Dealer Group</label>
							<select class="form-control input-sm" id="addQuoteDealerGroupID">
								<option value=""></option>
							<?php foreach ($dealerGroups as $dealerGroupID => $dealerGroupName) { ?>
								<option value="<?= $dealerGroupID ?>"><?= $dealerGroupName ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label for="comment">Packages</label>
							<select class="form-control input-sm" id="addQuoteTypeID">
									<option value=""></option>
							<?php foreach ($defaults as $groupName => $types) { ?>
								<optgroup label="<?= $groupName ?>">
								<?php foreach ($types as $id => $name) { ?>
									<option value="<?= $id ?>"><?= $name ?></option>
								<?php } ?>
								</optgroup>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="addQuote();">Create a Sales Order</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->