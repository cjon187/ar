<style>
	.formTbl th {padding-right:10px;color:#333333;text-align:left;font-size:9pt;padding-bottom:5px;vertical-align:middle;}
	.formTbl td {color:#333333;padding-bottom:3px;vertical-align:middle;}
	.formTbl textarea {font-family:arial}
	.dateTbl td {padding-right:3px}

	.showOnPrint{
		display:none;
	}

	@media print{
		.showOnPrint{
			display:inline;
		}
	}

</style>

<script>
	$(function(){
		seeTaskStatus();
	});

	function switchToNewKit()
	{
		$.ajax({data:	{switchToNewKit: <?=$_SESSION['kits']['taskID']?>},
				type:	'POST',
				dataType: 'json',
				success: function(data) {
					window.location.replace(data.url);
				},
				error: function (data) {
					ARAlertError(data.responseJSON.error);
				}
		   	    });
		return false;
	}

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

	function validateTrackingNum(obj)
	{
		//obj.value = obj.value.replace(/[^fFwWpP0123456789/]/g,'');
	}

</script>

<form method="POST">
	<input type="hidden" name="taskID" value="<?= $_SESSION['kits']['taskID'] ?>">
	<input type="hidden" name="eventID" value="<?= $_SESSION['kits']['eventID'] ?>">
<table cellspacing="0" cellpadding="0" style="width:400px">
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
					<th style="width:70px">Date:</th>
					<td style="width:80px"><?= displayEventDate($event,true) ?></td>
					<th style="width:65px"># Salesrep:</th>
					<td style="width:70px"><?= $dealer['numSalesrep'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<?php
					$printer = Printer::ById($_SESSION['kits']['printerID']);
					if($printer instanceof Printer){
						$printerAbbr = $printer->printerAbbr;
					}
					else{
						$printerAbbr = "";
					}
					?>
					<th style="width:70px">Printed:</th>
					<td style="width:80px"><?= $printerAbbr ?></td>
					<th style="width:65px">DA#:</th>
					<td style="width:120px"><?= displayWorksheetNum($worksheet) ?></td>

				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:70px">Country:</th>
					<td style="width:80px"><?= ($dealerObj->country instanceof Country ? $dealerObj->country->countryName : 'N/A') ?></td>
					<th style="width:65px">Province:</th>
					<td style="width:120px"><?= $dealer['province'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:70px">VAT/ABN #:</th>
					<td style="width:80px"><?= ($dealer['vatNum'] == '' ? 'N/A' : $dealer['vatNum']) ?></td>
					<th style="width:65px">Language:</th>
					<td style="width:120px"><?= ($dealerObj->language instanceof Language ? $dealerObj->language->languageName : 'N/A') ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:70px">Last Kit:</th>
					<td style="width:80px"><?= ($lastKit['eventStart'] == '' ? 'NA' : date("M Y",strtotime($lastKit['eventStart']))) ?></td>
					<td style="width:140px" colspan="2"><b># Kits this month: </b><?= $numKits ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<td><b>MEC:</b> <?= $am['name'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<th style="width:150px">Kit Last Edit Date: </th>
					<td style="width:140px"><?= $_SESSION['kits']['lastUpdatedDate'] ?></td>
				</tr>
			</table>
		</td>
	</tr>

	<?php
	if(in_array($_SESSION['login']['staffLevelID'], [STAFFLEVEL_MASTER, STAFFLEVEL_AM])){
	?>
		<tr>
			<td>
				<button type="button" style="min-width: 74px;" class="btn btn-info btn-xs trackingInfoModel" onClick="switchToNewKit()">
				    Update To new Kit
				</button>
			</td>
		</tr>
	<?php } ?>

	<tr>
		<td style="padding-top:15px">
			<table cellspacing="0" cellpadding="0" class="formTbl">
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="miscPosters" <?= ($_SESSION['kits']['miscPosters'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Showroom Posters</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="welcomeBanners" <?= ($_SESSION['kits']['welcomeBanners'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Welcome Banners</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="appointmentBoards" <?= ($_SESSION['kits']['appointmentBoards'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Appointment Boards &nbsp;<input style="width:30px" type="text" name="numAppointmentBoards" value="<?= $_SESSION['kits']['numAppointmentBoards'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="gamePlanBoards" <?= ($_SESSION['kits']['gamePlanBoards'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Game Plan Boards</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="ballotForms" <?= ($_SESSION['kits']['ballotForms'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Registration Forms &nbsp;<input style="width:30px" type="text" name="numBallotForms" value="<?= $_SESSION['kits']['numBallotForms'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="soldSigns" <?= ($_SESSION['kits']['soldSigns'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Sold Signs &nbsp;<input style="width:30px" type="text" name="numSoldSigns" value="<?= $_SESSION['kits']['numSoldSigns'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="dealJackets" <?= ($_SESSION['kits']['dealJackets'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Deal Jackets &nbsp;<input style="width:30px" type="text" name="numDealJackets" value="<?= $_SESSION['kits']['numDealJackets'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="luckyMoney" <?= ($_SESSION['kits']['luckyMoney'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Lucky Money</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="workBooks" <?= ($_SESSION['kits']['workBooks'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Work Books &nbsp;<input style="width:30px" type="text" name="numWorkBooks" value="<?= $_SESSION['kits']['numWorkBooks'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="posters" <?= ($_SESSION['kits']['posters'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Invitation Posters</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="extraInvites" <?= ($_SESSION['kits']['extraInvites'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Extra Invites &nbsp;<input style="width:50px" type="text" name="numExtraInvites" value="<?= $_SESSION['kits']['numExtraInvites'] ?>"></td>
				</tr>
				<tr>
					<input type="hidden" name="extraConquest" value="0">
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" value="1" name="extraConquest" <?= ($_SESSION['kits']['extraConquest'] == 1 ? 'checked' : '')?>>
					</td>
					<td>Extra Conquest</td>
				</tr>
				<!-- <tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="notepads" <?= ($_SESSION['kits']['notepads'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Notepads</td>
				</tr> -->
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="stressRelievers" <?= ($_SESSION['kits']['stressRelievers'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Stress Relievers &nbsp;<input style="width:30px" type="text" name="numStressRelievers" value="<?= $_SESSION['kits']['numStressRelievers'] ?>"></td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" class="showOnPrint" disabled>
						<input type="checkbox" name="balloons" <?= ($_SESSION['kits']['balloons'] == 'on' ? 'checked' : '')?>>
					</td>
					<td>Balloons</td>
				</tr>

				<tr>
					<td colspan="6" style="background-color:#eee;padding:10px; width: 400px;">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<input type="checkbox" class="showOnPrint" disabled>
									<input type="checkbox" name="chryslerPraise" <?= ($_SESSION['kits']['chryslerPraise'] == 'on' ? 'checked' : '')?>>
								</td>
								<td>Chrysler Brochures</td>
							</tr>
							<tr>
								<input type="hidden" name="jeffsTrainingInfo" value="0">
								<td>
									<input type="checkbox" class="showOnPrint" disabled>
									<input type="checkbox" value = "1" name="jeffsTrainingInfo" <?= ($_SESSION['kits']['jeffsTrainingInfo'] == 1 ? 'checked' : '')?>>
								</td>
								<td>Jeffs Training Info</td>
							</tr>
							<!--
							<tr>
								<td><input type="checkbox" name="chryslerComebackStory" <?= ($_SESSION['kits']['chryslerComebackStory'] == 'on' ? 'checked' : '')?>></td>
								<td>Chrysler Comeback Story</td>
							</tr>
							-->
							<tr>
								<td>
									<input type="checkbox" class="showOnPrint" disabled>
									<input type="checkbox" name="feedbackForms" <?= ($_SESSION['kits']['feedbackForms'] == 'on' ? 'checked' : '')?>>
								</td>
								<td>Feedback Form</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="background-color:#eee;padding:10px">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<input type="checkbox" class="showOnPrint" disabled>
									<input type="checkbox" name="luxury" <?= ($_SESSION['kits']['luxury'] == 'on' ? 'checked' : '')?>>
								</td>
								<td>Luxury</td>
								<td><input type="checkbox" name="extraLarge" <?= ($_SESSION['kits']['extraLarge'] == 'on' ? 'checked' : '')?>></td>
								<td>Extra Large</td>
								<td><input type="checkbox" name="bilingual" <?= ($_SESSION['kits']['bilingual'] == 'on' ? 'checked' : '')?>></td>
								<td>Bilingual</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style="text-align:right;color:#777;padding-top:15px">
			<div>
				<center>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td style="width:100px;height:100px;color:black;border:1px solid #aaa;vertical-align:middle;text-align:center;" id="kitStatus" onClick="toggleTaskStatus()" onmouseover="this.style.cursor='pointer'">
								<div id="kitStatusDiv" style="font-size:14pt">
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
					<td>Attn To:<br><input type="text" name="attention" value="<?= $_SESSION['kits']['attention'] ?>" style="width:300px"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top:0px">
			<table cellspacing="0" cellpadding="0" class="formTbl" style="width:100%">
				<tr>
					<td>Kits Tracking #<br><input type="text" name="kitsTracking" onKeyUp="validateTrackingNum(this)"  value="<?= $_SESSION['kits']['kitsTracking'] ?>" style="width:200px"></td>
					<td>Kits Sent Date<br><input type="text" name="sent" readonly value="<?= $_SESSION['kits']['sent'] ?>" style="background-color: #ddd; width:200px"></td>
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
						foreach($trackingDetails as $pin => $scans){
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
									//print_r($scans);
									if(count($scans) > 0){
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
										if(!$initial){
											echo '</div>';
										}
									}
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
						<textarea id="notes" name="notes" style="width:100%;height:100px"><?= $_SESSION['kits']['notes'] ?></textarea>
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
					<td colspan="2" style="color:red;font-weight:bold;>"><?= $_SESSION['kitsError'] ?></td>
					<?php unset($_SESSION['kitsError']); ?>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>