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
$ccms['lang']['system']['error_database'] = /* BABELFISH */ "Impossible de se connecter à la base de données. S'il vous plaît vérifier vos informations de connexion et le nom de base de données.";
$ccms['lang']['system']['error_openfile'] = /* BABELFISH */ "Impossible d'ouvrir le fichier spécifié";
$ccms['lang']['system']['error_notemplate'] = /* BABELFISH */ "Aucun modèle n'a pu être trouvée à appliquer à votre site. S'il vous plaît ajouter au moins un modèle pour. / Lib / templates /.";
$ccms['lang']['system']['error_templatedir'] = /* BABELFISH */ "Impossible de trouver le répertoire des modèles! Assurez-vous qu'il existe et contient au moins un modèle.";
$ccms['lang']['system']['error_write'] = /* BABELFISH */ "Le fichier n'a pas accès en écriture";
$ccms['lang']['system']['error_dirwrite'] = /* BABELFISH */ "Directory n'a pas accès en écriture";
$ccms['lang']['system']['error_chmod'] = /* BABELFISH */ "Le fichier actuel ne pouvait pas être modifié. Vérifiez les permissions sur les fichiers dans le répertoire / contenu (666).";
$ccms['lang']['system']['error_value'] = /* BABELFISH */ "Erreur: valeur incorrecte";
$ccms['lang']['system']['error_default'] = /* BABELFISH */ "Page par défaut ne peut pas être supprimé.";
$ccms['lang']['system']['error_forged'] = /* BABELFISH */ "La valeur a été falsifié";
$ccms['lang']['system']['error_filedots'] = /* BABELFISH */ "Nom de fichier ne doit pas contenir des points, par exemple. Html '.";
$ccms['lang']['system']['error_filesize'] = /* BABELFISH */ "Nom du fichier doit être au moins 3 caractères.";
$ccms['lang']['system']['error_filesize_2'] = /* BABELFISH */ "Nom du fichier doit être au plus 50 caractères.";
$ccms['lang']['system']['error_pagetitle'] = /* BABELFISH */ "Saisissez un titre de la page de 3 caractères ou plus.";
$ccms['lang']['system']['error_pagetitle_2'] = /* BABELFISH */ "Saisissez un titre de la page de 100 caractères ou moins.";
$ccms['lang']['system']['error_subtitle'] = /* BABELFISH */ "Donnez un court sous-titre de votre page.";
$ccms['lang']['system']['error_subtitle_2'] = /* BABELFISH */ "Entrez un court sous-titre de 200 caractères ou moins de votre page.";
$ccms['lang']['system']['error_description'] = /* BABELFISH */ "Entrez une description de plus de 3 caractères";
$ccms['lang']['system']['error_description_2'] = /* BABELFISH */ "Entrez une description de moins de 250 caractères";
$ccms['lang']['system']['error_reserved'] = /* BABELFISH */ "Vous avez spécifié un nom de fichier réservé à un usage interne.";
$ccms['lang']['system']['error_general'] = /* BABELFISH */ "Une erreur est survenue";
$ccms['lang']['system']['error_correct'] = /* BABELFISH */ "S'il vous plaît corriger les suivantes:";
$ccms['lang']['system']['error_create'] = /* BABELFISH */ "Erreur lors de la Fin de la création du nouveau fichier";
$ccms['lang']['system']['error_exists'] = /* BABELFISH */ "Le nom du fichier que vous avez spécifié existe déjà.";
$ccms['lang']['system']['error_delete'] = /* BABELFISH */ "Erreur lors de la suppression de remplir le fichier sélectionné";
$ccms['lang']['system']['error_selection'] = /* BABELFISH */ "Il n'y avait pas les éléments sélectionnés.";
$ccms['lang']['system']['error_versioninfo'] = /* BABELFISH */ "Aucune information de version est disponible.";
$ccms['lang']['system']['error_misconfig'] = /* BABELFISH */ "<strong>Il semble y avoir une mauvaise configuration.</strong><br>S'il vous plaît vérifiez que le fichier htaccess. Est correctement configuré pour tenir compte de la structure de votre fichier. Si vous avez<br>CompactCMS installé dans un sous-répertoire, puis ajustez le. Htaccess en conséquence.";
$ccms['lang']['system']['error_deleted'] = /* BABELFISH */ "<h1>Le fichier que vous avez sélectionné semble être supprimé</h1> <p>Rafraîchir la liste des fichiers pour voir la plus récente liste des fichiers disponibles pour éviter cette erreur ne se produise. Si cela ne résout pas cette erreur, vérifier manuellement le dossier de contenu pour le fichier que vous tentez d'ouvrir.</p>";
$ccms['lang']['system']['error_404title'] = /* BABELFISH */ "Fichier non trouvé";
$ccms['lang']['system']['error_404header'] = /* BABELFISH */ "Une erreur 404 est survenue, le fichier demandé <strong>{%pagereq%}.html</strong> n'a pu être trouvée.";
$ccms['lang']['system']['error_404content'] = /* BABELFISH */ "Le fichier demandé <strong>{%pagereq%}.html</strong> n'a pu être trouvée.";
$ccms['lang']['system']['error_403title'] = /* BABELFISH */ "Interdite";
$ccms['lang']['system']['error_403header'] = /* BABELFISH */ "Une erreur s'est produite 403: vous n'avez pas la permission d'accéder au fichier demandé.";
$ccms['lang']['system']['error_403content'] = /* BABELFISH */ "Vous n'êtes pas autorisé à accéder à <strong>{%pagereq%}.html</strong> en ce moment.";
$ccms['lang']['system']['error_sitemap'] = /* BABELFISH */ "Un aperçu de toutes les pages";
$ccms['lang']['system']['error_tooshort'] = /* BABELFISH */ "Une ou plusieurs valeurs ont été soumis, soit trop court ou incorrecte";
$ccms['lang']['system']['error_passshort'] = /* BABELFISH */ "Un mot de passe doit contenir au moins 6 caractères";
$ccms['lang']['system']['error_passnequal'] = /* BABELFISH */ "Les mots de passe saisis ne correspondent pas";
$ccms['lang']['system']['noresults'] = /* BABELFISH */ "Aucun résultat";
$ccms['lang']['system']['tooriginal'] = /* BABELFISH */ "Retour à l'original";
$ccms['lang']['system']['message_rights'] = /* BABELFISH */ "Tous droits réservés";
$ccms['lang']['system']['message_compatible'] = /* BABELFISH */ "Testé avec succès sur";

