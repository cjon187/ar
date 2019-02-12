<script>
	function changeDealer(did,exportPDF) {
		l = '?s1=<?= $_GET['s1'] ?>&s2=DataHealth&did=' + did;
		if(exportPDF) {
			location.href= l + '&export';
		} else {
			location.href= l;
		}
	}
</script>
<div class="row">
	<div class="col-md-12">
		<label>Dealership</label>
		<div>
			<div class="pull-left">
				<div class="form-group form-inline">
					<select id="dealerID"  name="dealerID" class="form-control input-sm" onChange="changeDealer($(this).val(),false)">
						<option <?= (empty($_GET['did']) ? 'SELECTED' : '') ?>></option>
				<?php
					foreach($dealers as $id => $name) {
				?>
						<option value="<?= $id ?>" <?= ($_GET['did'] == $id ? 'SELECTED' : '') ?>><?= $name ?></option>
				<?php
					}
				?>				
					</select>
				</div>
			</div>
		<?php
			if(!empty($counts['contacts'])) {
		?>
			<div class="pull-right">
				<button class="btn btn-success" onClick="changeDealer($('#dealerID').val(),true)">Export</button>
			</div>
		<?php
			}
		?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?php
	if(!empty($counts['contacts'])) {
		$bladeParams['counts'] = $counts;
		$bladeParams['percents'] = $percents;
		$bladeParams['databaseStars'] = $databaseStars;
		$bladeParams['warnings'] = $warnings;
		echo $blade->view()->make('report.dataHealth',$bladeParams)->render();

	}
?>