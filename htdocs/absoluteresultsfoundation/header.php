<?php 
	$cpage = strtolower(array_shift(explode('.',basename($_SERVER['SCRIPT_NAME']))));

	$menuItems = [];
	$menuItems['index'] = 'The Foundation';
	$menuItems['programs'] = 'Programs';
	$menuItems['humanitarianaid'] = 'Humanitarian Aid';
	//$menuItems['education'] = 'Education';
	$menuItems['aboutus'] = 'About Us';

?>
<!doctype html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
		<title>Absolute Results Foundation</title>
		

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link href='//fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
		<style>
			body {
				background-color:white;
			}
			
			.container { 
				width:100%; 
				max-width:960px; 
				font-size:1.2em;
			}
			#header {
				margin:20px 0px;
			}
			#menuItems > div {
				float:left;
				margin-top:10px;
			}
			.menuItem {
				font-weight:bold;
				text-align:center;
				padding:20px 15px;
				font-size:0.9em;
				cursor:pointer;
			}
			.menuItem a {
				color:black;

			}
			.menuItem.selected {
				background-color:#DB2325;
				color:white;

			}
			.menuItem:hover {
				background-color:#e36d6e;
				color:white;

			}
			
			#menuDropdown {
				display:none;
			}

			@media (max-width: 992px) {	
				#menuDropdown {
					display:block;
				}
				#menuItems {
					display:none;
				}
			}

		</style>
		<script>
		</script>
	</head>
	<body>
		<div class="container">
			<div id="header">
				<div class="row">
					<div class="col-xs-5 col-md-3">
						<img src="images/arlogo.png" class="img-responsive">
					</div>
					<div class="col-xs-7 col-md-9">
						<div class="pull-right">
							<div id="menuItems">
						<?php
							foreach($menuItems as $page => $name) {
						?>
								<div class="menuItem <?= ($cpage == $page ? 'selected' : '') ?>" onClick="location.href='<?= $page ?>.php'">
									<?= $name ?>
								</div>
						<?php
							}
						?>
							</div>
							<div id="menuDropdown">

								<div class="btn-group">
									<button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<?= $menuItems[$cpage] ?> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu dropdown-menu-right">

							<?php
								foreach($menuItems as $page => $name) {
							?>
										<li <?= ($cpage == $page ? 'class="active"' : '') ?>><a href="<?= $page ?>.php"><?= $name ?></a></li>
							<?php
								}
							?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>