// Administration general messages
$ccms['lang']['backend']['gethelp'] = /* BABELFISH */ "suggestions Got, des commentaires ou des difficultés à avoir? Visitez <a class='external' title='Visit the official forum' rel='external' href='http://community.compactcms.nl/forum/'>le forum</a>!";
$ccms['lang']['backend']['ordertip'] = /* BABELFISH */ "Utilisez les menus déroulants ci-dessous pour refléter la structure de vos pages dans le menu de vos sites. Soyez conscient que le système ne tient pas compte en double combinaisons de niveau supérieur ou secondaire.";
$ccms['lang']['backend']['createtip'] = /* BABELFISH */ "Pour créer une nouvelle page, remplissez le formulaire ci-dessous et une nouvelle page sera créée pour vous à la volée. Une fois le fichier a été créé, vous serez en mesure de modifier la page comme d'habitude.";
$ccms['lang']['backend']['currentfiles'] = /* BABELFISH */ "Dans la liste ci-dessous vous trouverez toutes les pages publiées actuellement. Vous remarquerez que la page par défaut du fichier ne peut pas être supprimé, car il est la page d'accueil actuelle de votre site web. D'autres dossiers peuvent avoir accès restreint parce que l'administrateur a la maîtrise exclusive de ces fichiers.";
$ccms['lang']['backend']['confirmdelete'] = /* BABELFISH */ "S'il vous plaît confirmer que vous souhaitez supprimer l'élément sélectionné (s).";
$ccms['lang']['backend']['confirmthumbregen'] = /* BABELFISH */ "S'il vous plaît confirmer que vous voulez régénérer toutes les vignettes.";
$ccms['lang']['backend']['settingssaved'] = /* BABELFISH */ "Vos modifications ont été enregistrées avec succès.";
$ccms['lang']['backend']['must_refresh'] = /* BABELFISH */ "S'il vous plaît assurez-vous de recharger la page principale pour voir <strong>toutes les</strong> modifications apportées à votre";
$ccms['lang']['backend']['itemcreated'] = /* BABELFISH */ "A traité correctement la question présenté (s).";
$ccms['lang']['backend']['fullremoved'] = /* BABELFISH */ "Supprimé avec succès l'élément sélectionné (s).";
$ccms['lang']['backend']['fullregenerated'] = /* BABELFISH */ "Régénérée avec succès l'image.";
$ccms['lang']['backend']['tooverview'] = /* BABELFISH */ "Retour au sommaire";
$ccms['lang']['backend']['permissions'] = /* BABELFISH */ "Définir les autorisations CCMS";
$ccms['lang']['backend']['contentowners'] = /* BABELFISH */ "Définir les propriétaires de contenu";
$ccms['lang']['backend']['templateeditor'] = /* BABELFISH */ "éditeur de modèle";
$ccms['lang']['backend']['usermanagement'] = /* BABELFISH */ "Gestion des utilisateurs";
$ccms['lang']['backend']['changevalue'] = /* BABELFISH */ "Cliquez pour changer";
$ccms['lang']['backend']['previewpage'] = /* BABELFISH */ "Extrait";
$ccms['lang']['backend']['editpage'] = /* BABELFISH */ "Modifier";
$ccms['lang']['backend']['restrictpage'] = /* BABELFISH */ "Restreint";
$ccms['lang']['backend']['newfiledone'] = /* BABELFISH */ "Ce fichier est fraîche et propre pour que vous remplissiez-up!";
$ccms['lang']['backend']['newfilecreated'] = /* BABELFISH */ "Le fichier a été créé";
$ccms['lang']['backend']['startedittitle'] = /* BABELFISH */ "Commencez par modifier!";
$ccms['lang']['backend']['starteditbody'] = /* BABELFISH */ "Le nouveau fichier a été créé. Commencez par modifier tout de suite ou soit ajouter plus de pages ou de gérer les cours.";
$ccms['lang']['backend']['success'] = /* BABELFISH */ "Réussi!";
$ccms['lang']['backend']['fileexists'] = /* BABELFISH */ "Le fichier existe";
$ccms['lang']['backend']['statusdelete'] = /* BABELFISH */ "Statut de la suppression sélectionnés:";
$ccms['lang']['backend']['statusremoved'] = /* BABELFISH */ "retiré";
$ccms['lang']['backend']['uptodate'] = /* BABELFISH */ "à jour.";
$ccms['lang']['backend']['outofdate'] = /* BABELFISH */ "dépassées.";
$ccms['lang']['backend']['considerupdate'] = /* BABELFISH */ "Envisager la mise à jour";
$ccms['lang']['backend']['orderprefsaved'] = /* BABELFISH */ "Vos préférences pour l'ordre de vos éléments de menu ont été enregistrées.";
$ccms['lang']['backend']['inmenu'] = /* BABELFISH */ "Dans le menu";
$ccms['lang']['backend']['updatelist'] = /* BABELFISH */ "Liste des fichiers de mise à jour";
$ccms['lang']['backend']['administration'] = "Administration";
$ccms['lang']['backend']['currentversion'] = /* BABELFISH */ "Vous êtes actuellement en exécutant la version";
$ccms['lang']['backend']['mostrecent'] = /* BABELFISH */ "La plus récente version stable est CompactCMS";
$ccms['lang']['backend']['versionstatus'] = /* BABELFISH */ "Votre installation est";
$ccms['lang']['backend']['createpage'] = /* BABELFISH */ "Créer une nouvelle page";
$ccms['lang']['backend']['managemenu'] = /* BABELFISH */ "Gérer menu";
$ccms['lang']['backend']['managefiles'] = /* BABELFISH */ "Gérer les pages en cours";
$ccms['lang']['backend']['delete'] = /* BABELFISH */ "Supprimer";
$ccms['lang']['backend']['toplevel'] = /* BABELFISH */ "Niveau supérieur";
$ccms['lang']['backend']['sublevel'] = /* BABELFISH */ "niveau des sous";
$ccms['lang']['backend']['active'] = "Active";
$ccms['lang']['backend']['disabled'] = /* BABELFISH */ "Handicapés";
$ccms['lang']['backend']['template'] = /* BABELFISH */ "Modèle";
$ccms['lang']['backend']['notinmenu'] = /* BABELFISH */ "Poste non dans un menu";
$ccms['lang']['backend']['menutitle'] = "Menu";
$ccms['lang']['backend']['linktitle'] = /* BABELFISH */ "Lien";
$ccms['lang']['backend']['item'] = /* BABELFISH */ "Point";
$ccms['lang']['backend']['none'] = /* BABELFISH */ "Aucun";
$ccms['lang']['backend']['yes'] = /* BABELFISH */ "Oui";
$ccms['lang']['backend']['no'] = /* BABELFISH */ "N";
$ccms['lang']['backend']['translation'] = /* BABELFISH */ "Traductions";
$ccms['lang']['backend']['hello'] = /* BABELFISH */ "Salut";
$ccms['lang']['backend']['logout'] = /* BABELFISH */ "Déconnexion";
$ccms['lang']['backend']['see_forum'] = /* BABELFISH */ "Voir le forum";

