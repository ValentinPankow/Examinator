$(document).ready(function() {
    $('#textTopic').summernote({
        minHeight: 85
    });
    $('#textOther').summernote();
    
    $("#examsTable").DataTable({
        "responsive": true,
        "autowidth": true,
        fixedHeader: {
            header: true,
            footer: true
        },
        "ajax": 'src/php/_ajax/ajax.listExams.php'
    });
});

$('#chkActivateOther').on('change', function() {
    if ($('#chkActivateOther').is(':checked')) {
        $('#otherWrapper').show();
    } else {
        $('#otherWrapper').hide();
    }
});