// VP Darkmode and functions that can be used on the entire web page

// Darkmode- Einstellungen aus Cookie holen
let darkMode = getCookie("darkMode");
let darkModeActivated = false;

// Url Informationen
const url = window.location.search;
const urlParams = new URLSearchParams(url);

// Prüfen ob Darkmode aktiviert ist
if (darkMode == "true") {
    darkModeActivated = true;
} else {
    darkModeActivated = false;
}

// Wenn die Seite fertig geladen hat
$(document).ready(function() {
    // Darkmode umschalten
    toggleDarkmode(darkModeActivated);
});

$('#btnHelp').on('click', function() {
    $('#helpModal').modal('show');
});

// Einstellungsseite für Preferenzen erstellen
let $sidebar = $('.control-sidebar');
let $container = $('<div />', {
    class: 'p-3 control-sidebar-content'
});
$sidebar.append($container);
$container.append(
    '<h5>Examinator anpassen</h5><hr class="mb-2"/>'
);

// Darkmode Checkbox erstellen
let $dark_mode_checkbox = $('<input />', {
    type: 'checkbox',
    value: 1,
    id: "chkDarkMode",
    checked: darkModeActivated,
    class: 'mr-1'
});
let $dark_mode_container = $('<div />', { class: 'mb-4' }).append($dark_mode_checkbox).append('<span>Dark Mode</span>');
$container.append($dark_mode_container);

// Wenn die Darkmode Checkbox angeklickt wurde
$('#chkDarkMode').on('change', function() {
    if ($('#chkDarkMode').is(':checked')) {
        document.cookie = "darkMode=true";
        toggleDarkmode(true);
        darkModeActivated = true;
    } else {
        document.cookie = "darkMode=false";
        toggleDarkmode(false);
        darkModeActivated = false;
    }
});

function triggerResponseMsg(icon, message) {
    Toast.fire({
        icon: icon,
        title: message
    })
    if (darkModeActivated) {
        $('.swal2-popup-custom').addClass('swal-dark');
    }
}

// Funktion zum prüfen der E-Mails auf validität
function isMail(mail) {
    const regEx = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regEx.test(String(mail).toLowerCase());
}


function formatDate(dateStr) {
    return dateStr.substring(8, 10) + "." + dateStr.substring(5, 7) + "." + dateStr.substring(0, 4);
}

// Funktion zum Cookie auslesen
// https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// https://stackoverflow.com/questions/2144386/how-to-delete-a-cookie
function deleteCookie( name, path, domain = false ) {
    if( getCookie( name ) ) {
        document.cookie = name + "=" +
        ((path) ? ";path="+path:"")+
        ((domain)?";domain="+domain:"") +
        ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
    }
}

// Funktion um die aktuelle Seite zu bekommen
function getPage() {
    return urlParams.get('page');
}

// Funktion zum umschalten des Darkmodes
function toggleDarkmode(active) {
    if (active) {
        $('body').addClass('dark-mode');
        $('.swal-popup').addClass('swal-dark');
        $('#controlsBtn').empty();
        $('#controlsBtn').append("<i class='far fa-moon'></i>");
        $('.main-header').addClass('navbar-dark');
        $('.main-header').removeClass('navbar-light');
    } else {
        $('body').removeClass('dark-mode');
        $('.swal-popup').removeClass('swal-dark');
        $('#controlsBtn').empty();
        $('#controlsBtn').append("<i class='fas fa-sun'></i>");
        $('.main-header').addClass('navbar-light');
        $('.main-header').removeClass('navbar-dark');
    }
}

// Toast
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

/*

    Usersettings

*/

$('#usersettingsPasswordChange').on('change', function() {
    if ($('#usersettingsPasswordChange').is(':checked')) {
        $('#usersettingsPassword').prop('disabled', false);
        $('#usersettingsPasswordConfirm').prop('disabled', false);
        $('#usersettingsCurrentPassword').prop('disabled', false);

        $('#usersettingsPassword').val('');
        $('#usersettingsPasswordConfirm').val('');
        $('#usersettingsCurrentPassword').val('');
    } else {
        $('#usersettingsPassword').prop('disabled', true);
        $('#usersettingsPasswordConfirm').prop('disabled', true);
        $('#usersettingsCurrentPassword').prop('disabled', true);

        $('#usersettingsPassword').val('--------');
        $('#usersettingsPasswordConfirm').val('--------');
        $('#usersettingsCurrentPassword').val('--------');
    }
});

