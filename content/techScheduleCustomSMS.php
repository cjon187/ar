<style>
	hr{
		margin: 0px 0px 10px 0;
		border-top: 1px solid #000;
	}
	
	.form-control{
		height: 24px;
		padding: 2px 4px;
	}

	#staffNav{
		border: 2px solid black;
	}

		#staffNav .listHref{
			padding: 3px 4px; 
			color:black;
			text-align:left;
		}

		#staffNav .listSelected{
			background-color: #ddd;
			font-size: 1.3em;
			font-weight: bold;
		}
		#staffNav .hideRow{
			display:none;
		}
		#staffNav .hideRowLanguage{
			display: none;
		}
		.sort{
			margin: 5px 0;
		}

	#addAbroad{
		background-color: #1E90FF;
		padding: 5px 7px;
		width: 100%;
		color: white;
		font-weight: bold;
		border-radius: 5px;
		margin-top: 5px;
	}
		.abroadRow{
			margin-left: 15px;
			font-weight: bold;
		}
		.licenseRow{
			margin-left: 15px;
			font-size: 0.9em;
			font-weight:bold;
		}

	.searchDiv{
		margin: 1px 0;
	}
	.staffLevelSortDiv{
		margin: 1px 0;
	}
	.languageSortDiv{
		margin: 1px 0;
	}

	.required{
		background-color: #FFA07A;
	}

	.submitButton{
		color:white;
		padding: 8px 13px;
		border:none;
		border-radius: 5px;
		font-weight: bold;
		font-size: 1.1em;
		margin-right: 15px;
	}
		 .icon-input-btn{
		        display: inline-block;
		        position: relative;
		    }
		    .icon-input-btn input[type="submit"]{
		        padding-left: 2em;
		    }
		     .icon-input-btn input[type="button"]{
		        padding-left: 2em;
		    }
		    .icon-input-btn .glyphicon{
		        display: inline-block;
		        position: absolute;
		        left: 0.65em;
		        top: 27%;
		        color: white;
		    }
		.deleteSubmit{
			background-color: red;
		}
		.updateSubmit{
			background-color: green;
		}
		.createSubmit{
			background-color: green;
		}
		.newStaff{
			background-color: #1E90FF;
		}

	.has-error{
		background-color: #FDD7E4 !important;
	}

	.pageContainer{
		font-size: 1.0em;
	}
	.staffPagesHeader{
		/* text-decoration:underline;  */
		background-color:#3399FF; 
		color:white;
		padding: 3px 0 3px 5px;
		margin-bottom: 0px;
		border-top-right-radius: 5px;
		border-top-left-radius: 5px;
	}
	.pageRow{
		height: 30px;
		border-top: 1px solid #ddd;
		padding-top: 3px;
		text-align:left;
	}
		.mainPage{
			margin-left: 15px;
		}

		.pageSection{
			margin-left: 60px;	
			text-align:left;	
		}
		.pageChild{
			margin-left: 30px;
			text-align:left;
		}
		.pagePermission{
			display:inline-block;
			
		}
		.pageName{
			display:inline-block;
		}

		.pageSection + .pageRow, .pageChild + .pageRow{
			margin-top: 7px;
		}

	.form-group label{
		line-height: 0.80em;
	}

	.thirtyDays{
		background-color: red;
		color: white;
		border-radius: 3px;
		
		margin-bottom: 3px;
	}
	.sixtyDays{
		background-color: yellow;
		color: black;
		border-radius: 3px;
		
		margin-bottom: 3px;
	}
</style>

