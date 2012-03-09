SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- DROP SCHEMA IF EXISTS `nikel693_stentors` ;
CREATE SCHEMA IF NOT EXISTS `nikel693_stentors` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `nikel693_stentors` ;
-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 11 Février 2012 à 12:37
-- Version du serveur: 5.1.56
-- Version de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `nikel693_stentors`
--

-- --------------------------------------------------------

--
-- Structure de la table `MemberProfiles`
--

DROP TABLE IF EXISTS `MemberProfiles`;
CREATE TABLE IF NOT EXISTS `MemberProfiles` (
  `MP_GenericProfileId` int(11) NOT NULL COMMENT 'exclude:true',
  `MP_FirstParent` int(11) DEFAULT NULL COMMENT 'exclude:true',
  `MP_SecondParent` int(11) DEFAULT NULL COMMENT 'exclude:true',
  `MP_BirthDate` date NOT NULL COMMENT 'class:left',
  `MP_Age` int(3) DEFAULT NULL,
  `MP_Section` int(2) DEFAULT NULL COMMENT 'elem:select|src:section|class:clearBoth',
  `MP_School` varchar(255) DEFAULT NULL COMMENT 'class:left',
  `MP_SchoolYear` int(4) DEFAULT NULL COMMENT 'elem:int',
  `MP_Phone` varchar(45) DEFAULT NULL,
  `MP_CountryOrig` varchar(55) DEFAULT NULL,
  `MP_PassportNum` varchar(45) DEFAULT NULL,
  `MP_PassportExpiracyDate` date DEFAULT NULL,
  `MP_PassportBirthDate` varchar(45) DEFAULT NULL,
  `MP_PassportFirstName` varchar(45) DEFAULT NULL,
  `MP_PassportLastName` varchar(45) DEFAULT NULL,
  `MP_LiveWith` int(2) DEFAULT NULL COMMENT 'elem:radio|src:listResp',
  `MP_AgreePhotos` tinyint(1) DEFAULT NULL COMMENT 'elem:checkbox',
  `MP_AssuSocNum` varchar(45) DEFAULT NULL COMMENT 'class:left',
  `MP_Notes` text,
  `MP_CreateDate` datetime DEFAULT NULL COMMENT 'exclude:true',
  `MP_ModifDate` datetime DEFAULT NULL COMMENT 'exclude:true',
  `MP_ModifBy` int(11) DEFAULT NULL COMMENT 'exclude:true			',
  PRIMARY KEY (`MP_GenericProfileId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ParentsProfile`
--

DROP TABLE IF EXISTS `ParentsProfile`;
CREATE TABLE IF NOT EXISTS `ParentsProfile` (
  `PP_GenericProfileId` int(11) NOT NULL COMMENT 'exclude:true',
  `PP_AddressId` int(11) NOT NULL COMMENT 'elem:hidden',
  `PP_TaxReceipt` tinyint(1) NOT NULL COMMENT 'elem:checkbox',
  `PP_EmploiTps` text COMMENT 'class:left',
  `PP_Role` int(11) DEFAULT NULL COMMENT 'elem:select|src:parentsProfile|class:left',
  `PP_Notes` text COMMENT 'class:left',
  `PP_CreateDate` datetime DEFAULT NULL COMMENT 'exclude:true',
  `PP_ModifDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'exclude:true',
  `PP_ModifBy` int(11) DEFAULT NULL COMMENT 'exclude:true',
  PRIMARY KEY (`PP_GenericProfileId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- -----------------------------------------------------
-- Table `nikel693_stentors`.`MedicalRecord`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nikel693_stentors`.`MedicalRecord` ;

CREATE  TABLE IF NOT EXISTS `nikel693_stentors`.`MedicalRecord` (
  `MR_GenericProfileId` INT NOT NULL COMMENT 'exclude:true' ,
  `MR_AssuMaladie` VARCHAR(45) NOT NULL ,
  `MR_ExpiracyDate` VARCHAR(45) NOT NULL ,
  `MR_HasTravelInsur` TINYINT(1) NOT NULL COMMENT 'elem:checkbox' ,
  `MR_TravelInduranceName` VARCHAR(45) NULL ,
  `MR_TravelInduranceExpiracy` DATE NULL ,
  `MR_TravelInduranceNum` VARCHAR(45) NULL ,
  `MR_TravelIndurancePhone` VARCHAR(45) NULL ,
  `MR_EmergyPhone` VARCHAR(45) NULL ,
  `MR_OtherHouse` VARCHAR(45) NULL ,
  `MR_OtherWork` VARCHAR(45) NULL ,
  `MR_OtherCell` VARCHAR(45) NULL ,
  `MR_Allergy` VARCHAR( 255 ) NULL COMMENT 'elem:multiCheckbox|src:allergy',
  `MR_AllergyOther` VARCHAR(255) NULL ,
  `MR_AllergyMedic` TINYINT(1) NULL COMMENT 'elem:checkbox' ,
  `MR_AllergyMedicName` VARCHAR(45) NULL ,
  `MR_AllergyMedicQty` VARCHAR(45) NULL ,
  `MR_AllowEmergencyCares` TINYINT(1) NULL COMMENT 'elem:radio|src:yesNo' ,
  `MR_Diseases` VARCHAR(45) NULL COMMENT 'elem:select|src:diseases' ,
  PRIMARY KEY (`MR_GenericProfileId`) )
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

update MemberProfiles SET MP_LiveWith = 9 Where MP_LiveWith = 1;
update MemberProfiles SET MP_LiveWith = 10 Where MP_LiveWith = 2;
update MemberProfiles SET MP_LiveWith = 11 Where MP_LiveWith = 3;
update MemberProfiles SET MP_LiveWith = 12 Where MP_LiveWith = 4;
update MemberProfiles SET MP_LiveWith = 13 Where MP_LiveWith = 5;
update MemberProfiles SET MP_Section = 14 Where MP_Section = "Couleurs";
update MemberProfiles SET MP_Section = 15 Where MP_Section = "Baryton";
update MemberProfiles SET MP_Section = 16 Where MP_Section = "Bass Drum";
update MemberProfiles SET MP_Section = 17 Where MP_Section = "Clairons";
update MemberProfiles SET MP_Section = 19 Where MP_Section = "Contrebasse";
update MemberProfiles SET MP_Section = 18 Where MP_Section = "Mellophone";
update MemberProfiles SET MP_Section = 20 Where MP_Section = "Pit";
update MemberProfiles SET MP_Section = 21 Where MP_Section = "Snare";
update MemberProfiles SET MP_Section = 22 Where MP_Section = "Soprano";
update MemberProfiles SET MP_Section = 23 Where MP_Section = "Toms";
update MemberProfiles SET MP_Section = 24 Where MP_Section = "Drums";

INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (9, 'garde');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (10, 'garde');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (11, 'garde');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (12, 'garde');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (13, 'garde');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (14, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (15, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (16, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (17, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (18, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (19, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (20, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (21, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (22, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (23, 'section');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (24, 'section');

INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (25, 'role');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (26, 'role');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (27, 'role');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (28, 'role');
INSERT INTO `nikel693_stentors`.`References` (`R_ID`, `R_TypeRef`) VALUES (29, 'role');


INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (9, 1, 'Parents', 1);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (10, 1, 'Père', 2);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (11, 1, 'Mère', 3);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (12, 1, 'Garde partagée', 4);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (13, 1, 'Tuteur / responsable légal', 5);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (14, 1, 'Couleurs', 1);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (15, 1, 'Baryton', 2);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (16, 1, 'Bass Drum', 3);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (17, 1, 'Clairons', 4);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (18, 1, 'Mellophone', 5);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (19, 1, 'Contrebasse', 6);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (20, 1, 'Pit', 7);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (21, 1, 'Snare', 8);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (22, 1, 'Soprano', 9);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (23, 1, 'Toms', 10);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (24, 1, 'Drums', 11);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (26, 1, 'Père', 2);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (25, 1, 'Mère', 3);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (27, 1, 'Tuteur', 4);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (28, 1, 'Grands parents', 5);
INSERT INTO `nikel693_stentors`.`ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES (29, 1, 'Majeur', 6);


REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, `ST_ModuleID`) VALUES
('form_enum_allergy', 1, 'Allergie', 'cible', '', 0, 0),
('form_enum_allergy', 2, 'Allergy', 'cible', '', 0, 0),
('form_enum_role', 1, 'Lien de parenté', 'cible', '', 0, 0),
('form_enum_role', 2, 'Family relationship', 'cible', '', 0, 0),
('form_enum_garde', 1, 'Personne ayant la garde', 'cible', '', 0, 0),
('form_enum_garde', 2, 'Live with', 'cible', '', 0, 0),
('form_enum_section', 1, 'Section', 'cible', '', 0, 0),
('form_enum_section', 2, 'Section', 'cible', '', 0, 0),
('form_enum_diseases', 1, 'Maladies', 'cible', '', 0, 0),
('form_enum_diseases', 2, 'Diseases', 'cible', '', 0, 0);
REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, `ST_ModuleID`) VALUES
('form_profile_delete_dialog_text', 1, 'Êtes-vous sûr de vouloir supprimer ce profil définitivement?', 'cible', '', 0, 20),
('form_profile_delete_dialog_text', 2, 'Are you sure you want to permanently delete this profile?', 'cible', '', 0, 20),
('form_profile_delete_dialog_title', 1, 'Confirmer la suppression?', 'cible', '', 0, 20),
('form_profile_delete_dialog_title', 2, 'Confirm delete action', 'cible', '', 0, 20);

ALTER TABLE Modules ADD `M_HasFrontEnd` tinyint(1) NOT NULL default 1;

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, `ST_ModuleID`) VALUES
('list_column_role', '1', 'Rôle', 'cible', '', '0', '30'),
('list_column_role', '2', 'Role', 'cible', '', '0', '30'),
('list_column_section', '1', 'Section', 'cible', '', '0', '30'),
('list_column_section', '2', 'Section', 'cible', '', '0', '30'),
('management_module_member_list_members', '1', 'Liste des membres', 'cible', '', '0', '30'),
('management_module_member_list_members', '2', 'Members list', 'cible', '', '0', '30'),
('management_module_parent_list', '1', 'Liste des parents', 'cible', '', '0', '30'),
('management_module_parent_list', '2', 'Parents list', 'cible', '', '0', '30'),
('profile_tab_title_member', '1', 'Membre', 'cible', '', '0', '30'),
('profile_tab_title_member', '2', 'Member', 'cible', '', '0', '30'),
('parent_module_name', 1, 'Parent, responsalbes', 'cible', '', 0, 999),
('parent_module_name', 2, 'Parent, responsalbes', 'cible', '', 0, 999),
('member_module_name', 1, 'Membre', 'cible', '', 0, 30),
('member_module_name', 2, 'Member', 'cible', '', 0, 30),
('salutation_melle', 1, 'Mlle', 'cible', '', 0, 0),
('salutation_melle', 2, 'Miss', 'cible', '', 0, 0);

