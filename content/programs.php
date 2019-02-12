<style>
	.programsTbl th,.programsTbl td {padding-right:5px}
	.programsTbl th{font-size:10pt;text-align:left}
	.programsTbl textarea{font-family:arial}
</style>
	
<h3>Programs</h3>
<form method="POST">
<table cellspacing="0" cellpadding="0" class="programsTbl">
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th>Date</th>
					<td>
						<select name="dateMonth" onChange="this.form.submit()">
				<?php
					for($i = 1;$i<=12;$i++)
					{
				?>
							<option value="<?= $i ?>" <?= ($_SESSION['programs']['dateMonth'] == $i ? 'SELECTED' : '') ?>><?= date("M",strtotime("2014-".$i)) ?></option>
				<?php
					}
				?>
						</select>
					</td>
					<td>
						<select name="dateYear" onChange="this.form.submit()">
				<?php
					for($i = date("Y",strtotime("now + 1 year"));$i>=2014;$i--)
					{
				?>
							<option value="<?= $i ?>" <?= ($_SESSION['programs']['dateYear'] == $i ? 'SELECTED' : '') ?>><?= $i ?></option>
				<?php
					}
				?>
						</select>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th>Business Center</th>
					<td>
						<select name="businessCenter" onChange="this.form.submit()">
				<?php
					foreach($bcs as $bc => $info)
					{
				?>
							<option value="<?= $bc ?>" <?= ($_SESSION['programs']['businessCenter'] == $bc ? 'SELECTED' : '') ?>><?= $info['desc'] ?></option>
				<?php
					}
				?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

<br>

<form method="POST">
<table cellspacing="0" cellpadding="0" style="width:100%;background-color:#ccc;border:1px solid #333">
	<tr>
		<td style="padding:10px">
			<table cellspacing="0" cellpadding="0" class="programsTbl" style="width:100%">
				<tr>
					<th>Programs</th>
				</tr>
				<tr>
					<td><textarea name="details" style="width:100%;height:200px"><?= $_SESSION['programs']['details'] ?></textarea></td>
				</tr>
				<tr>
					<td><input type="submit" value="Save"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>