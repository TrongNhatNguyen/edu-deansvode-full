var vote =
    {	
		manageForm: function()
		{
			// $('#btnAdd').click(function () {
			//   $(this).toggleClass('pull-left');
			//   $('#form-addschool').animate({height: 'toggle'}, 0)
			// });
			// $('#form-addschool .btn-cancel').click(function () {
			//   $('#btnAdd').toggleClass('pull-left');
			//   $('#form-addschool').animate({height: 'toggle'}, 0)
			// });

			$('#school_name').keyup(function (event) {
				event.preventDefault();
				if (event.keyCode === 13) {
                    $('#btnDoAddschool').click();
				}
			});
			
			$('#btnDoAddschool').click(function() {
				vote.addSchool();
			});
			
		},
		
		deleteSchool: function(id)
		{
			/** Xử lý phần xóa sub school được thêm vào bởi dean */
			
			$.ajax({
				url: '/vote/delete-sub-school',
				type: 'POST',
				cache: false,
				data: {id: id},
				success: function (string) {
					$('#new-subschool-' + id).remove();
					$('#blockSchool_' + id).remove();
				},
				error: function () {
				}
			});
		},		
 
		addSchool: function()
		{
            /** Xử lý phần thêm sub school */

			var school_name = $.trim( $('#school_name').val() );
			var website = $.trim( $('#website').val() );
			$('#school_name').css('border', '');
			if (school_name == '' )
			{
				$('#school_name').css('border', '1px solid red');
				return;
			}
			
			$.ajax({
				url: '/vote/add-sub-school',
				type: 'POST',
				cache: false,
				data: {countryID: iCountryID, school_name: school_name, website: website},
				success: function (string) {
					var getData = $.parseJSON(string);
					
					// $('#showschool').append(getData.html_school);
					// $('#school_name').val('');
					// $('#website').val('');
					// $('span.sp_delete[id=' + getData.school_id + ']').click(function(){
					// 	vote.deleteSchool(getData.school_id );
					// });
					
					/* New code 06/03/2018 */
					// console.log(getData);
					$('#form-addschool').append($.parseHTML(getData.html_subschool));
					$('#showschool-2018').append($.parseHTML(getData.html_subschool_option));
					$('#school_name').val('');
					$('button.bt-remove[id=' + getData.school_id + ']').click(function(){
						vote.deleteSchool(getData.school_id );
					});
				},
				error: function () {
				}
			});
		},
		
        setLinkHeader: function (path) {
            if (path != '') {
                $('#pdf-prev').attr('href',path).attr('target','_blank');
            }
			else
			{
				$('#pdf-prev').removeAttr('href');
			}
        },
        
		showRules: function () {
             $("#rules_of").click(function(){
				  $( ".ul-text" ).toggle();
			  });
        },
        
		doCopyVote: function (url) {
            if (url == 'disabled') return false;
            $('#popup-noti').find('#content-msg').html("You are going to report your " + preYearName + " recommendations for this year session. Can you confirm?");
            $('#popup-noti').find('.fancybox_confirm').show();
            $.fancybox( { href : '#popup-noti',
                beforeShow : function() {
                    jQuery(".fancyconfirm_cancel").click(function() {
                        $.fancybox.close();
                    });
                    jQuery(".fancyConfirm_ok").click(function() {
                        $.fancybox.close();
                        $("#loadingCopy").show();
                        $.get(url, function (resp) {
                            window.location = window.location.href;
                        });
                    });
                },
                afterClose  : function () {
                    $('#popup-noti').find('.fancybox_confirm').hide();
                }
            });
        },

        checkQuickProcess: function (copyAction) {
            if (copyAction == 'disabled') {
                // Assign only text to remove link
                $("div.box_col_02 > span.col_l").hide();
            }
        },
		
		/** Reload  **/
		preloadPage: function (getData)
		{
			$('#main').html(getData.html_vote_page);
			$('#header').attr('class', getData.classHeader);
			$('#logo').attr('src', getData.logo);

			if ($('#showschool-2017').length)
			{
				$('#showschool-2017').html(getData.html_subschool_2017);
			}
            if ($('#showschool-2018').length)
            {
                $('#showschool-2018').html(getData.html_subschool_2018);
            }

            $('#showschool').html(getData.html_school);			
            $('#list_country').html(getData.list_country);
			loadScrollbar(getData.countryID)
			vote.setLinkHeader(pathOwnerLink);
			
			getData.buttonMode = parseInt(getData.buttonMode);
			if (getData.buttonMode == 0 || getData.buttonMode == 1)
			{
				/** Stay at Main List **/
				limitVote = getData.limit;
				$(".limit_school").html(limitVote);
				$(".total_school").html(limitVote);
				/** Count school vote again **/
				countChecked();
				vote.showRules();
				window.location='#topVote';
			}
			else
			{
				/** Stay at Sub List **/
				if (getData.countryID == 1)
				{
					$('#myModal_info').modal('show');
				}
				else
				{
					window.location='#topVote';
				}					
				vote.manageForm();	
				vote.controlNoteNoListSchool(getData.html_school);		
			}
			resetInformation(getData);		
		},
		
		onLoad: function ()
		{
			buttonMode = parseInt(buttonMode);
			buttonManager(buttonMode);
			vote.showRules();
			vote.manageForm();
			loadProgressBar(percent);
			loadScrollbar(iCountryID);
			vote.setLinkHeader(pathOwnerLink);
			$(".total_school").html(limitVote);			
			if (buttonMode != 2)
			{
				countChecked();
			}
			var sListSchool = $('#showschool').html();
			vote.controlNoteNoListSchool(sListSchool);
			$('#header').attr('class', classHeader);
			$('#logo').attr('src', logo);
		},
		
		controlCheckbox: function(self, n)
		{
			if (self.filter(":checked").length == n) 
			{
				self.not(":checked").bind('change', function(){
												exceedLimitChoise();
												$(this).prop( "checked", false );
												return false;
											});
			} 
			if (self.filter(":checked").length < n) 
			{
				self.unbind('change');
			}
			var numberChecked 	= $("input:checked[rel=vote_item]").length;
			var voteNumber		= limitVote - numberChecked;
			voteNumber			= (voteNumber < 0 ) ? 0 : voteNumber;
			$(".limit_school").text(voteNumber);			
		},
        
		controlNoteNoListSchool: function(sListSchool)
		{
			$('#noteHasList').show();
			$('#noteNoList').hide();
			if ($.trim (sListSchool) == '')
			{
				$('#noteHasList').hide();
				$('#noteNoList').show();
			}
		},

		// Preserve school by dean suggestion
		preserveSchool: function(schoolName, countryId)
		{
			$.ajax({
				url: '/vote/preserve-school',
				type: 'post',
				dataType: 'json',
				data: {schoolName: schoolName, countryId: countryId}
			});
		},
	}

