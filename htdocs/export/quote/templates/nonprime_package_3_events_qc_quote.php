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
	.productTbl {border-collapse:collapse;}
	.productTbl td {border:1px solid black;padding:5px;margin:0px}

	.services td {width:500px;vertical-align:top;border-bottom:1px solid black;padding:5px;}

	.section {width:150px;}
</style>
<page>
<br><font style="font-weight:bold;font-size:18pt">CONVENTION DE SERVICES « CRÉDIT À RISQUE » <?= ($quote->deleted != '' ? '<font style="color:red">DELETED</font>' : '') ?></font>
<br>
<br><font style="font-weight:bold;font-size:14pt">QUÉBEC</font>
<br>
<br><font style="font-weight:bold;font-size:14pt">CONDITIONS ET MODALITÉS</font>
<br>
<br>Cette CONVENTION DE SERVICES (ci-après la « Convention ») est pour un (1) événement par mois, pour une durée déterminée de trois (3) mois consécutifs, entrant en vigueur en date du  <?= dateLang($quote->start,'fr') ?> (la « Date d’entrée en vigueur ») entre ABSOLUTE RESULTS PRODUCTIONS LTD., une personne morale incorporée en vertu des lois de la Colombie-Britannique, au Canada (ci-après « ARPL ») et <?= $quote->dealer->name ?> (ci-après le « Client »), chacune étant ci-après désignée comme une « partie » et collectivement comme les « parties », à l’égard de certains services fournis au Client par ARPL.  
<br>
<br>Compte tenu des engagements et des obligations mutuels ci-après contenus, les parties conviennent de ce qui suit :
<br>
<br>ARPL accepte de fournir au Client et le Client accepte de payer pour les services (ci-après les « Services »), tels que décrits dans et en conformité avec cette Convention. Cette Convention est composée de cette page de couverture, de l’Annexe A (Conditions et Modalités), de l’Annexe B (Services), et de l’Annexe C (Tarification).
<br>
<br>EN FOI DE QUOI les parties ont fait signer la présente Convention par leurs signataires dûment autorisés en date de la Date d’entrée en vigueur. Les signatures ci-dessous confirment l’approbation et l’acceptation de la Convention dans son intégralité.

<br>
<br>
<?php signatureBoxHTML('nonprime') ?>
<page_footer>
	
	<br><br>
	<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
