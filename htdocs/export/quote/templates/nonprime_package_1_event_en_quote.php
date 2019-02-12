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
<br><font style="font-weight:bold;font-size:18pt">NON-PRIME SERVICES AGREEMENT <?= ($quote->deleted != '' ? '<font style="color:red">DELETED</font>' : '') ?></font>
<br>
<br><font style="font-weight:bold;font-size:14pt">ENGLISH CANADA OUTSIDE OF QUEBEC</font>
<br>
<br><font style="font-weight:bold;font-size:14pt">TERMS AND CONDITIONS</font>
<br>
<br>This SERVICES AGREEMENT (the “<b>Agreement</b>”) is for one (1) event, within a fixed one (1) month, effective <?= date("M j, Y",strtotime($quote->start)) ?> (the “<b>Effective Date</b>”) between <b>ABSOLUTE RESULTS PRODUCTIONS LTD.</b>, a corporation incorporated under the laws of British Columbia, Canada (“<b>ARPL</b>”) and <?= $quote->dealer->name ?> (the “<b>Client</b>”), each a “<b>party</b>” and collectively the “<b>parties</b>”, with respect to certain services provided by ARPL to the Client.  
<br>
<br>In consideration of the mutual covenants and promises contained herein, the parties agree as follows:
<br>
<br>ARPL agrees to provide and the Client agrees to pay for the services (“<b>Services</b>”), all as described in and in accordance with this Agreement.  This Agreement consists of this cover page and the attached <b><u>Schedule</u></b> A (Terms and Conditions), <b><u>Schedule</u></b> B (Services), and <b><u>Schedule</u></b> C (Pricing).
<br>
<br><b>IN WITNESS WHEREOF</b> the parties have caused this Agreement to be executed by their duly authorized signatories as of the Effective Date.  The signatures appearing below indicate approval and acceptance of the entire Agreement.

<br>
<br>
<?php signatureBoxHTML('nonprime') ?>
<page_footer>
	
	<br><br>
	<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
