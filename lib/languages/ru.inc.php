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

/* Russian translation by Ilya <icherevkov@gmail.com> */

// System wide messages
$ccms['lang']['system']['error_database'] = "Невозможно подключиться к базе данных. Пожалуйста, проверьте ваш логин и название базы данных в настройках.";
$ccms['lang']['system']['error_openfile'] = "Невозможно открыть указанный файл.";
$ccms['lang']['system']['error_notemplate'] = "Не найдено ни одного шаблона, который мог бы быть использован для вашего сайта. Пожалуйста, добавьте как минимум один шаблон в ./lib/templates/.";
$ccms['lang']['system']['error_templatedir'] = "Не найдена директория с шаблонами! Пожалуйста, убедитесь, что она существует и содержит как минимум один шаблон.";
$ccms['lang']['system']['error_write'] = "Файл не имеет разрешения на запись.";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Directory не имеет доступ на запись";
$ccms['lang']['system']['error_chmod'] = "Данный файл не может быть изменен. Проверьте разрешения в /content directory (666).";
$ccms['lang']['system']['error_value'] = "Ошибка: недопустимое значение.";
$ccms['lang']['system']['error_default'] = "Страница, заданная по умолчанию, не может быть удалена.";
$ccms['lang']['system']['error_filedots'] = "Имя файла не должно включать точки, например '.html'.";
$ccms['lang']['system']['error_filesize'] = "Название файла должно состоять как минимум из трех символов.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Имя файла должно быть не более 50 символов.";
$ccms['lang']['system']['error_pagetitle'] = "Введите название страницы, состоящее из как минимум 3 символов или более.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Введите название страницы из 100 символов или менее.";
$ccms['lang']['system']['error_subtitle'] = "Дайте краткий подзаголовок вашей странице.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Введите краткий подзаголовок 200 символов или менее для вашей страницы.";
$ccms['lang']['system']['error_description'] = "Введите описание из более чем трех символов";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Введите описание менее 250 символов";
$ccms['lang']['system']['error_reserved'] = "Файл с заданным названием уже существует";
$ccms['lang']['system']['error_general'] = "Произошла ошибка";
$ccms['lang']['system']['error_correct'] = "Пожалуйста исправьте следующее:";
$ccms['lang']['system']['error_create'] = "Произошла ошибка во время создания нового файла";
$ccms['lang']['system']['error_exists'] = "Заданное имя уже существует.";
$ccms['lang']['system']['error_delete'] = "Произошла ошибка во время удаления выбранного файла";
$ccms['lang']['system']['error_selection'] = "Ни один файл не был выбран.";
$ccms['lang']['system']['error_versioninfo'] = "Информация о версии программы недоступна.";
$ccms['lang']['system']['error_misconfig'] = "<strong>Похоже, вы недонастроили систему до конца.</strong><br/>Пожалуйста, убедитесь, что файл .htaccess настроен правильно. Если вы <br/>установили CompactCMS в какую-либо поддиректрию, то настройте файл .htaccess соответственно.";
$ccms['lang']['system']['error_deleted'] = "<h1>Файл, который вы выбрали, похоже удален.</h1><p>Обновите ваш список файлов для того, чтобы избежать этой ошибки. Если это не помогает, вручную проверьте каталог с файлом, который вы хотите открыть.</p>";
$ccms['lang']['system']['error_404title'] = "Файл не найден";
$ccms['lang']['system']['error_404header'] = "Ошибка 404, запрашиваемый файл <strong>{%pagereq%}.html</strong> не найден.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "Требуемый файл <strong>{%pagereq%}.html</strong> не может быть найдено.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Запрещено";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "403 ошибка: вы не имеете доступа к запрошенному файлу.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Вы не разрешен доступ к <strong>{%pagereq%}.html</strong> в этот момент.";
$ccms['lang']['system']['error_sitemap'] = "Просмотр всех страниц";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "Один или несколько представленные значения были либо слишком короткий или неправильного";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Пароль должен содержать не более 6 символов";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Пароли не совпадают";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Ничего не найдено";
$ccms['lang']['system']['tooriginal'] = "Вернуться к оригиналу";
$ccms['lang']['system']['message_rights'] = "Все права защищены";
$ccms['lang']['system']['message_compatible'] = "Успешно протестировано с";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = "Есть предложения, комментарии или вы столкнулись с проблемой? Посетите <a href='http://community.compactcms.nl/forum/' title='Посетите официальный форум' class='external' rel='external'>форум</a>!";
$ccms['lang']['backend']['ordertip'] = "Используйте эту панель для отображения структуры страниц в меню вашего сайта. Помните, что система не предполагает повторения комбинаций одного и того же уровня и подуровня.";
$ccms['lang']['backend']['createtip'] = "Для создания новой страницы заполните форму ниже, и новая страница будет создана для вас без обновления панели. После того, как страница будет создана, вы сможете отредактировать ее как обычно.";
$ccms['lang']['backend']['currentfiles'] = "В списке ниже вы найдете все уже когда-либо опубликованные страницы. Учтите, что вы не можете удалить файл, заданный как главная страница вашего сайта по умолчанию. Некоторые файлы могут иметь ограниченный доступ, так как администратор установил исключительные права на эти файлы.";
$ccms['lang']['backend']['confirmdelete'] = "Пожалуйста, подтвердите, что вы хотите удалить отмеченное со всем содержимым.";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Пожалуйста, подтвердите, что вы хотите, чтобы регенерировать все эскизы.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Изменения были успешно сохранены.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Пожалуйста, не забудьте перезагрузить главной странице, чтобы увидеть <strong>все</strong> изменения";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Успешно обработанные представленный товар (ов).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Успешно удален выбранный элемент (ы).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Успешно регенерировать миниатюр.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Вернуться к списку";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Установить КВСО разрешения";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Определить содержание владельцев";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Редактор шаблонов";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Управление пользователями";
$ccms['lang']['backend']['changevalue'] = "Кликните для изменения";
$ccms['lang']['backend']['previewpage'] = "Просмотр";
$ccms['lang']['backend']['editpage'] = "Изменить";
$ccms['lang']['backend']['restrictpage'] = "Доступ запрещен";
$ccms['lang']['backend']['newfiledone'] = "Файл готов к наполнению!";
$ccms['lang']['backend']['newfilecreated'] = "Файл был создан";
$ccms['lang']['backend']['startedittitle'] = "Начинайте редактирование!";
$ccms['lang']['backend']['starteditbody'] = "Новый файл был создан. Начинайте редактирование прямо сейчас, либо добавьте больше страниц или измените существующие. ";
$ccms['lang']['backend']['success'] = "Успех!";
$ccms['lang']['backend']['fileexists'] = "Файл существует";
$ccms['lang']['backend']['statusdelete'] = "Статус файлов, отмеченных на удаление:";
$ccms['lang']['backend']['statusremoved'] = "удалено";
$ccms['lang']['backend']['uptodate'] = "не нуждается в обновлении.";
$ccms['lang']['backend']['outofdate'] = "устарела.";
$ccms['lang']['backend']['considerupdate'] = "Подтвердите обновление";
$ccms['lang']['backend']['orderprefsaved'] = "Порядок элементов в меню был сохранен.";
$ccms['lang']['backend']['inmenu'] = "В меню";
$ccms['lang']['backend']['updatelist'] = "Обновить список файлов";
$ccms['lang']['backend']['administration'] = "Администрация";
$ccms['lang']['backend']['currentversion'] = "Сейчас вы используете версию";
$ccms['lang']['backend']['mostrecent'] = "Наиболее стабильная и актуальная версия - ";
$ccms['lang']['backend']['versionstatus'] = "Ваша система";
$ccms['lang']['backend']['createpage'] = "Создать новую страницу";
$ccms['lang']['backend']['managemenu'] = "Управление меню";
$ccms['lang']['backend']['managefiles'] = "Управление файлами";
$ccms['lang']['backend']['delete'] = "Удалить";
$ccms['lang']['backend']['toplevel'] = "Уровень";
$ccms['lang']['backend']['sublevel'] = "Подуровень";
$ccms['lang']['backend']['active'] = "Активно";
$ccms['lang']['backend']['disabled'] = "Отключено";
$ccms['lang']['backend']['template'] = "Шаблон";
$ccms['lang']['backend']['notinmenu'] = "Элемент не в меню";
$ccms['lang']['backend']['menutitle'] = "Меню";
$ccms['lang']['backend']['linktitle'] = "Ссылка";
$ccms['lang']['backend']['item'] = "Элемент";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Никто";
$ccms['lang']['backend']['yes'] = "Да";
$ccms['lang']['backend']['no'] = "Нет";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Переводы";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Привет";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "Выйти";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "См. форум";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Используйте действительное имя пользователя и пароль, чтобы получить доступ к спине CompactCMS-End. Если вы приехали сюда по ошибке, вернуться к <a href='admin/includes/'>главной странице</a>.</p> <p>Обратитесь к веб-мастеру для Ваших данных.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Имя пользователя";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Пароль";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Войти";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Пожалуйста, укажите учетные данные пользователя";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Введите как имя пользователя и пароль";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Введите имя пользователя";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Введите свой пароль";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Эта учетная запись была отключена";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Обратите внимание, что Вы уже сделали несколько попыток";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Неправильное имя пользователя или пароль";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "Главная";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Слева";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Право";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Нижний колонтитул";
$ccms['lang']['menu']['5'] = /* BABELFISH */ "Дополнительные";

