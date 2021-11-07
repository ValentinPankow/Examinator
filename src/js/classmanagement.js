// Benachrichtungs Element erzeugen
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
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
$('#saveClass').on('click', function() {
    saveNewClass();
});

//Speichert eine neue Klasse über direktes Anlegen
function saveNewClass()
{

    let name = $('#createName').val();
    let password = $('#createPassword').val();

    // Variable für Fehlermeldung
    let errorMsg = null;

    // Fehlermeldung Passworteingabe ist nicht gleich
    if ($('#editPassword').val() != $('#editPasswordConfirmation').val()) {
        errorMsg = $('.passwordNotEqual').html();
    }

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
                        $('#name').val('');
                        $('#password').val('');

                    } else {
                        triggerResponseMsg('error', $('.errorCreateClass').html());
                    }
                } catch(e) {
                    console.log(e);
                }
            }
        );
    }
}

// DH (Zumeist C&P von VP mit Anpassungen)
//Öffnet das Modal zum editieren in der Klassenverwaltung
$('.edit').on('click',  function () {
    let button = $(this);
    $('#editClassModal').find('button[name="editClass"]').attr('data-id', button.attr('data-id'));

    $('#editClassModal').modal('show');
});


// Wenn das Modal geöffnet wurde, die Klasse laden
$('#editClassModal').on('shown.bs.modal', function() {
    getClass($('#editClassModal').find('button[name="editClass"]').attr('data-id'));
});



// DH (Zumeist C&P von VP mit Anpassungen)
//Öffnet die Funktion zum editieren einer Klasse beim anklicken des Buttons "Ändern"
$('#editClassModal').find('button[name="editClass"]').on('click', function() {
    editClass($('#editClassModal').find('button[name="editClass"]').attr('data-id'));
});


// DH (Zumeist C&P von VP mit Anpassungen)
//Ändert die Klasse. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.

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
                    } else {
                        triggerResponseMsg('error', $('.errorEditClass').html());
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
                $('#changeClassModal').modal('hide');
            }
        }
    );
}


// DH (Zumeist C&P von VP mit Anpassungen)
//Öffnet das Modal zum editieren in der Klassenverwaltung
$('.delete').on('click',  function () {
    let button = $(this);
    $('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id', button.attr('data-id'));

    $('#deleteClassModal').modal('show');
});


$('#deleteClassModal').find('button[name="deleteClass"]').on('click', function() {
    deleteClass($('#deleteClassModal').find('button[name="deleteClass"]').attr('data-id'));
});

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
                    reload();
                    $('#deleteClassModal').modal('hide');
                } else {
                    triggerResponseMsg('error', $('.errorDeleteClass').html());
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
}
