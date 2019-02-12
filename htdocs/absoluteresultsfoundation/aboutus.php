<?php 
	include_once('includes.php');
	
	if(isset($_POST['email']))
	{		
		if($_POST['email'] == '')
		{
			?>
			alert('Please tell us your email address.');		
			<?php
			exit;			
		}
		
		$sql = 'INSERT INTO ps_newsletter_signup (email,dateRegistered) VALUES ("' . $_POST['email'] . '","' . date("Y-m-d H:i:s") . '")';
		if(!mysqli_query($db_foundation,$sql))
		{
			$dup = mysqli_fetch_assoc(mysqli_query($db_foundation,'SELECT * FROM ps_newsletter_signup WHERE email = "' . $_POST['email'] . '"'));
			if($dup['email'] != '')
			{
				?>
				alert('This email address has already registered.');		
				<?php
			}
			else
			{
				?>
				alert('Error Occurred');		
				<?php
			}
		}
		else
		{
			?>
			alert('Thank you.\nYou are now registered for the Absolute Results Foundation newsletter.');
			location.reload();		
			<?php
		}
		
		exit;
	}
	
	include_once('header.php'); 
?>
<script>
	
	function signup()
	{	
		$.ajax({data:	$('#newsletterForm').serializeArray(),
				type:	'POST',
				dataType: 'script'
   	    });  
		return false;
	}
</script>
<style>
	.aboutUsBox {
		padding-bottom:20px;
		min-height:200px;
	}
</style>
<div class="container">
<div class="row">
	<div class="col-md-6">
		<div class="aboutUsBox">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px">
				<h4 style="font-weight:bold;color:#cf1f2e">About the Foundation</h4>
				The Absolute Results Foundation is a project started by leaders and stakeholders of the Absolute Results group of companies to unify and better organize the charity eﬀort that have been a key components of our corporate culture. 
				<br><br>
				The Absolute Results Foundation is a registered Canadian Charity: CRA#833508237RR0001 and society incorporation #S-0061880.
				<!-- <br><br>
				<a href="contactus.php">Click here to receive updates from the foundation.</a> -->
				<br><br>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="aboutUsBox">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px">
				<h4 style="font-weight:bold;color:#cf1f2e">Contact Us</h4>
				Stay connected with us. 
				<div style="padding:20px 0px">
					<font style="font-weight:bold">Absolute Results Foundation</font>
					<br>
					104-19353 22nd Ave.
					<br>
					Surrey BC, V3Z 3X6
					<br>
					<a href="mailto:info@absoluteresultsfoundation.org" style="font-size:0.9em">info@absoluteresultsfoundation.org</a>
				</div>
			</div>
		</div>	
	</div>
</div>
<div class="row" style="padding-bottom:10px">
	<div class="col-md-6">
		<div class="aboutUsBox">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px">
				<h4 style="font-weight:bold;color:#cf1f2e">Beliefs Statement</h4>
				We believe that all humankind are spiritual beings created by God. It is our innate duty and our greatest joy to help grow their God given gifts to equip them to transform themselves and their communities. 
				<br><br>
				We believe everyone needs hope and dignity to fulfill the promise of a better society. 
				<br><br>
				We believe true transformation takes a generation and we invest for the long term, especially in children: building relationships, creating environments, getting our hands dirty, and developing tools to fulﬁll that promise.
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="aboutUsBox">
			<div style="background-color:#f7f7f7;padding:10px 20px 20px 20px">
				<h4 style="font-weight:bold;color:#cf1f2e">Newsletter</h4>
				Sign up for our email newsletter to receive information and updates.
				
				<div class="row" style="padding:20px 0px">
					<div class="col-md-6">
						<form id="newsletterForm" onSubmit="return signup()">		
							<div style="font-weight:bold">Email</div>
							<input class="form-control" type="text" name="email" id="email" />
							<br>
							<input class="btn btn-success btn-sm" type="submit" value="Sign Up" />
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>