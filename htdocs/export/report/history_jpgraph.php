<?php
	include_once('jpgraph/src/jpgraph.php');
	include_once('jpgraph/src/jpgraph_bar.php');

// 
// Create some random data for the plot. We use the current time for the
// first X-position
//
$dataDate = array();
$dataAppt = array();
$dataShow = array();
$dataSold = array();


$dates = explode(',',$_GET['date']);
$appts = explode(',',$_GET['appt']);
$shows = explode(',',$_GET['show']);
$solds = explode(',',$_GET['sold']);

$totalAppts = 0;
$totalShows = 0;
$totalSolds = 0;
foreach($dates as $i => $num)
{
	if($i === 0) continue;
	
	$dataDate[] = strtotime($dates[$i]);
	$dataAppt[] = $appts[$i];
	$dataShow[] = $shows[$i];
	$dataSold[] = $solds[$i];
	$totalAppts += $appts[$i];
	$totalShows += $shows[$i];
	$totalSolds += $solds[$i];
}

$avgAppt = number_format($totalAppts/(count($dates)-1));
$avgShow = number_format($totalShows/(count($dates)-1));
$avgSold = number_format($totalSolds/(count($dates)-1));
 
$data1y = array($appts[0],$shows[0],$solds[0]);
$data2y = array($avgAppt,$avgShow,$avgSold);
 
// Create the graph. These two calls are always required
$graph = new Graph(600,375,'auto');
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels(array('Appointments','Shows','Deals'));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
// ...and add it to the graPH
$graph->Add($gbplot);


$b1plot->SetColor("darkgray");
$b1plot->SetFillColor("#777777");
$b1plot->SetFillGradient('AntiqueWhite2','AntiqueWhite4:0.8',GRAD_VERT);
$b1plot->SetLegend(date("M j, Y",strtotime($dates[0])) . ' Event');

$b2plot->SetColor("darkgray");
$b2plot->SetFillColor("#ea4516");
$b2plot->SetFillGradient('#ff7321','#a53110',GRAD_VERT);
$b2plot->SetLegend('Dealership Average');

//$graph->title->Set($totalAppts);

// Display the graph
$graph->SetMargin(30,0,10,0);
$graph->Stroke();
?>