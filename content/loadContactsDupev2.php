<style>
	.topTbl {width:100%}
	.topTbl td {vertical-align:top;padding:0px;vertical-align:middle}
	.dupeTbl {border-collapse:collapse;width:100%;font-size:9pt;}
	.dupeTbl td {border:1px solid #cccccc;padding:0px 5px;vertical-align:middle}
</style>

<script>


	<?php
	foreach($dupeArray['choices'] as $dupeContactID => $contactArray)
	{
	?>
		var section<?= $dupeContactID ?> = new Array(<?= implode(',',$contactArray)?>);
	<?php
	}
	?>

	function changeChoice(formID,val)
	{
		for(var i = 0; i < window['section' + formID].length; i++ )
		{
			document.getElementById('section_' + formID + 'dupe_' + window['section' + formID][i]).value = val;
		}
		document.getElementById('ignore_' + formID).checked = false;
	}

	function ignoreChoice(formID)
	{
		for(var i = 0; i < window['section' + formID].length; i++ )
		{
			document.getElementById('section_' + formID + 'dupe_' + window['section' + formID][i]).value = '';
		}

		document.getElementById('ignore_' + formID).checked = true;
	}
</script>
<div id="statusDiv" style="font-weight:bold;color:red"><?= $status ?></div>

<table cellspacing="0" cellpadding="0" class="topTbl">
	<tr>
		<td style="padding-right:5px;font-weight:bold" nowrap>Dupe Check Type:</td>
		<td>
			<form method="POST">
				<select name="dupeType" onChange="this.form.submit()">
	<?php
		foreach($dupeTypes as $dupeType => $detail)
		{
	?>
					<option value="<?= $dupeType ?>" <?= ($_SESSION['loadContacts']['dupe']['type'] == $dupeType ? 'selected' : '') ?>><?= $detail['description'] ?></option>
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
<form id="dupeForm" method="POST">
	<input type="hidden" name="action" value="merge">
<?php
	$displayCount = 0;
	$show = false;

	if($_SESSION['loadContacts']['dupe']['type'] == 'firstInitialAddressLastname')
	{
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$postalCodes = array();
			foreach($contactIDArray as $dupeKey => $contactID) $postalCodes[] = $dupeArray['rows'][$contactID]['postalCode'];

			if(count(array_unique($postalCodes)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
	}

	if($_SESSION['loadContacts']['dupe']['type'] == 'firstInitialMainPhone')
	{
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$lastnames = array();
			foreach($contactIDArray as $dupeKey => $contactID) $lastnames[] = $dupeArray['rows'][$contactID]['lastname'];

			if(count(array_unique($lastnames)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$firstnames = array();
			foreach($contactIDArray as $dupeKey => $contactID) $firstnames[] = $dupeArray['rows'][$contactID]['firstname'];

			if(count(array_unique($firstnames)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
	}

	if($_SESSION['loadContacts']['dupe']['type'] == 'firstInitialPostalAddress')
	{
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$lastnames = array();
			foreach($contactIDArray as $dupeKey => $contactID) $lastnames[] = $dupeArray['rows'][$contactID]['lastname'];

			if(count(array_unique($lastnames)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$firstnames = array();
			foreach($contactIDArray as $dupeKey => $contactID) $firstnames[] = $dupeArray['rows'][$contactID]['firstname'];

			if(count(array_unique($firstnames)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$mainPhone = array();
			foreach($contactIDArray as $dupeKey => $contactID) $mainPhone[] = $dupeArray['rows'][$contactID]['mainPhone'];

			if(count(array_unique($mainPhone)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
	}

	if($_SESSION['loadContacts']['dupe']['type'] == 'firstInitialLastnameMainPhone')
	{
		foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
		{
			$addPostal = array();
			foreach($contactIDArray as $dupeKey => $contactID) $addPostal[] = $dupeArray['rows'][$contactID]['address1'].$dupeArray['rows'][$contactID]['postalCode'];

			if(count(array_unique($addPostal)) === 1)
			{
				unset($dupeArray['choices'][$dupeContactID]);
			}
		}
	}


	foreach($dupeArray['choices'] as $dupeContactID => $contactIDArray)
	{
		//if($show == true) continue;
		$new = false;
		foreach($dupeArray['choices'][$dupeContactID] as $val)
		{
			if(isset($_SESSION['loadContacts']['loadedContactID'][$val])) $new = true;
		}
		if(!$new)
		{
			unset($dupeArray['choices'][$dupeContactID]);
			continue;
		}

		if(isset($_SESSION['loadContacts']['dupe'][$_SESSION['loadContacts']['dupe']['type']]['done'][$dupeContactID])) continue;


		if($displayCount % 10 == 0)
		{
	?>
		<a href="#" onClick="document.getElementById('dupeForm').submit();return false;">merge</a>
		<br><br>
	<?php
		}
		$displayCount++;
		$show = true;
?>
		<table cellspacing="0" cellpadding="0" class="dupeTbl">
	<?php
		foreach($contactIDArray as $dupeKey => $contactID)
		{
	?>
			<tr onMouseOver="this.style.cursor='pointer'">
				<td style="border:0px;width:50px">
					<select id="section_<?= $dupeContactID ?>dupe_<?= $contactID ?>" name="dupe_<?= $contactID ?>" style="width:50px">
						<option value=""></option>
		<?php
			foreach($dupeArray['choices'][$dupeContactID] as $choicesKey => $val)
			{
				//if($dupeKey < $choicesKey) continue;
		?>
						<option value="<?= $val ?>"><?= $choicesKey+1 ?></option>
		<?php
			}
		?>
					</select>
				</td>
		<?php
			if($dupeArray['maxUpdated'][$dupeContactID] == $dupeArray['rows'][$contactID]['lastUpdated'])
				$bgColor = 'style="background-color:#eeeeee"';
			else $bgColor = '';

			$colsCount = 0;
			foreach($dupeArray['rows'][$contactID] as $key => $val)
			{
				if(in_array($key,array('dupeContactID','lastUploaded'))) continue;
				if($key == 'contactID') $val = $dupeKey+1;
				if($key == 'lastUpdated') $val = date("Y-m-d",strtotime($val));
				//if($key == 'lastUploaded') $val = ($dupeKey == 0 ? '*' : '');
				$colsCount++;
		?>
				<td <?= $bgColor ?> onClick="changeChoice('<?= $dupeContactID ?>','<?= $dupeArray['rows'][$contactID]['contactID'] ?>')" nowrap><?= $val ?></td>
		<?php
			}
		?>
			</tr>
	<?php
		}
	?>
			<tr>
				<td style="border:0px;"><input type="checkbox" id="ignore_<?= $dupeContactID ?>" name="ignore_<?= $dupeContactID ?>"> ignore</td>
				<td style="text-align:center;font-weight:bold;font-size:8pt;height:20px" colspan="<?= $colsCount ?>" onMouseOver="this.style.cursor='pointer'" onClick="ignoreChoice('<?= $dupeContactID ?>')">ignore</td>
			</tr>
		</table>

	<br><br>
<?php
	}

	if(!$show)
	{
		if(count($dupeArray['choices']) != 0)
			echo count($dupeArray['choices']) . ' dupes ignored';
		else
			echo 'No dupes found';
	}
	else
	{
	?>
		<a href="#" onClick="document.getElementById('dupeForm').submit();return false;">merge</a>
		<br><br>
	<?php
	}
?>
</form>
<br><br><br>
<a href="?s1=loadContacts&s2=Householdv2">Continue</a>
<!--
<form method="POST">
<table cellspacing="0" cellpadding="0" class="dupeTbl">
<?php
	$currentID = null;
	while($dupeRow = mysqli_fetch_assoc($dupeResults))
	{
		if(isset($_SESSION['loadContacts']['dupe']['done'][$dupeRow['dupeContactID']])) continue;
		if($currentID != null && $dupeRow['dupeContactID'] != $currentID)
		{
	?>
				<input type="submit" name="merge" value="merge">
				<input type="submit" name="ignore" value="ignore">
			</table>
			</form>
			<br><br>
			<form method="POST">
			<table cellspacing="0" cellpadding="0" class="dupeTbl">
	<?php
			$j = 0;
		}
		$j++;
		$currentID = $dupeRow['dupeContactID'];
?>
	<tr>
		<td style="border:0px;width:10%">
			<input type="hidden" name="dupeContactID" value="<?= $dupeRow['dupeContactID'] ?>">
			<select name="dupe_<?= $dupeRow['contactID'] ?>" style="width:100%">
	<?php
		$i = 0;
		foreach($dupeArray['choices'][$dupeRow['dupeContactID']] as $val)
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
		foreach($dupeRow as $key => $val)
		{
			$i++;
			if($key == 'dupeContactID') continue;
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