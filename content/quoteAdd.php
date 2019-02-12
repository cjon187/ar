<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="scripts/jquery.textarea_autosize.js"></script>
<script src="scripts/jquery.numeric.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{AR_SECURE_URL}}scripts/font-awesome-4.7.0/css/font-awesome.css" />
<style>
	.section {
		margin-bottom:20px;
	}

	.section .glyphicon-remove {
		color:red;
		cursor:pointer;
	}

	.section .form-control {
		margin-bottom:0px;
		padding-bottom:0px;
	}
	.errorDiv {
		font-weight:bold;
		color:red;
		margin:0px 10px;
	}

	.quoteActionDetails {
		display:inline-block;
		color:#439843;
		font-style:italic;
		font-size:0.9em;
		margin:0px 5px;
	}
	.quoteAction .btn{
		margin-bottom:5px;
	}

	#quoteTitle {
		font-size:2em;
	}
	.dtextarea {
		min-height: 30px;
	}

	#itemsTable input,#itemsTable textarea {
		border:none;
		padding:0px;
		font-size:1em;
		line-height:1em;
		resize: none;
	}
	#itemsTable .form-control {
		box-shadow:none;
		padding:3px;
	}
	#itemsTable td:not(:first-child) {
		border-left:1px solid #ddd;
	}
	#itemsTable td {
		border-bottom:1px solid #ddd;
	}

	#notes {
		min-height:100px;
	}

	.ui-sortable tr {
		cursor:pointer;
	}

	.sortIcon {
		color:#ccc;
		font-size:0.9em;
	}
