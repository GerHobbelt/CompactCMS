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

// System wide messages
$ccms['lang']['system']['error_database'] = "Impossibile connettersi al database. Verificare username, password ed il nome del database.";
$ccms['lang']['system']['error_openfile'] = "Impossibile aprireilfile specificato.";
$ccms['lang']['system']['error_notemplate'] = "Non ci sono template disponibili. Aggiungere almeno un template nella directory ./lib/templates/ map.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "Impossibile trovare la directory dei template! Assicurarsi che esista e che contenga almeno un modello.";
$ccms['lang']['system']['error_write'] = "File senza accesso in scrittura";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Elenco non ha accesso in scrittura";
$ccms['lang']['system']['error_chmod'] = "Il file non pu&ograve; essere modificato. Controllare i permessi (CHMOD).";
$ccms['lang']['system']['error_value'] = "Errore: valore incorretto";
$ccms['lang']['system']['error_default'] = "La pagine principale non pu&ograve; essere cancellata.";
$ccms['lang']['system']['error_forged'] = "Il valore &egrave; stato impostato con";
$ccms['lang']['system']['error_filedots'] = "Il nome del file non pu&ograve; contenere punti, es. '.html'.";
$ccms['lang']['system']['error_filesize'] = "Il nome del file deve essere almeno di tre caratteri.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Nome del file dovrebbe essere al massimo di 50 caratteri.";
$ccms['lang']['system']['error_pagetitle'] = "Inserire il titolo della pagina di almeno tre caratteri.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Inserisci un titolo di pagina 100 caratteri o meno.";
$ccms['lang']['system']['error_subtitle'] = "Inserire il sottotitolo della pagina.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Inserire un breve sottotitolo di 200 caratteri o meno per la tua pagina.";
$ccms['lang']['system']['error_description'] = "Inserire una descrizione di almeno tre caratteri.";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Inserire una descrizione di meno di 250 caratteri";
$ccms['lang']['system']['error_reserved'] = "E' stato specificato il nome di un file riservato.";
$ccms['lang']['system']['error_general'] = "Errore";
$ccms['lang']['system']['error_correct'] = "Corregere il/i seguente/i:";
$ccms['lang']['system']['error_create'] = "Errore nel completamento del nuovo file";
$ccms['lang']['system']['error_exists'] = "Nome di file esistente.";
$ccms['lang']['system']['error_delete'] = "Errore durante l'eliminazione del file.";
$ccms['lang']['system']['error_selection'] = "Nessun file selezionato.";
$ccms['lang']['system']['error_versioninfo'] = "Informazione non disponibile.";
$ccms['lang']['system']['error_misconfig'] = "<strong>Errore di configurazione.</strong><br/>Verificare che il file .htaccess sia correttamente configurato. Se<br/>CompactCMS &egrave; stato installato in una sottodirectory, modificare correttamente il file .htaccess.";
$ccms['lang']['system']['error_deleted'] = "<h1>Il file selezionato non &egrave; stato trovato</h1><p>Aggiornare la lista dei file per mostrare i file attualmente disponibili e prevenire il suddetto errore. Se ci&ograve; non risolve il problema, controllare manualmente l'esistenza del file nella cartella dei contenuti.</p>";
$ccms['lang']['system']['error_404title'] = "File non trovato";
$ccms['lang']['system']['error_404header'] = "Errore 404, il file <strong>{%pagereq%}.html</strong> richiesto &egrave; inesistente.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "Il file richiesto <strong>{%pagereq%}.html</strong> non è stato trovato.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Proibito";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "A 403 Errore: non si dispone dell'autorizzazione per accedere al file richiesto.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Non hai i permessi per accedere a <strong>{%pagereq%}.html</strong> in questo momento.";
$ccms['lang']['system']['error_sitemap'] = "Anteprima di tutte le pagine";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "valori presentati uno o più erano o troppo brevi o non corretta";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "La password deve contenere più di 6 caratteri";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Le password immesse non corrispondono";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Nessun risultato";
$ccms['lang']['system']['tooriginal'] = "Torna all'originale";
$ccms['lang']['system']['message_rights'] = "Tutti i diritti sono riservati.";
$ccms['lang']['system']['message_compatible'] = "Testato su";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = "Suggerimenti o problemi? Visita <a href='http://www.compactcms.nl/forum/' title='Visit the official forum' class='external' rel='external'>il forum</a>!";
$ccms['lang']['backend']['ordertip'] = "Utilizzare la lista sotto per strutturare il menu del sito. Il sistema non gestisce i duplicati.";
$ccms['lang']['backend']['createtip'] = "Per creare una nuova pagina, riempire il modulo sotto ad una nuova pagina sar&agrave; creata. Appena il file sar&agrave; creato sar&agrave; possibile modificarlo.";
$ccms['lang']['backend']['currentfiles'] = "Nella lista sotto son presenti i file pubblicati. La pagina di default non pu&ograve; essere cancellata essendo la primapagina del sito. Gli altri file potrebbero essere soggette a restrizioni dell'amministratore.";
$ccms['lang']['backend']['confirmdelete'] = "Cancellare le pagine selezionate ed il loro contenuto?";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Si prega di confermare che si desidera rigenerare tutte le miniature.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Le modifiche sono state salvate con successo.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Assicurati di ricaricare la pagina principale per vedere <strong>tutte</strong> le modifiche";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Ha elaborato la voce presentata (s).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Ha eliminato la voce selezionata (s).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Rigenerato con successo le miniature.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Torna alla pagina precedente";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Set CCMS autorizzazioni";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definire i proprietari di contenuti";
$ccms['lang']['backend']['templateeditor'] = "Template editor";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Gestione degli utenti";
$ccms['lang']['backend']['changevalue'] = "Premi per cambiare";
$ccms['lang']['backend']['previewpage'] = "Anteprima";
$ccms['lang']['backend']['editpage'] = "Modifica";
$ccms['lang']['backend']['restrictpage'] = "Riservato";
$ccms['lang']['backend']['newfiledone'] = "Inserire il contenuto...";
$ccms['lang']['backend']['newfilecreated'] = "Il file &egrave; stato creato con successo";
$ccms['lang']['backend']['startedittitle'] = "Inizio modifica!";
$ccms['lang']['backend']['starteditbody'] = "Nuovo file creato. Modificare il suo contenuto o aggiungere/gestire nuove pagine.";
$ccms['lang']['backend']['success'] = "Riuscito!";
$ccms['lang']['backend']['fileexists'] = "File esistente";
$ccms['lang']['backend']['statusdelete'] = "Stato dell'eliminazione della selezione:";
$ccms['lang']['backend']['statusremoved'] = "rimosso";
$ccms['lang']['backend']['uptodate'] = "aggiornato.";
$ccms['lang']['backend']['outofdate'] = "obsoleto.";
$ccms['lang']['backend']['considerupdate'] = "Aggiornamento consigliato";
$ccms['lang']['backend']['orderprefsaved'] = "Ordine dei menu salvato.";
$ccms['lang']['backend']['inmenu'] = "Nel menu";
$ccms['lang']['backend']['updatelist'] = "Aggiorna lista file";
$ccms['lang']['backend']['administration'] = "Amministrazione";
$ccms['lang']['backend']['currentversion'] = "Versione corrente";
$ccms['lang']['backend']['mostrecent'] = "L'ultima versione di CompactCMS stabile &egrave;";
$ccms['lang']['backend']['versionstatus'] = "La tua installazione &egrave;";
$ccms['lang']['backend']['createpage'] = "Crea nuova pagina";
$ccms['lang']['backend']['managemenu'] = "Gestisci menu";
$ccms['lang']['backend']['managefiles'] = "Gestisci i file correnti";
$ccms['lang']['backend']['delete'] = "Elimina";
$ccms['lang']['backend']['toplevel'] = "Livello top";
$ccms['lang']['backend']['sublevel'] = "Sottolivello";
$ccms['lang']['backend']['active'] = "Attivo";
$ccms['lang']['backend']['disabled'] = "Disabilitato";
$ccms['lang']['backend']['template'] = "Categoria";
$ccms['lang']['backend']['notinmenu'] = "L'oggetto non &egrave; nel men&ugrave;";
$ccms['lang']['backend']['menutitle'] = "Menu";
$ccms['lang']['backend']['linktitle'] = "Link";
$ccms['lang']['backend']['item'] = "Oggetto";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Nessuno";
$ccms['lang']['backend']['yes'] = "S&igrave;";
$ccms['lang']['backend']['no'] = "No";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Traduzioni";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Ciao";
$ccms['lang']['backend']['logout'] = "Log-out";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Vedere forum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Usa un nome utente e password validi per accedere al back-end CompactCMS. Se sei arrivato qui per errore, tornare alla <a href='admin/includes/'>home page</a>.</p> <p>Contattare il webmaster per i vostri dati.</p>";
$ccms['lang']['login']['username'] = "Username";
$ccms['lang']['login']['password'] = "Password";
$ccms['lang']['login']['login'] = "Login";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Si prega di fornire le credenziali utente";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Inserisci il tuo nome utente e la password";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Inserisci il tuo nome";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Inserisci la tua password";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Questo account è stato disattivato";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Si noti che è già fatto più tentativi";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Utente o password incorrette";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = "Principale";
$ccms['lang']['menu']['2'] = "Sinistra";
$ccms['lang']['menu']['3'] = "Destra";
$ccms['lang']['menu']['4'] = "Fondo";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = "Nome file";
$ccms['lang']['forms']['pagetitle'] = "Titolo pagina";
$ccms['lang']['forms']['subheader'] = "Sottotitolo";
$ccms['lang']['forms']['description'] = "Descrizione";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Modulo";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "Elementi di contenuto (default)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Aggiunte";
$ccms['lang']['forms']['printable'] = "Stampabile";
$ccms['lang']['forms']['published'] = "Attiva";
$ccms['lang']['forms']['iscoding'] = "Codice";
$ccms['lang']['forms']['createbutton'] = "Crea!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Modifica";
$ccms['lang']['forms']['savebutton'] = "Salva";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end lingua";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "in questo momento stiamo solo mostrando le pagine che hanno almeno in questo testo qui";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Modificare o rimuovere il filtro per";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Aggiungi filtro per";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = "Indirizzo URL (home.html) :: Nome del file (senza estensione .html)";
$ccms['lang']['hints']['pagetitle'] = "Titolo della pagina (Home) :: Breve descrizione della pagina.";
$ccms['lang']['hints']['subheader'] = "Intestazione della pagina (Benvenuto nel nostro sito) :: Una breve descrizione utilizzata sia in ogni pagina sia come titolo nella barra del broswer.";
$ccms['lang']['hints']['description'] = "Codici Meta :: Descrizione unica di questa pagina (utilizzata anche nei dati meta).";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Modulo :: selezionare quale modulo dovrebbe gestire il contenuto di questo file. Se si è sicuri, selezionare l'impostazione predefinita.";
$ccms['lang']['hints']['printable'] = "Stampabile :: In caso positivo viene creata una pagina stampabile. 'NO' dovrebbe essere selezionato per le pagine contenenti immagini ed elementi multimediali.";
$ccms['lang']['hints']['published'] = "Pubblicato? :: Selezionare la casella se si vuole pubblicare la pagina per renderla accessibile al pubblico ed inclusa nella sitemap.";
$ccms['lang']['hints']['toplevel'] = "Livello Top :: Specifica il livello pi&ugrave;alto per questo menu. Selezionare N.I.M. per non includerla nei menu.";
$ccms['lang']['hints']['sublevel'] = "Sottolivello :: Selezionando 0 l'oggetto del menu &egrave; nel livello pi&ugrave; alto. Se invece &egrave; un sottolivello per un certo menu, selezionare ilsottolivello appropriato.";
$ccms['lang']['hints']['template'] = "Categoria :: Se si usano template multipli, &egrave; possibile categorizzare i menu in base al template.";
$ccms['lang']['hints']['activelink'] = "Menu attivo? :: Non tutte le pagine necessitano di un link attivo. Per disattivare una pagina premere la checkbox corrispondente.";
$ccms['lang']['hints']['menuid'] = "Categoria Menu :: Selezione in quale menu questo oggetto deve essere inserito. Il default &egrave; il main menu (1), in cui &egrave; mostrato il link alla homepage.";
$ccms['lang']['hints']['iscoding'] = "Contiene codice :: Il file contiene codice (es. PHP o Javascript)? Selezionado 'S&igrave;' &egrave; possibile inserire il proprio codice a mano nell'editor della pagina.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>È possibile fare clic sul <span class='sprite livefilter livefilter_active'> icona del filtro a sinistra del titolo per aggiungere, modificare o rimuovere un testo per filtrare l'elenco pagina, ad esempio quando si 'tipo' a casa nel campo di modifica che appare quando si fa clic sull'icona, quindi premere il tasto Invio / tasto Invio, solo le pagine che hanno a casa 'il testo' in questa colonna sarà mostrata. <br>Facendo clic sull'icona di nuovo e cancellare il testo nel campo di modifica, quindi premendo il tasto 'Invio', verrà rimosso il filtro.<br>Al passaggio del mouse sopra l'icona del filtro per vedere se la colonna è attualmente in fase di filtrato e, se sì, con quale testo del filtro.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = "Chiudi l'editor";
$ccms['lang']['editor']['editorfor'] = "Editor di testo per";
$ccms['lang']['editor']['instruction'] = "Utilizzare l'editor sotto per modificare il file corrente. Premere poi il pulsante 'Salva cambiamenti' per pubblicare le modifiche ed aggiornare automaticamente il sito.";
$ccms['lang']['editor']['savebtn'] = "Salva cambiamenti";
$ccms['lang']['editor']['cancelbtn'] = "Annulla";
$ccms['lang']['editor']['confirmclose'] = "Chiudere la finestra e scartare le modifiche?";
$ccms['lang']['editor']['preview'] = "Anteprima pagina";
$ccms['lang']['editor']['savesuccess'] = "<strong>Successo!</strong> Il contenuto sotto &egrave; stato salvato in ";
$ccms['lang']['editor']['backeditor'] = "Torna all'editor";
$ccms['lang']['editor']['closewindow'] = "Chiudi finestra";
$ccms['lang']['editor']['keywords'] = "Parole chiave - <em> separate da virgole, massimo 250 caratteri </ em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto generare una password sicura";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Il tuo livello di conto corrente non consente di utilizzare questa funzionalità.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Crea nuovo back-up";
$ccms['lang']['backup']['explain'] = "To prevent possible loss of data due to whatever external event, it's wise to create back-ups of your files reguraly.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Attenzione :: Si prega di essere consapevole del fatto che la tua <dfn>lightbox</dfn> 'immagini album sono <strong>non</strong> backup descrizioni! L'album <strong>sono</strong>, ma le immagini stesse e le loro miniature sono <strong>non inclusi in queste copie di backup</strong>con. Se si desidera che i backup di questi, allora è necessario conferire l'amministratore del sito su un sistema di backup aggiuntive che consentono di backup e ripristino di questi (forse grande) collezioni di file.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Disponibile back-up";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "nome del file di back-up";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Download archivio";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Si prega di attendere il backup è stato creato ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Creare un utente";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Panoramica CCMS utenti";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Modifica dati personali dell'utente";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Modificare la password dell'utente";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Impostazioni account";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Utente";
$ccms['lang']['users']['username'] = "Username";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Nome";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Nome";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Cognome";
$ccms['lang']['users']['password'] = "Password";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Conferma la password";
$ccms['lang']['users']['email'] = "E-mail";
$ccms['lang']['users']['active'] = /* BABELFISH */ "Attivo";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Livello";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "A livello di utente";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Ultimo log";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Gestire i modelli";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Il modello attuale è <strong>non</strong> scrivibile";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Stampa";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Autorizzazione preferenze";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Utilizzare la tabella riportata di seguito per specificare quale livello minimo utente può utilizzare alcune funzionalità. Ogni utente al di sotto del livello minimo specificato dall'utente richiesta, non verrà visualizzato, né hanno accesso alla funzione.";
$ccms['lang']['permission']['target'] = "Target";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Livello 1 - Istruzioni";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Livello 2 - Editor";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Livello 3 - Direttore";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Livello 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "I proprietari del contenuto";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Qui è possibile nominare la proprietà di pagina specifica per i singoli utenti. Se per una certa pagina non vi sono utenti selezionati, tutti possono modificare la pagina. In caso contrario, solo l'utente ha specificato diritti di modifica al file. Gli amministratori hanno sempre accesso a tutti i file.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Pagine";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Utenti";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Traduci";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Mostra le stringhe di traduzione.";

