# AGH-Dok-Mailer

Tool zum automatischen versenden von eMail-Benachrichtigungen, sobald neue Dokumente in der Dokumentationsdatenbank des Berliner Abgeordnetenhauses vorliegen.

Dabei kann wie in der Suche auf der Seite der [Parlamentsdokumentation](https://pardok.parlament-berlin.de/starweb/AHAB/servlet.starweb?path=AHAB/lissh.web) nach Schlagworten gefiltert werden. Weitere Filter sind denkbar, stellt dazu gerne Anfragen!

## Konfiguration
1. ```config.inc.php``` in ```config.php``` umbenennen
2. In der Datei Parameter eingeben, z. B. Mailzugangsdaten sowie Schlagwörter für die Parlamentsdokumentation
3. Cronjob einrichten
z. B. ```php -f /PFAD/ZUR/DATEI/AGH.php```

Entwickler: Julian von Bülow  
Lizenz: [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/deed.de)