</style>
<script>
	var quoteID = '<?= $quote->id ?>';
	var quoteTypeID = '<?= $quote->type->id ?>';
	var quoteLanguageID = '<?= $quoteLanguage->id ?>';
	var itemIndex = 0;

	$(function() {
<?php
	if(isset($_SESSION['quote']['reload'])) {
?>
		window.opener.location.reload();
<?php
		unset($_SESSION['quote']['reload']);
	}
?>

		$("[datepicker]").datepicker({
			changeMonth: true,
      		changeYear: true,
      		dateFormat: "yy-mm-dd"
		});

		$("[datepicker]").keydown(function() {
			return false;
		});

		loadItems('<?= $type->id ?>',0);

		//Helper function to keep table row from collapsing when being sorted
		var fixHelperModified = function(e, tr) {
			var $originals = tr.children();
			var $helper = tr.clone();
			$helper.children().each(function(index) {
				$(this).width($originals.eq(index).width())
			});
			return $helper;
		};

		//Make diagnosis table sortable
		$("#itemsTable tbody").sortable({
			helper: fixHelperModified
		}).disableSelection();


        loadAvailableItems(<?= $type->id ?>,<?= $quoteLanguage->id ?>);

	});

	function loadItems(aType,reset) {

        $('#loading_modal').modal('show');
		$.ajax({data:	{loadItems: '',
						 quoteID: quoteID,
						 typeID: aType,
						 reset: reset,
						 quoteLanguageID: quoteLanguageID},
				type:	'POST',
				dataType: 'json',
				success: function(results) {
					if(reset) {
						$("#itemsTable").find('tbody').html('');
					}
					$.each(results, function(i, data) {
						addItem(data.key,'',data.worksheetItemTypeID,data.description,data.quantity,data.unitPrice);
					})
        			$('#loading_modal').modal('hide');
				}
		});




        /*$('#loading_modal').modal('show');
		$.ajax({data:	{loadItems: '',
						 quoteID: quoteID,
						 typeID: '<?= $_GET['typeID'] ?>',
						 quoteLanguageID: quoteLanguageID},
				type:	'POST',
				dataType: 'json',
				success: function(results) {
					$.each(results, function(i, data) {
						addItem(data.key,'',data.worksheetItemTypeID,data.description,data.quantity,data.unitPrice);
					})
        			$('#loading_modal').modal('hide');
				}
		});*/
	}

	function addDefaultItem(worksheetTypeDefaultItemID) {

		$.ajax({data:	{getDefaultItem: worksheetTypeDefaultItemID,
						 quoteTypeID: quoteTypeID,
						 quoteLanguageID: quoteLanguageID},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					addItem(data.key,'',data.worksheetItemTypeID,data.description,data.quantity,data.unitPrice);
				}
		});
	}

	function removeItem(row) {
		row.remove();
	}

	function addItem(key,quoteItemID,worksheetItemTypeID,description,quantity,unitPrice) {
		//rand = Math.floor((Math.random() * 1000) + 1);
		itemIndex++;
		key = key + '_' + itemIndex;
		console.log(key);

		$("#itemsTable").find('tbody')
		.append($('<tr>')
			.attr('quoteItemID', quoteItemID)
			.attr('worksheetItemTypeID', worksheetItemTypeID)
			.append($('<td>')
				.attr('class', 'sortIcon')
				.append($('<span>')
					.attr('class', 'glyphicon glyphicon-sort')
				)
			)
			.append($('<td>')
				.append($('<textarea>')
					.attr('rows', '1')
					.attr('class', 'form-control input-sm dtextarea')
					.attr('name', 'itemsCSV['+ key + '][description]')
					.html(description)
				)
			)
			.append($('<td>')
				.css('position', 'relative')
				.attr('worksheetItemTypeID', worksheetItemTypeID)
				.append($('<input>')
					.attr('type', 'text')
					.attr('numeric','')
					.attr('class', 'form-control input-sm')
					.attr('name', 'itemsCSV['+ key + '][quantity]')
					.attr('placeholder', 'TBD')
					.val(quantity)
				)
			)
			.append($('<td>')
				.append($('<input>')
					.attr('type', 'text')
					.attr('numeric','')
					.attr('class', 'form-control input-sm')
					.attr('name', 'itemsCSV['+ key + '][unitPrice]')
					.attr('placeholder', 'Included')
					.val(unitPrice)
				)
			)
			.append($('<td>')
				.append($('<span>')
					.attr('class', 'glyphicon glyphicon-remove')
					.click(function() {
						removeItem($(this).closest('[worksheetItemTypeID]'));
					})
				)
			)
		);

	<?php
		if(!empty($quote->owner->countryID == COUNTRY_US)) {
	?>
		$('td[worksheetItemTypeID=<?= WorksheetItemType::INVITATIONS ?>]').prepend($('<div>')
			.offset({  left: -30 })
			.css('position', 'absolute')
			.html($('<div>')
				.attr('class', 'btn btn-xs btn-primary')
				.html('<i class="fa fa-calculator" aria-hidden="true"></i>')
				.click(function() {
					updateInvitationKey = key;
					$('#unitPrice_modal').modal('show');
				})
			)
		)
	<?php
		}
	?>

		$('[numeric]').numeric();
		$('.dtextarea').textareaAutoSize();
	}

	function calculateUnitPrice() {
		$.ajax({data:	{calculateUnitPrice: 1,
						 countryID: '<?= $quote->owner->countryID ?>',
						 typeID: $('#invitationUnitPrice').val()},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					if(data.success == 1) {
						$('[name=' + updateInvitationKey + '_unitPrice]').val(data.unitPrice);
						$('#unitPrice_modal').modal('hide');

					} else {
						swal({
							title: "Error!",
							text: 'Unable to calculate Unit Price',
							type: "error",
							confirmButtonText: "Ok"
						});
					}
				}
		});
	}

	function checkQuote() {
		if('<?= ajaxHTML($quote->contactEmail) ?>' == '') {
			ARAlertError('No Contact Saved.');
			return;
		}

		ARAlertConfirmation('Are you sure you want to email the sales order to <?= ajaxHTML($quote->contact . ' ' . $quote->contactEmail) ?>?',
							 null,
							 function() {
							 	emailQuote(false);
							 }, function() {
							 });
	}

	function emailQuote(sendToSelf) {

    	$('#loading_modal').modal('show');
		$('.emailQuoteBtn').addClass('disabled');
		$.ajax({data:	{emailQuote: '<?= $quote->id ?>',
						 sendToSelf: sendToSelf},
				type:	'POST',
				dataType: 'script',
				success: function() {
					window.opener.location.reload();
					location.reload();
				}
		});
	}

	function signQuote() {
		if(confirm('Are you sure you want to mark this sales order as signed?')) {

        	$('#loading_modal').modal('show');
			$('#signQuoteBtn').addClass('disabled');
			$.ajax({data:	{signQuote: '<?= $quote->id ?>'},
					type:	'POST',
					dataType: 'script',
					success: function() {
						location.reload();
					}
			});
		}
	}

	function unsignQuote() {
		if(confirm('Are you sure you want to remove the signature for this sales order?')) {

        	$('#loading_modal').modal('show');
			$('#unsignQuoteBtn').addClass('disabled');
			$.ajax({data:	{unsignQuote: '<?= $quote->id ?>'},
					type:	'POST',
					dataType: 'script',
					success: function() {
						location.reload();
					}
			});
		}
	}

	function deleteQuote() {
		if(confirm('Are you sure you want to delete this sales order?')) {

        	$('#loading_modal').modal('show');
			$('#deleteQuoteBtn').addClass('disabled');
			$.ajax({data:	{deleteQuote: '<?= $quote->id ?>'},
					type:	'POST',
					dataType: 'script',
					success: function() {
						window.opener.location.reload();
						window.close();
					}
			});
		}
	}
