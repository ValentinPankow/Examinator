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
    } else {
        $('body').removeClass('dark-mode');
        $('.swal-popup').removeClass('swal-dark');
        $('#controlsBtn').empty();
        $('#controlsBtn').append("<i class='fas fa-sun'></i>");
    }
}