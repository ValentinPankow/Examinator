//Benachrichtungs Element erzeugen
//(DH) (C&P von VP)
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

$(document).ready(function () {
    
    //Öffnet die Funktion zum abspeichern eines neuen Fach beim anklicken des Buttons "Anlegen"
    //(DH)
    $('#saveSubject').on('click', function() {
        saveNewSubject();
    });
    
    //Öffnet das Modal zum editieren in der Fachverwaltung
    //(DH)(Zumeist C&P von VP mit Anpassungen)
    $('.edit').on('click',  function () {
        let button = $(this);
        $('#editSubjectModal').find('button[name="editSubject"]').attr('data-id', button.attr('data-id'));
        $('#editSubjectModal').modal('show');
    });
    
    //Wenn das Modal geöffnet wurde, das Fach laden
    //(DH)(Zumeist C&P von VP mit Anpassungen)
    $('#editSubjectModal').on('shown.bs.modal', function() {
        getSubject($('#editSubjectModal').find('button[name="editSubject"]').attr('data-id'));
    });
    
    $('#editSubjectModal').on('hidden.bs.modal', function() {
        $('#editName').val('');
        $('#editSubjectModal').find('.overlay').show();
    });  
    
    //Öffnet die Funktion zum editieren einer Klasse beim anklicken des Buttons "Ändern"
    //(DH)(Zumeist C&P von VP mit Anpassungen)
    $('#editSubjectModal').find('button[name="editSubject"]').on('click', function() {
        editSubject($('#editSubjectModal').find('button[name="editSubject"]').attr('data-id'));
    });
    
    //Öffnet das Modal zum editieren in der Klassenverwaltung
    // DH (Zumeist C&P von VP mit Anpassungen)
    $('.delete').on('click',  function () {
        // Change Logic as well in saveNewSubject
        let button = $(this);
        $('#deleteSubjectModal').find('button[name="deleteSubject"]').attr('data-id', button.attr('data-id'));
        $('#deleteSubjectModal').modal('show');
    });
    
    
    $('#deleteSubjectModal').find('button[name="deleteSubject"]').on('click', function() {
        deleteSubject($('#deleteSubjectModal').find('button[name="deleteSubject"]').attr('data-id'));
    });
    
});
  
//Speichert ein neues Fach über "Anlegen"
//(DH)(Zumeist C&P von VP mit Anpassungen)
function saveNewSubject()
{
    let name = $('#createName').val();

    // Variable für Fehlermeldung
    let errorMsg = null;

    //Fehlermeldung falls kein Name eingegeben wurde
    if ($('#createName').val() == '') {
        errorMsg = $('.noNameSelected').html();
    }

    //Falls eine Fehlermeldung entsteht, diese zurückgeben [...]
    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    //[...] ansonsten das neue Fach versuchen anzulegen
    } else {
        $.post(
            'src/php/_ajax/ajax.querySubject.php',
            {
                data: {
                    action: 'insert',
                    name: name,
                },
            },
            function(rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        // Eingabemaske zurücksetzen
                        $('#createName').val('');
                        triggerResponseMsg('success', $('.successCreateSubject').html());
                        let append = "<div class='col-xl-4 col-lg-4 col-md-6 col-12' id='subject_" + obj.data_id + "'>\
                                        <div class='card' style='border: 1px solid #EE4D00;'>\
                                            <div class='card-header'>\
                                                <h3 class='card-title'>\
                                                    <b style='vertical-align: sub'>" + name + "</b>\
                                                </h3>\
                                                <div class='card-tools pr-3'>\
                                                    <button type='button' class='btn btn-sm text-white edit' style='background-color: #EE4D00' data-toggle='modal' data-id='" + obj.data_id + "'><i class='fas fa-edit'></i></button>\
                                                    <button type='button' class='btn btn-sm text-white ml-2 delete' style='background-color: #EE4D00' data-toggle='modal' data-id='" + obj.data_id + "'><i class='fas fa-trash'></i></button>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>";
                        $('#subjectsContainer').append(append).bind($(".delete").on("click", function(e) {
                            let button = $(this);
                            $('#deleteSubjectModal').find('button[name="deleteSubject"]').attr('data-id', button.attr('data-id'));
                            $('#deleteSubjectModal').modal('show');
                        }).bind($(".edit").on("click", function(e) {
                            let button = $(this);
                            $('#editSubjectModal').find('button[name="editSubject"]').attr('data-id', button.attr('data-id'));
                            $('#editSubjectModal').modal('show');
                        })));
                    } else {
                        if (obj.error == "failed") {
                            triggerResponseMsg('error', $('.errorCreateSubject').html());
                        } else {
                            triggerResponseMsg('error', $('.errorDuplicateSubject').html());
                        }
                    }
                } catch(e) {
                    console.log(e);
                }
            }
        );
    }
}

//Löscht ein Fach mithilfe der ID
// DH (Zumeist C&P vo VP mit Anpassungen)
function deleteSubject(id) {
    $.post(
        'src/php/_ajax/ajax.deleteSubject.php',
        {
            data: {
                id: id
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {
                    triggerResponseMsg('success', $('.successDeleteSubject').html());
                    $('#subject_' + id).remove();
                } else {
                    triggerResponseMsg('error', $('.errorDeleteSubject').html());
                }
                $('#deleteSubjectModal').modal('hide');
            } catch(e) {
                console.log(e);
            }
        }
    );
}

// Holt sich ein Fach nach der ID (Beim öffnen des Modals zum editieren benötigt)
// (DH)
function getSubject(id) {
    $.post(
        'src/php/_ajax/ajax.getSubject.php',
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
                    $('#editName').val(obj.subject.name);
                    $('#editSubjectModal').find('.overlay').fadeOut(500);
                } else {
                    triggerResponseMsg('error', $('.errorGetSubject').html());
                    $('#editSubjectModal').modal('hide');
                }
            } catch (e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorGetSubject').html());
                $('#editSubjectModal').modal('hide');
            }
        }
    );
}

//Ändert das Fach. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.
//(DH)(Zumeist C&P von VP mit Anpassungen)
function editSubject(id)
{
    let name = $('#editName').val();

    // Variable für Fehlermeldung
    let errorMsg = null;

    // Fehlermeldung, wenn kein Name angegeben wurde
    if ($('#editName').val() == '') {
        errorMsg = $('.noNameSelected').html();
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    } else {
        $.post(
            'src/php/_ajax/ajax.querySubject.php',
            {
                data: {
                    id: id,
                    action: 'update',
                    name: name,
                },
            },
            function (rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        triggerResponseMsg('success', $('.successEditSubject').html());
                        $('#subject_' + id + ' h3 b').html(name);
                    } else {
                    if (obj.error == "failed") {
                        triggerResponseMsg('error', $('.errorEditSubject').html());
                    } else {
                        triggerResponseMsg('error', $('.errorDuplicateSubject').html());
                    }
                    }
                    $('#editSubjectModal').modal('hide');
                } catch (e) {
                    console.log(e);
                    triggerResponseMsg('error', $('.errorEditSubject').html());
                    $('#editSubjectModal').modal('hide');
                }
            }
        );
    }
}