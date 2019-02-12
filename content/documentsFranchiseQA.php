<style>
	.q {
		font-weight:bold;
	}
	.a_btn {
		margin-top:10px;
	}
	.a {
		display:none;
		border:1px solid #ccc;
		border-radius:5px;
		background-color:#fefefe;
		padding:10px;
	}
</style>
<script>
	$(function() {
		$('.a_btn button').click(function(){
			$(this).hide();
			$(this).parent().siblings('.a').show(400);
		})
	})


</script>
<h3>
	Franchise Q & A
</h3>

<?php
	foreach($questions as $qid => $qa)
	{
?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info" role="alert">
			<div class="q"><?= $qa['q'] ?></div>

			<div class="a_btn"><button class="btn btn-success btn-xs">See Answer</button></div>
			<div class="a"><?= $qa['a'] ?></div>
		</div>
	</div>
</div>
<?php
	}
?>