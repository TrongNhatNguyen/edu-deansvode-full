(function($){
	
	var AssoZone = {
			arrValue: [],
			
			init: function()
			{
				data = $('.association-zone').attr('rel-data');
				
				if (common.isNotSet(data))
					return;
				
				data = $.parseJSON(data);
				
				for (i in data)
				{
					this.appendZone(data[i].id, data[i].name);
					this.arrValue.push(data[i].id);
				}
				
				this.blindRemove();
			},

			add: function()
			{
				val = $('.association-zone').val();
				
				if ( this.arrValue.indexOf(val) !== -1 || val == '' )
					return;
				
				this.arrValue.push(val);
				
				this.appendZone(val, $('.association-zone option:selected').text());
				
				this.blindRemove();
			},
			
			
			blindRemove: function()
			{
				$('.remove').off('click').bind('click',function(){
					$(this).parent().remove();
					//xoa phan tu trong mang
					i = AssoZone.arrValue.indexOf($(this).parent().find('input[name="subzone_id[]"]').val());
					delete AssoZone.arrValue[i];
				});
			},
			
			appendZone: function(val, text)
			{
				$('.asso-zone-list').append(
					'<li><input type="hidden" name="subzone_id[]" value="'+ val +'"/>' +
						'<span>' + text + '</span>' +
						'<a href="javascript:void(0)" class="remove" title="Remove">&nbsp</a>' +
					'</li>'
				);
			}
	};
	
	$(document).ready(function(){
		$('.association-zone').parent().append('&nbsp<input type="button" class="add-asso-zone" value="Add">');
		$('.association-zone').parent().append('<ul class="asso-zone-list"></ul>');
		
		$('.add-asso-zone').click(function(){
			AssoZone.add();
		});
		
		AssoZone.init();
	});
	
})(jQuery);