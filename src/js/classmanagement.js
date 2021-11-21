// Benachrichtungs Element erzeugen
//(DH C&P von VP)
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

//Öffnet die Funktion zum abspeichern einer neuen Klasse beim anklicken des Buttons "Anlegen"
//(DH)
$('#saveClass').on('click', function() {
    saveNewClass();
});

//Speichert eine neue Klasse über "Anlegen"
//DH (C&P von VP mit eigenen Anpassungen)
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

    //Fehlermeldung falls kein Name eingegeben wurde
    if ($('#createName').val() == '') {
        errorMsg = $('.noNameSelected').html();
    }

    //Falls eine Fehlermeldung entsteht, diese zurückgeben [...]
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
                        setTimeout(function() {window.location.reload();}, 3000);
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

//Öffnet das Modal zum editieren in der Klassenverwaltung
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


//Öffnet die Funktion zum editieren einer Klasse beim anklicken des Buttons "Ändern"
// DH (Zumeist C&P von VP mit Anpassungen)
$('#editClassModal').find('button[name="editClass"]').on('click', function() {
    editClass($('#editClassModal').find('button[name="editClass"]').attr('data-id'));
});

//Ändert die Klasse. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.
// DH (Zumeist C&P von VP mit Anpassungen)
function editClass(id)
{
    let name = $('#editName').val();
    let password = $('#editPassword').val();
    let passwordConfirmation = $('#editPasswordConfirmation').val();

    // Variable für Fehlermeldung
    let errorMsg = null;

    // Fehlermeldung Passworteingabe ist nicht gleich
    if ($('#editPassword').val() != $('#editPasswordConfirmation').val()) {
        errorMsg = $('.passwordNotEqual').html();
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
                        setTimeout(function() {window.location.reload();}, 3000);
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
    alert($('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id'));
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
                    setTimeout(function() {window.location.reload();}, 3000);
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
                triggerResponseMsg('success', 'Die Datei wurde erfolgreich importiert. '+ obj.successCount + " erfolgreich, " + obj.failCount + " fehlgeschlagen.");
            } else {
                if (obj.status == "type_error") {
					// File is not of the correct type
                    triggerResponseMsg('error', 'Die Datei hat nicht den richtigen Dateityp!');
                } else {
					// File upload error
                    triggerResponseMsg('error', 'Die Datei konnte nicht hochgeladen werden!');
                }
            }

			// Clear the upload file input
            $('#fileUpload').val('');
            $('#upload-file-info').html('');
        }
    });
});