</page_footer>
</page>
<page>
	<div class="title">Schedule A</div>
	<br>
	<br><div class="title">Terms and Conditions</div>
	<br> 
	<br><b><u>1.	TIMELINE.</u></b>
	<br>
	<br>1.1	ARPL will deliver the Services in accordance with the timelines set out in Schedule B.
	<br>
	<br>1.2	Client will share with ARPL all customer data and allow ARPL to manage such customer data as required in order to deliver the Services described in Schedule B. The Client represents and warrants to ARPL that Client has obtained all necessary consents to allow ARPL to collect, use and disclose customer data and to communicate with the Client’s customers as required to deliver the Services.
	<br>
	<br>1.3	Client allows ARPL and its appointed employees and representatives consensual “full access” to the Dealer’s Dealertrack Portal.  This access is strictly for reviewing and submitting credit applications on behalf of the respective dealership, solely for the purpose of obtaining credit towards the purchase of a new or used vehicle(s) from the respective dealer. 
	<br>
	<br>1.4	Client will cooperate with ARPL and provide all information, assistance and support assets (logos, pictures, Client information, etc.) needed to provide the Services promptly following any request by ARPL according to the timeline established by ARPL. 
	<br>
	<br>1.5	The Client acknowledges and agrees that ARPL’s provision of the Services may depend on matters solely within the Client’s control, and that ARPL will not bear any liability or otherwise be responsible for any delays or variations in the provision of the Services caused by any such matter, including any failure to provide timely instructions, information, responses to inquiries, or approvals.
	<br>
	<br>1.6	If an event of Force Majeure occurs, the parties shall immediately consult with each other to discuss the timeline of delivery of the Services by ARPL. If the consequences of Force Majeure continue for a period of more than thirty (30) days, either party may terminate this Agreement. Force Majeure” means an occurrence which is beyond our control, and which is unforeseen, unavoidable or insurmountable.  Neither party shall be liable in damages or have the right to terminate this agreement for any delay or default in performing hereunder if such delay or default is caused by conditions beyond its control, including but not limited to, an act of God, earthquakes, typhoons, flood, fire, lightning, unusually severe weather, war, terrorist act, epidemics, civil disturbances, strike, lockout or other labor disturbance, shortage of labor, postal disruption, power outages, government restraint, action, delay or inaction, as well as a significant change in the car industry that directly affects the provision of our services.  We have the right to cancel the double the money guarantee at any time for any reason without providing notice to you. 
	<br>
	<br>Where there is an event of force majeure, the party prevented from or delayed in performing its obligations under this agreement must immediately notify the other party giving full particulars of the event of force majeure and the reasons for the event of force majeure preventing or delaying that party in performing its obligations under this agreement. That party must use its reasonable efforts to mitigate the effect of the event of force majeure upon its performance of the agreement and to fulfil its obligations under the agreement. Absolute Results Production Ltd. will be deemed to have fulfilled its delivery obligations under this agreement once the items for delivery have been sent from its facilities for delivery.
	<br>
	<br>Upon completion of the event of force majeure the party affected must as soon as reasonably practicable recommence the performance of its obligations under this contract. Where the party affected is the contractor, the contractor must provide a revised programme rescheduling the works to minimise the effects of the prevention or delay caused by the event of force majeure. 
	<br>
	<br>An event of force majeure does not relieve a party from liability for an obligation which arose before the occurrence of that event, nor does that event affect the obligation to pay money in a timely manner which matured prior to the occurrence of that event. 
	<br>
	<br><b><u>2.	FEES.</u></b>
	<br>
	<br>2.1	The Client shall pay ARPL the fees and costs set forth in Schedule C, plus all applicable taxes.  
	<br>
	<br>2.2	The fees and costs for the Services will be invoiced by ARPL and shall be paid by the Client as follows:
	<br>
	<br>(a)	ARPL shall invoice the Client on the 1st of every month, or the next business day thereafter, if the 1st is a Sunday or statutory holiday in British Columbia;
	<br>
	<br>(b)	The Client shall pay the full amount of every invoice issued by ARPL under Section 2.2(a), which shall include the pre-payment of the standard lead package, on or before the 15th of the month following the date of the invoice or the next business day thereafter if the 15th is a Sunday or statutory holiday in the province where the Client is located as set forth on the first page of this Agreement.  For the sake of clarity, the Client shall pay the full amount of an invoice issued by ARPL dated January 1st on or before January 15th.
	<br>
	<br>2.3	Any outstanding balance owing by the Client thirty (30) days following the invoice date will bear a late payment fee of 18% per annum, compounded and calculated monthly until payment in full is made, which shall be the equivalent annual rate of 19.56%.  All fees shall be in Canadian currency. 
	<br>
	<br>2.4	ARPL reserves the right to suspend the Services if ARPL does not receive timely payment in accordance with this Agreement, and any such suspension of Services will not give rise to any right of termination by the Client nor any claim by the Client for breach of ARPL’s obligations under this Agreement.
	<br>
	<br>2.5	It is the express intention of the parties that ARPL is an independent contractor and not an employee, agent, joint venturer, franchisee, franchisor or partner of the Client.  Nothing in this Agreement shall be interpreted or construed as creating or establishing the relationship of employer and employee between the Client and ARPL or any employee or agent of ARPL.  This Agreement does not create and shall not be deemed to create a partnership or joint venture between the parties.  The Client will not withhold any taxes or make any tax payments on behalf of ARPL.
	<br>
	<br><b><u>3.	INTELLECTUAL PROPERTY.</u></b>
	<br>
	<br>3.1	The Client shall have sole title and ownership of all intellectual property previously held by the Client and all intellectual property created or developed by the Client during the term of this Agreement (“Client IP”). The Client grants ARPL permission to copy, use, modify and otherwise exploit Client IP as necessary in delivering the Services. The Client is responsible for ensuring, and hereby represents and warrants to ARPL that the Client IP to be used by ARPL for the provision of the Services does not infringe, violate or misappropriate any intellectual property rights of a third party and that the Client has the unrestricted right to grant ARPL the permission to use Client IP as set out above.  
	<br>
	<br>3.2	ARPL shall have sole title and ownership of all work product previously held by ARPL and created or developed by or for ARPL in the provision of the Services and all intellectual property rights therein, including all copyrights, moral rights, patents, trademarks, trade names, service marks, design rights, database rights, rights to domain names, and other similar intellectual property rights (whether registered or not) and applications for such rights as may exist anywhere in the world. 
	<br>
	<br>3.3	The Client hereby irrevocably and unconditionally agrees to indemnify, defend and hold fully harmless ARPL and its directors, officers, employees, agents and representatives from and against any and all third party actions, proceedings, losses, damages, liabilities, obligations, costs, claims, charges and expenses, including legal counsel fees, suffered by ARPL of whatsoever nature arising out of or in connection with: (i) ARPL’s use of the Client IP as authorized under this Agreement (including any related copyrights, trade secrets, trade names, patents, intellectual property rights and privacy rights in any jurisdiction or country in which the Client IP is utilized); (ii) any breach of any representation, warranty or covenant made under this Agreement; and (iii) any claim asserted by any customer, end-user or other third party based on a product or service provided by the Client.
	<br>
	<br><b><u>4.	CONFIDENTIALITY AND NON-SOLICITATION.</u></b>
	<br>
	<br>4.1	Each party (the “Receiving Party”) shall keep confidential and shall not disclose any Confidential Information of the disclosing party (the “Disclosing Party”) to anyone other than the Receiving Party’s officers, directors, employees or representatives who: (a) have a “need to know”; and (b) have been advised of the confidential and proprietary nature of the Confidential Information and of the obligations imposed by this Agreement (“Representatives”). The Receiving Party will cause its Representatives to comply with this Agreement’s confidentiality obligations and will be responsible and liable for any breach of those obligations by any Representative. The Receiving Party will not use or exploit any Confidential Information except for the purposes of exercising its rights or performing its obligations under this Agreement. For purposes of this Agreement, “Confidential Information” means all such information, material and data of the Disclosing Party which: (i) is labeled or designated in writing as confidential or proprietary, (ii) the Receiving Party is advised is proprietary or confidential, or (iii) in view of the nature of such information and/or the circumstances of its disclosure, the Receiving Party knows or reasonably should know is confidential or proprietary relating to the Disclosing Party or its affiliates, and shall include, without limitation, the following information: financial data, plans, forecasts, intellectual property, methodologies, algorithms, agreements, market intelligence, technical concepts, customer information, strategic analyses and publications.  
	<br>
	<br>4.2	The confidentiality obligations herein shall not apply to any such information (a) which is or becomes publicly known without any fault of or participation by the Receiving Party, (b) was in the Receiving Party's possession prior to the time it was received from the Disclosing Party or came into the Receiving Party’s possession thereafter, but only if in each case it is lawfully obtained from a source other than the Disclosing Party and not subject to any obligation of confidentiality or restriction on use; or (c) is independently developed by the Receiving Party by persons not having exposure to Disclosing Party's Confidential Information.
	<br>
	<br>4.3	Notwithstanding any other wording in this Section 4, if the Receiving Party becomes or may become legally compelled to disclose any Confidential Information, the Receiving Party may disclose that Confidential Information to the extent required by law provided that: (a) the Receiving Party promptly notifies the Disclosing Party of the efforts to compel disclosure (unless prohibited by law from doing so); (b) the Receiving Party cooperates with and assists with the Disclosing Party’s lawful attempts to prevent or limit disclosure or to obtain a protective order; and (c) to the extent disclosure is still required by law, the Receiving Party takes all reasonable steps to make the disclosure on a confidential basis.
	<br>
	<br>4.4	The Receiving Party will protect all Confidential Information by using the same degree of care regarding the Confidential Information that the Receiving Party would exercise regarding its own confidential information, but not less than reasonable care. Confidential Information shall remain the exclusive property of the Disclosing Party and no patent, copyright, trademark or other proprietary right is licensed, granted or otherwise transferred by this Agreement or any disclosure of Confidential Information to the Receiving Party.  
	<br>
	<br>4.5	The Client will not directly or indirectly at any time during the term of this Agreement and for 12 months after this Agreement terminates or expires, without ARPL’s prior written consent: (a) induce or encourage any ARPL employee or contractor to leave his or her employment or engagement with ARPL; or (b) employ, attempt to employ, assist any person to employ, or retain as a consultant or contractor, any of ARPL’s employees or contractors or former employees or contractors. The previous sentence will not apply to individuals hired as a result of the use of an independent employment agency (so long as the agency was not directed to solicit a particular individual) or as a result of the use of a general solicitation not specifically directed to ARPL employees or contractors.
	<br>
	<br>4.6	Each party acknowledges that its breach of this Section 4 will irreparably harm the other party, and that such harm will not be susceptible to accurate measurement for the purpose of calculating monetary damages.  Accordingly, the non-breaching party, in addition to seeking and recovering monetary damages and other remedies available at law, will have the right to an injunction or other equitable relief to prevent a breach or threatened breach, without the necessity of posting a bond or other security or taking any further action.
	<br>
	<br><b><u>5.	TERM.</u></b>
	<br>
	<br>5.1	The term of this Agreement commences on the Effective Date for a fixed duration of one (1) month only (the “Term”).  The Term shall NOT be automatically extended, and a new Agreement will have to be signed, should the Client wish to proceed with an additional Term. 
	<br>
	<br>5.2	ARPL may at any time and for any reason terminate this Agreement upon thirty (30) days written notice to the Client.  
	<br>
	<br>5.3	If either party materially breaches any of its obligations under this Agreement, the non-breaching party may terminate this Agreement immediately upon thirty (30) days written notice to the breaching party, and this notice will automatically become effective unless the breaching party completely remedies the breach to non-breaching party’s satisfaction within that thirty (30) day period.  In the event of the Client’s failure to pay the full balance owing to ARPL, ARPL may terminate this Agreement immediately upon written notice.  
	<br>
	<br>5.4	Upon termination of this Agreement, the Client shall immediately pay all amounts due and owing to ARPL for the Services performed to the date of termination pursuant to the provisions of Schedule B, and all reasonable out-of-pocket expenses that APRL incurs or will incur under its contractual relationships with third parties in connection with the Services.
	<br>
	<br><b><u>6.	TERMINATION.</u></b>
	<br>
	<br>6.1	The rights and obligations of ARPL and the Client contained in Sections 2, 3, 4, 5.4, 6, 7, 8 and 9 shall survive any termination or expiration of this Agreement. Termination of this Agreement shall be without prejudice to any rights which have accrued to either party prior to termination, including any payment obligations of the Client.  
	<br>
	<br><b><u>7.	NO WARRANTY.</u></b>
	<br>
	<br>7.1	ARPL makes no, and expressly disclaims all, warranties, conditions, representations or guarantees of any kind, express, statutory or implied, regarding the Services, including those regarding merchantability, fitness for purpose, design, condition, quality, title or non-infringement.  ARPL does not warrant that the Services will meet the requirements of the Client or that the performance of the Services will be free from interruption, damage or error, or that the results that may be obtained from the Services will be accurate or reliable.   
	<br>
	<br><b><u>8.	LIMITATION OF LIABILITY.</u></b>
	<br>
	<br>8.1	ARPL’s total aggregate liability to the Client under this Agreement shall not exceed the total amounts paid by the Client to ARPL under this Agreement.
	<br>
	<br>8.2	IN NO EVENT SHALL EITHER PARTY BE LIABLE, REGARDLESS OF THE FORM OF CLAIM OR ACTION, FOR (i) LOST PROFITS, BUSINESS, OPPORTUNITIES, OR REVENUES OF ANY KIND, (ii) LOST SAVINGS; (iii) LOST SOFTWARE OR DATA; (iv) LOSS OF USE OF HARDWARE, SOFTWARE, SYSTEMS OR DATA; OR (v) EXCEPT WITH RESPECT TO ANY BREACH OF SECTION 4, ANY INDIRECT OR CONSEQUENTIAL LOSS; HOWEVER CAUSED AND WHETHER OR NOT THE OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.   
	<br>
	<br><b><u>9.	GENERAL.</u></b>
	<br>
	<br>9.1	All notices, requests, demands and other communications hereunder shall be in writing and shall be deemed to have been duly given if delivered by hand, facsimile, electronic mail or mailed postage prepaid to the addresses as first set forth on the first page of this Agreement or to such other address as may be given in writing by the parties and shall be deemed to have been received, if delivered by hand, on the date of delivery; if by facsimile or electronic mail to the facsimile numbers and electronic mail addresses set out on the first page of this Agreement, if any, on the business day next following the date of transmission; and if mailed to the addresses set out on the first page of this Agreement, on the fifth business day following posting; provided that if there is, between the time of mailing and the actual receipt of the notice, a mail strike, slowdown or other labour dispute which might affect the delivery of the notice by mail, then the notice shall only be effective if actually delivered, faxed or e-mailed to the addresses set out on the first page of this Agreement. 
	<br>
	<br>9.2	If any provision of this Agreement is held to be invalid, illegal or unenforceable for any reason, such provision shall be severed and the remainder of this Agreement shall continue in full force and effect as if this Agreement had been executed with the invalid provision eliminated. 
	<br>
	<br>9.3	The failure by either party to enforce at any time or for any period any one or more of the terms and conditions of this Agreement shall not be a waiver of them or of the right at any time subsequently to enforce all terms and conditions of this Agreement.
	<br>
	<br>9.4	This Agreement is governed by the laws of the Province of British Columbia and the federal laws of Canada applicable therein, and each of the parties attorns to the exclusive jurisdiction of the courts of British Columbia.
	<br>
	<br>9.5	This Agreement is the complete and exclusive statement of agreement between the parties relating to the subject matter hereof and supersedes all prior written and oral communications related to the same. 
	<br>
	<br>9.6	All modifications to or waivers of any terms of this Agreement must be in a writing that is signed by the parties hereto and expressly references this Agreement.  
	<br>
	<br>9.7	Each party hereby represents and warrants to the other party that (i) it has the power and authority to enter into this Agreement; (ii) it will comply with all applicable laws in the performance of its obligations under this Agreement; and (iii) it is not subject to any other agreement that would conflict with its ability to perform its obligations under this Agreement.
	<br>
	<br>9.8	This Agreement shall not be assignable by a party, in whole or in part, without the prior written consent of the other party.
	<br>
	<br>9.9	This Agreement shall enure to the benefit of and be binding upon the parties and their lawful successors and permitted assigns.
	<br>
	<br>9.10	The parties shall execute and deliver to each other any further instruments and do any further acts that may be required to give full effect to the intent expressed in this Agreement.
	<br>
	<br>9.11	The Client will act as a customer reference for ARPL. ARPL may refer to the Client as a customer in ARPL’s promotional materials, including on ARPL’s website, and may use the Client’s name and logo for that purpose.  ARPL may also place a link from its website to the Client’s website.
	<br>
	<br>9.12	This Agreement may be signed in counterparts, by fax or pdf, each of which shall be deemed an original and all of which taken together shall constitute one and the same Agreement.
	<br>
	<br>9.13	The parties have expressly agreed that this document and all ancillary agreements, documents or notices relating thereto be drafted solely in English. Les parties aux présentes ont expressément convenu que ce document et toute autre convention, document ou avis y afférent soient rédigés uniquement en anglais.
	<br>
	<br>[SIGNATURES OF THE PARTIES APPEARING ON THE COVER PAGE OF THIS AGREEMENT INDICATE APPROVAL OF THE ENTIRE AGREEMENT]



	<page_footer>
		<div style="text-align:center;font-size:8pt">Copyright © <?= date("Y") ?>, Absolute Results Productions LTD. All rights reserved.</div>
	</page_footer>
