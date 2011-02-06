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

/* Translation by Bob Axell <cyberdyze@gmail.com> */

// System wide messages
$ccms['lang']['system']['error_database'] = "Kunde ej koppla till databasen. Var god verifiera databasinformation nedan.";
$ccms['lang']['system']['error_openfile'] = "Kunde ej öppna den specifierade filen";
$ccms['lang']['system']['error_notemplate'] = "Inga stilmallar hittades som kunde appliceras på din sida. Var god lägg in din stilmall i mappen ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "Kunde inte hitta mallar katalog! Se till att den finns och innehåller minst en mall.";
$ccms['lang']['system']['error_write'] = "Filen är ej skrivbar";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Nummer Inga skrivrättigheter";
$ccms['lang']['system']['error_chmod'] = "Den nuvarande filen kunde ej modifieras. Var vänlig kontrollera skrivbarhetsinställningar på filen och dess mapp (666).";
$ccms['lang']['system']['error_value'] = "Fel: felaktigt värde";
$ccms['lang']['system']['error_default'] = "Huvudsida kan ej raderas.";
$ccms['lang']['system']['error_forged'] = "Värde har manipulerats";
$ccms['lang']['system']['error_filedots'] = "Filnamn bör ej innehålla punkter, t.e.x. '.html'.";
$ccms['lang']['system']['error_filesize'] = "Filnamn måste innehålla minst 3 tecken.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Filnamnet ska vara högst 50 tecken långt.";
$ccms['lang']['system']['error_pagetitle'] = "Skriv in en sidotitel med minst 3 tecken.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Ange en sida titel på 100 tecken eller mindre.";
$ccms['lang']['system']['error_subtitle'] = "Skriv in en kort undertitel till din sida";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Skriv en kort underrubrik på 200 tecken eller mindre för din sida.";
$ccms['lang']['system']['error_description'] = "Skriv in en beskrivning med mer än 3 tecken";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Ange en beskrivning av mindre än 250 tecken";
$ccms['lang']['system']['error_reserved'] = "Du har specifierat ett filnamn som reserverats för intern användning.";
$ccms['lang']['system']['error_general'] = "Fel inträffade";
$ccms['lang']['system']['error_correct'] = "Var god rätta till följande:";
$ccms['lang']['system']['error_create'] = "Fel vid skapandet av den nya filen";
$ccms['lang']['system']['error_exists'] = "Det filnamn du valt existerar redan.";
$ccms['lang']['system']['error_delete'] = "Fel vid raderandet av den nya filen";
$ccms['lang']['system']['error_selection'] = "Ingen fil har valts.";
$ccms['lang']['system']['error_versioninfo'] = "Det finns ingen versioninformation tillgänglig.";
$ccms['lang']['system']['error_misconfig'] = "<strong>Det verkar finnas en felkonfigurering.</strong><br/>Vad vänlig se till att .htaccess filens inställningar motsvarar nuvarande filstruktur. Om du har<br/>installerat CompactCMS i en underkatalog, justera då .htaccess filen i enlighet med detta.";
$ccms['lang']['system']['error_deleted'] = "<h1>Filen du har valt verkar ha raderats</h1><p>Uppdatera listan av filer för att undvika att detta händer. Om detta inte hjälper måste du manuellt öppna sökvägen och leta reda på filen du vill ha.</p>";
$ccms['lang']['system']['error_404title'] = "Flen hittades ej";
$ccms['lang']['system']['error_404header'] = "Ett 404 fel inträffade, den begärda filen kunde ej hittas.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "Den begärda filen <strong>{%pagereq%}.html</strong> inte kunde hittas.";
$ccms['lang']['system']['error_403title'] = "Forbidden";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "En 403 fel uppstod: Du har inte behörighet att komma åt den önskade filen.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Du får inte tillgång till <strong>{%pagereq%}.html</strong> just nu.";
$ccms['lang']['system']['error_sitemap'] = "En översikt över alla sidor";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "En eller flera värden som lämnas antingen för korta eller felaktiga";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Ett lösenord bör innehålla mer än 6 tecken";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Den inmatade lösenord matchar inte";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Inga resultat";
$ccms['lang']['system']['tooriginal'] = "Tillbaka till originalet";
$ccms['lang']['system']['message_rights'] = "Alla rättigheter reserverade";
$ccms['lang']['system']['message_compatible'] = "Testkörd på ";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = "Har du förslag, feedback eller problem? Besök <a href='http://community.compactcms.nl/forum/' title='Besök det officiella forumet' class='external' rel='external'>forumet</a>!";
$ccms['lang']['backend']['ordertip'] = "Använd drop-down menyn nedan för att justera menyn på din sida. Notera att systemet inte lägger märka till dubletter.";
$ccms['lang']['backend']['createtip'] = "För att skapa en ny sida, fyll i formuläret nedan och sidan kommer att skapas direkt. När sidan har skapats kan du redigera den som vanligt";
$ccms['lang']['backend']['currentfiles'] = "I listan nedan hittar du alla hittills publicerade sidor. Huvudsisdan kan ej raderas, eftersom den är förstasidan på din webbplats. Andra filer kan ha begränsad åtkomlighet eftersom endast administratören äger tillträde till dem.";
$ccms['lang']['backend']['confirmdelete'] = "Var vänlig bekräfta att du verkligen vill ta bort alla dessa sidor och dess innehåll.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Vänligen bekräfta att du vill att förnya alla miniatyrer.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Dina ändringar har sparats.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Se till att ladda om huvudsidan för att se <strong>alla</strong> dina ändringar";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Kunde bearbeta in post (er).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Tagits bort vald post (er).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Framgångsrikt regenererad miniatyrerna.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Tillbaka till översikten";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Ställ CCMS behörigheter";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definiera innehåll ägare";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Mall redaktör";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Användarhantering";
$ccms['lang']['backend']['changevalue'] = "Klicka för att ändra";
$ccms['lang']['backend']['previewpage'] = "Förhandsgranska";
$ccms['lang']['backend']['editpage'] = "Redigera";
$ccms['lang']['backend']['restrictpage'] = "Begränsad";
$ccms['lang']['backend']['newfiledone'] = "Denna fil är fräsh och klar att fyllas i!";
$ccms['lang']['backend']['newfilecreated'] = "Filen har skapats";
$ccms['lang']['backend']['startedittitle'] = "Börja redigera!";
$ccms['lang']['backend']['starteditbody'] = "Den nya filen har skapats. Börja redigera genast eller skapa nya sidor, eller alternativt redigera gammla.";
$ccms['lang']['backend']['success'] = "Framgång!";
$ccms['lang']['backend']['fileexists'] = "Filen existerar";
$ccms['lang']['backend']['statusdelete'] = "Status av utvald radering:";
$ccms['lang']['backend']['statusremoved'] = "raderad";
$ccms['lang']['backend']['uptodate'] = "aktuell.";
$ccms['lang']['backend']['outofdate'] = "inaktuell.";
$ccms['lang']['backend']['considerupdate'] = "Överväg uppdatering";
$ccms['lang']['backend']['orderprefsaved'] = "Dina menyinställningar har sparats.";
$ccms['lang']['backend']['inmenu'] = "I meny";
$ccms['lang']['backend']['updatelist'] = "Uppdatera fil-lista";
$ccms['lang']['backend']['administration'] = "Administration";
$ccms['lang']['backend']['currentversion'] = "Du kör för tillfället version";
$ccms['lang']['backend']['mostrecent'] = "Den senaste stabila CompactCMS versionen är";
$ccms['lang']['backend']['versionstatus'] = "Din installation är";
$ccms['lang']['backend']['createpage'] = "Skapa en ny sida";
$ccms['lang']['backend']['managemenu'] = "Hantera meny";
$ccms['lang']['backend']['managefiles'] = "Hantera nuvarande filer";
$ccms['lang']['backend']['delete'] = "Radera";
$ccms['lang']['backend']['toplevel'] = "Huvudgrupper";
$ccms['lang']['backend']['sublevel'] = "Undergrupper";
$ccms['lang']['backend']['active'] = "Aktiv";
$ccms['lang']['backend']['disabled'] = "Avaktiverad";
$ccms['lang']['backend']['template'] = "Aktiverad";
$ccms['lang']['backend']['notinmenu'] = "Föremål ej i meny";
$ccms['lang']['backend']['menutitle'] = "Meny";
$ccms['lang']['backend']['linktitle'] = "Länk";
$ccms['lang']['backend']['item'] = "Föremål";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Inga";
$ccms['lang']['backend']['yes'] = "Ja";
$ccms['lang']['backend']['no'] = "Nej";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Översättningar";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Hej";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "Logga ut";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Se forum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Använd ett giltigt användarnamn och lösenord för att få tillgång till CompactCMS back-end. Om du kom hit av misstag, tillbaka till <a href='admin/includes/'>startsidan</a>.</p> <p>Kontakta din webmaster för dina uppgifter.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Användarnamn";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Lösenord";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Logga in";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Ange dina autentiseringsuppgifter";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Ange både ditt användarnamn och lösenord";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Ange ditt användarnamn";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Ange ditt lösenord";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Detta konto har inaktiverats";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Observera att du redan har gjort flera försök";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Felaktigt användarnamn eller lösenord";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = "Main";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Vänster";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Höger";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Sidfot";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = "Filnamn";
$ccms['lang']['forms']['pagetitle'] = "Sidotitel";
$ccms['lang']['forms']['subheader'] = "Undertitel";
$ccms['lang']['forms']['description'] = "Beskrivning";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Modul";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "Innehåll post (standard)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Tillägg";
$ccms['lang']['forms']['printable'] = "Utskrivbar";
$ccms['lang']['forms']['published'] = "Aktiv";
$ccms['lang']['forms']['iscoding'] = "Kodning";
$ccms['lang']['forms']['createbutton'] = "Skapa!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Ändra";
$ccms['lang']['forms']['savebutton'] = "Spara";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end språk";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "Just nu är vi bara visar sidor som har minst denna text här";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Redigera eller ta bort filter för";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Lägg till filter för";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = "Sido-URL:et (home.html) :: Filnamnet med vilket denna sida framtas (utan .html)";
$ccms['lang']['hints']['pagetitle'] = "Titel för denna sida (Home) :: AEn kort förmedlande titel för denna sida.";
$ccms['lang']['hints']['subheader'] = "Kort rubriktext (Välkommen till vår webbplats): En kort beskrivande text som används i sidhuvudet på varje sida samt i rubriken på varje sida.";
$ccms['lang']['hints']['description'] = "Meta Beskrivning :: En unik beskrivning för denna sida som kommer att användas som sidorna 'meta beskrivning'.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Modul :: Välj vilken modul ska hantera innehållet i denna fil. Om du är osäker, välj standard.";
$ccms['lang']['hints']['printable'] = "ida tryckbarhet:: När alternativet 'JA' väljs skall en utskriftsvänlig sida genereras. 'NEJ' bör väljas för sidor med bilder eller andra medier. Dessa är bättre utan en utskrivbar sida.";
$ccms['lang']['hints']['published'] = "Publicerad status :: Välj om sidan bör offentliggöras, om det blir i webbplatskartan och om den kommer att vara tillgänglig för allmänheten.";
$ccms['lang']['hints']['toplevel'] = "Huvudgrupper :: Ange den högsta nivån för det här menyalternativet. Välj --- att inte inkludera sidan i menyn.";
$ccms['lang']['hints']['sublevel'] = "Undergrupper :: Välj 0 när denna punkt bör ha högsta nivå. Om det är av lägre rang för en viss huvudgrupp, vänligen välj lämplig undergrupp.";
$ccms['lang']['hints']['template'] = "Stilmall:: Om du använder flera mallar för din installation kan du tillsätta olika mallar för varje enskild sida genom denna drop-down meny.";
$ccms['lang']['hints']['activelink'] = "	
Aktiv länk i menyn? :: Punkter måste inte alltid ha en faktisk koppling. Du kan avaktivera länken bakom denna punkt i front-end-menyn genom att avmarkera kryssrutan nedan.";
$ccms['lang']['hints']['menuid'] = "Meny kategori: Välj i vilken meny denna punkt bör förtecknas i. Standardvärdet är huvudmenyn (1), där även hemsideslänken visas.";
$ccms['lang']['hints']['iscoding'] = "Innehåller kodning :: Inehåller denna fil exempelvis PHP eller Javascript? Om du väljer 'Ja' begränsas tillgången till filen från back-end WYSIWYG redigeraren och redaktören.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Du kan klicka på <span class='sprite livefilter livefilter_active'> filtret ikonen till vänster om titeln för att lägga till, redigera eller ta bort en text för att filtrera sidan listan, t.ex. när du skriver 'hem' i redigeringsfältet som visas när du klickar på ikonen, tryck sedan på Enter / Retur, bara sidor som har texten 'Hem' i denna kolumn visas. <br>Om du klickar på ikonen igen och ta bort texten i textfältet och sedan trycka på Enter / Retur-tangenten, kommer att ta bort filtret.<br>Håll muspekaren över ikonen för filtrering för att se om kolumnen för närvarande filtreras, och i så fall använda som filter text.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = "Stäng redigeraren";
$ccms['lang']['editor']['editorfor'] = "Text redigerare för";
$ccms['lang']['editor']['instruction'] = "Use the editor below to modify the current file. Once you're done, hit the 'Save changes' button below to directly publish your modifications to the world wide web. Also add up to ten relevant keywords for search engine optimalization.";
$ccms['lang']['editor']['savebtn'] = "Spara ändringar";
$ccms['lang']['editor']['cancelbtn'] = "Ångra";
$ccms['lang']['editor']['confirmclose'] = "Stäng detta fönster och kassera förändringar?";
$ccms['lang']['editor']['preview'] = "Förhandsgranska resultatsidan";
$ccms['lang']['editor']['savesuccess'] = "<strong>Framgång!</strong> Innehållet nedan har sparats till";
$ccms['lang']['editor']['backeditor'] = "Tillbaka till redigerare";
$ccms['lang']['editor']['closewindow'] = "Stäng fönstret";
$ccms['lang']['editor']['keywords'] = "Nyckelord - <em>åtskillda med kommatecken, max 250 tecken totalt</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto generera ett säkert lösenord";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Ditt nuvarande nivå kan du inte använda funktionen.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Skapa ett nytt back-up";
$ccms['lang']['backup']['explain'] = "To prevent possible loss of data due to whatever external event, it's wise to create back-ups of your files reguraly.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Varning: Tänk på att din <dfn>ljusbord</dfn> album 'bilder är <strong>inte</strong> backas upp beskrivningar! Albumet <strong>är</strong>, men bilderna själva och deras miniatyrer <strong>inte i dessa säkerhetskopior</strong>med. Om du vill ha säkerhetskopior av dessa, då du kommer att behöva ge webbplatsadministratören om en extra backup system som hjälper dig att säkerhetskopiera och återställa dessa (möjligen stor) fil samlingar.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Finns back-ups";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "Back-up filnamn";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Hämta arkiv";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Vänta medan säkerhetskopian skapas ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Skapa en användare";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Översikt CCMS användare";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Redigera användarens personliga detaljer";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Redigera användarens lösenord";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Kontoinställningar";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Användare";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Användarnamn";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Namn";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Förnamn";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Efternamn";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Lösenord";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Bekräfta lösenord";
$ccms['lang']['users']['email'] = /* BABELFISH */ "E-post";
$ccms['lang']['users']['active'] = /* BABELFISH */ "Aktiv";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Nivå";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "Användarnivå";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Senaste log";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Hantera mallar";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Den nuvarande mallen är <strong>inte</strong> skrivbar";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Skriv ut";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Tillstånd preferenser";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Använd tabellen nedan för att ange vilka minimikrav användarnivå kan använda vissa funktioner. Alla användare under den angivna minsta användaren nivå kommer inte att se eller ha tillgång till funktionen.";
$ccms['lang']['permission']['target'] = "Target";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Nivå 1 - User";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Nivå 2 - Redaktör";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Nivå 3 - Manager";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Nivå 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Upphovsrättsinnehavare";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Här kan du utse särskild sida äganderätt till enskilda användare. Om en viss sida ingen användare är vald, kan alla ändra på sidan. Annars bara den angivna användaren hade modifiering rättigheter till filen. Administratörer har alltid tillgång till alla filer.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Sidor";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Användare";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Översätt";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Visar översättning strängarna.";

