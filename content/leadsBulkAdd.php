<?php
include_once('leads_header.php');

function buildCSVFieldDropDown($options,$name) {
	$html = '
<select name="'.$name.'">
	';
	foreach($options AS $key=>$val) {
		$html .= '<option value="'.$key.'">'.$val.'</option>';
	}
	$html .= '
</select>	
	';
	return $html;
}

?>
<link rel="stylesheet" type="text/css" href="/css/fileuploader/jquery.fileupload.css"/>
	<style>
	.progress {
		height: 20px;
		margin-bottom: 20px;
		margin-top: 20px;
		overflow:hidden;
		background-color:#f5f5f5;
		border-radius: 4px;
		box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
	}

	.progress-bar {
		height: 100%;
		background: #449d44;
	}

	.error {
		color: red;
	}

	.success {
		color: #449d44;
	}

	#preview table {
		font-size:10pt;
	}

	.gly-spin {
	  -webkit-animation: spin 2s infinite linear;
	  -moz-animation: spin 2s infinite linear;
	  -o-animation: spin 2s infinite linear;
	  animation: spin 2s infinite linear;
	}
	@-moz-keyframes spin {
	  0% {
	    -moz-transform: rotate(0deg);
	  }
	  100% {
	    -moz-transform: rotate(359deg);
	  }
	}
	@-webkit-keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	  }
	}
	@-o-keyframes spin {
	  0% {
	    -o-transform: rotate(0deg);
	  }
	  100% {
	    -o-transform: rotate(359deg);
	  }
	}
	@keyframes spin {
	  0% {
	    -webkit-transform: rotate(0deg);
	    transform: rotate(0deg);
	  }
	  100% {
	    -webkit-transform: rotate(359deg);
	    transform: rotate(359deg);
	  }
	}
	.gly-rotate-90 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
	  -webkit-transform: rotate(90deg);
	  -moz-transform: rotate(90deg);
	  -ms-transform: rotate(90deg);
	  -o-transform: rotate(90deg);
	  transform: rotate(90deg);
	}
	.gly-rotate-180 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
	  -webkit-transform: rotate(180deg);
	  -moz-transform: rotate(180deg);
	  -ms-transform: rotate(180deg);
	  -o-transform: rotate(180deg);
	  transform: rotate(180deg);
	}
	.gly-rotate-270 {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
	  -webkit-transform: rotate(270deg);
	  -moz-transform: rotate(270deg);
	  -ms-transform: rotate(270deg);
	  -o-transform: rotate(270deg);
	  transform: rotate(270deg);
	}
	.gly-flip-horizontal {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1);
	  -webkit-transform: scale(-1, 1);
	  -moz-transform: scale(-1, 1);
	  -ms-transform: scale(-1, 1);
	  -o-transform: scale(-1, 1);
	  transform: scale(-1, 1);
	}
	.gly-flip-vertical {
	  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1);
	  -webkit-transform: scale(1, -1);
	  -moz-transform: scale(1, -1);
	  -ms-transform: scale(1, -1);
	  -o-transform: scale(1, -1);
	  transform: scale(1, -1);
	}

	.modal-vertical-centered {
	  transform: translate(0, 50%) !important;
	  -ms-transform: translate(0, 50%) !important; /* IE 9 */
	  -webkit-transform: translate(0, 50%) !important; /* Safari and Chrome */
	}

	ul.errors li {
		list-style-type:none;
		color:red;
	}

	ul.errors li span {
		color:red;
	}

	li.error {
		color:red;
	}
	</style>
<div class="container-fluid">
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="/index.php?s1=leads">Leads</a></li>
			<li class="active">Add Bulk Leads</li>
		</ol>
	</div>
</div>
<div id="nonprime">
<div class="progress">
	<div class="progress-bar progress-bar-success" style="width: 0%"></div>
