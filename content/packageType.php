<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="scripts/jquery.textarea_autosize.js"></script>
<script src="scripts/jquery.numeric.min.js"></script>
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
		width:150px;
		margin-bottom:5px;
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
	.typeName {
		font-size:1.8em;
		line-height:1em;
		margin:10px 0px 20px 0px;
	}
</style>
<script>
	$(function() {
		loadItems();

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


	});

	function loadItems() {
        $('#loading_modal').modal('show');
		$.ajax({data:	{loadItems: ''},
				type:	'POST',
				dataType: 'json',
				success: function(results) {
					$.each(results, function(i, data) {
						addItem(data.key,'',data.worksheetItemTypeID,data.description,data.default_description,data.quantity,data.unitPrice,data.isDefault);
					})
        			$('#loading_modal').modal('hide');
				}
		});
	}


	function addAllDefaultItems() {
		$.ajax({data:	{getAllDefaultItems: ''},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					$.each(data,function(k,d) {
						addItem(d.key,'',d.worksheetItemTypeID,d.description,d.default_description,'','','');
					});
				}
		});
	}

	function addDefaultItem(worksheetTypeItemID) {

		$.ajax({data:	{getDefaultItem: worksheetTypeItemID},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					addItem(data.key,'',data.worksheetItemTypeID,data.description,data.default_description,'','','');
				}
		});
	}

	function removeAllItems() {
		$('[worksheetItemTypeID]').remove();
	}

	function removeItem(row) {
		row.remove();
	}

	function addItem(key,worksheetTypeDefaultItemID,worksheetItemTypeID,description,default_description,quantity,unitPrice,isDefault) {
		rand = Math.floor((Math.random() * 1000) + 1);
		key = key + '_' + rand;

		$("#itemsTable").find('tbody')
		.append($('<tr>')
			.attr('worksheetTypeDefaultItemID', worksheetTypeDefaultItemID)
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
					.attr('name', key + '_description')
					.attr('placeholder', 'Default: ' + default_description)
					.html(description)
				)
			)
			.append($('<td>')
				.append($('<input>')
					.attr('type', 'text')
					.attr('numeric','')
					.attr('class', 'form-control input-sm')
					.attr('name', key + '_quantity')
					.attr('placeholder', 'TBD')
					.val(quantity)
				)
			)
			.append($('<td>')
				.append($('<input>')
					.attr('type', 'text')
					.attr('numeric','')
					.attr('class', 'form-control input-sm')
					.attr('name', key + '_unitPrice')
					.attr('placeholder', 'Included')
					.val(unitPrice)
				)
			)
			.append($('<td>')
				.append($('<select>')
					.attr('class', 'form-control input-sm')
					.attr('name', key + '_isDefault')
					.append($('<option>')
						.attr('value', 0)
						.html('no')
					)
					.append($('<option>')
						.attr('value', 1)
						.html('yes')
					)
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

		$('[name='+key+'_isDefault]').val(isDefault);

		$('[numeric]').numeric();
		$('.dtextarea').textareaAutoSize();
	}
</script>
<div class="row">
	<div class="col-md-12">
<?php
	if($worksheetType->status) {
?>
		<form method="POST">
			<input type="hidden" name="worksheetTypeID" value="<?= $worksheetType->id ?>">
			<div>
				<div class="pull-left">
					<div class="typeName"><?= $worksheetType->name ?></div>
				</div>
				<div class="pull-right">
					<div class="form-inline">
						<label>Language</label>
						<select class="form-control input-sm" name="languageID" onChange="location.href='?s1=package&s2=Type&id=<?= $_GET['id'] ?>&lid=' + $(this).val()">
					<?php
						foreach($activeLanguages as $lang) {
					?>
						foreach($activeLanguages as $lang) {
							<option value="<?= $lang->id ?>" <?= ($_GET['lid'] == $lang->id ? 'SELECTED' : '') ?>><?= ($lang->defaultLanguage ? '** ' : '') ?><?= $lang->name ?></option>
					<?php
						}
					?>
						</select>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div>
				<div class="pull-left">
					<button type="submit" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>'">Cancel</button>
				</div>
				<div class="pull-right">

					<select class="form-control" name="currencyID">
						<option value="">Select Currency</option>
				<?php
					foreach($currencies as $id => $name) {
				?>
						<option value="<?= $id ?>" <?= ($worksheetType->currencyID == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
				<?php
					}
				?>
					</select>
				</div>
				<div class="pull-right" style="margin-right:5px">

					<select class="form-control" name="countryID">
						<option value="">Select Country</option>
				<?php
					foreach($countries as $id => $name) {
				?>
						<option value="<?= $id ?>" <?= ($worksheetType->countryID == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
				<?php
					}
				?>
					</select>
				</div>
				<div class="pull-right" style="margin-right:5px">

					<select class="form-control" name="nationID">
						<option value="">Select Nation</option>
				<?php
					foreach($nations as $id => $name) {
				?>
						<option value="<?= $id ?>" <?= ($worksheetType->nationID == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
				<?php
					}
				?>
					</select>
				</div>
			</div>
			<div class="section">
				<table id="itemsTable" class="table table-condensed">
					<thead>
						<tr>
							<th style="width:5%"></th>
							<th style="width:37%">Item</th>
							<th style="width:18%">Quantity</th>
							<th style="width:18%">Unit Price</th>
							<th style="width:18%">Default?</th>
							<th style="width:4%"></th>
						</tr>
					</thead>
					<tbody class="itemsBody">
					</tbody>
				</table>
			</div>
			<div class="section">
				<div class="btn-group">
					<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Add Item <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">

	<?php
		if(is_array($worksheetItemTypes)) {
			foreach($worksheetItemTypes as $item) {
				$item->description = $item->getDescription(ENGLISH_US,0);
	?>
				<li><a onClick="addDefaultItem(<?= $item->id ?>)"><?= strtok($item->description,"\n") ?></a></li>

	<?php
			}
		}
	?>
					</ul>

				</div>

				<button type="button" class="btn btn-primary btn-sm" onClick="removeAllItems();addAllDefaultItems();">Add All Items</button>
				<button type="button" class="btn btn-danger btn-sm" onClick="removeAllItems()">Remove All Items</button>

				<div class="pull-right">
					<button type="button" class="btn btn-danger btn-sm" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&id=<?= $worksheetType->id ?>&disable'">Disable Package</button>
				</div>
			</div>
			<div class="section">
				<label>Default Notes</label>
				<textarea name="notes" class="form-control" rows="5"><?= $defaultNotes->notes ?></textarea>
			</div>
		</form>
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
<?php
	} else {
?>
		<div>
			<div class="pull-left typeName"><?= $worksheetType->name ?></div>
			<div class="pull-right">
				<button type="button" class="btn btn-success" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&id=<?= $worksheetType->id ?>&enable'">Enable Package</button>
				<button type="button" class="btn btn-default" onClick="location.href='?s1=<?= $_GET['s1'] ?>'">Cancel</button>
			</div>
		</div>
<?php
	}
?>
	</div>
</div>
