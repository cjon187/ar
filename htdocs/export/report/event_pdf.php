<?php
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('statsUtils.php');
include_once('trackbackUtils.php');
include_once('pdfUtils.php');

$db = new ARDB();

$eventObj = Event::byHash($_GET['hash']);


$eventRow = displayEventInfo($eventObj->id);
if(empty($eventRow)) {
	echo 'This summary do not exist.';
	exit;
}


$origcY = 25;

$eventStats = displayStats($eventRow,array('hotWarmCold'));

$mailed = displayEventMailed($eventRow['eventID']);
$inviteListTotal = $mailed['invites'];

$sql = 'SELECT *, coalesce(lastActivityDate >= "' . date("Y-m-d",strtotime('now - 18 months')) . '",0) as within18 FROM ' .$eventRow['apptTbl'] . ' where eventID = ' . $eventRow['eventID'];
$apptResults = mysqli_query($db_data,$sql);

while($apptRow = mysqli_fetch_assoc($apptResults))
{
	for($i = 1; $i <= 4; $i ++)
	{
		if($apptRow['sold' . $i] == 'y')
		{
			$totalSold++;
			if($apptRow['newUsed' . $i] == 'new') $newSold++;
			else if($apptRow['newUsed' . $i] == 'used') $usedSold++;
			else if($apptRow['newUsed' . $i] == 'demo') $demoSold++;
			else if($apptRow['newUsed' . $i] == 'amp') $ampSold++;

			$soldLogArray[$apptRow['appointmentID']] = $apptRow;
		}
	}

	if($apptRow['arrivedTime'] != '' && $apptRow['sold1'] != 'y' && (in_array($apptRow['prospect'],array('hot','warm','cold'))))
	{
			$followUpLogArray[$apptRow['prospect'].$apptRow['appointmentID']] = $apptRow;
	}

	if($apptRow['salesperson'] != "")
	{
		if($apptRow['trainerAppointment'] == 'on') $sr = 'zzz-'.$eventRow['trainers'];
		else $sr = $apptRow['salesperson'];

		if($apptRow['appointmentTime'] != "") $salesrepSummary[$sr]['appt']++;
		if($apptRow['arrivedTime'] != "") $salesrepSummary[$sr]['show']++;
		if($apptRow['sold1'] != "") $salesrepSummary[$sr]['sold']++;
		if($apptRow['sold2'] != "") $salesrepSummary[$sr]['sold']++;
		if($apptRow['sold3'] != "") $salesrepSummary[$sr]['sold']++;
		if($apptRow['sold4'] != "") $salesrepSummary[$sr]['sold']++;
	}
}

if(count($salesrepSummary) > 0){
	ksort($salesrepSummary);
}

$dealerInfo = displayDealerInfo($eventRow['dealerID']);

$event = Event::ById($eventRow['eventID']);
$dealer = $event->dealer;