</page>
<page>
	<div class="title">Schedule B</div>
	<br>
	<br><div class="title">SERVICES</div>
	<br> 
	<table class="services">
		<tr>
			<td class="section">
				LEAD ACQUISITION & PROCESSING
			</td>
			<td>
				<ul>
					<li>Acquire and generate leads</li>
					<li>ARPL will complete a monthly data pull including Non-Prime F&I data to identify Non-Prime Customers in order to schedule a payment review with the customer at the Non-Prime Event.</li>
					<li>ARPL will hunt for prospects (e.g. 8 year old cars/living in designated postal code areas) using IDT™ & Market Intelligence™ and with execute a Non-Prime conquest mailer to these customers based on this targeted data.</li>
					<li>ARPL Financial Specialist and/or Non-prime Sales Director will review all finance turn-downs (mandatory 48 hr turnover policy) during but not limited to The Event and/or preparation time prior to and including The Event dates.</li>

				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				LEAD QUALIFICATION
			</td>
			<td>
				<ul>
					<li>
						The Absolute Results® Communication Centre (ARC) Non-prime Agent will call the new conquest lead:
						<ul>
							<li>Gather credit information</li>
							<li>Explain approval process</li>
							<li>Generate credit application file number and discuss the documents that will be required</li>
							<li>Establish appointment at dealership</li>

						</ul>
					</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				DEAL STRUCTURING
			</td>
			<td>
				<ul>
					<li>
						The ARPL Financial Services Specialist will:
						<ul>
							<li>Review the application</li>
							<li>Submit to the right lending institution on behalf of dealership to obtain pre-approval</li>
							<li>Review inventory options with a good, better, best strategy</li>
						</ul>
					</li>
				</ul>

			</td>
		</tr>
		<tr>
			<td class="section">
				DEALERSHIP ACTIVITY & APPOINTMENTS</td>
			<td>
				<ul>
					<li>
						ARPL Financial Services Specialist will call the Customer:
						<ul>
							<li>Confirm appointment</li>
							<li>Review the documentation needed at the appointment</li>
							<li>Application has passed stage 1 of the approval process</li>

						</ul>
					</li>
					<li>
						The Non-prime Team engages the sales manager to review the “deal sheet” and ideal inventory selection.
						<ul>
							<li>Educates customer shows choice of vehicles Good/Better/Best</li>
							<li>Solidifies commitment</li>
							<li>Reviews documentation</li>

						</ul>
					</li>
				</ul>

				If required customer will be introduced to the F&I Manager to complete any remaining documentation.
			</td>
		</tr>
		<tr>
			<td class="section">
				DELIVERY & FOLLOW-UP
			</td>
			<td>
				<ul>
					<li>Ask for 5 referrals & explain our referral bonus</li>
					<li>Educate on the importance of making ALL their payments</li>

				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				IN CASE OF NON-DELIVERY
			</td>
			<td>
				<ul>
					<li>ARC will follow up, get feedback, review with ARPL Non-prime Sales Director and provide feedback to dealership staff.</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="section">
				NON-PRIME PORTFOLIO MANAGEMENT
			</td>
			<td>
				<ul>
					<li>ARC will follow up each unsold lead and all referrals regularly by phone, email and SMS. This non-prime portfolio will be integrated in the dealer’s Absolute Results® portal.</li>
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
	<div class="title">Schedule C</div>
	<br>
	<br><div class="title">PRICING</div>
	<br> 
	<br>Absolute Results® will issue ONE (1) invoice prior to event. Follow up invoice after event will include the per car and lead acquisition charges if applicable.
	<br>
	<br>
	<table cellspacing="0" cellpadding="0" class="productTbl">
		<tr>
			<td colspan="5" style="background-color:black;color:white">
				<b>FIRST MONTH ACTIVATION</b> – 1st Invoice
			</td>
		</tr>
		<tr>
			<td style="width:20%;font-weight:bold;">Date of Invoice</td>
			<td style="width:10%;font-weight:bold;">Quantity</td>
			<td style="width:40%;font-weight:bold;">Description</td>
			<td style="width:20%;font-weight:bold;">Unit Price</td>
			<td style="width:10%;font-weight:bold;">Total</td>
		</tr>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>FIXED COSTS:</b>
			</td>
		</tr>
	<?php
		if(!empty($items['eventCoordination']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
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
				<b>VARIABLE COSTS:</b>
			</td>
		</tr>
	<?php
		if(!empty($items['digital']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
			<td><?= $items['digital']['quantity'] ?></td>
			<td><b><?= $items['digital']['description'] ?></b></td>
			<td><?= $items['digital']['unitPrice'] ?></td>
			<td><?= $items['digital']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['conquest']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
			<td><?= $items['conquest']['quantity'] ?></td>
			<td><b><?= $items['conquest']['description'] ?></b></td>
			<td><?= $items['conquest']['unitPrice'] ?></td>
			<td><?= $items['conquest']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['arc']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
			<td><?= $items['arc']['quantity'] ?></td>
			<td><b><?= $items['arc']['description'] ?></b></td>
			<td><?= $items['arc']['unitPrice'] ?></td>
			<td><?= $items['arc']['total'] ?></td>
		</tr>
	<?php
		}
		if(!empty($items['invitations']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
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
				<b>FOLLOWING MONTH</b> – 2nd Invoice
			</td>
		</tr>
		<tr>
			<td colspan="5" style="background-color:#eee">
				<b>VARIABLE COSTS:</b>
			</td>
		</tr>
		<tr>
			<td style="width:20%;font-weight:bold;">Date of Invoice </td>
			<td style="width:10%;font-weight:bold;">Quantity</td>
			<td style="width:40%;font-weight:bold;">Description</td>
			<td style="width:20%;font-weight:bold;">Unit Price</td>
			<td style="width:10%;font-weight:bold;">Total</td>
		</tr>
	<?php
		if(!empty($items['perCar']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
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
				<b>OPTIONAL COSTS:</b>
			</td>
		</tr>
	<?php
		if(!empty($items['leadAcquisition']['description'])) {
	?>
		<tr>
			<td>1st of the month</td>
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