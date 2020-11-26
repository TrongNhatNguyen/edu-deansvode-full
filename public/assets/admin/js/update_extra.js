var UpdateExtra = 
{
	element : 'input.uploader',
	oFinder : {},
	queue : {},

	// ckfinder multi uploader
	loadUploader: function()
	{		
		$(this.element).each(function(){
			UpdateExtra.buildUploader($(this));
		});
		
		var obj = this;
		
		$('.fc-load-finder').click(function(){
			
			var id = $(this).attr('id');

			if (common.isNotSet(obj.oFinder[id]))
			{
				obj.oFinder[id] = new CKFinder(); 
				
				var path = $(this).closest('dd').find(obj.element).attr('rel_path');
				if (common.isNotSet(path) )
				{
					path = "images:/";
				}					
	 
				obj.oFinder[id].startupPath = path;
				obj.oFinder[id].selectActionData = 'xImagePath';
				obj.oFinder[id].selectActionFunction = function(fileUrl){
					obj.fillValUploader($('#'+id).closest('dd') ,fileUrl);
				}
			}
			
			obj.oFinder[id].popup();
		});
	},
	
	buildUploader: function($element)
	{
		$element.parent().append('&nbsp<input type="button" class="fc-load-finder" id="fcf'+ (new Date()).getTime() +'" value="Browse...">');
		
		$element.attr('type','hidden');
		
		if ( $('.thumbList').length == 0 )
		{
			$element.parent().append('<ul class="sw-ImageList"></ul>');
		}
		
		if ( common.isset($element.val()) )
			this.fillValUploader($element.closest('dd'), $element.val());
	},
	
	fillValUploader: function($odt, sFileUrl)
	{
		$odt.find(this.element).val(sFileUrl);
		$odt.find('.sw-ImageList').html('<li class="thumb"><a class="preview" href="javascript:void(0)" style="opacity: 1;"><img src="'+ sFileUrl +'"></a><a title="Remove" class="remove" href="javascript:void(0)">&nbsp;</a></li>');
		$odt.find('.sw-ImageList a.remove').click(function(){
			$(this).parent().remove();
			$(this.element).val('');
		});
	},
}

$(document).ready(function(){
	UpdateExtra.loadUploader();
});



var MultiImageVideo = {
	
	fileItemHTML : '',

	addVideo: function()
	{
		common.dialog(MultiImageVideo.process, {},'Add Video', "Youtube URL: <input id='youtubeURL' type='text'/>&nbsp;&nbsp;");
	},
	
	process: function()
	{
		var obj = MultiImageVideo;
		var idYoutube = obj.getYouTubeIdFromURL($('#youtubeURL').val());
		
		if (idYoutube == false)
		{
			alert('Youtube URL invalid!');
			return;
		}
		
		var content = obj.fileItemHTML;
		content = content.replace('src="{PATH}"', 'src="http://i3.ytimg.com/vi/' + idYoutube + '/mqdefault.jpg"');
		content = content.replace('name="file_cover" value="{PATH}"', 'name="file_cover" value="http://i3.ytimg.com/vi/' + idYoutube + '/sddefault.jpg"');
		content = content.replace('{TITLE_FIELD}', '<input type="hidden" name="isVideo[]" value="1"/>');
		content = content.replace(/{PATH}/gi, $('#youtubeURL').val());
		
		$('#image-list').append(content);
		common.dialog('close');
		
		/** Bind event again **/ 
		update.mangerFile();
		
		if (!$('input[name=file_cover]').is(':checked')){
			$('input[name=file_cover]').first().attr('checked', 'checked');
		}
	},
	
	getYouTubeIdFromURL: function(url){
	    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
	    var match = url.match(regExp);
	    if (match&&match[7].length==11){
	        return match[7];
	    }else{
	        return false;
	    }
	}
}


update.PD_SetFileField = function ( fileUrl, data, allFiles )
{
	for( i=0; i < allFiles.length; i++ )
	{
		var sDiv = update.fileItemHTML;
		fileUrl = allFiles[i].url;
		sDiv = sDiv.replace(/{PATH}/gi, fileUrl);
		if (update.bAllowTitle == '0'){
			update.sTitleField = '';
		}	
		sDiv = sDiv.replace(/{TITLE_FIELD}/gi, update.sTitleField + '<input type="hidden" name="isVideo[]" value="0"/>');
		sDiv = sDiv.replace(/{CHECKED}/gi, '');
		if (update.fileMode == 'multi')
		{
			$('#image-list').append(sDiv);		
		}else{
			$('#image-list').html(sDiv);		
		}
	}
	/** Bind event again **/ 
	update.mangerFile();
	
	if (!$('input[name=file_cover]').is(':checked')){
		$('input[name=file_cover]').first().attr('checked', 'checked');
	}
	
}