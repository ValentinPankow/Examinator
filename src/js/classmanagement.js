// Benachrichtungs Element erzeugen
// (DH C&P von VP)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: "swal2-popup-custom"
    },
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

$(document).ready(function() {
    let helpText = "\
    <h5>Klasse anlegen</h5>\
    <p>In diesem Bereich kann eine Klasse durch eingabe des Klassennamens sowie des Passwortes, mit dem sich die Schüler der Klasse anmelden können \
    und nach klicken des Buttons 'Anlegen', angelegt werden. Das Passwort muss mindestens 8 Zeichen besitzen</p>\
    <h5>Klassen ändern / löschen</h5>\
    <p>In dem Bereich 'Alle Klassen' werden alle Klassen aufgelistet, die angelegt wurden.</p>\
    <p>Durch einen Klick auf den Button mit dem Stift Symbol kann die Klasse geändert werden. Soll das Passwort geändert werden muss vorher \
    das Kontrollkästchen bestätigt werden, ansonsten wird nur der Name geändert.</p>\
    <p>Das löschen der Klasse erfolgt über einen Klick auf den Knopf mit dem Mülleimer Symbol. Nach Bestätigung einer Sicherheits Meldung, wird die Klasse gelöscht.</p>\
    <h5>Klassenliste importieren</h5>\
    <p>In diesem Bereich kann eine CSV Datei importiert werden die folgendes Format besitzen muss:</p>\
    <ul>\
    <li>name</li>\
    <li>password</li>\
    </ul>\
    <p><i class='fas fa-info-circle'></i> Das Passwort muss mindestens 8 Zeichen enthalten.</p>\
    <p><i class='fas fa-info-circle'></i> Klassen die bereits vorhanden sind, werden übersprungen</p>\
    <p>Mit dem Knopf 'Log anzeigen' kann eingesehen werden, welche Klassen importiert werden konnten und welche nicht.</p>\
    ";
    $('#helpText').html(helpText);
});

// Öffnet die Funktion zum abspeichern einer neuen Klasse beim anklicken des Buttons "Anlegen"
// (DH)
$('#saveClass').on('click', function() {
    saveNewClass();
});

// Speichert eine neue Klasse über "Anlegen"
// DH (C&P von VP mit eigenen Anpassungen)
function saveNewClass()
{
    let name = $('#createName').val();
    let password = $('#createPassword').val();

    // Variable für Fehlermeldung
    let errorMsg = null;

    // Fehlermeldung Passworteingabe ist nicht gleich
    if ($('#createPassword').val() != $('#createPasswordConfirmation').val()) {
        errorMsg = $('.passwordNotEqual').html();
    }

    // Fehlermeldung, falls das Passwort nicht mindestens 8 Zeichen hat
    if (password.length < 8) {
        errorMsg = $('.errorPasswordLength').html();
    }

    // Fehlermeldung falls kein Name eingegeben wurde
    if ($('#createName').val() == '') {
        errorMsg = $('.noNameSelected').html();
    }

    // Falls eine Fehlermeldung entsteht, diese zurückgeben [...]
    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    // [...] ansonsten die neue Klasse versuchen anzulegen
    } else {
        $.post(
            'src/php/_ajax/ajax.queryClass.php',
            {
                data: {
                    action: 'insert',
                    name: name,
                    password: password
                },
            },
            function(rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        // Eingabemaske zurücksetzen
                        $('#createName').val('');
                        $('#createPassword').val('');
                        $('#createPasswordConfirmation').val('');
                        triggerResponseMsg('success', $('.successCreateClass').html());
                        // setTimeout(function() {window.location.reload();}, 3000);
                        let append = "<div class='col-xl-4 col-lg-4 col-md-6 col-12' id='class_" + obj.data_id + "'>\
                                        <div class='card card-primary border border-primary'>\
                                            <div class='card-body'>\
                                                <h3 class='card-title'>\
                                                    <b style='vertical-align: sub'>" + name + "</b>\
                                                </h3>\
                                                <div class='text-right card-tools'>\
                                                    <button type='button' class='btn btn-sm btn-primary text-white edit' data-toggle='modal' data-id='" + obj.data_id + "'><i class='fas fa-edit'></i></button>\
                                                    <button type='button' class='btn btn-sm btn-primary text-white ml-2 delete' data-toggle='modal' data-id='" + obj.data_id + "'><i class='fas fa-trash'></i></button>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>";
                        $('#classesContainer').append(append).bind($(".delete").on("click", function(e) {
                            let button = $(this);
                            $('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id', button.attr('data-id'));
                            $('#deleteClassModal').modal('show');
                        }).bind($(".edit").on("click", function(e) {
                            let button = $(this);
                            $('#editClassModal').find('button[name="editClass"]').attr('data-id', button.attr('data-id'));
                            $('#editClassModal').modal('show');
                        })));
                    } else {
                        if (obj.error == "failed") {
                            triggerResponseMsg('error', $('.errorCreateClass').html());
                        } else {
                            triggerResponseMsg('error', $('.errorDuplicateClass').html());
                        }
                    }
                } catch(e) {
                    console.log(e);
                }
            }
        );
    }
}

