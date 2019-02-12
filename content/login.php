<script>
	$(function () {
		$('#username').focus();
	});
</script>
<style>
	#login {font-size:0.8em}

	.login_input {
		padding-bottom:5px;
	}
</style>
<img src="images/logo.png" style="width: 160px;">
<div id="login" style="padding-top:20px;">
	<form method="POST" >
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<div class="login_title">Username</div>
				<div class="login_input"><input class="form-control" id="username" type="text" name="username"></div>
				<div class="login_title">Password</div>
				<div class="login_input"><input class="form-control" id="password" type="password" name="password"></div>
				<div>
					<div style="float:left;color:red;font-weight:bold"><?= $loginError ?></div>
					<div style="float:right;"><input class="btn btn-danger" type="submit" style="background-color:#db3136;" value="Log In"></div>
				</div>
			</div>
		</div>
	</form>
</div>

