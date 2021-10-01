let darkMode = getCookie("darkMode");
let darkModeActivated = false;

const url = window.location.search;
const urlParams = new URLSearchParams(url);

if (darkMode == "true") {
    darkModeActivated = true;
} else {
    darkModeActivated = false;
}

$(document).ready(function() {
    toggleDarkmode(darkModeActivated);
});

var $sidebar = $('.control-sidebar');
var $container = $('<div />', {
    class: 'p-3 control-sidebar-content'
});

$sidebar.append($container);

$container.append(
    '<h5>Customize Examinator</h5><hr class="mb-2"/>'
);

var $dark_mode_checkbox = $('<input />', {
    type: 'checkbox',
    value: 1,
    id: "chkDarkMode",
    checked: darkModeActivated,
    class: 'mr-1'
});

var $dark_mode_container = $('<div />', { class: 'mb-4' }).append($dark_mode_checkbox).append('<span>Dark Mode</span>');
$container.append($dark_mode_container);

$('#chkDarkMode').on('change', function() {
    if ($('#chkDarkMode').is(':checked')) {
        document.cookie = "darkMode=true";
        toggleDarkmode(true);
    } else {
        document.cookie = "darkMode=false";
        toggleDarkmode(false);
    }
});

function isMail(mail) {
    const regEx = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regEx.test(String(mail).toLowerCase());
}

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

function getPage() {
    return urlParams.get('page');
}

function toggleDarkmode(active) {
    if (active) {
        $('body').addClass('dark-mode');
    } else {
        $('body').removeClass('dark-mode');
    }
}