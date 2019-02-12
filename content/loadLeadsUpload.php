<style>
	.uploadTbl td {padding-right:10px;vertical-align:middle}
</style>

<script>
	function saveFilePath(val)
	{
		document.loadExcelForm.excelPath.value = val;
	}
	
	function skipUpload()
	{
		selectedDealerID = document.getElementById('loadExcelForm').dealerID.value;
		document.location.href="?s1=loadLeads&s2=Upload&skip=&dealerID=" + selectedDealerID;
	}
</script>



<font style="font-weight:bold;color:red"><?= $_SESSION['loadLeadsError'] ?></font>
<?php unset($_SESSION['loadLeadsError']); ?>
<form id="loadExcelForm" name="loadExcelForm" enctype="multipart/form-data" method="POST">
	<input type="hidden" name="excelPath">
<table cellspacing="0" cellpadding="0" class="uploadTbl">
	<tr>
		<td>Dealer:</td>
		<td>
			<select name="dealerID">
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
		<td>Excel Upload:</td>
		<td><input name="excel" type="file" onChange="saveFilePath(this.value)"></td>
	</tr>
	<tr>
		<td>List Source</td>
		<td><input name="list" type="text"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="uploadExcel" value="Load" /></td>
	</tr>
</table>
</form>