</page_footer>
</page>
<page>
	<div class="title">ANNEXE A</div>
	<br><div class="title">TERMES ET CONDITIONS</div>
	<br>
	<br> 
	<br><b><u>1.	ÉCHÉANCIER.</u></b>
	<br>
	<br>1.1	ARPL s’engage à livrer les Services conformément aux échéanciers décrits à l’Annexe B.
	<br>
	<br>1.2	Le Client s’engage à fournir à ARPL toutes les données clients et à lui permettre d’administrer ces données aux fins requises pour livrer les Services décrits à l’Annexe B. Le Client déclare et garantit à ARPL qu’il a obtenu tous les consentements nécessaires pour permettre à ARPL de collecter, utiliser et divulguer les données des clients et de communiquer avec les clients du Client tel que requis pour livrer les Services.
	<br>
	<br>1.3	Le Client permet à ARPL et à ses employés et représentants, un « accès complet » et consensuel au « Dealertrack Portal » du Concessionnaire.  Cet accès est donné à des fins de soumission et de révision de demandes de crédit faites au nom du concessionnaire en question et est strictement réservé à l’obtention du crédit pour l’achat d’un (de) véhicule(s) neuf(s) ou usagé(s) de ce concessionnaire.
	<br>
	<br>1.4	Le Client collaborera avec ARPL et fournira à celle-ci toutes les informations, l’assistance et les ressources de soutien (logos, photos, information du Client, etc.) nécessaires afin de pouvoir fournir rapidement les Services suivant toute requête d’ARPL à cet effet et conformément à l’échéancier établi par celle ci. 
	<br>
	<br>1.5	Le Client reconnait et convient que la fourniture des Services par ARPL peut dépendre d’événements qui sont du seul contrôle du Client, et qu’ARPL n’aura aucune responsabilité ou ne sera aucunement autrement responsable pour tout retard ou écart dans la fourniture des Services qui serait dû à tout événement susmentionné, y compris tout défaut de fournir, en temps opportun, des instructions, des informations, des réponses aux questions, ou des approbations.
	<br>
	<br>1.6	S’il survient un événement de force majeure, les parties doivent immédiatement se consulter pour discuter de l’échéancier de livraison des Services par ARPL. Si les conséquences découlant d’une telle force majeure perdurent pendant plus de trente (30) jours, alors chacune des parties peut résilier la présente Convention. Aux fins des présentes, une « Force majeure » signifie tout événement hors du contrôle des parties, et qui est imprévisible, irrésistible, ou insurmontable et qui n’était pas connu à la Date d’entrée en vigueur. Il est entendu que de tels événements comprennent, sans s’y limiter, les tremblements de terre, les ouragans, les inondations, les guerres, les épidémies et les troubles civils. 
	<br>
	<br>
	<br>
	<br>
	<br><b><u>2.	FRAIS.</u></b>
	<br>
	<br>2.1	Le Client doit payer à ARPL les frais et les coûts prévus à l’Annexe C, plus les taxes applicables.
	<br>
	<br>2.2	Les frais et les coûts pour les Services seront facturés par ARPL et doivent être payés par le Client conformément à ce qui suit :
	<br>
	<br>(a)	ARPL facture le Client le 1er jour de chaque mois, ou le jour ouvrable suivant, si le 1er jour du mois est un dimanche ou un jour férié en Colombie-Britannique;
	<br>
	<br>(b)	Le Client doit payer le montant total de chaque facture émise par ARPL sous la clause 2.2(a), laquelle doit inclure le paiement préalable de l’ensemble des forfaits standards de prospects le ou avant le 15 de chaque mois après la date de la facture ou le jour ouvrable suivant si le 15 jour du mois est un dimanche ou un jour férié dans la province où le Client est située, tel qu’indiquée à la première page de cette Convention.  Par souci de clarté, le Client doit payer le montant total de la facture émise par ARPL en date du 1er jour de janvier le ou avant le 15 janvier.
	<br>
	<br>2.3	Pour tout montant résiduel du solde encore dû par le Client dans les trente (30) jours suivant la date de facturation, des frais pour paiement tardif de 18 % par année, composés et calculés mensuellement, seront ajoutés jusqu’à paiement complet, ce qui équivaut à un taux annuel de 19,56 %. Tous les frais doivent être en devise canadienne.
	<br>
	<br>2.4	ARPL se réserve le droit de suspendre les Services si elle ne reçoit pas paiement en temps opportun en vertu de cette Convention. Une telle suspension des Services ne donnera pas au Client un droit de résiliation ni un droit de réclamation pour manquement d’ARPL à ses obligations prévues dans cette Convention.
	<br>
	<br>2.5	Les parties conviennent expressément qu’ARPL demeure un entrepreneur indépendant et n’est pas un employé, agent, mandataire, coentrepreneur, franchisé, franchiseur, ni un associé du Client. Rien à la présente Convention ne peut être interprété comme venant créer ou établissant une relation d’employeur et employé entre le Client et ARPL ou tout employé, agent, ou mandataire de cette dernière. Cette Convention ne vient en rien créer et n’est pas réputée créer une société ou coentreprise entre les parties. Le Client convient de n’effectuer aucune retenue fiscale ni aucun paiement de taxes pour le compte d’ARPL.
	<br>
	<br><b><u>3.	PROPRIÉTÉ INTELLECTUELLE.</u></b>
	<br>
	<br>3.1	Le Client conserve tous les droits de propriété de toute propriété intellectuelle précédemment détenue par lui et de toute propriété intellectuelle créée ou développée par le Client pendant le terme de cette Convention (ci-après la « PI du Client »). Le Client autorise ARPL à copier, utiliser, modifier et autrement exploiter la PI du Client aussi souvent que cela est nécessaire pour livrer les Services. Il incombe au Client de s’assurer, et, par la présente, le Client déclare et donne garantie à ARPL que la PI du Client à être utilisée par ARPL pour fournir les Services n’enfreint pas, ne viole pas, ou ne détourne pas le moindre droit de propriété intellectuelle d’un tiers et que le Client a le droit non restreint de donner permission à ARPL d’utiliser la PI du Client tel que décrit ci-dessus.
	<br>
	<br>3.2	ARPL conserve tous les droits de propriété de tous produits qu’elle détenait précédemment, et qu’elle avait créés ou développés, par ou pour elle, lors de la livraison des Services, ainsi que tous les droits de propriété intellectuelle y afférents, y compris tous les droits d’auteur, droits moraux, brevets, marques de commerce, noms commerciales, marques de service, droits de conception et droits attachés aux dessins, droits attachés aux bases de données, droits attachés aux noms de domaine et autres droits de propriété intellectuelle similaires (qu’ils soient enregistrés ou non) et applications pour lesquelles de tels droits pourraient exister en quelque lieu que ce soit dans le monde. 
	<br>
	<br>3.3	Par la présente, le Client convient irrévocablement et inconditionnellement d’indemniser, de défendre et d’exonérer ARPL et ses administrateurs, dirigeants, employés, agents, mandataires et représentants contre tous recours de tierce partie, procédures, pertes, dommages, responsabilités, obligations, frais, réclamations, charges et dépenses, y compris les honoraires d’avocat, de quelque nature que ce soit, encourus par ARPL en lien avec : (i) l’utilisation par ARPL de la PI du Client tel qu’autorisé par cette Convention (y compris tout droit d’auteur, secret industriel, nom commercial, appellation commercial, brevet, droit de propriété intellectuelle et droit à la vie privée dans toute juridiction ou pays où la PI du Client serait utilisée); (ii) toute violation de toute déclaration, garantie ou engagement donnés aux termes de cette Convention; et (iii) toute réclamation de la part de tout client, utilisateur ou autre tierce partie concernant un produit ou un service fourni par le Client.
	<br>
	<br><b><u>4.	CONFIDENTIALITÉ ET NON-SOLLICITATION.</u></b>
	<br>
	<br>4.1	Chaque partie (ci-après la « Partie destinataire ») s’engage à garder confidentielle et à ne pas divulguer à qui que ce soit toute Information confidentielle de la partie divulgatrice (ci-après la « Partie divulgatrice »), à quiconque autre que les administrateurs, dirigeants, employés ou représentants de la Partie destinataire qui : (a) ont le « besoin de les connaître »; et (b) ont été informés de la nature confidentielle et du caractère exclusif de l’Information confidentielle, ainsi que des obligations imposées par cette Convention (ci-après les « Représentants »). La Partie destinataire prendra les mesures pour que ses Représentants se conforment aux obligations de confidentialité de cette Convention et elle sera responsable pour tout manquement à ces obligations de la part de tout Représentant. Le Partie destinataire n’utilisera, ni n’exploitera aucune des Informations confidentielles, excepté pour les fins d’exercice de ses droits ou l’exécution de ses obligations en vertu de cette Convention. Aux fins de cette Convention, « Information confidentielle » signifie toute information, matériel et données de la Partie divulgatrice : (i) qui est étiquetée comme ou comporte une désignation écrite à l’effet qu’elle est de nature confidentielle ou à caractère exclusif, (ii) dont la Partie destinataire a été avisée de la nature confidentielle ou du caractère exclusif, ou (iii) que, par la nature de telle information et/ou par les circonstances dans lesquelles telle information est divulguée, la Partie destinataire sait ou devrait raisonnablement savoir être confidentielle ou à caractère exclusif concernant la Partie divulgatrice ou ses filiales, et qui comprend, sans limitation, les informations suivantes : les données financières, les plans, les prévisions, la propriété intellectuelle, les méthodologies, les algorithmes, les conventions, l’intelligence du marché, les concepts techniques, l’information sur les clients, les analyses stratégiques et les publications.
	<br>
	<br>4.2	Les obligations de confidentialité contenues aux présentes ne s’appliquent pas aux informations qui : (a) sont ou deviennent connues du public sans aucune faute ou participation de la Partie destinataire; (b) étaient en possession de la Partie destinataire avant qu’elle ne les reçoivent de la Partie divulgatrice ou dont la Partie destinataire a obtenu possession par la suite, mais seulement si, en de tels cas, les informations lui ont été légalement transmises par une source autre que la Partie divulgatrice, et sans qu’elles soient sujettes à une quelconque obligation de confidentialité ou d’usage restreint; ou (c) sont indépendamment développées par la Partie destinataire, par des personnes qui n’avaient ni accès à, ni connaissance de ces Informations confidentielles provenant de la Partie divulgatrice.
	<br>
	<br>4.3	Nonobstant tout autre libellé compris dans les dispositions de la section 4, si la Partie destinataire devient ou peut devenir légalement tenue de divulguer une Information confidentielle, elle peut alors divulguer cette Information confidentielle dans la mesure requise par la loi, à la condition que : (a) la Partie destinataire notifie promptement à la Partie divulgatrice les mesures prises contre elle pour la forcer à divulguer (sauf si la loi le lui interdit); (b) la Partie destinataire coopère avec et aide la Partie divulgatrice pour tenter légalement d’empêcher ou de limiter la divulgation, ou pour obtenir une ordonnance de sauvegarde ou de confidentialité; et (c) dans la mesure où la divulgation serait toujours requise par la loi, la Partie destinataire prendre toutes les mesures raisonnables pour faire la divulgation sur une base confidentielle.
	<br>
	<br>4.4	La Partie destinataire s’engage à protéger l’Information confidentielle en utilisant le même degré de diligence qu’elle exercerait s’il s’agissait de sa propre information confidentielle, ce degré de dligence ne devant pas être inférieur au diligence raisonnable. L’Information confidentielle demeure la propriété exclusive de la Partie divulgatrice et aucun brevet, droit d’auteur, marque de commerce ou autre droit n’est autorisé sous licence, octroyé ou autrement transféré par cette Convention ni par la moindre divulgation de l’Information confidentielle à la Partie destinataire.
	<br>
	<br>4.5	Le Client ne doit pas, directement ou indirectement, en tout temps pendant la durée de cette Convention et pour une période de 12 mois suivant la résiliation ou l’expiration de cette Convention, sans le consentement écrit préalable d’ARPL : (a) inciter ou encourager tout employé ou entrepreneur d’ARPL à quitter son emploi ou rompre son engagement avec ARPL; ou (b) employer, tenter d’employer, aider quiconque à employer, ou retenir comme consultant ou entrepreneur, tout employé ou entrepreneur, ancien ou actuel, d’ARPL. La phrase précédente ne s’appliquera pas aux individus embauchés par l’intermédiaire d’une agence de services d’emploi indépendante (tant que l’agence n’a pas été chargée de solliciter une personne en particulier) ou par le biais d’une sollicitation générale qui ne vise pas en particulier les employés ou les entrepreneurs d’ARPL.
	<br>
	<br>4.6	Chaque partie reconnaît que la violation de cette section 4 engendrera un dommage irréparable à l’autre partie et qu’un tel dommage n’est pas susceptible de mesure précise aux fins de calcul d’un montant en dommages-intérêts pécuniaires. En conséquence, la partie ne violant pas cette section, en plus de tous ses recours en dommages-intérêts et autres recours légaux, aura le droit d’obtenir une injonction ou toute autre mesure de redressement équitable visant à empêcher une violation, actuelle ou pressentie, sans avoir besoin de déposer un cautionnement ou toute autre sûreté ou encore d’intenter tout autre recours.
	<br>
	<br><b><u>5.	TERME.</u></b>
	<br>
	<br>5.1	Le terme de cette Convention commence à la Date d’entrée en vigueur pour une durée déterminée de trois (3) mois seulement (ci-après le « Terme »). Le Terme ne sera PAS automatiquement reconduit, et une nouvelle Convention devra être signée, si le Client souhaite poursuivre avec un terme supplémentaire. 
	<br>
	<br>5.2 	ARPL peut, en tout temps et pour tout motif,  résilier cette Convention après avoir donné un préavis écrit de trente (30) jours au Client.
	<br>
	<br>5.3	Si l’une des parties fait défaut d’exécuter n’importe laquelle de ses obligations aux termes de cette Convention, la partie n’étant pas en défaut peut alors résilier immédiatement la Convention sur préavis d’une durée de trente (30) jours donné à la partie en défaut, et ce préavis entrant automatiquement en vigueur à moins que la partie en défaut ne pourvoie entièrement audit défaut dans ces trente (30) jours et à la satisfaction de la partie n’étant pas en défaut. Advenant que le Client fasse défaut de payer le solde entier dû à ARPL, ARPL peut, sur préavis écrit, immédiatement résilier cette Convention.
	<br>
	<br>5.4	 À la résiliation de cette Convention, le Client doit payer immédiatement tous les montants dûs et exigibles à ARPL pour les Services rendus à la date de résiliation en vertu des dispositions de l’Annexe B, ainsi que tous les déboursés remboursables qui sont raisonnables et qu’ARPL encourt ou va encourir en vertu de ses relations contractuelles avec des tiers, en lien avec les Services.
	<br>
	<br><b><u>6.	RÉSILIATION.</u></b>
	<br>
	<br>6.1	Les droits et obligations d’ARPL et du Client contenus aux articles 2, 3, 4, 5.4, 6, 7, 8 et 9 demeurent en vigueur malgré toute résiliation ou expiration de cette Convention. La résiliation de cette Convention opère sans préjudice de quelconque droit acquis par chacune des parties préalablement à la résiliation, y compris relativement à toute obligation de paiement du Client.
	<br>
	<br><b><u>7.	ABSENCE DE GARANTIE.</u></b>
	<br>
	<br>7.1 	ARPL ne donne aucune, et décline expressément toute, garantie, condition ou déclaration de quelque sorte que ce soit, expresse, statutaire ou implicite, concernant les Services, y compris celles en matière de commercialisation, de pertinence pour un usage particulier, de design, d’état, de qualité, de titre, ou d’absence de contrefaçon. ARPL ne garantit pas que les Services satisferont les exigences du Client ou que l’exécution des Services sera libre d’interruptions, de dommages, ou d’erreurs, ou que les résultats obtenus grâce aux Services seront exacts ou fiables.
	<br>
	<br><b><u>8.	LIMITATION DE RESPONSABILITÉ.</u></b>
	<br>
	<br>8.1	La responsabilité totale d’ARPL envers le Client en vertu de cette Convention ne doit pas dépasser le total des montants payés à ARPL par le Client selon cette Convention.
	<br>
	<br>8.2	EN AUCUN CAS L’UNE OU L’AUTRE DES PARTIES NE PEUT ÊTRE TENUE RESPONSABLE, QU’IMPORTE LA FORME DE LA RÉCLAMATION OU DU RECOURS, POUR : (i) PROFITS OU REVENUS DE TOUTES SORTES NON RÉALISÉS, OCCASIONS D’AFFAIRES RATÉES, PERTE D’OPPORTUNITÉS, PERTE D’ACTIVITÉS OU PERTES COMMERCIALES; (ii) PERTES D’ÉCONOMIES; (iii) LOGICIELS OU DONNÉES PERDUS; (iv) PERTE DE JOUISSANCE DE QUELCONQUE MATÉRIELS INFORMATIQUES, LOGICIELS, SYSTÈMES OU DONNÉES; OU (v), À L’EXCEPTION DE TOUTE VIOLATION DE LA SECTION 4, TOUTE PERTE INDIRECTE CAUSÉE DE QUELQUE MANIÈRE QUE CE SOIT ET SANS ÉGARD AU FAIT QUE L’AUTRE PARTIE AIT ÉTÉ AVISÉE DE LA POSSIBILITÉ DE TELS DOMMAGES.
	<br>
	<br><b><u>9.  CONDITIONS GÉNÉRALES.</u></b>
	<br>
	<br>9.1	Tous les préavis, requêtes, demandes et autres communications en vertu des présentes doivent être mis par écrit et seront réputés avoir été dûment donnés si remis en mains propres, par fac-similé, courriel ou courrier, avec frais de port payé, aux adresses figurant sur la première page de cette Convention ou toute autre adresse qui pourra être donnée par écrit par les parties. Ils seront réputés avoir été dûment reçus, en cas de remise en mains propres, à la date de la remise; en cas de fac-similé ou courriel transmis aux numéros de fac-similé et adresses courriel figurant, le cas échéant, à la première page de cette Convention, au jour ouvrable consécutif à la date de transmission; et en cas d’envoi par courrier aux adresses figurant sur la première page de cette Convention, au cinquième jour ouvrable suivant l’envoi postal; à moins  qu’entre le moment de l’envoi et la réception réelle du préavis, s’il y a une grève de la poste, un ralentissement ou tout autre différend en matière d’emploi qui pourrait affecter la livraison du préavis par courrier, puisqu’alors le préavis prendra effet seulement s’il est vraiment livré, faxé ou remis par courriel aux adresses figurant à la première page de cette Convention. 
	<br>
	<br>9.2 Si une disposition de la Convention est déclarée, pour quelque raison que ce soit, invalide, illégale, ou inapplicable, elle sera considérée comme pouvant être retranchée et sera éliminée de la Convention, mais toutes les autres dispositions de la Convention resteront valides et applicables comme si la Convention avait été signée sans qu’elle contienne cette disposition.
	<br>
	<br>9.3	Le défaut d’une des parties d’exercer à quelconque moment ou pour quelconque période de temps un ou plusieurs termes et conditions de la Convention ne pourra être considéré comme une renonciation par cette partie aux termes et conditions de la Convention ou aux droits d’exercer à quelconque moment subséquent tous les termes et conditions de cette Convention.
	<br>
	<br>9.4	Cette Convention est régie par les lois de la province de la Colombie-Britannique  et les lois fédérales du Canada qui y sont applicables. Les parties se soumettent à la compétence exclusive des tribunaux de la Colombie-Britannique. 
	<br>
	<br>9.5	Cette Convention constitue l’intégralité de l’entente convenue entre les parties relativement aux objets des présentes et remplace toute communication orale ou écrite préalable à leur égard. 
	<br>
	<br>9.6	Toutes les modifications ou renonciations aux termes des présentes doivent être constatées par écrit signées par les parties aux présentes et faire expressément référence à cette Convention.
	<br>
	<br>9.7	Chaque partie déclare et garantit à l’autre par la présente qu’elle : (i) a le pouvoir et l’autorité pour devenir partie à cette Convention, (ii) s’engage à se conformer à toute loi applicable lors de l’exécution de ses obligations aux termes de cette Convention, et (iii) n’est liée par aucune autre convention qui entrerait en conflit avec l’aptitude de cette partie à exécuter ses obligations aux termes de cette Convention. 
	<br>
	<br>9.8	Cette Convention n’est pas cessible par une partie, en tout ou en partie, sans le consentement écrit et préalable de l’autre partie.
	<br>
	<br>9.9	Cette Convention s’appliquera en faveur de et liera les parties et leurs successeurs légitimes, ainsi que leurs ayant-droits autorisés. 
	<br>
	<br>9.10	Chaque partie doit signer et remettre à l’autre partie tout document supplémentaire et effectuer tout acte additionnel requis pour donner plein effet à l’intention exprimée dans la présente Convention.
	<br>
	<br>9.11	Le Client agira comme une référence client pour ARPL. ARPL pourra faire référence au Client en tant que client dans ses documents promotionnels, y compris sur son site Web. ARPL pourra utiliser le nom et le logo du Client à cette fin. ARPL pourra aussi placer sur son site Web un lien renvoyant au site Web du Client.
	<br>
	<br>9.12	La présente Convention peut être signée en un ou plusieurs exemplaires, par télécopie ou par pdf, chacun de ceux-ci étant réputé constituer un original et ensemble étant réputés constituer une seule et même Convention. 
	<br>
	<br>[LES SIGNATURES DES PARTIES FIGURANT À LA PAGE DE COUVERTURE DE CETTE CONVENTION INDIQUENT L’APPROBATION DE CHACUNE D’ELLES À L’INTÉGRALITÉ DE CELLE-CI]




	<page_footer>
		<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
	</page_footer>