var buttonManager = function (buttonMode) 
{
	buttonMode = parseInt(buttonMode)
	switch (buttonMode)
	{
		case 3:
			// finish with sub list
			$('#bFinish').show();
			$('#finish').attr('rel', 'finishSubList');
			$('span[id=bNext]').hide();
			$('span[id=bLater]').hide();
			$('#no_recmd').attr('rel', 'finishWithNoRecommendSubList');
			$('#no_recmd').attr('finish', 'true');
		break;
		
		case 1:
			// finish
			$('#bFinish').show();
			$('span[id=bNext]').hide();
			$('span[id=bLater]').hide();
			$('#no_recmd').attr('rel', 'finishWithNoRecommend');
			$('#no_recmd').attr('finish', 'true');
		break;
		
		case 2:
			// vote sub list
			$('#next').attr('rel', 'doVoteSubList');
			$('#no_recmd').attr('rel', 'noRecommendSubList');
			$('span[id=bFinish]').hide();
			$('span[id=bLater]').show();
		break;
		
		default:
			// vote main list
			$('#next').attr('rel', 'doVote');
			$('span[id=bFinish]').hide();
			$('span[id=bLater]').show();
			$('#no_recmd').attr('rel', 'noRecommend');
		break;
	}
}

var resetInformation = function (getData) 
{console.log(getData);
    $('.ctrname').html(getData.name);
    $('#flags-img img').attr('src', getData.flags);
    $('.prog_text').html(getData.progress);
    $('#countryComment').val(getData.comment);
    $("#progressbar").progressbar({
        value: parseFloat(getData.progress)
    })
	iCountryID = getData.countryID;
    buttonManager(getData.buttonMode);
	$('#loading').hide();	
	$('#loading_top').hide();	  
}

var countChecked = function () 
{
	var self = $("input:checkbox[rel=vote_item]");
	self.each(function(){
		$(this).click(function()
		{
			vote.controlCheckbox( self, limitVote);
		})
	})
	vote.controlCheckbox( self, limitVote);
    
}

var requireChoise = function () {
    showPopup("If you don't recommend any of the selected schools or comment, please click on << NO, I don't have any recommendations..... >>");
}

var exceedLimitChoise = function () {
	window.location = '#limitInfo';
    showPopup("You have exceeded the number of schools you can select");
}

var continueLater = function () {
    showPopup("Your Dean's voting session is saved.",function () {
        document.location = "/index/logout";
    });
}

