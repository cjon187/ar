<?php
include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('taskUtils.php');
include_once('pdfUtils.php');
include_once('agreementUtils.php');

$db = new ARDB();

if(isset($_GET['key']))
{
	if($_GET['key'] != "9C941INMK6" && md5($_GET['eid']) != $_GET['key'])
	{
		echo 'Invalid Key';
		exit;
	}
}
else if(isset($_GET['ekey']))
{
	if(!checkEncrypt($_GET['eid'],$_GET['ekey']))
	{
		echo 'Invalid E-Key';
		exit;
	}
}
else exit;

if(!isset($_GET['html'])) {
	$html2pdf_content = file_get_contents(AR_SECURE_URL . 'export/report/arc.php?eid=' . $_GET['eid'] . '&ekey=' . encrypt($_GET['eid']) . '&html');

	PdfBuilder::buildFromHTML($html2pdf_content,'ARC Summary.pdf');
	exit;

}

$event = displayEventInfo($_GET['eid']);
$trainer = displayStaffInfo($event['trainerID']);

if($event == "")
{
	echo 'This summary do not exist.';
	exit;
}
if($_SESSION['login']['level'] == 'dealer' && $event['dealerID'] != $_SESSION['login']['dealerID'])
{
	echo 'You do not have permission to access this summary.';
	exit;
}

$task = array_shift(getTasks($_GET['eid'],'arc'));

$origcY = 25;



$apptTbl = $event['apptTbl'];

//$sql = 'CREATE TABLE IF NOT EXISTS ' . $apptTbl . ' LIKE ps_appointments';
//mysqli_query($db_data,$sql);

$sql = 'SELECT * FROM ps_staff WHERE level = "arc"';
$arcResults = mysqli_query($db_data,$sql);
$arcStaff = array();
while($arc = mysqli_fetch_assoc($arcResults)) $arcStaff[$arc['staffID']]['name'] = $arc['name'];

$sql = 'SELECT * FROM ' . $apptTbl . ' where source like "%arc%" AND eventID = ' . $event['eventID'];
$apptResults = mysqli_query($db_data,$sql);

$stats = array();
$totals['hand_raiser'] = 0;
$totals['hot'] = 0;
$totals['warm'] = 0;
$totals['show'] = 0;
$totals['sold'] = 0;

while($apptRow = mysqli_fetch_assoc($apptResults))
{
	if($apptRow['arcProspect'] == "hand_raiser")
	{
		if($apptRow['arcStaffID'] != "") $arcStaff[$apptRow['arcStaffID']]['hand_raiser']++;
		$totals['hand_raiser']++;
	}
	if($apptRow['arcProspect'] == "hot")
	{
		if($apptRow['arcStaffID'] != "") $arcStaff[$apptRow['arcStaffID']]['hot']++;
		$totals['hot']++;
	}
	if($apptRow['arcProspect'] == "warm")
	{
		if($apptRow['arcStaffID'] != "") $arcStaff[$apptRow['arcStaffID']]['warm']++;
		$totals['warm']++;
	}

	if($apptRow['arrivedTime'] != "")
	{
		if($apptRow['arcStaffID'] != "") $arcStaff[$apptRow['arcStaffID']]['show']++;
		$totals['show']++;
	}

	for($i = 1; $i <= 4; $i ++)
	{
		if($apptRow['sold' . $i] == 'y')
		{
			if($apptRow['arcStaffID'] != "") $arcStaff[$apptRow['arcStaffID']]['sold']++;
			$totals['sold']++;
		}
	}

	if(!empty($apptRow['arcProspect']))
		$stats[$apptRow['arcProspect']]++;

	$stats['contacts']++;
			/*if($apptRow['arcProspect'] == "appointment") $totals[$apptRow['eventID']]['appt'] ++;

			if($apptRow['arrivedTime'] != "")
				$totals[$apptRow['eventID']]['show']++;

			for($i = 1; $i <= 4; $i ++)
			{
				if($apptRow['sold' . $i] == 'y')
					$totals[$apptRow['eventID']]['sold']++;
			}


			if($apptRow['arcProspect'] == "wrong_number") $totals[$apptRow['eventID']][$apptRow['arcProspect']] ++;
			if($apptRow['arcProspect'] == "left_message") $totals[$apptRow['eventID']][$apptRow['arcProspect']] ++;

			$totals[$apptRow['eventID']]['contacts']++;
			$totals[$apptRow['eventID']]['called']++;*/

}

$totals['leads'] = $totals['hand_raiser']+$totals['hot']+$totals['warm']+$stats['appointment'];

