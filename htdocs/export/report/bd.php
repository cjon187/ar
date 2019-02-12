<?php

	$is_b = true;

	include_once('loginUtils.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('pdfUtils.php');
	include_once('salesCallsUtils.php');

	if(isset($_GET['key'])) {
		if($_GET['key'] != "9C941INMK6" && md5($_GET['did']) != $_GET['key']) {
			echo 'Invalid Key';
			exit;
		}
	} else if(isset($_GET['ekey'])) {
		if(!checkEncrypt($_GET['did'],$_GET['ekey'],'dealer')) {
			echo 'Invalid Key';
			exit;
		}
	} else {
		checkPageAccess();
	}
	
	$sql = "SELECT * FROM ps_salescalls s, ps_staff s ";
	if (isset($_GET['did'])) {
		$dealer = displayDealerInfo($_GET['did']);
		$sql .= 'WHERE dealerID = ' . $dealer['dealerID']; ;
	} else if (isset($_GET['sid'])) {
		$staff = displayStaffInfo($_GET['sid']);
		$sql .= 'WHERE staffID = ' . $staff['staffID']; ;
	}
	$sql .= ' AND call_time <= "'.date("Y-m-d").'" AND s.staffID = s.staffID ';
	$sql .= ' ORDER BY staffID, dealerID, call_time DESC ';
	
	$results = mysqli_query($db_data,$sql);	
	if(mysqli_num_rows($results) == 0) {
		echo 'No information available yet.';
		exit;
	}

	// Get the rejections 
	$salescalls_rejections = getSalesCallRejections();
		/*

		CREATE THE HTML

	*/

	echo "<h1>Absolute Results Productions Ltd. - Sales Calls</h1>";
	if (isset($_GET['did'])) {
		echo "<h2>Dealership - ".$dealer['dealerName']."</h2>";
	} else {		
		echo "<h2>Staff - ".$staff['name']."</h2>";
	}	

	while($re = mysqli_fetch_assoc($results)) {
		$dealer = displayDealerInfo($re['dealerID']);
		echo "<hr />";		
		echo "<strong>Dealership: </strong>".$dealer['dealerName']."<br>";	
		echo "<strong>Contact: </strong> ".$re['contact']."<br>";
		echo "<strong>Call Time: </strong>".$re['call_time']."<br>";		
		echo "<strong>Call Result: </strong>".ucfirst(str_replace('_',' ',$re['booked_event']))."<br>";
		if ($re['booked_event'] == "rejected") {			
			$rreasons = explode(',',$re['reject_reasons']);
			foreach ($rreasons as $key => $value) {
				echo "<span class='label label-info'>".$salescalls_rejections[$value]."</span>";
			}			
		}
		echo "<strong>Notes: </strong>".$re['notes']."<br>";	
	}


	
	/*$origY = $cY;
	while($re = mysqli_fetch_assoc($results))
	{	
		$pdf->addPage('P','Letter');
		$pdf->Image('https://ar.absoluteresults.com/images/logo.png',10,8,50);
		$pdf->SetX(0);
		$pdf->Rect(5,28,205,1,'F');	
		
		$cY = $origY;
		
		$cY -= 10;
		$pdf->SetY(newline(0));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0,$dealer['dealerName'] . ' - Dealer Visits' ,0,0,'R');
		$pdf->SetY(newline());
		$pdf->Cell(0,0,date("F j, Y",strtotime($re['date'])) ,0,0,'R');
		
		$staff = displayStaffInfo($re['staffID']);
		$pdf->SetY(newline(20));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Staff');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$staff['name']);
		
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Dealership');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$dealer['dealerName']);
		
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Contact');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$re['contact']);
		
		$pdf->SetY(newline());
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(30,0,'Next Visit');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,0,$re['nextVisitDate']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Portal Reviewed');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$re['portalReviewed']);
		$pdf->SetY(newline(8));
		displayTextBox($re['portalComments']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Marketing Plan');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$re['markingPlanReviewed']);
		$pdf->SetY(newline(8));
		displayTextBox($re['markingPlanComments']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Private Sale');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$re['privateSaleReviewed']);
		$pdf->SetY(newline(8));
		displayTextBox($re['privateSaleComments']);
		
		$pdf->SetY(newline(10));
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(45,0,'Comments');
		$pdf->SetFont('Arial','',12);
		$pdf->SetY(newline(8));
		displayTextBox($re['comments']);
				
		addFooter();
	}	
	
	if(!isset($_GET['noOutput']))
	{
		$pdf->Output();	
		$pdf->Close();
	}*/
?>