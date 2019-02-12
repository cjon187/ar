<?php
require('composer/vendor/autoload.php');
include_once('db/ARDB.php');

use GuzzleHttp\Client;

class DLEventAPI {
	private $key = '38244be2120919b4be7355da6bcb3266';
	private $name = 'Mybdc-Api-Key';
	private $url = 'http://staging.arloyalty.com/api/v1/';
	//private $url = 'http://devel.ar.absoluteresults.com/test/dl_api/';


	const ARC_CALL = 'ar_arcc_call';
	const TRAINER_CALL = 'ar_trainer_call';
	const VEHICLE_SOLD = 'ar_event_sold';
	const VEHICLE_TRADE_IN = 'ar_event_trade_in';
	const VEHICLE_TEST_DRIVE = 'ar_test_drive';
	const EVENT_APPOINTMENT = 'ar_event_appointment';
	const WEB_REGISTRATION = 'ar_web_registration';
	const CUSTOMER_SHOWED_UP = 'ar_event_show';
	const MAIL_SENT = 'ar_mailer';
	const EMAIL_SENT = 'ar_email';
	const CUSTOMER_MADE_APPOINTMENT = 'ar_appointment';
	const SMS_RESPONSE = 'ar_inbound_sms';
	const CAMPAIGN_SMS_SENT = 'ar_campaign_sms';
	const SMS_SENT = 'ar_outbound_sms';
	const ARIS_PORTAL_LOGIN = 'ar_aris_login_with_username_and_password';

	//we have a "special" table :)
	const DL_AR_TABLE_TYPE = 10001;
	const DL_AR_TABLE_TYPE_NAME = 'ar_private_sale';

	//map the constants to classes
	public static $classes = [
		self::ARC_CALL => 'PSEventARCCall',
		self::TRAINER_CALL => 'PSEventCall',
		self::VEHICLE_SOLD => 'PSEventVehicleSold',
		self::VEHICLE_TRADE_IN => 'PSEventVehicleTradeIn',
		self::VEHICLE_TEST_DRIVE => 'PSEventVehicleTestDrive',
		self::EVENT_APPOINTMENT => 'PSEventAppointment',
		self::WEB_REGISTRATION => 'PSEventWebRegistration',
		self::CUSTOMER_SHOWED_UP => 'PSEventCustomerShowedUp',
		self::MAIL_SENT => 'PSEventMail',
		self::EMAIL_SENT => 'PSEventEmail',
		self::CUSTOMER_MADE_APPOINTMENT => 'PSEventCustomerMadeAppointment',
		self::SMS_RESPONSE => 'PSEventSMSResponse',
		self::CAMPAIGN_SMS_SENT => 'PSEventCampaignSMS',
		self::SMS_SENT => 'PSEventSMSSent',
		self::ARIS_PORTAL_LOGIN => 'PSEventArisLogin'
	];

	public static $class_types = [
		self::ARC_CALL => "ARC Call",
		self::TRAINER_CALL => "Trainer Call",
		self::VEHICLE_SOLD => "Vehicle Sold At Sale",
		self::VEHICLE_TRADE_IN => "Vehicle Traded-In",
		self::VEHICLE_TEST_DRIVE => "Test Drive",
		self::EVENT_APPOINTMENT => "Event Appointment",
		self::WEB_REGISTRATION => "Web Registration",
		self::CUSTOMER_SHOWED_UP => "Customer arrived for appointment",
		self::MAIL_SENT => "Invitation mailed",
		self::EMAIL_SENT => "Responded to email campaign",
		self::CUSTOMER_MADE_APPOINTMENT => "Customer booked appointment",
		self::SMS_RESPONSE => "Customer responded to SMS campaign",
		self::CAMPAIGN_SMS_SENT => "Campaign SMS sent to customer",
		self::SMS_SENT => "SMS sent to customer",
		self::ARIS_PORTAL_LOGIN => "Aris portal login"
	];

	private $db;
	private $client;

	private static $entropy = 1;

	protected $validUsers = array();

	public function __construct() {
		$this->db = new ARDB();
		$this->setupClientConnection();
	}

	protected function setupClientConnection() {
		$this->client = new Client([
			'base_uri' => $this->url,
			'headers' => [
				$this->name => $this->key
			]
		]);
	}

