-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- Serveur: 209.222.235.12:3306
-- Généré le : Dim 13 Juin 2010 à 21:14
-- Version du serveur: 5.0.70
-- Version de PHP: 5.2.10-pl0-gentoo
-- Version SVN: $Id: moduleNewsCreate.sql 824 2012-02-01 01:21:12Z ssoares $

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Structure de la table `NewsData`
--

CREATE TABLE IF NOT EXISTS `NewsData` (
  `ND_ID` int(11) NOT NULL auto_increment,
  `ND_CategoryID` int(11) NOT NULL,
  `ND_Date` date default NULL,
  `ND_ReleaseDate` date NOT NULL,
  `ND_AuthorID` int(11) NOT NULL,
  PRIMARY KEY  (`ND_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Structure de la table `NewsIndex`
--

CREATE TABLE IF NOT EXISTS `NewsIndex` (
  `NI_NewsDataID` int(11) NOT NULL,
  `NI_LanguageID` int(11) NOT NULL,
  `NI_Title` varchar(255) default NULL,
  `NI_Brief` text,
  `NI_Text` longtext,
  `NI_ImageAlt` varchar(255) default NULL,
  `NI_ImageSrc` varchar(255) default NULL,
  `NI_Status` tinyint(4) NOT NULL,
  `NI_ValUrl` varchar(255) default NULL,
  PRIMARY KEY  (`NI_NewsDataID`,`NI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Données pour activer module et les liens dans le back end
--

INSERT INTO Modules (M_ID, M_Title, M_MVCModuleTitle) VALUES (2, 'Nouvelles', 'news');

INSERT INTO Modules_ControllersActionsPermissions (MCAP_ModuleID, MCAP_ControllerTitle, MCAP_ControllerActionTitle, MCAP_PermissionTitle, MCAP_Position) VALUES
(2, 'index', 'list-all', 'edit', 2),
(2, 'index', 'list-categories', 'edit', 1);

INSERT INTO `ModuleViews` (`MV_ID`, `MV_Name`, `MV_ModuleID`) VALUES
(2002, 'details', 2),
(2003, 'homepagelist', 2),
(2004, 'listall', 2);

INSERT INTO `ModuleViewsIndex` (`MVI_ModuleViewsID`, `MVI_LanguageID`, `MVI_ActionName`) VALUES
(2002, 1, 'detail'),
(2002, 2, 'details'),
(2003, 1, 'nouvelles-accueil'),
(2003, 2, 'news-home'),
(2004, 1, 'toutes'),
(2004, 2, 'list-all');

INSERT INTO Extranet_Resources (ER_ID, ER_ControlName) VALUES
(2, 'news');

INSERT INTO Extranet_ResourcesIndex (ERI_ResourceID, ERI_LanguageID, ERI_Name) VALUES
(2, 1, 'Nouvelles'),
(2, 2, 'News');

INSERT INTO Extranet_RolesResources (ERR_ID, ERR_RoleID, ERR_ResourceID, ERR_InheritedParentID) VALUES
(2001,1, 2, 0),
(2002,2, 2, 2001),
(2003,3, 2, 2002);

INSERT INTO Extranet_RolesResourcesIndex (ERRI_RoleResourceID, ERRI_LanguageID, ERRI_Name, ERRI_Description) VALUES
(2001,1, 'Editeur', 'Peut créer et éditer les contenus des nouvelles.'),
(2001,2, 'Editor', 'Can create and edit the news content'),
(2002,1, 'Réviseur', 'A les même droit que l\'éditeur. Il peut aussi mettre en ligne des nouvelles.'),
(2002,2, 'Reviewer', 'Has the same rights than the editor. He also can set online the news.'),
(2003,1, 'Gestionnaire', 'A les droits du réviseur et peut supprimer les nouvelles.'),
(2003,2, 'Manager', 'Has the reviewer\'s rigth and can delete news.');

INSERT INTO Extranet_RolesResourcesPermissions (ERRP_RoleResourceID, ERRP_PermissionID) VALUES
(2001, 1),
(2002, 3),
(2003, 4);

INSERT INTO Categories (C_ID, C_ParentID, C_ModuleID, C_PageID,	C_ShowInRss,C_RssItemsCount) VALUES
(1,0,2,null,1,10);

INSERT INTO `CategoriesIndex` (`CI_CategoryID`, `CI_LanguageID`, `CI_Title`, `CI_WordingShowAllRecords`) VALUES
(1, 1, 'Générale', 'Liste des nouvelles à portée générales.'),
(1, 2, 'General', 'List of the general news');

INSERT INTO `Pages` (`P_ID`, `P_Position`, `P_ParentID`, `P_Home`, `P_LayoutID`, `P_ThemeID`, `P_ViewID`, `P_ShowSiteMap`, `P_ShowMenu`, `P_ShowTitle`) VALUES
(2001, 3, 0, 0, 2, 1, 2, 1, 1, 1),
(2002, 1, 2001, 0, 2, 1, 2, 1, 1, 1);

INSERT INTO PagesIndex (PI_PageID, PI_LanguageID, PI_PageIndex, PI_PageIndexOtherLink, PI_PageTitle, PI_TitleImageSrc, PI_TitleImageAlt, PI_MetaDescription, PI_MetaKeywords,`PI_MetaOther`, PI_Status, PI_Secure) VALUES
(2001, 1, 'nouvelles', '', 'Nouvelles', '', '', '', '', '', 1, 'non'),
(2001, 2, 'news_en', '', 'News', '', '', '', '', '', 1, 'non'),
(2002, 1, 'nouvelles-en-details', '', 'Nouvelles en détails', '', '', '', '', '', 1, 'non'),
(2002, 2, 'news-details', '', 'News details', '', '', '', '', '', 1, 'non');


INSERT INTO `ModuleCategoryViewPage` (`MCVP_ID`, `MCVP_ModuleID`, `MCVP_CategoryID`, `MCVP_ViewID`, `MCVP_PageID`) VALUES
(2001, 2, 1, 2001, 2001),
(2002, 2, 1, 2002, 2002),
(2003, 2, 1, 2003, 1),
(2004, 2, 1, 2004, 2001);

INSERT INTO `Views` (`V_ID`, `V_Name`, `V_ZoneCount`, `V_Path`, `V_Image`) VALUES
(6, 'News', 1, 'template/news.phtml', 'image.png');

REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('form_label_news_category', '1', 'Catégorie', 'cible', '', '0', 2),
('form_label_news_category', '2', 'Category', 'cible', '', '0', 2),
('management_module_news_list', 1, 'Nouvelles', 'cible', '', 0 , 2),
('management_module_news_list', 2, 'News', 'cible', '', 0 , 2),
('management_module_news_list_approbation_request', 1, 'Nouvelles à approuver', 'cible', '', 0 , 2),
('management_module_news_list_approbation_request', 2, 'News to be approved', 'cible', '', 0 , 2),
('management_module_news_list_categories', 1, 'Catégories de nouvelles', 'cible', '', 0 , 2),
('management_module_news_list_categories', 2, 'Categories list', 'cible', '', 0 , 2),
('news_categories_page_title', 1, 'Catégories de nouvelles', 'cible', '', 0 , 2),
('news_categories_page_description', 1, 'Cliquez sur <b>Ajouter une catégorie de nouvelles</b><br>pour créer une catégorie.<br><br>Vous pouvez <b>rechercher par mots-clés</b> parmi<br>la liste des catégories. Pour revenir à la liste complète,<br>cliquez sur <b>Voir la liste complète</b>.<br><br>Vous pouvez <b>modifier ou supprimer une<br>catégorie</b> en cliquant sur l\'icône <img src="/extranet/icons/list_actions_icon.png" align=middle>.', 'cible', '', 0 , 2),
('news_categories_page_title', 2, 'News categories list', 'cible', '', 0 , 2),
('news_categories_page_description', 2, 'This page is to consult the news categories list.', 'cible', '', 0 , 2),
('news_no_news', 1, 'Il n''y a présentement aucune nouvelle.\r\n', 'cible', '', 0, 2),
('news_no_news', 2, 'There is currently no news.\r\n', 'cible', '', 0, 2),
('news_button_add_category', 1, 'Ajouter une catégorie de nouvelles', 'cible', '', 0 , 2),
('news_button_add_category', 2, 'Add a category', 'cible', '', 0 , 2),
('header_list_news_approbation_title', 1, 'Approbation de nouvelles', 'cible', '', 0 , 2),
('header_list_news_approbation_title', 2, 'News approbation', 'cible', '', 0 , 2),
('header_list_news_approbation_description', 1, 'Aide pour l\'approbation de nouvelles', 'cible', '', 0 , 2),
('header_list_news_approbation_description', 2, 'Help on news approbation', 'cible', '', 0 , 2),
('management_module_news_list_all', 1, 'Nouvelles', 'cible', '', 0, 2),
('management_module_news_list_all', 2, 'News', 'cible', '', 0 , 2),
('header_list_news_text_default', 1, 'Liste des nouvelles', 'cible', '', 0 , 2),
('header_list_news_text_default', 2, 'List of all the news', 'cible', '', 0 , 2),
('header_list_news_description_default', 1, 'Cliquez sur <b>Ajouter une nouvelle</b> pour <br>créer une nouvelle.<br><br>Vous pouvez <b>rechercher par mots-clés, <br>par catégorie et par statut</b> parmi la liste<br>des nouvelles. Pour revenir à la liste complète,<br>cliquez sur <b>Voir la liste complète</b>.<br><br>Vous pouvez <b>modifier ou supprimer une<br>nouvelle</b> en cliquant sur l\'icône <img src="/extranet/icons/list_actions_icon.png" align=middle>.', 'cible', '', 0 , 2),
('header_list_news_description_default', 2, 'This page is to manage all the news.', 'cible', '', 0 , 2),
('form_select_option_view_news_list', 1, 'Toutes les nouvelles', 'cible', '', 0 , 2),
('form_select_option_view_news_details', 1, 'Détails d\'une nouvelle', 'cible', '', 0 , 2),
('see_all_news_text', 1, 'Toutes les nouvelles', 'client', '', 0 , 2),
('see_all_news_text', 2, 'All the news', 'client', '', 0 , 2),
('see_details_news_text', 1, 'Plus de détails', 'client', '', 0 , 2),
('see_details_news_text', 2, 'More details', 'client', '', 0 , 2),
('form_select_option_view_news_homepagelist', 1, 'Accueil', 'cible', '', 0 , 2),
('form_select_option_view_news_listall', 1, 'Toutes les nouvelles', 'cible', '', 0 , 2),
('label_category_news_bloc', '1', 'Catégorie des nouvelles de ce bloc', 'cible', '', 0 , 2),
('label_category_news_bloc', '2', 'News category', 'cible', '', 0 , 2),
('label_number_news_show', '1', 'Nombre de nouvelles à afficher', 'cible', '', 0 , 2),
('label_number_news_show', '2', 'Number of news to show', 'cible', '', 0 , 2),
('news_manage_block_contents', 1, 'Gestion des nouvelles', 'cible', '', 0 , 2),
('news_manage_block_contents', 2, 'News management', 'cible', '', 0 , 2),
('news_module_name', 1, 'Nouvelles', 'cible', '', 0 , 2),
('news_latest_news_text', 1, 'Actualités', 'client', '', 0 , 2),
('news_latest_news_text', 2, 'News', 'client', '', 0 , 2),
('news_module_name', 2, 'News', 'cible', '', 0 , 2),
('header_list_news_text', 1, 'Liste des nouvelles de la catégorie <em>«%CATEGORY_NEWS_NAME%»</em>', 'cible', '', 0 , 2),
('header_list_news_text', 2, 'News list of the category <em>«%CATEGORY_NEWS_NAME%»</em>', 'cible', '', 0 , 2),
('header_list_news_description', 1, 'Cette page vous permet de consulter la liste des nouvelles de la catégorie <em><strong>«%CATEGORY_NEWS_NAME%»</em></strong>.', 'cible', '', 0 , 2),
('header_list_news_description', 2, 'This page is to consult the news list of the category <em><strong>«%CATEGORY_NEWS_NAME%»</em></strong>.', 'cible', '', 0 , 2),
('header_edit_news_text', 1, 'Édition d\'une nouvelle', 'cible', '', 0 , 2),
('header_edit_news_text', 2, 'News edit', 'cible', '', 0 , 2),
('header_edit_news_description', 1, 'Cette page vous permet d\'éditer une nouvelle.', 'cible', '', 0 , 2),
('header_edit_news_description', 2, 'This page is to edit a news.', 'cible', '', 0 , 2),
('header_add_news_text', 1, 'Ajout d\'une nouvelle', 'cible', '', 0 , 2),
('header_add_news_text', 2, 'Add a news', 'cible', '', 0 , 2),
('header_add_news_description', 1, 'Cette page vous permet d\'ajouter une nouvelle.', 'cible', '', 0 , 2),
('header_add_news_description', 2, 'This page is to add a news.', 'cible', '', 0 , 2),
('label_date_news', '1', 'Afficher la date des nouvelles', 'cible', '', 0 , 2),
('label_date_news', '2', 'Show the news date', 'cible', '', 0 , 2),
('module_news', 1, 'Nouvelles', 'cible', '', 0 , 2),
('module_news', 2, 'News', 'cible', '', 0 , 2),
('button_add_news', 1, 'Ajouter une nouvelle', 'cible', '', 0 , 2),
('button_add_news', 2, 'Add a news', 'cible', '', 0 , 2),
('form_select_option_zoneViews_6', '1', 'Nouvelles', 'cible', '', 0 , 2),
('form_select_option_zoneViews_6', '2', 'News', 'cible', '', 0 , 2),
('form_select_option_view_news_listall_2columns', '1', 'Liste 2 colonnes', 'cible', '', '0', '0'),
('form_select_option_view_news_listall_2columns', '2', 'List 2 columns', 'cible', '', '0', '0'),
('form_select_option_view_news_listall_3columns', '1', 'Liste 3 colonnes', 'cible', '', '0', '0'),
('form_select_option_view_news_listall_3columns', '2', 'List 3 columns', 'cible', '', '0', '0'),
('form_extranet_news_label_releaseDate', '1', 'Date d''affichage', 'cible', '', '0', '2'),
('form_extranet_news_label_releaseDate', '2', 'Date of release', 'cible', '', '0', '2');
