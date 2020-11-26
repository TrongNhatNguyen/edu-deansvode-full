$(document).ready(function(){
    
    $('#txtKeyword').keypress(function(event){
        if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13))
        {
            list.makeSearch();
			list.show();
            return false;
        }
        else
        {
            return true;
        }
    });

    $('[class*="sorting"]').on('click', function(event){
        if (event.target === event.currentTarget)
        {
            if (typeof $(this).data('group') !== 'undefined')
            {
                var data_sort = $(this).data('group');
            }

            if ($(this).hasClass('sorting_desc'))
            {
                var field = $(this).data('field');
                if (data_sort)
                {
                    list.sort(field, data_sort + '_asc');
                }
                else
                {
                    list.sort(field, 'asc');
                }
                $(this).attr('class', 'sorting_asc');
            }
            else
            {
                var field = $(this).data('field');
                if (data_sort)
                {
                    list.sort(field, data_sort + '_desc');
                }
                else
                {
                    list.sort(field, 'desc');
                }
                $(this).attr('class', 'sorting_desc');
            }
        }
    });

    $('#customCheckAll').on('change', function(){
        $('.check-row').prop('checked', this.checked);
        $('.check-row').change();
    });

    $('#customCheckMoveAll').on('change', function(){
        $('.check-move-row').prop('checked', this.checked);
    });

    // $('#update-form').submit(function(event) {
    //     event.preventDefault();

    //     var data = $(this).serialize();
    //     console.log(data);
    // });

    $('#remove-selected').click(function(){
      var key = selected_rows.toString();

      remove(key, 'multi');
    });

    $('#frm-update-subschool').submit(function(event){
        event.preventDefault();

        var update_data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: page_url + '/update-school',
            dataType: 'text',
            data: update_data,
            beforeSend: function(){},
            success: function(response_data) {
                // console.log(response_data);
                if (response_data === 'success')
                {
                    $.notify({
                        title: '<strong>Success!</strong>',
                        message: 'Update successfully!'
                    },
                    {
                        type: 'info'
                    });
                    setTimeout(function(){
                        window.location.href = page_url;
                    }, 1000);
                }
            }
        });
    });

});

$(document).on('submit', '#update-form', function(event){
    event.preventDefault();

    if (changed_info.length > 0)
    {
        var data_string = $(this).serialize();

        $.ajax({
            type: 'post',
            url: page_url + '/update',
            dataType: 'json',
            data: data_string,
            success: function(response_data)
            {
                if (response_data.error === 0)
                {
                    $('#update-message').attr('class', 'alert alert-info');
                    
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
                else
                {
                    $('#update-message').attr('class', 'alert alert-danger');
                }
                $('#update-message').html(response_data.message);
            }
        });
    }
    else
    {
        $('#update-message').attr('class', 'alert alert-warning');
        $('#update-message').html('No Info changed!');
    }

    // console.log(data);
})

function change_year(url)
{
    window.location = url;
    return false;
}

function toggle_status(id, element)
{
    var current_status = $(element).attr('class');
    var next_status;
    if (current_status === 'active')
    {
        next_status = 'inactive';
    }
    else
    {
        next_status = 'active';
    }

    // Đối với trường hợp change status ở trang sublist(eduniversal selected hoặc league 2)
    // thì ta cũng phải ẩn hoặc hiện ô 'move to list' tương ứng
    if (page_url.includes('selected-schools') || page_url.includes('sub-school') || page_url.includes('list-from-dean'))
    {
        var div_move_to_list = '.move-' + id;
    }

    $.ajax({
        type: 'post',
        url: page_url + '/change-status',
        dataType: 'text',
        data: {id: id, current_status: current_status},
        success: function(response_text)
        {
            if (response_text === 'success')
            {
                $(element).attr('class', next_status);
                $(element).html('<img src="/static/admin/assets/img/' + next_status + '.svg" width="18">');
                
                if (typeof div_move_to_list !== 'undefined')
                {
                    if (next_status === 'inactive')
                        $(div_move_to_list).hide();
                    else
                        $(div_move_to_list).show();
                }
            }
        }
    });
}

function remove(key, delete_type)
{
    if (is_blank(key) || (delete_type !== 'single' && delete_type !== 'multi'))
    {
        alert('Please select row to delete!');

        return false;
    }

    $.confirm({
        title: 'Delete',
        content: 'Are you sure? This action can not undo!',
        type: 'red',
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'post',
                    url: page_url + '/delete',
                    // url: delete_url,
                    dataType: 'text',
                    data: {key: key, delete_type: delete_type},
                    success: function(response_text)
                    {
                        if (response_text === 'success')
                        {
                            switch (delete_type)
                            {
                                case 'single':
                                    $('tr#'+key).hide('slow', function(){
                                        $(this).remove();
                                    });
                                    var remained_number_rows = parseInt($('#spnTotalRows').html().replace(',', '')) - 1;
                                    $('#spnTotalRows').html(remained_number_rows);
                                    var message = '1 row has been removed.';
                                    break;
                                case 'multi':
                                    var arr_deleted = JSON.parse('[' + key + ']');
                                    var deleted_length = arr_deleted.length;
                                    for (var i = 0; i < deleted_length; i++)
                                    {
                                        $('tr#'+arr_deleted[i]).hide('slow', function(){
                                            $(this).remove();
                                        });
                                    }
                                    var remained_number_rows = parseInt($('#spnTotalRows').html().replace(',', '')) - deleted_length;
                                    $('#spnTotalRows').html(remained_number_rows);
                                    var message = deleted_length + ' rows has been removed.';
                                    break;
                            }
                            $.notify({
                                title: '<strong>Success!</strong>',
                                message: message
                            },
                            {
                                type: 'info'
                            });
                        }
                        selected_rows = [];
                    }
                });
            },
            cancel: function() {}
        }
    });
}