	public function updateDLAppointments() {
		$data = $this->validUsers;
		if(is_array($data) && count($data)) {
			foreach($data AS $contact_id=>$row) {
				$sources = $row['sources'];
/*				if($row['appointmentID'] == 86246) {
					echo '<div style="background: white; color: black;">';
					echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
					echo '<pre>';
					print_r($row);
					echo '</pre>';
					echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
					echo '</div>';
				}*/
				foreach($sources AS $source) {
					if($source == 'invite') {
						$class = new PSEventMail($this->db);
						$class->fixData($row);
						$g = $class->getData();
						$this->sendEventPayloadToDL($g,self::MAIL_SENT);
						continue;
					}
					if($source == 'arc') {
						$class = new PSEventARCCall($this->db);
						$class->fixData($row);
						$g = $class->getData();
						$this->sendEventPayloadToDL($g,self::ARC_CALL);
						continue;
					}
					if($source == 'web') {
						$class = new PSEventWebRegistration($this->db);
						$class->fixData($row);
						$g = $class->getData();
						$this->sendEventPayloadToDL($g,SELF::WEB_REGISTRATION);
						continue;

					}
					if($source == 'email') {
						$class = new PSEventEMail($this->db);
						$class->fixData($row);
						$g = $class->getData();
						$this->sendEventPayloadToDL($g,self::EMAIL_SENT);
						continue;
					}
				}
				//wow.....another CSV.....
				$trainerContacts = explode(',',trim($row['trainerContact'],','));
				if(is_array($trainerContacts) && count($trainerContacts)) {
					//I have no clue how to get at the follow-up time....I assume its in ps_crm_followups
					//nope! Of course its in ps_dumpster_followup becuase....reasons.....
					$class = new PSEventTrainerCall($this->db);
					$class->fixData($row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::TRAINER_CALL);
				}

				if($row['sold1'] == 'y') {
					$class = new PSEventVehicleSold($this->db);
					$class->fixData('1',$row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::VEHICLE_SOLD);
				}
				if($row['sold2'] == 'y') {
					$class = new PSEventVehicleSold($this->db);
					$class->fixData('2',$row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::VEHICLE_SOLD);
				}
				if($row['sold3'] == 'y') {
					$class = new PSEventVehicleSold($this->db);
					$class->fixData('3',$row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::VEHICLE_SOLD);
				}
				if($row['sold4'] == 'y') {
					$class = new PSEventVehicleSold($this->db);
					$class->fixData('4',$row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::VEHICLE_SOLD);
				}
				//there have been no demos since 2014.....demo1 is null or empty for 0115 - 0116 so....why bother..
				if($row['hasTestDrive'] == 'on') {
					$class = new PSEventVehicleTestDrive($this->db);
					$class->fixData($row);
					$g = $class->getData();	
					$this->sendEventPayloadToDL($g,self::VEHICLE_TEST_DRIVE);
				}
				//I guess if appoitnmentTime is not null then and appointment was made?
				if($row['appointmentTime'] && $row['appointmentTime'] != '') {
					$class = new PSEventCustomerMadeAppointment($this->db);
					$class->fixData($row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::CUSTOMER_MADE_APPOINTMENT);
				}

				if($row['arrivedTime'] && $row['arrivedTime'] != '') {
					$class = new PSEventCustomerShowedUp($this->db);
					$class->fixData($row);
					$g = $class->getData();
					$this->sendEventPayloadToDL($g,self::CUSTOMER_SHOWED_UP);
				}
			}
		}
	}

	public function sendEventPayloadToDL(array $data,$type) {
		static::$entropy++;
		//table_id for us right now will always be 10001
		$uuid = uniqid().static::$entropy;
		$event_name = $type;
		//add the hardcoded table_id
		$data['table_type'] = self::DL_AR_TABLE_TYPE_NAME;
		$data['table_id'] = self::DL_AR_TABLE_TYPE;

		//send the "nice" wording
		$data['event_type'] = $type;
/*		echo '<div style="background: white; color: black;">';
		echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
		echo "<h4>{$type}</h4>";
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
		echo '</div>';return;*/
		$payload = json_encode($data);
		//for now setup a new client to send
		$client = new Client([
			'base_uri' => $this->url,
			'headers' => [
				$this->name => $this->key
			]
		]);

		$send_endpoint = 'events/';
		$resp = $client->request('POST',$send_endpoint,[
			'form_params' => [
				'uuid' => $uuid,
				'event_name' => $event_name,
				'payload' => $payload
			]
		]);
		echo '<div style="background: white; color: black;">';
		echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
		echo '<pre>';
		print_r($resp->getBody());
		echo '</pre>';
		echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
		echo '</div>';
	}

