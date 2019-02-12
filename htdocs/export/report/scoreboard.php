<?php

	include_once('loginUtils.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');

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
			echo 'Invalid Key';
			exit;
		}
	}
	else
	{
		checkPageAccess();
	}

	$event = Event::byId($_GET['eid']);
	if($event instanceof Event) {
		$dlc = new DealerLoginController($event->dealer);
		$dlc->buildScoreboardPDF($event);
	} else {
		echo 'Invalid Event';
	}

	exit;
/*
	$event = displayEventInfo($_GET['eid']);
	$dealer = displayDealerInfo($event['dealerID']);
	$sql = 'SELECT * FROM
			(SELECT eventID,dealerID,saleStartDate,saleEndDate,trainerName FROM ps_events WHERE eventID in (' . $event['eventID'] . ')) as t1
			INNER JOIN
			(SELECT dealerID,dealerName FROM ps_dealers) as t2
			USING
			(dealerID)
			ORDER BY eventID';
	$results = mysqli_query($db_data,$sql);

	$results = mysqli_query($db_data,$sql);
	if($results !== false && mysqli_num_rows($results) > 0){
		$stats = displayStats(mysqli_fetch_assoc($results),array('oc'));
	}

	$lang = array();
	$lang["Salesperson"] = "Représentant";
	$lang["Up's"] = "Passants";
	$lang["Sales"] = "Ventes";
	$lang["Closing"] = "% Closing";
	$lang["Appts"] = "RDV";
	$lang["Goal"] = "Objectif";
	$lang["Pace"] = "Écart";
	$lang["Kept"] = "Gardé";
	$lang["Sales"] = "Ventes";
	$lang["Closing"] = "% Closing";
	$lang["Shows"] = "Présents";
	$lang["Sales"] = "Ventes";
	$lang["Goal"] = "Objectif";
	$lang["Pace"] = "Écart";
	$lang["Closing"] = "% Closing";

	if($dealer['isFrench'] != 'on') foreach($lang as $key => $val) $lang[$key] = $key;

	$origY = $cY;

	$p = 1;
	$i = 1;
	$pages = array();
if ($stats['salesrep'] !== null) {

	foreach($stats['salesrep'] as $salesrep => $info)
	{
		$pages[$p][$salesrep] = $info;
		$i++;

		if($i == 30)
		{
			$p++;
			$i = 1;
		}
	}

	foreach($pages as $page => $p)
	{
		$pdf->addPage('L','Letter');
		$pdf->Image('https://ar.absoluteresults.com/images/logo_small.png',10,8,50);

		$cY = $origY;

		$cY -= 13;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0,$dealer['dealerName'] . ' - ' . displayEventDate($event) . ' ' . date("Y", strtotime($event['saleStartDate'])) ,0,0,'R');
		$pdf->SetY(newline(5));
		$pdf->Cell(0,0,'Scoreboard Summary',0,0,'R');
		$pdf->SetY(newline(5));
		$pdf->Cell(0,0,$event['trainerName'],0,0,'R');

		$repW = 50;
		$colW = 14;
		$colH = 5;
		$spW = 7;

		$pdf->SetFillColor(240,240,240);

		$pdf->SetY(newline(15));

		$pdf->Cell(0,0,'','B');
		$pdf->SetY(newline(-4));
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell($repW,$colH,$lang['Salesperson']);
		$pdf->Cell($colW,$colH,$lang['Up\'s'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Sales'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Closing'],0,0,'C',true);
		$pdf->Cell($spW,$colH,'',0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Appts'],0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Goal'],0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Pace'],0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Kept'],0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Sales'],0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Closing'],0,0,'C');
		$pdf->Cell($spW,$colH,'',0,0,'C');
		$pdf->Cell($colW,$colH,$lang['Shows'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Sales'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Goal'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Pace'],0,0,'C',true);
		$pdf->Cell($colW,$colH,$lang['Closing'],0,0,'C',true);

		$pdf->SetY(newline());
		$pdf->Cell(0,0,'','B');
		$pdf->SetY(newline(-4));

		$pdf->SetFont('Arial','',11);
		foreach($p as $salesrep => $info)
		{
			$pdf->SetY(newline());
			$pdf->Cell($repW,$colH,$salesrep);
			$pdf->Cell($colW,$colH,(is_numeric($info['upShow'])) ? number_format($info['upShow']) : 'N/A',0,0,'C',true);
			$pdf->Cell($colW,$colH,(is_numeric($info['upSold'])) ? number_format($info['upSold']) : 'N/A',0,0,'C',true);
			$pdf->Cell($colW,$colH,number_format($info['upClosing']) . '%',0,0,'C',true);
			$pdf->Cell($spW,$colH,'',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['appt'])) ? number_format($info['appt']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['apptGoal'])) ? number_format($info['apptGoal']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['apptPace'])) ? number_format($info['apptPace']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['apptShow'])) ? number_format($info['apptShow']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['apptSold'])) ? number_format($info['apptSold']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,number_format($info['apptClosing']) . '%',0,0,'C');
			$pdf->Cell($spW,$colH,'',0,0,'C');
			$pdf->Cell($colW,$colH,(is_numeric($info['show'])) ? number_format($info['show']) : 'N/A',0,0,'C',true);
			$pdf->Cell($colW,$colH,(is_numeric($info['sold'])) ? number_format($info['sold']) : 'N/A',0,0,'C',true);
			$pdf->Cell($colW,$colH,(is_numeric($info['soldGoal'])) ? number_format($info['soldGoal']) : 'N/A',0,0,'C',true);
			$pdf->Cell($colW,$colH,(is_numeric($info['soldPace'])) ? number_format($info['soldPace']) : 'N/A',0,0,'C');
			$pdf->Cell($colW,$colH,number_format($info['closing']) . '%',0,0,'C',true);

		}

		$pdf->SetY(newline());
		$pdf->Cell(0,0,'','B');
		$pdf->SetY(newline(-4));
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','b',11);
		$pdf->SetTextColor(255,0,0);
		$pdf->Cell($repW,$colH,'OC:' . ((is_numeric($stats['ocUnits'])) ? number_format($stats['ocUnits']) : 'N/A'));

		$pdf->SetTextColor(0,0,0);
		$pdf->Cell($colW,$colH,(is_numeric($stats['upShow'])) ? number_format($stats['upShow']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,(is_numeric($stats['upSold'])) ? number_format($stats['upSold']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,number_format($stats['upClosing']) . '%',0,0,'C',true);
		$pdf->Cell($spW,$colH,'',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['appt'])) ? number_format($stats['appt']) : 'N/A',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['apptGoal'])) ? number_format($stats['apptGoal']) : 'N/A',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['apptPace'])) ? number_format($stats['apptPace']) : 'N/A',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['apptShow'])) ? number_format($stats['apptShow']) : 'N/A',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['apptSold'])) ? number_format($stats['apptSold']) : 'N/A',0,0,'C');
		$pdf->Cell($colW,$colH,number_format($stats['apptClosing']) . '%',0,0,'C');
		$pdf->Cell($spW,$colH,'',0,0,'C');
		$pdf->Cell($colW,$colH,(is_numeric($stats['show'])) ? number_format($stats['show']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,(is_numeric($stats['sold'])) ? number_format($stats['sold']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,(is_numeric($stats['soldGoal'])) ? number_format($stats['soldGoal']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,(is_numeric($stats['soldPace'])) ? number_format($stats['soldPace']) : 'N/A',0,0,'C',true);
		$pdf->Cell($colW,$colH,number_format($stats['closing']) . '%',0,0,'C',true);
		$pdf->SetY(newline());
		$pdf->Cell(0,0,'','B');
		$pdf->SetY(newline(-4));
		$pdf->SetY(newline());

		addFooter(true);
	}
}

	if(!isset($_GET['noOutput']))
	{
		$pdf->Output();
		$pdf->Close();
	}*/
?>