// Texts for authentication screen
$ccms['lang']['login']['welcome'] = /* BABELFISH */ "<p>Utilisez un nom d'utilisateur et mot de passe pour accéder à l'arrière CompactCMS-end. Si vous êtes arrivé ici par erreur, revenir à la <a href='admin/includes/'>page d'accueil</a>.</p> <p>Contactez votre webmaster pour vos coordonnées.</p>";
$ccms['lang']['login']['username'] = /* BABELFISH */ "Nom d'utilisateur";
$ccms['lang']['login']['password'] = /* BABELFISH */ "Mot de passe";
$ccms['lang']['login']['login'] = /* BABELFISH */ "Connectez-vous";
$ccms['lang']['login']['provide'] = /* BABELFISH */ "S'il vous plaît fournir vos informations d'identification utilisateur";
$ccms['lang']['login']['nodetails'] = /* BABELFISH */ "Entrez votre identifiant et mot de passe";
$ccms['lang']['login']['nouser'] = /* BABELFISH */ "Entrez votre nom d'utilisateur";
$ccms['lang']['login']['nopass'] = /* BABELFISH */ "Entrez votre mot de passe";
$ccms['lang']['login']['notactive'] = /* BABELFISH */ "Ce compte a été désactivé";
$ccms['lang']['login']['falsetries'] = /* BABELFISH */ "Notez que vous avez déjà fait plusieurs tentatives";
$ccms['lang']['login']['nomatch'] = /* BABELFISH */ "Mauvais nom d'utilisateur ou mot de passe";

