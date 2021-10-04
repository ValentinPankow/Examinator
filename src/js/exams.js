$(document).ready(function() {
    $('#textTopic').summernote({
        minHeight: 85
    });
    $('#textOther').summernote();
    
    $("#examsTable").DataTable({
        "responsive": true,
        "autowidth": true,
        "ajax": {
            "url": "src/php/_ajax/ajax.listExams.php",
            "dataSrc": "exams"
        },
        "columns": [
            { "data" : "subject" },
            { "data" : "class" },
            { "data" : "room" },
            { "data" : "lessonFrom" },
            { "data" : "lessonTo" }
        ],
        fixedHeader: {
            header: true,
            footer: true
        }
    });
});

$('#chkActivateOther').on('change', function() {
    if ($('#chkActivateOther').is(':checked')) {
        $('#otherWrapper').show();
    } else {
        $('#otherWrapper').hide();
    }
});