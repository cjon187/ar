<style>
	.formTbl th {padding-right:10px;color:#333333;text-align:left;font-size:9pt;padding-bottom:5px;vertical-align:middle;}
	.formTbl td {color:#333333;padding-bottom:3px;vertical-align:middle;}
	.formTbl textarea {font-family:arial}
	.dateTbl td {padding-right:3px}
</style>

<script>	
	$(function(){
		seeTaskStatus();
	});
	
	function toggleTaskStatus()
	{
		$.ajax({data:	{toggleTaskStatus: ''},
				type:	'GET',
				dataType: 'script'
		   	    });	
		return false;			
	}
	function seeTaskStatus()
	{
		$.ajax({data:	{seeTaskStatus: ''},
				type:	'GET',
				dataType: 'script'
		   	    });	
		return false;			
	}
	function setAsPrinted()
	{
		$.ajax({data:	{setAsPrinted: ''},
				type:	'GET',
				dataType: 'script'
		   	    });	
		return false;		
	}
	
	function showHideDetail(pin)
	{
		if(document.getElementById(pin + 'Details').style.display == 'block')
			document.getElementById(pin + 'Details').style.display = 'none';
		else			
			document.getElementById(pin + 'Details').style.display = 'block';
	}
</script>
<form method="POST">
	<input type="hidden" name="taskID" value="<?= $_SESSION['pap']['taskID'] ?>">
	<input type="hidden" name="eventID" value="<?= $_SESSION['pap']['eventID'] ?>">
<table cellspacing="0" cellpadding="0" style="width:300px">
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<td colspan="2" style="font-size:15pt;font-weight:bold" nowrap><?= $dealer['dealerName'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:50px">Date:</th>
					<td style="width:70px"><?= displayEventDate($event,true) ?></td>
					<th style="width:70px"># Salesrep:</th>
					<td style="width:50px"><?= $dealer['numSalesrep'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:50px">Printed:</th>
					<td style="width:70px"><?= $invitationTask['printer'] ?></td>
					<th style="width:70px">French?:</th>
					<td style="width:50px"><?= ($dealer['isFrench'] == 'on' ? 'Y' : 'N') ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:50px">DA#:</th>
					<td style="width:70px"><?= displayWorksheetNum($worksheet) ?></td>
					<td style="width:120px" colspan="2"><b>AM: </b><?= $am['name'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:15px">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<td><input type="checkbox" name="invitation" <?= ($_SESSION['pap']['invitation'] == 'on' ? 'checked' : '')?>></td>
					<td>Invitation</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="postageReceipt" <?= ($_SESSION['pap']['postageReceipt'] == 'on' ? 'checked' : '')?>></td>
					<td>Postal Receipt</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="papApproval" <?= ($_SESSION['pap']['papApproval'] == 'on' ? 'checked' : '')?>></td>
					<td>PAP Approval</td>
				</tr>
			</table>
		</td>
		<td style="text-align:right;color:#777;padding-top:15px">
			<div>
				<center>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td style="width:100px;height:100px;color:black;border:1px solid #aaa;vertical-align:middle;text-align:center;" id="papStatus" onClick="toggleTaskStatus()" onmouseover="this.style.cursor='pointer'">
								<div id="papStatusDiv" style="font-size:14pt">
								</div>
							</td>
						</tr>
					</table>
					<i>Status</i>
				</center>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Attn To:<br><input type="text" name="attention" value="<?= $_SESSION['pap']['attention'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>PAP Tracking #<br><input type="text" name="papTracking" value="<?= $_SESSION['pap']['papTracking'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	if(count($trackingDetails) > 0)
	{
?>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Tracking Information
	<?php
		foreach($trackingDetails as $pin => $scans)
		{
	?>						
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding-right:15px;vertical-align:top" onClick="showHideDetail('<?= $pin ?>')" onMouseOver="this.style.cursor='pointer'"><b><?= $pin ?></b></td>
								<td>
			<?php
				if($scans == "")
				{
					echo 'No Tracking Info';
				}			
				
				$initial = true;
				//print_r($scans);
				foreach($scans as $scan)
				{
					$desc = '';
					if(in_array($scan['Description'],array('Shipment delivered to','Delivered to'))) $desc = $scan['ScanDetails']['DeliverySignature'];
					else $desc = ($scan['Depot']['Name'] == "" ? '' : 'at ' . $scan['Depot']['Name']);
			?>						
									<?= date("Y-m-d h:i a",strtotime($scan['ScanDate'] . ' ' . $scan['ScanTime'])); ?>
									<br>
									<?= $scan['Description'] ?> <?= $desc ?>
									<br><br>
			<?php
					if($initial)
					{
						echo '<div id="' . $pin . 'Details" style="display:none">';
						$initial = false;
					}
					
				}
				if(!$initial) echo '</div>';
			?>		
													
								</td>
							</tr>
						</table>
	<?php
		}
	?>						
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}
?>		
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Notes</td>
				</tr>
				<tr>
					<td>
						<textarea id="notes" name="notes" style="width:100%;height:100px"><?= $_SESSION['pap']['notes'] ?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td style="text-align:left;"><input style="width:100px;" type="submit" value="Update"></td>
					<td style="text-align:right;"><input style="width:100px;" type="button" value="Print" onClick="setAsPrinted();window.print();"></td>
				</tr>
				<tr>
					<td colspan="2" style="color:red;font-weight:bold;>"><?= $_SESSION['papError'] ?></td>
					<?php unset($_SESSION['papError']); ?>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>