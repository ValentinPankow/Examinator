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

let classesTable = null;

$(document).ready(function(){
    classesTable = $('#classesTable').DataTable({
        "responsive": true,
        "autowidth": true,
        "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
        "ordering": false,
        "ajax": {
            "url": "src/php/_ajax/ajax.listClasses.php",
            "dataSrc": "classes"
        },
        "columns": [
            { "data": "name" },
            { 
                searchable: false,
                orderable: false,
                "data": "id",
                render: function (account) { return '\
                    <div class="btn-group">\
                        <button type="button" class="btn btn-primary" name="editClass" data-id="'+account+'"><i class="fas fa-pen"></i></button>\
                        <button type="button" class="btn btn-danger" name="deleteClass" data-id="'+account+'"><i class="fas fa-trash"></i></button>\
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
});


$("#addClass").on("click", function() {
    $("#addClassModal").modal("show");
});

$('#classesTable').on("click", 'button[name="editClass"]', function () {
    let button = $(this);
    $('#editClassModal').find('button[name="save"]').attr('data-id', button.attr('data-id'));
    $('#editClassModal').modal('show');
});

$('#classesTable').on("click", 'button[name="deleteClass"]', function () {
    let button = $(this);
    $('#deleteClassModal').find('button[name="delete"]').attr('data-id', button.attr('data-id'));
    $('#deleteClassModal').modal('show');
});

$('#editClassModal').on('shown.bs.modal', function () {
    getClassData($("#editClassModal").find('button[name="save"]').attr('data-id'));
});

$("#editClassModal").find('button[name="save"]').on('click', function () {
    editClass($("#editClassModal").find('button[name="save"]').attr('data-id'));
});

$('#deleteClassModal').find('button[name="delete"]').on('click', function () {
    deleteClass($('#deleteClassModal').find('button[name="delete"]').attr('data-id'));
});

$('#passwordChange').on("change", function () {
    if ($('#passwordChange').is(':checked')) {
        $('#inputEditPassword').prop('disabled', false);
        $('#inputEditConfirmPassword').prop('disabled', false);
        $('#inputEditPassword').val("");
        $('#inputEditConfirmPassword').val("");
    } else {
        $('#inputEditPassword').prop('disabled', true);
        $('#inputEditConfirmPassword').prop('disabled', true);
        $('#inputEditPassword').val("--------");
        $('#inputEditConfirmPassword').val("--------");
    }
});

$('#editClassModal').on('hidden.bs.modal', function () {
    $('#editClassModal').find('.overlay').show();
    $('#inputEditName').val("");
    $('#inputEditPassword').val("--------");
    $('#inputEditConfirmPassword').val("--------");
    $('#passwordChange').prop('checked', false);
});

$('#addClassModal').on('hidden.bs.modal', function () {
    $('#addClassModal').find('.overlay').show();
    $('#inputName').val("");
    $('#inputPassword').val("");
    $('#inputConfirmPassword').val("");
});

$('#saveNewClass').on("click", function () {
    addNewClass();
});

function addNewClass() {
    let nameValue = $('#inputName').val().trim();
    let passwordValue = $('#inputPassword').val();
    let confirmPasswordValue = $('#inputConfirmPassword').val();

    let errorMsg = null;
    if(nameValue == null) {
        errorMsg = $('.missingInput').html();
    }
    if (passwordValue != confirmPasswordValue) {
        errorMsg = $('.errorPassword').html();
    }
    if (passwordValue.length < 8) {
        errorMsg = $('.errorPasswordLength').html();
    }
    if(nameValue == "" || passwordValue == "" || confirmPasswordValue == "") {
        errorMsg = $('.missingInput').html();
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    }

        $.post(
            'src/php/_ajax/ajax.queryClass.php',
            {
                data: {
                    action: 'insert',
                    name: nameValue,
                    password: passwordValue,
                },
            },

            function (rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        triggerResponseMsg('success', $('.successCreateClass').html());
                    } else {
                        if (obj.error == "insert") {
                            triggerResponseMsg('error', $('.errorCreateClass').html());
                        } else {
                            triggerResponseMsg('error', $('.errorDuplicateClass').html());
                        }
                    }

                    $('#addClassModal').modal("hide");
                    reloadTable();
                } catch (e) {
                    console.log(e);
                    triggerResponseMsg('error', $('.errorCreateClass').html());
                }
            }
        );
    }


    function getClassData(id) {
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
                    console.log(obj);
                    if (obj.success) {
                        $('#inputEditName').val(obj.class.name);
                        $('#editClassModal').find('.overlay').fadeOut(500);
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        );
    }

    function deleteClass(id) {
        $.post(
            'src/php/_ajax/ajax.deleteClass.php',
            {
                data: {
                    id: id
                },
            },
            function (rtn) {
                try {
                    let obj = JSON.parse(rtn);
                    if (obj.success) {
                        triggerResponseMsg('success', $('.successDeleteClass').html());
                    } else {
                        triggerResponseMsg('error', $('.errorDeleteClass').html());
                    }
                    $("#deleteClassModal").modal("hide");
                    reloadTable();
                } catch (e) {
                    console.log(e);
                    triggerResponseMsg('error', $('.errorDeleteClass').html());
                }
            }
        )
    }

    function editClass(id) {

        let nameValue = $('#inputEditName').val().trim();
        let passwordValue = $('#inputEditPassword').val();
        let confirmPasswordValue = $('#inputEditConfirmPassword').val();
        let changePassword = $('#passwordChange').is(":checked") ? true : false;

        let errorMsg = null;

        if (passwordValue != confirmPasswordValue && changePassword) {
           errorMsg = $('.errorPassword').html(); 
        }

        if (passwordValue.length < 8 && changePassword) {
            errorMsg = $('.errorPasswordLength').html();
        }

        if (nameValue == "" || (changePassword && (confirmPasswordValue == "" || passwordValue == ""))) {
            errorMsg = $('.missingInput').html();
        }

        if (errorMsg != null) {
            triggerResponseMsg('error', errorMsg);
            return false;
        }

        $.post(
           'src/php/_ajax/ajax.queryClass.php',
           {
               data: {
                   id: id,
                   action: 'update',
                   name: nameValue,
                   password: passwordValue,
                   changePassword: changePassword
               },
           },
           function (rtn) {
               try {
                   let obj = JSON.parse(rtn);
                   if (obj.success) {
                       triggerResponseMsg('success', $('.successEditClass').html());
                   } else {
                       if (obj.error == "update") {
                           triggerResponseMsg('error', $('.errorEditClass').html());
                       } else {
                           triggerResponseMsg('error', $('.errorDuplicateClass').html());
                       }
                   }

                   $("#editClassModal").modal("hide");
                   reloadTable();
               } catch (e) {
                   console.log(e);
                   triggerResponseMsg('error', $('.errorEditClass').html());
               }
           }
        ); 
    }

    function reloadTable() {
        $('#tableOverlay').fadeIn(500);
        setTimeout(function() { 
            classesTable.ajax.reload(hideOverlay());
        }, 150);    
    }
    
    function hideOverlay() {
        $('#tableOverlay').fadeOut(500);
    }