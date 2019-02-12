
<style>
	#loadTable td {
		padding-bottom:5px;
	}
	#loadTable td:first-of-type {
		padding-top:5px;
	}
</style>
<script>
	function saveFilePath(val)
	{
		document.loadExcelForm.excelPath.value = val;
	}

	function skipUpload()
	{
		selectedDealerID = document.getElementById('loadExcelForm').dealerID.value;
		document.location.href="?s1=loadContacts&s2=Upload&skip=&dealerID=" + selectedDealerID;
	}
</script>

<div id="ar-page-title">Load Contacts</div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="row">
	<div class="col-md-7">
		<font style="font-weight:bold;color:red"><?= $_SESSION['loadContactsError'] ?></font>
		<?php unset($_SESSION['loadContactsError']); ?>
		<form id="loadExcelForm" name="loadExcelForm" enctype="multipart/form-data" method="POST">
			<input type="hidden" name="excelPath">
			<table id="loadTable">
				<tbody>
					<tr>
						<td style="width:120px">Dealer</td>
						<td>
							<select name="dealerID" class="form-control input-sm">
								<option value=""></option>
				<?php
					while($dealerRow = mysqli_fetch_assoc($dealersResults))
					{
				?>
								<option value="<?= $dealerRow['dealerID'] ?>"><?= $dealerRow['dealerName'] ?></option>
				<?php
					}
				?>
							</select>
						</td>
					</tr>
					<tr>
						<td>File</td>
						<td><input name="excel" type="file" onChange="saveFilePath(this.value)"></td>
					</tr>
					<tr>
						<td>List Name</td>
						<td><input name="list" class="form-control input-sm" type="text"><!-- &nbsp;&nbsp;&nbsp;<input type="checkbox" name="isUpdate"> Use as Updated</td> -->
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="isUpdate"> Use as Updated
								</label>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<input type="submit" class="btn btn-primary" name="uploadExcel" value="Load" />
		</form>

		<br><br>
		<a href="misc/wraparound.php" target="_blank">Wrap Around Tool</a>
		<br><br>
		<a href="#" onClick="skipUpload();return false;">Skip Upload</a>
	</div>
</div>