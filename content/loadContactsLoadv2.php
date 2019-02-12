
<script>
	function doCommand(com)
	{
		var param = {};
		param[com] = '';

		$.ajax({data:	param,
				type:	'GET',
				dataType: 'script'
		   	    });
	}


</script>
<?php
	$dealerInfo = displayDealerInfo($_SESSION['loadContacts']['dealerID']);
?>

<div>
	<h3>Dealer: <?= $dealerInfo['dealerID'] ?> - <?= $dealerInfo['dealerName'] ?> </h3>
</div>

<div id="initializeDiv">
	<input type="button" onClick="document.getElementById('initializeDiv').innerHTML = 'Initializing ... ';doCommand('initialize')" value="Start">
</div>

<div id="insertDiv"></div>
<div id="findDupesDiv"></div>
<div id="findHouseholdDupesDiv"></div>
<div id="completeDiv"></div>

<br><br><br>

<div id="errorDiv" style="color:red;font-weight:bold"></div>