// Album messages
$ccms['lang']['album']['album'] = "Album";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Album inställningar";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "Specifikt gäller det här albumet att";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Album beskrivning";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Aktuellt album";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Ladda upp innehåll";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Överför till befintliga album";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Börja ladda upp";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Bläddra filer";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Töm lista";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Enkel filöverföring</strong><br> <p>Flash loader gick inte att initiera. Se till att Javascript är aktiverat och Flash är installerat. Enkel uppladdning är möjligt, men inte optimalt.</p>";
$ccms['lang']['album']['manage'] = "Manage albums";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Albumlista";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Generera miniatyrer alla";
$ccms['lang']['album']['newalbum'] = "New album name";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Inga album har skapats ännu!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Filer";
$ccms['lang']['album']['nodir'] = "Please make sure the directory <strong>albums</strong> exists in your image directory";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Senast uppdaterad";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Var god vänta ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Hantera aktuella nyheter";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Lägg till nyheter";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Skriv ny artikel";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Hantera inställningar";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Skriv nyheter";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# Meddelanden på front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Visa författare";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Visa publiceringsdatum";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Visa bara teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Nyheter titel";
$ccms['lang']['news']['author'] = /* BABELFISH */ "Nyheter Författare";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Datum";
$ccms['lang']['news']['published'] = /* BABELFISH */ "År?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Innehåll";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Visa arkiv";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Inga reaktioner har postat ännu!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Att kontrollera att detta meddelande inte är automatiserad, nytt ange";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reaktion";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Betyg";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com Användarvisningsbild";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "skrev";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Hantera reaktioner";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Ta bort den här posten";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-post författare";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "har tagits bort från databasen.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Ditt namn";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Din e-post";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Din webbplats";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Kommentarer";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Verifiering";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Förhandsgranska kommentar";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Lägg till kommentarer";
$ccms['lang']['guestbook']['posted'] = "Comment has been posted!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Tack";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Fel & Avvisning";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Din kommentar har avvisats.";


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
	$ccms['lang']['guestbook']['reaction']	
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
	  
       
      /*
         ----------------------------------------------------------
      */
	  
?>