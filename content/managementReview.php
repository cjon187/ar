<style>
	#managementReviews {
		border:1px solid #ccc;
		border-radius:4px;
		padding:5px 10px;
		margin:10px 0px;
		background-color:#DFF0D8;
	}
	#managementReviews tbody tr:first-child td {
		border:0px;
	}

	#managementReviewTitle {
		font-weight:bold;
		margin:5px 0px;
	}
</style>

<?php 
	if(!empty($managementReviews_typeID) && !empty($managementReviews_activityID)) {
?>

<script src="scripts/jquery.form.min.js"></script>
<script src="scripts/moment.js"></script>
<script>
	$(function() {	
		$('#managementReviewComment').focus();
		$('#managementReviewForm').ajaxForm(); 
		loadComments();
	});

	function loadComments() {
		$("#managementReviewsTable").find('tbody').html('');
		$.ajax({data:	{loadComments: '',
						 typeID: '<?= $managementReviews_typeID ?>',
						 activityID: '<?= $managementReviews_activityID ?>'},
				type:	'POST',
				url: '?s1=managementReview&typeID=<?= $managementReviews_typeID ?>&activityID=<?= $managementReviews_activityID ?>',
				dataType: 'script',
				success: function(data) {
					data = jQuery.parseJSON(data);
					if(data.length == 0) {
						$("#managementReviewsTable").find('tbody')
							.append($('<tr>')
								.append($('<td>')
									.html('No Comments Found.')
								)
						);
					} else {
						$.each(data, function(index,item) {
							$("#managementReviewsTable").find('tbody')
								.append($('<tr>')
									.append($('<td>')
										.html(item.staff)
									)
									.append($('<td>')
										.html(moment(item.date,'YYYY-MM-DD HH:mm').format('<b>YYYY</b> MMM D h:mmA'))
									)
									.append($('<td>')
										.html(item.comment)
									)
							);
						});
					}
				}
		});
	}


	function addManagementReview() {
		if($('#managementReviewComment').val() == '') {
			alert('No comments entered.');
		} else {
			$('#managementReviewAddBtn').attr('disabled','disabled');
			$('#managementReviewForm').ajaxSubmit({
				dataType: 'json',
				url: '?s1=managementReview&typeID=<?= $managementReviews_typeID ?>&activityID=<?= $managementReviews_activityID ?>',
				success: function(data) {
					if(data.success == 1) {
						loadComments();
						$('#managementReviewComment').val('');
						$('#managementReviewComment').focus();
					}
					else {
						alert('Error Occurred.' + data.errors);
					}
					$('#managementReviewAddBtn').removeAttr('disabled');
				}
			});
		}
	}
</script>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div id="managementReviews">
				<div id="managementReviewTitle">
					Management Reviews
				</div>
				<table id="managementReviewsTable" class="table table-condensed">
					<tbody>
					</tbody>
				</table>		
				<form method="POST" id="managementReviewForm">
					<input type="hidden" name="addComment" value="1">
					<input type="hidden" name="typeID" value="<?= $managementReviews_typeID ?>">
					<input type="hidden" name="activityID" value="<?= $managementReviews_activityID ?>">
					<div class="form-group">
						<textarea class="form-control" id="managementReviewComment" name="comments" placeholder="Write comment..." rows="3"></textarea>
						<div style="padding-top:3px">
							<button type="button" id="managementReviewAddBtn" class="btn btn-primary btn-xs" onClick="addManagementReview()">Add Comment</button>
						</div>
					</div>
				</form>					
			</div>
		</div>
	</div>	
</div>
<!-- <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="btn btn-sm btn-info" onClick="$('#managementReviewModal').modal('show')">Management Comments</div>
		</div>
	</div>
</div>
<div class="modal fade" id="managementReviewModal" tabindex="-1" role="dialog" aria-labelledby="managementReviewModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Management Review</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="managementReviews">
							<table id="managementReviewsTable" class="table table-condensed">
								<tbody>
								</tbody>
							</table>							
						</div>
						<form method="POST" id="managementReviewForm">
							<input type="hidden" name="addComment" value="1">
							<input type="hidden" name="typeID" value="<?= $managementReviews_typeID ?>">
							<input type="hidden" name="activityID" value="<?= $managementReviews_activityID ?>">
							<div class="form-group">
								<textarea class="form-control" id="managementReviewComment" name="comments" placeholder="Write comment..." rows="3"></textarea>
								<div style="padding-top:3px">
									<button type="button" class="btn btn-primary btn-xs" onClick="addManagementReview()">Add Comment</button>
								</div>
							</div>
						</form>
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>	 -->

<?php 
	}
?>