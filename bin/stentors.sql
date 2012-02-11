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
  `MR_Allergy` ENUM('noix','piqure','autre') NULL ,
  `MR_AllergyOther` VARCHAR(255) NULL ,
  `MR_AllergyMedic` TINYINT(1) NULL COMMENT 'elem:chesckbox' ,
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

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, `ST_ModuleID`) VALUES
('form_enum_role', 1, 'Lien de parenté', 'cible', '', 0, 0),
('form_enum_role', 2, 'Family relationship', 'cible', '', 0, 0),
('form_enum_garde', 1, 'Personne ayant la garde', 'cible', '', 0, 0),
('form_enum_garde', 2, 'Live with', 'cible', '', 0, 0),
('form_enum_section', 1, 'Section', 'cible', '', 0, 0),
('form_enum_section', 2, 'Section', 'cible', '', 0, 0),
('form_enum_diseases', 1, 'Maladies', 'cible', '', 0, 0),
('form_enum_diseases', 2, 'Diseases', 'cible', '', 0, 0);

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
('parent_module_name', 1, 'Parent', 'cible', '', 0, 999),
('parent_module_name', 2, 'Parent', 'cible', '', 0, 999),
('member_module_name', 1, 'Membre', 'cible', '', 0, 30),
('member_module_name', 2, 'Member', 'cible', '', 0, 30),
('salutation_melle', 1, 'Mlle', 'cible', '', 0, 0),
('salutation_melle', 2, 'Miss', 'cible', '', 0, 0);

REPLACE INTO Modules (M_ID, M_Title, M_MVCModuleTitle, M_UseProfile) VALUES (31, 'Parents', 'parent', 1);

REPLACE INTO Modules_ControllersActionsPermissions (MCAP_ModuleID, MCAP_ControllerTitle, MCAP_ControllerActionTitle, MCAP_PermissionTitle, MCAP_Position) VALUES
(30, 'index', 'list', 'edit', 1);
(31, 'index', 'list', 'edit', 1);

REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, ST_ModuleID) VALUES
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
('header_list_parents_text', 1, 'Liste des parents',  'cible', '', 0, 31),	('header_list_parents_text', 2, 'Parents list', 'cible', '', 0, 31),
('header_list_parents_description', 1, 'Liste des parents',  'cible', '', 0, 31),	('header_list_parents_description', 2, 'Parents list', 'cible', '', 0, 31);