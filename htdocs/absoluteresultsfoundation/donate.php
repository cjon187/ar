<?php 
	include_once('includes.php');
	
	
	include_once('header.php'); 
?>
<script>
</script>
<style>
	.section {
		background-color:#f7f7f7;
		margin:10px 0px;
		padding:20px;
	}
	.sectionTitle {
		font-weight:bold;
		color:#cf1f2e;
		font-size:1.2em;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h4 style="font-weight:bold;color:#cf1f2e">Donate to Absolute Results Foundation</h4>
			Our mission is to improve the lives of vulnerable people by mobilizing the power of humanity around the world. Your support will help us achieve our goal.
			<div class="section">
				<div class="sectionTitle">How to donate?</div>
				<br>
				<b>One Time Donation</b>
				<p>
					To make a one-time donation, please click on the button below. The donation process is secured through Moneris, a trusted provider of Internet security. Donation accepts <b><i>VISA, MasterCard, American Express and Discover</b></i> in <i>Canadian</i> funds. 
					<br>
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-primary" onClick="location.href='https://www.moneris.com'">One-Time Donation</button>
						</div>
						<div class="col-xs-6">
							<div class="pull-right">
								<img src="images/cc.png" class="img-responsive">
							</div>
						</div>
					</div>
				</p>
				<br>
				<b>Recurring Donation Solutions</b>
				<p>
					To make a monthly/quarterly/yearly donations, please download the Pre-Authorized Debit Agreement using the button below. 
					<br>
					<div style="margin-bottom:10px">
						<button class="btn btn-primary" onClick="window.open('downloads/ARF Pre-Authorized Debit Agreement.pdf')">Recurring Donations</button>
					</div>
				</p>
			</div>
			<div class="section">
				<div class="sectionTitle">Disclaimers</div>

				Please review our <a href="downloads/ARF Donations - Privacy Policy.pdf" target="_blank">Privacy Policy</a>, <a href="downloads/ARF Donations - Terms Of Service.pdf" target="_blank">Terms of Service</a> and <a href="downloads/ARF Donations - Return Policy.pdf" target="_blank">Return Policy</a>.
			</div>
		</div>
	</div>
</div>

<?php include_once('footer.php'); ?>