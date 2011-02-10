<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.2
	
This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
	
> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

/* Translation by Dierk Bandow <dierk@dbb-web.de> */

// System wide messages
$ccms['lang']['system']['error_database'] = "Die Verbindung zur Datenbank konnte nicht hergestellt werden. Bitte die Login-Details und das Datenbank-Passwort überprüfen.";
$ccms['lang']['system']['error_openfile'] = "Kann die angegebene Datei nicht öffnen";
$ccms['lang']['system']['error_notemplate'] = "Es stehen noch keine Templates zur Verfügung.  mindestens 1 Template nach ./lib/templates/ hinzufügen.";
$ccms['lang']['system']['error_templatedir'] = /* GER */ "Der Vorlagen-Verzeichnis konnte nicht gefunden werden! Stellen Sie sicher, dass es existiert und mindestens eine Vorlage enthält.";
$ccms['lang']['system']['error_write'] = "Datei hat keine Schreibberechtigung";
$ccms['lang']['system']['error_dirwrite'] = /* GER */ "Keine Schreibrechte zum Verzeichnis";
$ccms['lang']['system']['error_chmod'] = "Die Datei kann nicht geändert werden. Überprüfen Sie die Dateiberechtigung (666).";
$ccms['lang']['system']['error_value'] = "Fehler: falsche Eingabe";
$ccms['lang']['system']['error_default'] = "Homepage kann nicht gelöscht werden.";
$ccms['lang']['system']['error_forged'] = "Wert nicht vorhanden";
$ccms['lang']['system']['error_filedots'] = "Dateinamen dürfen keine Punkte enthalten, z.B. '.html'.";
$ccms['lang']['system']['error_filesize'] = "Dateiname muss mind. 3 Zeichen lang sein.";
$ccms['lang']['system']['error_filesize_2'] = /* GER */ "Der Dateiname sollte höchstens 50 Zeichen lang sein.";
$ccms['lang']['system']['error_pagetitle'] = "Bitte einen Seitentitel von mind. 3 Zeichen eingeben.";
$ccms['lang']['system']['error_pagetitle_2'] = /* GER */ "Geben Sie bitte einen Seitentitel von 100 Zeichen oder weniger ein.";
$ccms['lang']['system']['error_subtitle'] = "Bitte einen kurzen Untertitel für die Seite erstellen.";
$ccms['lang']['system']['error_subtitle_2'] = /* GER */ "Geben Sie bitte einen kurzen Sub-Titel von 200 Zeichen oder weniger für Ihre Seite ein.";
$ccms['lang']['system']['error_description'] = "Die Beschreibung der Seite muss ist zu kurz";
$ccms['lang']['system']['error_description_2'] = /* GER */ "Geben Sie bitte eine Beschreibung von weniger als 250 Zeichen ein";
$ccms['lang']['system']['error_reserved'] = "Der eingegebene Dateiname ist für den internen Gebrauch reserviert.";
$ccms['lang']['system']['error_general'] = "Ein Fehler ist aufgetreten";
$ccms['lang']['system']['error_correct'] = "Bitte folgendes berichtigen:";
$ccms['lang']['system']['error_create'] = "Fehler bei der Erstellung einer neuen Datei";
$ccms['lang']['system']['error_exists'] = "Der Dateiname besteht bereits.";
$ccms['lang']['system']['error_delete'] = "Fehler beim Löschen der ausgewählten Datei";
$ccms['lang']['system']['error_selection'] = "Es wurde keine Datei ausgewählt.";
$ccms['lang']['system']['error_versioninfo'] = "Keine Versionsinformationen verfügbar.";
$ccms['lang']['system']['error_misconfig'] = "<strong>Es scheint ein Fehler bei der Konfiguration vorzuliegen.</strong><br/>Die .htaccess Datei muss korrekt konfiguriert sein um die Dateistruktur wiederzugeben. Wenn<br/>CompactCMS in ein Unterverzeichnis installiert wurde, muss die .htaccess Datei dementsprechend angepasst werden.";
$ccms['lang']['system']['error_deleted'] = "<h1>Die ausgew&auml;hlte Datei schein gel&oouml;scht worden zu sein</h1><p>Um diesen Fehler zu vermeiden, Dateiliste erneut aufrufen um die Liste der letzten verf&uuml;gbaren Dateien anzuzeigen. Falls dieses Vorgehen das Problem nicht löst, den Ordner per Hand öffnen und nachsehen, ob die Datei überhaubt existiert.</p>";
$ccms['lang']['system']['error_404title'] = "Datei nicht gefunden";
$ccms['lang']['system']['error_404header'] = "Ein 404 Fehler ist aufgetreten, die angeforderte Datei <strong>{%pagereq%}.html</strong> konnte nicht gefunden werden.";
$ccms['lang']['system']['error_404content'] = /* GER */ "Die angeforderte Datei <strong>{%pagereq%}.html</strong> konnte nicht gefunden werden.";
$ccms['lang']['system']['error_403title'] = /* GER */ "Zutritt verboten";
$ccms['lang']['system']['error_403header'] = /* GER */ "Ein 403 Fehler: Sie haben keine Berechtigung die angeforderte Datei zu sehen.";
$ccms['lang']['system']['error_403content'] = /* GER */ "Sie sind nicht berechtigt, den Zugang zum <strong>{%pagereq%}.html</strong> in diesem Moment zu erhalten.";
$ccms['lang']['system']['error_sitemap'] = "Seitenübersicht";
$ccms['lang']['system']['error_tooshort'] = /* GER */ "Ein oder mehrere vorgelegten Werte sind entweder zu kurz oder falsch eingegeben";
$ccms['lang']['system']['error_passshort'] = /* GER */ "Ein Passwort sollte mindestens 6 Stellen lang sein";
$ccms['lang']['system']['error_passnequal'] = /* GER */ "Die eingegebenen Passwörter stimmen nicht überein";
$ccms['lang']['system']['noresults'] = /* GER */ "Keine Ergebnisse";
$ccms['lang']['system']['tooriginal'] = "zurück zum Original";
$ccms['lang']['system']['message_rights'] = "Alle Rechte vorbehalten";
$ccms['lang']['system']['message_compatible'] = "Erfolgreich getestet mit";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = "Bei Vorschl&auml;gen, Feedback oder Schwierigkeiten  <a href='http://community.compactcms.nl/forum/' title='Das offizielle CCMS-forum' class='external' rel='external'>gehe zum Forum</a>!";
$ccms['lang']['backend']['ordertip'] = "Drop-downs benutzen, um die Struktur der Seite im Menü wiederzugeben.";
$ccms['lang']['backend']['createtip'] = "Um eine neue Seite zu erstellen, sind die Felder auszufüllen. Nach der Erstellung der Seite kann diese wie üblich bearbeitet werden.";
$ccms['lang']['backend']['currentfiles'] = "In der Auflistung unten sind alle erstellten Seiten zu finden. Die Startseite kann grundsätzlich nicht gelöscht werden. Einige Seiten können nur vom Administrator verändert werden, da dieser allein die Berechtigungen vergibt.";
$ccms['lang']['backend']['confirmdelete'] = "Bitte bestätigen, dass alle Seiten und der gesamte Inhalt gelöscht werden sollen.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Bitte bestätigen Sie, dass Sie alle Vorschaubilder regenerieren möchten.";
$ccms['lang']['backend']['settingssaved'] = /* GER */ "Ihre Änderungen wurden erfolgreich gespeichert.";
$ccms['lang']['backend']['must_refresh'] = /* GER */ "Bitte laden Sie die Hauptseite mittels Funktionstaste F5 um sicher zu stellen daß <strong>alle</strong> Ihre Änderungen sichbar sein.";
$ccms['lang']['backend']['itemcreated'] = /* GER */ "Die eingereichten Artikel sind erfolgreich bearbeitet.";
$ccms['lang']['backend']['fullremoved'] = /* GER */ "Das(Die) ausgewählte Element(en) ist(sind) erfolgreich gelöscht.";
$ccms['lang']['backend']['fullregenerated'] = /* GER */ "Die Thumbnails sind erfolgreich regeneriert.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Zurück zur Übersicht";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Stellen Sie CCMS Berechtigungen";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definieren Sie Content-Eigentümer";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Vorlagen-Editor";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Benutzerverwaltung";
$ccms['lang']['backend']['changevalue'] = "Änderung bestätigen";
$ccms['lang']['backend']['previewpage'] = "Ansicht";
$ccms['lang']['backend']['editpage'] = "Bearbeiten";
$ccms['lang']['backend']['restrictpage'] = "Eingeschränkt";
$ccms['lang']['backend']['newfiledone'] = "Diese Datei ist bereit mit Inhalt gefüllt zu werden!";
$ccms['lang']['backend']['newfilecreated'] = "Die Datei wurde angelegt!";
$ccms['lang']['backend']['startedittitle'] = "Mit der Eingabe beginnen!";
$ccms['lang']['backend']['starteditbody'] = "Die neue Datei wurde angelegt. Entweder sofort mit der Bearbeitung beginnen oder weitere Seiten hinzufügen.";
$ccms['lang']['backend']['success'] = "Erfolg!";
$ccms['lang']['backend']['fileexists'] = "Datei besteht bereits";
$ccms['lang']['backend']['statusdelete'] = "Status des Löschvorgangs:";
$ccms['lang']['backend']['statusremoved'] = "entfernt";
$ccms['lang']['backend']['uptodate'] = "aktuell.";
$ccms['lang']['backend']['outofdate'] = "veraltet.";
$ccms['lang']['backend']['considerupdate'] = "Update erwägen";
$ccms['lang']['backend']['orderprefsaved'] = "Die Reihenfolge der Menüpunkte wurde gespeichert.";
$ccms['lang']['backend']['inmenu'] = "Im Menü";
$ccms['lang']['backend']['updatelist'] = "Item";
$ccms['lang']['backend']['administration'] = "Administraton";
$ccms['lang']['backend']['currentversion'] = "Die verwendete Version is";
$ccms['lang']['backend']['mostrecent'] = "Die letzte stabile CompactCMS Version ist";
$ccms['lang']['backend']['versionstatus'] = "Diese Installation ist";
$ccms['lang']['backend']['createpage'] = "Seite hinzufügen";
$ccms['lang']['backend']['managemenu'] = "Menü bearbeiten";
$ccms['lang']['backend']['managefiles'] = "Seiten verwalten";
$ccms['lang']['backend']['delete'] = "Entfernen";
$ccms['lang']['backend']['toplevel'] = "Top Level";
$ccms['lang']['backend']['sublevel'] = "Sub Level";
$ccms['lang']['backend']['active'] = "Aktiv";
$ccms['lang']['backend']['disabled'] = "Ausgeschaltet";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Vorlage";
$ccms['lang']['backend']['notinmenu'] = "Punkt erscheint nicht im Menü";
$ccms['lang']['backend']['menutitle'] = "Menü";
$ccms['lang']['backend']['linktitle'] = "Link";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "Artikel";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Keine";
$ccms['lang']['backend']['yes'] = "Ja";
$ccms['lang']['backend']['no'] = "Nein";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Übersetzungen";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Hallo";
$ccms['lang']['backend']['logout'] = "Log-out";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Siehe Forum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Verwenden Sie einen gültigen Benutzernamen und Passwort zu Ende Zugriff auf die Back-CompactCMS. Wenn Sie hier angekommen versehentlich Rückkehr zur <a href='admin/includes/'>Startseite</a>.</p> <p>Kontaktieren Sie Ihren Webmaster für Ihre Daten.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Benutzername";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Passwort";
$ccms['lang']['login']['login'] = "Login";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Bitte geben Sie Ihre Anmeldeinformationen";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Geben Sie beide Ihrem Benutzernamen und Passwort";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Geben Sie Ihren Benutzernamen";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Geben Sie Ihr Passwort";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Dieses Konto wurde deaktiviert";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Beachten Sie, dass Sie bereits mehrere Versuche";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Benutzername oder Passwort falsch";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = "Main";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Links";
$ccms['lang']['menu']['3'] = "Right";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Fußzeile";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = "Dateiname";
$ccms['lang']['forms']['pagetitle'] = "Seitentitel";
$ccms['lang']['forms']['subheader'] = "Subheader";
$ccms['lang']['forms']['description'] = "Beschreibung";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Modul";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "Content Item (Standard)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Zugänge";
$ccms['lang']['forms']['printable'] = "Druckbar";
$ccms['lang']['forms']['published'] = "Aktiv";
$ccms['lang']['forms']['iscoding'] = "Code";
$ccms['lang']['forms']['createbutton'] = "Erstellen!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Ändern";
$ccms['lang']['forms']['savebutton'] = "Speichern";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-End-Sprache";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "Momentan sind wir nur zu zeigen, Seiten, die mindestens diesen Text hier haben";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Bearbeiten oder Entfernen Filter für";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Schreibe Filter für";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = "Dateiname (home.html) :: Dateiname, mit dem die Datei aufgerufen wird (ohne .html)";
$ccms['lang']['hints']['pagetitle'] = "Seitentitel (Home) :: Titel dieser Seite.";
$ccms['lang']['hints']['subheader'] = "Kurzer Headertext (Willkommen auf unserer Homepage) :: Kurzer beschreibender Text, der sowohl im Header als auch im Titel jeder Seite erscheint.";
$ccms['lang']['hints']['description'] = "Meta description :: Meta description für diese Seite. Wird in der Seitenspezifischen 'meta description' benutzt.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Module :: Wählen Sie, welches Modul den Inhalt dieser Datei behandeln soll. Wenn Sie unsicher sind, wählen Sie die Standardeinstellung.";
$ccms['lang']['hints']['printable'] = "Druckfreundliche Seite :: Bei 'Ja' wird eine druckfreundliche Seite generiert. 'Nein' sollte bei Seiten mit Fotos und/oder anderen Medien ausgewählt werden.";
$ccms['lang']['hints']['published'] = "Veröffentlicht :: Diesen Punkt auswählen, wenn die Seite veröffentlicht werden soll. Sie ist dann für die Öffentlichkeit sichtbar.";
$ccms['lang']['hints']['toplevel'] = "Top Level :: Setzt die Seite im Menü an die erste Stelle. N.I.M. auswählen, wenn die Seite nicht im Menü erscheinen soll.";
$ccms['lang']['hints']['sublevel'] = "Sub Level :: 0 auswählen, wenn die Seite als 'Top Level' Seite erscheinen soll. Sonst den entsprechenden Untermenüpunkt auswählen.";
$ccms['lang']['hints']['template'] = "Kategorie :: Bei der Verwendung mehrerer Templates können hier die Templates den einzelnen Seiten zugeordnet werden.";
$ccms['lang']['hints']['activelink'] = "Aktiver Link im menu? :: Nicht alle Menüpunkte benötigen einen aktiven Link (versteckte Seiten). Um den Link zu deaktivieren, muss der Haken aus der Checkbox entfernt werden.";
$ccms['lang']['hints']['menuid'] = "Menü Kategorie :: Hier auswählen, in welchem Menü der Menüpunkt erscheinen soll. Standard ist das Hauptmenü (1), in dem auch die Startseite erscheint.";
$ccms['lang']['hints']['iscoding'] = "Code :: Soll die Datei Code (PHP, Javascript) enthalten, 'Ja' ausählen. Der WISYWIG-Editor wird ausgeschaltet.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br>Sie können das Klicken auf <span class='sprite livefilter livefilter_active'> das Symbol Filter auf der linken Seite auf den Titel hinzufügen, bearbeiten oder entfernen Text Liste zu filtern die Seite, zB wenn Sie 'Heimat' in das Symbol Eingabefeld erscheint der die klicken, wenn Sie, drücken Sie die Eingabetaste / Return-Taste, nur Seiten, die 'den Text' Heimat in dieser Spalte angezeigt. <br>Ein Klick auf das Icon wieder und Löschen von Text in das Eingabefeld, das Drücken der Enter / Return, wird den Filter entfernen.<br>Mauszeiger über das Symbol Filter, um zu sehen, ob die Spalte wird derzeit filtriert, und wenn ja, mit welchem Filter Text.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = "Editor schließen";
$ccms['lang']['editor']['editorfor'] = "Texteditor für";
$ccms['lang']['editor']['instruction'] = "Editor zum ändern der Seite. Nach der Änderung auf 'Ändern' klicken um die Seite sofort im www zu publizieren.";
$ccms['lang']['editor']['savebtn'] = "Änderung übernehmen";
$ccms['lang']['editor']['cancelbtn'] = "Abbrechen";
$ccms['lang']['editor']['confirmclose'] = "Fenster schließen und Änderungen verwerfen?";
$ccms['lang']['editor']['preview'] = "Voransicht";
$ccms['lang']['editor']['savesuccess'] = "<strong>Erfolg!</strong> Der Inhalt wurde Gespeichert nach";
$ccms['lang']['editor']['backeditor'] = "Zurück zum Editor";
$ccms['lang']['editor']['closewindow'] = "Fenster schließen";
$ccms['lang']['editor']['keywords'] = "Keywords - <em>getrennt durch Kommata, maximaal 250 Zeichen</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto erzeugen ein sicheres Passwort";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Ihre aktuelle Rechnung Ebene ermöglicht es Ihnen nicht um dieses Feature nutzen.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Erstellen Sie neue Back-up";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "Um möglichen Datenverlust durch was auch immer externes Ereignis, ist es sinnvoll zu erstellen Backups Ihrer Dateien regelmäßig.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Warnung :: Bitte beachten Sie, dass Ihr <dfn>Leuchtkasten</dfn> Alben 'Bilder sind <strong>nicht</strong> gesichert Beschreibungen! Das Album <strong>sind</strong>, aber die Bilder selbst und ihre Thumbnails werden <strong>nicht Sicherungen, die in diese</strong>mit. Wenn Sie Backups von denen, dann brauchen Sie verleihen Ihr Site-Administrator über einen zusätzlichen Backup-System, damit Sie Backup-und Restore diese (möglicherweise großen) Datei Sammlungen.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Erhältlich Back-ups";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "Back-up-Dateinamen";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Download-Archiv";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Bitte warten Sie während der Datensicherung erstellt wird ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Erstellen Sie einen Benutzer";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Übersicht CCMS Benutzer";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Benutzer bearbeiten die persönlichen Daten";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Bearbeiten Kennwort des Benutzers";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Konto-Einstellungen";
$ccms['lang']['users']['user'] = "User";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Benutzername";
$ccms['lang']['users']['name'] = "Name";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Vorname";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Nachname";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Passwort";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Kennwort bestätigen";
$ccms['lang']['users']['email'] = /* BABELFISH */ "E-Mail";
$ccms['lang']['users']['active'] = "Active";
$ccms['lang']['users']['level'] = "Level";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "User-Ebene";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Letzter Login";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Vorlagen verwalten";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Die aktuelle Vorlage ist <strong>nicht</strong> beschreibbar";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Drucken";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Permission Präferenzen";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Verwenden Sie die Tabelle unten, um festzulegen, welche Mindestanforderungen Benutzer-Ebene können bestimmte Funktionen nicht nutzen. Jeder Benutzer unter dem angegebenen minimal erforderliche Benutzer-Ebene, nicht sehen, noch haben Zugriff auf die Funktion.";
$ccms['lang']['permission']['target'] = "Target";
$ccms['lang']['permission']['level1'] = "Level 1 - User";
$ccms['lang']['permission']['level2'] = "Level 2 - Editor";
$ccms['lang']['permission']['level3'] = "Level 3 - Manager";
$ccms['lang']['permission']['level4'] = "Level 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Eigentümer von Inhalten";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Hier können Sie bestimmte Seite Betriebskosten für einzelne Benutzer zu ernennen. Wenn für eine bestimmte Seite keine Benutzer ausgewählt werden, kann jeder Änderung der Seite. Ansonsten wird nur der angegebene Benutzer hatte Modifikation Rechte an der Datei. Administratoren haben immer Zugriff auf alle Dateien.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Seiten";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Benutzer";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Übersetzen";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Zeigt die Übersetzungs-Strings.";

