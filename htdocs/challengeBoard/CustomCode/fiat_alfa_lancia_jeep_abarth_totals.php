<?php

$eventsArray2 = $eventsArray = displayEventsStats($events,array('oc','fiatSold','alfaRomeoSold','lanciaSold','jeepSold', 'abarthSold'));



$customCode1HTML = 
	'<style>
		.model {font-family:MyFont;font-size:12pt;white-space:nowrap}
		.sold {font-size:9pt;color:white; margin-top: 3px;}
		.soldTbl td {padding-right:10px}
	</style>
	<table cellspacing="0" cellpadding="0" style="width:60%; margin-top: 20px;" class="soldTbl">
		<tr>
			<td>
				<img src="CustomCode/images/fiat_alfa_lancia_jeep_abarth_totals/fiatlogo.png" />
				<div class="sold">'. number_format($eventsArray2['totals']['fiatSold']) .' ventes</div>
			</td>
			<td>
				<img src="CustomCode/images/fiat_alfa_lancia_jeep_abarth_totals/alfalogo.png" />
				<div class="sold">'. number_format($eventsArray2['totals']['alfaRomeoSold']) .' ventes</div>
			</td>
			<td>
				<img src="CustomCode/images/fiat_alfa_lancia_jeep_abarth_totals/lancialogo.png" />
				<div class="sold">'. number_format($eventsArray2['totals']['lanciaSold']) .' ventes</div>
			</td>
			<td>
				<img src="CustomCode/images/fiat_alfa_lancia_jeep_abarth_totals/jeeplogo.png" />
				<div class="sold">'. number_format($eventsArray2['totals']['jeepSold']) .' ventes</div>
			</td>
			<td>
				<img src="CustomCode/images/fiat_alfa_lancia_jeep_abarth_totals/abarthlogo.png" />
				<div class="sold">'. number_format($eventsArray2['totals']['abarthSold']) .' ventes</div>
			</td>
		</tr>
	</table>';

?>