// Öffnet das Modal zum editieren in der Klassenverwaltung
// DH (Zumeist C&P von VP mit Anpassungen)
$('.edit').on('click',  function () {
    let button = $(this);
    $('#editClassModal').find('button[name="editClass"]').attr('data-id', button.attr('data-id'));
    $('#editClassModal').modal('show');
});


// Wenn das Modal geöffnet wurde, die Klasse laden
$('#editClassModal').on('shown.bs.modal', function() {
    getClass($('#editClassModal').find('button[name="editClass"]').attr('data-id'));
});

// Wenn das Modal geöffnet wurde, die Klasse laden
$('#editClassModal').on('hidden.bs.modal', function() {
    $('#editName').val('');
    $('#editPassword').val('');
    $('#editPasswordConfirmation').val('');
    $('#editClassModal').find('.overlay').show();
});


// Öffnet die Funktion zum editieren einer Klasse beim anklicken des Buttons "Ändern"
// DH (Zumeist C&P von VP mit Anpassungen)
$('#editClassModal').find('button[name="editClass"]').on('click', function() {
    editClass($('#editClassModal').find('button[name="editClass"]').attr('data-id'));
});

$('#passwordChange').on("change", function () {
    if ($('#passwordChange').is(':checked')) {
        $('#editPassword').prop('disabled', false);
        $('#editPasswordConfirmation').prop('disabled', false);
        $('#editPassword').val("");
        $('#editPasswordConfirmation').val("");
    } else {
        $('#editPassword').prop('disabled', true);
        $('#editPasswordConfirmation').prop('disabled', true);
        $('#editPassword').val("--------");
        $('#editPasswordConfirmation').val("--------");
    }
});

// Ändert die Klasse. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.
// DH (Zumeist C&P von VP mit Anpassungen)
function editClass(id)
{
    let name = $('#editName').val();
    let password = $('#editPassword').val();
    let passwordConfirmation = $('#editPasswordConfirmation').val();
    let changePassword = $('#passwordChange').is(":checked") ? true : false;

    // Variable für Fehlermeldung
    let errorMsg = null;

    if ($('#editPassword').val() != $('#editPasswordConfirmation').val() && changePassword) {
        errorMsg = $('.passwordNotEqual').html();
    }
    if (password.length < 8 && changePassword) {
        errorMsg = $('.errorPasswordLength').html();
    }
    
    // Fehlermeldung kein Name eingegeben
    if ($('#editName').val() == '') {
        errorMsg = $('.noNameSelected').html();
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    } else {
        $.post(
            'src/php/_ajax/ajax.queryClass.php',
            {
                data: {
                    id: id,
                    action: 'update',
                    name: name,
                    password: password,
                    passwordConfirmation: passwordConfirmation
                },
            },
            function (rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        triggerResponseMsg('success', $('.successEditClass').html());
                        $('#class_' + id + ' h3 b').html(name);
                    } else {
                        if (obj.error == "failed") {
                            triggerResponseMsg('error', $('.errorEditClass').html());
                        } else {
                            triggerResponseMsg('error', $('.errorDuplicateClass').html());
                        }
                    }
                    $('#editClassModal').modal('hide');
                } catch (e) {
                    console.log(e);
                    triggerResponseMsg('error', $('.errorEditClass').html());
                    $('#editClassModal').modal('hide');
                }
            }
        );
    }

}


