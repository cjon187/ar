<?php

$eventsArray2 = $eventsArray = displayEventsStats($events,array('oc','fiatSold','abarthSold', 'alfaRomeoSold','chryslerSold','jeepSold'));



$customCode1HTML = 
	'<style>
		.model {font-family:MyFont;font-size:12pt;white-space:nowrap}
		.sold {font-size:9pt;color:white; margin-top: 3px;}
		.soldTbl td {padding-right:10px}
		.soldTbl td {color:white}
	</style>
	<table cellspacing="0" cellpadding="2" style="width:80%; margin-top: 20px;" class="soldTbl">
		<tr>
			<td style="width:20%">
				<img src="CustomCode/images/fiat_abarth_alfa_chrysler_jeep_totals/fiatlogo.png" />
			</td>
			<td style="width:20%">
				<img src="CustomCode/images/fiat_abarth_alfa_chrysler_jeep_totals/abarthlogo.png" />
			</td>
			<td style="width:20%">
				<img src="CustomCode/images/fiat_abarth_alfa_chrysler_jeep_totals/alfalogo.png" />
			</td>
			<td style="width:20%">
				<img src="CustomCode/images/fiat_abarth_alfa_chrysler_jeep_totals/chryslerlogo.png" />
			</td>
			<td style="width:20%">
				<img src="CustomCode/images/fiat_abarth_alfa_chrysler_jeep_totals/jeeplogo.png" />
			</td>
		</tr>
		<tr >
			<td style="width:20%">
				'.number_format($eventsArray2['totals']['fiatSold']).' Sold
			</td>
			<td style="width:20%">
				'.number_format($eventsArray2['totals']['abarthSold']).' Sold
			</td>
			<td style="width:20%">
				'.number_format($eventsArray2['totals']['alfaRomeoSold']).' Sold
			</td>
			<td style="width:20%">
				'.number_format($eventsArray2['totals']['chryslerSold']).' Sold
			</td>
			<td style="width:20%">
				'.number_format($eventsArray2['totals']['jeepSold']).' Sold
			</td>
		</tr>
</table>';

?>
