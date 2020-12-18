// Strip white spaces on input SLUG + ISO CODE:
$(function() {
    $('#txt-country-iso-code').bind('input', function() {
        $(this).val(function(_, v) {
            return v.replace(/\s+/g, '');
        });
    });
    $('#txt-country-alias').bind('input', function() {
        $(this).val(function(_, v) {
            return v.replace(/\s+/g, '');
        });
    });

    $('#txt-vote-manager').bind('input', function() {
        $(this).val(function(_, v) {
            return v.replace(/\s+/g, '');
        });
    });
});