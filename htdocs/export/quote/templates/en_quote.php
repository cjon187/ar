<?php

include_once('arSession.php');

include_once('loginUtils.php');
include_once('displayUtils.php');
include_once('mysqliUtils.php');
include_once('dataUtils.php');
include_once('pdfUtils.php');

include_once('../includes.php');

$db = new ARDB();
?>
<style>
	.title {font-weight:bold;font-size:15pt;display:inline}
	.productTbl {border-collapse:collapse;width:100%;}
	.productTbl td {border:1px solid black;padding:5px;margin:0px}

	.services td {width:500px;vertical-align:top;padding:5px;}

	.section {width:150px;}
</style>
<page>
<br><font style="font-weight:bold;font-size:18pt">ABSOLUTE RESULTS PRIVATE SALES QUOTE <?= ($quote->deleted != '' ? '<font style="color:red">DELETED</font>' : '') ?></font>
<br>
<br><font style="font-weight:bold;font-size:14pt">TERMS AND CONDITIONS</font>
<br>
<br>This SALES QUOTE (the "Quote") is effective as of <b><?= date('F j, Y',strtotime($quote->start)) ?></b> (the "Effective Date") to <b><?= date('F j, Y',strtotime($quote->end)) ?></b> between ABSOLUTE RESULTS PRODUCTIONS LTD., a corporation incorporated under the laws of British Columbia, Canada (“ARPL”) and  <b><?= $owner->name ?></b> (the "Client"), each a "party" and collectively the "parties", with respect to certain services provided by ARPL to the Client.  
<br>
<br>In consideration of the mutual covenants and promises contained herein, the parties agree as follows:
<br>
<br>ARPL agrees to provide and the Client agrees to pay for the products and services (“<b><u>Products & Services</u></b>”), all as described in and in accordance with this Quote.
<br>
<br><b>IN WITNESS WHEREOF</b> the parties have caused this Quote to be executed by their duly authorized signatories as of the Effective Date.  The signatures appearing below indicate approval and acceptance of the entire quote.
<br>
<br>
<table cellspacing="0" cellpadding="0" class="productTbl">
	<tr>
		<td style="width:50%"><b>Description</b></td>
		<td style="width:25%"><b>Quantity</b></td>
		<td style="width:25%"><b>Unit Price</b></td>
	</tr>
<?php
	if(is_array($quote->items)) {
		itemsBoxHTML($quote->items);
	}
?>		
	
</table>

<br>

<?php
	if(!empty($quote->notes)) {
?>
	<div style="width:100%;padding-bottom:15px">
		<?= nl2br($quote->notes) ?>
	</div>
<?php
	}
?>

<b>Disclaimer</b>
<br>Quote based on Standard invitation & conquest sizing. Larger sizes  subject to additional charge.
<br>See Absolute Results for details.
<br>*Up to two events at no additional charge, see Absolute Results for full details.
<br><br>

<?php signatureBoxHTML() ?>

<page_footer>
	
	<br><br>
	<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
</page_footer>
</page>