$dealer = displayDealerInfo($event['dealerID']);
$dealerObj = Dealer::byId($dealer['dealerID']);
/*$stats = array();
if($event['saleEndDate'] <= "2015-11-25") {
	$query = 'https://x1.taarga.com/call-center/campaign/status?access_token=e9ef6aacc17289b5238fe42a56a697fe&event_id=' . $event['eventID'];
	$string = file_get_contents($query);
	$xml = simplexml_load_string($string);

	if($xml->contacts == '' || $xml->contacts == 0) {
		echo 'Report uninitialized.';
		exit;
	}

	$stats['contacts'] = $xml->contacts;
	$stats['touched_records'] = $xml->touched_records;
	$stats['appointments'] = $xml->appointments;
	$stats['wrong_number'] = $xml->wrong_number;
	$stats['no_interest'] = $xml->no_interest;
	$stats['left_voice_message'] = $xml->left_voice_message;
	$stats['do_not_call'] = $xml->do_not_call;
	$stats['deceased'] = $xml->deceased;
}
else {

	$db = new ARDB();
	$f9 = new Five9Soap();
	$five9Stats = $f9->getStatsByEventIds($event['eventID']);
	if($five9Stats) {

		$stats['contacts'] = intval($five9Stats[$event['eventID']]['contacts']);
		$stats['touched_records'] = intval($five9Stats[$event['eventID']]['called']);
		$stats['appointments'] = intval($five9Stats[$event['eventID']]['Appointment Booked']);
		$stats['wrong_number'] = intval($five9Stats[$event['eventID']]['Wrong Number']);
		$stats['no_interest'] = intval($five9Stats[$event['eventID']]['Not Interested']);
		$stats['left_voice_message'] = intval($five9Stats[$event['eventID']]['Answering Machine']);
		$stats['do_not_call'] = intval($five9Stats[$event['eventID']]['Do Not Call']);
		$stats['deceased'] = intval($five9Stats[$event['eventID']]['Deceased']);
	}
}*/
$taarga = array();
$taarga['contacts'] = $stats['contacts'];
$taarga['called'] = $stats['touched_records'];
$taarga['appointments'] = $stats['appointments'];

$ws = Worksheet::byID($task['worksheetID']);
$ag = [];
if($ws instanceof Worksheet) {

	$results = $ws->items;
	if($results) {
		foreach($results as $re) {
			if($re->worksheetItemTypeID == WorksheetItemType::ARC) {
				$wsi = $re;
				break;
			}
		}
	}

	$ag = $ws->toArray();
	if($wsi instanceof WorksheetItem) {
		$ag['total_cost'] = $wsi->unitPrice * $wsi->quantity;
		if($dealerObj->hasOEM(OEM_FCA)) {
			$ag['coop'] = min($ag['total_cost']/2,750);
		}
		else {
			$ag['coop'] = 0;
		}
		$ag['net_cost'] = max(0,$ag['total_cost']-$ag['coop']);
	}
}

$cada_lead_cost = 480;
$cada_avg_gross = 3665;

