<?php include_once('leads_header.php'); ?>

<style>
	#nonprime > .row h1 {
		line-height:1em;
		margin-bottom:0px;
	}
	.filters {
		margin-top:5px;
		padding:10px;
		background-color:#f7f7f7;
	}

	.filters label{
		width:100px;
	}

	.dealers {
		margin:10px 0px;
	}
	.dealers .row:first-child .dealer {
		border-top:3px solid #555;
	}
	.dealer {
		padding: 15px 10px;
		margin:0px;
		border-bottom:1px solid #555;
		font-size:1em;
		cursor:pointer;
	}
	.dealer.inactive {
		color: #ccc;
	}
	.dealer:hover {
		background: #f7f7f7;
	}
	.dealer .title {
		font-weight:bold;
		font-size:1.1em;
	}

	.signed {
		color:green;
		font-weight:bold;
		font-size:0.9em;
	}
</style>
<script>
	$(function() {
		
		$('[leadDealerID] .dealerInfo').click(function() {
			location.href='?s1=<?= $_GET['s1'] ?>&s2=Dealers&s3=Edit&id=' + $(this).parents('[leadDealerID]').attr('leadDealerID');
		})
	});

</script>

<div class="container-fluid" id="nonprime">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="?s1=<?= $_GET['s1'] ?>">Leads</a></li>
				<li class="active">Active Dealers</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="pull-right">
				<button type="button" class="btn btn-primary btn-xs" onClick="location.href='?s1=<?= $_GET['s1'] ?>&s2=Dealers&s3=Edit'">Add Dealer</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="dealers">
	<?php
		if(is_null($dealers)) {
	?>
				<div class="row">
					<div class="col-md-12">
						<div class="dealer">
							No Dealers Found
						</div>
					</div>
				</div>
				
	<?php
		}
		else {
			foreach ($dealers as $leadDealer) {
				$quote = $leadDealer->quote; 
	?>
				<div class="row">
					<div class="col-md-12">
						<div class="dealer <?= ($leadDealer->status != 1 ? 'inactive' : '') ?>" leadDealerID="<?= $leadDealer->id ?>">
							<div class="row">
								<div class="col-md-4">
									<div class="dealerInfo">
										<div class="title"><?= $leadDealer->dealer->name ?></div>
										<div><?= $leadDealer->staff->name ?></div>
									</div>
								</div>
								<div class="col-md-8">
									<?php
										if(!empty($quote->quoteID)) {
									?>
											<button type="button" class="btn btn-success btn-xs" onClick="window.open('export/quote/?id=<?= $quote->id ?>&ekey=<?= encrypt($quote->id,'quote') ?>')">View Quote</button>
										
										<?php
											if($quote->quoteSigned != "") { 
										?>
											<span class="signed">Quote signed on <?= date("M j, Y",strtotime($quote->quoteSigned)) ?>.</span>
										<?php
											}
											else {
										?>
											<button type="button" class="btn btn-primary btn-xs" onClick="window.open('?s1=quote&s2=Add&qid=<?= $quote->id ?>','editQuote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1')">Edit Quote</button>
										<?php
											}
										?>
									<?php
										}
										else {
									?>
										<button type="button" class="btn btn-primary btn-xs" onClick="var win = window.open('?s1=quote&s2=Add&new&typeID=<?= WorksheetType::NONPRIME_PACKAGE_3_EVENTS ?>&dealerID=<?= $leadDealer->dealer->id ?>','addquote','width=750,height=750,toolbar=0,scrollbars=1,resizable=1');win.focus();">Create Quote</button>
			
									<?php
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
	<?php				
			}
		}
	?>
			</div>
		</div>
	</div>
</div>