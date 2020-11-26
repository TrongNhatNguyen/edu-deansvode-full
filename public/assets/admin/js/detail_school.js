
var detail_school = {

	url_context	: '/admin/marketing',
	url_common	: '/ranking/common',
	tabName		: 'curTabe',
	id			: 0,

	loadTabs: function()
	{
		var curTab = $.cookie(detail_school.tabName);
		curTab	   = isNaN(curTab) ? 0 : curTab;
		$( "#tabs" ).tabs({
			activate: function( event, ui )
			{
				var iTab = $(this).tabs("option", "active");
				$.cookie(detail_school.tabName, iTab);
			},
			active: curTab
		});
	},

	loadPartnerhips: function()
	{
		$('input[name=partnerType]').each(function(){
			$(this).bind('click', function()
			{
				var sURL = detail_school.url_context + '/partner-' + $(this).attr('rel');
				$.ajax({url : sURL, data: {school_id:detail_school.id}})
					.done (function (resp)
					{
						$('#Partnerships').html(resp);
					})



			})
		})
	},

	loadUploader: function()
	{
		$('div.icon_edit_image').each(function(){
			$(this).click(function()
			{
				update.updateHTMLFunc = detail_school.showSchoolLogo;
				update.startFinder({ sFilePath: 'school/logo', sFinderType: 'image' } );
			});
		});
	},

	showSchoolLogo: function( fileUrl, data, allFiles)
	{
		$.ajax(
			{
				url : detail_school.url_context + '/change-logo',
				data: {school_id:detail_school.id, logo_path: fileUrl},
			}
		).done(function(resp)
		{
			$('#showSchoolLogo').attr('src', fileUrl);
		})
	},

	loadBodyOtherProgram: function()
	{
		$('ul.OtherProgram>li').each(function()
		{
			var obj      = $(this);
			var id  	 = obj.attr('id');
			obj.children('span.title').click(function()
			{
				$('div.body', obj).toggle('fast', function()
				{
					$('div.body').not(this).hide('fast');
					if ($(this).is(':empty') )
						$(this).load(detail_school.url_context + '/detail-sub-program/id/' + id);
				});
			});
		});
	},

	initialize: function()
	{
		detail_school.loadBodyOtherProgram();
		detail_school.loadTabs();
		/** For logo school **/
		detail_school.loadUploader();
		common.fixBrokenImage();

	},

	changeCountry: function(action)
	{
		return detail_school.changeBasicInfo(action, 'load-country', 'change-country', 'listCountry');
	},

	changeSubZone: function(action)
	{
		return detail_school.changeBasicInfo(action, 'load-sub-zone', 'change-subzone', 'listSubZone');
	},

	changePalm: function(action)
	{
		return detail_school.changeBasicInfo(action, 'load-palm', 'change-palm', 'listPalm');
	},

	changeBasicInfo: function(action, showContentAction, saveContentAction, contentId )
	{
		var data = {};
		switch (action)
		{
			case 'edit':
				data.serverUrl = detail_school.url_common + '/' + showContentAction;
				data.updateHTML = function(resp)
				{
					$('#' + elmManager.contentType + elmManager.contentId).html(resp);
				}
				break