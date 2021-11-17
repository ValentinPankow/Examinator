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

let accountsTable = null;

$(document).ready(function(){
    accountsTable = $('#accountsTable').DataTable({
        "responsive": true,
        "autowidth": true,
        "lengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
        "ordering": false,
        "ajax": {
            "url": "src/php/_ajax/ajax.listAccounts.php",
            "dataSrc": "accounts"
        },
        "columns": [
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "email" },
            { 
                "data": null,
                render: function (row) {
                    if (row.is_teacher && row.is_admin) {
                        return "Admin & Lehrer";
                    } else if (row.is_teacher && !row.is_admin) {
                        return "Lehrer";
                    } else if (!row.is_teacher && row.is_admin) {
                        return "Admin";
                    } else {
                        return "-";
                    }
                } 
            },
            { 
                searchable: false,
                orderable: false,
                "data": "id",
                render: function (account) { return '\
                    <div class="btn-group">\
                        <button type="button" class="btn btn-primary" name="editAccount" data-id="'+account+'"><i class="fas fa-pen"></i></button>\
                        <button type="button" class="btn btn-danger" name="deleteAccount" data-id="'+account+'"><i class="fas fa-trash"></i></button>\
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


$("#addUser").on("click", function() {
    $("#addUserModal").modal("show");
});

$("#importUser").on("click", function() {
    $("#importUserModal").modal("show");
});

$('input#inputUpload').on('change', function (e) {
    //get the file name
    let fileName = e.target.files[0].name;
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});

$('button#importUsersd').on('click', function () {
    // Get the selected file(s) and store them in the files variable --- [0].files; because we just want to have one file
    let fd = new FormData();
    let files = $('#inputUpload')[0].files;

	// Add the file to the form data variable
    if (files.length > 0) {
        fd.append('file', files[0]);
    }

    $.ajax({
        type: 'POST',
        url: 'src/php/_ajax/ajax.userImport.php',
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
            $('#inputUpload').val('');
        }
    });
});   

$("#accountsTable").on("click", 'button[name="editAccount"]', function() {
    let button = $(this);
    $('#editUserModal').find('button[name="save"]').attr('data-id', button.attr('data-id'));
    $('#editUserModal').modal('show');
});

$('#deleteUserModal').find('button[name="delete"]').on('click', function () {
    deleteUser($('#deleteUserModal').find('button[name="delete"]').attr('data-id'));
});


$('#editUserModal').on('shown.bs.modal', function() {
    getUserData($("#editUserModal").find('button[name="save"]').attr('data-id'));
    $('#passwordChange').prop('checked', false)
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

$('#editUserModal').find('button[name="save"]').on('click', function() {
    editUser($("#editUserModal").find('button[name="save"]').attr('data-id'));
});

$('#editUserModal').on('hidden.bs.modal', function() {
    $('#editUserModal').find('.overlay').show();
    $('#inputEditEmail').val("");
    $('#inputEditFirstName').val("");
    $('#inputEditLastName').val("");
    $('#isAdminEdit').attr('checked', false);
    $('#isTeacherEdit').attr('checked', false);
    $('#inputEditPassword').val("--------");
    $('#inputEditConfirmPassword').val("--------");
    $('#inputEditPassword').prop('disabled', true);
    $('#inputEditConfirmPassword').prop('disabled', true);
    $('#passwordChange').prop('checked', false);
});

$("#accountsTable").on("click", 'button[name="deleteAccount"]', function() {
    let button = $(this);
    $('#deleteUserModal').find('button[name="delete"]').attr('data-id', button.attr('data-id'));
    $('#deleteUserModal').modal('show');
});

$("#saveNewAccount").on("click", function() {
    addNewUser();  
});

function addNewUser()
{
   
    let emailValue = $('#inputEmail').val().trim();
    let firstnameValue = $('#inputFirstName').val().trim();
    let lastnameValue = $('#inputLastName').val().trim();
    let passwordValue = $('#inputPassword').val();
    let confirmPasswordValue = $('#inputConfirmPassword').val();
    let isAdminValue = $('#isAdmin').is(":checked") ? true : false;
    let isTeacherValue = $('#isTeacher').is(":checked") ? true : false;

    let errorMsg = null;
    if (!isMail(emailValue)) {
        errorMsg = $('.errorMail').html();
    }
    if (passwordValue != confirmPasswordValue) {
        errorMsg = $('.errorPassword').html();
    }
    if (passwordValue.length < 8) {
        errorMsg = $('.errorPasswordLength').html();
    }
    if (!isAdminValue && !isTeacherValue) {
        errorMsg = $('.errorRole').html();
    }
    if (emailValue == "" || firstnameValue == "" || lastnameValue == "" || passwordValue == "" || confirmPasswordValue == "") {
        errorMsg = $('.missingInput').html();
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    }

    $.post(
        'src/php/_ajax/ajax.queryUser.php',
        {
            data: {
                action: 'insert',
                email: emailValue,
                firstname: firstnameValue,
                lastname: lastnameValue,
                password: passwordValue,
                isAdmin: isAdminValue,
                isTeacher: isTeacherValue
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {

                    // Ausgabe der Erfolgs Nachricht
                    triggerResponseMsg('success', $('.successCreateUser').html());
                    if(true) {

                    }
                } else {
                    if (obj.error == "insert") {
                        triggerResponseMsg('error', $('.errorCreateUser').html());
                    } else {
                        triggerResponseMsg('error', $('.errorDuplicate').html());
                    }
                    
                }

                $("#addUserModal").modal("hide");
                reloadTable();

            } catch(e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorCreateUser').html());
            }
        }
    );
}

function getUserData(id) {
    $.post(
        'src/php/_ajax/ajax.getUser.php',
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
                    if (obj.user.is_admin == 1) {
                        $('#isAdminEdit').attr('checked', true);
                    }  else {
                        $('#isAdminEdit').attr('checked', false);
                    }

                    if (obj.user.is_teacher == 1) {
                        $('#isTeacherEdit').attr('checked', true);
                    } else {
                        $('#isTeacherEdit').attr('checked', false);
                    }
                    //Zurückbekommene Werte den Feldern zuteilen
                    $('#inputEditEmail').val(obj.user.email);
                    $('#inputEditFirstName').val(obj.user.first_name);
                    $('#inputEditLastName').val(obj.user.last_name);
                    $('#editUserModal').find('.overlay').fadeOut(500);
                } 
            } catch (e) {
                console.log(e);
            }
        }
    );
}

function editUser(id)
{
   
    let emailValue = $('#inputEditEmail').val().trim();
    let firstnameValue = $('#inputEditFirstName').val().trim();
    let lastnameValue = $('#inputEditLastName').val().trim();
    let passwordValue = $('#inputEditPassword').val();
    let confirmPasswordValue = $('#inputEditConfirmPassword').val();
    let isAdminValue = $('#isAdminEdit').is(":checked") ? true : false;
    let isTeacherValue = $('#isTeacherEdit').is(":checked") ? true : false;
    let changePassword = $('#passwordChange').is(":checked") ? true : false;

    let errorMsg = null;
    if (!isMail(emailValue)) {
        errorMsg = $('.errorMail').html();
    }
    if (passwordValue != confirmPasswordValue && changePassword) {
        errorMsg = $('.errorPassword').html();
    }
    if (passwordValue.length < 8 && changePassword) {
        errorMsg = $('.errorPasswordLength').html();
    }
    if (!isAdminValue && !isTeacherValue) {
        errorMsg = $('.errorRole').html();
    }
    if (emailValue == "" || firstnameValue == "" || lastnameValue == "" || (changePassword && (confirmPasswordValue == "" || passwordValue == ""))) {
        errorMsg = $('.missingInput').html();
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    }

    $.post(
        'src/php/_ajax/ajax.queryUser.php',
        {
            data: {
                id: id,
                action: 'update',
                email: emailValue,
                firstname: firstnameValue,
                lastname: lastnameValue,
                password: passwordValue,
                isAdmin: isAdminValue,
                isTeacher: isTeacherValue,
                changePassword: changePassword
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {

                    // Ausgabe der Erfolgs Nachricht
                    triggerResponseMsg('success', $('.successEditUser').html());
                } else {
                    if (obj.error == "update") {
                        triggerResponseMsg('error', $('.errorEditUser').html());
                    } else {
                        triggerResponseMsg('error', $('.errorDuplicate').html());
                    }
                    
                }

                $("#editUserModal").modal("hide");
                reloadTable();
                
            } catch(e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorEditUser').html());
            }
        }
    );
}

function deleteUser(id) 
{
    $.post(
        'src/php/_ajax/ajax.deleteUser.php',
        {
            data: {
                id: id    
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.success) {

                    // Ausgabe der Erfolgs Nachricht
                    triggerResponseMsg('success', $('.successDeleteUser').html());
                } else {
                        triggerResponseMsg('error', $('.errorDeleteUser').html());    
                }
                $("#deleteUserModal").modal("hide");
                reloadTable();
                
            } catch(e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorDeleteUser').html());
            }
        }
    )
}

function reloadTable() {
    $('#tableOverlay').fadeIn(500);
    setTimeout(function() { 
        accountsTable.ajax.reload(hideOverlay());
    }, 150);    
}

function hideOverlay() {
    $('#tableOverlay').fadeOut(500);
}