</page>
<page>
	<div class="title">ANNEXE B</div>
	<br>
	<br><div class="title">SERVICES</div>
	<br> 
	<table class="services">
		<tr>
			<td class="section">
				ACQUISITION DE PROSPECTS & TRAITEMENT
			</td>
			<td>
				<ul>
					<li>Acquérir et générer des prospects</li>
					<li>ARPL effectuera une extraction mensuelle des données, y compris les données F & I Crédit à risque pour identifier les clients Crédit à risque afin de prévoir un examen des paiements avec le client lors de l’événement Client à risque. </li>
					<li>ARPL cherchera des prospects (par exemple : voitures de 8 ans d’âge/habitant dans les quartiers des codes postaux désignés) en utilisant IDT® & Market Intelligence™ et réalisera un dépliant de publicité directe pour les prospects conquis Crédit à risque à ces clients en fonction de ces données ciblées.</li>
					<li>Le spécialiste des finances et/ou le directeur des ventes  Crédit à risque d’ARPL feront la revue de tous les refus aux finances (obligatoire, politique de temps de réponse de 48 h) pendant, mais sans s’y limiter, l’événement et/ou le temps de préparation avant et y compris les dates de l’événement.</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				QUALIFICATION DU PROSPECT
			</td>
			<td>
				<ul>
					<li>
						ARPL prendra des mesures pour que les agents Crédit à risque du Centre de communication appellent les nouveaux prospects conquis pour :
						<ul>
							<li>Recueillir les informations de crédit</li>
							<li>Expliquer la procédure d’approbation</li>
							<li>Générer un numéro de dossier pour la demande de crédit et discuter des documents qui seront requis</li>
							<li>Enregistrer un rendez-vous chez le concessionnaire</li>
						</ul>
					</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				STRUCTURER L’ENTENTE
			</td>
			<td>
				<ul>
					<li>
						Le spécialiste des services financiers d’ARPL :
						<ul>
							<li>Révisera les demandes</li>
							<li>Soumettra la demande à l’organisme prêteur approprié pour le compte du concessionnaire pour obtenir une approbation préalable </li>
							<li>Révisera les options d’inventaire en utilisant une stratégie « bon, mieux, meilleur »</li>
						</ul>
					</li>
				</ul>

			</td>
		</tr>
		<tr>
			<td class="section">
				ACTIVITÉ & RENDEZ-VOUS CHEZ LE CONCESSIONNAIRE
			</td>
			<td>
				<ul>
					<li>
						Le spécialiste des services financiers d’ARPL appellera le client pour :
						<ul>
							<li>Confirmer le rendez-vous</li>
							<li>Réviser la documentation requise pour le rendez-vous</li>
							<li>La demande a passé l’étape 1 du processus d’approbation</li>

						</ul>
					</li>
					<li>
							L’Équipe Crédit à risque demande au gérant des ventes de réviser la « feuille d’entente » et la sélection idéale dans l’inventaire.
						<ul>
							<li>Informe le client, lui montre un choix de véhicules (bien/mieux/meilleur)</li>
							<li>Renforce l’engagement</li>
							<li>Révise la documentation</li>

						</ul>
					</li>
				</ul>

				Si requis, le client fera la rencontre du gérant F & I afin de compléter tout document restant.
			</td>
		</tr>
		<tr>
			<td class="section">
				LIVRAISON & SUIVI
			</td>
			<td>
				<ul>
					<li>Demander 5 références et expliquer le bonus pour référence</li>
					<li>Informer quant à l’importance de faire TOUS leurs paiements</li>

				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				EN CAS DE NON-LIVRAISON
			</td>
			<td>
				<ul>
					<li>Le Centre de communication fera le suivi, obtiendra de la rétroaction, fera la révision avec le directeur des ventes Crédit à risque d’ARPL et fournira des commentaires au personnel du concessionnaire.</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				GESTION DES PORTEFEUILLES CRÉDIT À RISQUE
			</td>
			<td>
				<ul>
					<li>Le Centre de communication fera le suivi de chaque prospect invendu et de toutes les références régulièrement par téléphone, courriel et messagerie texte (SMS). Ce portefeuille Crédit à risque sera intégré au portail Absolute Results® du vendeur. </li>
				</ul>
			</td>
		</tr>
	</table>

	<page_footer>
		<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
	</page_footer>

