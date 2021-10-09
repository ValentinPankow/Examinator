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
    $('#accountsTable').DataTable({
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
                    accountsTable.ajax.reload();
                } else {
                    if (obj.error == "insert") {
                        triggerResponseMsg('error', $('.errorCreateUser').html());
                    } else {
                        triggerResponseMsg('error', $('.errorDuplicate').html());
                    }
                    
                }

                $("#addUserModal").modal("hide");
                
            } catch(e) {
                console.log(e);
                triggerResponseMsg('error', $('.errorCreateUser').html());
            }
        }
    );
}