// Menu titles for administration back-end
$ccms['lang']['menu']['1'] = "Main";
$ccms['lang']['menu']['2'] = /* BABELFISH */ "Gauche";
$ccms['lang']['menu']['3'] = /* BABELFISH */ "Droit";
$ccms['lang']['menu']['4'] = /* BABELFISH */ "Pied de page";
$ccms['lang']['menu']['5'] = "Extra";

// Administration form related texts
$ccms['lang']['forms']['filename'] = /* BABELFISH */ "Nom de fichier";
$ccms['lang']['forms']['pagetitle'] = /* BABELFISH */ "Titre de la page";
$ccms['lang']['forms']['subheader'] = /* BABELFISH */ "Sous-en-";
$ccms['lang']['forms']['description'] = "Description";
$ccms['lang']['forms']['module'] = "Module";
$ccms['lang']['forms']['contentitem'] = /* BABELFISH */ "élément de contenu (par défaut)";
$ccms['lang']['forms']['additions'] = /* BABELFISH */ "Ajouts";
$ccms['lang']['forms']['printable'] = /* BABELFISH */ "Imprimable";
$ccms['lang']['forms']['published'] = "Active";
$ccms['lang']['forms']['iscoding'] = /* BABELFISH */ "Codage";
$ccms['lang']['forms']['createbutton'] = "Create!";
$ccms['lang']['forms']['modifybutton'] = /* BABELFISH */ "Modifier";
$ccms['lang']['forms']['savebutton'] = /* BABELFISH */ "Enregistrer";
$ccms['lang']['forms']['setlocale'] = /* BABELFISH */ "Front-end langue";
$ccms['lang']['forms']['filter_showing'] = /* BABELFISH */ "à l'heure actuelle nous ne sommes qu'à l'affichage des pages qui ont au moins ce texte ici";
$ccms['lang']['forms']['edit_remove'] = /* BABELFISH */ "Modifier ou supprimer des filtres pour";
$ccms['lang']['forms']['add'] = /* BABELFISH */ "Ajouter le filtre pour";