// Album messages
$ccms['lang']['album']['album'] = "Album";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Album impostazioni";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "In particolare si applicano a questo album";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Album descrizione";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "album di corrente";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Carica il contenuto";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Carica esistenti album";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Inizia upload";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Sfogliare i file";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Cancella elenco";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Singolo file upload</strong><br> <p>Il caricatore Flash è riuscito a inizializzare. Assicurarsi che Javascript sia abilitato e Flash è installato. upload di file singole sono possibili, ma non ottimizzati.</p>";
$ccms['lang']['album']['manage'] = "Manage albums";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Lista album";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Rigenerare miniature tutti";
$ccms['lang']['album']['newalbum'] = "New album name";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Nessun album sono stati ancora creato!";
$ccms['lang']['album']['files'] = "Files";
$ccms['lang']['album']['nodir'] = "Please make sure the directory <strong>albums</strong> exists in your image directory";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Ultima modifica";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Si prega di attendere ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Gestire le notizie attuali";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Aggiungi le notizie";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Scrivi nuovo articolo";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Gestire le impostazioni";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Scrivere news";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "Messaggio # sul front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Mostra autore";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Mostra data di pubblicazione";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Mostra solo teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Notizie titolo";
$ccms['lang']['news']['author'] = /* BABELFISH */ "News autore";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Data";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Pubblicato?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Articolo contenuti";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Visualizza l'archivio";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Nessuna reazione è stato pubblicato ancora!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Per verificare che questo messaggio non è automatizzato, per favore rientrare";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reazione";
$ccms['lang']['guestbook']['rating'] = "Rating";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com utente avatar";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "ha scritto";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Gestire le reazioni";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Eliminare questa voce";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail autore";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "è stato rimosso dal database.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Il tuo nome";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Il tuo indirizzo e-mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Il tuo sito web";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Commenti";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Verifica";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Anteprima del commento";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Aggiungi il tuo commento";
$ccms['lang']['guestbook']['posted'] = "Comment has been posted!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Grazie";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Errori & Respingimenti";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Il tuo commento è stato respinto.";


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
       
      /*
         ----------------------------------------------------------
      */
	  
?>
