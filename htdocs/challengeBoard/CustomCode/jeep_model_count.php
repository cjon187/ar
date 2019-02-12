<?php

$eventsArray2 = displayEventsStats(array_keys(!empty($events) ? $events : []),array('oc','jeepSold','jeepModelsSold'));



$customCode1HTML =
	'<style>
		.model {font-family:MyFont;font-size:12pt;white-space:nowrap}
		.sold {font-family:MyFont;font-size:12pt;color:yellow}
		.soldTbl td {padding-right:10px; color: white;}
	</style>
	<table cellspacing="0" cellpadding="0" style="width:80%; margin-top: 20px;" class="soldTbl">
		<tr>
			<td>
				<img src="CustomCode/images/jeepModelCount/wrangler.png" />
				<div class="model">Wrangler</div>
				<div class="sold">'. number_format($eventsArray2['totals']['jeepWranglerSold']) .' Sold</div>
			</td>
			<td>
				<img src="CustomCode/images/jeepModelCount/gcherokee.png" />
				<div class="model">Grand Cherokee</div>
				<div class="sold">'. number_format($eventsArray2['totals']['jeepGrandCherokeeSold']) .' Sold</div>
			</td>
			<td>
				<img src="CustomCode/images/jeepModelCount/cherokee.png" />
				<div class="model">Cherokee</div>
				<div class="sold">'. number_format($eventsArray2['totals']['jeepCherokeeSold']) .' Sold</div>
			</td>
			<td>
				<img src="CustomCode/images/jeepModelCount/renegade.png" />
				<div class="model">Renegade</div>
				<div class="sold">'. number_format($eventsArray2['totals']['jeepRenegadeSold']) .' Sold</div>
			</td>
		</tr>
	</table>';

?>