function move_single_school(move_key, list_to_move)
{
    var move_content = get_list_to_move_content(list_to_move);

    var data_key = move_key;

    // var confirm_move = confirm('Do you want to move School B' + data_key + ' to ' + move_content + '?');
    
    $.confirm({
        title: 'Move school',
        content: 'Do you want to move School B' + data_key + ' to ' + move_content + '?',
        type: 'red',
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'post',
                    url: page_url + '/move-to-list',
                    dataType: 'json',
                    data: {key: data_key, move_type: 'single', list_to_move: list_to_move},
                    success: function(response_data)
                    {
                        // var success_id = response_data.success_id
                        if (response_data.error !== 1)
                        {
                            // console.log(success_id);
                            var success_id = response_data.success_id;
                            var current_total_rows = parseInt($('#spnTotalRows').html());

                            $('#' + success_id).hide('slow', function(){
                                $(this).remove();
                            });

                            $('#spnTotalRows').html(current_total_rows - 1);
                            
                            $.notify({
                                title: '<strong>Success!</strong>',
                                message: 'School B' + success_id + ' has been moved to ' + move_content + '.'
                                },
                                {
                                type: 'info'
                            });
                            $('#alert-move').modal('hide');
                        }
                        else
                        {
                            $.notify({
                                title: '<strong>Error!</strong>',
                                message: 'Can not move School B' + success_id + ' to ' + move_content
                            },{
                                type: 'error'
                            });
                            $('#alert-move').modal('hide');
                        }
                    }
                });
            },
            cancel: function() {}
        }
    });

    // if (confirm_move === true)
    // {
    //     $.ajax({
    //         type: 'post',
    //         url: page_url + '/move-to-list',
    //         dataType: 'json',
    //         data: {key: data_key, move_type: 'single', list_to_move: list_to_move},
    //         success: function(response_data)
    //         {
    //             // var success_id = response_data.success_id
    //             if (response_data.error !== 1)
    //             {
    //                 // console.log(success_id);
    //                 var success_id = response_data.success_id;
    //                 var current_total_rows = parseInt($('#spnTotalRows').html());

    //                 $('#sub-school-' + success_id).hide('slow', function(){
    //                     $(this).remove();
    //                 });

    //                 $('#spnTotalRows').html(current_total_rows - 1);
                    
    //                 $.notify({
    //                     title: '<strong>Success!</strong>',
    //                     message: 'School B' + success_id + ' has been moved to ' + move_content + '.'
    //                     },
    //                     {
    //                     type: 'info'
    //                 });
    //                 $('#alert-move').modal('hide');
    //             }
    //             else
    //             {
    //                 $.notify({
    //                     title: '<strong>Error!</strong>',
    //                     message: 'Can not move School B' + success_id + ' to ' + move_content
    //                 },{
    //                     type: 'error'
    //                 });
    //                 $('#alert-move').modal('hide');
    //             }
    //         }
    //     });
    // }
    // $('#accept-move').unbind('click', request_move(data_key, list_to_move, move_content));
}