	public function getEventDataForCustomer($organization_id,$customer_id,$from=null,$to=null,$ar_only=false) {
		
		$endpoint = 'events/';
		$params = [
			'organization_id' => $organization_id,
			'customer_id' => $customer_id
		];

		if($ar_only) {
			$params['table_type'] = self::DL_AR_TABLE_TYPE;
		}

		if($from && $to) {
			//use times. For now just do everything
		}

		try {
			$resp = $this->client->request('GET',$endpoint,['query' => $params]);
		} catch(Exception $e) {
			echo '<div style="background: white; color: black;">';
			echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
			echo '<pre>';
			print_r($e);
			echo '</pre>';
			echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
			echo '</div>';
		}

		if($resp->getStatusCode() == 200) {
			$raw = $resp->getBody();
			$arr = $this->cleanResponse(json_decode($raw));

			$responses = array();
			foreach($arr['data'] AS $row) {
				if($row->table_id == 10001) {
					$obj = new DLAPIEventResponse($row);
				} else {
					$obj = new DLAPIResponse($row);
				}
				$responses[] = $obj;
			}
			return $responses;
		}
	}

	public function getAllCampaignBucketsForOrganization($orgID) {
		
		$endpoint = 'campaigns/';
		$params = [
			'organization_id' => $orgID
		];

		$uri = http_build_query($params);

		$resp = $this->client->request('GET',$endpoint,['query' => $params]);

		if($resp->getStatusCode() == 200) {
			$raw = $resp->getBody();
			$arr = $this->cleanResponse(json_decode($raw));

			$data = $arr['data']->campaigns;
			
			//we could do processing here I guess....not really sure what this gets us but so be it
			return $data;
			
			if(count($arr['errors'])) {
				//what do we do with these.....
				return false;
			}
		} else {
			throw new Exception('Soemthing went wrong with the request. Response was: '.$res->getStatusCode());
		}
	}

	/**
	 * Sends a request to the API to do a check if this person exists. Requires at least org_id, first name, last name and one of address/city, email or phone
	 */
	public function doesPersonExist($org_id,$fname,$lname,$address=null,$postal=null,$email=null,$home_phone=null,$work_phone=null) {

		if(empty($fname) || empty($lname) || !is_integer($org_id)) {
			throw new Exception('Need at least organization ID, first name and last name');
			//return false;
		}

		$match_endpoint = 'customers/match/';
		$params = array(
			'organization_id' => $org_id,
			'first_name' => $fname,
			'last_name' => $lname
		);

		$has_extra = false;

		if($address) {
			$has_extra = true;
			$params['street'] = $address;
		}

		if($postal) {
			$has_extra = true;
			$params['zipcode'] = $postal;
		}

		if($email) {
			$has_extra = true;
			$params['email'] = $email;
		}

		if($home_phone) {
			$has_extra = true;
			$params['home_phone'] = $home_phone;
		}

		if($work_phone) {
			$has_extra = true;
			$params['work_phone'] = $work_phone;
		}

		if(!$has_extra) {
			throw new Exception('Need one of address, phone, email or postal');
			//return false;
		}

		$uri = http_build_query($params);

		$resp = $this->client->request('GET',$match_endpoint,['query' => $params]);

		if($resp->getStatusCode() == 200) {
			$raw = $resp->getBody();
			$arr = $this->cleanResponse(json_decode($raw));

			if(count($arr['errors'])) {
				//what do we do with these.....
				return false;
			}
		} else {
			throw new Exception('Soemthing went wrong with the request. Response was: '.$res->getStatusCode());
		}

		if(count($arr['data'])) {
			return $arr['data']->id;
		}

		return false;
	}

	private function cleanResponse($response) {
		return [
			'data' => $response->data,
			'errors' => $response->errors
		];
	}

