<script type="text/javascript" src="scripts/fusioncharts/js/fusioncharts.js"></script>
<script type="text/javascript" src="scripts/fusioncharts/js/themes/fusioncharts.theme.fint.js"></script>

<script>
	$(function() {
	<?php
		foreach($jsonData as $cat => $data) {
	?>
		var chart = new FusionCharts(
		{
			"type": "stackedcolumn2d",
			"renderAt": "<?= $cat ?>",
			"width": "100%",
			"height": "250",
			"dataFormat": "json",
			"dataSource":
			{
			    "chart": {
			        "showSum": "1",
			        "theme": "fint"
			    },
			    "categories": [
			        {
			            "category": [
			                {
			                    "label": "<?= $lastYearLabel ?>"
			                },
			                {
			                    "label": "<?= $yearLabel ?>"
			                }
			            ]
			        }
			    ],
			    "dataset": <?= json_encode($data) ?>
			}
		});

		chart.render();
	<?php
		}
	?>


		$('[drillDownType]').click(function(){
			if($(this).attr('drillDownID') != '') {
				location.href="?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>&type=" + $(this).attr('drillDownType') + '&id=' + $(this).attr('drillDownID');
			}
		})
	});
</script>
<style>
	.chartDiv .panel-heading {
		font-weight:bold;
		color:#555;
	}

	[drillDownType] {
		cursor:pointer;
	}

	h3 {
		line-height:1em;
		margin-top:0px;
	}
	.filtersDiv {
		margin:10px 0px;
	}
</style>

<div id="ar-page-title"><?= $pageTitle ?></div>
<div class="clearfix"></div>
<hr class="hr-lg">

<div class="row">
	<div class="col-md-12">
		<div class="pull-left">
		<?php
			if($_GET['type'] != '') {
		?>
			<div style="margin-bottom:10px">
				<a href="?s1=<?= $_GET['s1'] ?>&s2=<?= $_GET['s2'] ?>">Back to Global</a></div>
		<?php
			}
		?>
		</div>

		<div class="clearfix"></div>
		<div class="filtersDiv">
			<form method="POST">
				<div class="pull-left" style="margin-right:10px">
					<select class="form-control" name="stat" onChange="this.form.submit();">
						<option value="">Private Sales</option>
						<option value="solds" <?= ($_SESSION['report']['stat'] == 'solds' ? 'SELECTED' : '') ?>>Vehicles Sold</option>
						<option value="invites" <?= ($_SESSION['report']['stat'] == 'invites' ? 'SELECTED' : '') ?>>Invitations</option>
						<option value="conquests" <?= ($_SESSION['report']['stat'] == 'conquests' ? 'SELECTED' : '') ?>>Conquest Flyers</option>
					</select>
				</div>
				<div class="pull-left" style="padding-right:10px">
					<select class="form-control" name="dateRange" onChange="this.form.submit();">
						<option value="" <?= ($_SESSION['report']['dateRange'] == 'ytd' ? 'SELECTED' : '') ?>>Year to Date</option>
				<?php
					for($i = 1;$i <= date("m"); $i ++) {
				?>
						<option value="<?= $i ?>" <?= ($_SESSION['report']['dateRange'] == $i ? 'SELECTED' : '') ?>><?= date("F",strtotime('2016-' . $i)) ?></option>
				<?php
					}
				?>
					</select>
				</div>
				<div class="pull-right">
					<a href="?s1=report" style="font-size: 18px; font-weight:bold; color: blue; ">
						Back to Reports
					</a>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<?php
		$index = 0;
		foreach($chartData as $cat => $data) {
			if($index % 3 == 0 && $index != 0) echo '</div><div class="row">';
			$index++;

	?>
	<div class="col-md-4">
		<div class="chartDiv">
			<div class="panel panel-default">
				<div class="panel-heading" drillDownType="<?= $drillDown['type'] ?>" drillDownID="<?= $categoryData[$cat][$drillDown['idName']] ?>"><?= strtoupper($cat) ?></div>
				<div class="panel-body">
					<div id="<?= $cat ?>"></div>
				</div>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>