// Administration hints for form fields
$ccms['lang']['hints']['filename'] = /* BABELFISH */ "L'url de la page (home.html) :: Le nom du fichier qui cette page est appelée (sans html.)";
$ccms['lang']['hints']['pagetitle'] = /* BABELFISH */ "Titre de cette page (Accueil) :: Un titre court et descriptif pour cette page.";
$ccms['lang']['hints']['subheader'] = /* BABELFISH */ "texte d'en-tête abrégé (Bienvenue sur notre site) :: Un court texte descriptif utilisé dans l'entête de chaque page ainsi que dans le titre de chaque page.";
$ccms['lang']['hints']['description'] = /* BABELFISH */ "Meta description :: Une description unique pour cette page qui sera utilisée comme meta description des pages.";
$ccms['lang']['hints']['module'] = /* BABELFISH */ "Module :: Sélectionnez ce module doit gérer le contenu de ce fichier. Si vous n'êtes pas sûr, sélectionnez la valeur par défaut.";
$ccms['lang']['hints']['printable'] = /* BABELFISH */ "imprimabilité Page :: Lorsque vous sélectionnez 'OUI' une page imprimable est générée. «NON» doit être sélectionnée pour les pages avec des images ou d'autres médias. Il s'agit de mieux sans une page imprimable.";
$ccms['lang']['hints']['published'] = /* BABELFISH */ "le statut de publication :: Sélectionnez si cette page doit être publiée, si ça va être énumérés dans le plan du site et si elle sera accessible au public.";
$ccms['lang']['hints']['toplevel'] = /* BABELFISH */ "de haut niveau :: Spécifiez le niveau supérieur pour ce menu.";
$ccms['lang']['hints']['sublevel'] = /* BABELFISH */ "niveau secondaire :: Sélectionnez 0 lorsque cet article devrait être un élément de plus haut niveau. S'il s'agit d'un élément de sous pour un certain niveau supérieur, s'il vous plaît sélectionnez le niveau approprié sous.";
$ccms['lang']['hints']['template'] = /* BABELFISH */ "Modèle :: Si vous utilisez plusieurs modèles pour votre installation, vous pouvez nommer des modèles distincts pour chaque page à l'aide de ce menu déroulant.";
$ccms['lang']['hints']['activelink'] = /* BABELFISH */ "Lien actif dans le menu? :: Pas tous les articles toujours besoin d'un lien effectif. Pour désactiver le lien derrière cette question dans le menu front-end, décochez la case ci-dessous.";
$ccms['lang']['hints']['menuid'] = /* BABELFISH */ "Menu :: Choisissez dans quel menu de cet ouvrage doivent être énumérés en La valeur par défaut est le menu principal (1), où également le lien page d'accueil doit être indiqué.";
$ccms['lang']['hints']['iscoding'] = /* BABELFISH */ "Contient de codage :: Est-ce ce fichier contient manuel a ajouté un code tel que PHP ou Javascript? Sélection «Oui» à restreindre l'accès au fichier de l'éditeur WYSIWYG le back-end et permet à l'éditeur de code.";
$ccms['lang']['hints']['filter'] = /* BABELFISH */ "<br><br>Vous pouvez cliquer sur l' <span class='sprite livefilter livefilter_active'> icône de filtre à gauche du titre pour ajouter, modifier ou supprimer un texte pour filtrer la liste des pages sur, par exemple, lorsque vous «type» d'origine dans le champ de saisie qui apparaît lorsque vous cliquez sur l'icône, puis appuyez sur Entrée / touche Retour, seules les pages qui ont la maison »le texte» dans cette colonne seront affichées. <br>En cliquant sur l'icône de nouveau et de supprimer le texte dans le champ d'édition, puis en appuyant sur la touche Entrée / Retour, va supprimer le filtre.<br>Placez le curseur sur l'icône de filtre pour voir si la colonne est actuellement filtrées, et si oui, en utilisant le filtre de texte qui.";

