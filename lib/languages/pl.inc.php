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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "Nie można połączyć się z bazą danych. Sprawdź dane logowania i nazwa bazy danych.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "Nie można otworzyć określonego pliku";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "Nie można odnaleźć szablonów być stosowane do witryny. Należy dodać przynajmniej jeden szablon do. / Lib / templates /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "Nie można odnaleźć szablonów katalogu! Upewnij się, że istnieje i zawiera co najmniej jeden szablon.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "Plik nie ma dostępu do zapisu";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Lista nie ma dostępu do zapisu";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "Bieżący plik nie może zostać zmodyfikowany. Sprawdź uprawnienia do plików w katalogu / treści (666).";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "Błąd: nieprawidłowe wartości";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "Strona domyślna nie mogą być usunięte.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "Wartość została zmodyfikowana";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "Nazwa pliku nie może zawierać kropek, np. '.html'.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "Nazwa pliku powinna wynosić co najmniej 3 znaki.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Nazwa pliku powinna być co najwyżej 50 znaków.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "Wprowadź tytuł strony z 3 lub więcej znaków.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Wpisz tytuł strony niż 100 znaków.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "Podać krótki podtytuł na stronie.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Napisz krótki podtytuł 200 znaków lub mniej na swojej stronie.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "Wprowadź opis więcej niż 3 liter";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Wprowadź opis mniej niż 250 znaków";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "Masz określono nazwę pliku zastrzeżone do użytku wewnętrznego.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "Wystąpił błąd";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "Proszę poprawić następujące:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "Błąd podczas wypełniania tworzenia nowego pliku";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "Nazwa pliku już istnieje.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "Błąd podczas wypełniania usunąć wybrany plik";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "Nie zostały zaznaczone żadne obiekty.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "Brak informacji o wersji jest dostępna.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>Wydaje się, że błąd w konfiguracji.</strong><br>Upewnij się, że. Htaccess jest poprawnie skonfigurowany, aby odzwierciedlić strukturę pliku. Jeśli masz<br>zainstalowane CompactCMS w podkatalogu, a następnie ustawić. htaccess odpowiednio.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>Wybrany plik wydaje się być usunięte</h1> <p>Odśwież listę plików, aby zobaczyć najnowsze listy dostępnych plików, aby zapobiec temu błędowi dzieje. Jeśli to nie rozwiąże tego problemu, należy ręcznie sprawdzić zawartość folderu dla pliku, który próbujesz otworzyć.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "Nie znaleziono pliku";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "404 Wystąpił błąd, żądany plik <strong>{%pagereq%}.html</strong> nie został odnaleziony.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "Żądany plik <strong>{%pagereq%}.html</strong> nie można znaleźć.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Zakazany";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "403 Wystąpił błąd: nie masz uprawnień dostępu do żądanego pliku.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Nie masz uprawnień do dostępu <strong>{%pagereq%}.html</strong> w tej chwili.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "Zestawienie wszystkich stron";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "Jedna lub wiele wartości przedstawione były albo zbyt krótkie lub niewłaściwe";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Hasło powinno zawierać więcej niż 6 znaków";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Wpisane hasła nie pasują";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Brak wyników";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "Powrót do pierwotnego";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "Wszelkie prawa zastrzeżone";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "Pomyślnie przetestowane na";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "Masz sugestie, opinie lub problem rozwiązać? Odwiedź <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>forum</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "Użyj rozwijanych poniżej w celu odzwierciedlenia struktury strony w menu witryny. Należy pamiętać, że system nie uwzględnia dwóch egzemplarzach pod górę lub kombinacji poziomie.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "Aby utworzyć nową stronę, wypełnić formularz poniżej a nowe strony będą tworzone dla Ciebie na bieżąco. Po utworzeniu pliku, będziesz mógł edytować strony, jak zwykle.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "W aukcji poniżej znajdą Państwo wszystkie strony, które obecnie publikowane. Zauważysz, że stroną domyślną plik nie może być usunięty, ponieważ jest obecny stronie głównej Twojej witryny. Inne pliki mogą mieć ograniczony dostęp, ponieważ administrator ma wyłączne prawo własności tych plików.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "Proszę potwierdzić, że chcesz usunąć zaznaczony element (y).";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Proszę potwierdzić, że chcesz wygenerować wszystkie miniatury.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Zmiany zostały zapisane.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Pamiętaj, aby przeładować stronę główną, aby zobaczyć <strong>wszystkie</strong> zmiany";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Pomyślnie przetworzył złożony produkt (y).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Pomyślnie usunięto zaznaczony element (y).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Pomyślnie regenerowanej miniatur.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Powrót do przeglądu";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Ustaw uprawnienia CCMS";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definiowanie właścicieli treści";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Edytor szablonu";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Zarządzanie użytkownikami";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "Kliknij, aby zmienić";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "Podgląd";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "Edytuj";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "Ograniczony";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "Ten plik jest świeże i czyste, aby wypełnić-up!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "Plik został utworzony";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "Rozpocznij edycję!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "Nowy plik został utworzony. Rozpocznij edycję albo od razu lub dodać więcej stron lub zarządzać bieżących.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "Sukces!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "Plik istnieje";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "Status skasowanie:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "usunięte";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "aktualne.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "nieaktualne.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "Zalecamy aktualizację";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "Preferencje dla kolejność elementów menu zostały zapisane.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "W menu";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "Aktualizacja listy plików";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "Administracja";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "Aktualnie używana wersja";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "Najnowsza stabilna wersja CompactCMS jest";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "Instalacja została";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "Tworzenie nowej strony";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "Zarządzanie menu";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "Kierowanie bieżącymi stron";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "Usuń";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "Najwyższy poziom";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "poziomie niższym";
$ccms['lang']['backend']['active'] = "Active";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "Niepełnosprawnych";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Szablon";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "Pozycja nie w menu";
$ccms['lang']['backend']['menutitle'] = "Menu";
$ccms['lang']['backend']['linktitle'] = "Link";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "Pozycja";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Brak";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "Tak";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "Nr";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Tłumaczenia";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Witam";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "Zaloguj się";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Zobacz forum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Wpisz uważnie swój login i hasło, aby uzyskać dostęp do zaplecza-CompactCMS. Jeśli przybył tu przez pomyłkę, wróć do <a href='admin/includes/'>strony głównej</a>.</p> <p>Skontaktuj się z webmasterem na swoje dane.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Nazwa użytkownika";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Hasło";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Zaloguj się";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Prosimy o podanie poświadczeń użytkownika";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Podaj zarówno nazwę użytkownika i hasło";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Wprowadź swoją nazwę użytkownika";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Wpisz swoje hasło";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "To konto zostało wyłączone";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Należy pamiętać, że już wielu prób";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Niepoprawna nazwa użytkownika lub hasło";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "Główne";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Pozostało";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Prawo";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Stopka";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "Dodatkowe";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "Nazwa pliku";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "Tytuł strony";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "Nagłówka";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "Opis";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Moduł";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "Zawartość pamięci podręcznej (domyślnie)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Dodatki";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "Do druku";
$ccms['lang']['forms']['published'] = "Active";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "Kodowanie";
$ccms['lang']['forms']['createbutton'] = /* BABELFISH */ "Stwórz!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Modyfikować";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "Zapisz";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end język";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "Teraz jesteśmy tylko wyświetlania stron, które mają co najmniej ten tekst tutaj";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Edytuj lub usuń filtr";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Dodaj filtr";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "URL strony (home.html): nazwy pliku, która strona jest wezwany (bez html).";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "Tytuł dla tej strony (główna): Krótki opisowy tytuł dla tej strony.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "Krótki tekst nagłówka (Witamy na naszej stronie): Krótki opis tekstowy używany w nagłówku każdej strony, jak w tytule każdej strony.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "Meta Description: unikalną nazwę dla tej strony, który będzie używany jako opis strony 'meta.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Moduł :: Wybierz co moduł powinien obsługiwać zawartość tego pliku. Jeśli nie jesteś pewien, wybierz domyślny.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "nadruku Page :: Po wybraniu 'TAK' do druku strona jest generowana. 'NIE' powinno być wybrane do stron ze zdjęciami lub inne media. Są to lepiej bez wydruku strony.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "status Opublikowano :: Wybierz, jeżeli strony powinny być publikowane, jeśli to będzie zawarte w mapie witryny i czy będzie on dostępny dla publiczności.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "Najwyższy poziom: Określ najwyższym poziomie dla tej pozycji menu.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "poziom Sub :: Wybierz 0, gdy pozycja ta powinna być pozycja w poziomie. Jeśli jest to pozycja sub na określonym poziomie top, proszę wybrać odpowiedni poziom sub.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "Szablon :: Jeśli korzystasz z wielu szablonów do instalacji można ustanowić oddzielnych szablonów do poszczególnych stron przy użyciu tego rozwijanego.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "Aktywny link w menu? :: Nie wszystkie elementy muszą zawsze rzeczywisty związek. Aby wyłączyć za tym link w menu front-end, usuń zaznaczenie pola wyboru poniżej jej.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "Menu :: Wybierz menu, które w tej pozycji powinny być wymienione w domyślnym jest menu główne (1), gdzie również link do strony startowej powinny być widoczne.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "Zawiera kodowanie :: Czy ten plik zawiera kod obsługi dodał, takie jak PHP czy JavaScript? Wybranie opcji 'Tak' spowoduje ograniczenie dostępu do pliku z back-end edytor WYSIWYG i pozwala na edytor kodu.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Możesz kliknąć na <span class='sprite livefilter livefilter_active'> ikonę filtru po lewej stronie tytułu dodawać, edytować lub usunąć tekst do filtrowania listy na stronie, np. po wpisaniu 'domu' w polu edycji, które pojawia się po kliknięciu na ikonę, a następnie naciśnij Enter klawisz Return, tylko strony, które w tekście 'domu' w tej kolumnie będą wyświetlane. <br>Kliknięcie na tę ikonę ponownie i usuwanie tekstu w polu edycji, a następnie naciskając Enter / klawisz Enter, spowoduje usunięcie filtra.<br>Najedź kursorem myszy na ikonę filtru, aby zobaczyć, czy kolumna jest obecnie sączy się, a jeśli tak, za pomocą którego filtr tekstu.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "Zamknij edytor";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "Edytor tekstu dla";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "Skorzystaj z edytora poniżej modyfikacji bieżącego pliku. Kiedy skończysz, kliknij na 'Zapisz zmiany' aby bezpośrednio opublikować zmiany w World Wide Web. Można też dodać do dziesięciu odpowiednich słów kluczowych dla optymalizacji wyszukiwarek.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "Zapisz zmiany";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "Anuluj";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "Zamknij okno i odrzucić zmiany?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "Podgląd strony wyników";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>Sukces!</strong> treści jak poniżej został zapisany";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "Powrót do edytora";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "Zamknij okno";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "Słowa kluczowe - <em>oddzielając je przecinkami, max 250 znaków</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto wygenerować bezpieczne hasło";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Aktualnego poziomu konta nie pozwala na korzystanie z tej funkcji.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Utwórz nowe back-up";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "Aby uniknąć utraty danych z powodu do tego, co zewnętrzne zdarzenie, to mądry, aby tworzyć kopie zapasowe plików regularnie.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Uwaga: Należy pamiętać, że <dfn>lightbox</dfn> 'obrazy albumy są <strong>nie</strong> poparte opisy! albumu <strong>są</strong>, ale samych obrazów i ich miniaturki <strong>nie są wliczone w te kopie</strong>z. Jeśli chcesz kopie tych, to wtedy będzie musiał przyznać administratora witryny o dodatkowy system tworzenia kopii zapasowych, które pomogą Ci kopii zapasowej i przywracania tych (ewentualnie duży) zbiory plików.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Dostępne Back-UPS";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "Back-up nazwę pliku";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Pobierz archiwum";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Poczekaj kopii zapasowej jest tworzony ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Utwórz użytkownika";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Przegląd użytkowników CCMS";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Edytuj dane osobowe użytkownika";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Edytuj hasło użytkownika";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Ustawienia konta";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Użytkownik";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Nazwa użytkownika";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Nazwa";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Imię";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Ostatnie nazwa";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Hasło";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Potwierdź hasło";
$ccms['lang']['users']['email'] = "E-mail";
$ccms['lang']['users']['active'] = "Active";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Poziom";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "poziomie użytkownika";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Ostatnio zalogowany";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Zarządzanie szablonami";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Obecny szablon jest <strong>nie</strong> do zapisu";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Drukuj";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "preferencje uprawnień";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Skorzystaj z poniższej tabeli, aby określić minimalny poziom, co użytkownik może korzystać z niektórych funkcji. Każdy użytkownik poniżej określonego minimalnego poziomu wymaganego użytkownika, nie będzie widać ani mieć dostęp do tej funkcji.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Cel";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Poziom 1 - User";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Poziom 2 - Edytor";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Poziom 3 - Manager";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Poziom 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Właściciele treści";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Tutaj można wyznaczyć konkretne własności strony indywidualnych użytkowników. Jeśli na danej stronie nie są wybrane użytkowników, każdy może zmodyfikować stronę. W przeciwnym wypadku tylko określony użytkownik ma prawa modyfikacji pliku. Administratorzy zawsze mają dostęp do wszystkich plików.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Strony";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Użytkownicy";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Tłumacz";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Pokazuje ciągi tłumaczenia.";