$('#usersettingsModal').on('hidden.bs.modal', function() {
    $('#usersettingsModal').find('.overlay').fadeIn(500);

    $('#usersettingsPasswordChange').prop('checked', false);
    $('#usersettingsPassword').prop('disabled', true);
    $('#usersettingsPasswordConfirm').prop('disabled', true);
    $('#usersettingsCurrentPassword').prop('disabled', true);

    $('#usersettingsMail').val('');
    $('#usersettingsPassword').val('--------');
    $('#usersettingsPasswordConfirm').val('--------');
    $('#usersettingsCurrentPassword').val('--------');
});

$('#usersettingsModal').on('shown.bs.modal', function() {
    getUserSettingsData();
});

$('#saveUsersettings').on('click', function() {
    saveUsersettings();
});

$('#usersettingsMail, #usersettingsPassword, #usersettingsPasswordConfirm, #usersettingsCurrentPassword').keypress(function(e) {
    if(e.which == 13){
        saveUsersettings();
    }
});

function getUserSettingsData() {
    $.post(
        'src/php/_ajax/ajax.getUser.php',
        {
            data: {
                id: getCookie('UserLogin')
            },
        },
        function (rtn) {
            try {
                let obj = JSON.parse(rtn);
                console.log(obj);
                if (obj.success) {
                    $('#usersettingsMail').val(obj.user.email);
                    $('#usersettingsFirstname').val(obj.user.first_name);
                    $('#usersettingsLastname').val(obj.user.last_name);

                    $('#usersettingsModal').find('.overlay').fadeOut(500);
                } 
            } catch (e) {
                console.log(e);
            }
        }
    );
}

function saveUsersettings() {
    let pwd = $('#usersettingsPassword').val();
    let pwdConfirm = $('#usersettingsPasswordConfirm').val();
    let currentPwd = $('#usersettingsCurrentPassword').val();
    let mail = $('#usersettingsMail').val();
    let changePassword = $('#usersettingsPasswordChange').is(':checked') ? true : false;

    let errorMsg = null;
    if ($('#usersettingsPasswordChange').is(':checked')) {
        if (pwd.length < 8) {
            errorMsg = $('.errorPasswordLength').html();
        }
        if (pwd != pwdConfirm) {
            errorMsg = $('.errorPassword').html();
        }
        if (pwd.length <= 0 || pwdConfirm.length <= 0 || currentPwd.length <= 0 || mail.length <= 0) {
            errorMsg = $('.missingInput').html();
        }
    } else {
        if (!isMail(mail)) {
            errorMsg = $('.errorMail').html();
        }
        if (mail.length <= 0) {
            errorMsg = $('.missingInput').html();
        }
    }

    if (errorMsg != null) {
        triggerResponseMsg('error', errorMsg);
        return false;
    }

    $.post(
        'src/php/_ajax/ajax.usersettings.php',
        {
            data: {
                password: pwd,
                currentPassword: currentPwd,
                currentPwd: currentPwd,
                email: mail,
                changePassword: changePassword
            },
        },
        function(rtn) {
            try {
                obj = JSON.parse(rtn);
                if (obj.success) {
                    triggerResponseMsg('success', $('.usersettingsSuccess').html());
                    $('#usersettingsMail').val('');
                    $('#usersettingsPassword').val('');
                    $('#usersettingsPasswordConfirm').val('');
                    $('#usersettingsCurrentPassword').val('');

                    $('#usersettingsPasswordChange').prop('checked', false);
                    $('#usersettingsPassword').prop('disabled', true);
                    $('#usersettingsPasswordConfirm').prop('disabled', true);
                    $('#usersettingsCurrentPassword').prop('disabled', true);

                    $('#usersettingsMail').val('');
                    $('#usersettingsPassword').val('--------');
                    $('#usersettingsPasswordConfirm').val('--------');
                    $('#usersettingsCurrentPassword').val('--------');

                    $('#usersettingsModal').find('.overlay').fadeIn(500);
                    getUserSettingsData();
                } else {
                    if (obj.error == "duplicate") {
                        triggerResponseMsg('error', $('.errorDuplicate').html());
                    } else if (obj.error = "wrong_pwd") {
                        triggerResponseMsg('error', $('.currentPwdError').html());
                    } else {
                        triggerResponseMsg('error', $('.usersettingsError').html());
                    }
                }
            } catch(e) {

            }
        }
    )
}