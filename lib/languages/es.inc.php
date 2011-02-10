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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "No se pudo conectar a la base de datos. Por favor, compruebe sus datos de acceso y el nombre de base de datos.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "No se puede abrir el archivo especificado";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "No se pudo encontrar plantillas que se aplicará a su sitio. Por favor, añada al menos una plantilla. / Lib / templates /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "No se pudo encontrar el directorio de plantillas! Asegúrese de que existe y contiene por lo menos una plantilla.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "El archivo no tiene acceso de escritura";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Directorio no tiene acceso de escritura";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "El archivo actual no puede ser modificado. Compruebe los permisos en los archivos en el directorio / contenido (666).";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "Error: valor incorrecto";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "la página predeterminada no se pueden eliminar.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "Valor ha sido manipulado";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "Nombre de archivo no debe contener puntos, por ejemplo, '. Html'.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "Nombre de archivo debe ser de al menos 3 caracteres de longitud.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Nombre de archivo debe tener un máximo de 50 caracteres de largo.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "Introduzca un título de la página de 3 caracteres o más.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Introduzca un título de la página de 100 caracteres o menos.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "Dar un sub-título corto de la página.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Introduzca un sub-título corto de 200 caracteres o menos para su página.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "Introduzca una descripción de más de 3 caracteres";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Introduzca una descripción de menos de 250 caracteres";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "Se ha especificado un nombre de archivo reservado para uso interno.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "Se produjo un error";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "Por favor, corrija el texto siguiente:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "Error al completar la creación del nuevo archivo";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "El nombre del archivo que ha especificado ya existe.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "Error al completar la eliminación del archivo seleccionado";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "No se encontraron elementos seleccionados.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "No hay información de la versión está disponible.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>Parece haber una configuración incorrecta.</strong><br>Por favor, asegúrese de que el archivo htaccess. Esté correctamente configurado para reflejar la estructura de archivos. Si usted tiene<br>CompactCMS instalado en un subdirectorio, a continuación, modifica el archivo. Htaccess en consecuencia.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>El archivo seleccionado parece ser eliminado</h1> <p>Actualizar la lista de archivos para ver la lista más reciente de los archivos para evitar que este error se produzca. Si esto no resuelve el problema, comprobar manualmente la carpeta de contenido para el archivo que está intentando abrir.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "Archivo no encontrado";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "Un error 404 se produjo, el archivo solicitado <strong>{%pagereq%}.html</strong> no se encontró.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "El archivo solicitado <strong>{%pagereq%}.html</strong> no se pudo encontrar.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Prohibida";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "Un error 403 se produjo: usted no tiene permiso de acceso al archivo solicitado.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "No se le permite acceder a <strong>{{%pagereq%}.html</strong> en este momento.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "Una visión general de todas las páginas";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "valores presentados Uno o varios eran demasiado cortas o incorrecta";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Una contraseña debe contener más de 6 caracteres";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Las contraseñas introducidas no coinciden";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "No hay resultados";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "Volver a la original";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "Todos los derechos reservados";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "Probado con éxito en";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "¿Tienes sugerencias, comentarios o problemas? Visita <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>el foro</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "Utilice los siguientes menús desplegables para reflejar la estructura de sus páginas en el menú de su sitio. Tenga en cuenta que el sistema no tiene en cuenta duplicada combinaciones de nivel superior o secundaria.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "Para crear una nueva página, llena el siguiente formulario y una nueva página será creada para usted sobre la marcha. Después de que el archivo ha sido creado, usted podrá editar la página como de costumbre.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "En la lista a continuación usted encontrará todas las páginas que publican actualmente. Se dará cuenta de que la página por defecto del archivo no puede ser eliminado, porque es la página principal actual de su sitio web. Otros archivos pueden tener acceso restringido debido a que el administrador tiene la propiedad exclusiva sobre estos archivos.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "Por favor, confirme que desea eliminar el elemento seleccionado (s).";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "Por favor, confirme que desea volver a generar todas las miniaturas.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Los cambios se han guardado correctamente.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "Por favor, asegúrese de cargar la página principal para ver <strong>todos</strong> los cambios";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "Procesado con éxito el tema presentado (s).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Se ha eliminado correctamente el elemento seleccionado (s).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Regenerado con éxito las miniaturas.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Volver a la vista";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Establecer permisos de CCMS";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Definir los propietarios de contenido";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "Editor de plantillas";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Administración de usuarios";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "Haga clic para cambiar";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "Prevista";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "Editar";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "Restringidos";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "Este archivo es fresco y limpio para que usted pueda llenar para arriba!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "El archivo ha sido creado";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "edición de Start!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "El nuevo archivo se ha creado. Comience a editar de inmediato o bien agregar más páginas o administrar los actuales.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "Éxito!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "El archivo ya existe";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "Condición Jurídica y Social de la supresión seleccionado:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "eliminado";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "hasta la fecha.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "anticuado.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "Considere la posibilidad de actualización";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "Sus preferencias para el orden de los elementos de su menú se han guardado.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "En el menú";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "Actualizar lista de archivos";
$ccms['lang']['backend']['administration'] = /* BABELFISH */ "Administración";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "Actualmente se encuentra ejecutando la versión";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "La versión estable más reciente es CompactCMS";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "Su instalación es";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "Crear una nueva página";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "Administrar menú";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "Administrar las páginas actuales";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "Eliminar";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "Nivel superior";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "Sub nivel";
$ccms['lang']['backend']['active'] = /* BABELFISH */ "Activo";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "Personas de movilidad reducida";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Plantilla";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "El artículo no en un menú";
$ccms['lang']['backend']['menutitle'] = /* BABELFISH */ "Menú";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "Enlace";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "Del artículo";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Ninguno";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "Sí";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "N";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Traducciones";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "¡Hola!";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "Registro de salida";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Ver el foro";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Utilice un nombre de usuario y contraseña válidos para acceder a la parte posterior CompactCMS-end. Si llegó aquí por error, vuelva a la <a href='admin/includes/'>página de inicio</a>.</p> <p>Póngase en contacto con su webmaster para sus datos.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Nombre de usuario";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Contraseña";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Iniciar sesión";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "Por favor envíe sus credenciales de usuario";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Introduzca su nombre de usuario y contraseña";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Introduzca su nombre de usuario";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Ingrese su contraseña";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Esta cuenta ha sido desactivada";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Tenga en cuenta que ya hizo varios intentos";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Incorrecto nombre de usuario o contraseña";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = /* BABELFISH */ "Principales";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Izquierda";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Derecho";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Pie de página";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "Nombre de archivo";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "Título de la página";
$ccms['lang']['forms']['subheader'] = "Subheader";
$ccms['lang']['forms']['description'] = /* BABELFISH */ "Descripción";
$ccms['lang']['forms']['module'] = /* BABELFISH */ "Módulo";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "elemento de contenido (por defecto)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Adiciones";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "Para imprimir";
$ccms['lang']['forms']['published'] = /* BABELFISH */ "Activo";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "Codificación";
$ccms['lang']['forms']['createbutton'] = "Create!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Modificar";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "Guardar";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end idioma";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "ahora sólo estamos mostrando las páginas que tienen al menos este texto aquí";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Editar o eliminar filtro para";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Añadir el filtro de";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "La URL de la página (home.html) :: El nombre del archivo que se llama a esta página (sin HTML).";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "Título de la página (Home) :: Un título breve descriptivo para esta página.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "texto del encabezado de corto plazo (Bienvenido a nuestro sitio) :: Un breve texto descriptivo utilizado en la cabecera de cada página, así como en el título de cada página.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "descripción Meta: Una descripción única de esta página que se utilizará como meta descripción de las páginas.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Módulo :: Seleccione qué módulo debe controlar el contenido de este archivo. Si no está seguro, seleccione la opción predeterminada.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "impresión de la página :: Cuando se selecciona 'SÍ' para imprimir una página se genera. 'NO' debe ser seleccionado para las páginas con fotos u otros medios. Estos están mejor sin una página para imprimir.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "el estado de publicación :: Seleccione si esta página debe ser publicada, si va a figurar en el mapa del sitio y si va a ser accesible al público.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "De nivel superior :: Especifique el nivel superior de este elemento de menú.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "nivel de sub :: Seleccione 0 cuando este tema debería ser un tema de primer nivel. Si se trata de un ítem de alto nivel determinados, por favor, seleccionar el nivel de sub apropiado.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "Plantilla :: Si utiliza varias plantillas para su instalación, puede nombrar a plantillas separadas para cada página con este menú desplegable.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "Active Link en el menú? :: No todos los artículos siempre se necesita un vínculo efectivo. Para desactivar el vínculo detrás de este elemento en el menú de aplicaciones para usuario, desactive la casilla de abajo.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "Menú :: Seleccione el menú en el que debe ser este elemento de la lista in El valor por defecto es el menú principal (1), donde también debe ser el enlace de tu página de inicio se muestra.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "Contiene la codificación :: ¿Este archivo contiene instrucciones agregó código como PHP o JavaScript? Seleccionar 'Sí' se restrinja el acceso al archivo del editor WYSIWYG el back-end y permite el editor de código.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Puede hacer clic en el <span class='sprite livefilter livefilter_active'> icono de filtro a la izquierda del título para agregar, editar o eliminar un texto para filtrar la lista en la página, por ejemplo, al «tipo» casa en el campo de edición que aparece al hacer clic en el icono, a continuación, pulse Intro / tecla de retorno, sólo las páginas que tienen en casa 'el texto' en esta columna se muestran. <br>Al hacer clic en el icono de nuevo y borrar el texto en el campo de edición, a continuación, presione la tecla Enter / Return, se eliminará el filtro.<br>Pase el ratón sobre el icono del filtro para ver si la columna está siendo filtrada, y si es así, con la que el texto del filtro.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "Cierre el editor";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "Editor de texto para";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "Utilice el editor de abajo para modificar el archivo actual. Una vez que hayas terminado, pulsa en 'Guardar cambios' botón de abajo para publicar directamente las modificaciones a la World Wide Web. También se suman a diez palabras clave relevantes para la Optimización de motores de búsqueda.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "Guardar los cambios";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "Cancelar";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "Cierre esta ventana y descartar los cambios?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "Vista previa de la página de resultados";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>El éxito!</strong> El contenido se muestra a continuación se ha guardado en";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "Volver al editor";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "Cerrar ventana";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "Palabras clave - <em>separadas por comas, máximo 250 caracteres</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Generar automáticamente una contraseña segura";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Su nivel de cuenta corriente no le permite utilizar esta característica.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Crear una nueva copia de seguridad";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "Para evitar la posible pérdida de datos debido a cualquier evento externo, es aconsejable crear copias de seguridad de sus archivos regularmente.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Advertencia: Tenga en cuenta que su <dfn>mesa de luz</dfn> 'imágenes de discos son <strong>no</strong> una copia de seguridad en las descripciones! El álbum <strong>son</strong>, pero las imágenes de sí mismos y sus miniaturas son <strong>no incluidos en estas copias de seguridad</strong>con. Si desea copias de seguridad de esos, entonces usted tendrá que confieren el administrador del sitio acerca de un sistema de copia de seguridad adicional para ayudar a hacer un backup y restaurar estos grandes) Archivo colecciones posiblemente (.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Disponible copias de seguridad";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "archivo de respaldo nombre";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "Descargar archivo";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "Por favor, espere mientras que la copia de seguridad se está creando ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Crear un usuario";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Información general de CCMS usuarios";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Edición de los detalles personales del usuario";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Modificar la contraseña del usuario";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Configuración de la cuenta";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Usuario";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Nombre de usuario";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Nombre";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Nombre";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Apellido";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Contraseña";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Confirmar contraseña";
$ccms['lang']['users']['email'] = "E-mail";
$ccms['lang']['users']['active'] = /* BABELFISH */ "Activo";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Nivel";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "A nivel de usuario";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Última sesión";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Administrar plantillas";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "La plantilla actual es <strong>no</strong> escribible";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Imprimir";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "preferencias permiso";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Utilice la tabla a continuación para especificar qué nivel de usuario mínimo puede utilizar algunas funciones. Cualquier usuario por debajo del nivel mínimo especificado de usuario necesarios, no podrá ver ni tener acceso a la función.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Meta";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Nivel 1 - Instrucciones";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Nivel 2 - Editor";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Nivel 3 - Administrador";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Nivel 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Los propietarios de contenido";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Aquí usted puede nombrar a la propiedad de página específico a los usuarios individuales. Si por una determinada página no hay usuarios seleccionados, cada uno puede modificar la página. De lo contrario sólo los especificados por el usuario tiene derechos de modificación en el archivo. Administradores siempre tienen acceso a todos los archivos.";
$ccms['lang']['owners']['pages'] = /* BABELFISH */ "Páginas";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Usuario";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Traducir";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Muestra las cadenas de traducción.";

// Album messages
$ccms['lang']['album']['album'] = /* BABELFISH */ "Álbum";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "Álbum de configuración";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "Se aplican específicamente a este álbum";
$ccms['lang']['album']['description'] = /* BABELFISH */ "Album descripción";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "Álbumes actual";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Subir contenido";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Subir a los actuales álbum";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Iniciar la carga";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Examinar los archivos";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Borrar la lista";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>archivo único de carga</strong><br> <p>El cargador de Flash no se pudo inicializar. Asegúrese de que esté activado Javascript y Flash instalado. cargas único archivo son posibles, pero no optimizado.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "Gestionar los álbumes";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Lista de albums";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Regenerar todas las miniaturas";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "Crear nuevo álbum";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "No hay álbumes se han creado todavía!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Archivos";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "Por favor, asegúrese de que el directorio de <strong>álbumes</strong> existe en el. / media / directorio";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Última modificación";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "Por favor espere ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Administrar noticias de actualidad";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Añade noticias";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Escribir nuevo artículo";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Administrar la configuración de";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Escribir noticias";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# Mensajes en front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Mostrar autor";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Mostrar la fecha de publicación";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Mostrar sólo teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Noticias título";
$ccms['lang']['news']['author'] = /* BABELFISH */ "Noticias autor";
$ccms['lang']['news']['date'] = /* BABELFISH */ "Fecha";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Publicado?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Artículo contenidos";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Ver archivo";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "No se observaron reacciones se han publicado!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Para comprobar que este mensaje no está automatizado, por favor, vuelva a introducir";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Reacción";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Clasificación";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Gravatar.com avatar del usuario";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "escribió";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Manejar las reacciones";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Borrar esta entrada";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail autor";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "se ha eliminado de la base de datos.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Su nombre";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Tu e-mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Su sitio web";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Comentarios";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Verificación";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "Prevista comentario";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Añade tu comentario";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "Su comentario ha sido enviado!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Gracias";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Fracasos y rechazos";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Tu comentario ha sido rechazada.";


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
