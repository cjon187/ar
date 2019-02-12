<style>
	.title {
		font-size:1.8em;
		font-weight:bold;
		line-height:1em;
		margin-bottom:20px;
	}
	.groupName {
		font-size:1.3em;
		font-weight:bold;
		line-height:1em;
		margin:10px 0px;
	}

	.sectionTableRow {
		cursor:pointer;
	}
	.sectionTableRow:hover {
		background-color:#eee;
	}
	.sectionTableRow.inactive {
		color:#ccc;
	}
	#addWorksheetCategory {
		padding-bottom:10px;
		font-weight:bold;
		font-size:1em;
	}
</style>
<script>
	$(function() {
		$('[worksheetTypeID]').click(function() {
			location.href="?s1=<?= $_GET['s1'] ?>&s2=Type&id=" + $(this).attr('worksheetTypeID');
		})
	})
	function openWorksheetModal(gid) {
		$('#categoryName').html($('[groupID=' + gid + ']').html());
		$('#addWorksheetTypeGroupID').val(gid);
		$('#addWorksheetModal').modal('show');
	}
	function addWorksheetType() {
		if($('#worksheetTypeName').val() == '') {
			ARAlertError('Please give this agreement a name.');
			return false;
		} else {
			return true;
		}
	}
</script>
<?php
	if($error) {
?>
	<div class="alert alert-danger"><?= $error ?></div>
<?php
	}
?>
<div id="ar-page-title">Packages</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="row" style="margin-bottom: 10px;">
	<div class="col-md-6">
	</div>
	<div class="col-md-6">
		<div class="pull-right">
			<form method="POST">
				<select name="seeActive" class="form-control input-sm" onChange="this.form.submit()">
					<option value="1" <?= ($_SESSION['worksheetTypeGroups']['seeActive'] == 1 ? 'SELECTED' : '') ?>>Active Only</option>
					<option value="0" <?= ($_SESSION['worksheetTypeGroups']['seeActive'] == 0 ? 'SELECTED' : '') ?>>In-Active Only</option>
					<option value="2" <?= ($_SESSION['worksheetTypeGroups']['seeActive'] == 2 ? 'SELECTED' : '') ?>>Both</option>
				</select>
			</form>
		</div>
	</div>
</div>
<?php
	foreach($worksheetTypeGroups as $group) {

		$t = new WorksheetType();
		$t->where('worksheetTypeGroupID',$group->id);
		if($_SESSION['worksheetTypeGroups']['seeActive'] == 1) {
			$t->where('status',1);
		} else if($_SESSION['worksheetTypeGroups']['seeActive'] == 0) {
			$t->where('status',0);
		}
		$t->orderBy('-`order`');
		$types = $t->get();
?>
		<div class="group">
			<div>
				<div class="pull-left">
					<div class="groupName" groupID=<?= $group->id ?>><?= $group->name ?></div>
				</div>
				<div class="pull-right">
					<button class="btn btn-xs btn-primary" onClick="openWorksheetModal(<?= $group->id ?>)">Add Package</button>
				</div>
				<div class="clearfix"></div>
			</div>

			<table class="table table-condensed sectionTable">
	<?php
		if(is_array($types)) {
			foreach($types as $type) {
	?>
			<tr class="sectionTableRow <?= ($type->status == 0 ? 'inactive' : '') ?>" worksheetTypeID=<?= $type->id ?>>
				<td><?= $type->name ?></td>
			</tr>
	<?php
			}
		} else {
	?>
			<tr class="sectionTableRow inactive">
				<td>No Packages Found</td>
			</tr>
	<?php
		}
	?>
			</table>
		</div>
<?php
	}
?>
<div class="modal fade" tabindex="-1" role="dialog" id="addWorksheetModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">New Agreement Default</h4>
			</div>
			<div class="modal-body">
				<div id="addWorksheetCategory">Category: <span id="categoryName"></span></div>
				<form method="POST" id="addWorksheetForm" onSubmit="return addWorksheetType()">
					<input type="hidden" id="addWorksheetTypeGroupID" name="addWorksheetTypeGroupID">
					<div>Agreement Type Name:</div>
					<input type="text" class="form-control input-sm" id="worksheetTypeName" name="worksheetTypeName">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onClick="$('#addWorksheetForm').submit()" >Create</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->