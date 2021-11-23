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
    $('#forgotPwd').tooltip();

    let helpText = "\
    <h5>Klassenlogin</h5>\
    <p>Der Login der Klasse funktioniert mit der Eingabe des Klassennamens und des Passwortes der Klasse.</p>\
    <h5>Benutzerlogin</h5>\
    <p>Der Login des Benutzers funktioniert mit der Eingabe der E-Mail Adresse sowie des Passwortes des Benutzers.</p>\
    <h5>Passwort vergessen?</h5>\
    <p>Im Falle das ein Benutzer nicht mehr auf seinen Account zugreifen kann, muss er sich an einen Administrator wenden.</p>\
    ";
    $('#helpText').html(helpText);
});

$('#loginBtn').on('click', function() {
   login();
});

$('#inputPassword').keypress(function(e) {
    if(e.which == 13){
        login();
    }
});

function login(){
    $.post(
        'src/php/_ajax/ajax.login.php',
        {
            data: {
                user: $('#inputUser').val().trim(),
                password: $('#inputPassword').val()
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                console.log(obj);
                if (obj.success) {
                    triggerResponseMsg('success', $('.loginSuccess').html());
                    location.href = "?page=dashboard";
                } else {
                    triggerResponseMsg('error', $('.loginError').html());
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
}