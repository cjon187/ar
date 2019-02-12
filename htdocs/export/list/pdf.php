<?php
include_once('arSession.php');
include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
//include_once('pdfUtils.php');
include_once('taskUtils.php');
use Philo\Blade\Blade;


//include_once('agreementUtils.php');
$db = new ARDB();

if(isset($_GET['key']))
{
	if($_GET['key'] != md5($_GET['eid']))
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
else if(isset($_POST['dealerID']))
{
	if(!checkEncrypt($_POST['dealerID'],$_POST['ekey'],'dealer'))
	{
		echo 'Invalid Key';
		exit;
	}
}
else
{
	checkPageAccess();
}

$dataArray = [];
$fileNameFlags = "";
$dataFromExporter = false;
if(!empty($_POST['contacts'])) {
	$dealer	= displayDealerInfo($_POST['dealerID']);
	$contacts = json_decode($_POST['contacts'],true);
	$dataArray = $contacts;
	$listCnt = $totalRows = count($contacts);
	$dataFromExporter = true;

} else {
	if($_GET['fromContainer'] != '')
	{
		$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_container_' . $_GET['fromContainer'] . '
		WHERE exclude is null OR exclude != "y"
		ORDER BY lastname';

		$dealer	= displayDealerInfo($_SESSION['container']['dealerID']);
	} else {
		$event = displayEventInfo($_GET['eid']);
		$dealer	= displayDealerInfo($event['dealerID']);

		if($_GET['taskID'] != '') {

			$task = displayTaskInfo($_GET['taskID'] ,'invitations');
		}
		else {
			$task = array_shift(getTasks($_GET['eid'],'invitations'));
		}


		$taskObj = TaskInvitations::byId($task['taskID']);
		$contactIDs = $taskObj->getContactIDs();

		$fileNameFlags .= '_TID'.$task['taskID'];

		if(isset($_GET['email_to_mail'])) {
			$fileNameFlags .= '_E2M';
		}

		if(empty($contactIDs)) {
			$contactIDs = [];
		}

		if($_GET['last18Months'] == 'yes')
		{
			$fileNameFlags .= '_Within18Months';
			$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_dealer_' . $task['dealerID'] . '_contacts

			WHERE contactID in (' . implode(',',$contactIDs) . ') AND ((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
			ORDER BY lastname';
		}
		else if($_GET['last18Months'] == 'no')
		{
			$fileNameFlags .= '_Over18Months';
			$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_dealer_' . $task['dealerID'] . '_contacts

			WHERE contactID in (' . implode(',',$contactIDs) . ') AND !((serviceDate is not null AND serviceDate != "" AND serviceDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '") OR (deliveryDate is not null AND deliveryDate != "" AND deliveryDate >= "' . date("Y-m-d",strtotime('now - 17 months')) . '"))
			ORDER BY lastname';
		}
		else if(isset($_GET['callable']))
		{
			$fileNameFlags .= '_Callable';
			$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_dealer_' . $task['dealerID'] . '_contacts

			WHERE contactID in (' . implode(',',$contactIDs) . ') AND (doNotCall = "no")
			HAVING (coalesce(mainPhone,"") != "" OR coalesce(mobilePhone,"") != "" OR coalesce(businessPhone,"") != "") AND (within18 = 1 OR coalesce(mainPhoneNDNC,"") = 1 OR coalesce(businessPhoneNDNC,"") = 1 OR coalesce(mobilePhoneNDNC,"") = 1)
			ORDER BY lastname';
		}
		else if(isset($_GET['notcallable']))
		{
			$fileNameFlags .= '_Unknown-DNC-NDNC';
			$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_dealer_' . $task['dealerID'] . '_contacts

			WHERE contactID in (' . implode(',',$contactIDs) . ')
			HAVING doNotCall = "yes" OR !((coalesce(mainPhone,"") != "" OR coalesce(mobilePhone,"") != "" OR coalesce(businessPhone,"") != "") AND (within18 = 1 OR coalesce(mainPhoneNDNC,"") = 1 OR coalesce(businessPhoneNDNC,"") = 1 OR coalesce(mobilePhoneNDNC,"") = 1 ))
			ORDER BY lastname';
		}
		else
		{
			$sql = 'SELECT *,coalesce(greatest(coalesce(serviceDate,""),coalesce(deliveryDate,"")) >= "' . date("Y-m-d",strtotime('now - 17 months')) . '",0) as within18 FROM ps_dealer_' . $task['dealerID'] . '_contacts
			WHERE contactID in (' . implode(',',$contactIDs) . ')
			ORDER BY lastname';
		}

		if(empty($contactIDs)) {
			$sql = 'SELECT NULL LIMIT 0,0';
		}
	}
	//print_r($sql);
	//print_r($task); exit;

	$results = mysqli_query($GLOBALS['db_data'],$sql);

	if(isset($_GET['showCount']))
	{
		echo mysqli_num_rows($results);
		exit;
	}
	$totalRows = mysqli_num_rows($results);
	$origcY = 25;


	if(isset($_GET['email_to_mail'])){
		while($contact = mysqli_fetch_assoc($results))
		{
			if($contact['assignedSalesman'] != "") $dataArray[$contact['assignedSalesman']][] = $contact;
			else $dataArray['orphans'][] = $contact;
		}
	}
	else{
		while($contact = mysqli_fetch_assoc($results))
		{
			if($contact['salesman'] != "") $dataArray[$contact['salesman']][] = $contact;
			else $dataArray['orphans'][] = $contact;
		}
	}
	//mysqli_data_seek($results,0);

	$listCnt = mysqli_num_rows($results);
}



uksort($dataArray, function ($a, $b)
{
    if ($a == 'orphans') return -1;
    else if ($b == 'orphans') return 1;
    else return ($a < $b) ? -1 : 1;
});

$fileSavePath = '';
if(!empty($_POST['fileName'])) {
	$fileName = $_POST['fileName'];
} else {
	$dealerObj = Dealer::ById($dealer['dealerID']);
	if($dealerObj instanceof Dealer){
		$fileName = ASCIIFold::removeAccents($dealerObj->urlslug).'-'. $listCnt . $fileNameFlags.'_SalesRepPhoneList';
	}
	else{
		$fileName = str_replace(" ","",$dealer['dealerName']).'-'. $listCnt . $fileNameFlags.'_SalesRepPhoneList';
	}
}

if($_GET['fromContainer'] != ""){
	//
}
else if(!empty($_POST['fileSavePath'])) {
	if($totalRows > 0){
		$fileSavePath = $_POST['fileSavePath'].'/'.$fileName;
	}
}
else if(isset($_GET['export']) && $_GET['export'] == 'yes'){
	if($totalRows > 0){
		$fileSavePath = $_GET['path'].$fileName;
	}
}


$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);
$bladeParams = [];
$bladeParams['dealer'] = Dealer::byId($dealer['dealerID']);
$bladeParams['filename'] = $fileName;
$bladeParams['data'] = $dataArray;

if(strlen($bladeParams['dealer']->dealerName) >= 35){
	$bladeParams['perPage'] = 10;
}
else{
	$bladeParams['perPage'] = 11;
}
$bladeParams['dataFromExporter'] = $dataFromExporter;
$html = $blade->view()->make('salesrepPhoneList.pdf',$bladeParams)->render();

$options = ['margin-bottom'=> 5,
			'margin-left' => 5,
			'margin-right'=> 5,
			'margin-top'=> 5];

if(empty($fileSavePath)) {
	PdfBuilder::buildFromHTML($html ,$fileName.'.pdf',$options, (isset($_GET['download']) ? true : false));
} else {
	$tmpPath = ARFileController::tempFile();
	$finalPath = $this->fileSavePath.'/'.$fileName.'.pdf';
	PdfBuilder::saveFromHTML($html,$fileSavePath,$options);
}

exit;
/*

//$count = 0;
$perPage = 9;
//$currentSalesrep = '';
//while($contact = mysqli_fetch_assoc($results))
foreach($dataArray as $salesman => $contactList)
{
	$page = 1;
	$salesrepCount = 0;
	foreach($contactList as $count => $contact)
	{
		$isAHeader = false;
		if($salesrepCount == 0 || ($salesrepCount%$perPage == 0))
		{

			if($salesrepCount != 0)
			{
				//$pdf->addPage('P','Letter');
				//$pdf->useTemplate($tplidx, 0, 0);
			}

			//$currentSalesrep = $salesman;
			$isAHeader = true;
			$pdf->addPage('P','Letter');
			//$pdf->useTemplate($tplidx, 0, 0);
			$pdf->SetAutoPageBreak('off');

			//if(file_exists('../../images/logo.png')) $imagePath = '../../images/logo.png';
			if(file_exists(AR_ROOT.'htdocs/images/logo.png')) $imagePath = AR_ROOT.'htdocs/images/logo.png';
			else $imagePath = 'images/logo.png';
			$pdf->Image($imagePath,5,8,75);


			$pdf->SetY(12);
			$pdf->SetFont('Arial','',15);
			$pdf->Cell(0,0,mb_strtoupper($dealer['dealerName'],'UTF-8') . ' PHONE LIST',0,0,'R');

			$pdf->SetY(23);
			$pdf->SetFont('Arial','b',20);
			if($dealer['dealerID'] == 11748){
				//$pdf->Cell(0,0,mb_convert_case(mb_strtolower(str_replace('Orphans', ' ', $salesman),'UTF-8'), MB_CASE_TITLE, "UTF-8"),0,0,'R');
			}
			else{
				$pdf->Cell(0,0,mb_convert_case(mb_strtolower($salesman,'UTF-8'), MB_CASE_TITLE, "UTF-8"),0,0,'R');
			}


			$pdf->SetX(0);
			$pdf->SetY($origcY);
			$cX=0;
			$cY=$origcY;

			$pdf->SetFillColor(0);
			$pdf->Rect(5,28,205,1,'F');
			$pdf->SetY(newline(10));
		}

		$pdf->SetX(5);
		if(isset($_GET['showCustomerCode']))
		{
			$pdf->SetFont('Arial','',11);
			$pdf->Cell(50,0,($contact['customerCode'] != "" ? '['.$contact['customerCode'] . ']' : ''));
		}
		$pdf->SetFont('Arial','B',14);



		$pdf->Cell(50,0,($contact['companyName'] != "" ? $contact['companyName'] . ' ' : '') . ($contact['lastname'] == "" || $contact['firstname'] == "" ? $contact['lastname'] . ($contact['salutation'] != "" ? $contact['salutation']. ' ' : '' ) .$contact['firstname'] : $contact['lastname'] . ', ' . ($contact['salutation'] != "" ? $contact['salutation']. ' ' : '' ) . $contact['firstname'] ) );
		//$pdf->Cell(50,0,(($contact['lastname'] == "" || $contact['firstname'] == "" ? $contact['lastname'] . ($contact['salutation'] != "" ? $contact['salutation']. ' ' : '' ) .$contact['firstname'] : $contact['lastname'] . ', ' . ($contact['salutation'] != "" ? $contact['salutation']. ' ' : '' ) . $contact['firstname'] ) );

		$pdf->SetY(newline(6));
		$pdf->SetFont('Arial','B',12);
		$pdf->SetX(6);
		$pdf->SetTextColor(0,0,0);
		$shortCell = 30;
		$longCell = 45;
		$showNumShortCell = 50;
		$showNumLongCell = 70;
		$cell = $shortCell;
		$showNumCell = $showNumShortCell;
		if(in_array($dealer['nationID'], array(NATION_AF, NATION_UK, NATION_AU, NATION_EU))){
			$cell = $longCell;
			$showNumCell = $showNumLongCell;
		}
		if($contact['mainPhone'] != "")
		{
			if(strtolower($contact['doNotCall']) == 'yes') $pdf->Cell($cell,0,'DNC');
			else if(!$dataFromExporter && $contact['within18'] == 0 && $contact['mainPhoneNDNC'] == '2') $pdf->Cell($longCell,0, 'NDNC - ' . (string) $contact['mainPhone']);
			else if(!$dataFromExporter && $contact['within18'] == 0 && isset($contact['mainPhoneNDNC']) && $contact['mainPhoneNDNC'] == '') $pdf->Cell($longCell,0, 'UKN - ' . (string) $contact['mainPhone']);
			else $pdf->Cell((is_numeric($contact['mainPhone']) ? $cell : $longCell),0, (string) $contact['mainPhone']);
		}

		$pdf->SetFont('Arial','',11);

		if($contact['mobilePhone'] != "")
		{
			if(strtolower($contact['doNotCall']) == 'yes') $pdf->Cell($cell,0,'DNC');
			else if(!$dataFromExporter && $contact['within18'] == 0 && $contact['mobilePhoneNDNC'] == '2') $pdf->Cell($longCell,0, 'NDNC - ' . (string) $contact['mobilePhone']);
			else if(!$dataFromExporter && $contact['within18'] == 0 && isset($contact['mobilePhoneNDNC']) && $contact['mobilePhoneNDNC'] == '') $pdf->Cell($longCell,0, 'UKN - ' . (string) $contact['mobilePhone']);
			else $pdf->Cell((is_numeric($contact['mobilePhone']) ? $cell : $longCell),0, (string) $contact['mobilePhone'] . ' - c');
		}

		if($contact['businessPhone'] != "")
		{
			if(strtolower($contact['doNotCall']) == 'yes') $pdf->Cell(30,0,'DNC');
			else if(!$dataFromExporter && $contact['within18'] == 0 && $contact['businessPhoneNDNC'] == '2') $pdf->Cell($longCell,0, 'NDNC - ' . (string) $contact['businessPhone']);
			else if(!$dataFromExporter && $contact['within18'] == 0 && isset($contact['businessPhoneNDNC']) && $contact['businessPhoneNDNC'] == '') $pdf->Cell($longCell,0, 'UKN - ' . (string) $contact['businessPhone']);
			else $pdf->Cell((is_numeric($contact['businessPhone']) ? $cell : $longCell),0, (string) $contact['businessPhone'] . ' - w');
		}

		$pdf->SetTextColor(0,0,0);

		$pdf->SetY(newline(6));
		$pdf->SetX(6);
		$pdf->SetFont('Arial','B',11);

		$pdf->Cell(50,0,($contact['noLongerOwn'] ? 'No Longer Own: ' : '') . $contact['year']. ' ' . $contact['description'] . ' ' . $contact['style'] . ' ' . $contact['fuel_type'] . ' ' . ($contact['def_engine_size'] == "" ? '' : $contact['def_engine_size'] . 'L') . ' ' . ($contact['def_engine_cylinders'] == "" ? '' : $contact['def_engine_cylinders'] . ' CYL') . ($contact['regNum'] == "" ? '' : ' Reg#' . $contact['regNum']));
		//$pdf->Cell(50,0,($contact['noLongerOwn'] ? 'No Longer Own: ' : '') . $contact['year'] . ' ' . $contact['description'] . ' ' . $contact['style'] . ' ' . $contact['fuel_type'] . ' ' . ($contact['def_engine_size'] == "" ? '' : $contact['def_engine_size'] . 'L') . ' ' . ($contact['def_engine_cylinders'] == "" ? '' : $contact['def_engine_cylinders'] . ' CYL') . ($contact['regNum'] == "" ? '' : ' Reg#' . $contact['regNum']));
		$pdf->SetY(newline(4));
		$pdf->SetX(6);
		$pdf->SetFont('Arial','',11);
		if(isset($_GET['manager'])) $pdf->Cell(50,0,($contact['monthlyPayment'] == "" ? '' : '$') . $contact['monthlyPayment'] . (($contact['monthlyPayment'] != "") && ($contact['rate'] != "") ? '@' : '') . $contact['rate'] . ($contact['rate'] == "" ? '':'%') . ($contact['price'] == "" ? '' : ' Price: $') . number_format($contact['price']) . ($contact['deliveryDate'] == "" ? '':'   Payment Date: ') . $contact['deliveryDate'] . ($contact['deliveryDate'] != "" && $contact['lastPaymentDate'] != ""  ? ' to ':'') . $contact['lastPaymentDate']);
		$pdf->Cell(50,0,'');
		$pdf->SetFillColor(100);
		$pdf->SetY(newline(4.5));
		$pdf->Rect(5,$cY,205,0.3,'F');

		$pdf->SetY(newline(5.8));



		if($isAHeader)
		{
			$pdf->SetFillColor(0);
			$pdf->SetFont('Arial','',8);
			$temp = $cY;
			$pdf->SetY(270);
			$pdf->Rect(5,265,205,1,'F');
			$pdf->Cell(0,0,'Copyright © ' . date('Y') . '. Absolute Results Productions Ltd. All rights reserved.',0,0,'C');

			$pdf->SetX(0);
			$pdf->SetY(270);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(0,0,'Exported: ' . date("M Y"),0,0,'L');
			$pdf->Cell(0,0,'PG ' . $page . '/' . ceil(count($contactList)/$perPage),0,0,'R');

			$pdf->SetY($temp);
			$page++;
		}




		$salesrepCount++;
	}
}

if(!empty($_POST['fileName'])) {
	$fileName = $_POST['fileName'];
} else {
	$fileName = str_replace(" ","",$dealer['dealerName']).'-'. $listCnt . $fileNameFlags.'_SalesRepPhoneList.pdf';
}


if($_GET['fromContainer'] != ""){
	$pdf->Output($fileName,'D');
}
else if(!empty($_POST['fileSavePath'])) {
	if($totalRows > 0){
		$pdf->Output($_POST['fileSavePath'].'/'.$fileName,'F');
	}
}
else if(isset($_GET['export']) && $_GET['export'] == 'yes'){
	if($totalRows > 0){
		$pdf->Output($_GET['path'].$fileName,'F');
	}
}
else if(isset($_GET['download']) && $event['saleStartDate'] != ""){
	$pdf->Output($fileName,'D');
	exit;
}
else {
	$pdf->Output();
}

$pdf->Close();
*/
?>