	public function sendPrivateSaleEventTransaction($customer_id,$type) {}

	public function getAllAppointmentRowsForDealerByMonth($dealerID,$month,$year=null) {
		if(!$year || !is_integer($year)) {
			$year = date('y');
		}

		if($year > 2000) {
			$year = $year - 2000;
		}

		$year = sprintf("%02d",$year);
		$table = sprintf("ps_appointments_%02d%02d a",$month,$year);

		//fuck me....why does the dealerID not get saved here? This giant-ass join for no reason......
		//if there are no first or last names, or they are null, just move along we will never match them
/*		$rows = $this->db->where('e.dealerID',$dealerID)
			->where("(a.firstName IS NOT NULL AND a.firstName != '') OR (a.lastName IS NOT NULL AND a.lastName != '')")
			->join('ps_events e','a.eventID = e.eventID','INNER')
			->join('ps_dealers d','e.dealerID = d.dealerID','INNER')
			->get($table,array(0,500),["a.*",'d.dealerName','d.DLOrganizationID']);*/
		$query = <<<SQL
-- SELECT a.firstName,a.lastName,a.address,a.city,a.postalCode,a.mainPhone,a.mobilePhone,a.email,LOWER(a.source) AS source, d.dealerName,d.DLOrganizationID
SELECT a.*,LOWER(a.source) AS source, d.dealerName,d.DLOrganizationID,d.dealerID
FROM {$table}
INNER JOIN ps_events e
ON (e.eventID = a.eventID)
INNER JOIN ps_dealers d
ON (e.dealerID = d.dealerID)
WHERE d.dealerID = ?
AND source like '%invite%'
LIMIT 50
SQL;
	$rows = $this->db->rawQuery($query,[$dealerID]);

		foreach($rows AS $row) {
			$fname = $row['firstName'];
			$lname = $row['lastName'];
			$addr = $row['address'];
			$city = $row['city'];
			$prov = $row['province'];
			$postal = $row['postalCode'];
			$mainPhone = $row['mainPhone'];
			$mobilePhone = $row['mobilePhone'];
			$email = $row['email'];
			$sources = explode(',',trim($row['source'],','));
			$org_id = $row['DLOrganizationID'];
/*echo '<div style="background: white; color: black;">';
echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
echo '<pre>';
print_r("{$org_id}-{$row['firstName']}-{$row['lastName']}-{$row['source']}");
echo '</pre>';
echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
echo '</div>';*/
			/*echo '<div style="background: white; color: black;">';
			echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
			echo '<pre>';
			print_r($row);
			echo '</pre>';
			echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
			echo '</div>';*/
			//doesPersonExist($org_id,$fname,$lname,$address=null,$postal=null,$email=null,$home_phone=null,$work_phone=null)

			if($contactID = $this->doesPersonExist($org_id,$fname,$lname,$addr,$postal,$email,$mainPhone,$mobilePhone)) {
				//okay this person DOES exist so lets check the sources....if they exist as an array add them to the array to process later
				///nope...if they have a sold they may not have a source....
				$row['contact_id'] = $contactID;
				$row['customer_id'] = $contactID;
				$row['sources'] = $sources;
				$table = (string)sprintf("%02d%d",$month,$year);
				$row['db_table'] = $table;
				$this->validUsers[] = $row;
/*				if(is_array($sources)) {
					echo '<div style="background: white; color: black;">';
					echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
					echo '<pre>';
					print_r($sources);
					echo '</pre>';
					echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
					echo '</div>';
					$row['contact_id'] = $contactID;
					$row['sources'] = $sources;
					$this->validUsers[] = $row;
				}*/
			} 
		}
	}
}

class DLAPIResponse {
	protected $data = [];
	protected $fields = [
		'id',
		'created_at',
		'updated_at',
		'organization_id',
		'customer_id',
		'customer_vehicle_id',
		'marketing_id',
		'inventory_id',
		'updater_type',
		'updater_id',
		'table_type',
		'table_id',
		'event_type',
		'extra_data',
		'user_id',
		'alert_id',
		'uuid',
		'webhook_response'
	];

	public function __construct($row) {
		foreach($row as $field=>$val) {
			$this->$field = $val;
		}
	}

	public function __get($key) {
		return $this->data[$key];
	}

