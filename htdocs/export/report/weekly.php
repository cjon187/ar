<?php


$dateStart = date("Y-m-d",strtotime("now - " . ((date("N")-1)+8) . " days"));
$dateEnd = date("Y-m-d",strtotime($dateStart . " + 7 days"));

/*$dateStart = date("Y-m-d",strtotime("2015-10-11"));
$dateEnd = date("Y-m-d",strtotime("2015-10-18"));*/

include_once('mysqliUtils.php');
include_once('statsUtils.php');
include_once('taskUtils.php');
include_once('trackbackUtils.php');

include_once('classes/LogicalEventFilters.class.php');
include_once('db/ARDB.php');
$db = new ARDB();

$lef = new LogicalEventFilters($db, $dateStart, $dateEnd);
$lef->setSaleTypeIDs(array(5));
$lef->setConfirmed("confirmed");

$areas = array(
			array("wbc", LogicalDealerFilters::CA_ALL_W),
			array("ebc", LogicalDealerFilters::CA_ALL_E),
			array("qbc", LogicalDealerFilters::CA_ALL_Q),
			array("us", LogicalDealerFilters::ALL_USA),
			array("uk", LogicalDealerFilters::ALL_UK),
			array("fr-be", LogicalDealerFilters::EU_FR_BE),
			array("au", LogicalDealerFilters::ALL_AU),
			array("rsa", LogicalDealerFilters::AF_RSA)
		);

$events = array();
$stats = array();

foreach($areas as $area){
	//print_r2($area);
	$results = $lef->getEventIDs($area[1]);
	$stats[$area[0]] = displayStats($results);
	//$stats[$area[0]] = displayStats($results, array('oc'));
	if(in_array($area[1], array(LogicalDealerFilters::CA_ALL_W, LogicalDealerFilters::CA_ALL_E,LogicalDealerFilters::CA_ALL_Q,LogicalDealerFilters::ALL_USA,LogicalDealerFilters::ALL_UK))){
		//$stats[$area[0]]['conquestEvents'] = getTasks($results,'conquests');
		foreach($results as $event){
			$events[$event] = $event;
		}
		//$events = array_merge($events, $results);
	}
}
//print_r2($stats);
$conquests = getTasks($events,'conquests');

//print_r2($stats);

/*foreach($stats as $area => $areaEvent)
{
	print_r2($areaEvent);
}*/


//$areas = array();
//$areas['wbc']['sql'] = '(province IN ("BC","AB","SK","MB","YT","NT"))';
//$areas['ebc']['sql'] = '(province IN ("ON","NB","NS","NL","PE"))';
//$areas['qbc']['sql'] = '(province IN ("QC"))';
//$areas['usa']['sql'] = '(nation = "us")';
//$areas['uk']['sql'] = '(nation = "uk")';

//$events = array();
//$stats = array();
/*foreach($areas as $area => $info)
{
	$eventsArray = array();
	$sql = 'SELECT * FROM
			(SELECT * FROM ps_events WHERE salesTypeID = 5 AND confirmed = "confirmed" AND saleStartDate >= "' . $dateStart . '" AND saleStartDate <= "' . $dateEnd . '") as a1
			INNER JOIN
			(SELECT * FROM ps_dealers WHERE ' . $info['sql'] . ') as a2
			USING
			(dealerID)';

	$results = mysqli_query($db_data,$sql);
	while($re = mysqli_fetch_assoc($results))
	{
		$eventsArray[] = $re;
		$events[] = $re['eventID'];
	}

	$stats[$area] = displayEventsStats($eventsArray,array('oc'));
}*/



function bestEventSort($a,$b)
{
	$avg_a = ($a['salesrepCount'] > 0 ? $a['sold'] / $a['salesrepCount'] : -1);
	$avg_b = ($b['salesrepCount'] > 0 ? $b['sold'] / $b['salesrepCount'] : -1);

	if($avg_a == $avg_b) return 0;
	else if($avg_a < $avg_b) return 1;
	else return -1;
}

function worstEventSort($a,$b)
{
	$avg_a = ($a['salesrepCount'] > 0 ? $a['sold'] / $a['salesrepCount'] : -1);
	$avg_b = ($b['salesrepCount'] > 0 ? $b['sold'] / $b['salesrepCount'] : -1);

	if($avg_a == $avg_b) return 0;
	else if($avg_a > $avg_b) return 1;
	else return -1;
}

include_once('pdfUtils.php');

$pdf->addPage('P','Letter');
if(file_exists('../../images/logo_small.png')) $imagePath = '../../images/logo_small.png';
else $imagePath = 'images/logo_small.png';
$pdf->Image($imagePath,10,8,50);


$origY = $cY;