// Administration form related texts
$ccms['lang']['forms']['filename'] = "Название";
$ccms['lang']['forms']['pagetitle'] = "Заголовок";
$ccms['lang']['forms']['subheader'] = "Подзаголовок";
$ccms['lang']['forms']['description'] = "Описание";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Модуль";
$ccms['lang']['forms']['contentitem'] = "Информационная страница (по умолчанию)";
$ccms['lang']['forms']['additions'] = "Дополнения";
$ccms['lang']['forms']['printable'] = "Печать";
$ccms['lang']['forms']['published'] = "Активно";
$ccms['lang']['forms']['iscoding'] = "Код";
$ccms['lang']['forms']['createbutton'] = "Создать!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Изменить";
$ccms['lang']['forms']['savebutton'] = "Сохранить";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Фронтальный языке";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "Сейчас мы только показывать страницы, которые, по крайней мере этот текст здесь";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Изменить или удалить фильтр для";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Добавить фильтр для";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = "URL страницы (home.html):: Имя файла, в котором хранится страница (без .html).";
$ccms['lang']['hints']['pagetitle'] = "Название страницы (Home) :: Краткий заголовок страницы.";
$ccms['lang']['hints']['subheader'] = "Краткий подзаголовок (Приветствуем вас на нашем сайте) :: Краткое описание, которое используется в начале каждой страницы так же, как и заголовок.";
$ccms['lang']['hints']['description'] = "Meta-описание :: Meta-описание вашей страницы.";
$ccms['lang']['hints']['module'] = "Модуль :: Выберите, какой модуль будет использоваться для отображения данной страницы. Если вы не уверены, выберите модуль по умолчанию.";
$ccms['lang']['hints']['printable'] = "Возможность печати страницы :: Выберите 'ДА', если хотите, чтобы на странице отображалась кнопка печати. Выберите 'НЕТ', если на странице присутствуют изображения или другое медиа-содержание. Страницы с подобным содержанием могут печататься некорректно.";
$ccms['lang']['hints']['published'] = "Статус публикации :: Выберите, должна ли данная страница быть опубликованна, отображена на карте сайта, и может ли она быть доступна для посетителей.";
$ccms['lang']['hints']['toplevel'] = "Уровень :: Выберите уровень для данного меню. Выберите --- для того, чтобы не включать страницу в меню.";
$ccms['lang']['hints']['sublevel'] = "Подуровень :: Выберите 0, если этот элемент должен быть основным. Если это подэлемент конкретного элемента меню, пожалуйста, выберите соответствующий подуровень.";
$ccms['lang']['hints']['template'] = "Шаблон :: Если вы используете несколько шаблонов на вашем сайте, то вы можете назначить каждой странице сайта соответствующий шаблон.";
$ccms['lang']['hints']['activelink'] = "Активность ссылки в меню :: Не все элементы всегда нуждаются в ссылке. Для деактивации ссылки с этого элемента снимите галочку с чекбокса ниже.";
$ccms['lang']['hints']['menuid'] = "Меню :: Выберите в каком меню должен содержаться данный элемент. Основное меню задано по умолчанию (1), это то меню где отображается ссылка на страницу home.";
$ccms['lang']['hints']['iscoding'] = "Разрешить коды :: Будет ли данная страница содержать какой-либо вручную добавленный код, такой как PHP или Javascript? При выборе 'ДА', система запретит доступ к визуальному редактору WYSIWYG и активирует редактор кода.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Вы можете нажать на <span class='sprite livefilter livefilter_active'> значок фильтра слева от названия добавить, изменить или удалить текст для фильтрации списка на странице, например, когда вы 'типа' дом в поле редактирования, который появляется при нажатии на значок, а затем нажмите Enter / Вернуться ключ, только страницы, которые имеют дома 'текст' в этом столбце будет показан. <br>Щелкнув значок снова и удаление текста в поле редактирования, нажав клавишу Enter / Return ключ, будет удалить фильтр.<br>Наведите курсор мыши на значок фильтра, чтобы увидеть, является ли столбец в настоящее время фильтруется, и если да, то с помощью которой текст фильтра.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = "Закрыть редактор";
$ccms['lang']['editor']['editorfor'] = "Текстовый редактор для";
$ccms['lang']['editor']['instruction'] = "Используйте редактор ниже для изменения текущего файла. Окончив редактирование, нажмите кнопку 'Сохранить изменения' для публикации вашей странице на сайте. Не забудьте добавить до десяти ключевых слов для поисковой оптимизации.";
$ccms['lang']['editor']['savebtn'] = "Сохранить изменения";
$ccms['lang']['editor']['cancelbtn'] = "Отмена";
$ccms['lang']['editor']['confirmclose'] = "Закрыть это окно без сохранения изменений?";
$ccms['lang']['editor']['preview'] = "Просмотр конечной страницы";
$ccms['lang']['editor']['savesuccess'] = "<strong>Успех! </strong>Информация, как показано ниже, была сохранена в ";
$ccms['lang']['editor']['backeditor'] = "Вернуться в редактор";
$ccms['lang']['editor']['closewindow'] = "Закрыть окно";
$ccms['lang']['editor']['keywords'] = "Ключевые слова - <em>разделенные запятыми, макс. 250 символов</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Автоматическое создание безопасных паролей";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Ваш текущий уровень учетной записи не позволяет использовать эту функцию.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = "Создать новый бэк-ап";
$ccms['lang']['backup']['explain'] = "Для предотвращения потери данных в результате какой-либо ошибки рекомендуется регулярно делать резервную копию ваших файлов.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Предупреждение: Помните, что ваш <dfn>лайтбокс</dfn> 'изображения альбомы <strong>не</strong> подкреплены описанием! альбома <strong>являются</strong>, но сами изображения и их эскизы <strong>, не включенных в эти резервные копии</strong>с. Если вы хотите сохранять резервные копии тех, то вам необходимо придать администратор сайта о дополнительных система резервного копирования, чтобы помочь вам резервного копирования и восстановления этих (возможно, большая) коллекции файлов.";
$ccms['lang']['backup']['currenthd'] = "Доступные бэк-апы";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "Резервное имя файла";
$ccms['lang']['backup']['download'] = "Загрузить архив";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Пожалуйста, подождите, пока резервная копия создается ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Создайте пользователя";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Обзор КВСО пользователей";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Изменить личные данные пользователей";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Изменить пароль пользователя";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Настройки аккаунта";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Пользователь";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Имя пользователя";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Название";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Имя";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Последнее название";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Пароль";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Подтверждение пароля";
$ccms['lang']['users']['email'] = /* BABELFISH */ "Электронная почта";
$ccms['lang']['users']['active'] = /* BABELFISH */ "Активный";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Уровень";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "На уровне пользователя";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Последнее журнала";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Управление шаблонами";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Текущий шаблон <strong>не</strong> для записи";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Печать";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Разрешение предпочтения";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Используйте таблицу ниже, чтобы указать, что минимальный уровень пользователь может использовать определенные возможности. Любой пользователь ниже установленного минимального требуемого уровня пользователя, не видят и не имеют доступа к этой функции.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Целевой";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Уровень 1 - Пользователь";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Уровень 2 - редактор";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Уровень 3 - менеджер";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Уровень 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Владельцы содержимого";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Здесь вы можете назначить конкретные страницы собственности для отдельных пользователей. Если для определенной страницы ни один пользователь не выбраны, каждый может изменить страницу. В противном случае только указанный пользователь был модификации прав на файл. Администраторы всегда имеют доступ ко всем файлам.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Страницы";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Пользователи";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Перевести";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Показывает перевод строки.";