	public function __set($key,$val) {
		if(in_array($key,$this->fields)) {
			$this->data[$key] = $val;
		}
	}
}

class DLAPIEventResponse extends DLAPIResponse {
	public function __get($key) {
		if(strtolower($key) == 'event') {
/*			echo '<div style="background: white; color: black;">';
			echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
			echo '<pre>';
			print_r($this->data['event_type']);
			echo '</pre>';
			echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
			echo '</div>';
			echo '<div style="background: white; color: black;">';
			echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
			echo '<pre>';
			print_r(DLEventAPI::$classes[$this->data['event_type']]);
			echo '</pre>';
			echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
			echo '</div>';*/
			$json = json_decode($this->data['extra_data']->payload);
			$json->event_name = $this->makeNiceEvent($this->data['event_type']);
			return $json;
/*			$className = DLEventAPI::$classes[$this->data['event_type']];
			$obj = new $className(ARDB::getInstance());
			$obj->setData($this->data['extra_data']);
			echo '<div style="background: white; color: black;">';
			echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
			echo '<pre>';
			print_r($json);
			echo '</pre>';
			echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
			echo '</div>';*/
		}
		return parent::__get($key);
	}

	protected function makeNiceEvent($event) {
		return DLEventAPI::$class_types[$event];
	}
}

class DLPrivateSaleEventTransaction {
	protected $events = [];
	protected $tasks = [];

	protected $data = [];
	protected $db;
	protected $fields = array(
		'id', 
		'customer_id',
		'dealer_id', 
		'organization_id', 
		'contact_id', 
		'created_date', 
		'updated_date',
		'db_table'
	);

	public function __construct($db) {
		if(is_array($this->_fields)) {
			$this->fields = array_merge($this->fields, $this->_fields);
		}
		$this->data = array_fill_keys($this->fields,null);
		$this->db = $db;
	}

	public function doQuery() {

	}

	public function setData($data) {
		foreach($data AS $field=>$val) {
			$this->$field = $val;
		}
	}

	public function getData() {
		return $this->data;
	}

	public function fixData($data) {
		foreach($this->fields AS $field) {
			if($field == 'id') {
				$this->data['id'] = $data['appointmentID'];
				continue;
			}
			if($field == 'event_id') {
				//cache the eventObj so we don't hit the DB on every iteration
				if(!array_key_exists($data['eventID'],$this->events)) {
					$this->events[$data['eventID']] = Event::byId($data['eventID']);
				}
				$this->data['event_id'] = $data['eventID'];
				continue;
			}
			if($field == 'dealer_id') {
				$this->data['dealer_id'] = $data['dealerID'];
				continue;
			}
			if($field == 'staff_id') {
				$this->data['staff_id'] = $data['staffID'];
				continue;
			}
			if($field == 'organization_id') {
				$this->data['organization_id'] = $data['DLOrganizationID'];
				continue;
			}
			if($field == 'created_date') {
				$this->data['created_date'] = $data['timestamp'];
				continue;
			}

			if($field == 'updated_date') {
				$this->data['updated_date'] = $data['timestamp'];
				continue;
			}
			$this->data[$field] = $data[$field];
		}
	}

	public function __set($key,$val) {
		if($key == 'data') {
			return;
		}
		if(in_array($key,$this->fields)) {
			$this->data[$key] = $val;
		}
	}

	public function __get($key) {
		return $this->data[$key];
	}
}

class PSEventCustomerMadeAppointment extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'booked_by_type',
		'booked_by_type_id',
		'sales_rep_id',
		'appointment_time',
		'arrived_time'
	];

	public function fixData($data) {
		parent::fixData($data);
		$this->appointment_time = $data['appointmentTime'];
		$this->arrived_time = $data['arrivedTime'];
		if($data['trainerAppointment'] == 'yes') {
			$this->booked_by_type = 1;
			//why is the trainerID not in here as staffID.....like....seriously?
			$this->booked_by_type_id = $this->events[$data['eventID']]->trainerID;
		} else {
			//dunno what the other options are....probably ARC which I assume here
			$this->booked_by_type = 2;
			$this->booked_by_type_id = 0;
		}
	}
}

