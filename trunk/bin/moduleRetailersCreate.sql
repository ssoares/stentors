--
-- Données pour activer module et les liens dans le back end
--

INSERT INTO Modules (M_ID, M_Title, M_MVCModuleTitle, M_UseProfile) VALUES (16, 'Retailers', 'retailers', 1);

--INSERT INTO `ModuleViews` (`MV_ID`, `MV_Name`, `MV_ModuleID`) VALUES
--(16001, 'retailer_details', 16),
--(16002, 'retailer_list', 16);

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, ST_ModuleID) VALUES
('module_retailers', 1, 'Détaillants', 'cible', '', 0, 16),
('module_retailers', 2, 'Retailers', 'cible', '', 0, 16),
('retailers_module_name', 1, 'Détaillants', 'cible', '', 0, 16),
('retailers_module_name', 2, 'Retailers', 'cible', '', 0, 16),
('form_label_select_city', 1, 'S&eacute;lectionnez une ville', 'cible', '', 0, 16),
('form_label_select_city', 2, 'Choose a city', 'cible', '', 0, 16),
('form_label_select_state', 1, 'S&eacute;lectionnez une province', 'cible', '', 0, 16),
('form_label_select_state', 2, 'Choose a province', 'cible', '', 0, 16),
('locate_retailer_label', 1, 'Localiser un professionnel', 'client', '', 0, 16),
('locate_retailer_label', 2, 'Locate a professionnal', 'client', '', 0, 16),
('profile_title_retailer_web', 1, 'Détaillants: informations à afficher', 'cible', '', 0, 16),
('profile_title_retailer_web', 2, 'Retailers: Data to display', 'cible', '', 0, 16),
('form_label_Display_web', 1, 'Afficher sur le site', 'cible', '', 0, 16),
('form_label_Display_web', 2, 'Display on the website', 'cible', '', 0, 16);
