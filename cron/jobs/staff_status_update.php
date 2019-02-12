<?php

	include_once('includes.php');



	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//CHECK THE STATUS OF ALL ACTIVE AR STAFF EMPLOYEES/CONTRACTORS/FRANCHISEES/BUSINESS PARTNERS
	//LOCK ANY ACCOUTS OUTSIDE OF THEIR RANGES, SEND EMAIL TO HR
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	write_log("*************************");
	write_log("Begin AR Staff Status Check");
	write_log("*************************");
		$expired = [];
		$contractFranchiseExpired = [];
		$results = $db->rawQuery('SELECT s.name,s.staffID,status FROM ps_staff s
									LEFT JOIN trainer_franchise_dates tfd ON(s.staffID = tfd.staffID)
									AND (
										(`start` is not null AND `end` is null)
										OR
										(`start` is not null AND `end` is not null AND DATE_FORMAT(NOW(),"%Y-%m-%d") <= `end`)
									)
									WHERE s.status = 1
									AND tfd.id is null');
		if(count($results) > 0){
		    foreach($results as $re){
		        $staff = Staff::ById($re['staffID']);
		        if($staff instanceof Staff){
		            $expired[$re['staffID']] = $re['name'];
		            $staff->status = 0;
		            $staff->deleted = "yes";
		            $staff->dateDeleted = \Carbon\Carbon::now()->format('Y-m-d');
		            $staff->save();
		        }
		    }

		    $results = $db->rawQuery('SELECT * FROM
		                                (SELECT s.name,s.staffID,status, tfd.start, tfd.end, tfd.franchiseType FROM ps_staff s
		                                    LEFT JOIN trainer_franchise_dates tfd ON(s.staffID = tfd.staffID)
		                                    WHERE tfd.staffID IN('. implode(',',array_keys($expired)) .')
		                                    ORDER BY `end` DESC
		                                ) as x
		                              GROUP BY x.staffID
		                              ORDER BY end ASC');
		    if(count($results) > 0){
		        foreach($results as $re){
		            $expired[$re['staffID']] = $re;
		            if(in_array($re['franchiseType'],[TrainerFranchiseDates::FRANCHISEE,TrainerFranchiseDates::CONTRACTOR])){
			        	$contractFranchiseExpired[$re['staffID']] = $re;
			        }
		        }
		    }
		}

		$date = \Carbon\Carbon::now()->addWeeks(2)->format("Y-m-d H:i:s");
		$results = $db->rawQuery('SELECT s.name,s.staffID,status FROM ps_staff s
		                            LEFT JOIN trainer_franchise_dates tfd ON(s.staffID = tfd.staffID)
		                            AND (
		                                (`start` is not null AND `end` is null)
		                                OR
		                                (`start` is not null AND `end` is not null AND "'. $date .'" <= `end`)
		                            )
		                            WHERE s.status = 1
		                            AND tfd.id is null');
		$expiringIDs = [];
		$contractFranchiseExpiring = [];
		$expiring = [];
		if(count($results) > 0){
		    foreach($results as $re){
		        $expiringIDs[] = $re['staffID'];
		    }
		}

		if(count($expiringIDs) > 0){

		    $results = $db->rawQuery('SELECT * FROM
		                                (SELECT s.name,s.staffID,status, tfd.start, tfd.end, tfd.franchiseType FROM ps_staff s
		                                    LEFT JOIN trainer_franchise_dates tfd ON(s.staffID = tfd.staffID)
		                                    WHERE tfd.staffID IN('. implode(',',$expiringIDs) .')
		                                    ORDER BY `end` DESC
		                                ) as x
		                                GROUP BY x.staffID
		                                ORDER BY end ASC');
		    if(count($results) > 0){
		        foreach($results as $re){
		            $expiring[] = $re;
		            if(in_array($re['franchiseType'],[TrainerFranchiseDates::FRANCHISEE,TrainerFranchiseDates::CONTRACTOR])){
			        	$contractFranchiseExpiring[$re['staffID']] = $re;
			        }
		        }
		    }
		}

		$sc = new StaffController();
		$sc->sendStaffExpirationNotificationEmail($expired, $expiring);
		$sc->sendStaffExpirationNotificationEmail($contractFranchiseExpired, $contractFranchiseExpiring, true);
	write_log("*************************");
	write_log("AR Status Check Complete");
	write_log("*************************");
?>