class PSEventShow extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'event_id',
		'arrival_time',
		'prospect',
		'sales_rep_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		$this->arrival_time = $data['arrivedTime'];
		$this->prospect = $data['prospect'];
		$this->sales_rep_id = $data['salesRepID'];
	}
}


class PSEventVehicleSold extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'event_id',
		'model_year',
		'make',
		'model',
		'new',
		'vin',
		'notes',
		'sales_rep_id',
		'sold_via'
	];

	public function fixData($number="1",$data) {
		parent::fixData($data);
		$sold_f = "sold{$number}";
		$make_f = "make{$number}";
		$model_f = "model{$number}";
		$year_f = "year{$number}";
		$newused_f = "newUsed{$number}";
		$vin_f = "vin{$number}";
		$notes_f = "description{$number}";
		$salesrep_f = "salesrep{$number}";
		$source_f = "source{$number}";

		$this->model_year = $data[$year_f];
		$this->make = $data[$make_f];
		$this->model = $data[$model_f];
		$this->new = ($data[$newused_f] == 'new' ? true : false);
		$this->notes = $data[$notes_f];
		$this->sales_rep_id = $row['salesrepID'];
		$this->sold_via = ($row['soldOver'] == 'phone' ? 1 : 0);
		$this->vin = $data[$vin_f];
	}
}

class PSEventVehicleTradeIn extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'event_id',
		'model_year',
		'make',
		'model',
		'vin',
		'notes'
	];

	public function fixData($number="1",$data) {
		parent::fixData($data);
		$year_f = "tradeYear{$number}";
		$make_f = "tradeMake{$number}";
		$model_f = "tradeModel{$number}";

		$this->model_year = $data[$year_f];
		$this->make = $data[$make_f];
		$this->model = $data[$model_f];
	}
}

class PSEventVehicleTestDrive extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'event_id',
		'notes',
		'sales_rep_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		$this->notes = $data['testDriveDetails'];
		$this->sales_rep_id = $data['salesrepID'];
	}
}

class PSEventCall extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'staff_id',
		'event_id'
	];

	public function fixData($row) {
		parent::fixData($row);
	}
}

class PSEventARCCall extends PSEventCall {
	protected $_fields = [
		'task_id',
		'disposition',
		'notes'
	];

	public function fixData($data) {
		parent::fixData($data);
		if(is_array($this->tasks[$data['eventID']]) && array_key_exists(Task::ARC,$this->tasks[$data['eventID']])) {
			$arcCall = $this->tasks[$data['eventID']][Task::ARC];
		} else {
			$arcCall = Task::byEventIDAndType($eventID,Task::ARC);
			$this->tasks[$data['eventID']][Task::ARC] = $arcCall;
		}
		$this->task_id = $arcCall->id;
		$this->disposition = $data['arcProspect'];
		$this->notes = $data['notes'];
	}
}

class PSEventTrainerCall extends PSEventCall {
	protected $_fields = [
		'disposition',
		'follow_up_date',
		'event_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		$followUpRow = $this->db->where('eventID',$data['eventID'])
				->where('appointmentID',$data['appointmentID'])
				->where('dealerID',$data['dealerID'])
				->getOne('ps_dumpster_followup');
		if(isset($followUpRow['followUpDate'])) {
			$this->follow_up_date = $followUpRow['followUpDate'];
		}
		$this->disposition = $data['prospect'];
	}
}

class PSEventMail extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'task_id',
		'sent_date',
		'event_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		if($task = $this->getInviteDataRow($data['eventID'])) {
			$this->sent_date = $task->printed;
			$this->task_id = $task->id;
		}
	}

	private function getInviteDataRow($eventID) {
		if(is_array($this->tasks[$data['eventID']]) && array_key_exists(Task::INVITATIONS,$this->tasks[$eventID])) {
			//$inviteTasks = $this->tasks[$eventID][Task::INVITATIONS];
			$task = $this->tasks[$eventID][Task::INVITATIONS];
		} else {
			$inviteTasks = Task::byEventIDAndType($eventID,Task::INVITATIONS);
			if(!is_array($inviteTasks)) {
				$task = array();
			} else {
				//assume the first one is the one we want if there are multiples
				$task = $inviteTasks[0];
			}
			$this->tasks[$eventID][Task::INVITATIONS] = $task;
		}
		return $task;
	}
}

