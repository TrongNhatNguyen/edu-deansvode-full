var multiSelection ={

	chkName : '',
	
	loadMultiTable: function() {
		oTable = $('.tableData').dataTable({
			"bAutoWidth": false,
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"aaSorting": [],
			columnDefs: [{
				targets: [0, 1, 2, 3, 4, 5], // column or columns numbers
				orderable: false,  // set orderable for selected columns
			}],
			"columns": [
				{className: "th1"},
				{className: "th2"},
				{className: "th3"},
				{className: "th4"},
				{className: "th5"},
				{className: "th6"},
			],
		});
	},

	loadTableAfterFilter : function(data)
	{
		if ( $.fn.dataTable.isDataTable( '#chooseList' ) ) {
			oTableChoose = $('#chooseList').DataTable();
			oTableChoose.destroy();
			$( "#data-filter" ).html(data);
			$('#chooseList').DataTable({
				"bJQueryUI": true,
				"sPaginationType": "full_numbers",
				"aaSorting": [],
				columnDefs: [ {
					targets: [0, 1, 2, 3, 4,5], // column or columns numbers
					orderable: false,  // set orderable for selected columns
				}],
			});
		}
		else {
			oTableChoose = $('#chooseList').DataTable( {
				paging: true
			} );
		}
	},

	addAll: function(){

	},
	managerMultiTable:function()
	{
		/** select list **/
		$('#add').click(function() {
			var nonCheck = true;
			var oTableSend = $('#sendList').DataTable();
			var oTableChoose = $('#chooseList').DataTable();

			$('input[name="checkSelect"]:checked').each(function() {
				THIS = $(this).parent().parent();
				oTableSend.row.add( [
					'<input type="hidden" name="sendlist[]" value="'+THIS.find('td').eq(0).find('input').val()+'"><input type="checkbox" class ="chkRowSend" name="' + multiSelection.chkName + '"/>',
					THIS.find('td').eq(1).html(),
					THIS.find('td').eq(2).html(),
					THIS.find('td').eq(3).html(),
					THIS.find('td').eq(4).html(),
					THIS.find('td').eq(5).html(),
					THIS.find('td').eq(6).html(),
				] ).draw( false );

				THIS.addClass('row_selected');
				oTableChoose.row( $('tr.row_selected')[0] )
					.remove()
					.draw();
				nonCheck = false;
			});

			if (nonCheck)
			{
				alert('You must choose at leat one record.');
			}

		});
		
		/** remove list **/
		$('#remove').click(function(){
			var nonCheck = true;
			var oTableSend = $('#sendList').dataTable();
			var oTableChoose = $('#chooseList').dataTable();
			$('input[name="' + multiSelection.chkName + '"]:checked').each(function(){
				THIS = $(this).parent().parent();
				oTableChoose.fnAddData( [
	                                   '<input type="checkbox" name="checkSelect" class="chkRowChoose" value="'+THIS.find('td').eq(0).find('input').val()+'">',
									THIS.find('td').eq(1).html(),
									THIS.find('td').eq(2).html(),
					 				THIS.find('td').eq(3).html(),
					                THIS.find('td').eq(4).html(),
					                THIS.find('td').eq(5).html(),
									THIS.find('td').eq(6).html(),
				] );
	            
				THIS.addClass('row_selected');
				oTableSend.fnDeleteRow( oTableSend.$('tr.row_selected')[0] );
				
				nonCheck = false;
			});
			if (nonCheck)
			{
				alert('You must choose at leat one record.');
			}
			
		});

		/** select all list **/
		$('#addAll').click(function() {
			var nonCheck = true;
			var oTableSend = $('#sendList').DataTable();
			var oTableChoose = $('#chooseList').DataTable();
			var table = $('#chooseList').dataTable();
			table.$('tr', {"filter":"applied"}).each( function () {
				oTableSend.row.add( [
					'<input type="hidden" name="sendlist[]" value="'+$(this).find("td:eq(0)").find('input').val()+'"><input type="checkbox" class ="chkRowSend" name="' + multiSelection.chkName + '"/>',
					$(this).find("td:eq(1)").html(),
					$(this).find("td:eq(2)").html(),
					$(this).find("td:eq(3)").html(),
					$(this).find("td:eq(4)").html(),
					$(this).find("td:eq(5)").html(),
					$(this).find("td:eq(6)").html(),
				] ).draw( false );
				$(this).addClass('row_selected');
				oTableChoose.row( $('tr.row_selected')[0] )
					.remove()
					.draw();
			} );


		});

		/**Remove all list**/
		$('#removeAll').click(function() {
			var oTableSend = $('#sendList').dataTable();
			var oTableChoose = $('#chooseList').dataTable();

			oTableSend.$('tr', {"filter":"applied"}).each( function () {
				oTableChoose.fnAddData( [
					'<input type="checkbox" name="checkSelect" class="chkRowChoose" value="'+$(this).find('td').eq(0).find('input').val()+'">',
					$(this).find("td:eq(1)").html(),
					$(this).find("td:eq(2)").html(),
					$(this).find("td:eq(3)").html(),
					$(this).find("td:eq(4)").html(),
					$(this).find("td:eq(5)").html(),
					$(this).find("td:eq(6)").html(),
				] );
				$(this).addClass('row_selected');
				oTableSend.fnDeleteRow( oTableSend.$('tr.row_selected')[0] );

			});

		});

	},
}

//Xử lý popup
$( '#country_popup,#subzone_popup').change(function() {
	var id_country = $('#country_popup').val();
	var id_subzone = $('#subzone_popup').val();
	var school_name = $('#school_name').val();
	loadData(id_country,id_subzone,school_name);

});
function search_name(){
	var id_country = $('#country_popup').val();
	var id_subzone = $('#subzone_popup').val();
	var school_name = $('#school_name').val();
	loadData(id_country,id_subzone,school_name);
}