var strURL = '/vote/index/makevote/1';

$("#no_recmd").click(function () {
    isFirstVote = '0';
	var action  = $(this).attr('rel');
	var isFinish  = $(this).attr('finish');
    $('#loading_top').show();
	var sComment = $('#countryComment').val();
	var deanRecommendSubSchool = '';

	/** Preserve school that suggested by dean even though he didn't click Add button */
	if (action == "noRecommendSubList") {
		deanRecommendSubSchool += $('.item-suggest #school_name').val();
		if (deanRecommendSubSchool.length > 0) {
			vote.preserveSchool(deanRecommendSubSchool, iCountryID);
		}
	}

    $.ajax({
        url: strURL,
        type: 'POST',
        cache: false,
        data: {actionKey: action, countryID: iCountryID, finish: isFinish, comment: sComment, deanRecommendSubSchool: deanRecommendSubSchool},
        success: function (string) 
		{
			if (isFinish == 'true')
			{
				window.location = "/finish"
				return;
			}
			var getData = $.parseJSON(string);
			/** Update new information **/
			vote.preloadPage(getData);
        },
        error: function () {
        }
    });
});

$("#next").click(function () {
    console.log($(this).attr('rel'));
    if (!$('input[rel=vote_item]').is(':checked') && $(this).attr('rel') === 'doVote') {
		if ($('#countryComment').val().length == 0) {
			requireChoise();
        	return false;
		}
    }
	
    $('#loading').show();
    isFirstVote = '0';
	var action  = $(this).attr('rel');
    var sComment = $('#countryComment').val();
	var selected = new Array();
	var deanRecommendSubSchool = '';
	
    $('input:checkbox:checked').each(function () {
        selected.push($(this).val());
	});
	
	/** Preserve school that suggested by dean even though he didn't click Add button */
	if (action == "doVoteSubList") {
		deanRecommendSubSchool += $('.item-suggest #school_name').val();
		if (deanRecommendSubSchool.length > 0) {
			vote.preserveSchool(deanRecommendSubSchool, iCountryID);
		}
	}

    $.ajax({
        url: strURL,
        type: 'POST',
        cache: false,
        data: {actionKey: action, checkedschool: selected, countryID: iCountryID, finish: 'false', comment: sComment, deanRecommendSubSchool: deanRecommendSubSchool},
        success: function (string) {
            var getData = $.parseJSON(string);
            /** Update new information **/
            vote.preloadPage(getData);
        },
        error: function () {
        }
    });
});

$("#back").click(function () {

    if (isFirstVote == '1') {
        requireChoise();
        return false;
    }

    $('#loading').css('display', 'block');
    var selected = new Array();
    $('input:checkbox:checked').each(function () {
        selected.push($(this).val());
    });
    $.ajax({
        url: strURL,
        type: 'POST',
        cache: false,
        data: {actionKey: 'back'},
        success: function (string) {
            var getData = $.parseJSON(string);
            /** Update new information **/
            vote.preloadPage(getData);
        },
        error: function () {
        }
    });
});

$("#later").click(function () {
    continueLater();
})

$("#finish").click(function () {

    var _list_school = $("input[name='school[]']");
    if(_list_school.length == 0)
    {
        $("#finish_no_recmd").click();
        return false;
    }


    if (!$('input[rel=vote_item]').is(':checked')) {
        requireChoise();
        return false;
    }

    $('#loading').css('display', 'block');
    // $('#finish').prop('disabled', true);
    var selected = new Array();
    $('input:checkbox:checked').each(function () {
        selected.push($(this).val());
    });
	var action  = $(this).attr('rel');
    var sComment = $('#countryComment').val();
    $.ajax({
        url: strURL,
        type: 'POST',
        cache: false,
        data: {actionKey: action, checkedschool: selected, countryID: iCountryID, finish: 'true', comment: sComment},
        success: function (string) {
            /*var getData = '';
            try
            {
                getData = $.parseJSON(string);
            }
            catch(e){}*/
            // $('#finish').prop('disabled', false);
            $('#loading').css('display', 'none');
            window.location = "/finish"
        },
        error: function () {
        }
    });
});

var loadProgressBar = function (percent) {
    $("#progressbar").progressbar({
        value: parseFloat(percent)
    });
};

var removeScrollMobile = function() {
  if($(window).width() < 990) {
	$('.scroll-pane').addClass('no-scroll-pane').removeClass('scroll-pane');
  }
}
        
var loadScrollbar = function (currentID) 
{
    $(".scroll-pane").mCustomScrollbar({ theme:"rounded-dots"});
	$('.scroll-pane').mCustomScrollbar('scrollTo','#cid_' + currentID);
	removeScrollMobile();
}