<!-- THIS IS FOR FORM VALIDATION -->
<script src="scripts/jquery.validate.min.js"></script>
<link rel="stylesheet" href="scripts/multiple-select-master/multiple-select.css" />
<script src="scripts/multiple-select-master/jquery.multiple.select.js"></script>
<link rel="stylesheet" href="scripts/datetimepicker/jquery.datetimepicker.css" />
<script src="scripts/datetimepicker/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">


	$(document).ready(function(){


		$("#languages").multipleSelect();
		
		$("#staffLevelSearch").multipleSelect({
			'minimumCountSelected':1 
		});
		$("#staffLanguageSearch").multipleSelect({
			'minimumCountSelected':1 
		});

		$('#staffLevelSearch').change(function(){
			updateDropdown();
		});
		
		$('#searchInput').on('input',function(){
			updateDropdown();
		});

		$('#staffLanguageSearch').on('change',function(){
			updateDropdown();
		});

		
		updateDropdown();
		

		$('.parent').on('click', function(index){
			var parent = $(this);
			var id = $(this).attr('id').split('_')[0];

			$('.child_'+id).each(function(){
				if(parent.prop("checked") == true){
					if($(this).prop('checked') == false){
						$(this).prop("checked", true);
						$(this).trigger('change');
					}
				}
				else{
					if($(this).prop('checked') == true){
						$(this).prop("checked", false);
						$(this).trigger('change');
					}
				}
			});	
		});
	});		
	//END OF ON PAGE LOAD


	function updateDropdown(){	
		$.ajax({data:	{sortStaffLevel: $('#staffLevelSearch').multipleSelect('getSelects')},
					type:	'GET',
					dataType: 'script'
		   	    });	
		$.ajax({data:	{searchInput: $('#searchInput').val()},
					type:	'GET',
					dataType: 'script'
		   	    }); 

		var temp = $('#staffLanguageSearch').multipleSelect('getSelects');
			if(temp == false) temp = "";
			$.ajax({data:	{sortLanguage: temp},
					type:	'GET',
					dataType: 'script'
		   	    });

		$('.listHref').each(function(){
			var show = true;
			var rowID = $(this).attr('id').split('_');
			var staffLevel = rowID[1];
			var name = $(this).data('value');
			
			//FILTER ON STAFF NAME
			if($('#searchInput').val() != ""){
				var search = $('#searchInput').val();
				if (name.toLowerCase().indexOf(search.toLowerCase()) < 0){
					show = false;
				}				
			}

			//FILTER ON STAFF LEVEL
			if($.inArray(staffLevel, $('#staffLevelSearch').multipleSelect('getSelects')) < 0){
				show = false;
			}

			var staffLanguages = String($(this).data("languages")).split(',');
			var setLanguages = $('#staffLanguageSearch').multipleSelect('getSelects');
			if(setLanguages.length > 0){
				var intersect = $(staffLanguages).filter(setLanguages);
				if(intersect.length == 0){
					show = false;
				}
			}


			if(show == true){
				$(this).parent().removeClass('hideRow');
			}
			else{
				$(this).parent().addClass('hideRow');
			}

		});

		<?php
		if($staffSelected){
			?>
				$('#staffNav').scrollTop($('.listSelected').parent().position().top - $('.listSelected').height() - 110);
			<?php
		}	
		?>
	}



	//ON FORM SUBMIT, 
	function submitHandler(){
		return true;
	}


</script>