// Album messages
$ccms['lang']['album']['album'] = "Album";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Album-Einstellungen";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "Insbesondere gilt dieses Album";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Album Beschreibung";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Aktuelle Alben";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Inhalte hochladen";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Hochladen, um vorhandenes Album";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Upload starten";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Durchsuchen von Dateien";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Liste löschen";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Single File-Upload</strong><br> <p>Die Flash-Loader konnte nicht initialisiert werden. Machen Sie sicher Javascript aktiviert ist und Flash installiert ist. Einzelne Datei-Uploads sind möglich, aber nicht optimiert.</p>";
$ccms['lang']['album']['manage'] = "Manage albums";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Alben-Liste";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Regenerieren alle Vorschaubilder";
$ccms['lang']['album']['newalbum'] = "New album name";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Keine Alben wurden noch erstellt!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Dateien";
$ccms['lang']['album']['nodir'] = "Please make sure the directory <strong>albums</strong> exists in your image directory";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Zuletzt geändert";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Bitte warten ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Verwalten Sie aktuelle Nachrichten";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Schreibe Nachrichten";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Neuen Artikel schreiben";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Verwalten von Einstellungen";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Schreiben Sie Nachrichten";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# Nachrichten auf Front-End";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Zeige Autor";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Show Veröffentlichungsdatum";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Nur zeigen Teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Titel der Nachricht";
$ccms['lang']['news']['author'] = /* BABELFISH */ "News Autor";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Datum";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Veröffentlicht?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Artikel Inhalt";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Zeige das Archiv";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Keine Reaktionen wurden noch nicht veröffentlicht!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Um sicherzustellen, dass diese Nachricht nicht automatisiert ist, wenden Sie sich bitte erneut eingeben";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reaktion";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Bewertung";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com Benutzer avatar";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "schrieb";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Verwalten Reaktionen";
$ccms['lang']['guestbook']['delentry'] = "Delete this entry";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-Mail-Autor";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "aus der Datenbank entfernt worden.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Ihr Name";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Ihre E-Mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Ihre Website";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Kommentare";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Überprüfung";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Vorschau Kommentar";
$ccms['lang']['guestbook']['add'] = "Add your comments";
$ccms['lang']['guestbook']['posted'] = "Comment has been posted!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Vielen Dank";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Ausfälle & Ablehnungen";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Ihr Kommentar wurde abgelehnt.";


      /* ### OBSOLETED ENTRIES ### */
      /*
         Please check the CompactCMS code to:

         a) make sure whether these entries are indeed obsoleted.
            When yes, then the corresponding entry above should be
            removed as well!

         b) When no, i.e. the entry exists in the code, this merits
            a bug report regarding the ./collect_lang_items.sh script.
       
         ----------------------------------------------------------
	
	$ccms['lang']['album']['nodir'] 
	$ccms['lang']['backend']['fileexists'] 
	$ccms['lang']['backend']['startedittitle'] 
	$ccms['lang']['backend']['updatelist'] 
	$ccms['lang']['editor']['closeeditor'] 
	$ccms['lang']['guestbook']['delentry'] 
	$ccms['lang']['guestbook']['removed'] 
	$ccms['lang']['login']['falsetries'] 
	$ccms['lang']['login']['provide'] 
	$ccms['lang']['system']['error_default'] 
	$ccms['lang']['system']['error_sitemap'] 
       
         ----------------------------------------------------------
	
         ### MISSING ENTRIES ###

         The entries below have been found to be missing from this 
         translation file; move them from this comment section to the
         PHP code above and assign them a suitable text.

         When done so, you can of course remove them from the list 
         below.
       
         ----------------------------------------------------------
      */
	  
