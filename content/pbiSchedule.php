
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<style>
	.pbiScheduleTbl {border-collapse:collapse;width:100%;background-color:white}
	.pbiScheduleTbl th {padding:3px 5px;font-size:9pt;color:#777777;font-weight:normal;white-space:nowrap}
	.pbiScheduleTbl td {padding:0px 5px;border: 1px solid #ccc;font-size:10pt;text-align:center;height:30px;vertical-align:middle;}
	.pbiScheduleTbl div {vertical-align:middle;line-height:29px;}
	.taskTD {background-color:<?= $naC ?>;width:58px}
	.pbiScheduleTbl td input {font-size: 9pt;}
	.pbiScheduleTbl td a {text-decoration:none}
	.dayTbl {width:100%;height:100%}
	.dayTbl td {padding:3px;border:0px;}
	.eventDiv {white-space: nowrap;;position:absolute;padding:0px 0px 0px 0px;border:1px solid #72a45a;height:17px;background-image:url('images/eventBG.gif');font-size:8pt}
	.searchTbl td {border:0px;padding:0px;height:19px;vertical-align:middle}
	.searchTbl td input {font-size:8pt;width:100%}
	
	.ui-datepicker {font-size: 12px}
</style>
<script>
	$(function() {
		$('.dealer').tooltip();
	})
	function seeTracking(tasks)
	{
		var win = window.open('?s1=production&s2=Tracking&tasks=' + tasks,'seeTracking','width=400,height=600,toolbar=0,scrollbars=1');
		win.focus();
		return false;	
	}
	
	function seeKits(eid,taskID)
	{
		var win = window.open('?s1=kits&eventID=' + eid + '&taskID=' + taskID,'seeKits','width=400,height=800,toolbar=0,scrollbars=1');
		win.focus();
		return false;	
	}
	
</script>
<div class="row">
	<h1 style="text-decoration: underline; margin-left: 10px;">
		PBI - Event Schedule
	</h1>
</div>

<div class="row">
	<div class="col-xs-4" style="height: 30px; ">
		<a href="?s1=pbiSchedule&viewBack="><img src="images/calendarLeft.gif" border="0"></a>
		<a href="?s1=pbiSchedule&viewForward="><img src="images/calendarRight.gif" border="0"></a>
		<span style="color:black; font-weight: bold; font-size: 12pt;">
			<?= date("M Y",strtotime($_SESSION['pbiSchedule']['viewYear'] . '-' . $_SESSION['pbiSchedule']['viewMonth'] . '-1')) ?>
		</span>
	</div>
	<div class="col-xs-8" style="text-align:right; height: 30px;">
		<form class="form-inline" method="POST">
			<div class="form-group">
				<select name="printer" onChange="this.form.submit()" style="margin-right: 20px;">
					<option value="0">PBI - BOTH</option>
					<option value="<?= PRINTER_PBI ?>" <?= ($_SESSION['pbiSchedule']['printer'] == PRINTER_PBI ? 'SELECTED' : '') ?>>PBI - EAST</option>
					<option value="<?= PRINTER_PBIWEST ?>" <?= ($_SESSION['pbiSchedule']['printer'] == PRINTER_PBIWEST ? 'SELECTED' : '') ?>>PBI - WEST</option>
					
				</select>
			</div>
		
			<div class="form-group">
				<select name="country" onChange="this.form.submit()" style="margin-right: 20px;">
					<option value="" <?= ($_SESSION['pbiSchedule']['country'] == "" ? 'SELECTED' : '') ?>>All Dealers</option>
					<?php
					foreach($countries as $cid => $cname) {
					?>
						<option value="<?= $cid ?>" <?= ($_SESSION['pbiSchedule']['country'] == $cid ? 'SELECTED' : '') ?>><?= $cname ?></option>
					<?php } ?>
				</select>
			</div>
			
			<div class="form-group" >
			    <div class = "input-group" style="width: 150px; ">
		           <input type = "text" name="search" class = "form-control" style="line-height: 26px; height: 26px;" value="<?= $_SESSION['pbiSchedule']['search'] ?>" onChange="this.form.submit()">
		           
		           <span class = "input-group-btn">
		              <button class = "btn btn-default" type = "button" style="line-height: 26px; height: 26px; padding: 1px 4px;">
		                 <span class="glyphicon glyphicon-search"></span>
		              </button>
		           </span>
		           
		        </div>
		    </div>
		</form>


	</div>
</div>
<div class="row" style="text-align:center; margin-top: 10px;">
	<div class="col-xs-12">
		<table cellspacing="0" cellpadding="0" style="padding-left:20px;padding-right:20px;width:100%">
			<tr>
				<td>	
					<table cellspacing="0" cellpadding="0" class="pbiScheduleTbl">
						<tr>
							<th>#</th>
							<th>Printer</th>
							<th style="text-align:left">Dealership</th>	
							<th nowrap>Prov</th>
							<th nowrap>Country</th>
							<th nowrap>Account Manager</th>
							<th style="">Date</th>
							<th style="">Training</th>
							<th style=" ">Trainer</th>
							<th style=" ">RA Name</th>
							<th style=" ">Tracking</th>
							<th style=" ">Kits</th>
							<th style=" ">Invite DAs</th>
							<th style=" ">Invite #</th>
							<th style=" ">Printed</th>
							<th style=" ">Conquest DAs</th>
							<th style=" ">Conquest #</th>
							<th style=" ">Printed</th>
						</tr>
		<?php
			$isGrey = false;
			$i= 0;
			foreach($events as $eventID => $info)
			{
				//print_r2($info);
				$dealer = displayDealerInfo($info['event']['dealerID']);
				$eventObj = Event::ById($eventID);
				$dealerObj = Dealer::ById($info['event']['dealerID']);
				if($dealerObj instanceof Dealer) {
					$raContact = $dealerObj->getRAContact();
				}else{
					$raContact = "Not Set";
				}
				$lineThrough = 'text-decoration:line-through;';	
				if($info['event']['confirmed'] == 'confirmed') $lineThrough = '';
				
				$isGrey = !$isGrey;
				if($isGrey) $bg = '#F8F8F8';
				else $bg = 'white';
				
				$i++;
				
				
				$invite = array();
				$conquest = array();
				$invitations = array();
				$invitations['agreements'] = array();
				$invitations['mailed'] = array();
				$invitations['printed'] = array();
				$conquests = array();
				$conquests['agreements'] = array();
				$conquests['mailed'] = array();
				$conquests['printed'] = array();

				if(count($info['invitations']) > 0) {
					foreach($info['invitations'] as $taskID => $task) {
						$invitations['agreements'][] = displayWorksheetNum($task);
						$invitations['mailed'][] = taskMailed($task);
						$invitations['printed'][] = $task['printed'];	
					}

					$invite = array_shift($info['invitations']);
				}			

					
				
				if(count($info['conquests']) > 0) {
					foreach($info['conquests'] as $taskID => $task) {
						$conquests['agreements'][] = displayWorksheetNum($task);
						$conquests['mailed'][] = taskMailed($task);
						$conquests['printed'][] = $task['printed'];	
					}
					$conquest = array_shift($info['conquests']);
				}			
		?>
						<tr style="background-color:<?= $bg ?>">
							<td nowrap style="<?= $lineThrough ?>"><?= $i ?></td>
							<td nowrap style="<?= $lineThrough ?>">
								<?php
								if( (isset($conquest['printerID']) && isset($invite['printerID'])) && ($conquest['printerID'] != $invite['printerID']) ) {
									$printer1 = Printer::ById($invite['printerID']);
									$printer2 = Printer::ById($conquest['printerID']);
									echo $printer1->printerAbbr.'/'.$printer2->printerAbbr;
								}
								else if(isset($invite['printerID'])){
									$printer = Printer::ById($invite['printerID']);
									echo $printer->printerAbbr;
								}
								else if(isset($conquest['printerID'])){
									$printer = Printer::ById($conquest['printerID']);
									echo $printer->printerAbbr;
								}
								else{
									echo 'N/A';
								}	
								?>
							</td>
							<td nowrap style="text-align:left;<?= $lineThrough ?>" class="dealer"  title="<?= $dealerObj->address . ' ' . $dealerObj->city . ' ' . strtoupper($dealerObj->province->provinceAbbr) . ' ' . $dealerObj->postalCode ?>"><?= $dealerObj->dealerName ?></td>
							
							<td nowrap style="<?= $lineThrough ?>"><?= strtoupper($dealerObj->province->provinceAbbr) ?></td>
							<td nowrap style="<?= $lineThrough ?>"><?= $dealerObj->country->iso ?></td>
							
							<?php
								$mecName = $eventObj->accountManager->name;
								$explodeName = explode(' ',$mecName);
								$mecName = $explodeName[0][0].'. '.$explodeName[count($explodeName) - 1];
							?>
							<td nowrap style="<?= $lineThrough ?>"><?= $mecName ?></td>
							<td nowrap style="<?= $lineThrough ?>"><?= displayEventDate($info['event'],true) ?></td>
							<td nowrap style="<?= $lineThrough ?>"><?= $eventObj->trainingDays ?></td>
							
							<?php
								$trainerName = $eventObj->trainerName;
								$explodeName = explode(' ',$trainerName);
								$trainerName = $explodeName[0][0].'. '.$explodeName[count($explodeName) - 1];
							?>
							<td nowrap style="<?= $lineThrough ?>"><?= $trainerName ?></td>
							<td nowrap style="<?= $lineThrough ?>"><?= $raContact->name ?></td>	
							
							<?php
							if($_SESSION['login']['staffID'] == 874){
								?><td><?= ($invite['printed'] == "" ? '' : 'I') . ($conquest['printed'] == "" ? '' : 'C') ?></td><?php
							} else {
								?><td style="color:blue" onMouseover="this.style.cursor='pointer'" onClick="return seeTracking('<?= $eventID . '_' . $invite['taskID'] . '_' . $conquest['taskID'] ?>');"><?= ($invite['printed'] == "" ? '' : 'I') . ($conquest['printed'] == "" ? '' : 'C') ?></td><?php
							}				
							?>
							
							<?php 
								if(isset($kits[$eventID])) { 
									$kitTask = array_shift($kits[$eventID]);
									if($_SESSION['login']['staffID'] == 874){
										?><td><?= ($kitTask['status'] == '' ? '' : $kitTask['status']) ?></td><?php
									} else {
										?><td style="color:blue" onMouseover="this.style.cursor='pointer'" onClick="return seeKits('<?= $eventID ?>','<?= $kitTask['taskID'] ?>')"><?= ($kitTask['status'] == '' ? '' : $kitTask['status']) ?></td><?php
									}
								} else {
									echo '<td style="background-color:' . $bgColors['naC'] . '"></td>';
								}
							?>
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$invitations['agreements']) ?></td>	
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$invitations['mailed']) ?></td>	
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$invitations['printed']) ?></td>	
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$conquests['agreements']) ?></td>	
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$conquests['mailed']) ?></td>	
							<td nowrap style="<?= $lineThrough ?>"><?= implode('<br>',$conquests['printed']) ?></td>						
						</tr>	
						<?php
						}
						if(count($events) == 0) {
							echo '<tr><th style="height:40px;font-size:12pt;text-align:left">No jobs found.</th></tr>';
						}
						?>			


					</table>	
				</td>
			</tr>
		</table>
	</div>
</div>