class PSEventCustomerShowedUp extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'event_id',
		'arrival_time',
		'prospect',
		'sales_rep_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		$this->arrival_time = $data['arrivedTime'];
		$this->prospect = $data['prospect'];
		$this->sales_rep_id  = $data['salesRepID'];
	}
}

class PSEventWebRegistration extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'task_id',
		'source',
		'code',
		'registration_date',
		'appraisal_high',
		'appraisal_low'
	];

	public function fixData($data) {
		parent::fixData($data);
		//hoo boy I hope there is another way.....
		preg_match("/Trade in Value: \$([\d,]+) - \$([\d,]+)/", $data['notes'], $matches);
		if(is_array($matches)) {
			if(isset($matches[1])) {
				$this->appraisal_low = str_replace(',','',$matches[1]);
			}
			if(isset($matches[2])) {
				$this->appraisal_high = str_replace(',','',$matches[2]);
			}
		}
		$this->source = $data['webSource'];
		$this->code = $data['webCode'];
		//I don't know where registration_date would be kept...
	}
}

//email sent to the users...this is sort of "at best" a guess....
class PSEventEmail extends DLPrivateSaleEventTransaction {
	protected $_fields = [
		'task_id',
		'sent_date',
		'event_id'
	];

	public function fixData($data) {
		parent::fixData($data);
		$row = $this->db->where('eventID',$data['eventID'])
				->orderBy('confirmed',DESC)
				->getOne('ps_tasks_email');
		$event = $this->events[$data['eventID']];
		if(is_array($row) && !empty($row['confirmed'])) {
			$this->sent_date = $data['confirmed'];
		} else {
			if(!$event->eventStartDate) {
				//well we're screwed....
				$this->sent_date = -1;
			}
			$f = new DateTime($event->eventStart);
			//assume the email went out 2 days before the event start
			$f->sub(new DateInterval('P2D'));
			$h = $f->format('Y-m-d');
			$this->sent_date = $h;
		}
	}
}

$api = new DLEventAPI();

$events = $api->getEventDataForCustomer(1075,11114097,null,null,true);
echo '<div style="background: white; color: black;">';
echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
echo '<pre>';
print_r($events);
echo '</pre>';
echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
echo '</div>';
foreach($events AS $thing) {
	echo '<div style="background: white; color: black;">';
	echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
	echo '<pre>';
	print_r($thing->event);
	echo '</pre>';
	echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
	echo '</div>';
}
exit;
//$api->getAllAppointmentRowsForDealerByMonth(406,7,2015);
//$api->updateDLAppointments();
//exit;

$test = [
'id' => 86295,
'customer_id' => 11114097,
'dealer_id' => 406,
'organization_id' => 1075,
'contact_id' => 11114097,
'created_date' => date('Y-m-d'),
'updated_date' => date('Y-m-d'),
'task_id' => 18905,
'sent_date' => '2015-06-30',
'event_id' => 40467,
]; 

//WORKS!
$api->sendEventPayloadToDL($test,DLEventAPI::MAIL_SENT);
exit;

//get campaign buckets
$b = $api->getAllCampaignBucketsForOrganization(2381); //derrick seems to be the only one with data
//$b = $api->getAllCampaignBucketsForOrganization(2433); //durham has none, so also a good test :)
echo '<div style="background: white; color: black;">';
echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
echo '<pre>';
print_r($b);
echo '</pre>';
echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
echo '</div>';
exit;

try {
	if($id = $api->doesPersonExist(2381,'gail','hane',null,null,'GAILH03@TELUS.NET')) {
		//this person exists!
	} else {
		//this person does NOT exist!
	}
} catch(Exception $e) {
	echo '<div style="background: white; color: black;">';
	echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
	echo '<pre>';
	print_r($e->getMessage());
	echo '</pre>';
	echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
	echo '</div>';
	exit;
}
echo '<div style="background: white; color: black;">';
echo '<span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE START LINE '.__LINE__.' FROM '.__FILE__.'</span><br/>';
echo '<pre>';
print_r($id);
echo '</pre>';
echo '<br/><span style="background: #CC0000; color: #FFFFFF; padding-left: 1em; padding-right: 1em;">REMOVE END LINE '.__LINE__.'</span>';
echo '</div>';
?>