REPLACE INTO Modules (M_ID, M_Title, M_MVCModuleTitle, M_UseProfile) VALUES (31, 'Parents', 'parent', 1);

REPLACE INTO Modules_ControllersActionsPermissions (MCAP_ModuleID, MCAP_ControllerTitle, MCAP_ControllerActionTitle, MCAP_PermissionTitle, MCAP_Position) VALUES
(30, 'index', 'list', 'edit', 1);
(31, 'index', 'list', 'edit', 1);

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, ST_ModuleID) VALUES
('form_label_PP_TaxReceipt', 1, 'Envoyé le relevé pour les impôts',  'cible', '', 0, 31),
('profile_tab_title_parent', '1', 'Responsables', 'cible', '', '0', '31'),
('profile_tab_title_parent', '2', 'Responsables', 'cible', '', '0', '31'),
('button_add_list', 1, 'Ajouter une personne',  'cible', '', 0, 0),
('button_add_list', 2, 'Add a person', 'cible', '', 0, 0),
('form_label_PP_AssuSocNum', 1, 'Numéro d''assurance sociale', 'cible', '', 0, 0);
REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, ST_ModuleID) VALUES
('form_label_MR_HasGlasses', 1, 'Porte des lunettes',  'cible', '', 0, 0),	('form_label_MR_HasGlasses', 2, '', 'cible', '', 0, 0),
('form_label_MR_HasLens', 1, 'Porte des verres de contact',  'cible', '', 0, 0),	('form_label_MR_HasLens', 2, '', 'cible', '', 0, 0),
('form_label_MR_Fracture', 1, 'A déjà une une fracture du crâne',  'cible', '', 0, 0),	('form_label_MR_Fracture', 2, '', 'cible', '', 0, 0),
('form_label_MR_Chirurgie', 1, 'A déjà eu une intervention chirurgicale',  'cible', '', 0, 0),	('form_label_MR_Chirurgie', 2, '', 'cible', '', 0, 0),
('form_label_MR_Specific', 1, 'Précisions',  'cible', '', 0, 0),	('form_label_MR_Specific', 2, '', 'cible', '', 0, 0),
('button_add_profile', 1, 'Ajouter une personne',  'cible', '', 0, 0),	('button_add_profile', 2, '', 'cible', '', 0, 0),
('share_print_text', 1, 'Imprimer',  'cible', '', 0, 0),	('share_print_text', 2, 'Print', 'cible', '', 0, 0),


