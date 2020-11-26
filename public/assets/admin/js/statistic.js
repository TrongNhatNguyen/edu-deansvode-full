// world
function showWordStatVote(print){
    var print_command = '';
    if(print == 'true')
        print_command = '?print=true';
    $.get('/admin/statistic/world-vote' + print_command,function(resp){
        $('#tdWordStat').append(resp);
        $('#spnLoadingWorld').remove();
        setTimeout("showWorldStatVoted('"+print+"')",1000);
    });
}
function showWorldStatVoted(print){
    $.get('/admin/statistic/world-voted',function(resp){
        $('#tdAreaStat').append(resp);
        $('#spnLoadingArea').remove();
        setTimeout("showWorldPalmesStatVoted('"+print+"')",1000);
    });
}
function showWorldPalmesStatVoted(print){
    $.get('/admin/statistic/world-palmes',function(resp){
        $('#tdPalmesStat').append(resp);
        $('#spnLoadingPalmes').remove();
        setTimeout("showWorldStatRevote('"+print+"')",1000);
    });
}
function showWorldStatRevote(print){
    $.get('/admin/statistic/world-revote',function(resp){
        $('#tdRevoteStat').append(resp);
        $('#spnLoadingRevote').remove();
        if(print == 'true')
            window.print();
    });
}








function print_school_row(name, id) {
    var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,";
    disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";

    $.get('/admin/statistic/school-detail-voted/c_id/'+id+'/school_name/'+name,function(resp){
        var docprint=window.open("","",disp_setting);
        docprint.document.open();
        docprint.document.write('<html><head><title>Statistique - School - '+name+'</title>');
        docprint.document.write('<link href="css/print.css" media="all" rel="stylesheet" type="text/css" />');

        docprint.document.write('</head><body onload="self.print()"><center>');
        docprint.document.write('<p style="text-align: left; font-size: 16px; font-weight: bold; color: #0073B1">Statistique - School - '+name+'</p>');
        docprint.document.write(resp);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.focus();
    });
}


function viewNextTable($this) {
    var _tb = $($this).closest('table');
    $(_tb).hide();
    $(_tb).next().fadeIn();
}

function viewPrevTable($this) {
    var _tb = $($this).closest('table');
    $(_tb).hide();
    $(_tb).prev().fadeIn();
}