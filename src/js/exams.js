// Benachrichtungs Element erzeugen
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

$(document).ready(function() {
    // Erstellen der Eingabefelder
    $('#textTopic').summernote({
        minHeight: 85
    });
    $('#textOther').summernote();

    $('#textTopicChange').summernote({
        minHeight: 85
    });
    $('#textOtherChange').summernote();
    
    // Erstellen der Tabelle um klausuren anzeigen zu können.
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
            { 
                "data" : null,
                render: function (row) {
                    if (row.lessonFrom != null) {
                        return row.lessonFrom + ". Std.";
                    } else if (row.timeFrom != null) {
                        return row.timeFrom.substring(0, 5) + " <i class='far fa-clock'></i>";
                    } else {
                        return "-";
                    }
                }
            },
            { 
                "data" : null,
                render: function (row) {
                    if (row.lessonTo != null) {
                        return row.lessonTo + ". Std.";
                    } else if (row.timeTo != null) {
                        return row.timeTo.substring(0, 5) + " <i class='far fa-clock'></i>";
                    } else {
                        return "-";
                    }
                } 
            },
            { 
                searchable: false,
                orderable: false,
                "data" : "id",
                render: function (exams) { return '\
                    <div class="btn-group">\
                        <button type="button" class="btn btn-primary" name="editExam" data-id="'+exams+'"><i class="fas fa-pen"></i></button>\
                        <button type="button" class="btn btn-danger" name="deleteExam" data-id="'+exams+'"><i class="fas fa-trash"></i></button>\
                    </div>'
                }
            }
        ],
        fixedHeader: {
            header: true,
            footer: true
        },
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/de_de.json"
        }
    });

    // Set inputs to current Time + 45 Minutes
    /*var d = new Date();
    var dd = new Date(d.getTime() + 45*60000);
    $('#inputTimeTo').val((dd.getHours()<10?'0'+dd.getHours():dd.getHours())+':'+(dd.getMinutes()<10?'0'+dd.getMinutes():dd.getMinutes()));
    $('#inputTimeFrom').val((d.getHours()<10?'0'+d.getHours():d.getHours())+':'+(d.getMinutes()<10?'0'+d.getMinutes():d.getMinutes()));*/

    // Set inputs to current Date
    var day = ("0" + d.getDate()).slice(-2);
    var month = ("0" + (d.getMonth() + 1)).slice(-2);
    var today = d.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#inputDate').val(today);
});

// Examples to bind values to modal inputs
/*
    $('#editModalAccounts').find('input[name="username"]').val(button.closest('tr').find('td').eq(0).text()); //BIND USERNAME FROM TABLE TO INPUT
    $('#editModalAccounts').find('input[name="firstname"]').val(button.closest('tr').find('td').eq(1).text()); //BIND FIRSTNAME FROM TABLE TO INPUT
    $('#editModalAccounts').find('input[name="lastname"]').val(button.closest('tr').find('td').eq(2).text()); //BIND LASTNAME FROM TABLE TO INPUT
    $('#editModalAccounts').find('input[name="mail"]').val(button.closest('tr').find('td').eq(3).text()); //BIND MAIL ADDRESS FROM TABLE TO INPUT
*/

// Show Modals and bind id from entry to save button
$('#examsTable').on('click', 'button[name="editExam"]', function () {
    let button = $(this);
    $('#changeExamsModal').find('button[name="save"]').attr('data-id',button.attr('data-id')); // BIND USER ID FROM TABLE BUTTON TO SAVE BUTTON
    $('#changeExamsModal').modal('show');
});

$('#examsTable').on('click', 'button[name="deleteExam"]', function () {
    let button = $(this);
    $('#deleteExamsModal').find('button[name="delete"]').attr('data-id',button.attr('data-id')); // BIND USER ID FROM TABLE BUTTON TO SAVE BUTTON
    $('#deleteExamsModal').modal('show');
});

