<?php

	include_once('arSession.php');

	include_once('loginUtils.php');
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	include_once('dataUtils.php');


	if(!checkEncrypt($_GET['id'],$_GET['ekey'],'quote'))
	{
		echo 'Invalid Key';
		exit;
	}


	use Philo\Blade\Blade;
	$blade = new Blade(AR_VIEWS_FOLDER,AR_CACHEDVIEWS_FOLDER);
	$bladeParams = [];


	$db = new ARDB();
	$quote = Quote::byId($_GET['id']);
	$owner = $quote->owner;
	if($quote->ownerTypeID == Quote::OWNER_TYPE_DEALER) {
		$dealer = $quote->dealer;
	} else {
		$dealer = array_shift($owner->dealers);
	}
	$staff = $quote->staff;

	if($dealer->language->major == 'fr') {
		$quoteLanguage = 'fr';
	} else {

		$quoteLanguage = 'en';
	}

	include_once('lang/'.$quoteLanguage.'.php');


	if(!empty($quote->currencyID)) {
		$lang['currency'] = $quote->currency->currencySymbol;
	} else if($dealer->nationID == NATION_UK) $lang['currency'] = '£';
	else if($dealer->nationID == NATION_EU) $lang['currency'] = '€';
	else $lang['currency'] = '$';


	$startDate = new DateTime($quote->start);
	$endDate = new DateTime($quote->end);
	$interval = $endDate->diff($startDate);
	$effectiveDays = $interval->format('%a');

	$ar = displayAREntity($dealer->toArray(),$quote->start);

	uasort($quote->items,function ($a,$b) {
		if(empty($a->order) && empty($b->order))  return $a->id > $b->id;
		else if(empty($a->order))  return 1;
		else if(empty($b->order))  return 0;
		else if($a->order == $b->order) return $a->id > $b->id;
		else return $a->order > $b->order;
	});



	$append = [];
	if($quote->type->id == WorksheetType::NONPRIME_PACKAGE_1_EVENT) {
		$template = 'nonprime_package_1_event_en_quote';
	} else if($quote->type->id == WorksheetType::NONPRIME_PACKAGE_3_EVENTS) {
		if($dealer->provinceID == PROVINCE_QC)	 {
			$template = 'nonprime_package_3_events_qc_quote';
		} else {
			$template = 'nonprime_package_3_events_en_quote';
		}
	} else if($quote->type->id == WorksheetType::CA_FCA_DRIVE || $quote->type->id == WorksheetType::CA_NONFCA_DRIVE) {
		$template = 'drive_quote';
		$ar = AREntity::byId(AREntity::ARTI)->toArray();
	} else if($quote->dealer->nationID == NATION_EU || $quote->dealer->nationID == NATION_UK) {
		if($quoteLanguage == 'fr') {
			$template = 'eu_fr_quote';
		} else {
			$template = 'eu_en_quote';
		}
		$ar = AREntity::byId(AREntity::ARSM)->toArray();
		$showTotal = true;
	} else {
		$showTotal = true;
		$template = $quoteLanguage . '_quote';
		$append[] = 'calendar';
	}


	$arEntity = $quote->arEntity;
	if($arEntity instanceof AREntity) {
		$ar = $arEntity->toArray();
	}

	$totalPrice = 0;
	$items = array();
	$quoteItems = $quote->items;
	if(!empty($quoteItems)) {
		foreach($quote->items as $item) {
			$itemType = $item->type->name;

			$arr = [];
			$arr['description'] = nl2br($item->description);
			if(empty($item->quantity)) $arr['quantity'] = $lang['tbd'];
			else $arr['quantity'] = $item->quantity;

			if(empty($item->unitPrice)) $arr['unitPrice'] = $lang['included'];
			else $arr['unitPrice'] = ($quote->dealer->countryID == COUNTRY_CA && $quoteLanguage == 'fr' ? $item->unitPrice . $lang['currency'] : $lang['currency'].$item->unitPrice);

			if(is_numeric($item->quantity) && is_numeric($item->unitPrice)) {
				$arr['total'] = ($quote->dealer->countryID == COUNTRY_CA && $quoteLanguage == 'fr' ? ($item->quantity * $item->unitPrice) . $lang['currency'] : $lang['currency'].($item->quantity * $item->unitPrice));
				$totalPrice += ($item->quantity * $item->unitPrice);
			}
			else if(is_numeric($item->quantity)) $arr['total'] = $lang['included'];
			else $arr['total'] = $lang['tbd'];

			$items[$itemType] = $arr;
		}
	}

	$locale = $dealer->language->minor;
	if(empty($locale)) {
		$locale = 'en_US';
	}

	$bladeParams['trans'] = new ARTranslator($locale);
	$bladeParams['quote'] = $quote;
	$bladeParams['items'] = $items;
	$bladeParams['staff'] = $staff;
	$bladeParams['owner'] = $owner;
	$bladeParams['dealer'] = $dealer;
	$bladeParams['lang'] = $lang;
	$bladeParams['quoteLanguage'] = $quoteLanguage;
	$bladeParams['ar'] = $ar;
	if(!empty($ar['arEntityID'])) {
		$bladeParams['arEntity'] = AREntity::byId($ar['arEntityID']);
	}
	$bladeParams['template'] = $template;
	$bladeParams['showTotal'] = $showTotal;
	$bladeParams['totalPrice'] = $totalPrice;
?>