// Editor messages
$ccms['lang']['editor']['closeeditor'] = /* BABELFISH */ "Fermez l'éditeur";
$ccms['lang']['editor']['editorfor'] = /* BABELFISH */ "L'éditeur de texte pour";
$ccms['lang']['editor']['instruction'] = /* BABELFISH */ "Utilisez l'éditeur ci-dessous pour modifier le fichier actuel. Une fois que vous avez terminé, appuyez sur 'Enregistrer les modifications' bouton ci-dessous pour publier directement vos modifications dans le world wide web. Également ajouter jusqu'à dix mots clés pertinents pour optimalisation des moteurs de recherche.";
$ccms['lang']['editor']['savebtn'] = /* BABELFISH */ "Enregistrer les modifications";
$ccms['lang']['editor']['cancelbtn'] = /* BABELFISH */ "Annuler";
$ccms['lang']['editor']['confirmclose'] = /* BABELFISH */ "Fermez cette fenêtre et annuler les modifications?";
$ccms['lang']['editor']['preview'] = /* BABELFISH */ "Extrait de la page de résultats";
$ccms['lang']['editor']['savesuccess'] = /* BABELFISH */ "<strong>Success!</strong> Le contenu comme indiqué ci-dessous a été enregistré";
$ccms['lang']['editor']['backeditor'] = /* BABELFISH */ "Retour à l'éditeur";
$ccms['lang']['editor']['closewindow'] = /* BABELFISH */ "Fermer la fenêtre";
$ccms['lang']['editor']['keywords'] = /* BABELFISH */ "Mots-clés - <em>séparées par des virgules, max 250 caractères</em>";

// Authorization messages
$ccms['lang']['auth']['generatepass'] = /* BABELFISH */ "Auto générer un mot de passe sûr";
$ccms['lang']['auth']['featnotallowed'] = /* BABELFISH */ "Votre niveau de la balance courante ne vous permet pas d'utiliser cette fonctionnalité.";

################### MODULES ###################

// Back-up messages
$ccms['lang']['backup']['createhd'] = /* BABELFISH */ "Créer un nouveau back-up";
$ccms['lang']['backup']['explain'] = /* BABELFISH */ "Pour éviter toute perte de données due à quelque événement externe, il est sage de créer des sauvegardes de vos fichiers régulièrement.";
$ccms['lang']['backup']['warn4media'] = /* BABELFISH */ "Attention: S'il vous plaît soyez conscient que votre <dfn>lightbox</dfn> 'images albums sont <strong>pas</strong> sauvegardés descriptions! album The <strong>sont</strong>, mais les images elles-mêmes et leurs vignettes sont <strong>pas inclus dans ces sauvegardes</strong>avec. Si vous voulez des sauvegardes des ceux-ci, alors vous aurez besoin de conférer administrateur de votre site sur un système de sauvegarde supplémentaires pour vous aider à la sauvegarde et la restauration de ces grandes) Fichier collections éventuellement (.";
$ccms['lang']['backup']['currenthd'] = /* BABELFISH */ "Disponible back-ups";
$ccms['lang']['backup']['timestamp'] = /* BABELFISH */ "nom de fichier de sauvegarde";
$ccms['lang']['backup']['download'] = /* BABELFISH */ "télécharger l'archive";
$ccms['lang']['backup']['wait4backup'] = /* BABELFISH */ "S'il vous plaît patienter pendant que la sauvegarde est en cours de création ...";

// User management messages
$ccms['lang']['users']['createuser'] = /* BABELFISH */ "Créer un utilisateur";
$ccms['lang']['users']['overviewusers'] = /* BABELFISH */ "Vue d'ensemble des utilisateurs du CDSM";
$ccms['lang']['users']['editdetails'] = /* BABELFISH */ "Modifier les informations personnelles de l'utilisateur";
$ccms['lang']['users']['editpassword'] = /* BABELFISH */ "Modifier le mot de passe utilisateur";
$ccms['lang']['users']['accountcfg'] = /* BABELFISH */ "Paramètres du compte";
$ccms['lang']['users']['user'] = /* BABELFISH */ "Utilisateur";
$ccms['lang']['users']['username'] = /* BABELFISH */ "Nom d'utilisateur";
$ccms['lang']['users']['name'] = /* BABELFISH */ "Nom";
$ccms['lang']['users']['firstname'] = /* BABELFISH */ "Prénom";
$ccms['lang']['users']['lastname'] = /* BABELFISH */ "Nom de famille";
$ccms['lang']['users']['password'] = /* BABELFISH */ "Mot de passe";
$ccms['lang']['users']['cpassword'] = /* BABELFISH */ "Confirmer mot de passe";
$ccms['lang']['users']['email'] = "E-mail";
$ccms['lang']['users']['active'] = "Active";
$ccms['lang']['users']['level'] = /* BABELFISH */ "Niveau";
$ccms['lang']['users']['userlevel'] = /* BABELFISH */ "niveau de l'utilisateur";
$ccms['lang']['users']['lastlog'] = /* BABELFISH */ "Dernière connexion";