// Album messages
$ccms['lang']['album']['album'] = "Альбом";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Альбом настройки";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "В частности применять этот альбом";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Описание альбома";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Текущий альбомы";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Добавить содержание";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Отправить в существующий альбом";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Начать загрузку";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Обзор файлов";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Очистить список";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Одноместный загрузки файлов</strong><br> <p>Погрузчик Flash Сбой инициализации. Убедитесь, что Javascript включен и установлен Flash. Одноместный добавления файлов возможно, но не оптимизированы.</p>";
$ccms['lang']['album']['manage'] = "Управление альбомами";
$ccms['lang']['album']['albumlist'] = "Список альбомов";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Восстановить все миниатюры";
$ccms['lang']['album']['newalbum'] = "Название нового альбома";
$ccms['lang']['album']['noalbums'] = "Ни одного альбома пока не создано!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Файлы";
$ccms['lang']['album']['nodir'] = "Пожалуйста, проверьте, что папка <strong>albums</strong> существует в вашем каталоге с изображениями";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Изменен";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Пожалуйста, подождите ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Управление текущей новостей";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Добавить новость";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Создать новую статью";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Управление настройками";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Создать новости";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# Сообщения на интерфейсных";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Показать автора";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Показать дату публикации";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Показывать только тизер";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Новости титул";
$ccms['lang']['news']['author'] = /* BABELFISH */ "Новости автора";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Дата";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Опубликован?";
$ccms['lang']['news']['teaser'] = /* BABELFISH */ "Задира";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Статья содержание";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Просмотр архива";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = "Ни одного комментария еще не было оставлено";
$ccms['lang']['guestbook']['verinstr'] = "Для проверки, что сообщение не автоматическое, пожалуйста введите";
$ccms['lang']['guestbook']['reaction'] = "Комментарий";
$ccms['lang']['guestbook']['rating'] = "Рэйтинг";
$ccms['lang']['guestbook']['avatar'] = "Аватар Gravatar.com";
$ccms['lang']['guestbook']['wrote'] = "написал";
$ccms['lang']['guestbook']['manage'] = "Управлять комментариями";
$ccms['lang']['guestbook']['delentry'] = "Удалить эту запись";
$ccms['lang']['guestbook']['sendmail'] = "Сообщение по e-mail автору";
$ccms['lang']['guestbook']['removed'] = "был удален из базы данных.";
$ccms['lang']['guestbook']['name'] = "Ваше имя";
$ccms['lang']['guestbook']['email'] = "Ваш e-mail";
$ccms['lang']['guestbook']['website'] = "Ваш веб-сайт";
$ccms['lang']['guestbook']['comments'] = "Комментарии";
$ccms['lang']['guestbook']['verify'] = "Подтверждение";
$ccms['lang']['guestbook']['preview'] = "Предпросмотр комментария";
$ccms['lang']['guestbook']['add'] = "Добавить комментарий";
$ccms['lang']['guestbook']['posted'] = "Комментарий опубликован!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Спасибо";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Неудачи и Отказы";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Ваш комментарий был отклонен.";


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
$ccms['lang']['backend']['rename_file']         = "Rename file";
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
$ccms['lang']['system']['error_forged']         = "You attempted to access site areas for which you are not authorized";
$ccms['lang']['system']['error_rec_exists']     = "Entry already exists in the database";
$ccms['lang']['system']['error_rename']         = "Failed to rename the file";
$ccms['lang']['system']['error_rename_target_exists'] = "Cannot rename the file as a file with the new name already exists";
$ccms['lang']['system']['error_session_expired'] = "Your session has expired or you are not logged in";
$ccms['lang']['system']['home']                 = "Home";
       
      /*
         ----------------------------------------------------------
      */
	  
?>
