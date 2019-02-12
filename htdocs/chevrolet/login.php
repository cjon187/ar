<?php
	include_once('includes.php');

	unset($_SESSION['login']);

	if(isset($_POST['username'])) {
		if($_POST['username'] != '') {

			if(trim(strtolower($_POST['username'])) == 'chev' && trim(strtolower($_POST['password'])) == 'arprivatesale') {
				$_SESSION['login']['username'] = trim(strtolower($_POST['username']));
				echo json_encode(array('success' => true));	
				exit;
			}
		}

		echo json_encode(array('success' => false,
								'error' => 'Invalid Login'));
		exit;		
	}

	include_once('header.php');
?>
<style>
	#loginForm > div {
		padding-top:10px;
	}
	#loginForm [name="username"],#loginForm [name="password"] {
		width:200px;
	}

	.alert {
		display:none;
		padding:3px 10px;
		margin:5px 0px;
	}
</style>
<script>
	function loginFormSubmit(){
		$('#loginForm').ajaxSubmit({
			dataType: 'json',
			success: function(data) {
				if(data.success) {
					location.href='index.php';
				}
				else {
					$('#alert_errors').html('');
					$('#alert_errors').append('<div>' + data.error + '</div>');
					$('#alert_errors').show();
				}
			}
		});
	}
</script>	
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="section">
			<div class="title">Login</div>
			<div>If you do not have the username and password, please contact us at 1.888.751.7171 or <a href="mailto:info@absoluteresults.com">info@absoluteresults.com</a>.</div>
			<div class="row">
				<div class="col-md-8">
					<form method="POST" id="loginForm" class="form-inline" onSubmit="loginFormSubmit();return false;">
						<div class="form-group">
							<input type="text" name="username" class="form-control" placeholder="Username">
							<input type="password" name="password" class="form-control" placeholder="Password">
						</div>		
						<div style="margin-top:5px">
							<button type="submit" class="btn btn-primary btn-sm">Log In</button>
						</div>	
					</form>
					
					<div id="alert_errors" class="alert alert-danger" role="alert"></div>
				</div>
			</div>
		</div>
	</div>
</div>



<?php
	include_once('footer.php');
?>