// Template editor
$ccms['lang']['template']['manage'] = /* BABELFISH */ "Gérer les templates";
$ccms['lang']['template']['nowrite'] = /* BABELFISH */ "Le modèle actuel est <strong>pas</strong> accessible en écriture";
$ccms['lang']['template']['print'] = /* BABELFISH */ "Imprimer";

// Permissions
$ccms['lang']['permission']['header'] = /* BABELFISH */ "Permission préférences";
$ccms['lang']['permission']['explain'] = /* BABELFISH */ "Utilisez le tableau ci-dessous pour préciser le niveau minimum d'utilisateur peut utiliser certaines fonctionnalités. Tout utilisateur en dessous du niveau minimum requis par l'utilisateur spécifié, ne voit ni avoir accès à la fonction.";
$ccms['lang']['permission']['target'] = /* BABELFISH */ "Cible";
$ccms['lang']['permission']['level1'] = /* BABELFISH */ "Niveau 1 - User";
$ccms['lang']['permission']['level2'] = /* BABELFISH */ "Niveau 2 - Sous la direction de";
$ccms['lang']['permission']['level3'] = /* BABELFISH */ "Niveau 3 - Manager";
$ccms['lang']['permission']['level4'] = /* BABELFISH */ "Niveau 4 - Admin";

// Content owners
$ccms['lang']['owners']['header'] = /* BABELFISH */ "Les propriétaires de contenu";
$ccms['lang']['owners']['explain'] = /* BABELFISH */ "Ici, vous pouvez nommer la propriété page spécifique à des utilisateurs individuels. Si, pour une certaine page sans les utilisateurs sont sélectionnés, chacun peut modifier la page. Sinon, seul l'utilisateur spécifié a les droits de modification dans le fichier. Les administrateurs ont toujours accès à tous les fichiers.";
$ccms['lang']['owners']['pages'] = "Pages";
$ccms['lang']['owners']['users'] = /* BABELFISH */ "Utilisateurs";

// Translation assistance page 
$ccms['lang']['translation']['header'] = /* BABELFISH */ "Traduire";
$ccms['lang']['translation']['explain'] = /* BABELFISH */ "Affiche les chaînes de traduction.";

// Album messages
$ccms['lang']['album']['album'] = "Album";
$ccms['lang']['album']['settings'] = /* BABELFISH */ "paramètres de l'album";
$ccms['lang']['album']['apply_to'] = /* BABELFISH */ "S'appliquent spécifiquement à cet album";
$ccms['lang']['album']['description'] = /* BABELFISH */ "description de l'album";
$ccms['lang']['album']['currentalbums'] = /* BABELFISH */ "albums actuel";
$ccms['lang']['album']['uploadcontent'] = /* BABELFISH */ "Téléchargez les contenus";
$ccms['lang']['album']['toexisting'] = /* BABELFISH */ "Envoyer existants album";
$ccms['lang']['album']['upload'] = /* BABELFISH */ "Démarrer le téléchargement";
$ccms['lang']['album']['browse'] = /* BABELFISH */ "Parcourir les fichiers";
$ccms['lang']['album']['clear'] = /* BABELFISH */ "Effacer la liste";
$ccms['lang']['album']['singlefile'] = /* BABELFISH */ "<strong>Un seul fichier télécharger</strong><br> <p>Le chargeur de Flash n'a pas pu s'initialiser. Assurez-vous que JavaScript est activé et Flash est installé. le téléchargement de fichiers uniques sont possibles, mais pas optimisé.</p>";
$ccms['lang']['album']['manage'] = /* BABELFISH */ "Gérer les albums";
$ccms['lang']['album']['albumlist'] = /* BABELFISH */ "Liste des albums";
$ccms['lang']['album']['regenalbumthumbs'] = /* BABELFISH */ "Régénérer les vignettes";
$ccms['lang']['album']['newalbum'] = /* BABELFISH */ "Créer un nouvel album";
$ccms['lang']['album']['noalbums'] = /* BABELFISH */ "Aucun album n'a encore été créé!";
$ccms['lang']['album']['files'] = /* BABELFISH */ "Fichiers";
$ccms['lang']['album']['nodir'] = /* BABELFISH */ "S'il vous plaît vérifiez que le répertoire <strong>albums</strong> existe dans le fichier. / media / répertoire";
$ccms['lang']['album']['lastmod'] = /* BABELFISH */ "Dernière mise à jour";
$ccms['lang']['album']['please_wait'] = /* BABELFISH */ "S'il vous plaît patienter ...";