if(!isset($_GET['soldOnly']))
{

	$pdf->addPage('P','Letter');
	//$pdf->useTemplate($tplidx, 0, 0);
	$pdf->SetAutoPageBreak(false);

	$pdf->Image(AR_SECURE_URL . 'images/logo.png',10,8,75);

	$pdf->SetX(0);
	$pdf->Rect(5,28,205,1,'F');

	$pdf->SetY(newline(10));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Dealership');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(50,0,$dealerInfo['dealerName']);

	$pdf->SetY(newline(7));

	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Sale Date');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(50,0,date('M d, Y',strtotime($eventRow['saleStartDate'])));

	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Contact Person');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,0,$eventRow['contact']);


	$pdf->SetY(newline(7));

	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Total Invites');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(50,0,number_format($inviteListTotal));

	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Trainers');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,0,$eventRow['trainerName']);

	$pdf->SetY(newline(7));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'# Sales Staff');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(50,0,number_format($eventRow['totalSalesreps']));

	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(35,0,'Monthly Volume');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,0,$eventRow['monthVolume']);

	$pdf->SetY(newline(7));

	$pdf->SetY(newline(4));
	$pdf->Cell(85,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(36,0,'Made',0,0,'C');
	$pdf->Cell(36,0,'Showed',0,0,'C');
	$pdf->Cell(36,0,'%',0,0,'C');

	$pdf->SetY(newline(4));
	hr(5);


	$pdf->SetY(newline(6));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(85,0,'Appointments');
	$pdf->SetFont('Arial','',12);


	$pdf->Cell(36,0,number_format($eventStats['appt']),0,0,'C');
	$pdf->Cell(36,0,number_format($eventStats['apptShow']),0,0,'C');
	$pdf->Cell(36,0,($eventStats['appt'] > 0 ? number_format(100*$eventStats['apptShow']/$eventStats['appt']) . '%' : 'N/A'),0,0,'C');

	$pdf->SetY(newline(6));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(85,0,'Non-Appointments');
	$pdf->SetFont('Arial','',12);


	$pdf->Cell(36,0,'-',0,0,'C');
	$pdf->Cell(36,0,number_format($eventStats['upShow']),0,0,'C');
	$pdf->Cell(36,0,'-',0,0,'C');


	$pdf->SetY(newline(6));
	hr(5);

	$pdf->SetY(newline(4));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(85,0,'Total');
	$pdf->SetFont('Arial','',12);

	$pdf->Cell(36,0,number_format($eventStats['appt']),0,0,'C');
	$pdf->Cell(36,0,number_format($eventStats['show']),0,0,'C');

	$pdf->SetY(newline(15));

	$anchor = $cY;
	$pdf->Cell(36,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(25,0,'Prospects',0,0,'C');
	$pdf->Cell(36,0,'%',0,0,'C');

	$pdf->SetY(newline(4));
	hr(5,95);

	$prospectArray['hot'] = 'Hot';
	$prospectArray['warm'] = 'Warm';
	$prospectArray['cold'] = 'Cold';

	foreach($prospectArray as $type => $desc)
	{
		$pdf->SetY(newline(6));
		$pdf->SetFont('Arial','b',12);
		$pdf->Cell(36,0,$desc);
		$pdf->SetFont('Arial','',12);

		$pdf->Cell(25,0,number_format($eventStats[$type]),0,0,'C');
		$pdf->Cell(36,0,($eventStats['show'] > 0 ? displayPercentage($eventStats[$type]/$eventStats['show']) : 'N/A'),0,0,'C');
	}

	$pdf->SetY(newline(6));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(36,0,'Demonstration');
	$pdf->SetFont('Arial','',12);

	$pdf->Cell(25,0,$eventStats['demo'],0,0,'C');
	$pdf->Cell(36,0,($eventStats['show'] > 0 ? displayPercentage($eventStats['demo']/$eventStats['show']) : 'N/A'),0,0,'C');
	$pdf->SetY(newline(6));
	//hr(5);



	$current = $cY;
	$cY = $anchor;
	$trackback = getTrackBackInfo($eventRow['eventID']);
	$pdf->SetY(newline(0));

	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(36,0,'Conquest TrackBack');

	$pdf->SetY(newline(4));
	hr(5,91,105);
	$pdf->SetY(newline(6));
	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(55,0,'Flyers Mailed');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(36,0,($event->stats->conquest != "" ? $event->stats->conquest : 'N/A'));
	$pdf->SetY(newline(6));
	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(55,0,'Flyers Web Registrations');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(36,0,($trackback['web'] != "" ? number_format($trackback['web']) : 'N/A'));
	$pdf->SetY(newline(6));
	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(55,0,'Flyers Shows');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(36,0,($trackback['show'] != "" ? number_format($trackback['show']) : 'N/A'));
	$pdf->SetY(newline(6));
	$pdf->Cell(110,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(55,0,'Flyers Sold');
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(36,0,($trackback['sold'] != "" ? number_format($trackback['sold']) : 'N/A'));


	$cY = $current;
	$pdf->SetY(newline(6));
	$pdf->Cell(121,0,'');
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(36,0,'Deals',0,0,'C');
	$pdf->Cell(36,0,'%',0,0,'C');

	$pdf->SetY(newline(4));
	hr(5);

	$soldArray['newSold'] = 'New';
	$soldArray['usedSold'] = 'Used';
	if($dealer->countryID == COUNTRY_US && $dealer->hasBrand(Brand::BMW)) {
		$soldArray['demoSold'] = 'Demo';
		$soldArray['ampSold'] = 'AMP Update';
	}

	foreach($soldArray as $type => $desc)
	{
		$pdf->SetY(newline(6));
		$pdf->SetFont('Arial','b',12);
		$pdf->Cell(121,0,$desc);
		$pdf->SetFont('Arial','',12);

		$pdf->Cell(36,0,number_format($eventStats[$type]),0,0,'C');
		$pdf->Cell(36,0,($eventStats['show'] > 0 ? displayPercentage($eventStats[$type]/$eventStats['show']) : 'N/A'),0,0,'C');
	}
	$pdf->SetY(newline(6));
	hr(5);

	$pdf->SetY(newline(4));
	$pdf->SetFont('Arial','b',12);
	$pdf->Cell(121,0,'Total');
	$pdf->SetFont('Arial','',12);

	$pdf->Cell(36,0,number_format($eventStats['sold']),0,0,'C');
	$pdf->Cell(36,0,($eventStats['show'] > 0 ? displayPercentage($eventStats['sold']/$eventStats['show']) : 'N/A'),0,0,'C');

	if(count($imgList) > 0) $charPerLine = 70;
	else $charPerLine = 125;

	if($eventRow['comments']) {
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','b',12);
		//$pdf->Cell(1,0,'');
		$pdf->Cell(0,0,'Comments');

		$pdf->SetY(newline());
		//$pdf->Cell(1,0,'');

		$com = $eventRow['comments'];
		$pdf->SetFont('Arial','',10);
		displayTextBox($com,$charPerLine);
	}

	if($eventRow['recommendations']) {
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','b',12);
		//$pdf->Cell(1,0,'');
		$pdf->Cell(0,0,'Recommendations');

		$pdf->SetY(newline());
		//$pdf->Cell(1,0,'');

		$com = $eventRow['recommendations'];
		$pdf->SetFont('Arial','',10);
		displayTextBox($com,$charPerLine);
	}

	if($dealer->isLuxury) {
		$preEvent = PreEventCall::where('eventID',$eventObj->id)->getOne();
		if($preEvent instanceof PreEventCall && $preEvent->comments) {
			$pdf->SetY(newline(10));
			$pdf->SetFont('Arial','b',12);
			//$pdf->Cell(1,0,'');
			$pdf->Cell(0,0,'Pre Event Meeting/Call');

			$pdf->SetY(newline());
			//$pdf->Cell(1,0,'');

			$com = $preEvent->comments;
			$pdf->SetFont('Arial','',10);
			displayTextBox($com,$charPerLine);
		}

		if($eventRow['postEventTrainingSummary']) {
			$pdf->SetY(newline(10));
			$pdf->SetFont('Arial','b',12);
			//$pdf->Cell(1,0,'');
			$pdf->Cell(0,0,'Trainer Summary');

			$pdf->SetY(newline());
			//$pdf->Cell(1,0,'');

			$com = $eventRow['postEventTrainingSummary'];
			$pdf->SetFont('Arial','',10);
			displayTextBox($com,$charPerLine);
		}

		if($eventRow['trainerImpact']) {
			$pdf->SetY(newline(10));
			$pdf->SetFont('Arial','b',12);
			//$pdf->Cell(1,0,'');
			$pdf->Cell(0,0,'Trainer Touchdown');

			$pdf->SetY(newline());
			//$pdf->Cell(1,0,'');

			$com = $eventRow['trainerImpact'];
			$pdf->SetFont('Arial','',10);
			displayTextBox($com,$charPerLine);
		}

		if($eventRow['salesrepTouchdown']) {

			$pdf->SetY(newline(10));
			$pdf->SetFont('Arial','b',12);
			//$pdf->Cell(1,0,'');
			$pdf->Cell(0,0,'Salesrep Touchdown');

			$pdf->SetY(newline());
			//$pdf->Cell(1,0,'');

			$com = $eventRow['salesrepTouchdown'];
			$pdf->SetFont('Arial','',10);
			displayTextBox($com,$charPerLine);
		}

		if($eventRow['brandTouchdown']) {

			$pdf->SetY(newline(10));
			$pdf->SetFont('Arial','b',12);
			//$pdf->Cell(1,0,'');
			$pdf->Cell(0,0,'Service Leads/Conquest Touchdown');

			$pdf->SetY(newline());
			//$pdf->Cell(1,0,'');

			$com = $eventRow['brandTouchdown'];
			$pdf->SetFont('Arial','',10);
			displayTextBox($com,$charPerLine);
		}
	}


	$pdf->SetFont('Arial','',8);
	$pdf->SetY(270);
	$pdf->Rect(5,265,205,1,'F');
	$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');
}

if(isset($_GET['sr'])) {
	$soldIndex = 0;
	if(count($soldLogArray) > 0){
		foreach($soldLogArray as $info) {
			for($i = 1;$i <= 4;$i++) {
				if($info['sold' . $i] == 'y') {
					if($soldIndex % 30 == 0) {
						$pdf->addPage('L','Letter');

						$cY = 5;
						$pdf->SetFont('Arial','B',18);

						$pdf->SetY(newline());
						$pdf->Cell(0,0,'EVENT SOLDS');

						$pdf->SetFont('Arial','B',12);
						$pdf->SetY(newline(10));
						$pdf->Cell(10,0,"");
						$pdf->Cell(40,0,"Salesperson");
						$pdf->Cell(60,0,"Customer");
						$pdf->Cell(40,0,"VIN");
						$pdf->Cell(25,0,"Stock #");
						$pdf->Cell(15,0,"N/U",0,0,'C');
						$pdf->Cell(0,0,"Vehicle");


						$pdf->SetFont('Arial','',8);
						$pdf->SetY(210);
						$pdf->Rect(5,265,205,1,'F');
						$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');


						$pdf->SetY(newline(3));
						hr(5);
						$pdf->SetY(newline(4));

						$pdf->SetFont('Arial','',9);
					}


					$soldIndex++;
					$pdf->Cell(10,0,number_format($soldIndex));
					$pdf->Cell(40,0,$info['salesperson']);
					$pdf->Cell(60,0,trim($info['firstName'] . ' ' . $info['lastName']));
					$pdf->Cell(40,0,$info['vin' . $i]);
					$pdf->Cell(25,0,$info['stock' . $i]);
					$pdf->Cell(15,0,($info['newUsed' . $i] == 'new' ? 'N' : ($info['newUsed' . $i] == 'used' ? 'U' : '')),0,0,'C');
					$pdf->Cell(0,0,trim($info['year' . $i] . ' ' . trim($info['make' . $i] . ' ' . $info['model' . $i])));

					$pdf->SetY(newline(3));
					hr(1);
					$pdf->SetY(newline(3));
				}
			}
		}
	}
	if(!isset($_GET['soldOnly'])) {


		if($dealer->isLuxury) {
			$soldIndex = 0;
			if(count($soldLogArray) > 0){
				foreach($soldLogArray as $info) {
					for($i = 1;$i <= 4;$i++) {
						if(!empty($info['tradeYear' . $i])) {
							if($soldIndex % 30 == 0) {
								$pdf->addPage('L','Letter');

								$cY = 5;
								$pdf->SetFont('Arial','B',18);

								$pdf->SetY(newline());
								$pdf->Cell(0,0,'TRADE INS');

								$pdf->SetFont('Arial','B',12);
								$pdf->SetY(newline(10));
								$pdf->Cell(10,0,"");
								$pdf->Cell(40,0,"Salesperson");
								$pdf->Cell(60,0,"Customer");
								$pdf->Cell(15,0,"N/U",0,0,'C');
								$pdf->Cell(50,0,"Sold Vehicle");
								$pdf->Cell(0,0,"Trade-In");


								$pdf->SetFont('Arial','',8);
								$pdf->SetY(210);
								$pdf->Rect(5,265,205,1,'F');
								$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');


								$pdf->SetY(newline(3));
								hr(5);
								$pdf->SetY(newline(4));

								$pdf->SetFont('Arial','',9);
							}


							$soldIndex++;
							$pdf->Cell(10,0,number_format($soldIndex));
							$pdf->Cell(40,0,$info['salesperson']);
							$pdf->Cell(60,0,trim($info['firstName'] . ' ' . $info['lastName']));
							$pdf->Cell(15,0,($info['newUsed' . $i] == 'new' ? 'N' : ($info['newUsed' . $i] == 'used' ? 'U' : '')),0,0,'C');
							$pdf->Cell(50,0,trim($info['year' . $i] . ' ' . trim($info['make' . $i] . ' ' . $info['model' . $i])));
							$pdf->Cell(0,0,trim($info['tradeYear' . $i] . ' ' . trim($info['tradeMake' . $i] . ' ' . $info['tradeModel' . $i])));

							$pdf->SetY(newline(3));
							hr(1);
							$pdf->SetY(newline(3));
						}
					}
				}
			}
		}

		$followUpIndex = 0;
		if(count($followUpLogArray) > 0){
			ksort($followUpLogArray);


			foreach($followUpLogArray as $info)
			{
				if($followUpIndex % 30 == 0)
				{
					$pdf->addPage('L','Letter');

					$cY = 5;
					$pdf->SetFont('Arial','B',18);

					$pdf->SetY(newline());
					$pdf->Cell(0,0,'FOLLOW UP');

					$pdf->SetFont('Arial','B',12);
					$pdf->SetY(newline(10));
					$pdf->Cell(40,0,"Salesperson");
					$pdf->Cell(50,0,"Customer");
					$pdf->Cell(20,0,"Main");
					$pdf->Cell(20,0,"Mobile");
					$pdf->Cell(60,0,"Email");
					$pdf->Cell(0,0,"Current Vehicle");


					$pdf->SetFont('Arial','',8);
					$pdf->SetY(210);
					$pdf->Rect(5,265,205,1,'F');
					$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');


					$pdf->SetY(newline(3));
					hr(5);
					$pdf->SetY(newline(4));

					$pdf->SetFont('Arial','',9);
				}


				$followUpIndex++;
				$pdf->Cell(40,0,$info['salesperson']);
				$pdf->Cell(50,0,'[' . strtoupper(substr($info['prospect'],0,1)) . '] ' . trim($info['firstName'] . ' ' . $info['lastName']));
				$pdf->Cell(20,0,$info['mainPhone']);
				$pdf->Cell(20,0,$info['mobilePhone']);
				$pdf->Cell(60,0,$info['email']);
				$pdf->Cell(0,0,trim($info['currentVehicleYear'] . ' ' . $info['currentVehicleModel']));

				$pdf->SetY(newline(3));
				hr(1);
				$pdf->SetY(newline(3));
			}
		}

		$salesrepSummaryIndex = 0;

		if(count($salesrepSummary) > 0){
			foreach($salesrepSummary as $sr => $info)
			{
				if($salesrepSummaryIndex % 38 == 0)
				{
					$pdf->addPage('P','Letter');

					$cY = 5;
					$pdf->SetFont('Arial','B',18);

					$pdf->SetY(newline());
					$pdf->Cell(0,0,'SALESPERSON SUMMARY');

					$pdf->SetFont('Arial','B',12);
					$pdf->SetY(newline(10));
					$pdf->Cell(40,0,"Salesperson");
					$pdf->Cell(40,0,"Appointments");
					$pdf->Cell(20,0,"Shows");
					$pdf->Cell(20,0,"Sold");


					$pdf->SetFont('Arial','',8);
					$pdf->SetY(270);
					$pdf->Rect(5,265,205,1,'F');
					$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');


					$pdf->SetY(newline(3));
					hr(5);
					$pdf->SetY(newline(4));

					$pdf->SetFont('Arial','',9);
				}


				$salesrepSummaryIndex++;
				$pdf->Cell(40,0,str_replace('zzz-','',$sr));
				$pdf->Cell(40,0,number_format($info['appt']));
				$pdf->Cell(20,0,number_format($info['show']));
				$pdf->Cell(20,0,number_format($info['sold']));

				$pdf->SetY(newline(3));
				hr(1);
				$pdf->SetY(newline(3));
			}
		}

		//IMAGE INCLUDE
		$imgList = EventTrainerImageFile::where('eventID',$eventRow['eventID'])->where('status',1)->get();

		if(count($imgList) > 0)
		{
			foreach($imgList as $key => $img)
			{
				if($key%3 == 0)
				{
					$pdf->addPage('P','Letter');
					$cY = 5;
					$pdf->SetFont('Arial','B',18);

					$pdf->SetY(newline());
					$pdf->Cell(0,0,'EVENT PICTURES');
				}

				$f = ARFileController::copyFileToTemp($img);
				$pdf->Image($f,10,(80 * ($key%3))+20,0,75);

				if($key%3 == 0) {
					$pdf->SetFont('Arial','',8);
					$pdf->SetY(270);
					$pdf->Rect(5,265,205,1,'F');
					$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');
				}
			}
		}
	}
}

$pdf->Output();
$pdf->Close();
?>