$cY -= 15;
$pdf->SetY(newline(0));
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,0,date("M j, Y",strtotime($dateStart)) . date(" - M j, Y",strtotime($dateEnd)),0,0,'R');
$pdf->SetY(newline(5));
$pdf->Cell(0,0,'Private Sales',0,0,'R');

$count = 0;
$index = 0;
foreach($stats as $area => $stat)
{


	if($stat['totals']['eventCount'] > 0)
	{
		if($count%5 == 0 && $count > 0){
			addFooter();
			$pdf->addPage('P','Letter');
			$index = 0;
		}

		$catY = $origY + ($index * 48);
		$cY = $catY;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',17);
		$pdf->SetFillColor(255,0,0);
		$pdf->Cell(0,13,strtoupper($area),0,0,'L',true);

		$xOffset = 35;
		$cY = $catY;
		$pdf->SetY(newline(7));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(22,0,'Total Events: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$stat['totals']['eventCount']);

		$xOffset += 35;
		$cY = $catY;
		$pdf->SetY(newline());
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(20,0,'Total Appts: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$stat['totals']['appt']);
		$pdf->SetY(newline(4));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(20,0,'Avg. Appts: ');
		$pdf->SetFont('Arial','b',10);
		if($stat['totals']['salesrepCount']) {
			$pdf->Cell(7,0,number_format($stat['totals']['appt'] / $stat['totals']['salesrepCount'],2));
		}
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(5,0,' / rep');

		$xOffset += 45;
		$cY = $catY;
		$pdf->SetY(newline());
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(23,0,'Total Shows: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$stat['totals']['show']);
		$pdf->SetY(newline(4));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(23,0,'Avg. Shows: ');
		$pdf->SetFont('Arial','b',10);
		if($stat['totals']['salesrepCount']) {
			$pdf->Cell(7,0,number_format($stat['totals']['show'] / $stat['totals']['salesrepCount'],2));
		}
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(5,0,' / rep');

		$xOffset += 45;
		$cY = $catY;
		$pdf->SetY(newline());
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(18,0,'Total Sold: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$stat['totals']['sold']);
		$pdf->SetY(newline(4));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(18,0,'Avg. Sold: ');
		$pdf->SetFont('Arial','b',10);
		if($stat['totals']['salesrepCount']) {
			$pdf->Cell(7,0,number_format($stat['totals']['sold'] / $stat['totals']['salesrepCount'],2));
		}
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(5,0,' / rep');

		$types = array('best','worst');
		foreach($types as $j => $type)
		{
			$cY = $catY;
			$xOffset = 10 + ($j * 100);
			$pdf->SetY(newline(18));
			$pdf->SetX($xOffset);

			$pdf->SetFont('Arial','bi',9);
			$pdf->Cell(35,0,strtoupper($type) . ' EVENTS');
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(8,0,'reps',0,0,'C');
			$pdf->Cell(8,0,'appt',0,0,'C');
			$pdf->Cell(8,0,'show',0,0,'C');
			$pdf->Cell(8,0,'sold',0,0,'C');

			uasort($stat,$type.'EventSort');
			$i = 0;
			foreach($stat as $eventID => $eventStat)
			{
				if($eventID == "totals") continue;
				$i++;
				$pdf->SetY(newline());
				$pdf->SetX($xOffset);
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(35,0,substr(Event::ById($eventID)->dealer->dealerName,0,20));
				$pdf->Cell(8,0,number_format($eventStat['salesrepCount']),0,0,'C');
				$pdf->Cell(8,0,number_format($eventStat['appt']),0,0,'C');
				$pdf->Cell(8,0,number_format($eventStat['show']),0,0,'C');
				$pdf->Cell(8,0,number_format($eventStat['sold']),0,0,'C');
				$pdf->SetFont('Arial','b',9);
				$pdf->Cell(6.5,0,($eventStat['salesrepCount'] > 0 ? number_format($eventStat['sold'] / $eventStat['salesrepCount'],2) : 'N/A' ) ) ;
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(5,0,' / rep');
				if($i >= 5) break;
			}
		}

		$index++;

		$count++;




	}
	/*else
	{
		$pdf->SetY(newline(18));
		$pdf->SetFont('Arial','i',9);
		$pdf->Cell(40,0,'No Events');
	}*/

	//$index++;




}

addFooter();


$cY = 25;
$pdf->addPage('P','Letter');
if(file_exists('../../images/logo_small.png')) $imagePath = '../../images/logo_small.png';
else $imagePath = 'images/logo_small.png';
$pdf->Image($imagePath,10,8,50);


$origY = $cY;

$cY -= 13;
$pdf->SetY(newline(0));
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,0,date("M j, Y",strtotime($dateStart)) . date(" - M j, Y",strtotime($dateEnd)),0,0,'R');
$pdf->SetY(newline(5));
$pdf->Cell(0,0,'Conquest',0,0,'R');



$index = 0;
foreach($stats as $area => $areaEvent)
{
	if(in_array($area, array('wbc', 'ebc', 'qbc', 'us', 'uk'))){
		$catY = $origY + ($index * 40);
		$cY = $catY;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',17);
		$pdf->SetFillColor(0,170,255);
		$pdf->Cell(0,13,strtoupper($area),0,0,'L',true);

		$conquestEvents = 0;
		$conquestCount = 0;
		$stat['trackback'] = array();

		if(count($areaEvent) > 0)
		{
			foreach($areaEvent as $eventID => $eventStat)
			{
				if(is_array($conquests[$eventID]) && count($conquests[$eventID])) {
					$conquestTask = array_shift($conquests[$eventID]);
					if($conquestTask['taskID'] != "")
					{
						$conquestEvents++;
						$conquestCount += taskMailed($conquestTask);


						if($conquestTask['postalRoutes'] != "")
						{
							$tb = getTrackBackInfo($eventID);
							$stat['trackback']['events'][$eventID] = $tb;
							foreach($tb as $key => $val) $stat['trackback'][$key] += $val;
							$stat['trackback']['eventCount']++;
						}

					}
				}
			}
		}

		$xOffset = 35;
		$cY = $catY;
		$pdf->SetY(newline(7));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(22,0,'Total Events: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$stats[$area]['totals']['eventCount']);

		$xOffset += 35;
		$cY = $catY;
		$pdf->SetY(newline());
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(22,0,'Conquests: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,$conquestEvents);
		$pdf->SetY(newline(4));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(22,0,'Conquest %: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(7,0,($stats[$area]['totals']['eventCount'] > 0 ? number_format(100* $conquestEvents / $stats[$area]['totals']['eventCount']). '%' : 'N/A'));

		$xOffset += 45;
		$cY = $catY;
		$pdf->SetY(newline());
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(21,0,'# Mailed: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,number_format($conquestCount));
		$pdf->SetY(newline(4));
		$pdf->SetX($xOffset);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(21,0,'Avg. Mailed: ');
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell(10,0,($conquestEvents != 0 ? number_format($conquestCount / $conquestEvents) : "N/A"));
		$pdf->SetFont('Arial','bi',9);

		if($stat['trackback']['eventCount'] != '')
		{
			$pdf->SetY(newline(8));
			$pdf->Cell(20,0,'TrackBack');
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(18,0,'Private Sale',0,0,'C');
			$pdf->Cell(18,0,'Conquested',0,0,'C');
			$pdf->Cell(18,0,'Percentage',0,0,'C');

			$pdf->SetY(newline(4));
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(20,0,'Web Leads');
			$pdf->Cell(18,0,$stat['trackback']['totalWeb'],0,0,'C');
			$pdf->Cell(18,0,$stat['trackback']['web'],0,0,'C');
			$pdf->SetFont('Arial','b',9);
			$pdf->Cell(18,0,number_format(100*$stat['trackback']['web'] / $stat['trackback']['totalWeb'],1) . '%',0,0,'C');

			$pdf->SetY(newline(4));
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(20,0,'Show');
			$pdf->Cell(18,0,$stat['trackback']['totalShow'],0,0,'C');
			$pdf->Cell(18,0,$stat['trackback']['show'],0,0,'C');
			$pdf->SetFont('Arial','b',9);
			$pdf->Cell(18,0,number_format(100*$stat['trackback']['show'] / $stat['trackback']['totalShow'],1) . '%',0,0,'C');

			$pdf->SetY(newline(4));
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(20,0,'Sold');
			$pdf->Cell(18,0,$stat['trackback']['totalSold'],0,0,'C');
			$pdf->Cell(18,0,$stat['trackback']['sold'],0,0,'C');
			$pdf->SetFont('Arial','b',9);
			$pdf->Cell(18,0,number_format(100*$stat['trackback']['sold'] / $stat['trackback']['totalSold'],1) . '%',0,0,'C');

			$pdf->SetY(newline(5));
			$pdf->SetTextColor(255,0,0);
			$pdf->SetFont('Arial','i',8);
			$pdf->Cell(30,0,'* based on ' . $stat['trackback']['eventCount'] . ' events with TrackBack information');
			$pdf->SetTextColor(0,0,0);
		}
		else
		{
			$pdf->SetY(newline(8));
			$pdf->SetFont('Arial','i',9);
			$pdf->Cell(40,0,'No events with TrackBack information available');
		}
		$index++;
	}
}

addFooter();

$pdf->Output();

$pdf->Close();

?>