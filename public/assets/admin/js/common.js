// user-defined function
var common = {

    loadDatePicker: function (obj) {
        obj.datepicker({
            showOn: "button",
            buttonImage: "/static/admin/images/edit.png",
            buttonImageOnly: true,
            dateFormat: "dd/mm/yy",
            showAnim: "slide",
            changeMonth: true,
            changeYear: true
        });
    },

    isset: function (val) {
        return ( typeof val != 'undefined' && val != null && val != '' );
    },

    isNotSet: function (val) {
        return ( typeof val == 'undefined' || val == null || val == '' );
    },

    openProcess: function () {

        $('#loading').fadeIn('slow');
    },

    closeProcess: function () {

        $('#loading').fadeOut('slow');
    },

    dialog: function (callback_func, params, title, message, type) {

        /** Check type of dialog: confirm, popup,... **/
        if (common.isNotSet(type)) type = 'confirm';

        /** Close dialog in case **/
        if (callback_func == 'close') {
            $("#dialog").dialog("close");
            return;
        }

        /** Launch body for dialog **/
        $("#dialogMessage").html(message);
        switch (type) {


            case 'confirm':

                /** Launch dialog **/
                $("#dialog").dialog({
                    resizable: false,
                    width: 'auto',
                    dialogClass: "",
                    modal: true,
                    title: title,
                    buttons: {
                        "Confirm": function () {
                            callback_func.call(this, params)
                        },
                        Cancel: function () {
                            $(this).dialog("close")
                        }
                    }
                });
                break;

            case 'page':

                if (common.isNotSet(params.width)) params.width = '98%';

                /** Temporary, hide icon alert **/
                $('span.ui-icon-alert').hide();
                /** Launch dialog **/
                $('#dialogMessage').load(callback_func, params, function () {

                    $("#dialog").dialog({
                        resizable: false,
                        width: params.width,
                        modal: true,
                        title: title,
                        dialogClass: "top10",
                        buttons: {
                            Close: function () {
                                $(this).dialog("close")
                            }
                        },
                        create: function (event, ui) {
                            /** Remove title bar
                             $(".ui-dialog-titlebar").hide();**/
                        },
                    });

                });

                break;

            case 'popup':

                /** Temporary, hide icon alert **/
                $('span.ui-icon-alert').hide();

                /** Launch popup **/
                $("#dialog").dialog({
                    title: title,
                    modal: true,
                    dialogClass: "",
                    width: 'auto'


                });

                break;

            default:

                break;
        }
    },

    loadCurrent: function (obj, idContent) {

        obj.val(idContent).attr('selected', true);
    },

    fixBrokenImage: function () {
       /* $("img")
            .error(function () {
                //$(this).attr( "src", "/static/ranking/images/noimage.png" );
            })
        */
    },

    fixSchoolLogoImage: function(){
        $("#showSchoolLogo")
            .error(function () {
                $(this).attr( "src", "/static/admin/images/no_logo_school.png" );
            })
    },

    show_answer: function(id){
        $.ajax({
            type: "POST",
            url: "/admin/interview/ajax-show-answer",
            dataType: 'json',
            data: {
                id:id,
            },
            beforeSend: function() {
                $('.pp_content').html('');
            },
            success: function(data) {
                $('.pp_content').html(data.answer);
            }
        });
    },

    show_answer1: function(id){
        $.ajax({
            type: "POST",
            url: "/admin/interview-student/ajax-show-answer",
            dataType: 'json',
            data: {
                id:id,
            },
            beforeSend: function() {
                $('.pp_content').html('');
            },
            success: function(data) {
                $('.pp_content').html(data.answer);
            }
        });
    },

    show_comment: function(id){
        $.ajax({
            type: "POST",
            url: "/admin/comments/ajax-show-comments",
            dataType: 'json',
            data: {
                id:id,
            },
            beforeSend: function() {
                $('.pp_content').html('');
            },
            success: function(data) {
                $('.pp_content').html(data.comment);
            }
        });
    },

    ajaxSetup: function () {

        $.ajaxSetup({

            cache: false,

            dataType: 'html',

            type: "POST",

            error: function (jqXHR, textStatus, errorThrown) {

                console.log(textStatus, errorThrown);

            }

        });

        $(document).ajaxStart(function () {

            common.openProcess();

        });

        $(document).ajaxStop(function () {

            common.closeProcess();

        });

    },

    str2url: function (str, ucfirst) {

        if (common.isNotSet(ucfirst)) ucfirst = 0;
        str = str.toUpperCase();
        str = str.toLowerCase();


        /* PS_ALLOW_ACCENTED_CHARS_URL */
        str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]\\u00A1-\\uFFFF/g, '');
        /* Lowercase */
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        /* Lowercase */
        str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E4\u00E5\u0101\u0103\u0105\u0430]/g, 'a');
        str = str.replace(/[\u0431]/g, 'b');
        str = str.replace(/[\u00E7\u0107\u0109\u010D\u0446]/g, 'c');
        str = str.replace(/[\u010F\u0111\u0434]/g, 'd');
        str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B\u0435\u044D]/g, 'e');
        str = str.replace(/[\u0444]/g, 'f');
        str = str.replace(/[\u011F\u0121\u0123\u0433\u0491]/g, 'g');
        str = str.replace(/[\u0125\u0127]/g, 'h');
        str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131\u0438\u0456]/g, 'i');
        str = str.replace(/[\u0135\u0439]/g, 'j');
        str = str.replace(/[\u0137\u0138\u043A]/g, 'k');
        str = str.replace(/[\u013A\u013C\u013E\u0140\u0142\u043B]/g, 'l');
        str = str.replace(/[\u043C]/g, 'm');
        str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B\u043D]/g, 'n');
        str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F6\u00F8\u014D\u014F\u0151\u043E]/g, 'o');
        str = str.replace(/[\u043F]/g, 'p');
        str = str.replace(/[\u0155\u0157\u0159\u0440]/g, 'r');
        str = str.replace(/[\u015B\u015D\u015F\u0161\u0441]/g, 's');
        str = str.replace(/[\u00DF]/g, 'ss');
        str = str.replace(/[\u0163\u0165\u0167\u0442]/g, 't');
        str = str.replace(/[\u00F9\u00FA\u00FB\u00FC\u0169\u016B\u016D\u016F\u0171\u0173\u0443]/g, 'u');
        str = str.replace(/[\u0432]/g, 'v');
        str = str.replace(/[\u0175]/g, 'w');
        str = str.replace(/[\u00FF\u0177\u00FD\u044B]/g, 'y');
        str = str.replace(/[\u017A\u017C\u017E\u0437]/g, 'z');
        str = str.replace(/[\u00E6]/g, 'ae');
        str = str.replace(/[\u0447]/g, 'ch');
        str = str.replace(/[\u0445]/g, 'kh');
        str = str.replace(/[\u0153]/g, 'oe');
        str = str.replace(/[\u0448]/g, 'sh');
        str = str.replace(/[\u0449]/g, 'ssh');
        str = str.replace(/[\u044F]/g, 'ya');
        str = str.replace(/[\u0454]/g, 'ye');
        str = str.replace(/[\u0457]/g, 'yi');
        str = str.replace(/[\u0451]/g, 'yo');
        str = str.replace(/[\u044E]/g, 'yu');
        str = str.replace(/[\u0436]/g, 'zh');

        /* Uppercase */
        str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5\u0410]/g, 'A');
        str = str.replace(/[\u0411]/g, 'B');
        str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C\u0426]/g, 'C');
        str = str.replace(/[\u010E\u0110\u0414]/g, 'D');
        str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A\u0415\u042D]/g, 'E');
        str = str.replace(/[\u0424]/g, 'F');
        str = str.replace(/[\u011C\u011E\u0120\u0122\u0413\u0490]/g, 'G');
        str = str.replace(/[\u0124\u0126]/g, 'H');
        str = str.replace(/[\u0128\u012A\u012C\u012E\u0130\u0418\u0406]/g, 'I');
        str = str.replace(/[\u0134\u0419]/g, 'J');
        str = str.replace(/[\u0136\u041A]/g, 'K');
        str = str.replace(/[\u0139\u013B\u013D\u0139\u0141\u041B]/g, 'L');
        str = str.replace(/[\u041C]/g, 'M');
        str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A\u041D]/g, 'N');
        str = str.replace(/[\u00D3\u014C\u014E\u0150\u041E]/g, 'O');
        str = str.replace(/[\u041F]/g, 'P');
        str = str.replace(/[\u0154\u0156\u0158\u0420]/g, 'R');
        str = str.replace(/[\u015A\u015C\u015E\u0160\u0421]/g, 'S');
        str = str.replace(/[\u0162\u0164\u0166\u0422]/g, 'T');
        str = str.replace(/[\u00D9\u00DA\u00DB\u00DC\u0168\u016A\u016C\u016E\u0170\u0172\u0423]/g, 'U');
        str = str.replace(/[\u0412]/g, 'V');
        str = str.replace(/[\u0174]/g, 'W');
        str = str.replace(/[\u0176\u042B]/g, 'Y');
        str = str.replace(/[\u0179\u017B\u017D\u0417]/g, 'Z');
        str = str.replace(/[\u00C6]/g, 'AE');
        str = str.replace(/[\u0427]/g, 'CH');
        str = str.replace(/[\u0425]/g, 'KH');
        str = str.replace(/[\u0152]/g, 'OE');
        str = str.replace(/[\u0428]/g, 'SH');
        str = str.replace(/[\u0429]/g, 'SHH');
        str = str.replace(/[\u042F]/g, 'YA');
        str = str.replace(/[\u0404]/g, 'YE');
        str = str.replace(/[\u0407]/g, 'YI');
        str = str.replace(/[\u0401]/g, 'YO');
        str = str.replace(/[\u042E]/g, 'YU');
        str = str.replace(/[\u0416]/g, 'ZH');
        str = str.toLowerCase();
        str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]/g, '');


        str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u002F\u003A\u003B\u003C\u003D\u003E]/g, '');
        str = str.replace(/[\s\'\:\/\[\]-]+/g, ' ');

        // Add special char not used for url rewrite
        str = str.replace(/[ ]/g, '-');
        str = str.replace(/[\/\\"'|,;]*/g, '');

        if (ucfirst == 1) {
            var first_char = str.charAt(0);
            str = first_char.toUpperCase() + str.slice(1);
        }

        return str;
    },
    print_preview: function (print_id, title) {
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=650, height=600, left=100, top=25";
        var content_vlue = document.getElementById(print_id).innerHTML;

        var docprint = window.open("", "", disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><title>' + title + '</title>');
        docprint.document.write('<link href="css/print.css" media="all" rel="stylesheet" type="text/css" />');

        docprint.document.write('</head><body onload="self.print()"><center>');
        docprint.document.write('<p style="text-align: left; font-size: 16px; font-weight: bold; color: #0073B1;text-align: center;">' + title + '</p>');
        docprint.document.write(content_vlue);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.focus();
    },
    downChart : function(obj,div){

        var title = $(obj).attr('name');
        var arrChart = [];
        var elm = $('#' + div).find('.chart-content');
        $(elm).each(function( val ) {
            var arrContent = new Array();

            arrContent = {
                title 	: $( this ).find('.chart-title').text(),
                year 		: $( this ).find('.chart-year').val(),
                dataImg 	: $( this ).find('img').attr('src'),};

            arrChart.push(arrContent);
        });

        $.ajax({
            type 	: 'POST',
            dataType: 'text',
            url		: '/admin/statistic/export-chart',
            data	: {arrChart : arrChart, title:title},
            success	: function(rs){

                window.location.href = document.location.origin+rs;
            }
        });
    },
    sendMailStartCompaign: function(count){
        $.ajax({
            type: "POST",
            url: "/admin/new-session-manager/send-mail-dean",
            dataType: 'json',
            data: {
                count: count,
            },
            success: function(resp) {
                //console.log(resp.sqlString,resp.MailNotSend);
                if(resp.success==1 ){
                    setTimeout('common.sendMailStartCompaign(' + resp.count + ')', 5000);
                    $('#count_email').html(resp.count);

                }else{
                    $('#success').html('');
                }
            }
        });
    },
    sendMailMarketing: function(count){
        $('#message').hide();
        $('#success').show();
        $.ajax({
            type: "POST",
            url: "/admin/marketing/send-mail-marketing",
            dataType: 'json',
            data: {
                count: count,
            },
            success: function(resp) {
                //console.log(resp.sqlString,resp.MailNotSend);
                if(resp.success==1 ){
                    setTimeout('common.sendMailMarketing(' + resp.count + ')', 5000);
                    $('#count-email').html(resp.count);
                }else{
                    $('#success').html('Sending email is finished!');
                }
            }
        });
    },

    loadDataFilterPopup : function (id_country,id_subzone,school_name,vote_info,last_vote){
        //List id đã chọn loại bỏ khỏi list khi search
        var listid = new Array();
        var table = $('#sendList').dataTable();
        $('input[name^="sendlist"]', table.fnGetNodes()).each(function(){
            listid.push($(this).val());
        });
        var data= {
            id_country: id_country,
            id_subzone: id_subzone,
            school_name: school_name,
            vote_info: vote_info,
            list_id : listid,
            last_vote: last_vote,
        };
        $.ajax({
            type: "POST",
            url: "/admin/marketing/load-data-popup",
            dataType: 'json',
            data: {
                id_country: id_country,
                id_subzone: id_subzone,
                school_name: school_name,
                vote_info: vote_info,
                list_id : listid,
                last_vote: last_vote,
            },
            success: function(data) {
                if (data.success == 1) {
                    multiSelection.loadTableAfterFilter(data.html);
                }
            }
        });
    },

    load_template_mail: function(id){
        $.ajax({
            type: "POST",
            url: "/admin/marketing/load-template-mail",
            dataType: 'json',
            data: {
                id:id,
            },
            success: function(resp) {
                if(resp.success == 1){
                    $('input[name="subject"]').val(resp.data['subject']);
                    $('input[name="id_template"]').val(id);
                    $('.template').find('img').attr('src',resp.data['img_deactive']);
                    $('.template').find($('#img_active_'+id).find('img').attr('src',resp.data['img_active']));
                    CKEDITOR.instances['editor'].setData(resp.data['content'])
                }
            }
        });
    },

    add_template_mail:function(){
        $('input[name="id_template"]').val(-1);
        $('input[name="subject"]').val('');
        CKEDITOR.instances['editor'].setData('');
        $('input[name="subject"]').focus()
    },

    show_note_school: function(id){
        $.ajax({
            type: "POST",
            url: "/admin/school/show-note-school",
            dataType: 'json',
            data: {
                id : id,
            },
            success: function(data) {
                if(data.success==1){
                    $('input[name="id"]').val(data.id);
                    $('textarea[name="note"]').val(data.note);
                    $("#DialogAddNote").dialog({
                        modal: true,
                        width: 500
                    });
                }
            }
        });
    },

    save_note_school : function(){
        var id = $('input[name="id"]').val();
        var note = $('textarea[name="note"]').val();
        console.log(id);
        console.log(note);
        $.ajax({
            type: "POST",
            url: "/admin/school/save-note-school",
            dataType: 'json',
            data: {
                id : id,
                note: note,
            },
            success: function(data) {
                if(data.success==1){
                    $('.note_'+id).html(data.note);
                    $("#DialogAddNote").dialog('close');
                }
            }
        });
    },
}

common.ajaxSetup();
$(document).ready(function () {
    common.fixBrokenImage();
    $('#btnSubmit').click(function () {
        $('#frm_year').submit();
    });

    //show popup recipient
    $(".openDialogAddRecipient").click(function () {
        $.ajax({
            type: "POST",
            url: "/admin/marketing/popup",
            dataType: 'html',
            data: {},
            beforeSend: function () {
            },
            success: function (data) {
                $("#DialogAddRecipient").html(data);
                $("#DialogAddRecipient").dialog({
                    modal: true,
                    width: 1170
                });
            }
        });
    });
})


function validate_frm_year(){

    if (document.frm_year.year.value == "")
    {
        alert("Please input number year of Campagne!");
        document.frm_year.year.focus();
        return false;}
    else return true;
}
