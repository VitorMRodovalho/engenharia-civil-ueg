<?php

define( "LM_SUBSCRIBE_SUBJECT", "Anmeldung zum Newsletter auf der Seite [mosConfig_live_site]" );
define( "LM_SUBSCRIBE_MESSAGE", 
"Hallo [NAME],

die Anmeldung zum Newsletter auf \"[mosConfig_live_site]\" war erfolgreich.
Danke!

Um die Anmeldung zu bestätigen, ist es notwendig, dem unteren Link zu folgen
oder zu kopieren, in die Adresszeile des Browsers hineinzukopieren und die Adresse aufzurufen.

[LINK]

_________________________
[mosConfig_live_site]" );

define( "LM_UNSUBSCRIBE_SUBJECT", "Newsletter Service auf der Webseite \"[mosConfig_live_site]\": Abmeldung" );
define( "LM_UNSUBSCRIBE_MESSAGE", 
"Hallo [NAME],

die Abmeldung vom Newsletter Service auf der Webseite \"[mosConfig_live_site]\" war erfolgreich.
Vielen Dank für die Nutzung dieses Service!

________________________
[mosConfig_live_site]" );

define( "LM_NEWSLETTER_FOOTER", 
"<br/><br/>___________________________________________________________<br/><br/>
Sie erhalten diesen Newsletter, weil Sie sich bei unserem<br/>
Newsletter Service auf [mosConfig_live_site] angemeldet haben.<br/>
Um diesen Newsletter nicht mehr zu erhalten, können Sie sich hier abmelden: [UNLINK]" );


/* Module */
define( "LM_FORM_NOEMAIL", "Bitte geben Sie eine gültige Email-Adresse an." );
define( "LM_FORM_SHORTERNAME", "Bitte verwenden Sie einen kürzeren Namen. Danke." );
define( "LM_FORM_NONAME", "Bitte geben Sie Ihren Namen an. Danke." );
define( "LM_SUBSCRIBE", "Anmelden" );
define( "LM_UNSUBSCRIBE", "Abmelden" );
define( "LM_BUTTON_SUBMIT", "Los!" );

/* Backend */
define( "LM_ERROR_NEWSLETTER_COULDNTBESENT", "Newsletter konnte nicht versendet werden!" );
define( "LM_NEWSLETTER_SENDTO_X_USERS", "Newsletter an {X} Nutzer versendet" );
define( "LM_IMPORT_USERS", "Nutzer Importieren" );
define( "LM_EXPORT_USERS", "Nutzer Exportieren" );
define( "LM_UPLAOD_FAILED", "Upload fehlgeschlagen" );
define( "LM_ERROR_PARSING_XML", "Fehler beim Einlesen der XML Datei!" );
define( "LM_ERROR_NO_XML", "Bitte nur XML-Dateien hochladen." );
define( "LM_ERROR_EMAIL_ALREADY_ONLIST", "Die Email-Adresse ist in der Datenbank bereits enthalten." );
define( "LM_SUCCESS_ON_IMPORT", "Erfolgreich {X} Nutzer importiert." );
define( "LM_IMPORT_FINISHED", "Import fertiggestellt." );
define( "LM_ERROR_DELETING_FILE", "Löschen der Datei fehlgeschlagen." );
define( "LM_DIR_NOT_WRITABLE", "Kann nicht in folgendes Verzeichnis schreiben: ".$GLOBALS['mosConfig_cachepath'] );
define( "LM_ERROR_INVALID_EMAIL", "Ungültige Email-Adresse" );
define( "LM_ERROR_EMPTY_EMAIL", "Leere Email address" );
define( "LM_ERROR_EMPTY_FILE", "Fehler: leere Datei" );
define( "LM_ERROR_ONLY_TEXT", "Nur Text" );

define( "LM_SELECT_FILE", "Bitte eine Datei angeben" );
define( "LM_YOUR_XML_FILE", "Ihre YaNC/Letterman XML Export Datei" );
define( "LM_YOUR_CSV_FILE", "CSV Import-Datei" );
define( "LM_POSITION_NAME", "Position des -Name- Feldes" );
define( "LM_NAME_COL", "Spalte -Name-" );
define( "LM_POSITION_EMAIL", "Position des -Email- Feldes" );
define( "LM_EMAIL_COL", "Spalte -Email-" );
define( "LM_STARTFROM", "Fange an von Zeile...zu lesen" );
define( "LM_STARTFROMLINE", "Start von Zeile" );
define( "LM_CSV_DELIMITER", "CSV Trennzeichen" );
define( "LM_CSV_DELIMITER_TIP", "CSV Trennzeichen: , ; oder Tabulator" );

/* Newsletter Management */
define( "LM_NM", "Newsletter Verwaltung" );
define( "LM_MESSAGE", "Nachricht" );
define( "LM_LAST_SENT", "zuletzt gesendet" );
define( "LM_SEND_NOW", "Jetzt senden" );
define( "LM_CHECKED_OUT", "gesperrt" );
define( "LM_NO_EXPIRY", "läuft aus: keine Begrenzung" );
define( "LM_WARNING_SEND_NEWSLETTER", "Sind Sie sicher, dass Sie den Newsletter verschicken wollen?\\nAchtung: Sollte der Newsletter an eine große Anzahl \\nvon Nutzern geschickt werden, kann dies eine Weile dauern!" );
define( "LM_SEND_NEWSLETTER", "Newsletter verschicken" );
define( "LM_SEND_TO_GROUP", "An folgende Gruppe senden" );
define( "LM_MAIL_FROM", "'Von'-Feld" );
define( "LM_DISABLE_TIMEOUT", "PHP-Timeout ausschalten" );
define( "LM_DISABLE_TIMEOUT_TIP", "Verhindert, dass die Ausführung des Skriptes nach einer bestimmten Zeit abgebrochen wird. <br/><strong>Wirkungslos bei safe_mode=On<strong>" );
define( "LM_REPLY_TO", "Rückantwort an" );
define( "LM_MSG_HTML", "Nachricht (HTML-WYSIWYG)" );
define( "LM_MSG", "Nachricht (HTML-Quellcode)" );
define( "LM_TEXT_MSG", "alternative Nachricht, Nur Text" );
define( "LM_NEWSLETTER_ITEM", "Newsletter-Artikel" );

/* Subscriber Management */
define( "LM_SUBSCRIBER", "Nutzer" );
define( "LM_NEW_SUBSCRIBER", "Neuer Nutzer" );
define( "LM_EDIT_SUBSCRIBER", "Nutzer ändern" );
define( "LM_SELECT_SUBSCRIBER", "Einen Nutzer auswählen" );
define( "LM_SUBSCRIBER_NAME", "Nutzername" );
define( "LM_SUBSCRIBER_EMAIL", "Nutzer - Email" );
define( "LM_SIGNUP_DATE", "Anmeldedatum" );
define( "LM_CONFIRMED", "Bestätigt" );
define( "LM_SUBSCRIBER_SAVED", "Die Nutzerinformationen wurden gespeichert" );
define( "LM_SUBSCRIBERS_DELETED", "Es wurden erfolgreich {X} Nutzer von der Newsletter-Liste gelöscht" );
define( "LM_SUBSCRIBER_DELETED", "Der Nutzer wurde erfolgreich gelöscht." );

/* Frontend */
define( "LM_ALREADY_SUBSCRIBED", "Sie sind bereits Teilnehmer unseres Newsletter-Services." );
define( "LM_NOT_SUBSCRIBED", "Sie sind leider NICHT Teilnehmer unseres Newsletter-Services." );
define( "LM_YOUR_DETAILS", "Ihre Details:" );
define( "LM_SUBSCRIBE_TO", "Melden Sie sich zu unserem Newsletter an" );
define( "LM_UNSUBSCRIBE_FROM", "Melden Sie sich von unserem Newsletter ab" );
define( "LM_VALID_EMAIL_PLEASE", "Bitte geben Sie eine gültige Email-Adresse an!" );
define( "LM_SAME_EMAIL_TWICE", "Die angegebene Email-Adress exisitiert bereits in unserer Nutzerliste! Wählen Sie eine andere" );
define( "LM_ERROR_SENDING_SUBSCRIBE", "Eine Nachricht über die Teilnahme am Newsletter-Service konnte nicht versendet werden:" );
define( "LM_SUCCESS_SUBSCRIBE", "Ihre Email-Adresse wurde unserer Newsletter-Liste hinzugefügt." );
define( "LM_RETURN_TO_NL", "Zurück zu unseren Newslettern" );
define( "LM_ERROR_UNSUBSCRIBE_OTHER_USER", "Die Email-Adresse konnte nicht aus der Nutzerliste gelöscht werden." );
define( "LM_ERROR_SENDING_UNSUBSCRIBE", "Eine Nachricht über die Abmeldung vom Newsletter-Service konnte nicht versendet werden:" );
define( "LM_SUCCESS_UNSUBSCRIBE", "Die Email-Adresse wurde von unserer Newsletter-Nutzerliste gelöscht." );
define( "LM_SUCCESS_CONFIRMATION", "Ihre Newsletter-Anmeldung wurde erfolgreich bestätigt." );
define( "LM_ERROR_CONFIRM_ACC_NOTFOUND", "Der Account, mit dem Ihre Bestätigung verknüpft ist, wurde leider nicht gefunden." );

define( "LM_CONFIRMED_ACCOUNTS_ONLY", "Nur an bestätigte Accounts?" );
define( "LM_CONFIRMED_ACCOUNTS_ONLY_TIP", "Versendet den Newsletter nur an <strong>bestätigte</strong> Newsletter-Accounts. <br/>Newsletter-Teilnehmer, die Ihre Bestätigung nicht durchgeführt haben, werden den Newsletter nicht erhalten." );

define( "LM_NAME_TAG_USAGE", "Im Newsletter-Text kann der Platzhalter <strong>[NAME]</strong> verwendet werden, um z.B. ein persönliche Anrede zu verwenden.<br/>Beim Versand des Newsletters wird <strong>[NAME]</strong> mit dem jeweiligen Namen des Nutzers ersetzt." );

define( "LM_USERS_TO_SUBSCRIBERS", "Nutzer zur Newsletter-Anmeldung hinzufügen" );
define( "LM_ASSIGN_USERS", "Nutzer zuweisen" );

?>