//Ändert die Klasse. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.
//DH (Zumeist C&P von VP mit Anpassungen)
function getClass(id) {
    $.post(
        'src/php/_ajax/ajax.getClass.php',
        {
            data: {
                id: id
            },
        },
        function (rtn) {
            try {
                let obj = JSON.parse(rtn);

                if (obj.success) {

                    // Zurückbekommene Werte den Feldern zuteilen
                    $('#editName').val(obj.class.name);
                    $('#editClassModal').find('.overlay').fadeOut(500);
                } else {
                    triggerResponseMsg('error', $('.errorGetClass').html());
                    $('#editClassModal').modal('hide');
                }
            } catch (e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorGetClass').html());
                $('#editClassModal').modal('hide');
            }
        }
    );
}

//Öffnet das Modal zum löschen in der Klassenverwaltung
//DH (Zumeist C&P von VP mit Anpassungen)
$('.delete').on('click',  function () {
    let button = $(this);
    $('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id', button.attr('data-id'));
    $('#deleteClassModal').modal('show');
});


$('#deleteClassModal').find('button[name="deleteClass"]').on('click', function() {
    deleteClass($('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id'));
});


//Löscht eine Klasse mithilfe der ID
// DH (Zumeist C&P vo VP mit Anpassungen)
function deleteClass(id) {
    $.post(
        'src/php/_ajax/ajax.deleteClass.php',
        {
            data: {
                id: id
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {
                    triggerResponseMsg('success', $('.successDeleteClass').html());
                    $('#class_' + id).remove();
                } else {
                    triggerResponseMsg('error', $('.errorDeleteClass').html());
                }
                $('#deleteClassModal').modal('hide');
            } catch(e) {
                console.log(e);
            }
        }
    );
}

$('button#importClass').on('click', function () {

    // Get the selected file(s) and store them in the files variable --- [0].files; because we just want to have one file
    let fd = new FormData();
    let files = $('#fileUpload')[0].files;

	// Add the file to the form data variable
    if (files.length > 0) {
        fd.append('file', files[0]);
    } else {
        triggerResponseMsg('error', $('.emptyInputFile').html());
        return false;
    }

    $.ajax({
        type: 'POST',
        url: 'src/php/_ajax/ajax.classImport.php',
        contentType: false,
        processData: false,
        data: fd,
        success: function (rtn) {
			// Parse the response JSON from php to an object
            let obj = JSON.parse(rtn);
			// Upload Successful
            if (obj.status == 'success') {
                if (obj.successCount == 0 && obj.failCount > 0) {
                    triggerResponseMsg('info', 'Die Datei enthält nur bereits vorhandene Klassen!');
                } else {
                    triggerResponseMsg('success', 'Die Datei wurde erfolgreich importiert. '+ obj.successCount + " erfolgreich, " + obj.failCount + " fehlgeschlagen.");
                }
            } else {
                if (obj.status == "type_error") {
					// File is not of the correct type
                    triggerResponseMsg('error', 'Die Datei hat nicht den richtigen Dateityp!');
                } else if (obj.status == "wrong_format") {
					// File wrong format
                    triggerResponseMsg('info', 'Die Datei ist nicht im richtigen Format!');
                } else {
                    // General File upload error
                    triggerResponseMsg('error', 'Die Datei konnte nicht hochgeladen werden!');
                }
            }

			// Clear the upload file input
            $('#fileUpload').val('');
            $('#upload-file-info').html('');
        }
    });
});


