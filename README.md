# Klausurenverwaltungssystem - Examinator

## Installationsanleitung

#### Voraussetzungen
- PHP > 8.0.0
- MariaDB / MySQL Server
- Apache2 / Nginx Webserver

#### Installation
Für die Installation muss das Verzeichnis „examinator“ in das vHosts Verzeichnis auf dem Webserver verschoben werden.

#### Konfiguration
Für die Einstellung muss einmal in der „config.php“ Datei, die in dem „examinator“ Verzeichnis liegt, die Einstellung „REDIRECT_URL“ so angepasst werden, dass die volle URL auf die Anwendung zeigt. Bsp.: 

- `define("REDIRECT_URL", "https://www.beispiel.de/");`

Das Protokoll https sollte immer angegeben und nicht durch http ausgetauscht werden. Zu beachten ist weiterhin, dass das „/“ am Ende auch angegeben werden muss.
Für die Datenbank Verbindung muss die Datei „db_config.php“ in „examinator/src/php/“ angepasst werden. 

#### Import der Datenbank
Die Datenbank kann anhand der Datei „examinator.sql“ im Verzeichnis „examinator/db/“ auf dem Datenbankserver importiert werden. Dafür muss vorher eine leere Datenbank erstellt werden.

#### Berechtigungen
Da für die Importfunktion durch PHP Dateien erstellt und verschoben werden achten Sie bitte darauf, dass PHP in dem Verzeichnis „examinator/dist/import/" lesen und schreiben kann.

#### Benutzer für erstmaligen Login
Für die erstmalige Verwendung wird ein bereits angelegter Benutzer mit Administrationsrechten bereitgestellt.
Die Daten für diesen Benutzer lauten:
- E-Mail-Adresse: examinator@bws-hofheim.de
- Passwort: bws#123!

Bitte ändern Sie im Anschluss das Passwort dieses Benutzers.
