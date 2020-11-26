
var elmManager = {

	params: {},
	elmType: 'textbox',
	contentType:'',
	contentId:0,
	action:'',
/*	width:0,
	height:0,*/
	value:0,
	html:'',
	
	loadHTML: function(value)
	{
		this.value = value;
		switch(this.elmType)
		{
			case 'textarea':
				this.textarea();
			break;
			
			case 'editor':
				this.editor();
			break;
			
			case 'listbox':
				this.listbox();
			break;
			
			default:
				this.textbox();
			break;
		}
		return this.html;	
	},

	textbox: function()
	{
		this.html = '<input type="text" id="' + this.contentType + '" ' +
					' style="width:' + this.params.width + 'px; height:' + this.params.height + 'px" ' +
					' value="' + this.value + '" />';
	},
	
	textarea: function()
	{
		this.html = '<textarea  id="' + this.contentType + '" ' +
					' style="width:' + this.params.width + 'px;height:' + this.params.height + 'px" >'+
					this.value + '</textarea>';
	},	
	
	editor: function()
	{
		this.html = '<textarea class="' + this.params.editor + '" id="' + this.contentType + '" ' +
					' style="width:' + this.params.width + 'px;height:' + this.params.height + 'px" >'+
					this.value + '</textarea><script>update.loadCKEditor($("textarea#' + this.contentType + '.' + this.params.editor + '"));</script>';
	},	
};