</div>
<div id="finished"></div>
	<form method="post">
		<div class="formSection">
			<div class="row">
				<div class="col-md-6">
					<label>File Source</label>
					<input type="file" name="uploadfile" id="fileupload" value="" class="form-control" data-url="/index.php?s1=leads&s2=BulkAdd&doUpload=true"/>
				</div>
			</div>
		</div>
	</form>
	<div id="preview" style="display:none;">
		<ul id="errors"></ul>
		<table class="table table-condensed table-striped">
			<tbody></tbody>
		</table>
			<div class="row">
				<div class="col-md-4">
					Dealer&nbsp;&nbsp;<select id="dealer-id" name="dealer" class="form-control">
						<option value="">-- Choose a Dealer --</option>
					<?php
						foreach($allDealers AS $dealerLeadID=>$dealerName) {
							echo '<option value="'.$dealerLeadID.'">'.$dealerName.'</option>';
						}
					?>
					</select>
				</div>
				<div class="col-md-4">
					Skip Header Line&nbsp;&nbsp;<input id="ignore-first" type="checkbox" name="ignore-first" value="1"/>
				</div>
				<div class="col-md-4">
				Date Format&nbsp;&nbsp;
				<select name="date-format" id="date-format">
				<?php
					foreach($dateFormatFields AS $idx=>$fieldRow) {
						echo '<option value="'.$idx.'">'.$fieldRow['name'].'</option>';
					}
				?>
				</select>
				</div>

			</div>
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-success" id="donebtn"><span id="save-btn-icon" class="glyphicon glyphicon-upload gly-spin" style="padding-left: 5px; display:none;"></span>Save</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script src="/scripts/fileuploader/jquery.ui.widget.js"></script>
<script src="/scripts/fileuploader/jquery.iframe-transport.js"></script>
<script src="/scripts/fileuploader/jquery.fileupload.js"></script>
<script src="/scripts/lodash.js"></script>
<script src="/scripts/bootbox.min.js"></script>
<script>
var fieldOptions = <?php echo json_encode($optionFields); ?>;

var posSel = function(name) {
	var $sel = $('<select></select>');
	$sel.attr({
		'name':'position['+name+']'
	})
	.addClass('form-control')
	.addClass('input-sm')
	.addClass('positionoption');
	$sel.append('<option value=""></option>');
	_.forEach(fieldOptions,function(val,key) {
		$sel.append('<option value="'+key+'">'+val+'</option>');
	});
	return $sel;
};

$(function() {
	var $preview = $('#preview');
	var $table = $('table',$preview);
	var $body = $('tbody',$table);
	var $errors = $('#errors');

	$('#fileupload').fileupload({
		dataType:'json',
		done:function(e,data) {
			var resp = data.result;
			if(_.has(resp,'errors')) {
				var errors = resp.errors;
				_.forEach(resp.errors,function(val,key) {
					console.log(val);
					$errors.append('<li class="error glyphicon glyphicon-remove-circle">'+val+'</li>');
				});
				bootbox.dialog({
					title: 'Errors',
					message: $errors.html()
				});
				return false;
			}
			//take the first row as the one to use
			var first = _.first(resp.data);
			var numFields = first.length;

			var counter = 1;

			for(var i=0;i<numFields;i++) {
				var $row = $('<tr></tr>');
				var $first = $('<td></td>');
				$first.append(posSel(i));
				$row.append($first);
				$.each(resp.data,function(idx,value) {
					var $td = $('<td></td>').text(value[i]);
					if(idx < 1) {
						$td.css({'fontWeight':"bold",'backgroundColor':'#ccc'});
					}
					$row.append($td);
				});
				$body.append($row);
			}
			$preview.show();
		},
		progress:function(e,data) {
			//console.log(data);
			var progress = parseInt(data.loaded / data.total * 100,10);
			$('.progress-bar').css('width',progress+'%');
		},
		submit:function(e,data) {
			$body.html('');
			$errors.html('');
			//console.log('submit');
			//console.log(data);
		}
	});

	$('#donebtn').on('click',function(e) {
		var $btn = $(this);
		$errors.html('');
		var $dealerID = $('#dealer-id').val();
		if($dealerID == '' || $dealerID < 1) {
			return;
		}
		var options = $('.positionoption');
		var hasOneVal = false;
		var params = new Array();
		$('.positionoption').each(function(idx,value) {
			var selVal = $(value).val();
			var selIdx = idx;
			if(!_.isEmpty(selVal)) {
				hasOneVal = true;
				params.push({
					'val':selVal,
					'idx': idx
				});
			}
		});
		if(!_.isEmpty(params)) {
			$btn.prop('disabled',true).find('span').show();
			$.ajax('/index.php?s1=leads&s2=BulkAdd&finish=true',{
				'method':'POST',
				'dataType':'json',
				'data': {
					'dealerID': $dealerID,
					'header':$('#ignore-first').is(':checked'),
					'dateFormat':$('#date-format').val(),
					info:params
				},
				complete: function() {
					$btn.prop('disabled',false).find('span').hide();
					//console.log('done!');
				},
				success: function(resp,req) {
					if(_.has(resp,'errors')) {
						_.forEach(resp.errors,function(val,key) {
							$errors.append('<li class="error glyphicon glyphicon-remove-circle">'+val+'</li>');
						});
						bootbox.dialog({
							title: 'Errors',
							message: $errors.html()
						});
						//$errors.show();
						return false;
					}
					//$preview.html('').append('<h3>Done! Sent '+resp.data.total+' possible leads</h3>');
					$('#nonprime').html('').append('<h2>Done! Sent '+resp.data.total+' possible leads</h3>');
				},
				error: function(req) {
					alert('well...that seems bad....something died and you may need to try again');
					$btn.prop('disabled',false).find('span').hide();
				}
			})
		}
	});
});

</script>