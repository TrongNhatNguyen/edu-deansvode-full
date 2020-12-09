
var list ={
	
	url				: '/ranking/index',
	edit_action 	: 'index',
	page			: 1,
	currentObj 		: null,
	
	
	data:{
		
		filter_field:{},
		filter_value:{}
		
	},
	
	edit: function(id){
		window.location = this.url + '/' + this.edit_action + '/id/' + id;
	},
	 
	swapImage: function(src){
		
		 return ( 
		              src == config.icon_unactive ? config.icon_active : config.icon_unactive);		
	},

	updateHTML: function(data){
		
		switch(data.action){
			
			case 'toggle-status':
				
				if(data.key == 'single'){
					
					var src = this.currentObj.attr('src');
					this.currentObj.attr('src', list.swapImage(src));
				
				}else{
					// process multi
					var arrStatus=[config.icon_unactive, config.icon_active]
					
					data.id.forEach( function(elem){
						
						var src = $( 'tr[id=' + elem + ']').find('img.status').attr('src');
						$( 'tr[id=' + elem + ']').find('img.status').attr('src', arrStatus[data.status]);
						
					} );
					common.dialog('close');
				}

				list.toggleCheckAll(false);
			break;
			
			case 'delete':
				
				if(data.key == 'single'){
					$( 'tr[id=' + data.id + ']' ).remove();
				}else{
				
					data.id.forEach( function(elem){
						$( 'tr[id=' + elem + ']' ).remove();
					} );
				}
				common.dialog('close');
				list.toggleCheckAll(false);
				
			break;

			case 'move':

				if(data.key == 'single'){
					$( 'tr[id=' + data.id + ']' ).remove();
				}else{
					data.id.forEach( function(elem){
						$( 'tr[id=' + elem + ']' ).remove();
					} );
				}
				common.dialog('close');
				list.toggleCheckAll(false);

				break;

			default:
			
			break
			
		}
		
	},
	
	toggleMultiVal: function(status){

		var mess = (status==1) ? 'enable' : 'disable';
		var callback_func = list.doUpdate;
	 	var listid = $('input[type="checkbox"]:checked:not(.chkCheckall)').map(function(i,n) {
	        return $(n).val();
	    }).get(); //get converts it to an array

		common.dialog(callback_func, {action:'toggle-status', key: 'multi', id: listid, status:status}, 
					  'Make ' + mess + ' selected rows ?', 
					  "All selected rows will " + mess + "!");
		
	},
	
	toggleVal: function(id,e){
		
		this.currentObj = $(e.target);
		var data = {action:'toggle-status', key: 'single', id: id};
		list.doUpdate(data);
		
	},
	
	 
	// do delete, update
	doUpdate: function(data){
		
		$.ajax({
			url: list.url + '/' + data.action,
			data: data,
			
		}).done(function(resp) {
			
			list.updateHTML(data);
			
			//change icon active by ajax
//			var idold = JSON.parse(resp);
//			if(typeof(idold.IdOldAnswer) != "undefined" && idold.IdOldAnswer !== null && idold.IdOldAnswer > 0) {
//				$( 'tr[id=' + idold.IdOldAnswer + ']').find('img.acanswer').attr('src', config.icon_unactive);
//			}
		})
	},
	
	show: function(){
		console.log(this.url);
		$.ajax({
			url: this.url + '/list',
			context: $('#container'),
			data: this.data,
			dataType: "json",
			
		}).done(function(json ) {
			console.log(json);
			//json
			$(this).html(json[0]);
			$('div[id=spnPaging]').each(function(){
				$(this).html(json[1])
			})
			$('#spnTotalRows').html(json[2]);
			
		});		
		
	},
	
	deleteMultiRow: function(){
		
		var callback_func = list.doUpdate;
	 	var listid = $('input[name="id"]:checked:not(.chkCheckall)').map(function(i,n) {
	        return $(n).val();
	    }).get(); //get converts it to an array
    
		common.dialog(callback_func, {action:'delete', key: 'multi', id: listid}, 
					  'Delete selected rows ?', 
					  "All selected rows would be deleted, This action can not undo!");
					  
	},
	
	deleteSingleRow: function(id){
		
		var callback_func = list.doUpdate;
		common.dialog(callback_func, {action:'delete', key: 'single', id: id}, 
					  'Delete one row ?', 
					  "This action can not undo!");
					  
	},
	
	pagingLimitToCookie: function(obj){
		
		$.cookie(pagingCookie, obj.val());
	},
	
	makePaging: function(obj){
		
		// makePerPage
		this.data ['limit'] = obj.val();
		var currentLimit = $.cookie(pagingCookie);
		
		if( common.isset(currentLimit) ){
			this.data ['limit'] = currentLimit;
		}

		this.data ['limit'] = currentLimit;
		this.data ['page'] = this.page;
		
		
	},
	
	makeSearch: function(){
		
		// make search 
		ajaction = $('#ajaction').val();
		from = $('#from').val();
		to = $('#to').val();
		selectFiter = $('#selectFiter').val();
		this.data ['from'] = from;
		this.data ['to'] = to;
		this.data ['selectFiter'] = selectFiter;
		this.data ['ajaction'] = ajaction;
		
		field = $('#listField').val();
		keyword = $('#txtKeyword').val();
		this.data ['field'] = field;
		this.data ['keyword'] = keyword;		

	},

	sort: function(sort_field, sort_type){
		this.data['sort_field'] = sort_field;
		this.data['sort_type'] = sort_type;
		this.show();
	},

	doUpdateSort: function(){
		
		var objValue=[];
		var tr = $("#container").children("tr");
		tr.each(function(){
			var objs={
			        "id" : $(this).attr("id"),
			        "value" : $(this).children("td").find("input[rel='allowSort']").val()
			    }   

			objValue.push(objs);
		})
		data = {param:objValue} ;
		data.action = 'update-sort';
		list.doUpdate(data);
	},
	
	makeFilterText: function(obj){

		var filter_value = obj.val();
		
		var key = obj.attr('id');
		
		this.data.filter_field[ key ]=obj.attr('id');
		
		this.data.filter_value[ key ]=filter_value;
		
	},
	
	makeFilterDate: function(obj){

		var filter_value = obj.val();
		if( filter_value !='all' ){
			
			this.data['filter_field_date'] = obj.attr('id');
			this.data['filter_value_date'] = filter_value;
		}
		
	},
	
	loadCurrent: function(objName){
		
			
		switch (objName){
			
			case 'select[id=cboLimit]':
			
				$('select[id=cboLimit]').each(function(){
					var currentLimit = $.cookie(pagingCookie);
					common.loadCurrent( $(this), currentLimit );
				})
			
			
			break;
			
			default :
			
			break;
			
		}
	},
	
	toggleCheckAll: function(key){
		if (!common.isset(key)){
			$(".chkRow").prop("checked", $('.chkCheckall').is(':checked'));
			$(".chkMove").prop("checked", $('.chkCheckallMove').is(':checked'));

		}else{
			$(".chkRow").prop("checked", key);
			$(".chkMove").prop("checked", key);
		}
	},

	
	bindEvent: function(){
			// $('select[rel=listFilter]').each(function(){
			// 	$(this).change(function(){
			// 		list.makeFilterText($(this));
			// 		list.show();
					
			// 	})			
			// });
			
			$('#btnSearch').click(function(){
				list.makeSearch();
				list.show();
			});


			$('select[id=cboLimit]').each(function(){
				$(this).change(function(){
					$('select[id=cboLimit]').not(this).val($(this).val());
					list.pagingLimitToCookie( $(this) );
					list.makePaging( $(this) );
					list.show();
				})	
			});
			
			// add multiple Check / Uncheck functionality
		    $(".chkCheckall").click(function () {
		          list.toggleCheckAll();
		    });

			$(".chkCheckallMove").click(function () {
				list.toggleCheckAll();
			});
		    
		    $("#bUpdateSort").click(function(){
				list.doUpdateSort();
			});



			
	},
	
	loadDatePicker: function(){
			
		$( "input.textbox_calendar" ).each(function()
		{
			common.loadDatePicker ($ (this));
			
			$(this).change (function()
			{
				list.makeFilterDate($(this));
				list.show();
			})
		})
	},

	moveListSchool: function(id,type){

		var callback_func = list.doUpdate;
		var message = '';
		if(type == 'list-from-deal'){
			message = "Do you move this school to  <b>League 2 ?</b>";
		}else if(type=='sub-school'){
			message = "Do you move this school to  <b>List School ?</b>";
		}
		common.dialog(callback_func, {action:'move', key: 'single', id: id},
			'Please confirm!',
			message);

	},

	moveMultiListSchool: function(type){

		var callback_func = list.doUpdate;
		var listid = new Array;
		$('[name="move[]"]').each( function (){
			if($(this).prop('checked') == true){
				listid.push($(this).val());
			}
		});

		var message = '';
		if(type=='list-from-deal'){
			message = "Do you move these schools to  <b>League 2 ?</b>";
		}else if(type=='sub-school'){
			message = "Do you move these schools to  <b>List School ?</b>";
		}

		common.dialog(callback_func, {action:'move', key: 'multi', id: listid},
			'Please confirm!',
			message);

	},


}

var requestPage = function(page){
	list.page = page;
	list.makeSearch();
	list.makePaging( $('select[id=cboLimit]').first() );
	list.show();
}