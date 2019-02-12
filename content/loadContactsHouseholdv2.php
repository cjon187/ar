<style>
	.topTbl {width:100%}
	.topTbl td {vertical-align:top;padding:0px;vertical-align:middle}
	.householdTbl {border-collapse:collapse;width:100%;font-size:9pt;}
	.householdTbl td {border:1px solid #cccccc;padding:0px 5px;vertical-align:middle}
</style>

<script>


	<?php
	foreach($dupeArray['choices'] as $householdContactID => $contactArray)
	{
	?>
		var section<?= $householdContactID ?> = new Array(<?= implode(',',$contactArray)?>);
	<?php
	}
	?>


	function changeChoice(formID,val)
	{
		for(var i = 0; i < window['section' + formID].length; i++ )
		{
			document.getElementById('section_' + formID + 'household_' + window['section' + formID][i]).value = val;
		}
		document.getElementById('ignore_' + formID).checked = false;
	}

	function ignoreChoice(formID)
	{
		for(var i = 0; i < window['section' + formID].length; i++ )
		{
			document.getElementById('section_' + formID + 'household_' + window['section' + formID][i]).value = '';
		}

		document.getElementById('ignore_' + formID).checked = true;
	}
</script>
<div id="statusDiv" style="font-weight:bold;color:red"><?= $status ?></div>

<table cellspacing="0" cellpadding="0" class="topTbl">
	<tr>
		<td style="padding-right:5px;font-weight:bold" nowrap>Household Check Type:</td>
		<td>
			<form method="POST">
				<select name="householdType" onChange="this.form.submit()">
	<?php
		foreach($householdTypes as $householdType => $detail)
		{
	?>
					<option value="<?= $householdType ?>" <?= ($_SESSION['loadContacts']['household']['type'] == $householdType ? 'selected' : '') ?>><?= $detail['description'] ?></option>
	<?php
		}
	?>

				</select>
			</form>
		</td>
		<td style="width:100%;text-align:right">
			<form method="POST" name="resetForm">
				<input type="hidden" name="reset" value="reset">
			</form>
			<a href="#" onClick="document.resetForm.submit()">Reset Ignored</a>
		</td>
	</tr>
</table>
<br>
<form id="houseHoldForm" method="POST">
	<input type="hidden" name="action" value="merge">
<?php
	$displayCount = 0;
	$show = false;
	foreach($dupeArray['choices'] as $householdContactID => $contactIDArray)
	{
		//if($show == true) continue;
		$new = false;
		foreach($dupeArray['choices'][$householdContactID] as $val)
		{
			if(isset($_SESSION['loadContacts']['loadedContactID'][$val])) $new = true;
		}
		if(!$new)
		{
			unset($dupeArray['choices'][$householdContactID]);
			continue;
		}

		if(isset($_SESSION['loadContacts']['household'][$_SESSION['loadContacts']['household']['type']]['done'][$householdContactID])) continue;

		if($displayCount % 10 == 0)
		{
	?>
		<a href="#" onClick="document.getElementById('houseHoldForm').submit();return false;">merge</a>
		<br><br>
	<?php
		}
		$displayCount++;
		$show = true;
?>
		<table cellspacing="0" cellpadding="0" class="householdTbl">
	<?php
		foreach($contactIDArray as $householdKey => $contactID)
		{
	?>
			<tr onMouseOver="this.style.cursor='pointer'">
				<td style="border:0px;width:50px">
					<select id="section_<?= $householdContactID ?>household_<?= $contactID ?>" name="household_<?= $contactID ?>" style="width:50px">
						<option value=""></option>
		<?php
			foreach($dupeArray['choices'][$householdContactID] as $choicesKey => $val)
			{
				//if($householdKey < $choicesKey) continue;
		?>
						<option value="<?= $val ?>"><?= $choicesKey+1 ?></option>
		<?php
			}
		?>
					</select>
				</td>
		<?php
			if($dupeArray['maxUpdated'][$householdContactID] == $dupeArray['rows'][$contactID]['lastUpdated'])
				$bgColor = 'style="background-color:#eeeeee"';
			else $bgColor = '';

			$colsCount = 0;
			foreach($dupeArray['rows'][$contactID] as $key => $val)
			{
				if($key == 'householdContactID') continue;
				if($key == 'contactID') $val = $householdKey+1;

				$colsCount++;
		?>
				<td <?= $bgColor ?> onClick="changeChoice('<?= $householdContactID ?>','<?= $dupeArray['rows'][$contactID]['contactID'] ?>')" nowrap><?= $val ?></td>
		<?php
			}
		?>
			</tr>
	<?php
		}
	?>
			<tr>
				<td style="border:0px;"><input type="checkbox" id="ignore_<?= $householdContactID ?>" name="ignore_<?= $householdContactID ?>"> ignore</td>
				<td style="text-align:center;font-weight:bold;font-size:8pt;height:20px" colspan="<?= $colsCount ?>" onMouseOver="this.style.cursor='pointer'" onClick="ignoreChoice('<?= $householdContactID ?>')">ignore</td>
			</tr>
		</table>
	<br><br>
<?php
	}

	if(!$show)
	{
		if(count($dupeArray['choices']) != 0)
			echo count($dupeArray['choices']) . ' households ignored';
		else
			echo 'No households found';
	}
	else
	{
	?>
		<a href="#" onClick="document.getElementById('houseHoldForm').submit();return false;">merge</a>
		<br><br>
	<?php
	}
?>
</form><br><br>
		<a href="#" onClick="document.getElementById('houseHoldForm').submit();return false;">merge</a>

<br><br><br>
<a href="?s1=loadContacts&s2=Dupev2">Back</a>
<a href="?s1=loadContacts&s2=Save">Continue</a>
<!--
<form method="POST">
<table cellspacing="0" cellpadding="0" class="householdTbl">
<?php
	$currentID = null;
	while($householdRow = mysqli_fetch_assoc($householdResults))
	{
		if(isset($_SESSION['loadContacts']['household']['done'][$householdRow['householdContactID']])) continue;
		if($currentID != null && $householdRow['householdContactID'] != $currentID)
		{
	?>
				<input type="submit" name="merge" value="merge">
				<input type="submit" name="ignore" value="ignore">
			</table>
			</form>
			<br><br>
			<form method="POST">
			<table cellspacing="0" cellpadding="0" class="householdTbl">
	<?php
			$j = 0;
		}
		$j++;
		$currentID = $householdRow['householdContactID'];
?>
	<tr>
		<td style="border:0px;width:10%">
			<input type="hidden" name="householdContactID" value="<?= $householdRow['householdContactID'] ?>">
			<select name="household_<?= $householdRow['contactID'] ?>" style="width:100%">
	<?php
		$i = 0;
		foreach($dupeArray['choices'][$householdRow['householdContactID']] as $val)
		{
			$i++;
	?>
				<option value="<?= $val ?>"><?= $i ?></option>
	<?php
		}
	?>
			</select>
		</td>
	<?php
		foreach($householdRow as $key => $val)
		{
			$i++;
			if($key == 'householdContactID') continue;
			if($key == 'contactID') $val = $j;
	?>
			<td nowrap><?= $val ?></td>
	<?php
		}
	?>
	</tr>
<?php
	}
?>
</table>
<?php
	if($currentID != null)
	{
?>
<input type="submit" name="merge" value="merge">
<input type="submit" name="ignore" value="ignore">
<?php
	}
?>
</form>
-->