$ccms['lang']['album']['assigned_page']         = "Assigned to page";
$ccms['lang']['backend']['floatingtitle']       = "Other";
$ccms['lang']['backend']['js_loading']          = "The browser is still loading several files from the server; please refrain from any activity while this initial loading process completes, thank you.";
$ccms['lang']['backend']['reload_admin_screen'] = "Refresh the admininistration screen";
$ccms['lang']['backend']['reordermenu_done']    = "The pages have been assigned new (renumbered) menu positions";
$ccms['lang']['backend']['warning']             = "Warning";
$ccms['lang']['forms']['overwrite_imgs']        = "Overwrite existing files";
$ccms['lang']['forms']['reorderbutton']         = "Re-order";
$ccms['lang']['guestbook']['author']            = "Author";
$ccms['lang']['guestbook']['configuration']     = "Configuration";
$ccms['lang']['guestbook']['date']              = "Date";
$ccms['lang']['guestbook']['host']              = "IP address";
$ccms['lang']['hints']['reordercmdhelp']        = "Renumber all menu entries to ensure both each menu entry has a unique top+sublevel position and the positions are sequential";
$ccms['lang']['permitem']['manageMenu']         = "From what user level on can users manage menu preferences";
$ccms['lang']['permitem']['manageModBackup']    = "From what user level on can users delete current back-up files";
$ccms['lang']['permitem']['manageModComment']   = "The level of a user that is allowed to manage comments";
$ccms['lang']['permitem']['manageModLightbox']  = "From what user level on can users manage albums throught the lightbox module (add, modify, delete)";
$ccms['lang']['permitem']['manageModNews']      = "From what user level on can users manage news items through the news module (add, modify, delete)";
$ccms['lang']['permitem']['manageModTranslate'] = "The level of a user that is allowed to use the (experimental) translation support utility";
$ccms['lang']['permitem']['manageModules']      = "From what user level on can users manage modules";
$ccms['lang']['permitem']['manageOwners']       = "To allow to appoint certain users to a specific page";
$ccms['lang']['permitem']['managePageActivation']     = "From what user level on can users manage the activeness of pages (published vs. unpublished)";
$ccms['lang']['permitem']['managePageCoding']    = "From what user level on can users set whether a page contains coding (wysiwyg vs. code editor)";
$ccms['lang']['permitem']['managePageEditing']  = "From what user level on can users edit pages (content, title, subheader, keywords, etc.)";
$ccms['lang']['permitem']['managePages']        = "From what user level on can users manage pages (add, delete)";
$ccms['lang']['permitem']['manageTemplate']     = "From what user level on can users manage and edit all of the available templates";
$ccms['lang']['permitem']['manageUsers']        = "From what user level on can users manage user accounts (add, modify, delete)";
$ccms['lang']['system']['error_rec_exists']     = "Entry already exists in the database.";
$ccms['lang']['system']['home']                 = "Home";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
