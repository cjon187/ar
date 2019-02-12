
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="scripts/jquery.numeric.min.js"></script>
<script src="scripts/moment.js"></script>

<link rel="stylesheet" href="scripts/datetimepicker/jquery.datetimepicker.css" />
<script src="scripts/datetimepicker/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="scripts/daterangepicker/jquery.comiseo.daterangepicker.css" />
<script src="scripts/daterangepicker/jquery.comiseo.daterangepicker.js"></script>

<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
<style>
	#nonprime {
		font-family: 'Raleway', sans-serif;
	}

	.errors {
		margin:10px 0px;
		padding:10px;
		background-color:#fce1da;
		border:1px solid red;
		color:red;
	}


	#nonprime label {
		font-size:0.9em;
		margin-bottom:2px;
	}

	#nonprime .form-group {
		margin-bottom:10px;
	}
	
	#nonprime .formSection {
		padding:10px;
		background-color:#f7f7f7;
		margin-bottom:10px;
	}

	.deleteBtnDiv {
		display:inline-block;
		margin-left:50px;
	}
	.daterangepicker {
		font-size:0.7em;
	}
	.btnsDiv {
		margin-bottom:10px;
	}



	.section {
		padding:5px 10px;
		background-color:#f7f7f7;
		border:1px solid #ccc;
		border-radius:3px;
		margin-top:10px;
		margin-bottom:10px;
	}
	.section .title {
		font-size:1.3em;
		padding:10px 0px;
		font-weight:bold;
	}
	.section .alert {
		padding:3px;
	}

	.section.overflow {
		height:300px;
		overflow-x:hidden;
		overflow-y:auto;
	}

	.sectionTable {
		font-size:0.9em;
	}

	.sectionTable thead th{
		background-color:#ccc;
	}
	.sectionTable tbody {
		background-color:white;
	}

	.sectionTable .sectionTableRow{
		background-color:white;
	}

	.sectionTable .sectionTableRow.notFound{
		font-weight:normal;
	}

	.sectionTable .sectionTableRow:not(.notFound):hover{
		background-color:#eee;
		cursor:pointer;
	}
	.sectionTable .followUpBadge {
		width:100px;
	}

	.sectionTable .followUpBadge div {
		padding:3px;
		font-size:0.8em;
		font-weight:bold;
		margin:0px;
		color:white;
		border-radius:2px;
		width:100%;
		text-align:center;
	}

	.sectionTable .bold {
		font-weight:bold;
	}

	.sectionTable .fixWidth20 {
		width:20px;
	}

	.sectionTable .fixWidth100 {
		width:100px;
	}

	.sectionTable .glyphicon {
		margin:0px 5px;
	}

	.pagination {
		margin:0px;
	}

	.paginationRow {
		margin-top:15px;
		margin-bottom:5px;
	}

</style>
<script>

	$(function() {
		$('[datetimepicker]').datetimepicker({
			format:'Y-m-d H:i:s',
			step:15
		});

		$('[datepicker]').datepicker({
			dateFormat:'yy-mm-dd',
		});

		$('[daterangepicker]').daterangepicker({
			presetRanges: [{
		         text: 'Today',
		         dateStart: function() { return moment() },
		         dateEnd: function() { return moment() }
		     }, {
		         text: 'Tomorrow',
		         dateStart: function() { return moment().add('days', 1) },
		         dateEnd: function() { return moment().add('days', 1) }
		     }, {
		         text: 'Yesterday',
		         dateStart: function() { return moment().subtract('days', 1) },
		         dateEnd: function() { return moment().subtract('days', 1) }
		     },{
		         text: 'Next 7 Days',
		         dateStart: function() { return moment() },
		         dateEnd: function() { return moment().add('days', 6) }
		     }, {
		         text: 'Next Week',
		         dateStart: function() { return moment().add('weeks', 1).startOf('week') },
		         dateEnd: function() { return moment().add('weeks', 1).endOf('week') }
		     },  {
		         text: 'Last 7 Days',
		         dateStart: function() { return moment().subtract('days', 6) },
		         dateEnd: function() { return moment() }
		     }, {
		         text: 'Last Week',
		         dateStart: function() { return moment().subtract('weeks', 1).startOf('week') },
		         dateEnd: function() { return moment().subtract('weeks', 1).endOf('week') }
		     }, {
		         text: 'All Time',
		         dateStart: function() { return moment("20150901", "YYYYMMDD") },
		         dateEnd: function() { return moment().add('year', 1) }
		     }],
		     datepickerOptions : {
		         numberOfMonths: 1,
		         minDate: null,
		         maxDate: null
		     }
		 });

		$('[numeric]').numeric();
	});

	function deleteTransaction(tID) {
		if(confirm('Are you sure you want to delete this transaction?'))
			location.href='?s1=<?= $_GET['s1'] ?>&s2=Transaction&delete&id=' + tID;
	}
</script>