/*
	function changeType(typeID) {
		alert('This will reset all items in this agreement. Don\'t save the agreement to revert.');
		location.href='?<?= http_build_query($getParams) ?>&typeID=' + typeID;

	}*/
	function changeType(typeID) {
		loadItems(typeID,1);
		loadAvailableItems(typeID,'<?= $quoteLanguage->id ?>');
	}

	function loadAvailableItems(quoteTypeID,quoteLanguageID) {

		$("#availableItems").html('');

		$.ajax({data:	{loadAvailableItems: '',
						 quoteTypeID: quoteTypeID,
						 quoteLanguageID: quoteLanguageID},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					$.each(data, function(i, item) {
						$("#availableItems")
						.append($('<li>')
							.click(function() {
								addDefaultItem(item.id);
							})
							.append($('<a>')
								.html(item.description)
							)
						)
					})
				}
		});
	}

	function validateForm() {
		if($('#start').val() == '') {
			ARAlertError('Please tell us the effective start date');
			return false;
		} else if($('#end').val() == '') {
			ARAlertError('Please tell us the effective end date');
			return false;
		}

		$('#loading_modal').modal('show');
		return true;
	}

	function editContact() {
		var win = window.open('?s1=crm3&s2=DealerContacts&id=<?= $quote->ownerID ?>','dealerContacts','width=800,height=750,toolbar=0,resizable=1,scrollbars=1');
		win.focus();
	}
