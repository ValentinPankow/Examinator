$(document).ready(function() {
    let helpText = "\
    <p><i class='fas fa-info-circle'></i> Favoriten sind dafür gedacht, seine Auswahl an Klassen oder Fächern auf die nötigsten zu beschränken. \
    dadurch werden auf der Seite 'Klausuren' sowie 'Klassen' nur noch seine eigenen relevanten ergebnisse angezeigt.</p>\
    <h5>Klassen</h5>\
    <p>Auf der linken Seite unter dem Abschnitt 'Favoritisierte Klassen' werden die aktuell aktiven Favoritisierten Klassen angezeigt. \
    diese können mit einem Klick auf den Schieberegler wieder defavorisiert werden.</p>\
    <p>Auf der rechten Seite stehen alle Klassen zur Auswahl, die aktuell nicht favorisiert sind.</p>\
    <h5>Fächer</h5>\
    <p>Bei den Fächern ist es genauso, wie mit den Klassen.</p>\
    <p><i class='fas fa-info-circle'></i> Mit einem Klick auf den unteren Button 'Änderungen speichern' werden alle vorgenommenen Änderungen gespeichert.</p>\
    ";
    $('#helpText').html(helpText);
});