// News messages
$ccms['lang']['news']['manage'] = /* BABELFISH */ "Gérer des nouvelles actuelles";
$ccms['lang']['news']['addnews'] = /* BABELFISH */ "Ajouter nouvelles";
$ccms['lang']['news']['addnewslink'] = /* BABELFISH */ "Donnez votre nouvel article";
$ccms['lang']['news']['settings'] = /* BABELFISH */ "Gérer les paramètres";
$ccms['lang']['news']['writenews'] = /* BABELFISH */ "Écrire des nouvelles";
$ccms['lang']['news']['numbermess'] = /* BABELFISH */ "# messages sur le front-end";
$ccms['lang']['news']['showauthor'] = /* BABELFISH */ "Afficher auteur";
$ccms['lang']['news']['showdate'] = /* BABELFISH */ "Voir la date de publication";
$ccms['lang']['news']['showteaser'] = /* BABELFISH */ "Afficher uniquement les teaser";
$ccms['lang']['news']['title'] = /* BABELFISH */ "Titre Nouvelles";
$ccms['lang']['news']['author'] = /* BABELFISH */ "Nouvelles auteur";
$ccms['lang']['news']['date'] = "Date";
$ccms['lang']['news']['published'] = /* BABELFISH */ "Publié?";
$ccms['lang']['news']['teaser'] = "Teaser";
$ccms['lang']['news']['contents'] = /* BABELFISH */ "Sommaire de l'article";
$ccms['lang']['news']['viewarchive'] = /* BABELFISH */ "Voir les archives";

// Guestbook message
$ccms['lang']['guestbook']['noposts'] = /* BABELFISH */ "Aucune réaction n'a encore été posté!";
$ccms['lang']['guestbook']['verinstr'] = /* BABELFISH */ "Pour vérifier que ce message n'est pas automatique, s'il vous plaît ré-entrer";
$ccms['lang']['guestbook']['reaction'] = /* BABELFISH */ "Réaction";
$ccms['lang']['guestbook']['rating'] = /* BABELFISH */ "Note";
$ccms['lang']['guestbook']['avatar'] = /* BABELFISH */ "Avatar de l'utilisateur Gravatar.com";
$ccms['lang']['guestbook']['wrote'] = /* BABELFISH */ "écrit";
$ccms['lang']['guestbook']['manage'] = /* BABELFISH */ "Gérer les réactions";
$ccms['lang']['guestbook']['delentry'] = /* BABELFISH */ "Supprimer cette entrée";
$ccms['lang']['guestbook']['sendmail'] = /* BABELFISH */ "E-mail auteur";
$ccms['lang']['guestbook']['removed'] = /* BABELFISH */ "a été supprimée de la base de données.";
$ccms['lang']['guestbook']['name'] = /* BABELFISH */ "Votre nom";
$ccms['lang']['guestbook']['email'] = /* BABELFISH */ "Votre adresse e-mail";
$ccms['lang']['guestbook']['website'] = /* BABELFISH */ "Votre site Web";
$ccms['lang']['guestbook']['comments'] = /* BABELFISH */ "Commentaires";
$ccms['lang']['guestbook']['verify'] = /* BABELFISH */ "Vérification";
$ccms['lang']['guestbook']['preview'] = /* BABELFISH */ "commentaire Prévisualiser";
$ccms['lang']['guestbook']['add'] = /* BABELFISH */ "Ajoutez vos commentaires";
$ccms['lang']['guestbook']['posted'] = /* BABELFISH */ "Votre commentaire a été posté!";
$ccms['lang']['guestbook']['success'] = /* BABELFISH */ "Je vous remercie";
$ccms['lang']['guestbook']['error'] = /* BABELFISH */ "Les échecs et les rejets";
$ccms['lang']['guestbook']['rejected'] = /* BABELFISH */ "Votre commentaire a été rejetée.";


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
