/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.skin = 'kama';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.language = 'en';
    config.scayt_autoStartup = false;
    config.removePlugins = 'scayt';
    config.disableNativeSpellChecker = true;
	config.filebrowserBrowseUrl = '';
	//config.resize = CKEDITOR.DIALOG_RESIZE_HEIGHT
	config.resize_dir = 'vertical'
    
    config.toolbar = 'Full';
	
	config.toolbar_All =
	[
    	['Source','-','Save','NewPage','Preview','-','Templates'],
	    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    	['BidiLtr', 'BidiRtl'],
	    '/',
    	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','Anchor'],
    	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	    '/',
    	['Styles','Format','Font','FontSize'],
	    ['TextColor','BGColor'],
    	['Maximize', 'ShowBlocks']
	];

    config.toolbar_Full =
    [
        ['Source','-','Preview','ShowBlocks','Templates','Cut','Copy','Paste','PasteText','PasteFromWord','Undo','Redo','Find','Replace','SelectAll','RemoveFormat','Link','Unlink','Anchor', 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Format','Font','FontSize','Bold','Italic','Underline','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', 'Maximize','TextColor','BGColor']
    ];
	
	config.toolbar_Basic =
	[
    	['Source','-','Bold','Italic','Underline','Strike','Subscript','Superscript','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','NumberedList','BulletedList','TextColor','BGColor','Link','Unlink']
	];
	
	config.toolbar_Simple =
	[
    	['Bold','Italic','Underline','Strike','Subscript','Superscript','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','NumberedList','BulletedList','TextColor','BGColor','Image']
	];
	
	//CKFINDER INCLUDE
	config.filebrowserBrowseUrl = '/static/default/js/ckfinder/ckfinder.html';
//	config.filebrowserImageBrowseUrl = '/static/admin/js/ckfinder/ckfinder.html?type=images';
	//config.filebrowserFlashBrowseUrl = '/static/admin/js/ckfinder/ckfinder.html?type=flash';
	config.filebrowserUploadUrl = '/static/default/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=files';
	config.filebrowserImageUploadUrl = '/static/default/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=images';
	//config.filebrowserFlashUploadUrl = '/static/admin/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=flash';
	
};