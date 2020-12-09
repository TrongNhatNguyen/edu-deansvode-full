var update ={
	
	url				: '{{ path("admin_area") }}',
	currentObj 		: null,
	finder			: null,
	fileItemHTML	: '',
	bAllowTitle		: 0,
	updateHTMLFunc	: null,
	fileMode		: 'multi',
	sTitleField		: '',
	
	bindEvent: function()
	{
		this.loadMultiCKEditor();
		this.processAlias();
	},
	
	processAlias: function()
	{
		$(".to-alias").keyup(function(){
			var content = $(this).val();
			if (content != ''){
				$('.input-alias').val (common.str2url (content));	
			}
		})
	},
	
	initFileUploader: function(){
		this.mangerFile();
	},
	
	/** Only run when click **/
	startFinder: function(fileOption)
	{
		if (common.isNotSet (fileOption.sFilePath)) fileOption.sFilePath = '';
		if (common.isNotSet (update.updateHTMLFunc)) update.updateHTMLFunc = update.PD_SetFileField;
		if (common.isNotSet (this.finder))
		{
			this.finder			= new CKFinder();
		}	
		this.finder.startupPath = fileOption.sFinderType + ':/' + fileOption.sFilePath + '/';
		this.finder.selectActionFunction = update.updateHTMLFunc;
		this.finder.selectActionData = 'xImagePath';
		this.finder.selectThumbnailActionFunction = ShowThumbnails;
		this.finder.popup();		
	},
	
	PD_SetFileField : function ( fileUrl, data, allFiles )
	{
		var sDiv = update.fileItemHTML;
		for( i=0; i < allFiles.length; i++ )
		{
			fileUrl = allFiles[i].url;
			sDiv = sDiv.replace(/{PATH}/gi, fileUrl);
			if (update.bAllowTitle == '0'){
				update.sTitleField = '';
			}	
			sDiv = sDiv.replace(/{TITLE_FIELD}/gi, update.sTitleField);
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
		
	},
	
	// ckfinder cho video
	loadFileUploader: function()
	{		
		$('.file-uploader').parent().append('&nbsp<input type="button" class="fc-load-finder" value="Browse...">');

		$('.fc-load-finder').click(function(){
			var finder = new CKFinder();
			var path = "Files:/video/";
			
			if (! common.isNotSet($('.file-uploader').attr('rel_path')) )
				path = $('.file-uploader').attr('rel_path');				

			finder.startupPath = path;
			finder.selectActionData = 'xFilePath';
			finder.selectActionFunction = function(fileUrl){
				update._fillValFileUploader(fileUrl);
			}
			finder.popup();	
		});

		$('.file-uploader').attr('type','hidden');
		
		if ( $('.thumbList').length == 0 )
		{
			$('.file-uploader').parent().append('<ul class="sw-videoList"></ul>');
		}
		
		if ( common.isset($('.file-uploader').val()) )
			this._fillValFileUploader($('.file-uploader').val());
	},
	
	_fillValFileUploader: function(sFileUrl)
	{
		fileName = sFileUrl.split('/').pop();
		$('.file-uploader').val(sFileUrl);
		$('.sw-videoList').html('<li><a href="'+sFileUrl+'" target="_blank">'+fileName+'</a><a title="Remove" class="remove" href="javascript:void(0)">&nbsp;</a></li>');
		$('.sw-videoList a.remove').click(function(){
			$(this).parent().remove();
			$('.file-uploader').val('');
		});
	},
		
	mangerFile: function()
	{
		if (update.fileMode != 'multi')
		{
			$('.showCoverOption').css('display', 'none');
		}	
		$('a.remove').hide();
		$('a.remove').each(function(){
			var obj = $(this);	
			obj.click(function()
			{
				obj.parent().hide('slow', function(){ obj.parent().remove(); });				
			})
		})
		
		$('a.preview').each(function(){
			$(this).click(function(){
				var imagePreview = '<img src="' + $('img',this).attr('src') + '">';
				common.dialog (null, {}, 'Preview', imagePreview, 'popup');
			})
		})
		
		$('li.thumb').each(function()
		{
			$(this).hover(function()
			{
				$('a.remove', this).show();
			    $('a.preview', this).stop().animate({"opacity": 0.7});
			},function()
			{
				$('a.remove', this).hide();
			    $('a.preview', this).stop().animate({"opacity": 1});
			});
		})
			
	},
	
	loadCKEditor: function(obj){

		if (obj.length > 0)
		{
			var id = obj.attr('id');
			var iHeight = obj.height();
			var iWidth = obj.width();
			var editor = CKEDITOR.instances[id];
		    if (editor) { editor.destroy(true); }
			CKEDITOR.replace(id, { height: iHeight,  width:iWidth, toolbar: obj.attr('class') });
		}		
	},	
	
	loadMultiCKEditor: function(){

		//var editorSetting = {all:'All', full:'Full', basic:'Basic', simple:'Simple'}

		$("dl.zend_form" ).append("<div class='clear'></div>");
		
		if ($(".editor").length > 0){
			
			$(".editor").each(function(){
				if ($(this).hasClass('All')){
					
					CKEDITOR.replace(this, { height: 160,  width:715, toolbar: 'All' });
					
				}else if ($(this).hasClass('Full')){
					
					CKEDITOR.replace(this, { height: 250 , toolbar: 'Full' });					
					
				}else if ($(this).hasClass('Basic')){
					
					CKEDITOR.replace(this, { height: 160, width:715, toolbar: 'Basic' });
					
				}else if ($(this).hasClass('Simple')){
					
					CKEDITOR.replace(this, { height: 160, width:715, toolbar: 'Simple' });					
					
				}
			});
		}		

	},
	
}