</page>
<?php
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
			else $arr['unitPrice'] = displayPrice($dealer->countryID,$quoteLanguage,$item->unitPrice,$lang['currency']);

			if(is_numeric($item->quantity) && is_numeric($item->unitPrice)) $arr['total'] = displayPrice($dealer->countryID,$quoteLanguage,$item->quantity * $item->unitPrice,$lang['currency']);
			else if(is_numeric($item->quantity)) $arr['total'] = $lang['included'];
			else $arr['total'] = $lang['tbd'];

			$items[$itemType] = $arr;
		}
	}


?>
<page>
	<div class="title">ANNEXE C</div>
	<br>
	<br><div class="title">TARIFICATION</div>
	<br> 
	<br>Absolute Results® émettra UNE (1) facture avant l’événement. La facture de suivi après l’événement inclura les frais d’acquisition par véhicule et par prospect, le cas échéant.
	<br>
	<br>
	<table cellspacing="0" cellpadding="0" class="productTbl">
		<tr>
			<td colspan="5" style="background-color:black;color:white">
				LE PREMIER MOIS D’ACTIVATION  – 1re facture
			</td>
		</tr>
		<tr>
			<td style="width:20%;font-weight:bold;">Date de la facture </td>
			<td style="width:10%;font-weight:bold;">Quantité</td>
			<td style="width:40%;font-weight:bold;">Description</td>
			<td style="width:20%;font-weight:bold;">Prix unitaire</td>
			<td style="width:10%;font-weight:bold;">Total</td>
		</tr>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS FIXES :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['eventCoordination']['quantity'] ?></td>
			<td><b><?= $items['eventCoordination']['description'] ?></b></td>
			<td><?= $items['eventCoordination']['unitPrice'] ?></td>
			<td><?= $items['eventCoordination']['total'] ?></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS VARIABLES :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['digital']['quantity'] ?></td>
			<td><b><?= $items['digital']['description'] ?></b></td>
			<td><?= $items['digital']['unitPrice'] ?></td>
			<td><?= $items['digital']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['conquest']['quantity'] ?></td>
			<td><b><?= $items['conquest']['description'] ?></b></td>
			<td><?= $items['conquest']['unitPrice'] ?></td>
			<td><?= $items['conquest']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['arc']['quantity'] ?></td>
			<td><b><?= $items['arc']['description'] ?></b></td>
			<td><?= $items['arc']['unitPrice'] ?></td>
			<td><?= $items['arc']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['invitations']['quantity'] ?></td>
			<td><b><?= $items['invitations']['description'] ?></b></td>
			<td><?= $items['invitations']['unitPrice'] ?></td>
			<td><?= $items['invitations']['total'] ?></td>
		</tr>
	<?php
		}
	?>
	</table>
	<br><br>
	<table cellspacing="0" cellpadding="0" class="productTbl">
		<tr>
			<td colspan="5" style="background-color:black;color:white">
				LES MOIS SUIVANTS – 2e et 3e facture
			</td>
		</tr>
		<tr>
			<td style="width:20%;font-weight:bold;">Date de la facture </td>
			<td style="width:10%;font-weight:bold;">Quantité</td>
			<td style="width:40%;font-weight:bold;">Description</td>
			<td style="width:20%;font-weight:bold;">Prix unitaire</td>
			<td style="width:10%;font-weight:bold;">Total</td>
		</tr>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS FIXES :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['eventCoordination']['quantity'] ?></td>
			<td><b><?= $items['eventCoordination']['description'] ?></b></td>
			<td><?= $items['eventCoordination']['unitPrice'] ?></td>
			<td><?= $items['eventCoordination']['total'] ?></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS VARIABLES :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['digital']['quantity'] ?></td>
			<td><b><?= $items['digital']['description'] ?></b></td>
			<td><?= $items['digital']['unitPrice'] ?></td>
			<td><?= $items['digital']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['conquest']['quantity'] ?></td>
			<td><b><?= $items['conquest']['description'] ?></b></td>
			<td><?= $items['conquest']['unitPrice'] ?></td>
			<td><?= $items['conquest']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['arc']['quantity'] ?></td>
			<td><b><?= $items['arc']['description'] ?></b></td>
			<td><?= $items['arc']['unitPrice'] ?></td>
			<td><?= $items['arc']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['invitations']['quantity'] ?></td>
			<td><b><?= $items['invitations']['description'] ?></b></td>
			<td><?= $items['invitations']['unitPrice'] ?></td>
			<td><?= $items['invitations']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['perCar']['quantity'] ?></td>
			<td><b><?= $items['perCar']['description'] ?></b></td>
			<td><?= $items['perCar']['unitPrice'] ?></td>
			<td><?= $items['perCar']['total'] ?></td>
		</tr>
	<?php
		}
	?>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS FACULTATIFS :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['leadAcquisition']['quantity'] ?></td>
			<td><b><?= $items['leadAcquisition']['description'] ?></b></td>
			<td><?= $items['leadAcquisition']['unitPrice'] ?></td>
			<td><?= $items['leadAcquisition']['total'] ?></td>
		</tr>
	<?php
		}
	?>
	</table>
	<br><br>
	<table cellspacing="0" cellpadding="0" class="productTbl">
		<tr>
			<td colspan="5" style="background-color:black;color:white">
				DERNIER MOIS – 4e facture
			</td>
		</tr>
		<tr>
			<td style="width:20%;font-weight:bold;">Date de la facture  </td>
			<td style="width:10%;font-weight:bold;">Quantité</td>
			<td style="width:40%;font-weight:bold;">Description</td>
			<td style="width:20%;font-weight:bold;">Prix unitaire</td>
			<td style="width:10%;font-weight:bold;">Total</td>
		</tr>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS VARIABLES :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['perCar']['quantity'] ?></td>
			<td><b><?= $items['perCar']['description'] ?></b></td>
			<td><?= $items['perCar']['unitPrice'] ?></td>
			<td><?= $items['perCar']['total'] ?></td>
		</tr>
	<?php
		}
	?>
	
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FRAIS FACULTATIFS :</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>Le 1er du mois</td>
			<td><?= $items['leadAcquisition']['quantity'] ?></td>
			<td><b><?= $items['leadAcquisition']['description'] ?></b></td>
			<td><?= $items['leadAcquisition']['unitPrice'] ?></td>
			<td><?= $items['leadAcquisition']['total'] ?></td>
		</tr>
	<?php
		}
	?>
	</table>

	<page_footer>
		<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
	</page_footer>

</page>