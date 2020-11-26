$.fn.extend(
	{
		edit: function(func)
		{
			var data = {};
			var icon = $(this);
			if ($.isFunction(func))
			{
				data = func.call(this, elmManager.action);
			}
			/** Check ajax or not
			 Not ajax case **/
			if (common.isNotSet (data.serverUrl))
			{
				if ($.isFunction (data.updateHTML))
				{
					data.updateHTML.call(this);
				}
				else
				{
					var obj = $('#' + elmManager.contentType + elmManager.contentId);
					obj.html (elmManager.loadHTML (obj.html()));
				}
				icon.toSave();
			}
			else
			{
				var params = {};
				$.each(data, function(i, item)
				{
					if (!$.isFunction(item)) params[i]= item;
				});
				/** Always keep content_id for all cases **/
				params.content_id = elmManager.contentId;

				$.ajax ({ url: data.serverUrl, data: params, type: 'GET'})
					.done (function (resp)
					{
						if ($.isFunction (data.updateHTML))
						{
							data.updateHTML.call(this, resp);
						}
						else
						{
							// Adding form
							icon.before (resp);
						}
						icon.toSave();
					})
			}
		},

		save: function(func)
		{
			var data = {};
			var params = {};
			var icon = $(this);

			if ($.isFunction(func))
			{
				data = func.call(this, elmManager.action);
				$.each(data, function(i, item)
				{
					if (!$.isFunction(item)) params[i]= item;
				});
			}
			else
			{
				data.serverUrl 		= pageURL + '/change-basic-info';
				params.value		= this.getValue();
				params.content_type	= elmManager.contentType;
			}
			/** Always keep content_id for all cases **/
			params.content_id = elmManager.contentId;

			$.ajax ({ url: data.serverUrl, data: params, dataType: 'JSON'})
				.done (function (resp)
				{
					if ($.isFunction (data.updateHTML))
					{
						data.updateHTML.call(this, resp);
					}
					else
					{
						var obj = $('#' + elmManager.contentType + elmManager.contentId);
						obj.html (resp.content);
					}
					icon.toEdit();
				})
		},

		removeItem: function(func)
		{
			var params = {};
			var icon = $(this);
			var data = func.call(this, elmManager.action);
			$.each(data, function(i, item)
			{
				if (!$.isFunction(item)) params[i]= item;
			});
			params.content_id = elmManager.contentId;
			$.ajax ({ url: data.serverUrl, data: params})
				.done (function (resp)
				{
					if ($.isFunction (data.updateHTML))
					{
						data.updateHTML.call(this, resp);
					}
				})
		},

		prepare: function()
		{
			var width 	   = $(this).attr('elm_width');
			var height 	   = $(this).attr('elm_height');

			if (common.isNotSet (width))  width = 120;
			if (common.isNotSet (height)) height = 18;

			elmManager.params.width  = width;
			elmManager.params.height = height;
			elmManager.params.editor = $(this).attr('editor');
			elmManager.elmType     	 = $(this).attr('elm_type');
			elmManager.contentType 	 = $(this).attr('content_type');
			elmManager.contentId   	 = $(this).attr('content_id');
			elmManager.action 	   	 = $(this).attr('action');
		},

		bindClick: function(objId, objFunc)
		{
			$(objId, this).each (function()
			{
				var obj = $(this);
				obj.click (function()
				{
					obj.prepare();
					var callbackFunc = objFunc ['change' + elmManager.contentType];
					obj [elmManager.action] (callbackFunc);
				});
			});
		},

		getValue: function()
		{
			var elmType = this.attr('elm_type');
			var value   = '';
			if (elmType == 'editor')
			{
				value = CKEDITOR.instances[elmManager.contentType].getData();
			}
			else
			{
				value = $('#' + elmManager.contentType ).val();
			}

			return value;
		},

		toSave: function()
		{
			/* keep setting before changing */
			this.attr('pre-action', this.attr ('action'));
			this.attr('pre-class', this.attr ('class'));
			/* Change icon */
			this.attr('class', 'icon_save');
			this.attr('action', 'save');
			/** Hide other handler **/
			this.prevAll('span[elm_type]').toggle();
			this.nextAll('span[elm_type]').toggle();
			/** Put id to form **/
			this.prev('form').attr('id', elmManager.contentType);
		},

		toEdit: function()
		{
			/* Change icon */
			this.attr('class', this.attr('pre-class'));
			this.attr('action', this.attr('pre-action'));
			/* Removing form */
			this.prevAll('form,br,input').remove();
			/* Remove temp attribute */
			this.removeAttr('pre-action');
			this.removeAttr('pre-class');
			/** Hide other handler **/
			this.prevAll('span[elm_type]').toggle();
			this.nextAll('span[elm_type]').toggle();
		}
	})

