<!doctype html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title>FCA Germany | Absolute Results</title>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>	
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<script src="scripts/jquery.form.min.js"></script>

	<link href='//fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="styles.css" type="text/css">

	<script src='https://www.google.com/recaptcha/api.js?hl=de'></script>

</head>


<body>	
	<div id="main">
		<div class="container" id="headerContainer">
			<div class="row">
				<div class="col-md-12">
					<div class="mainDiv">
						<div class="row">
							<div class="col-xs-10" style="padding-left: 0;">
								<?php if(strtolower($_SESSION['login']['username']) == "fcagermany"){ ?>
									<img src="images/logo_chrysler.png" style="max-width: 180px;" class="img-responsive" alt="Responsive image">
								<?php } ?>
								<?php if(strtolower($_SESSION['login']['username']) == "germany"){ ?>
									<a id="showModalA" data-toggle="modal" data-target="#offersModal" style="cursor:pointer;">
										<img src="images/logo_default.png" style="max-width: 180px;" class="img-responsive" alt="Responsive image">
									</a>
								<?php } ?>
							</div>

							<div class="col-xs-2">
								<div class="pull-right">
									<img src="images/arLogo.png" style="max-width: 180px;" class="img-responsive" alt="Responsive image">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="contentContainer">
			<div class="row">
				<div class="col-md-12">
					<div class="mainDiv">
	
