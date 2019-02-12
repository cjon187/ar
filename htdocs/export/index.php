<?php
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');

	use Carbon\Carbon;
	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);

	$db = new ARDB();

	$export = strtolower($_GET['export']);
	$report = strtolower($_GET['report']);
	$hash = $_GET['hash'];


	if(!empty($export)) {
		switch($export) {
			case('invoice'):
				$invoice = Invoice::byHash($hash);

				if($invoice instanceof Invoice) {
					$ic = new InvoiceController();
					$ic->buildInvoicePDF($invoice);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('contract'):
				$contract = Contract::byHash($hash);

				if($contract instanceof Contract) {
					$cc = new ContractController();
					$cc->buildContractPDF($contract);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('msr'):
				$dealer = Dealer::byHash($hash);

				$dc = new DealerController();
				$dc->setDealerID($dealer->id);
				$dc->buildMSRReport($_GET['ps']);
				break;
			case('infosheet_private_sale'):
				$worksheet = Worksheet::byHash($hash);

				if($worksheet instanceof Worksheet) {
					$isc = new InfoSheetController();
					$isc->buildPrivateSaleInfoSheet($worksheet);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('infosheet_conquest'):
				$worksheet = Worksheet::byHash($hash);

				if($worksheet instanceof Worksheet) {
					$isc = new InfoSheetController();
					$isc->buildConquestInfoSheet($worksheet);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('snapshot'):

				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$sc = new SnapshotController();

					if(isset($_GET['snapshot_summary']))  	$sc->setSnapshotSection('snapshot_summary',$_GET['snapshot_summary']);
					if(isset($_GET['vehicle_brands'])) 		$sc->setSnapshotSection('vehicle_brands',$_GET['vehicle_brands']);
					if(isset($_GET['formula'])) 			$sc->setSnapshotSection('formula',$_GET['formula']);
					if(isset($_GET['customer'])) 			$sc->setSnapshotSection('customer',$_GET['customer']);
					if(isset($_GET['vehicle_offmakes'])) 	$sc->setSnapshotSection('vehicle_offmakes',$_GET['vehicle_offmakes']);
					if(isset($_GET['previous_events'])) 	$sc->setSnapshotSection('previous_events',$_GET['previous_events']);
					if(isset($_GET['communications'])) 		$sc->setSnapshotSection('communications',$_GET['communications']);
					if(isset($_GET['vehicle_models'])) 		$sc->setSnapshotSection('vehicle_models',$_GET['vehicle_models']);
					if(isset($_GET['customer_map'])) 		$sc->setSnapshotSection('customer_map',$_GET['customer_map']);
					if(isset($_GET['vehicle'])) 			$sc->setSnapshotSection('vehicle',$_GET['vehicle']);
					if(isset($_GET['finance_data'])) 		$sc->setSnapshotSection('finance_data',$_GET['finance_data']);
					if(isset($_GET['glossary'])) 			$sc->setSnapshotSection('glossary',$_GET['glossary']);
					if(isset($_GET['vehicle_years'])) 		$sc->setSnapshotSection('vehicle_years',$_GET['vehicle_years']);
					if(isset($_GET['opportunities'])) 		$sc->setSnapshotSection('opportunities',$_GET['opportunities']);


					$ids = explode('|',$_GET['snapshotID']);
					if(count($ids) > 1) {
						$html = $sc->getSnapshotsHTML($ids,isset($_GET['pdf']));
					} else {
						$html = $sc->getSnapshotHTML($dealer->id,$_GET['snapshotID'],isset($_GET['pdf']));
					}

					if(isset($_GET['pdf'])) {
						PdfBuilder::buildFromHTML($html,'Snapshot.pdf',[
							'margin-bottom'=> 5,
							'margin-left' => 5,
							'margin-right'=> 5,
							'margin-top'=> 5,
							'javascript-delay' => 1000
						]);
					} else {
						echo $html;
					}
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('subscription_change'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {

					$sql = '';
					if(isset($_GET['eventID']) && $_GET['eventID'] != "" && is_numeric($_GET['eventID'])){
						$sql = 'SELECT eventID FROM ps_events WHERE dealerID = '. $dealer->dealerID .' AND eventID = '. $_GET['eventID'] .' AND confirmed = confirmed AND salesTypeID IN(1,2,5,6,7,8)';
					}
					else if(isset($_GET['eventIDs']) && $_GET['eventIDs'] != ""){
						if(is_numeric($_GET['eventIDs'])){
							$sql = 'SELECT eventID FROM ps_events WHERE dealerID = '. $dealer->dealerID .' AND eventID = '. $_GET['eventIDs'] .' AND confirmed = confirmed AND salesTypeID IN(1,2,5,6,7,8)';
						}
						else if(is_array($eventIDs)){
							$eventIDs = unserialize(urldecode($_GET['eventIDs']));
							$sql = 'SELECT eventID FROM ps_events WHERE dealerID = '. $dealer->dealerID .' AND eventID IN('. implode(',',$eventIDs) .') AND confirmed = confirmed AND salesTypeID IN(1,2,5,6,7,8)';
						}
					}

					if($sql == ""){
						$sql = 'SELECT eventID FROM ps_events WHERE dealerID = '. $dealer->dealerID .' AND confirmed = confirmed AND salesTypeID IN(1,2,5,6,7,8)';
					}

					$results = $db->rawQuery($sql);
					if($results){
						foreach($results as $re){
							$eventIDs[] = $re['eventID'];
						}
					}

					$lec = new ListExportController();
					$opts = $lec->optListExport($eventIDs);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('game_plan'):
				$event = Event::byHash($hash);

				if($event instanceof Event) {
					$gpc = new GamePlanController();
					$gpc->generatePDF($event);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('pre_sale'):
				$event = Event::byHash($hash);

				if($event instanceof Event) {
					$psc = new PrivateSaleController($event);
					$psc->buildPreSaleSummaryPDF();
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('conquest'):
				$event = Event::byHash($hash);

				if($event instanceof Event) {

					$task = TaskConquests::byId($_GET['taskID']);
					if(empty($task) || $task->event->id != $event->id) {
						echo 'Invalid Task';
						exit;
					}


					$filename = $task->event->eventName . ' Conquest Postal Routes.zip';
					$randomName = ARFileController::tempFile('zip');
					$zip = new ZipArchive();

					if ($zip->open($randomName, ZipArchive::CREATE)!==TRUE) {
					    exit("cannot open <$randomName>\n");
					}


					$cc = new ConquestController($event->dealerID);					
					$tbl = $cc->getPostalRouteTbl($task->id);
					if(empty($tbl)) {
						echo 'Invalid Postal Routes';
						exit;
					}
					$distributions = explode(',',$task->postalRoutesDistributions);
					$routes = explode(',',$task->postalRoutes);

					$sql = 'SELECT * FROM ' . $tbl . ' WHERE Route in ("' . implode('","',$routes) . '") GROUP BY Route';
					$results = mysqli_query($db_data,$sql);

					$urban = '';
					$rural = '';

					while($re = mysqli_fetch_assoc($results)) {

						if(substr($re['Route'],1,1) == '0') {
							$type = 'rural';
						}
						else {
							$type = 'urban';
						}

						$row = array();
						$row[] = '"' . substr($re['Route'],6) . '"';
						$row[] = '"' . $re['Depot'] . '"';
						$row[] = (in_array('houses',$distributions) ? $re['Route_houses'] : 0);
						$row[] = (in_array('apartments',$distributions) ? $re['Route_apartments'] : 0);
						$row[] = (in_array('farms',$distributions) ? $re['Route_farms'] : 0);
						$row[] = (in_array('businesses',$distributions) ? $re['Route_businesses'] : 0);

						$$type .= implode(',',$row) . "\r\n";

					}


					$zip->addFromString($task->event->eventName . " Urban Postal Routes.txt", $urban);
					$zip->addFromString($task->event->eventName . " Rural Postal Routes.txt", $rural);
					$zip->close();

					header('Content-Type: application/zip');
					header('Content-disposition: attachment; filename="' . $filename . '"');
					header('Content-Length: ' . filesize($randomName));
					readfile($randomName);
					unlink($randomName);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('prizm'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$pc = new PrizmController();
					$pc->generatePDF($dealer,$_GET['province'],$_GET['sortBy'],$_GET['sortParam'],$_GET['sortTop'],$_GET['prizmCount'],$_GET['prizmIndex']);
				}
				break;
			case('text_in'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$tic = new TextInController();
					$tic->generatePDF($dealer);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('upgrade_magic'):
				$dealer = Dealer::byHash($hash);
				if(isset($_GET['eid'])) {
					$event = Event::byId($_GET['eid']);
					if(!$event instanceof Event || $event->dealerID != $dealer->id) {
						echo 'Invalid Event';
						exit;
					}

					if(isset($_GET['aid'])) {
						$apptLog = new AppointmentsLog($event);
						$apptLog = $apptLog->byId($_GET['aid']);

						if(!$apptLog instanceof AppointmentsLog) {
							echo 'Invalid Customer';
							exit;
						}
					}
				}


				$um = new UpgradeMagicController($dealer);

				if($_GET['appts']) {
					$customersData = [];
					$sql = 'SELECT * FROM ' . $event->apptTbl . ' WHERE eventID = ' . $event->id . ' AND appointmentTime IS NOT NULL and appointmentTime != ""';

					$results = $db->rawQuery($sql);
					if($results) {
						foreach($results as $customer) {

							$customerData = [];
							$customerData['event'] = $event;
							$customerData['customer'] = $customer;

							$currentVehicle = [];
							$currentVehicle['year'] = $customer['currentYear'];
							$currentVehicle['make'] = $customer['currentMake'];
							$currentVehicle['model'] = $customer['currentModel'];

							$nextVehicle = [];
							$nextVehicle['year'] = $customer['nextVehicleYear'];
							$nextVehicle['make'] = $customer['nextVehicleMake'];
							$nextVehicle['model'] = $customer['nextVehicleModel'];

							$acodes = $um->getComparisonAcodes($currentVehicle,$nextVehicle);

							$customerData['currentAcode'] = $acodes['currentAcode'];
							$customerData['compareAcode'] = $acodes['compareAcode'];

							$customersData[] = $customerData;
						}
					}

					$logData = [];
					$logData['staffID'] = $_GET['sid'];
					$logData['salesrepID'] = $_GET['srid'];
					$logData['dealerStaffID'] = $_GET['dsid'];
					$logData['eventID'] = $event->id;
					$logData['appointments'] = count($results);
					$log = new UpgradeMagicLog($logData);
					$log->save();

					$um->buildComparisonPDF($customersData);
				} else if(isset($_GET['apptH'])) {

					$apptReminder = AppointmentReminder::byHash($_GET['apptH']);
					if($apptReminder instanceof AppointmentReminder) {

						$customerData['event'] = $apptReminder->event;
						$apptLog = $apptReminder->getAppointmentLog();

						if(!$apptLog instanceof AppointmentsLog) {
							echo 'Invalid Customer';
							exit;
						}
						if($apptLog instanceof AppointmentsLog) {
							$customerData['apptLog'] = $apptLog;

							$currentVehicle = [];
							$currentVehicle['year'] = $apptLog->currentYear;
							$currentVehicle['make'] = $apptLog->currentMake;
							$currentVehicle['model'] = $apptLog->currentModel;

							$nextVehicle = [];
							$nextVehicle['year'] = $apptLog->nextVehicleYear;
							$nextVehicle['make'] = $apptLog->nextVehicleMake;
							$nextVehicle['model'] = $apptLog->nextVehicleModel;

							$acodes = $um->getComparisonAcodes($currentVehicle,$nextVehicle);

							$customerData['currentAcode'] = $acodes['currentAcode'];
							$customerData['compareAcode'] = $acodes['compareAcode'];
						}

						$logData = [];
						$logData['eventID'] = $event->id;
						$logData['appointmentID'] = $apptLog->id;
						$log = new UpgradeMagicLog($logData);
						$log->save();

						if(isset($_GET['html'])) {
							echo $um->getComparisonHTML([$customerData],false);
						} else {
							$um->buildComparisonPDF([$customerData]);
						}
					}
				} else {

					$customerData = $_GET;
					$customerData['event'] = $event;
					if($apptLog instanceof AppointmentsLog) {
						$customerData['customer'] = $apptLog->toArray();
					}

					$logData = [];
					$logData['staffID'] = $_GET['sid'];
					$logData['salesrepID'] = $_GET['srid'];
					$logData['dealerStaffID'] = $_GET['dsid'];
					$logData['eventID'] = $event->id;
					$logData['appointmentID'] = $_GET['aid'];
					$log = new UpgradeMagicLog($logData);
					$log->save();

					$um->buildComparisonPDF([$customerData]);
				}
				break;
			case('annual_plan'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$apc = new AnnualPlanController();
					$benchmarkOptions = [];
					$benchmarkOptions['nationID'] = $dealer->nationID;
					$benchmarkOptions['groupName'] = $dealer->country->name;
					if($dealer->hasOEM(OEM_FCA) && $dealer->countryID == COUNTRY_CA) {
						$benchmarkOptions['fixedInvitesPerEvent'] = true;
					}
					$apc->renderAnnualPlanExport([$dealer->id],$benchmarkOptions,'pdf');
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('private_sale_plan'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$pspc = new PrivateSalePlanController();
					$pspc->buildPrivateSalePlanPDF($dealer);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('private_sale_full_plan'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {
					$pspc = new PrivateSalePlanController();
					$pspc->buildPrivateSaleFullPlanPDF($dealer);
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('year_over_year'):
				$dealer = Dealer::byHash($hash);

				if($dealer instanceof Dealer) {

					if($_GET['dateStart']) {
						$dateStart = $_GET['dateStart'];
					}
					if($_GET['dateEnd']) {
						$dateEnd = $_GET['dateEnd'];
					}


					$yoy = new YearOverYearController();
					if(isset($_GET['html'])) {
						$yoy->buildHTML($dealer,$dateStart,$dateEnd,isset($_GET['full']));
					} else {
						$yoy->buildPDF($dealer,$dateStart,$dateEnd,isset($_GET['full']));
					}
				} else {
					http_response_code(404);
					exit;
				}
				break;
			case('trades_required'):
				$event = Event::byHash($hash);

				if($event instanceof Event) {
					$trc = new TradesRequiredController();
					$trc->generatePDF($event);
				}
				break;
			case('email_capture'):
				$event = Event::byHash($hash);

				$arc = new ArcController();
				$arc->buildEmailCaptureExport($event);
				break;
			default:
				http_response_code(404);
				exit;
		}
	}

?>