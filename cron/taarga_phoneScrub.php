<?php
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');
	include_once('agreementUtils.php');
	include_once('displayUtils.php');
	include_once "Spreadsheet/Excel/Writer.php";
	require_once 'ExcelReader/reader.php';

	$db = new ARDB();
	echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;

	/*set_include_path(get_include_path() . PATH_SEPARATOR . AR_ROOT.'utils/sftp');
	include('Net/SFTP.php');*/

	$sql = 'UPDATE ps_dealers SET lockedBy = null, lockedAt = null WHERE lockedBy=242';
	mysqli_query($db_data,$sql);

	$sftp = TaargaPhoneScrubber::getSFTPInstance();

	$tblArrays = array();

	$todoList = $sftp->nlist('NA/complete');

	foreach($todoList as $file)
	{
		if(!in_array($file,array('.','..'))) $tblArrays['NA'][] = $file;
	}

	$todoList = $sftp->nlist('UK/complete');

	foreach($todoList as $file)
	{
		if(!in_array($file,array('.','..'))) $tblArrays['UK'][] = $file;
	}

	if(count($tblArrays) == 0)
	{
		echo 'No entries found.';
		echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
		exit;
	}




	foreach($tblArrays as $folder => $files) {
		foreach($files as $sftp_file) {

			$str = '';

			$file = explode('_',$sftp_file);
			$dealerID = $file[0];

			if($dealerID == 'DRIVE') {
				$isDrive = true;
			} else {
				$isDrive = false;
				$dealerInfo = displayDealerInfo($dealerID);
			}

			$_SESSION['login']['staffID'] = 242;

			if(!$isDrive && $dealerInfo['dealerName'] == '')
			{
				$str = '* ' . $dealerInfo['dealerName'] . ' Database not found.';

				$log = new TaargaPhoneScrubLog();
				$log->dealerID = null;
				$log->script = 'Taarga Phone Scrub Failed';
				$log->log = $sftp_file . ' - ' . $str;
				$log->runtime = date("Y-m-d H:i:s");
				$log->save();

			}
			else if(!$isDrive && !lockDatabase($dealerID,242))
			{
				$lockedBy = displayStaffInfo($dealerInfo['lockedBy']);
				$str = '* This ' . $dealerInfo['dealerName'] . ' database is locked by ' .  $lockedBy['name'] . '.';

				$log = new TaargaPhoneScrubLog();
				$log->dealerID = $dealerID;
				$log->script = 'Taarga Phone Scrub Failed';
				$log->log = $sftp_file . ' - ' . $str;
				$log->runtime = date("Y-m-d H:i:s");
				$log->save();
			}
			else
			{

				$dealer = Dealer::byId($dealerID);
				$filePath = 'complete/'.$sftp_file;

				$local_filePath = ARFileController::tempFile();
				$sftp->get($folder . '/' . $filePath,$local_filePath);
				$ext = array_pop(explode('.',$filePath));

				if(!$isDrive) {
					backupDB($dealerID);
				}

				$mobilePhones = array();
				$mainPhones = array();
				$nis = array();
				//$ndnc = array();
				$dnc = array();


				/*
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');

				$data->read($local_filePath);
				*/

				$objPHPExcel = PHPExcel_IOFactory::load($local_filePath);

				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

				for ($i = 2; $i <= count($sheetData); $i++) {

					if($sheetData[$i]['A'] != "") $mobilePhones[] = $sheetData[$i]['A'];
					if($sheetData[$i]['B'] != "") $mainPhones[] = $sheetData[$i]['B'];
					if($sheetData[$i]['C'] != "") $nis[] = $sheetData[$i]['C'];
					/*
					if($sheetData[$i][4] != "") $ndnc[] = $sheetData[$i][4];
					*/
					if($sheetData[$i]['E'] != "") $dnc[] = $sheetData[$i]['E'];
				}

				if($isDrive) {
					if(!empty($mobilePhones)) {
						$db->rawQuery('UPDATE phone_numbers SET isCellular = 1 WHERE phoneNumber in ("' . implode('","',$mobilePhones) . '")');
					}
					if(!empty($mainPhones)) {
						$db->rawQuery('UPDATE phone_numbers SET isCellular = 0 WHERE phoneNumber in ("' . implode('","',$mainPhones) . '")');
					}
					if(!empty($nis)) {
						$db->rawQuery('UPDATE phone_numbers SET isNIS = 1 WHERE phoneNumber in ("' . implode('","',$nis) . '")');
					}
				/*
					if(!empty($ndnc)) {
						$db->rawQuery('UPDATE phone_numbers SET isNDNC = 1 WHERE phoneNumber in ("' . implode('","',$ndnc) . '")');
					}
				*/
					$str = 'DRIVE Phones Updated';
					$dealerID = '0';

				} else {
					$sql1 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mobilePhone = mainPhone,mainPhone = null,mobilePhoneNDNC = mainPhoneNDNC,mainPhoneNDNC = null WHERE (mobilePhone is null or mobilePhone = "") AND mainPhone in ("' . implode('","',$mobilePhones) . '")';
					$sql2 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mobilePhone = businessPhone,businessPhone = null,mobilePhoneNDNC = businessPhoneNDNC,businessPhoneNDNC = null WHERE (mobilePhone is null or mobilePhone = "") AND businessPhone in ("' . implode('","',$mobilePhones) . '")';
					$sql3 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mainPhone = mobilePhone,mobilePhone = null,mainPhoneNDNC = mobilePhoneNDNC,mobilePhoneNDNC = null WHERE mobilePhone in ("' . implode('","',$mainPhones) . '")';
					$sql4 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mainPhone = null,mainPhoneNDNC = null WHERE mainPhone = "" OR mainPhone in ("' . implode('","',$nis) . '")';
					$sql5 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET businessPhone = null,businessPhoneNDNC = null WHERE businessPhone = ""  OR businessPhone in ("' . implode('","',$nis) . '")';
					$sql6 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mobilePhone = null,mobilePhoneNDNC = null WHERE mobilePhone = "" OR mobilePhone in ("' . implode('","',$nis) . '")';
					/*
					$sql7 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mainPhoneNDNC = "yes" WHERE (mainPhone is not null AND mainPhone != "") AND mainPhone in ("' . implode('","',$ndnc) . '")';
					$sql8 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET businessPhoneNDNC = "yes" WHERE (businessPhone is not null AND businessPhone != "") AND businessPhone in ("' . implode('","',$ndnc) . '")';
					$sql9 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET mobilePhoneNDNC = "yes" WHERE (mobilePhone is not null AND mobilePhone != "") AND mobilePhone in ("' . implode('","',$ndnc) . '")';
					*/
					$sql10 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET doNotText = "yes",doNotEmail = "yes" WHERE (mainPhone is not null AND mainPhone != "") AND mainPhone in ("' . implode('","',$dnc) . '")';
					$sql11 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET doNotText = "yes",doNotEmail = "yes" WHERE (businessPhone is not null AND businessPhone != "") AND businessPhone in ("' . implode('","',$dnc) . '")';
					$sql12 = 'UPDATE ps_dealer_' . $dealerID . '_contacts SET doNotText = "yes",doNotEmail = "yes" WHERE (mobilePhone is not null AND mobilePhone != "") AND mobilePhone in ("' . implode('","',$dnc) . '")';


					if(count($mobilePhones) > 0) $str .= (mysqli_query($db_data,$sql1) ? 'Success' : 'Failed') . ' ' . $sql1 . '<br>';
					if(count($mobilePhones) > 0) $str .= (mysqli_query($db_data,$sql2) ? 'Success' : 'Failed') . ' ' . $sql2 .  '<br>';
					if(count($mainPhones) > 0) $str .= (mysqli_query($db_data,$sql3) ? 'Success' : 'Failed') . ' ' . $sql3 .  '<br>';
					if(count($nis) > 0) $str .= (mysqli_query($db_data,$sql4) ? 'Success' : 'Failed') . ' ' . $sql4 .  '<br>';
					if(count($nis) > 0) $str .= (mysqli_query($db_data,$sql5) ? 'Success' : 'Failed') . ' ' . $sql5 .  '<br>';
					if(count($nis) > 0) $str .= (mysqli_query($db_data,$sql6) ? 'Success' : 'Failed') . ' ' . $sql6 .  '<br>';
					/*if(count($ndnc) > 0) $str .= (mysqli_query($db_data,$sql7) ? 'Success' : 'Failed') . ' ' . $sql7 .  '<br>';
					if(count($ndnc) > 0) $str .= (mysqli_query($db_data,$sql8) ? 'Success' : 'Failed') . ' ' . $sql8 .  '<br>';
					if(count($ndnc) > 0) $str .= (mysqli_query($db_data,$sql9) ? 'Success' : 'Failed') . ' ' . $sql9 .  '<br>';*/
					if(count($dnc) > 0) $str .= (mysqli_query($db_data,$sql10) ? 'Success' : 'Failed') . ' ' . $sql10 .  '<br>';
					if(count($dnc) > 0) $str .= (mysqli_query($db_data,$sql11) ? 'Success' : 'Failed') . ' ' . $sql11 .  '<br>';
					if(count($dnc) > 0) $str .= (mysqli_query($db_data,$sql12) ? 'Success' : 'Failed') . ' ' . $sql12 .  '<br>';

					$cc = new DealerContactsController($dealerID);
					$cc->updateDealerContactFlags();

					unlockDatabase($dealerID,242);
					echo $sftp_file . ' Completed' . PHP_EOL;
				}

				$sftp->delete($folder . '/' . $filePath); // doesn't delete directories
				//rename($local_filePath, $local_filePath.'.DONE');


				$log = new TaargaPhoneScrubLog();
				$log->dealerID = $dealerID;
				$log->script = 'Taarga Phone Scrub Downloaded';
				$log->log = $sftp_file . ' - ' . $str;
				$log->runtime = date("Y-m-d H:i:s");
				$log->save();

				$arfc = new ARFileController();
				$arfc->saveTaargaPhoneScrubLog($log->id, 1, $sftp_file . '.done', $_SESSION['login']['staffID'], $local_filePath, true);
			}
		}
	}
	echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;


?>