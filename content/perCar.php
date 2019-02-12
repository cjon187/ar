<style>
	.formTbl th {padding-right:10px;color:#333333;text-align:left;font-size:9pt;padding-bottom:5px;vertical-align:middle;width:120px}
	.formTbl td {color:#333333;padding-bottom:5px;vertical-align:middle;}
	.formTbl input,select {font-size:9pt;width:150px}
	.dateTbl td {padding-right:3px}
	.dateTbl input {width:50px}
</style>
<form method="POST">
	<input type="hidden" name="date" value="<?= $_SESSION['invoice']['date'] ?>">
	<input type="hidden" name="invoiceID" value="<?= $_SESSION['invoice']['invoiceID'] ?>">
	<input type="hidden" name="invoiceNum" value="<?= $_SESSION['invoice']['invoiceNum'] ?>">
	<input type="hidden" name="perCarPercent" value="<?= $_SESSION['invoice']['perCarPercent'] ?>">
<table cellspacing="0" cellpadding="0" style="width:500px">
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Staff:</th>
					<td>			
						<input type="hidden" name="staffID" value="<?= $_SESSION['perCar']['staffID'] ?>">			
						<?= $_SESSION['perCar']['invoiceOwner']['name'] ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>For:</th>
					<td><?= $_SESSION['perCar']['dealerInfo']['dealerName'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Contact :</th>
					<td>
						<select name="dealerStaffID">
							<option value=""></option>
	<?php while($staff = mysqli_fetch_assoc($staffResults)) { ?>
							<option value="<?= $staff['dealerStaffID'] ?>" <?= ($_SESSION['perCar']['dealerStaffID'] == $staff['dealerStaffID'] ? 'SELECTED' : '') ?>><?= $staff['name'] ?> (<?= $staff['email'] ?>)</option>
	<?php } ?>							
						</select>									
						<!--<b><?= $_SESSION['perCar']['worksheet']['contact'] ?></b> <?= ($_SESSION['perCar']['worksheet']['contactEmail'] == "" ? '' : '('.$_SESSION['perCar']['worksheet']['contactEmail'] . ')') ?>-->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Event Dates</th>
					<td><?= date("M j",strtotime($_SESSION['perCar']['eventStart'])) . (date("j",strtotime($_SESSION['perCar']['eventStart'])) ==  date("j",strtotime($_SESSION['perCar']['eventEnd'])) ? '':'-' . date("j",strtotime($_SESSION['perCar']['eventEnd']))); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Per Car Rate:</th>
					<td>$<?= $_SESSION['perCar']['perCarRate'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Cars Written:</th>
					<td><?= $_SESSION['perCar']['carsWritten'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Cars Delivered:</th>
					<td><input type="text" name="perCar" style="width:160px" value="<?= $_SESSION['perCar']['perCar'] ?>"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td style="width:70%;color:red;font-weight:bold"><?= $_SESSION['perCar']['error'] ?></td>
					<td style="text-align:right;"><input type="submit" value="Request Confirmation"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	unset($_SESSION['perCar']['error']);
?>