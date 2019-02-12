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
<div class="row" style="padding-bottom:10px">
	<div class="four columns">
		<h4 style="font-weight:bold;color:#cf1f2e">Contact Us</h4>
		Stay connected with us. 
		<div style="padding:20px 0px">
			<font style="font-weight:bold">Absolute Results Foundation</font>
			<br>
			120 - 2677 192 St.
			<br>
			Surrey BC, V3Z 3X1
			<br>
			<a href="mailto:info@absoluteresultsfoundation.org" style="font-size:0.9em">info@absoluteresultsfoundation.org</a>
		</div>
	</div>	
	<div class="eight columns">
		<h4 style="font-weight:bold;color:#cf1f2e">Newsletter</h4>
		Sign up for our email newsletter to receive information and updates.
		
		<div class="row" style="padding:20px 0px">
			<div class="six columns">
				<form id="newsletterForm" onSubmit="return signup()">		
					<div style="font-weight:bold">Email</div>
					<div class="field"><input class="input" type="text" name="email" id="email" /></div>
					<div class="medium primary btn pretty"><input type="submit" value="Sign Up" /></div>
				</form>	
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>