(function($){
	$(document).ready(function(){
		
		/** Study **/
		var sStudy = '';
		for (i in arrStudy)
		{
			sStudy += '<p>' + ( parseInt(arrStudy[i].parent_id) ? '&nbsp; &nbsp; -- ' : '+ ') + arrStudy[i].title + '</p>';
		}
		$('#message-element').after('<dt><label>List Study Request</label></dt><dd><br/>' + sStudy + '</dd>');
		
		/** Zone **/
		var sZone = '';
		for (i in arrZone)
		{
			sZone += '<p>' + arrZone[i].name + '</p>';
		}
		$('#message-element').after('<dt><label>List Zone Request</label></dt><dd><br/>' + sZone + '</dd>');
	});
})(jQuery);