

<script>
	function calculateEvents()
	{
		$.ajax({data:	{calculateEvents: ''},
				type:	'GET',
				dataType: 'script'
		   	    });		
	}
	function calculateSolds()
	{
		$.ajax({data:	{calculateSolds: ''},
				type:	'GET',
				dataType: 'script'
		   	    });		
	}
</script>	
<style>
	.masterTbl {background-color:#ccc;border-collapse:collapse}
	.masterTbl td {text-align:center;padding:10px 30px;border:1px solid black}
	.labelTbl td {padding:0px;text-align:left;border:0px;font-weight:bold}
</style>
<br>
<center>
	<font style="font-size:25pt;font-weight:bold">
		<i>The Master's Club</i>
	</font>
	<br><br><br>
	<table cellspacing="0" cellpadding="0" class="masterTbl" style="width:400px;">
		<tr>
			<td style="font-size:18pt;font-weight:bold">
				<?= $_SESSION['login']['name'] ?>
			</td>
		</tr>
		<tr>
			<td style="font-size:11pt;background-color:white">
				<b>Absolute Results Trainer
				<br>
				Since <?= date("F Y",strtotime($_SESSION['login']['dateCreated'])) ?></b>
			</td>
		</tr>
	</table>
	<br><br>
	<table cellspacing="0" cellpadding="0" class="masterTbl" style="width:600px;">
		<tr>
			<td>
				<font style="font-size:25pt;font-weight:bold">
					<div id="eventsDiv" style="display:inline;color:red"></div> EVENTS FACILITATED
				</font>
				<br><br>
				<table cellspacing="0" cellpadding="0" style="width:100%;background-color:white">
					<tr>
						<td style="height:40px;padding:0px">
							<div id="eventsBarDiv" style="color:red;font-size:15pt;float:left;background-color:white;height:100%;width:100%;line-height:40px"><i>loading...</i></div>
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" class="labelTbl" style="width:100%;">
					<tr>
						<td style="width:20%">0</td>
						<td style="width:20%;">100</td>
						<td style="width:20%;">200</td>
						<td style="width:20%;">300</td>
						<td style="width:20%;">400</td>
						<td style="width:0%;text-align:right">500</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br><br>
	<table cellspacing="0" cellpadding="0" class="masterTbl" style="width:600px;">
		<tr>
			<td>
				<font style="font-size:25pt;font-weight:bold">
					<div id="soldsDiv" style="display:inline;color:red"></div> VEHICLES SOLD
				</font>
				<br><br>
				<table cellspacing="0" cellpadding="0" style="width:100%;background-color:white">
					<tr>
						<td style="height:40px;padding:0px">
							<div id="soldsBarDiv" style="color:red;font-size:15pt;float:left;background-color:white;height:100%;width:100%;line-height:40px"><i>loading...</i></div>
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" class="labelTbl" style="width:100%;">
					<tr>
						<td style="width:20%">0</td>
						<td style="width:20%;">1000</td>
						<td style="width:20%;">2000</td>
						<td style="width:20%;">3000</td>
						<td style="width:20%;">4000</td>
						<td style="width:0%;text-align:right">5000</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<div id="averageDiv" style="font-size:18pt;font-weight:bold">&nbsp;</div>
	<br><br><br>
	<div style="padding-left:70px">
		<img src="images/logo.jpg">
	</div>
	<br><br>
</center>
<script>
	calculateEvents();
</script>