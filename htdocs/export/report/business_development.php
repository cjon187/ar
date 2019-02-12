<?php

	include_once('loginUtils.php');
	include_once('mysqliUtils.php');
	include_once('displayUtils.php');
	include_once('statsUtils.php');
	include_once('crmUtils.php');
	include_once('salesCallsUtils.php');

	$staffs = getStaffCRM();
	$calls = getSalesCallsByStaff(array_keys($staffs),'2015-06-01',date("Y-m-t"));
	foreach($staffs as $staffID => $staff)
	{
		$staff_calls = $calls[$staffID];

		//$staffs[$staffID]['num_calls'] = count($staff_calls);
		foreach($staff_calls as $staff_call)
		{
			$staffs[$staffID]['dealers'][$staff_call['dealerID']]['num_calls']++;
			if($staff_call['booked_event'] == 'booked') $staffs[$staffID]['dealers'][$staff_call['dealerID']]['booked'] = 1;
			if($staff_call['booked_event'] == 'rejected') $staffs[$staffID]['dealers'][$staff_call['dealerID']]['rejected'] = 1;
			if($staff_call['booked_event'] == 'heat_score') $staffs[$staffID]['dealers'][$staff_call['dealerID']]['heat_score'] = 1;
		}

		$staffs[$staffID]['num_dealers'] = count($staffs[$staffID]['dealers']);
		foreach($staffs[$staffID]['dealers'] as $dealerID => $outcomes)
		{
			foreach($outcomes as $outcome_type => $cnt)	$staffs[$staffID][$outcome_type] += $cnt;
		}

		unset($staffs[$staffID]['dealers']);
		if(!isset($staffs[$staffID]['num_calls']) || $staffs[$staffID]['num_calls'] == 0) unset($staffs[$staffID]);
	//print_r2($staff_calls);

	}


	print_r2($staffs);
?>