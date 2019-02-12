<?php
require('defines.php');
include_once('emailUtils.php');
//require_once('AbstractARGeocoder.class.php');
//include_once('EverythingLocationGeocoderResponse.class.php');

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Exception\ServerException;

use Carbon\Carbon;
error_reporting(E_ALL);
$db = new ARDB();

$username = 'mlittle@absoluteresults.com';
$password = 'kNJaf4QLo2e6POUZ0rXY@';

$send_to = 'mlittle@absoluteresults.com';
$send_cc = [];

$base_uri = 'https://saas.loqate.com/rest/';
//$base_uri = 'http://devel.ar.absoluteresults.com/test/';

$client = new Client([
	'base_uri' => $base_uri
]);

//first call the authorization endpoint
$auth_endpoint = 'authorize.php';
$auth_params = [
	'username' => $username,
	'password' => $password,
	'expiration' => '1hour'
];

$auth_resp = $client->request('POST',$auth_endpoint,[
	'form_params' => $auth_params
	]);

if($auth_resp->getStatusCode() == 200) {
	$auth_ret_raw = $auth_resp->getBody()->getContents();
	$auth_ret = json_decode($auth_ret_raw);
	if($auth_ret->status =! 'OK') {
		//something went wrong
		throw new Exception('Error calling the auth endpoint');
		exit;
	}
	$auth_session = $auth_ret->session_id;
} else {
	throw new Exception('Error getting to auth endpoint');
}

if(!isset($auth_session)) {
	throw new Exception('Need an authentication token');
}

$startdate = Carbon::parse('first day of last month');
$enddate = Carbon::parse('last day of last month');

$report_endpoint = 'report.php';
$report_params = [
	'lqtkey' => EVERYTHING_LOCATION_API_KEY,
	'sessionid' => $auth_session,
	//'metric' => 'country',
	'startdate' => $startdate->toDateString(),
	'enddate' => $enddate->toDateString()
];

$report_resp = $client->request('GET',$report_endpoint,[
	'query' => $report_params
	]);
if($report_resp->getStatusCode() == 200) {
	$report_ret_raw = $report_resp->getBody()->getContents();
	$report_ret = json_decode($report_ret_raw);
	if($report_ret->status != 'OK') {
		throw new Exception('Error calling report endpoint');
		exit;
	}
	$usage = [];
	$filename = '/tmp/'.time().'_everythinglocation.csv';
	$fp = fopen($filename,'w');
	fputcsv($fp,['ISO','Country','Count']);
	foreach($report_ret->report->Countries AS $country) {
		$country_iso = strtoupper($country->Code);
		if($country_iso == 'UN') {
			$country_name = 'Unknown/Not Detected';
		} else {
			$countryObj = Country::byISO($country_iso);
			$country_name = $countryObj->name;
		}
		$usage_row = [
			'iso' => $country_iso,
			'name' => $country_name,
			'count' => $country->Count
		];
		$usage[] = $usage_row;
		fputcsv($fp,$usage_row);
	}
	rewind($fp);
	fclose($fp);

	$email_subject = "Everything Location Usage Report for {$startdate->format('F Y')}";
	$email_body = $email_subject;
	$file_arr = [
			[
				'path' => $filename,
				'name' => "everything_location_usage_{$startdate->toDateString()}.csv",
				'type' => 'text/csv'
			]
		];
	if(!EmailController::sendEmail($email_subject,$email_body,'noreply@absoluteresults.com',$send_to,$send_cc,null,$file_arr,Email::TYPE_EVERYTHING_LOCATION_BILLING)) {
		echo "Uh oh..";exit;
	}

	unlink($filename);
//die('windows suxxor');

} else {
	throw new Exception('Error getting to report endpoint');
}
?>