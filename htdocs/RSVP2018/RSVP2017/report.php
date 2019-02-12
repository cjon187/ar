<?php
session_start('conference');
	include_once('displayUtils.php');
	include_once('mysqliUtils.php');
	include_once('defines.php');

	$db = new ARDB();

	$dataTableJSPath = AR_SECURE_URL."scripts/DataTables/media/js/";
	$dataTableExportJSPath = AR_SECURE_URL."scripts/DataTables/media/js/exportjs/";
	$dataTableCSSPath = AR_SECURE_URL."scripts/DataTables/media/css/";
	$dataTableExportCSSPath = AR_SECURE_URL."scripts/DataTables/media/css/exportcss/";


?>
<!doctype html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title>Trainer RSVP 2017 Report</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="../../gumby/js/libs/modernizr-2.6.2.min.js"></script>
	<script src="../../gumby/js/libs/gumby.min.js"></script>
	<script src="https://code.jquery.com/color/jquery.color-2.1.2.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../../gumby/css/gumby.css">

	<link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>

	<script src="<?= $dataTableJSPath ?>jquery.dataTables.min.js"></script>
	<script src="<?= $dataTableJSPath ?>jquery.dataTables.columnFilter.js"></script>


	<script src="<?= $dataTableExportJSPath ?>dataTables.buttons.min.js"></script>
	<script src="<?= $dataTableExportJSPath ?>buttons.flash.min.js"></script>
	<script src="<?= $dataTableExportJSPath ?>jszip.min.js"></script>
	<script src="<?= $dataTableExportJSPath ?>pdfmake.min.js"></script>
	<script src="<?= $dataTableExportJSPath ?>vfs_fonts.js"></script>
	<script src="<?= $dataTableExportJSPath ?>buttons.html5.min.js"></script>
	<script src="<?= $dataTableExportJSPath ?>buttons.print.min.js"></script>
	<link rel="stylesheet" href="<?= $dataTableCSSPath ?>jquery.dataTables.min.css" />
	<link rel="stylesheet" href="<?= $dataTableExportCSSPath ?>buttons.dataTables.min.css" />


</head>
<style>
	.container{
		width: 1600px;
		margin: 0 auto;
		background-color: #eee;
		overflow:scroll;
	}

	table tr td, table tbody tr td {
		font-size: 14px;
	}

	table.dataTable tbody th, table.dataTable tbody td {
		padding: 3px 5px;
	}

	table thead tr th {
		font-size: 14px;
	}

	table.dataTable thead th, table.dataTable thead td {
		padding: 5px 15px;
	}

	.showing{
		border: 1px solid black;
		border-radius: 5px;
		padding: 0 2px;
	}

	.toggle-vis{

	}

	.black{
		color:black;
	}

</style>
<!--  buttons: [
	            'copy', 'csv', 'excel', 'pdf', 'print',
	            exportOptions:{ columns: ':visible'}
	        ] -->
<script>
	var compareTable_obj;

	$(document).ready(function(){
		compareTable_obj = $('#compareTable').DataTable({
			"paging": false,
			dom: 'Bfrtip',
	        buttons: [
	            { extend: 'print', 	exportOptions: { columns: ':visible'}},
	            { extend: 'csv', 	exportOptions: { columns: ':visible'}},
	            { extend: 'excel', 	exportOptions: { columns: ':visible'}},
	            { extend: 'copy', 	exportOptions: { columns: ':visible'}},
	            { extend: 'pdf', 	exportOptions: { columns: ':visible'}}
	        ]
		});

		$('a.toggle-vis').on( 'click', function (e) {
	        e.preventDefault();
	        // Get the column API object
	        var column = compareTable_obj.column( $(this).attr('data-column') );
	        // Toggle the visibility
	        column.visible( ! column.visible() );
	        setSelected($(this));

	    } );



		loadTable();


	});

	function loadTable(){
		<?php
			$rows = TrainerConference2017::get();

			if(count($rows) > 0 && $rows !== null){
				foreach($rows as $g){
					?>
					compareTable_obj.row.add([
						   	<?= '"'. $g->firstname.'"' ?> ,
						   	<?= '"'. $g->lastname.'"' ?> ,
						   	<?= '"'. $g->mobile.'"' ?> ,
						   	<?= '"'. $g->email.'"' ?>,
						   	<?= '"'. $g->territory.'"' ?>,
						   	<?= '"'. $g->superbowl.'"' ?>,
						   	<?= '"'. $g->sundayAccommodations.'"' ?>,
						   	<?= '"'. $g->willShareRoom.'"' ?>,
						   	<?= '"'. $g->updatedAt.'"' ?>,
						   	<?= '"'. str_replace("\r\n", "", $g->notes).'"' ?>,
						]).draw(true);
					<?php
				}
			}

			/*for($int = 5; $int < 17; $int++){
				?>
				var column = compareTable_obj.column(<?= $int ?>);
				column.visible( ! column.visible() );
				<?php
			}*/
		?>
	}

	function selectAll(){
		$('a.toggle-vis').each(function(){
			if(!$(this).hasClass('showing')){
				var column = compareTable_obj.column( $(this).attr('data-column') );
	        	column.visible( ! column.visible() );
	       		setSelected($(this));
			}

		});
	}
	function unSelectAll(){
		$('a.toggle-vis').each(function(){
			if($(this).hasClass('showing')){
				var column = compareTable_obj.column( $(this).attr('data-column') );
	        	column.visible( ! column.visible() );
	       		setSelected($(this));
			}

		});
	}

	function setSelected(elem){
		if(elem.hasClass('showing')){
	        	elem.removeClass('showing');
        }
        else{
        	elem.addClass('showing');
        }
	}

	function submitForm(){
		$('#rsvpForm').submit();
	}

</script>
<body>

	<div class="container">
		<h2>RSVP Report</h2>
		<hr>
		<div class="reportDiv">

			<!--<div>
				<h4 style="text-decoration:underline; display:inline-block;">Toggle columns: </h4>
					<a href="" onClick="selectAll(); return false;"> &nbsp;&nbsp;&nbsp;&nbsp;- Select All</a>
					<a href="" onClick="unSelectAll(); return false;"> &nbsp;&nbsp;&nbsp;&nbsp;- UnSelect All</a>
					<div style="margin-left: 20px;">
						<h6 style="color: blue; font-size: 1.4em; text-decoration:underline">Default</h6>
						- <a class="toggle-vis black showing" data-column="1">FirstName</a>
						- <a class="toggle-vis black showing" data-column="2">LastName</a>
						- <a class="toggle-vis black showing" data-column="3">Phone</a>
						- <a class="toggle-vis black showing" data-column="4">Email</a>
						- <a class="toggle-vis black showing" data-column="5">Territory</a>
						- <a class="toggle-vis black showing" data-column="6">Superbowl</a>
						- <a class="toggle-vis black showing" data-column="7">Accommodations</a>
						- <a class="toggle-vis black showing" data-column="8">Notes</a>
						<br>
						<h6 style="color: blue; font-size: 1.4em; text-decoration:underline">Extra</h6>
						- <a class="toggle-vis black" data-column="5">GuestFirstName</a>
					</div>


			</div>
			<hr> -->
			<div class="compareTable">
				<table id="compareTable" class="table table-striped" style="width: 100%;" >
					<thead>
						<tr>
							<th>FirstName</th>
							<th>LastName</th>
							<th>Phone</th>
							<th>Email</th>
							<th>Territory</th>
							<th>Superbowl</th>
							<th>Accommodations</th>
							<th>Willing to share</th>
							<th>Updated At</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>

		</div>

	</div>

</body>
</html>

