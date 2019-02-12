<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<style>
	.formTbl th {padding-right:10px;color:#333333;text-align:left;font-size:9pt;padding-bottom:5px;vertical-align:middle;}
	.formTbl td {color:#333333;padding-bottom:5px;vertical-align:middle;}
	.formTbl textarea {font-family:arial}
	.dateTbl td {padding-right:3px}
	
	.ui-datepicker {font-size: 12px}
</style>
<script>
	$(document).ready(function()
	{		
		$("[dateSelector]").datepicker({
			changeMonth: true,
      		changeYear: true,
      		dateFormat: "yy-mm-dd"
		});
	});
	
	
	function setToday(formID)
	{
		$('#'+formID).val('<?= date("Y-m-d") ?>');
	}
	function showHideDetail(pin)
	{
		if(document.getElementById(pin + 'Details').style.display == 'block')
			document.getElementById(pin + 'Details').style.display = 'none';
		else			
			document.getElementById(pin + 'Details').style.display = 'block';
	}
	
	function validateTrackingNum(obj)
	{
		//obj.value = obj.value.replace(/[^fFwWpP0123456789/]/g,'');
	}
</script>	
<form method="POST">
	<input type="hidden" name="eventID" value="<?= $_SESSION['tracking']['eventID'] ?>">
	<input type="hidden" name="dealerID" value="<?= $_SESSION['tracking']['dealerID'] ?>">
<table cellspacing="0" cellpadding="0" style="width:300px">
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Dealership:</th>
					<td><?= $dealer['dealerName'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th>Date:</th>
					<td><?= displayEventDate($event,true) ?></td>
					<th style="padding-left:30px">RA Name:</th>
					<td><?= $raContact->name ?></td>
				</tr>
			</table>
		</td>
	</tr>

<?php if($hasInvites) { ?>		
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invitations Attention To:<br><input type="text" name="invitesAttention" value="<?= $iTask['attention'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invitations Sent to Gateway? 
						<select name="gateway">
							<option value="" <?= ($iTask['gateway'] == "" ? 'selected' : '') ?>></option>		
							<option value="yes" <?= ($iTask['gateway'] == "yes" ? 'selected' : '') ?>>Yes</option>		
							<option value="no" <?= ($iTask['gateway'] == "no" ? 'selected' : '') ?>>No</option>		
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invitations Tracking #<br><input type="text" name="invitesTracking" value="<?= $iTask['tracking'] ?>" onKeyUp="validateTrackingNum(this)" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	if(count($invitesTrackingDetails) > 0)
	{
?>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invitations Tracking Information
					<?php
					foreach($invitesTrackingDetails as $pin => $scans){
					?>						
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding-right:15px;vertical-align:top" onClick="showHideDetail('<?= $pin ?>')" onMouseOver="this.style.cursor='pointer'"><b><?= $pin ?></b></td>
								<td>
							<?php
							if($scans == ""){
								echo 'No Tracking Info';
							}			
							
							$initial = true;
							if($scans) {
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
								}
								if(!$initial) echo '</div>';
								?>							
								</td>
							</tr>
						</table>
					<?php } ?>						
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}
?>		
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>						
						<table cellspacing="0" cellpadding="0" class="dateTbl">
							<tr>
								<td>Sent Date</td>
								<td>
									<input type="text" dateSelector id="invitesPrinted" name="invitesPrinted" style="width:120px" value="<?= $iTask['printed'] ?>">
								</td>
								<td><input type="button" value="Set" onClick="setToday('invitesPrinted')"></td>
							</tr>
						</table>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php } ?>
<?php if($hasConquest) { ?>	
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Conquest Samples Tracking #<br><input type="text" name="conquestTracking"  onKeyUp="validateTrackingNum(this)" value="<?= $cTask['samplesTracking'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	if(count($conquestTrackingDetails) > 0)
	{
?>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Conquest Samples Tracking Information
					<?php
					foreach($conquestTrackingDetails as $pin => $scans) {
					?>						
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding-right:15px;vertical-align:top" onClick="showHideDetail('<?= $pin ?>')" onMouseOver="this.style.cursor='pointer'"><b><?= $pin ?></b></td>
								<td>
								<?php
								if($scans == ""){
									echo 'No Tracking Info';
								}			
								
								$initial = true;
								if($scans) {
									foreach($scans as $scan){
										$desc = '';
										if(in_array($scan['Description'],array('Shipment delivered to','Delivered to'))) $desc = $scan['ScanDetails']['DeliverySignature'];
										else $desc = ($scan['Depot']['Name'] == "" ? '' : 'at ' . $scan['Depot']['Name']);
										?>						

										<?= date("Y-m-d h:i a",strtotime($scan['ScanDate'] . ' ' . $scan['ScanTime'])); ?>
										<br>
										<?= $scan['Description'] ?> <?= $desc ?>
										<br><br>

										<?php
										if($initial){
											echo '<div id="' . $pin . 'Details" style="display:none">';
											$initial = false;
										}
											
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
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>						
						<table cellspacing="0" cellpadding="0" class="dateTbl">
							<tr>
								<td>Sent Date</td>
								<td>
									<input type="text" dateSelector id="conquestPrinted" name="conquestPrinted" style="width:120px" value="<?= $cTask['samplesPrinted'] ?>">
								</td>
								<td><input type="button" value="Set" onClick="setToday('conquestPrinted')"></td>
							</tr>
						</table>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php } ?>
<?php if($hasInvites) { ?>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invites Samples Tracking #<br><input type="text" name="invitesSampleTracking"  onKeyUp="validateTrackingNum(this)" value="<?= $iTask['samplesTracking'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	if(count($invitesSampleTrackingDetails) > 0)
	{
?>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Invites Samples Tracking Information
					<?php
					foreach($invitesSampleTrackingDetails as $pin => $scans){
					?>						
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="padding-right:15px;vertical-align:top" onClick="showHideDetail('<?= $pin ?>')" onMouseOver="this.style.cursor='pointer'"><b><?= $pin ?></b></td>
								<td>
								<?php
								if($scans == ""){
									echo 'No Tracking Info';
								}			
								
								$initial = true;
								if($scans) {
									foreach($scans as $scan){
										$desc = '';
										if(in_array($scan['Description'],array('Shipment delivered to','Delivered to'))) $desc = $scan['ScanDetails']['DeliverySignature'];
										else $desc = ($scan['Depot']['Name'] == "" ? '' : 'at ' . $scan['Depot']['Name']);
										?>						

										<?= date("Y-m-d h:i a",strtotime($scan['ScanDate'] . ' ' . $scan['ScanTime'])); ?>
										<br>
										<?= $scan['Description'] ?> <?= $desc ?>
										<br><br>

										<?php
										if($initial){
											echo '<div id="' . $pin . 'Details" style="display:none">';
											$initial = false;
										}
												
									}
								}
								if(!$initial){
									echo '</div>';
								} 
								?>		
													
								</td>
							</tr>
						</table>
						<?php } ?>						
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}
?>	

	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>						
						<table cellspacing="0" cellpadding="0" class="dateTbl">
							<tr>
								<td>Sent Date</td>
								<td>
									<input type="text" dateSelector id="invitesSamplePrinted" name="invitesSamplePrinted" style="width:120px" value="<?= $iTask['samplesPrinted'] ?>">
								</td>
								<td><input type="button" value="Set" onClick="setToday('invitesSamplePrinted')"></td>
							</tr>
						</table>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php } ?>	
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Tracking Notes<br><textarea name="trackingNotes" style="width:100%;height:100px"><?= ($hasInvites ? $iTask['trackingNotes'] : $cTask['trackingNotes']) ?></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:10px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td style="text-align:left;"><input style="width:120px;" type="submit" value="Update & Close"></td>
				</tr>
				<tr>
					<td colspan="2" style="color:red;font-weight:bold;>"><?= $_SESSION['trackingError'] ?></td>
					<?php unset($_SESSION['trackingError']); ?>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>