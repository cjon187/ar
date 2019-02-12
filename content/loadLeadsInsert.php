<style>
	.contentHeaderTbl {border-collapse:collapse}
	.contentHeaderTbl td {padding:2px;vertical-align:middle;border:1px solid #cccccc}
	.contentHeaderTbl th {font-size:10pt;text-align:left;padding:2px;vertical-align:middle;border:1px solid #cccccc}
</style>

<script>
	function saveFilePath(val)
	{
		document.loadExcelForm.excelPath.value = val;
	}
</script>

<font style="font-weight:bold;color:red"><?= $insertStatus ?></font>
<table cellspacing="0" cellpadding="0" class="insertDetailTbl">
	<tr>
		<td>Dealer:</td>
		<td style="font-weight:bold"><?= $_SESSION['loadLeads']['dealerName'] ?></td>
	</tr>
	<tr>
		<td>Excel:</td>
		<td style="font-weight:bold"><?= stripslashes($_SESSION['loadLeads']['excelPath']) ?></td>
	</tr>
</table>
<br><br>
<form name="loadLeadsForm" enctype="multipart/form-data" method="POST">
<table cellspacing="0" cellpadding="0" class="contentHeaderTbl">
	<tr>
		<th>Column Type</th>
		<th>Excel Header</th>
	</tr>					
<?php
	$i = 0;
	foreach($_SESSION['loadLeads']['contentHeader'] as $columnContent)
	{
?>
	<tr>
		<td>
			<select name="select<?= $i ?>">
				<?php printColumnOptions($_POST['select' . $i]) ?>
			</select>
		</td>
		<td><?= $columnContent ?></td>
	</tr>
<?php
		$i++;
	}
?>
</table>
<br>
<input type="submit" name="insertExcel" value="Load" />

</form>
