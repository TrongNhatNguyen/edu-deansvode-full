$(document).ready(function() {
	$('#loading').hide();
    $("#frmContact").submit(function (event) {
        //event.preventDefault();
        var _data = $("#frmContact").serialize();
        var fullname = $('input[name="fullname"]').val();
        var email = $('input[name="email"]').val();
        var institution = $('input[name="institution"]').val();
        var position = $('input[name="position"]').val();

        if(email == ''){
            $('#validate').html("Email is required and can't be empty");
            return false;
        }

        if(fullname == ''){
            $('#validate').html("Fullname is required and can't be empty");
            return false;
        }

        if(institution == ''){
            $('#validate').html("Institution is required and can't be empty");
            return false;
        }

        if(position == ''){
            $('#validate').html("Position is required and can't be empty");
            return false;
        }
		$('#loading').show();
        $.ajax({
            dataType: 'json',
            type: "POST",
            data: _data,
            url: '/index/contact',
            beforeSend: function()
            {
                $('#btn-send-mail').prop('disabled', true);
            }
        }).success(function (data) {
            $('#frmContact')[0].reset();
			$('#loading').hide();
            $('#btn-send-mail').prop('disabled', false);
            $('.thanks-modal-sm').modal('toggle');
			
        });
        return false;
    });
});

