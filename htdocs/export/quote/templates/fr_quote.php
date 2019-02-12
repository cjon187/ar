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
<br><font style="font-weight:bold;font-size:18pt">ABSOLUTE RESULTS – SOUMISSION DE VENTE PRIVÉE <?= ($quote->deleted != '' ? '<font style="color:red">DELETED</font>' : '') ?></font>
<br>
<br><font style="font-weight:bold;font-size:14pt">CONDITIONS ET MODALITÉS</font>
<br>
<br>Cette SOUMISSION (la « Soumission ») entre en vigueur en date du <b><?= dateLang($quote->start,'fr') ?></b> (la « Date d’entrée en vigueur ») à <b><?= dateLang($quote->end,'fr') ?></b> entre ABSOLUTE RESULTS PRODUCTIONS LIMITED, une personne morale incorporée en vertu des lois de la Colombie-Britannique, Canada (ci-après désignée « ARPL ») et <b><?= $owner->name ?></b> (ci-après désignée le « Client »), chacune étant ci-après désignée comme une « partie » et collectivement comme les « parties », à l’égard de certains produits et services à être fournis par ARPL au Client. 
<br>
<br>Compte tenu des engagements et des obligations mutuels ci-après contenus, les parties conviennent de ce qui suit : 
<br>
<br>ARPL accepte de fournir au Client et le Client accepte de payer pour les produits et services suivants (ci-après collectivement désignés les « Produits et services ») par cette soumission.
<br>
<br><b>EN FOI DE QUOI</b> les parties ont demandé à leurs dirigeants respectifs dûment autorisés de signer la présente soumission en date de la Date d’entrée en vigueur. Les signatures ci-dessous confirment l’approbation de la soumission dans son intégralité.
<br>

<table cellspacing="0" cellpadding="0" class="productTbl">
	<tr>
		<td style="width:50%"><b>Description</b></td>
		<td style="width:25%"><b>Quantité</b></td>
		<td style="width:25%"><b>Prix unitaire</b></td>
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

<br>La soumission est basée sur une invitation et une conquête standard. Les formats plus grands sont sujets à un coût additionnel. 
<?= (!empty($effectiveDays) ? '<br>La soumission est valide pour ' . $effectiveDays . ' jours à partir de la date d’entrée en vigueur.' : '') ?>
<br><br>

<?php signatureBoxHTML() ?>

<page_footer>
	
	<br><br>
	<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
</page_footer>
</page>
