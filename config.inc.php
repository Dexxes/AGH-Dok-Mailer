<?php
// AGH-Dok-Mailer
// Entwickler: Julian von Bülow
// Lizenz: CC BY-SA 4.0 | https://creativecommons.org/licenses/by-sa/4.0/deed.de

// Konfiguartionsvorlage

//Mehrere Schlagworte werden durch ein Semikolon mit anschließendem Leerzeichen abgetrennt, also "XXX; YYY; ZZZ"
define("SCHLAGWORTE", "");
define("SMTP_RECIPIENT", "");                               // Recipient of the notification mails

define("USE_LOCAL_MAILER", true);                           // the following SMTP-Settings will only be applied if this is set to false
define("SMTP_HOST", "");                                    // Specify main and backup SMTP servers
define("SMTP_PORT", 587);                                   // Port, 587 for TLS, 465 for SSL
define("SMTP_AUTH", true);                                  // Whether to enable SMTP authentication or not
define("SMTP_USER", "");                                    // SMTP username
define("SMTP_PASSWORD", "");                                // SMTP password
define("SMTP_SECURE", "tls");                               // Transport security like tls or ssl

define("MAIL_PREFIX", "");                                  // This prefix is put in the beginning of the mail subject to enable mail filtering, e. g.: "[AGH]"
define("MAIL_SUBJECT", "Neues aus dem Abgeordnetenhaus");
define("MAIL_FROM", "AGH-Dok-Mailer");                      // Friendly name of the sender
define("MAIL_FROM_ADDRESS", "no-reply@AGH-DOK-MAILER.xxx"); // Dummy sender mail address
?>