</script>
<div class="row">
	<div class="col-md-12">
		<?php
			if(count($_SESSION['quote']['errors'][0]) > 0) {
		?>
			<div class="alert alert-warning">
				<?php
				 	foreach($_SESSION['quote']['errors'][0] as $key => $error){
				 		echo $error[0]. '<br>';
				 	}
				 ?>
			</div>
		<?php
				unset($_SESSION['quote']['errors']);
			}
		?>
		<div class="section">
			<div id="quoteTitle"><b>Sales Order</b></div>
		</div>
		<div class="section">
			<div><b><?= $quote->owner->name ?></b></div>
		<?php
			if($quote->ownerTypeID == Quote::OWNER_TYPE_DEALER) {
		?>
			<div><?= $quote->owner->address ?></div>
			<div><?= $quote->owner->city ?>, <?= strtoupper($quote->owner->province->provinceAbbr) ?>, <?= $quote->owner->postalCode ?> <?= $quote->owner->country->name ?></div>
		<?php
			} else if($quote->ownerTypeID == Quote::OWNER_TYPE_DEALERGROUP) {
		?>
			<div><?= count($quote->owner->dealers) ?> Dealers</div>
		<?php
			}
		?>
		</div>
		<form method="POST" onSubmit="return validateForm()">
			<input type="hidden" name="quoteID" value="<?= $quote->quoteID ?>">
			<input type="hidden" name="ownerID" value="<?= $quote->ownerID ?>">
			<input type="hidden" name="ownerTypeID" value="<?= $quote->ownerTypeID ?>">
			<input type="hidden" name="activityID" value="<?= $quote->activityID ?>">

			<div class="section">
				<button type="submit" class="btn btn-primary btn-sm">Save</button>

	<?php
		if($quote->id != '') {
	?>
				<button type="button" class="btn btn-default btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&aid=<?= $_GET['aid'] ?>'">Cancel</button>
				<div class="pull-right">
					<button type="button" id="deleteQuoteBtn" class="btn btn-danger btn-sm" onClick="deleteQuote();">Delete</button>
				</div>
	<?php
		}
	?>
			</div>

			<div class="panel panel-success">
				<div class="panel-heading">Details</div>
				<div class="panel-body">
					<div class="section">
						<div class="row">
							<div class="col-xs-6">
								<div>
									<div class="pull-left">
										<label>Contact</label>
									</div>
									<div class="pull-right">
										<a class="btn btn-primary btn-xs" onClick="editContact()">Edit</a>
									</div>
									<div class="clearfix"></div>
								</div>
								<select name="ownerStaffID" class="form-control input-sm">
									<option value="">No Contact</option>
							<?php
								if(is_array($quote->owner->staff)) {
									foreach($quote->owner->staff as $staff) {
							?>
											<option value="<?= $staff->id ?>" <?= ($staff->id == $quote->ownerStaffID ? 'SELECTED' : '') ?>><?= $staff->name ?> [<?= $staff->email ?>]</option>
							<?php
									}
								}
							?>
								</select>
							</div>
							<div class="col-xs-3">
								<label>Effective Start</label>
								<input type="text" class="form-control input-sm" style="margin-bottom:0px;padding-bottom:0px"  datepicker id="start" name="start" value="<?= $quote->start ?>">
							</div>
							<div class="col-xs-3">
								<label>Effective End</label>
								<input type="text" class="form-control input-sm" style="margin-bottom:0px" datepicker id="end" name="end" value="<?= $quote->end ?>">
							</div>
						</div>
					</div>

					<div class="section">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="comment">Packages</label>
									<select class="form-control input-sm" name="worksheetTypeID" onChange="changeType($(this).val());">
											<option value=""></option>
									<?php foreach ($defaults as $groupName => $types) { ?>
										<optgroup label="<?= $groupName ?>">
										<?php foreach ($types as $id => $name) { ?>
											<option value="<?= $id ?>" <?= ($type->id == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
										<?php } ?>
										</optgroup>
									<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label for="comment">Currency</label>
									<select class="form-control input-sm" name="currencyID">
											<option value=""></option>
									<?php foreach ($currencies as $id => $name) { ?>
											<option value="<?= $id ?>" <?= ($quote->currencyID == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
									<?php } ?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-3">
								<div class="form-group">
									<label for="comment">Deposits</label>
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="text" class="form-control input-sm" numeric name="depositCollected" value="<?= $quote->depositCollected ?>">
									</div>
								</div>
							</div>
							<div class="col-xs-3">
								<label for="comment">Quote Total</label>
								<input type="text" class="form-control input-sm" value="<?= ($quote instanceof Quote ? $quote->getQuoteTotal() : 0) ?>" disabled>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label for="comment"># of Events</label>
									<input type="text" class="form-control input-sm" numeric name="numEvents" value="<?= $quote->numEvents ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-success">
				<div class="panel-heading">
					<div class="pull-left">Items</div>
					<div class="pull-right">
						<div class="btn-group dropdown">
							<button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Add Item <span class="caret"></span>
							</button>
							<ul id="availableItems" class="dropdown-menu pull-right">
							</ul>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<div class="section">
						<table id="itemsTable" class="table table-condensed">
							<thead>
								<tr>
									<th style="width:5%"></th>
									<th style="width:55%">Item</th>
									<th style="width:18%">Quantity</th>
									<th style="width:18%">Unit Price</th>
									<th style="width:4%"></th>
								</tr>
							</thead>
							<tbody class="itemsBody">
							</tbody>
						</table>
						<div class="form-group">
							<label>Notes</label>
							<textarea class="form-control" id="notes" name="notes"><?= $quote->notes ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="section">
				<button type="submit" class="btn btn-primary btn-sm">Save</button>

	<?php
		if($quote->id != '') {
	?>
				<button type="button" class="btn btn-default btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&aid=<?= $_GET['aid'] ?>'">Cancel</button>
				<div class="pull-right">
					<button type="button" id="deleteQuoteBtn" class="btn btn-danger btn-sm" onClick="deleteQuote();">Delete</button>
				</div>
	<?php
		}
	?>
			</div>
		</form>
	</div>
</div>


<?php
	if($quote->id != '') {
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success">
			<div class="panel-heading">Actions</div>
			<div class="panel-body">
				<button type="button" class="btn btn-success btn-xs" onClick="window.open('export/quote/?id=<?= $quote->id ?>&ekey=<?= encrypt($quote->id,'quote') ?>')">View Sales Order</button>
				<hr>
				<div class="quoteAction">
					<button type="button" class="btn btn-success btn-xs emailQuoteBtn" onClick="checkQuote(false)">Email Sales Order</button>
					<button type="button" class="btn btn-primary btn-xs emailQuoteBtn" onClick="emailQuote(true)">Email Self</button>
				<?php
					if($quote->quoteSent != '') {
				?>
					<div class="quoteActionDetails">
						Quote Last Emailed <?= date("M j, Y g:i A",strtotime($quote->quoteSent)) ?>.
					</div>
				<?php
					}
				?>
				</div>


				<div class="quoteAction">
			<?php
				if($quote->quoteSigned != '') {
			?>
					<button type="button" id="unsignQuoteBtn" class="btn btn-success btn-xs" onClick="unsignQuote()">Remove Signature</button>
					<div class="quoteActionDetails">
						Quote Signed <?= date("M j, Y g:i A",strtotime($quote->quoteSigned)) ?>.
					</div>
			<?php
				}
				else {
			?>
					<button type="button" id="signQuoteBtn" class="btn btn-success btn-xs" onClick="signQuote()">Mark As Signed</button>
			<?php
				}
			?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	}
?>
<div class="modal fade" id="loading_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Please Wait</h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="unitPrice_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4>Invitations Calculator</h4>
       			<select class="form-control input-sm" id="invitationUnitPrice" onChange="calculateUnitPrice()" >
					<option value="" DISABLED SELECTED>-- Choose Invitations -- </option>
					<?php
					foreach(AgreementController::getInvitationUnitPrices() as $key => $info) {
					?>
						<option value="<?= $key ?>"><?= $info['desc'] ?></option>
					<?php
					}
					?>
				</select>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
        </div>
    </div>
</div>