('form_label_MP_SchoolYear', 1, 'Année',  'cible', '', 0, 30),	('form_label_MP_SchoolYear', 2, 'Year', 'cible', '', 0, 30),
('header_list_member_text', 1, 'Liste des membres inscrits',  'cible', '', 0, 30),	('header_list_member_text', 2, 'Liste des membres inscrits', 'cible', '', 0, 30),
('header_list_member_description', 1, 'Liste des membres inscrits',  'cible', '', 0, 30),	('header_list_member_description', 2, 'Liste des membres inscrits', 'cible', '', 0, 30),
('form_label_MP_BirthDate', 1, 'Date de naissance',  'cible', '', 0, 30),	('form_label_MP_BirthDate', 2, 'Date de naissance', 'cible', '', 0, 30),
('form_label_MP_Section', 1, 'Section',  'cible', '', 0, 30),	('form_label_MP_Section', 2, 'Section', 'cible', '', 0, 30),
('form_label_MP_School', 1, 'École',  'cible', '', 0, 30),	('form_label_MP_School', 2, 'School', 'cible', '', 0, 30),
('form_label_MP_Phone', 1, 'Téléphone perso',  'cible', '', 0, 30),	('form_label_MP_Phone', 2, 'Phone', 'cible', '', 0, 30),
('form_label_MP_CountryOrig', 1, 'Nationalité',  'cible', '', 0, 30),	('form_label_MP_CountryOrig', 2, 'Nationality', 'cible', '', 0, 30),
('form_label_MP_PassportNum', 1, 'Numéro de passeport',  'cible', '', 0, 30),	('form_label_MP_PassportNum', 2, 'Passport number', 'cible', '', 0, 30),
('form_label_MP_PassportExpiracyDate', 1, 'Date d''expiration',  'cible', '', 0, 30),	('form_label_MP_PassportExpiracyDate', 2, 'Expiracy date', 'cible', '', 0, 30),
('form_label_MP_PassportBirthDate', 1, 'Date de naissance',  'cible', '', 0, 30),	('form_label_MP_PassportBirthDate', 2, 'Birth date', 'cible', '', 0, 30),
('form_label_MP_PassportFirstName', 1, 'Prénom',  'cible', '', 0, 30),	('form_label_MP_PassportFirstName', 2, 'Firstname', 'cible', '', 0, 30),
('form_label_MP_PassportLastName', 1, 'Nom',  'cible', '', 0, 30),	('form_label_MP_PassportLastName', 2, 'Lastname', 'cible', '', 0, 30),
('form_label_MP_LiveWith', 1, 'Sous la responsabilité de',  'cible', '', 0, 30),	('form_label_MP_LiveWith', 2, 'Relatives', 'cible', '', 0, 30),
('form_label_MP_AgreePhotos', 1, 'Accord pour le droit à l''image',  'cible', '', 0, 30),	('form_label_MP_AgreePhotos', 2, 'Can use photos', 'cible', '', 0, 30),
('form_label_MP_AssuSocNum', 1, 'Numéro d''assurance sociale',  'cible', '', 0, 30),	('form_label_MP_AssuSocNum', 2, 'Social Insurance Number', 'cible', '', 0, 30),
('form_label_MP_Notes', 1, 'Informations complémentaires',  'cible', '', 0, 30),	('form_label_MP_Notes', 2, 'More informations', 'cible', '', 0, 30),
('form_label_PP_EmploiTps', 1, 'Emploi du temps',  'cible', '', 0, 31),	('form_label_PP_EmploiTps', 2, 'Schedule', 'cible', '', 0, 31),
('form_label_PP_Role', 1, 'Lien de parenté / responsabilité',  'cible', '', 0, 31),	('form_label_PP_Role', 2, 'Relatives', 'cible', '', 0, 31),
('form_label_PP_Notes', 1, 'Informations complémentaires',  'cible', '', 0, 31),	('form_label_PP_Notes', 2, 'More informations', 'cible', '', 0, 31),
('form_label_MP_Age', 1, 'Age',  'cible', '', 0, 30),	('form_label_MP_Age', 2, 'Age', 'cible', '', 0, 30),
('header_list_parents_text', 1, 'Liste des parents',  'cible', '', 0, 31),	('header_list_parents_text', 2, 'Parents list', 'cible', '', 0, 31),
('header_list_parents_description', 1, 'Liste des parents',  'cible', '', 0, 31),	('header_list_parents_description', 2, 'Parents list', 'cible', '', 0, 31);

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, ST_ModuleID) VALUES
('form_label_MR_AssuMaladie', 1, 'Compagnie',  'cible', '', 0, 0),
('form_label_MR_AssuMaladie', 2, 'Company', 'cible', '', 0, 0),
('form_label_MR_ExpiracyDate', 1, 'Date d''expiration',  'cible', '', 0, 0),
('form_label_MR_ExpiracyDate', 2, 'Expiracy date', 'cible', '', 0, 0),
('form_label_MR_HasTravelInsur', 1, 'Possède une assurance hors Québec',  'cible', '', 0, 0),
('form_label_MR_HasTravelInsur', 2, 'Has insurrance outside of Quebec', 'cible', '', 0, 0),
('form_label_MR_TravelInduranceName', 1, 'Compagnie',  'cible', '', 0, 0),
('form_label_MR_TravelInduranceName', 2, 'Company', 'cible', '', 0, 0),
('form_label_MR_TravelInduranceExpiracy', 1, 'Date d''expiration',  'cible', '', 0, 0),
('form_label_MR_TravelInduranceExpiracy', 2, 'Expiracy date', 'cible', '', 0, 0),
('form_label_MR_TravelInduranceNum', 1, 'Numéro de police',  'cible', '', 0, 0),
('form_label_MR_TravelInduranceNum', 2, 'Insurrance number', 'cible', '', 0, 0),
('form_label_MR_TravelIndurancePhone', 1, 'Téléphone',  'cible', '', 0, 0),
('form_label_MR_TravelIndurancePhone', 2, 'Phone number', 'cible', '', 0, 0),
('form_label_MR_EmergyPhone', 1, 'Téléphone pour urgence',  'cible', '', 0, 0),
('form_label_MR_EmergyPhone', 2, 'Emergency phone number', 'cible', '', 0, 0),
('form_label_MR_OtherHouse', 1, 'Maison (autre)',  'cible', '', 0, 0),
('form_label_MR_OtherHouse', 2, 'Home (other)', 'cible', '', 0, 0),
('form_label_MR_OtherWork', 1, 'Travail (aute)',  'cible', '', 0, 0),
('form_label_MR_OtherWork', 2, 'Work (other)', 'cible', '', 0, 0),
('form_label_MR_OtherCell', 1, 'Cellulaire (autre)',  'cible', '', 0, 0),
('form_label_MR_OtherCell', 2, 'Cellphone (other)', 'cible', '', 0, 0),
('form_label_MR_Allergy', 1, 'Allergiqur à',  'cible', '', 0, 0),
('form_label_MR_Allergy', 2, 'Allergique à', 'cible', '', 0, 0),
('form_label_MR_AllergyOther', 1, 'Autres allergie',  'cible', '', 0, 0),
('form_label_MR_AllergyOther', 2, 'Autres allergies', 'cible', '', 0, 0),
('form_label_MR_AllergyMedic', 1, 'Traitement médicamenteu',  'cible', '', 0, 0),
('form_label_MR_AllergyMedic', 2, 'Drug medication', 'cible', '', 0, 0),
('form_label_MR_AllergyMedicName', 1, 'Nom du médicament',  'cible', '', 0, 0),
('form_label_MR_AllergyMedicName', 2, 'Drug name', 'cible', '', 0, 0),
('form_label_MR_AllergyMedicQty', 1, 'Concentration',  'cible', '', 0, 0),
('form_label_MR_AllergyMedicQty', 2, 'Concentration', 'cible', '', 0, 0),
('form_label_MR_AllowEmergencyCares', 1, 'Autorisation pour administrer le traitement',  'cible', '', 0, 0),
('form_label_MR_AllowEmergencyCares', 2, 'Autorisation pour administrer le traitement', 'cible', '', 0, 0),
('form_label_MR_Diseases', 1, 'Maladies',  'cible', '', 0, 0),
('form_label_MR_Diseases', 2, 'Diseases', 'cible', '', 0, 0),
('profile_tab_title_medical', 1, 'Fiche médicale',  'cible', '', 0, 0),
('profile_tab_title_medical', 2, 'Fiche médicale', 'cible', '', 0, 0),
('medical_module_name', 1, 'Fiche médicale',  'cible', '', 0, 0),
('medical_module_name', 2, 'Fiche médicale', 'cible', '', 0, 0),
('form_label_DD_HasMedic', 1, 'Prend des médicaments',  'cible', '', 0, 32),
('form_label_DD_HasMedic', 2, '', 'cible', '', 0, 32),
('form_label_DD_TypeMedic', 1, 'Sous forme de',  'cible', '', 0, 32),
('form_label_DD_TypeMedic', 2, '', 'cible', '', 0, 32),
('form_label_DD_MedicName', 1, 'Nom du médicament',  'cible', '', 0, 32),
('form_label_DD_MedicName', 2, '', 'cible', '', 0, 32),
('form_label_DD_Dose', 1, 'Concentration',  'cible', '', 0, 32),
('form_label_DD_Dose', 2, '', 'cible', '', 0, 32),
('form_label_DD_Frequence', 1, 'Fréquence par jour',  'cible', '', 0, 32),
('form_label_DD_Frequence', 2, '', 'cible', '', 0, 32),
('form_label_DD_KnowsHowto', 1, 'Connaît la procédure pour se l''administrer',  'cible', '', 0, 32),
('form_label_DD_KnowsHowto', 2, '', 'cible', '', 0, 32),
('form_label_DD_NeedHelp', 1, 'Besoin de supervision par un responsable',  'cible', '', 0, 32),
('form_label_DD_NeedHelp', 2, '', 'cible', '', 0, 32);


ALTER TABLE `MedicalRecord` ADD `MR_HasGlasses` TINYINT( 1 ) NULL COMMENT 'elem:checkbox' AFTER `MR_Diseases` ,
ADD `MR_HasLens` TINYINT( 1 ) NULL COMMENT 'elem:checkbox' AFTER `MR_HasGlasses` ,
ADD `MR_Fracture` TINYINT( 1 ) NULL COMMENT 'elem:checkbox' AFTER `MR_HasLens` ,
ADD `MR_Chirurgie` TINYINT( 1 ) NULL COMMENT 'elem:checkbox' AFTER `MR_Fracture` ,
ADD `MR_Specific` VARCHAR( 255 ) NULL AFTER `MR_Chirurgie`