function move_multi_schools(list_to_move)
{
    var move_key = new Array;
    $('[name="chk_move[]"]').each(function(){
        if ($(this).prop('checked') === true)
        {
            move_key.push($(this).val());
        }
    });

    if (move_key.length === 0)
    {
        $.notify({
            title: '<strong>Notice!</strong>',
            message: 'You must select School you want to move.'
        },{
            type: 'warning'
        });
        return false;
    }

    var move_content = get_list_to_move_content(list_to_move);

    $.confirm({
        title: 'Move multi schools',
        content: 'Do you want to move ' + move_key.length + ' School(s) to ' + move_content + '?',
        type: 'red',
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'post',
                    url: page_url + '/move-to-list',
                    dataType: 'json',
                    data: {key: move_key, move_type: 'multi', list_to_move: list_to_move},
                    success: function(response_data)
                    {
                        var success_id = response_data.success_id
                        if (success_id.length > 0)
                        {
                            // console.log(success_id);
                            var i;
                            for (i = 0; i < success_id.length; i++)
                            {
                                $('tr#' + success_id[i]).hide('slow', function(){
                                    $(this).remove();
                                });
                            }
                            $.notify({
                                title: '<strong>Success!</strong>',
                                message: success_id.length + ' Schools has been moved.'
                            },{
                                type: 'info'
                            });
                            $('#alert-move').modal('hide');
                        }
                        else
                        {
                            $.notify({
                                title: '<strong>Error!</strong>',
                                message: 'Can not move schools.'
                            },{
                                type: 'error'
                            });
                            $('#alert-move').modal('hide');
                        }
                    }
                });
            },
            cancel: function() {}
        }
    });

    // var confirm_move = confirm('Do you want to move ' + move_key.length + ' School(s) to ' + move_content + '?');

    // if (confirm_move === true)
    // {
    //     $.ajax({
    //         type: 'post',
    //         url: page_url + '/move-to-list',
    //         dataType: 'json',
    //         data: {key: move_key, move_type: 'multi', list_to_move: list_to_move},
    //         success: function(response_data)
    //         {
    //             var success_id = response_data.success_id
    //             if (success_id.length > 0)
    //             {
    //                 // console.log(success_id);
    //                 var i;
    //                 for (i = 0; i < success_id.length; i++)
    //                 {
    //                     $('tr#' + success_id[i]).hide('slow', function(){
    //                         $(this).remove();
    //                     });
    //                 }
    //                 $.notify({
    //                     title: '<strong>Success!</strong>',
    //                     message: success_id.length + ' Schools has been moved.'
    //                 },{
    //                     type: 'info'
    //                 });
    //                 $('#alert-move').modal('hide');
    //             }
    //             else
    //             {
    //                 $.notify({
    //                     title: '<strong>Error!</strong>',
    //                     message: 'Can not move schools.'
    //                 },{
    //                     type: 'error'
    //                 });
    //                 $('#alert-move').modal('hide');
    //             }
    //         }
    //     });
    // }
}

function get_list_to_move_content(list_to_move)
{
    switch (list_to_move)
    {
        case 'school':
        default:
            return 'Eduniversal Business School';
        case 'sub-list':
            return 'League 2';
        case 'suggestion':
            return "Dean's suggestion";
    }
}

function handle_select_row(checkbox)
{
    if (checkbox.checked === true)
    {
        selected_rows.push(checkbox.value);
    }
    else
    {
        let pos = selected_rows.indexOf(checkbox.value);
        selected_rows.splice(pos, 1);
    }

    // console.log(selected_rows);
}

function handle_edit_info(element)
{
    changed_info.push(element.value);
    $('#update-message').attr('class', '');
    $('#update-message').html('');
}

function is_blank(string)
{
    return (!string || $.trim(string) === "");
}