// Album messages
$ccms['lang']['album']['album'] = "Album";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Album ustawienia";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "W szczególności stosuje się do tego albumu";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Album opis";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Obecna albumów";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Prześlij treści";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Prześlij do istniejącego albumu";
$ccms['lang']['album']['upload'] = "Start upload";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Przeglądaj pliki";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Wyczyść listę";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Single upload plików</strong><br> <p>Ładowarka Flash failed to initialize. Upewnij się, że jest włączony Javascript i Flash jest zainstalowana. Single wysyłania plików są możliwe, ale nie zoptymalizowany.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "Zarządzanie album";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Lista albumów";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Regeneruje wszystkie miniatury";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "Tworzenie nowego albumu";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Brak albumów zostały utworzone jeszcze!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Pliki";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "Upewnij się, że katalog <strong>albumów</strong> istnieje w. / media / katalogu";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Ostatnia modyfikacja";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Proszę czekać ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Zarządzanie bieżących newsów";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Dodaj wiadomości";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Napisz nowy artykuł";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Zarządzanie ustawieniami";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Napisz wiadomość";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# Wiadomości na front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "o autorze";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Pokaż data publikacji";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Pokaż tylko teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "tytuł News";
$ccms['lang']['news']['author'] = /* BABELFISH */ "autor News";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Data";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Opublikowano?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Spis treści artykułu";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Zobacz archiwum";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Brak reakcji zostały opublikowane jeszcze!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Aby sprawdzić, że ta wiadomość nie jest zautomatyzowany, należy ponownie wprowadzić";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reakcji";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Ocena";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "avatar użytkownika Gravatar.com";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "napisał";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Zarządzanie reakcje";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Usuń ten wpis";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail autora";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "został usunięty z bazy danych.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Twoje imię i nazwisko";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Twój e-mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Twoja strona";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Komentarze";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Weryfikacji";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Podgląd komentarz";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Dodaj swój komentarz";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "Twój komentarz został opublikowany!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Dziękuję";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Błędy i Odrzucenia";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Twój komentarz został odrzucony.";


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