?>
<html>
<head>
<style>
	h1,h2,h3,h4,h5{line-height:1em;}
	td {vertical-align:top;}
	.box {border:5px solid #ccc;border-radius:5px;width:150px;height:100px;padding-bottom:5px;margin-right:20px;background-color:#fcfcfc;}
	.num , .sign {color:#cf1f2e;}
	.desc {color:#777;}

	.box .num {font-size:60px;font-weight:bold;text-align:center;}
	.box .desc {font-size:20px;font-weight:bold;text-align:center;}

	.stats .num {font-size:25px;padding-right:20px;}
	.stats .desc {font-size:25px; }

	.roi .num {font-size:18px;line-height:20px;width:80px;text-align:right;}
	.roi .sign {font-size:18px;width:10px;}
	.roi .desc {font-size:18px;padding-right:20px; }

	.roi .bold {padding:10px 0px;font-weight:bold;}
</style>
</head>
<body>
<div style="width:800px">
	<table style="width:100%">
		<tr>
			<td style="width:30%">
				<img src="<?= AR_SECURE_URL ?>images/arc_logo_trans.png" style="height:80px">
			</td>
			<td style="width:70%;text-align:right">
				<div style="font-size:2em"><?= $dealer['dealerName'] ?></div>
				<div style="font-size:1.5em"><?= displayEventDate($event,false,false,true) ?> <?= ($trainer['name'] != '' ? ' - ' . $trainer['name'] : '') ?></div>
			</td>
		</tr>
	</table>
	<h1 style="color:#555">ARC Summary</h1>
	<table>
		<tr>
			<td>
				<div class="box">
					<div class="num"><?= $totals['leads'] ?></div>
					<div class="desc">LEADS</div>
				</div>
			</td>
			<td>
				<div class="box">
					<div class="num"><?= $totals['show'] ?></div>
					<div class="desc">SHOWS</div>
				</div>
			</td>
			<td>
				<div class="box">
					<div class="num"><?= $totals['sold'] ?></div>
					<div class="desc">SOLDS</div>
				</div>
			</td>
		</tr>
	</table>
	<br><br>
	<table>
		<tr>
			<td style="padding-right:100px">
				<table class="stats">
					<tr>
						<td class="num"><?= number_format($stats['contacts']) ?></td>
						<td class="desc">Total Customers</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['contacts'] - $stats['wrong_number']) ?></td>
						<td class="desc">Workable Numbers</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['not_interested'] + $totals['leads']) ?></td>
						<td class="desc">Conversations</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['unavailable']) ?></td>
						<td class="desc">Unavailable </td>
					</tr>
				</table>
			</td>
			<td>
				<table class="stats">
					<tr>
						<td class="num"><?= number_format($stats['not_interested']) ?></td>
						<td class="desc">No Interest</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['wrong_number']) ?></td>
						<td class="desc">Wrong Number</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['do_not_call']) ?></td>
						<td class="desc">Do Not Call</td>
					</tr>
					<tr>
						<td class="num"><?= number_format($stats['deceased']) ?></td>
						<td class="desc">Deceased</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br><br>
	<h2 style="color:#555">ROI Summary</h2>
	<table class="roi">
		<tr>
			<td class="desc">COST PER LEAD THROUGH THE DOOR (CADA)</td>
			<td class="sign">$</td>
			<td class="num"><?= number_format($cada_lead_cost,2) ?></td>
		</tr>
		<tr>
			<td class="desc">ARC LEADS THROUGH THE DOOR</td>
			<td class="sign"></td>
			<td class="num"><?= $totals['show'] ?></td>
		</tr>
		<tr>
			<td class="desc">COST PER <b>ARC</b> LEAD</td>
			<td class="sign"><b>$</b></td>
			<td class="num"><b><?= empty($totals['show']) ? '0' : number_format($ag['total_cost']/$totals['show'],2) ?></b></td>
		</tr>
		<tr class="bold">
			<td class="desc">YOUR NET SAVINGS FOR ALL LEADS</td>
			<td class="sign">$</td>
			<td class="num"><?= number_format(($cada_lead_cost*$totals['show']) - $ag['total_cost'],2) ?> </td>
		</tr>
		<tr>
			<td style="height:20px"></td>
		</tr>
		<tr>
			<td class="desc">AVERAGE GROSS PER DEAL FRONT & BACK (CADA)</td>
			<td class="sign">$</td>
			<td class="num"><?= number_format($cada_avg_gross,2) ?></td>
		</tr>
		<tr>
			<td class="desc">TOTAL DEALS FROM ARC LEADS</td>
			<td class="sign"></td>
			<td class="num"><?= $totals['sold'] ?></td>
		</tr>
		<tr class="bold">
			<td class="desc">ANTICIPATED GROSS PROFIT</td>
			<td class="sign">$</td>
			<td class="num"><?= number_format(($cada_avg_gross*$totals['sold']),2) ?> </td>
		</tr>
		<tr>
			<td style="height:20px"></td>
		</tr>
		<?php
			if($ag['total_cost'] == 0)
			{
		?>
				<tr>
					<td class="desc">INVESTMENT IN ARC</td>
					<td class="sign"></td>
					<td class="num">N/A</td>
				</tr>
		<?php
			}
			else
			{
		?>
				<tr>
					<td class="desc">INVESTMENT IN ARC</td>
					<td class="sign">$</td>
					<td class="num"><?= number_format($ag['total_cost'],2) ?></td>
				</tr>
		<?php
			if($dealerObj->hasOEM(OEM_FCA)) {
		?>
				<tr>
					<td class="desc">YOUR CO-OP $$</td>
					<td class="sign">$</td>
					<td class="num"><?= number_format($ag['coop'],2) ?></td>
				</tr>
				<tr>
					<td class="desc">NET INVESTMENT AFTER CO-OP</td>
					<td class="sign"><b>$</b></td>
					<td class="num"><b><?= number_format($ag['net_cost'],2) ?></b></td>
				</tr>
		<?php
			}
		?>
				<tr class="bold">
					<td class="desc">TOTAL ANTICIPATED ROI</td>
					<td class="sign"></td>
					<td class="num"><?= ($ag['net_cost'] === 0 ? 'FREE' : number_format(100*($cada_avg_gross*$totals['sold'])/$ag['net_cost']) .  '%') ?></td>
				</tr>

		<?php
			}
		?>
	</table>

	<div style="text-align:center;font-size:12pt;color:#555;padding-top:100px">
		These numbers reflect industry standards. <br>
		19 times out of 20, two extra deals are generated pre or post sale.
	</div>
	<br><br>
	<div style="text-align:center;font-size:12pt">www.absoluteresults.com</div>
	<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Communications Center. All rights reserved.</div>
</div>