// Show or hide Other fields
$('#chkActivateOther').on('change', function() {
    if ($('#chkActivateOther').is(':checked')) {
        $('#otherWrapper').show();
    } else {
        $('#otherWrapper').hide();
    }
});

$('#chkActivateOtherChange').on('change', function() {
    if ($('#chkActivateOtherChange').is(':checked')) {
        $('#otherWrapperChange').show();
    } else {
        $('#otherWrapperChange').hide();
    }
});

// Deactivate Inputs if radio button is changed
$('#rbTime').on('change', function() {
    if ($('#rbTime').is(':checked')) {
        $('#inputTimeFrom').prop('disabled', false);
        $('#inputTimeTo').prop('disabled', false);

        $('#selectLessonFrom').prop('disabled', true);
        $('#selectLessonTo').prop('disabled', true);
        $('#selectLessonFrom').val('-');
        $('#selectLessonTo').val('-');
    }
});

$('#rbLesson').on('change', function() {
    if ($('#rbLesson').is(':checked')) {
        $('#selectLessonFrom').prop('disabled', false);
        $('#selectLessonTo').prop('disabled', false);

        $('#inputTimeFrom').prop('disabled', true);
        $('#inputTimeTo').prop('disabled', true);
        $('#inputTimeFrom').val('');
        $('#inputTimeTo').val('');
    }
});

$('#rbTimeChange').on('change', function() {
    if ($('#rbTimeChange').is(':checked')) {
        $('#inputTimeFromChange').prop('disabled', false);
        $('#inputTimeToChange').prop('disabled', false);

        $('#selectLessonFromChange').prop('disabled', true);
        $('#selectLessonToChange').prop('disabled', true);
        $('#selectLessonFromChange').val('-');
        $('#selectLessonToChange').val('-');
    }
});

$('#rbLessonChange').on('change', function() {
    if ($('#rbLessonChange').is(':checked')) {
        $('#selectLessonFromChange').prop('disabled', false);
        $('#selectLessonToChange').prop('disabled', false);

        $('#inputTimeFromChange').prop('disabled', true);
        $('#inputTimeToChange').prop('disabled', true);
        $('#inputTimeFromChange').val('');
        $('#inputTimeToChange').val('');
    }
});

$('#saveExam').on('click', function() {
    
});

function saveNewExam() {

    let dateValue = $('#inputDate').val();
    let timeFromValue = $('#inputTimeFrom').val();
    let timeToValue = $('#inputTimeTo').val();
    let lessonFromValue = $('#selectLessonFrom option:selected').val();
    let lessonToValue = $('#selectLessonTo option:selected').val();
    let classValue = $('#selectClass option:selected').val();
    let subjectValue = $('#selectSubject option:selected').val();
    let roomValue = $('#inputRoom').val().trim();
    let topicValue = $('#textTopic').summernote('code').replace(/<p[^>]*>/g, ' ').replace(/<\/p>/g, '');
    let otherValue = $('#textOther').summernote('code').replace(/<p[^>]*>/g, ' ').replace(/<\/p>/g, '');

    $.post(
        'src/php/_ajax/ajax.createExam.php',
        {
            data: {
                date: dateValue,
                timeFrom: timeFromValue,
                timeTo: timeToValue,
                lessonFrom: lessonFromValue,
                lessonTo: lessonToValue,
                class: classValue,
                subject: subjectValue,
                room: roomValue,
                topic: topicValue,
                other: otherValue
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {
                    Toast.fire({
                        icon: 'success',
                        title: $('.successCreateExam').html()
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: $('.errorCreateExam').html()
                    })
                }
            } catch(e) {
                console.log(e);
            }
        }
    );

    /*console.log(dateValue);
    console.log(timeFromValue);
    console.log(timeToValue);
    console.log(lessonFromValue);
    console.log(lessonToValue);
    console.log(classValue);
    console.log(subjectValue);
    console.log(roomValue);
    console.log(topicValue);
    console.log(otherValue);*/

}