SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `nikel693_stentors` ;
CREATE SCHEMA IF NOT EXISTS `nikel693_stentors` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `nikel693_stentors` ;

-- -----------------------------------------------------
-- Table `nikel693_stentors`.`ParentsProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nikel693_stentors`.`ParentsProfile` ;

CREATE  TABLE IF NOT EXISTS `nikel693_stentors`.`ParentsProfile` (
  `PP_GenericProfileId` INT(11) NOT NULL COMMENT 'exclude:true' ,
  `PP_ChildrenId` INT(11) NOT NULL COMMENT 'select:children' ,
  `PP_AddressId` INT(11) NOT NULL COMMENT 'exclude:true' ,
  `PP_EmploiTps` TEXT NULL ,
  `PP_CreateDate` DATETIME NULL COMMENT 'exclude:true' ,
  `PP_ModifDate` DATETIME NULL COMMENT 'exclude:true' ,
  `PP_ModifBy` INT(11) NULL COMMENT 'exclude:true' ,
  `PP_Role` VARCHAR(45) NULL COMMENT '	' ,
  `PP_Notes` TEXT NULL COMMENT 'elem:tiny' ,
  PRIMARY KEY (`PP_GenericProfileId`) ,
  INDEX `memberId` (`PP_ChildrenId` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `nikel693_stentors`.`MemberProfiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `nikel693_stentors`.`MemberProfiles` ;

CREATE  TABLE IF NOT EXISTS `nikel693_stentors`.`MemberProfiles` (
  `MP_GenericProfileId` INT(11) NOT NULL COMMENT 'exclude:true' ,
  `MP_BirthDate` DATE NOT NULL ,
  `MP_Age` INT(3) NULL ,
  `MP_Section` ENUM('clairons','drums','pit','couleurs') NULL COMMENT 'src:section' ,
  `MP_School` VARCHAR(255) NULL ,
  `MP_SchoolYear` INT(4) NULL ,
  `MP_Email` VARCHAR(255) NULL COMMENT 'validate:email' ,
  `MP_CountryOrig` VARCHAR(55) NULL ,
  `MP_PassportNum` VARCHAR(45) NULL ,
  `MP_PassportExpiracyDate` DATETIME NULL ,
  `MP_PassportBirthDate` VARCHAR(45) NULL ,
  `MP_PassportFirstName` VARCHAR(45) NULL ,
  `MP_PassportLastName` VARCHAR(45) NULL ,
  `MP_LiveWith` INT(1) NULL COMMENT 'elem:radio|src:listResp' ,
  `MP_AgreePhotos` TINYINT(1) NULL COMMENT 'elem:checkbox' ,
  `MP_AssuSocNum` VARCHAR(45) NULL ,
  `MP_CreateDate` DATETIME NULL COMMENT 'exclude:true' ,
  `MP_ModifDate` DATETIME NULL COMMENT 'exclude:true' ,
  `MP_ModifBy` INT(11) NULL COMMENT 'exclude:true			' ,
  `MP_Notes` TEXT NULL COMMENT 'elem:tiny' ,
  PRIMARY KEY (`MP_GenericProfileId`) )
ENGINE = MyISAM;


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