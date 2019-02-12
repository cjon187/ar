<?php
/**
 * Create AR Portal Objects from Zoho Web hooks
 *
 * 1) Loop through all Dealer requests
 * 2) Loop through all Contact Requests
 * 3) Loop through all Deal requests
 *
 * Things to note:
 * -Contacts and Deals will check to see if their parents are made before even attempting to run.
 * 	-Contacts check for a Dealer
 * 	-Deals will check for a dealer and a contact if it is set.
 *
 */

	include_once('includes.php');
	include_once('emailUtils.php');
	echo PHP_EOL . PHP_EOL . 'Portal to Zoho Sync Started - ' . Date('Y-m-d H:i:s');

	$db = new ARDB();

	SendARDealerRequests();
	SendARContactRequests();
	SendARDealRequests();
	//SendARLeadRequests();

	SendZohoDealerInsertRequests();
	SendZohoContactInsertRequests();
	SendZohoDealInsertRequests();

	SendZohoAccountUndoUpdateRequests();  //This is undo update as opposed to the 2 updates below it
	SendZohoContactUpdateRequests();
	SendZohoDealUpdateRequests();

	SendARDealerRequests();
	SendARContactRequests();
	SendARDealRequests();


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending AR Dealer/Account requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendARDealerRequests(){
			//return false;
			$arDealerRequests = ArToZohoApiRequest::where('modelType', ArToZohoApiRequest::DEALER)
												  ->where('ran',0)
												  ->orderBy('id', 'ASC')
												  ->groupby('modelID')
												  ->get();
			if(!empty($arDealerRequests)){
				$zda = new ZohoDealerAdapter();

				foreach($arDealerRequests as $acr){
					$dealerObj = Dealer::ById($acr->modelID);
					if($dealerObj instanceof Dealer){
						$success = false;
						$zohoDealer = $zda->arToZoho($dealerObj);

						//NEW DEALER CREATED ON AR SIDE, NEED TO UPDATE ZOHO. ON ZOHO RESPONSE, UPDATE IT WITH SYNC INFO
						if($acr->queryType == ArToZohoApiRequest::INSERT){
							$zohoApi = new ZohoApi();
							$defaultUserZohoID = ZohoDefaultRegionUser::getDefaultUserFromDealer($dealerObj);
							$zohoDealer->zohoData['SMOWNERID'] = $defaultUserZohoID;
							$zohoResponseID = $zohoApi->insertAccountRecords($zohoDealer->toXML(), [], $acr->id);
							if(is_numeric($zohoResponseID) && $zohoResponseID > 0){
								//This is a gross hack to get around the country and province field/relation issue
								//You have to reload the model to get away from how the relatiosn replaced the fields into array values.
								$dealerObj2 = Dealer::ById($dealerObj->id);
								$dealerObj2->zohoID = $zohoResponseID;
								$dealerObj2->zohoDataSync = Dealer::ZOHO_SYNCED;
								$dealerObj2->save();
								$zohoDealer->zohoData['Synced with AR Portal'] = "TRUE";
								$zohoDealer->zohoData['Update Warning'] = "This account has been synced with the AR portal. To edit details you must use the request changes button in the top right. Changes made that are not requested can/will be lost";
								$zohoResponse2 = $zohoApi->updateAccountRecords($dealerObj2->zohoID, $zohoDealer->toXML(['Portal Dealership ID','Synced with AR Portal','Update Warning']));
								echo PHP_EOL . 'Successfully added synced Dealer: ' .$acr->modelID.' with zoho Account: '. $zohoResponse2['content'];
								$success = true;
							}
						}
						else if($acr->queryType == ArToZohoApiRequest::UPDATE){
							//New dealer request from zoho, zohoID is set in this request. All we need to do is send zoho the sync info
							$zohoApi = new ZohoApi('xml');
							if($acr->isNewModel){
								$zohoDealer->zohoData['AR Portal Sync Errors'] = null;
								$zohoDealer->zohoData['Update Warning'] = "This account has been synced with the AR portal. To edit details you must use the request changes button in the top right. Changes made that are not requested can/will be lost";
								$zohoResponse = $zohoApi->updateAccountRecords($dealerObj->zohoID, $zohoDealer->toXML(['Portal Dealership ID', 'Synced with AR Portal', 'AR Portal Sync Errors','Update Warning']),[], $acr->id);
								if($zohoResponse){
									$dealerObj2 = Dealer::ById($dealerObj->id);
									$dealerObj2->zohoDataSync = Dealer::ZOHO_SYNCED;
									$dealerObj2->save();
									echo PHP_EOL . 'Successfully updated sync info back to zoho for dealer: ' .$acr->modelID.' on zoho Account: '. $zohoResponse;
									$success = true;
								}
							}
							//Update zoho with changes to an existing Dealer
							else{
								$zohoApi = new ZohoApi('xml');
								$zohoDealer->zohoData['Update Warning'] = "This account has been synced with the AR portal. To edit details you must use the request changes button in the top right. Changes made that are not requested can/will be lost";
								$zohoResponse = $zohoApi->updateAccountRecords($dealerObj->zohoID, $zohoDealer->toXML(), [], $acr->id);
								if($zohoResponse){
									$dealerObj2 = Dealer::ById($dealerObj->id);
									$dealerObj2->zohoDataSync = Dealer::ZOHO_SYNCED;
									$dealerObj2->save();
									echo PHP_EOL . 'Successfully update data for dealer: ' .$acr->modelID.' on zoho Account: '. $zohoResponse;
									$success = true;
								}
							}
						}
					}

					$acr->ran = 1;

					if($success){
						$acr->success = 1;
						$db = new ARDB();
						//Remove all extra requests after a success
						$db->rawQuery('DELETE FROM absoluteresults_log.ar_to_zoho_api_requests
									   WHERE modelType = '. ArToZohoApiRequest::DEALER .'
									   AND modelID = '. $acr->modelID .'
									   AND ran = 0
									   AND id > '. $acr->id);

						//Create requests for all unsynced Contacts and Deals without contacts
						$dealerObj->dealerStaff;
						if(!empty($dealerObj->dealerStaff)){
							foreach($dealerObj->dealerStaff as $staff){
								if($staff->zohoDataSync == DealerStaff::ZOHO_DEALER_DELAYED){
									$staff->zohoDataSync = DealerStaff::ZOHO_PENDING;
									$staff->save();
									$azar = new ArToZohoApiRequest();
									$azar->modelType = ArToZohoApiRequest::DEALERSTAFF;
									$azar->modelID = $staff->id;
									$azar->success = 0;
									$azar->ran = 0;
									$azar->queryType = ArToZohoApiRequest::INSERT;
									$azar->isNewModel = 1;
									$azar->save();
								}
							}
						}

						$quoteResults = $db->rawQuery('SELECT * FROM ps_quotes WHERE ownerTypeID = 1 AND ownerID = ' . $dealerObj->id .' AND zohoDataSync = ' . Quote::ZOHO_DEALER_DELAYED);
						if(!empty($quoteResults)){
							foreach($quoteResults as $result){
								$quoteObj = Quote::ById($result['quoteID']);
								$quoteObj->zohoDataSync = Quote::ZOHO_PENDING;
								$quoteObj->save();
								$azar = new ArToZohoApiRequest();
								$azar->modelType = ArToZohoApiRequest::QUOTE;
								$azar->modelID = $result['quoteID'];
								$azar->success = 0;
								$azar->ran = 0;
								$azar->queryType = ArToZohoApiRequest::INSERT;
								$azar->isNewModel = 1;
								$azar->save();
							}
						}
					}
					else{
						$dealerObj2 = Dealer::ById($dealerObj->id);
						$dealerObj2->zohoDataSync = Dealer::ZOHO_FAILED;
						$dealerObj2->save();
					}

					$acr->save();
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending AR Dealer Staff/Contact requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendARContactRequests(){
			$arContactRequests = ArToZohoApiRequest::where('modelType', ArToZohoApiRequest::DEALERSTAFF)
												  ->where('ran',0)
												  ->orderBy('id', 'ASC')
												  ->groupby('modelID')
												  ->get();
			if(!empty($arContactRequests)){
				$zda = new ZohoContactAdapter();

				foreach($arContactRequests as $acr){
					$dealerStaffObj = DealerStaff::ById($acr->modelID);
					$dealerObj = $dealerStaffObj->dealer;
					if($dealerStaffObj instanceof DealerStaff && $dealerObj instanceof Dealer){
						$success = false;
						$zohoContact = $zda->arToZoho($dealerStaffObj);
						$zohoContact->zohoData['ACCOUNTID'] = $dealerObj->zohoID;

						//NEW DEALER CREATED ON AR SIDE, NEED TO UPDATE ZOHO. ON ZOHO RESPONSE, UPDATE IT WITH SYNC INFO
						if($acr->queryType == ArToZohoApiRequest::INSERT){
							$zohoApi = new ZohoApi();

							$defaultUserZohoID = ZohoDefaultRegionUser::getDefaultUserFromDealer($dealerObj);
							$zohoContact->zohoData['SMOWNERID'] = $defaultUserZohoID;

							$zohoResponseID = $zohoApi->insertContactRecords($zohoContact->toXML(), [], $acr->id);
							if(is_numeric($zohoResponseID) && $zohoResponseID > 0){
								//This is a gross hack to get around the country and province field/relation issue
								//You have to reload the model to get away from how the relatiosn replaced the fields into array values.
								$dealerStaffObj->zohoID = $zohoResponseID;
								$dealerStaffObj->zohoDataSync = DealerStaff::ZOHO_SYNCED;
								if(empty($dealerStaffObj->zohoDealerID) && !empty($dealerObj->zohoID)){
									$dealerStaffObj->zohoDealerID = $dealerObj->zohoID;
								}
								$dealerStaffObj->save();

								$zohoContact->zohoData['Created In AR Portal'] = "TRUE";
								$zohoContact->zohoData['Data Synced with AR Portal'] = "TRUE";
								$zohoResponse2 = $zohoApi->updateContactRecords($dealerStaffObj->zohoID, $zohoContact->toXML(['Dealership Staff ID','Created In AR Portal','Data Synced with AR Portal']));
								echo PHP_EOL . 'Successfully added synced Contact: ' .$acr->modelID.' with zoho Contact: '. $zohoResponse2['content'];
								$success = true;
							}
						}
						else if($acr->queryType == ArToZohoApiRequest::UPDATE){
							//New contact request from zoho, zohoID is set in this request. All we need to do is send zoho the sync info
							$zohoApi = new ZohoApi('xml');
							if($acr->isNewModel){
								$zohoContact->zohoData['AR Portal Create Errors'] = null;
								$zohoContact->zohoData['Created In AR Portal'] = "TRUE";
								$zohoContact->zohoData['Data Synced with AR Portal'] = "TRUE";

								$zohoResponse = $zohoApi->updateContactRecords($dealerStaffObj->zohoID, $zohoContact->toXML(['Dealership Staff ID', 'Created In AR Portal', 'AR Portal Create Errors','Data Synced with AR Portal']));
								if($zohoResponse){
									$dealerStaffObj->zohoDataSync = DealerStaff::ZOHO_SYNCED;
									if(empty($dealerStaffObj->zohoDealerID) && !empty($dealerObj->zohoID)){
										$dealerStaffObj->zohoDealerID = $dealerObj->zohoID;
									}
									$dealerStaffObj->save();
									echo PHP_EOL . 'Successfully updated sync info back to zoho for contact: ' .$acr->modelID.' on zoho Contact: '. $zohoResponse;
									$success = true;
								}
							}
							//Update zoho with changes to an existing contact
							else{
								$zohoApi = new ZohoApi('xml');
								$zohoContact->zohoData['Created In AR Portal'] = "TRUE";
								$zohoContact->zohoData['Data Synced with AR Portal'] = "TRUE";
								if($dealerStaffObj->status == 0){
									$zohoContact->zohoData['SMOWNERID'] = ZohoApi::ADMIN_OWNER_ID;
								}
								$zohoResponse = $zohoApi->updateContactRecords($dealerStaffObj->zohoID, $zohoContact->toXML(),[], $acr->id);
								if($zohoResponse){
									$dealerStaffObj->zohoDataSync = DealerStaff::ZOHO_SYNCED;
									if(empty($dealerStaffObj->zohoDealerID) && !empty($dealerObj->zohoID)){
										$dealerStaffObj->zohoDealerID = $dealerObj->zohoID;
									}
									$dealerStaffObj->save();
									echo PHP_EOL . 'Successfully update data for contact: ' .$acr->modelID.' on zoho Contact: '. $zohoResponse['content'];
									$success = true;
								}
							}
						}
					}

					$acr->ran = 1;
					//Remove all extra requests after a success
					if($success){
						$acr->success = 1;
						$db = new ARDB();
						$db->rawQuery('DELETE FROM absoluteresults_log.ar_to_zoho_api_requests
									   WHERE modelType = '. ArToZohoApiRequest::DEALERSTAFF .'
									   AND modelID = '. $acr->modelID .'
									   AND ran = 0
									   AND id > '. $acr->id);

						//Put in a create request for all deals with this contact and dealer created
						$quoteResults = $db->rawQuery('SELECT * FROM ps_quotes WHERE ownerTypeID = 1 AND ownerID = ' . $dealerObj->id .' AND zohoDataSync = ' . Quote::ZOHO_CONTACT_DELAYED);
						if(!empty($quoteResults)){
							foreach($quoteResults as $result){
								$quoteObj = Quote::ById($result['quoteID']);
								$quoteObj->zohoDataSync = Quote::ZOHO_PENDING;
								$quoteObj->save();
								$azar = new ArToZohoApiRequest();
								$azar->modelType = ArToZohoApiRequest::QUOTE;
								$azar->modelID = $result['quoteID'];
								$azar->success = 0;
								$azar->ran = 0;
								$azar->queryType = ArToZohoApiRequest::INSERT;
								$azar->isNewModel = 1;
								$azar->save();
							}
						}
					}
					else{
						$dealerStaffObj2 = DealerStaff::ById($dealerStaffObj->id);
						if($dealerStaffObj2 instanceof DealerStaff){
							$dealerStaffObj2->zohoDataSync = DealerStaff::ZOHO_FAILED;
							$dealerStaffObj2->save();
						}
					}

					$acr->save();
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending AR Quote/Sales Order requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendARDealRequests(){
			$arQuoteRequests = ArToZohoApiRequest::where('modelType', ArToZohoApiRequest::QUOTE)
											  ->where('ran',0)
											  ->orderBy('id', 'ASC')
											  ->groupby('modelID')
											  ->get();

			if(!empty($arQuoteRequests)){
				$zda = new ZohoDealAdapter();

				foreach($arQuoteRequests as $acr){
					$quoteObj = Quote::ById($acr->modelID);
					$dealerObj = $quoteObj->dealer;
					$dealerStaffObj = $quoteObj->dealerStaff;

					if($quoteObj instanceof Quote && $dealerObj instanceof Dealer){

						$success = false;
						$zohoDeal = $zda->arToZoho($quoteObj);
						$zohoDeal->zohoData['ACCOUNTID'] = $dealerObj->zohoID;
						//$zohoDeal->zohoData['Update Warning'] = "This Deal has been synced with the AR portal. To edit fields in the AR Portal Fields section, click the 'Edit in Portal' Button in the top right.";

						//NEW DEALER CREATED ON AR SIDE, NEED TO UPDATE ZOHO. ON ZOHO RESPONSE, UPDATE IT WITH SYNC INFO
						if($acr->queryType == ArToZohoApiRequest::INSERT){

							$zohoApi = new ZohoApi();
							$defaultUserZohoID = ZohoDefaultRegionUser::getDefaultUserFromDealer($dealerObj);
							$zohoDeal->zohoData['SMOWNERID'] = $defaultUserZohoID;

							$zohoResponseID = $zohoApi->insertDealRecords($zohoDeal->toXML(), [], $acr->id);
							if(is_numeric($zohoResponseID) && $zohoResponseID > 0){
								dev_log("making new deal from ar to zoho here: " . $quoteObj->zohoID);
								//This is a gross hack to get around the country and province field/relation issue
								//You have to reload the model to get away from how the relatiosn replaced the fields into array values.
								$quoteObj->zohoID = $zohoResponseID;
								$quoteObj->zohoDataSync = Quote::ZOHO_SYNCED;
								if(empty($quoteObj->zohoDealerID) && !empty($dealerObj->zohoID)){
									$quoteObj->zohoDealerID = $dealerObj->zohoID;
								}
								if(empty($quoteObj->zohoContactID) && !empty($dealerStaffObj->zohoID)){
										$quoteObj->zohoContactID = $dealerStaffObj->zohoID;
									}
								$quoteObj->save();

								$zohoDeal->zohoData['Created In AR Portal'] = "TRUE";
								$zohoDeal->zohoData['Data Synced with AR Portal'] = "TRUE";
								$zohoResponse = $zohoApi->updateDealRecords($quoteObj->zohoID, $zohoDeal->toXML(['Portal Quote ID', 'Created In AR Portal', 'AR Portal Create Errors','Data Synced with AR Portal']));
								echo PHP_EOL . 'Successfully added synced Quote: ' .$acr->modelID.' with zoho Deal: '. $zohoResponse;
								$success = true;
							}
						}
						else if($acr->queryType == ArToZohoApiRequest::UPDATE){
							//New contact request from zoho, zohoID is set in this request. All we need to do is send zoho the sync info
							$zohoApi = new ZohoApi('xml');
							if($acr->isNewModel){
								$zohoDeal->zohoData['AR Portal Create Errors'] = null;
								$zohoDeal->zohoData['Created In AR Portal'] = "TRUE";
								$zohoDeal->zohoData['Data Synced with AR Portal'] = "TRUE";

								$zohoResponse = $zohoApi->updateDealRecords($quoteObj->zohoID, $zohoDeal->toXML(['Portal Quote ID', 'Created In AR Portal', 'AR Portal Create Errors']));
								if($zohoResponse){
									$quoteObj->zohoDataSync = DealerStaff::ZOHO_SYNCED;
									if(empty($quoteObj->zohoDealerID) && !empty($dealerObj->zohoID)){
										$quoteObj->zohoDealerID = $dealerObj->zohoID;
									}
									if(empty($quoteObj->zohoContactID) && !empty($dealerStaffObj->zohoID)){
										$quoteObj->zohoContactID = $dealerStaffObj->zohoID;
									}
									$quoteObj->save();
									echo PHP_EOL . 'Successfully updated sync info back to zoho for quote: ' .$acr->modelID.' on zoho Deal: '. $zohoResponse;
									$success = true;
								}
							}
							//Update zoho with changes to an existing contact
							else{
								$zohoApi = new ZohoApi('xml');
								$zohoDeal->zohoData['Created In AR Portal'] = "TRUE";
								$zohoDeal->zohoData['Data Synced with AR Portal'] = "TRUE";
								$zohoResponse = $zohoApi->updateDealRecords($quoteObj->zohoID, $zohoDeal->toXML(), [], $acr->id);
								if($zohoResponse){
									$quoteObj->zohoDataSync = DealerStaff::ZOHO_SYNCED;
									if(empty($quoteObj->zohoDealerID) && !empty($dealerObj->zohoID)){
										$quoteObj->zohoDealerID = $dealerObj->zohoID;
									}
									if(empty($quoteObj->zohoContactID) && !empty($dealerStaffObj->zohoID)){
										$quoteObj->zohoContactID = $dealerStaffObj->zohoID;
									}
									$quoteObj->save();
									echo PHP_EOL . 'Successfully update data for Quote: ' .$acr->modelID.' on zoho Deal: '. $zohoResponse;
									$success = true;
								}
							}
						}
					}

					$acr->ran = 1;
					//Remove all extra requests after a success
					if($success){
						$acr->success = 1;
						$db = new ARDB();
						$db->rawQuery('DELETE FROM absoluteresults_log.ar_to_zoho_api_requests
									   WHERE modelType = '. ArToZohoApiRequest::DEALERSTAFF .'
									   AND modelID = '. $acr->modelID .'
									   AND ran = 0
									   AND id > '. $acr->id);
					}
					else{
						$quoteObj2 = Quote::ById($quoteObj->id);
						$quoteObj2->zohoDataSync = Quote::ZOHO_FAILED;
						$quoteObj2->save();
					}

					$acr->save();
				}
			}
		}

	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending AR Contact US / Leads Inserts
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendARLeadRequests(){
			$arLeadRequests = ArToZohoApiRequest::where('modelType', ArToZohoApiRequest::CONTACT_US)
											  ->where('ran',0)
											  ->orderBy('id', 'ASC')
											  ->groupby('modelID')
											  ->get();

			if(!empty($arLeadRequests)){
				$zla = new ZohoLeadAdapter();

				foreach($arLeadRequests as $acr){
					$culObj = ContactUsLead::ById($acr->modelID);

					if($culObj instanceof ContactUsLead){
						$success = false;
						$zohoLead = $zla->arToZoho($culObj);
						$zohoLead->zohoData['ACCOUNTID'] = $dealerObj->zohoID;
						//$zohoLead->zohoData['Update Warning'] = "This Deal has been synced with the AR portal. To edit fields in the AR Portal Fields section, click the 'Edit in Portal' Button in the top right.";

						//NEW DEALER CREATED ON AR SIDE, NEED TO UPDATE ZOHO. ON ZOHO RESPONSE, UPDATE IT WITH SYNC INFO
						if($acr->queryType == ArToZohoApiRequest::INSERT){
							$zohoApi = new ZohoApi();
							$defaultUserZohoID = ZohoDefaultRegionUser::getDefaultUserFromCountryAndProvince($culObj->countryID, $culObj->province);
							$zohoLead->zohoData['SMOWNERID'] = $defaultUserZohoID;

							$zohoResponseID = $zohoApi->insertLeadRecords($zohoLead->toXML(), [], $acr->id);
							if(is_numeric($zohoResponseID) && $zohoResponseID > 0){
								$culObj->save();
								echo PHP_EOL . 'Successfully added lead from contact us form: ' .$acr->modelID;
								$success = true;
							}
						}
					}

					$acr->ran = 1;
					if($success){
						$acr->success = 1;
					}
					$acr->save();
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending dealer create requests FROM ZOHO
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendZohoDealerInsertRequests(){
			$zohoDealerRequests = ArZohoRestAccountLog::where('success',0)
			                               ->where('totalAttempts < 3')
			                               ->where('type', ZohoApi::API_TYPE_INSERT)
			                               ->groupby('zohoID')
			                               ->get();

			if(!empty($zohoDealerRequests)){
				foreach($zohoDealerRequests as $zdr){
					$zoho = new ZohoApi();
		        	$zohoObj = new ZohoDealer();
		        	$zohoResult = $zoho->getAccountById($zdr->zohoID, []);
		        	$zohoObj->loadFromZohoApiResult($zohoResult);

		        	$zda = new ZohoDealerAdapter();
		        	$dealerObj = $zda->zohoToAr($zohoObj);

		        	$zdr->totalAttempts = $zdr->totalAttempts + 1;
					$zdr->lastAttempt = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

					//TAKE THIS OUT LATER. IN NOW BECAUSE OF ELASTIC SEARCH PROBLEM
					//$zdr->overrideSimilarDealers = 0;

		        	if($dealerObj instanceof Dealer){
						$dc = new DealerController();
						$simDealerCheck = true;
						if($zdr->overrideSimilarDealers == 1) $simDealerCheck = false;

						if(!empty($request_data['requestUserEmail'])){
						    $staff = Staff::where('email', $request_data['requestUserEmail'])->getOne();
						    if($staff instanceof Staff){
						        $dealerObj->staffIDEdit = $staff->id;
						    }
						}
						else{
						    $dealerObj->staffIDEdit = 1;
						}

						$result = $dc->saveDealer($dealerObj->toArray(),$simDealerCheck, $dealerObj);

					}
					else{
						$result = ['Error'=>"Could not create dealer Obj from passed in parameters"];
					}

					if(is_numeric($result) && $result > 0) {
						echo PHP_EOL . 'Successfully synced Account: ' .$zdr->zohoID.' with AR Portal dealerID: '. $result;
					    $zdr->success = 1;
					    $zdr->error = null;
					    if($zdr->save()){

					    	//UNSET THE WAIT FOR PARENT CREATE ON CONTACTS AND DEALS WITH NO CONTACTS
						    	$zohoThisDealerContactRequests = ArZohoRestContactLog::where('success',0)
														                               ->where('zohoDealerID', $zdr->zohoID)
														                               ->where('type', ZohoApi::API_TYPE_INSERT)
														                               ->get();
				                if(!empty($zohoThisDealerContactRequests)){
				                	foreach($zohoThisDealerContactRequests as $request){
				                		$request->parentCreateDelayed = 0;
				                		$request->save();
				                		echo PHP_EOL . ' --Dealership of contact :' . $request->zohoID.' has been created, unsetting parentCreateDelayed';
				                	}
				                }

				                $zohoThisDealerDealRequests = ArZohoRestDealLog::where('success',0)
												                               ->where('zohoDealerID', $zdr->zohoID)
												                               ->where('type', ZohoApi::API_TYPE_INSERT)
												                               ->where('zohoContactID is null')
												                               ->get();
				                if(!empty($zohoThisDealerDealRequests)){
				                	foreach($zohoThisDealerDealRequests as $request){
				                		$request->parentCreateDelayed = 0;
				                		$request->save();
				                		echo PHP_EOL . ' --Dealership of Deal :' . $request->zohoID.' has been created, unsetting parentCreateDelayed';
				                	}
				                }

			                //UPDATE PREVIOUS REQUESTS TO FOLLOW UP SUCCESS, DELETE FUTURE REQUESTS
			                $zohoExtraRequests = ArZohoRestAccountLog::where('zohoID', $zdr->zohoID)
			                                                         ->where('type', ZohoApi::API_TYPE_INSERT)
			                                                         ->get();
			                if(!empty($zohoExtraRequests)){
			                	foreach($zohoExtraRequests as $zer){
			                		if($zer->id > $zdr->id){
			                			$zer->delete();
			                		}
			                		else{
			                			$zer->followupSuccess = 1;
			                			$zer->save();
			                		}
			                	}
			                }
					    }
					}
					else{
						echo PHP_EOL . 'Failed to sync zoho Account: ' .$zdr->zohoID;
						$zohoObj->zohoData['AR Portal Sync Errors'] = '';
						if(is_array($result) ){
							$zoho = new ZohoApi();
							if(key($result) == "similarDealers"){
								//$zdr->error = json_encode(['Error'=>"Dealership is to similar to existing dealership. Human intervention will be required"]);
								$zdr->error = json_encode($result);
								$zdr->errorType = ArZohoRestLog::ERROR_DUPLICATE;

								$simDealerString = "Similar Dealers Detected. If dealer is not listed below, check the Overwrite similar dealer check to proceed. Otherwise remove this new Account: \n\n";
								$dealers = $result['similarDealers'][0];
								foreach($dealers as $key => $d){
								    $info = $d['_source'];
								    $simDealerString .= $info['dealerName'] . "\n --->" . $info['address'] . " " . $info['city'] . " " . $info['postalCode'] . " " . $info['countryName'] . "\n --->" . implode(',',$info['brandNames']) ."\n\n";

								}
								$zohoObj->zohoData['AR Portal Sync Errors'] = $simDealerString;
								$zohoResponse = $zoho->updateAccountRecords($zdr->zohoID, $zohoObj->toXML(['AR Portal Sync Errors']));
							}
							else{
								$zdr->error = json_encode($result);
								$zdr->errorType = ArZohoRestLog::ERROR_CREATION;
								if(!empty($result)){
									foreach($result as $key => $e){
										if(!is_numeric($key) && $key != "" && !empty($e)){
											$zohoObj->zohoData['AR Portal Sync Errors'] .= $key." - " . implode(',',$e)."\r\n";
										}
										else if(!empty($e)){
											$zohoObj->zohoData['AR Portal Sync Errors'] .= implode(',',$e)."\r\n";
										}

									}
								}

					   			$zohoResponse = $zoho->updateAccountRecords($zdr->zohoID, $zohoObj->toXML(['AR Portal Sync Errors']));
							}

							//$zohoResponse = $zohoApi->updateAccountRecords($zdr->zohoID, $zohoObj->toXML(['AR Portal Sync Errors']));
					   	}
					   	else{
					   		$zdr->error = json_encode(['Error'=>"Failed to create Dealer. Unknown Error"]);
					   	}



					}
					$zdr->save();
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending Zoho create Contact requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendZohoContactInsertRequests(){
			$db = new ARDB();
			$zohoContactInsertRequests = $db->rawQuery('
				SELECT az.*, d.dealerID FROM absoluteresults_log.ar_zoho_rest_contacts_log az
				LEFT JOIN ps_dealers d ON(az.zohoDealerID = d.zohoID)
				WHERE success = 0
				AND type = 1
				AND totalAttempts < 3
				GROUP BY zohoID');

			$zoho = new ZohoApi();
			if(!empty($zohoContactInsertRequests)){
				foreach($zohoContactInsertRequests as $zcrArray){
					$zcr = ArZohoRestContactLog::ById($zcrArray['id']);

					//IF NO DEALER IS CREATED YET, DON'T BOTHER RUNNING THIS ONE. SAY DEALER IS DELAYED.
					//IF DEALER ID IS EMPTY, JOIN FAILED AND NO DEALER IS CREATED
					if($zcrArray['dealerID'] == ""){
						$zcr->parentCreateDelayed = 1;
						$zcr->save();
					}
					else{
						$zohoObj = new ZohoContact();
			        	$zohoResult = $zoho->getContactById($zcr->zohoID, []);
			        	$zohoObj->loadFromZohoApiResult($zohoResult);

			        	$zda = new ZohoContactAdapter();
			        	$contactObj = $zda->zohoToAr($zohoObj);

			        	$zcr->totalAttempts = $zcr->totalAttempts + 1;
						$zcr->lastAttempt = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

			        	if($contactObj instanceof DealerStaff){
							$dc = new DealerStaffController();

							if(!empty($request_data['requestUserEmail'])){
				        	    $staff = Staff::where('email', $request_data['requestUserEmail'])->getOne();
				        	    if($staff instanceof Staff){
				        	        $contactObj->createdBy = $staff->id;
				        	        $contactObj->editedBy = $staff->id;
				        	    }
				        	}
				        	else{
				        		$contactObj->createdBy = 1;
				        	    $contactObj->editedBy = 1;
				        	}

							$result = $dc->saveDealerStaff($contactObj->toArray(), $contactObj, false);

				        	//$contactObj->dealerID = $zcrArray['dealerID'];
						}
						else{
							$result = ['Error'=>"Could not create contact Obj from passed in parameters"];
						}

						if(is_numeric($result) && $result > 0) {
							echo PHP_EOL . 'Successfully synced Contact: ' .$zcr->zohoID.' with AR Portal dealerStaffID: '. $result;
						    $zcr->success = 1;
						    $zcr->error = null;
			    		    if($zcr->save()){
			    		    	//UNSET THE WAIT FOR PARENT CREATE ON DEALS
			    	                $zohoThisDealerDealRequests = ArZohoRestDealLog::where('success',0)
											    	                               ->where('zohoDealerID', $zcr->zohoDealerID)
											    	                               ->where('type', ZohoApi::API_TYPE_INSERT)
											    	                               ->where('zohoContactID', $zcr->zohoID)
											    	                               ->get();
			    	                if(!empty($zohoThisDealerDealRequests)){
			    	                	foreach($zohoThisDealerDealRequests as $request){
			    	                		$request->parentCreateDelayed = 0;
			    	                		$request->save();
			    	                		echo PHP_EOL . ' --Contact of Deal :' . $request->zohoID.' has been created, unsetting parentCreateDelayed';
			    	                	}
			    	                }

			                    //UPDATE PREVIOUS REQUESTS TO FOLLOW UP SUCCESS, DELETE FUTURE REQUESTS
			                    $zohoExtraRequests = ArZohoRestContactLog::where('zohoID', $zcr->zohoID)
			                    										 ->where('type', ZohoApi::API_TYPE_INSERT)
			                    										 ->get();
			                    if(!empty($zohoExtraRequests)){
			                    	foreach($zohoExtraRequests as $zer){
			                    		if($zer->id > $zcr->id){
			                    			$zer->delete();
			                    		}
			                    		else if ($zer->id < $zcr->id){
			                    			$zer->followupSuccess = 1;
			                    			$zer->save();
			                    		}
			                    	}
			                    }
			    		    }
						}
						else{
							echo PHP_EOL . 'Failed to sync zoho Contact: ' .$zcr->zohoID;
							$zohoObj->zohoData['AR Portal Create Errors'] = '';
							if(is_array($result) ){
						   		$zcr->error = json_encode($result);
								if(!empty($result)){
									foreach($result as $e){
										if(!empty($e)){
											$zohoObj->zohoData['AR Portal Create Errors'] .= implode(',',$e)."\r\n";
										}

									}
								}
					   			$zohoResponse = $zoho->updateContactRecords($zcr->zohoID, $zohoObj->toXML(['AR Portal Create Errors']));
						   	}
						   	else{
						   		$zcr->error = json_encode(['Error'=>"Failed to create Contact. Unknown Error"]);
						   	}
						   	$zcr->save();
						}
					}
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending Zoho create deal requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendZohoDealInsertRequests(){
			$db = new ARDB();
			$zohoDealRequests = $db->rawQuery('
				SELECT az.*, d.dealerID, ds.dealerStaffID FROM absoluteresults_log.ar_zoho_rest_deals_log az
				LEFT JOIN ps_dealers d ON(az.zohoDealerID = d.zohoID)
				LEFT JOIN ps_dealerstaff ds ON(az.zohoContactID = ds.zohoID)
				WHERE success = 0
				AND parentCreateDelayed = 0
				AND type = 1
				AND totalAttempts < 3
				GROUP BY zohoID');
			$zoho = new ZohoApi();
			if(!empty($zohoDealRequests)){
				foreach($zohoDealRequests as $zdrArray){
					echo PHP_EOL . 'Starting Zoho create Deal request on ID: ' .$zdr->zohoID;
					$zdr = ArZohoRestDealLog::ById($zdrArray['id']);
					//IF NO DEALER IS CREATED YET OR DEAL SPECIFIED BUT NOT CREATED, DON'T BOTHER RUNNING THIS ONE. SAY PARENT IS DELAYED.
					if($zdrArray['dealerID'] == "" || ($zdrArray['zohoContactID'] != "" && $zdrArray['dealerStaffID'] == "")){
						$zdr->parentCreateDelayed = 1;
						$zdr->save();
						echo PHP_EOL . ' --Waiting on Dealer or Dealerstaff Creation: ' .$zdr->zohoID;
					}
					else{
						$zohoObj = new ZohoDeal();
			        	$zohoResult = $zoho->getDealById($zdr->zohoID, []);
			        	$zohoObj->loadFromZohoApiResult($zohoResult);

			        	$zda = new ZohoDealAdapter();
			        	$dealObj = $zda->zohoToAr($zohoObj);

			        	$zdr->totalAttempts = $zdr->totalAttempts + 1;
						$zdr->lastAttempt = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

			        	if($dealObj instanceof Quote){
							$dc = new QuoteController();
							$result = $dc->saveQuote($dealObj->toArray(), $dealObj);
						}
						else{
							$result = ['Error'=>["Could not create deal Obj from passed in parameters"]];
						}

						if(is_numeric($result) && $result > 0) {
							echo PHP_EOL . 'Successfully synced Deal: ' .$zdr->zohoID.' with AR Portal quoteID: '. $result;
						    $zdr->success = 1;
						    $zdr->error = null;
			    		    if($zdr->save()){
			                    //UPDATE PREVIOUS REQUESTS TO FOLLOW UP SUCCESS, DELETE FUTURE REQUESTS
			                    $zohoExtraRequests = ArZohoRestDealLog::where('zohoID', $zdr->zohoID)
			                                                           ->where('type', ZohoApi::API_TYPE_INSERT)
			                                                           ->get();
			                    if(!empty($zohoExtraRequests)){
			                    	foreach($zohoExtraRequests as $zer){
			                    		if($zer->id > $zdr->id){
			                    			$zer->delete();
			                    		}
			                    		else if ($zer->id < $zdr->id){
			                    			$zer->followupSuccess = 1;
			                    			$zer->save();
			                    		}
			                    	}
			                    }
			    		    }
						}
						else{
							echo PHP_EOL . 'Failed to sync zoho Deal: ' .$zdr->zohoID;
							$zohoObj->zohoData['AR Portal Sync Errors'] = '';
							if(is_array($result) ){
						   		$zdr->error = json_encode($result);
								if(!empty($result)){
									foreach($result as $e){
										if(!empty($e)){
											$zohoObj->zohoData['AR Portal Sync Errors'] .= implode(',',$e)."\r\n";
										}

									}
								}

					   			$zohoResponse = $zoho->updateDealRecords($zdr->zohoID, $zohoObj->toXML(['AR Portal Sync Errors']));
						   	}
						   	else{
						   		$zdr->error = json_encode(['Error'=>"Failed to create Deal. Unknown Error"]);
						   	}
						   	$zdr->save();
						}
					}
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending zoho Account undo update requests
	//-------------------------------------------------------------------------------------------------------------------------------
			function SendZohoAccountUndoUpdateRequests(){
				$db = new ARDB();
				$zohoAccountUndoUpdateRequests = ArZohoRestAccountLog::where('success',0)
				                               ->where('totalAttempts < 3')
				                               ->where('type', ZohoApi::API_TYPE_UNDO_UPDATE)
				                               ->groupby('zohoID')
				                               ->ArrayBuilder()
				                               ->get();

				$zoho = new ZohoApi();
				if(!empty($zohoAccountUndoUpdateRequests)){
					foreach($zohoAccountUndoUpdateRequests as $zaArray){
						$zcr = ArZohoRestAccountLog::ById($zaArray['id']);
						$dealerObj = Dealer::where('zohoID', $zcr->zohoID)->getOne();
						if($dealerObj instanceof Dealer){
							$dc = new DealerController();
							$result = $dc->saveDealer(['dealerID'=>$dealerObj->id],false);
						}

						if(is_numeric($result) && $result > 0) {
						    $zcr->success = 1;
						    $zcr->error = null;
			    		    if($zcr->save()){
			    		    	$zohoExtraRequests = ArZohoRestAccountLog::where('zohoID', $zcr->zohoID)
			    		    											 ->where('type', ZohoApi::API_TYPE_UNDO_UPDATE)
			    		    											 ->get();
			    		    	if(!empty($zohoExtraRequests)){
			    		    		foreach($zohoExtraRequests as $zer){
			    		    			if($zer->id > $zcr->id){
			    		    				$zer->delete();
			    		    			}
			    		    			else if ($zer->id < $zcr->id){
			    		    				$zer->followupSuccess = 1;
			    		    				$zer->save();
			    		    			}
			    		    		}
			    		    	}
			    		    }
			    		}
			    			else{
			    				$zohoObj->zohoData['AR Portal Data Sync Errors'] = 'Failed to Undo Updates. Data out of sync. ';
			    				$zohoObj->zohoData['Data Synced with AR Portal'] = "FALSE";
			    				if(is_array($result) ){
			    			   		$zcr->error = json_encode($result);
			    					if(!empty($result)){
			    						foreach($result as $e){
			    							if(!empty($e)){
			    								$zohoObj->zohoData['AR Portal Data Sync Errors'] .= implode(',',$e)."\r\n";
			    							}
			    						}
			    					}
			    					dev_log($zohoObj->toXML(['Data Synced with AR Portal','AR Portal Data Sync Errors']));
			    		   			$zohoResponse = $zoho->updateContactRecords($zcr->zohoID, $zohoObj->toXML(['Data Synced with AR Portal','AR Portal Data Sync Errors']));
			    			   	}
			    			   	else{
			    			   		$zcr->error = json_encode(['Error'=>"Failed to undo update on Account. Unknown Error"]);
			    			   	}
			    			   	$zcr->save();
			    			}
					}
				}
			}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending zoho Contact update requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendZohoContactUpdateRequests(){
			$db = new ARDB();
			$zohoContactUpdateRequests = $db->rawQuery('
				SELECT az.*, d.dealerID FROM absoluteresults_log.ar_zoho_rest_contacts_log az
				LEFT JOIN ps_dealers d ON(az.zohoDealerID = d.zohoID)
				WHERE success = 0
				AND parentCreateDelayed = 0
				AND type = '. ZohoApi::API_TYPE_UPDATE .'
				AND totalAttempts < 3
				GROUP BY zohoID');

			$zoho = new ZohoApi();
			if(!empty($zohoContactUpdateRequests)){
				foreach($zohoContactUpdateRequests as $zcrArray){
					$zcr = ArZohoRestContactLog::ById($zcrArray['id']);
					//IF NO DEALER IS CREATED YET, DON'T BOTHER RUNNING THIS ONE. SAY DEALER IS DELAYED.
					//IF DEALER ID IS EMPTY, JOIN FAILED AND NO DEALER IS CREATED
					if($zcrArray['dealerID'] == ""){
						$zcr->parentCreateDelayed = 1;
						$zcr->save();
					}
					else{
						$zohoObj = new ZohoContact();
			        	$zohoResult = $zoho->getContactById($zcr->zohoID, []);
			        	$zohoObj->loadFromZohoApiResult($zohoResult);

			        	$zda = new ZohoContactAdapter();
			        	$contactObj = $zda->zohoToAr($zohoObj);

			        	$zcr->totalAttempts = $zcr->totalAttempts + 1;
						$zcr->lastAttempt = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

			        	if($contactObj instanceof DealerStaff){

			        		$updateArray = ['dealerStaffID'=>$contactObj->dealerStaffID,
			        						'firstname'=>$contactObj->firstname,
			        						'lastname'=>$contactObj->lastname,
			        						'phone'=>$contactObj->phone,
			        						'mobilePhone'=>$contactObj->mobilePhone,
			        						'email'=>$contactObj->email,
			        						'title'=>$contactObj->title,
			        						'arMarketingDNM'=>$contactObj->arMarketingDNM
			        		               ];

	    					if(!empty($request_data['requestUserEmail'])){
	    		        	    $staff = Staff::where('email', $request_data['requestUserEmail'])->getOne();
	    		        	    if($staff instanceof Staff){
	    		        	        $updateArray['createdBy'] = $staff->id;
	    		        	        $updateArray['editedBy']= $staff->id;
	    		        	    }
	    		        	}
	    		        	else{
	    		        		$updateArray['createdBy'] = 1;
	    		        	    $updateArray['editedBy']= 1;
	    		        	}

							$dc = new DealerStaffController();
							$result = $dc->saveDealerStaff($updateArray, null, false);

						}
						else{
							$result = ['Error'=>"Could not update contact Obj from passed in parameters"];
						}

						if(is_numeric($result) && $result > 0) {
						    $zcr->success = 1;
						    $zcr->error = null;
			    		    if($zcr->save()){
			                    //UPDATE PREVIOUS REQUESTS TO FOLLOW UP SUCCESS, DELETE FUTURE REQUESTS
			                    $zohoExtraRequests = ArZohoRestContactLog::where('zohoID', $zcr->zohoID)
			                    										 ->where('type', ZohoApi::API_TYPE_UPDATE)
			                    										 ->get();
			                    if(!empty($zohoExtraRequests)){
			                    	foreach($zohoExtraRequests as $zer){
			                    		if($zer->id > $zcr->id){
			                    			$zer->delete();
			                    		}
			                    		else if ($zer->id < $zcr->id){
			                    			$zer->followupSuccess = 1;
			                    			$zer->save();
			                    		}
			                    	}
			                    }
			    		    }
						}
						else{
							$zohoObj->zohoData['AR Portal Data Sync Errors'] = '';
							$zohoObj->zohoData['Data Synced with AR Portal'] = "FALSE";
							if(is_array($result) ){
						   		$zcr->error = json_encode($result);
								if(!empty($result)){
									foreach($result as $e){
										if(!empty($e)){

											$zohoObj->zohoData['AR Portal Data Sync Errors'] .= implode(',',$e)."\r\n";
										}
									}
								}
								dev_log($zohoObj->toXML(['Data Synced with AR Portal','AR Portal Data Sync Errors']));
					   			$zohoResponse = $zoho->updateContactRecords($zcr->zohoID, $zohoObj->toXML(['Data Synced with AR Portal','AR Portal Data Sync Errors']));
						   	}
						   	else{
						   		$zcr->error = json_encode(['Error'=>"Failed to update Contact. Unknown Error"]);
						   	}
						   	$zcr->save();
						}
					}
				}
			}
		}


	//-------------------------------------------------------------------------------------------------------------------------------
	//LOOP through all pending zoho Deal update requests
	//-------------------------------------------------------------------------------------------------------------------------------
		function SendZohoDealUpdateRequests(){
			$db = new ARDB();
			$zohoDealUpdateRequests = $db->rawQuery('
				SELECT az.*, d.dealerID, ds.dealerStaffID FROM absoluteresults_log.ar_zoho_rest_deals_log az
				LEFT JOIN ps_dealers d ON(az.zohoDealerID = d.zohoID)
				LEFT JOIN ps_dealerstaff ds ON(az.zohoContactID = ds.zohoID)
				WHERE success = 0
				AND parentCreateDelayed = 0
				AND type = '. ZohoApi::API_TYPE_UPDATE .'
				AND totalAttempts < 3
				GROUP BY zohoID');

			$zoho = new ZohoApi();
			if(!empty($zohoDealUpdateRequests)){
				foreach($zohoDealUpdateRequests as $zcrArray){
					$zcr = ArZohoRestDealLog::ById($zcrArray['id']);
					echo PHP_EOL . 'Starting Zoho Update Deal request on ID: ' .$zcrArray['zohoID'];

					//IF NO DEALER IS CREATED YET OR DEAL SPECIFIED BUT NOT CREATED, DON'T BOTHER RUNNING THIS ONE. SAY PARENT IS DELAYED.
					if($zcrArray['dealerID'] == "" || ($zcrArray['zohoContactID'] != "" && $zcrArray['dealerStaffID'] == "")){
						$zcr->parentCreateDelayed = 1;
						$zcr->save();
						echo PHP_EOL . '--Waiting on Dealer or Dealerstaff Creation: ' .$zcrArray['zohoID'];
					}
					else{
						$zohoObj = new ZohoDeal();
			        	$zohoResult = $zoho->getDealById($zcr->zohoID, []);
			        	$zohoObj->loadFromZohoApiResult($zohoResult);

			        	$zda = new ZohoDealAdapter();
			        	$quoteObj = $zda->zohoToAr($zohoObj);

			        	$zcr->totalAttempts = $zcr->totalAttempts + 1;
						$zcr->lastAttempt = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

			        	if($quoteObj instanceof Quote){

			        		$updateArray = ['quoteID'=>$quoteObj->quoteID,
			        						'zohoID'=>$quoteObj->zohoID,
			        						'ownerID'=>$quoteObj->ownerID,
			        						'ownerStaffID'=>$quoteObj->ownerStaffID,
			        						'currencyID'=>$quoteObj->currencyID,
			        						'notes'=>$quoteObj->notes,
			        						'staffID'=>$quoteObj->staffID,
			        						'start'=>$quoteObj->start,
			        						'end'=>$quoteObj->end,
			        						'zohoContactID'=>$quoteObj->zohoContactID,
			        						'dealName' => $quoteObj->dealName,
			        						'ownerTypeID'=>1,
			        						'zohoClosingDate'=>$quoteObj->zohoClosingDate,
			        						'stage'=>$quoteObj->stage,
			        		               ];

							$qc = new QuoteController();
							$result = $qc->saveQuote($updateArray, null);

						}
						else{
							$result = ['Error'=>"Could not update Quote Obj from passed in parameters"];
						}

						if(is_numeric($result) && $result > 0) {
							echo '--Success';
						    $zcr->success = 1;
						    $zcr->error = null;
			    		    if($zcr->save()){
			                    //UPDATE PREVIOUS REQUESTS TO FOLLOW UP SUCCESS, DELETE FUTURE REQUESTS
			                    $zohoExtraRequests = ArZohoRestDealLog::where('zohoID', $zcr->zohoID)
			                    										 ->where('type', ZohoApi::API_TYPE_UPDATE)
			                    										 ->get();
			                    if(!empty($zohoExtraRequests)){
			                    	foreach($zohoExtraRequests as $zer){
			                    		if($zer->id > $zcr->id){
			                    			$zer->delete();
			                    		}
			                    		else if ($zer->id < $zcr->id){
			                    			$zer->followupSuccess = 1;
			                    			$zer->save();
			                    		}
			                    	}
			                    }

			                    $zohoObj->zohoData['AR Portal Sync Errors'] = "";
			                    $zohoResponse = $zoho->updateAccountRecords($zdr->zohoID, $zohoObj->toXML(['AR Portal Sync Errors']));
			    		    }
						}
						else{
							echo '--Failed';
							$zohoObj->zohoData['AR Portal Data Sync Errors'] = '';
							$zohoObj->zohoData['Data Synced with AR Portal'] = "FALSE";
							if(is_array($result) ){
						   		$zcr->error = json_encode($result);
								if(!empty($result)){
									foreach($result as $e){
										if(!empty($e)){

											$zohoObj->zohoData['AR Portal Data Sync Errors'] .= implode(',',$e)."\r\n";
										}
									}
								}
					   			$zohoResponse = $zoho->updateDealRecords($zcr->zohoID, $zohoObj->toXML(['Data Synced with AR Portal','AR Portal Data Sync Errors']));
						   	}
						   	else{
						   		$zcr->error = json_encode(['Error'=>"Failed to update Contact. Unknown Error"]);
						   	}
						   	$zcr->save();
						}
					}
				}
			}
		}


	echo PHP_EOL . 'Portal to Zoho Sync Finished';
?>