<div class="containerStaff">
	<div class="row">
	

		<div class="col-sm-3" style="">
			<h4 style="text-decoration: underline;">Staff Search:</h4>
			<div class="searchDiv">
				<div style="width:40%; display:inline-block;" >By Staff Name: </div> <input id="searchInput" type="text" value="<?= (isset($_SESSION['customSMS']['searchInput']) ? $_SESSION['customSMS']['searchInput'] : '' ) ?>" style="width: 60%; float:right; ">
			</div>
			<div class="staffLevelSortDiv" style="clear:both;">
				<div style="width:38%; display:inline-block;" >By Staff Level: </div> 
				<select id="staffLevelSearch" name="staffLevelSearch" style="margin-left: 5px; width: 60%; display:inline-block; padding:0px; float:right; ">
					<?php
					$sls = array();
					if(isset($_SESSION['customSMS']['staffLevelSearch'])){
						$sls = $_SESSION['customSMS']['staffLevelSearch'];
					}
					$staffLevels = StaffLevel::getList();
				 	foreach($staffLevels as $key => $sl) {
				 		if(in_array($key, array(9,10,11))){ ?>
				 			<option value="<?= $key ?>" <?= (in_array($key, $sls ) ? 'SELECTED' : '' ) ?> ><?= $sl ?></option>
				 			<?php
				 		} 
				 	}

					?>
				</select>
			</div>
			<div class="languageSortDiv" style="clear:both;">
				<div style="width:38%; display:inline-block;" >By Language: </div> 
				<select id="staffLanguageSearch" name="staffLanguageSearch" style="margin-left: 5px; width: 60%; display:inline-block; padding:0px; float:right; ">
					<?php
						$lang = array();
						if(isset($_SESSION['customSMS']['staffLanguageSearch']) && $_SESSION['customSMS']['staffLanguageSearch'] != "" ){
							$lang = $_SESSION['customSMS']['staffLanguageSearch'];
						}
						$languages = Language::getList('majorFull', array('status'=>1, 'defaultLanguage'=>1));
					 	foreach($languages as $key => $l) { ?>
							<option value="<?= $key ?>" <?= (in_array($key, $lang ) ? 'SELECTED' : '' ) ?> ><?= $l ?></option>
					<?php 
					}
					?>
				</select>
			</div> 



			<nav id="staffNav" style="clear:both; height: 500px; overflow: scroll; background-color: #eee; color: black; font-size: 0.9em; ">
			  <ul class="nav nav-pills nav-stacked span2">
			  	<?php
			  		$allStaff = new Staff();
			  		//$allStaff = $allStaff->getActiveStaff('level');
			  		$allStaff = getActiveStaffArray('level');
			  		foreach($allStaff as $s){
			  			?>
							<li class="staffLI" style="border-bottom: 1px solid #ccc;">
								<a id="<?= $s['staffID'] ?>_<?= $s['staffLevelID'] ?>" data-languages= "<?= $s['languages'] ?>" data-value="<?= $s['name'] ?>" href="?s1=techSchedule&s2=CustomSMS&id=<?= $s['staffID'] ?>" class="listHref <?= ( $s['staffID'] == $staff->staffID ? 'listSelected' : '' ) ?>"  >
									<?= '[' . strtoupper($s['abbreviation']) .'] ' . $s['name'] ?>
								</a>
							</li>
			  			<?php
			  		}
			  	?>
			  </ul>
			</nav>
		</div>


		<div class="col-sm-9">
			<div id="errorsDiv">
				<?php
				if(count($errors) > 0){
					?>
					<h3 style="color:red; margin:5px 0; text-decoration:underline;">Errors</h3>
					<?php
					foreach($errors as $error){
						?>
							<div class="errorRow" style="color: red;">- <?= $error[0] ?></div>
						<?php
					}
				}
				?>
			</div>

			<form method="POST" id="dealerForm" onSubmit="return submitHandler();">
				<div class="row" style="margin-bottom: 5px;">
					<?php
					if($_SESSION['login']['section']['staff_admin']) { 		
						?>
						<span class="icon-input-btn" style="float:right;">
							<span class="glyphicon glyphicon-pencil"></span> 
							<input type="submit" name="action" class="updateSubmit submitButton" value="Update">
						</span>

						<?php
					}
					?>			
				
				</div>

				
				<input type="hidden" name="staffID" value = "<?= $staff->staffID ?>">
				<div class="row">
					<div class="col-sm-1">
						<div class="form-group">
							<label for="name">StaffID</label>
							<input type="text" class="form-control" id="displayStaffID" name="displayStaffID" disabled value="<?= $staff->staffID ?>" placeholder="Staff Name">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="name">Staff Name</label>
							<input type="text" class="form-control" id="displayName" name="displayName" disabled value="<?= $staff->name ?>" placeholder="Staff Name">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="name">Custom SMS (Max 160 Characters)</label>
							<textarea maxlength="160" class="form-control" rows="2" id="smsText" name="smsText"><?= $staff->trainerCustomSms->smsText ?></textarea>
						</div>
						
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="name">Custom SMS Reply (Max 160 Characters)</label>
							<textarea maxlength="160" class="form-control" rows="2" id="smsReply" name="smsReply"><?= $staff->trainerCustomSms->smsReply ?></textarea>
						</div>
						
					</div>
				</div>

					
				<div class="row" style="margin-top: 10px;">
					<div class="col-sm-3">
						<span class="icon-input-btn"  >
							<span class="glyphicon glyphicon-pencil"></span> 
							<input type="submit" name="action" class="updateSubmit submitButton" value="Update">
						</span>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>




