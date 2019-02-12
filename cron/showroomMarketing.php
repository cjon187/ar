<?php

	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('emailUtils.php');
	include_once('defines.php');


	function write_log($msg,$newline = true) {
		echo $msg . ($newline ? PHP_EOL : '');
	}


	$db = new ARDB();
	$dealerIDs = array();
	$organizationIDs = array();

	$dm = new ArisDealerModules();
	$dm->where('status',ArisDealerModules::MODULE_STATUS_ACTIVE);
	$dm->where('moduleID',ArisModule::ARC);
	$results = $dm->get();
	if(!empty($results)) {
		foreach($results as $re) {
			$dealerIDs[$re->dealerID] = $re->dealerID;
		}
	}

	if(!empty($dealerIDs)) {
		$ard = new Dealer();
		$ard->where('dealerID',array_keys($dealerIDs),'IN');
		$results = $ard->get();
		if(!empty($results)) {
			foreach($results as $re) {
				$organizationIDs[$re->dealerID] = $re->DLOrganizationID;
			}
		}
	}

	//HACK FOR O'CONNOR
	$organizationIDs = array(439=>2428);

	if(!empty($organizationIDs))
		foreach($organizationIDs as $dealerID => $orgID) {{
			$assigner = new WeeklyCampaignsAssigner($orgID);
			$assigner->saveCustomers();

			$f9 = new Five9Soap();
			$dealer = Dealer::byId($dealerID);
		    $five9List = $f9->getWeeklyCampaignsTimeZoneList($dealer);
		    if(!empty($five9List)) {
				echo $f9->clearList($five9List);
			}

			if($assigner->pushCustomers()){
				write_log($orgID . '-Success');
			} else {
				write_log($orgID . '-Failed');
			}
		}
	}
?>