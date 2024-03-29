-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- Host: 209.222.235.12:3306
-- Generation Time: Apr 21, 2010 at 10:12 AM
-- Server version: 5.0.70
-- PHP Version: 5.2.10-pl0-gentoo
-- Version SVN: $Id: defaultData.sql 826 2012-02-01 04:15:13Z ssoares $

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Initialize default data for the new database
--

--
-- Table structure for table `Address`
--

DROP TABLE IF EXISTS `AddressData`;
CREATE TABLE IF NOT EXISTS `AddressData` (
  `A_AddressId` int(10) NOT NULL auto_increment,
  `A_Duplicate` INT( 10 ) NULL DEFAULT NULL,
  `A_CityTextValue` varchar(50) default null,
  `A_CityId` int(10) NOT NULL,
  `A_StateId` int(10) NOT NULL default '1',
  `A_CountryId` int(10) default '1',
  `A_ZipCode` varchar(50) NOT NULL,
  `A_Fax` varchar(255) default '0',
  `A_Email` varchar(255) default '0',
  PRIMARY KEY  (`A_AddressId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
--
-- Table structure for table `Address`
--
DROP TABLE IF EXISTS  `AddressIndex`;
CREATE TABLE IF NOT EXISTS `AddressIndex` (
  `AI_AddressId` int(10) NOT NULL,
  `AI_LanguageID` int(10) NOT NULL,
  `AI_Name` varchar(255) default NULL,
  `AI_FirstName` varchar(255) default NULL,
  `AI_FirstAddress` varchar(255) NULL,
  `AI_SecondAddress` varchar(255) default NULL,
  `AI_FirstTel` varchar(255) default NULL,
  `AI_FirstExt` varchar(255) default NULL,
  `AI_SecondTel` varchar(255) default NULL,
  `AI_SecondExt` varchar(255) default NULL,
  `AI_Website` varchar(255) default NULL,
  PRIMARY KEY  (`AI_AddressId`, `AI_LanguageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `Countries`
--

DROP TABLE IF EXISTS  `Countries`;
CREATE TABLE IF NOT EXISTS `Countries` (
  `C_ID` int(11) NOT NULL,
  `C_Identifier` varchar(2) NOT NULL,
  PRIMARY KEY  (`C_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SP_Pays`
--

INSERT INTO `Countries` (`C_ID`, `C_Identifier`) VALUES
(1, ''),
(2, ''),
(3, ''),
(4, ''),
(5, ''),
(6, ''),
(7, 'ca'),
(8, ''),
(9, ''),
(10, ''),
(11, ''),
(12, ''),
(13, ''),
(14, ''),
(15, ''),
(16, ''),
(17, ''),
(18, ''),
(19, ''),
(20, ''),
(21, ''),
(22, ''),
(23, ''),
(24, ''),
(25, ''),
(27, ''),
(28, ''),
(29, ''),
(30, ''),
(31, ''),
(32, ''),
(33, ''),
(34, ''),
(35, ''),
(36, ''),
(37, ''),
(38, ''),
(39, ''),
(40, ''),
(41, ''),
(42, ''),
(43, ''),
(44, ''),
(45, ''),
(46, ''),
(47, ''),
(48, ''),
(49, ''),
(50, ''),
(51, ''),
(52, 'us'),
(53, '');

-- --------------------------------------------------------

--
-- Table structure for table `CountriesIndex`
--

DROP TABLE IF EXISTS  `CountriesIndex`;
CREATE TABLE IF NOT EXISTS `CountriesIndex` (
  `CI_CountryID` int(11) NOT NULL,
  `CI_LanguageID` int(11) NOT NULL,
  `CI_Name` varchar(255) default NULL,
  PRIMARY KEY  (`CI_CountryID`,`CI_LanguageID`),
  KEY `FK_CountryIndex_Country` (`CI_CountryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CountriesIndex`
--

INSERT INTO `CountriesIndex` (`CI_CountryID`, `CI_LanguageID`, `CI_Name`) VALUES
(1, 1, 'American Samoa'),
(1, 2, 'American Samoa'),
(2, 1, 'Austria'),
(2, 2, 'Austria'),
(3, 1, 'Australia'),
(3, 2, 'Australia'),
(4, 1, 'Belarus'),
(4, 2, 'Belarus'),
(5, 1, 'Belgium'),
(5, 2, 'Belgium'),
(6, 1, 'Bulgaria'),
(6, 2, 'Bulgaria'),
(7, 1, 'Canada'),
(7, 2, 'Canada'),
(8, 1, 'China'),
(8, 2, 'China'),
(9, 1, 'Croatia'),
(9, 2, 'Croatia'),
(10, 1, 'Czech Rep.'),
(10, 2, 'Czech Rep.'),
(11, 1, 'Denmark'),
(11, 2, 'Denmark'),
(12, 1, 'England'),
(12, 2, 'England'),
(13, 1, 'Estonia'),
(13, 2, 'Estonia'),
(14, 1, 'Europe'),
(14, 2, 'Europe'),
(15, 1, 'Finland'),
(15, 2, 'Finland'),
(16, 1, 'France'),
(16, 2, 'France'),
(17, 1, 'Great Britain'),
(17, 2, 'Great Britain'),
(18, 1, 'Germany'),
(18, 2, 'Germany'),
(19, 1, 'Hungary'),
(19, 2, 'Hungary'),
(20, 1, 'Iceland'),
(20, 2, 'Iceland'),
(21, 1, 'Ireland'),
(21, 2, 'Ireland'),
(22, 1, 'Israel'),
(22, 2, 'Israel'),
(23, 1, 'Italy'),
(23, 2, 'Italy'),
(24, 1, 'Japan'),
(24, 2, 'Japan'),
(25, 1, 'Kazakhstan'),
(25, 2, 'Kazakhstan'),
(27, 1, 'Korea'),
(27, 2, 'Korea'),
(28, 1, 'Latvia'),
(28, 2, 'Latvia'),
(29, 1, 'Lithuania'),
(29, 2, 'Lithuania'),
(30, 1, 'Luxembourg'),
(30, 2, 'Luxembourg'),
(31, 1, 'Mongolia'),
(31, 2, 'Mongolia'),
(32, 1, 'Mexico'),
(32, 2, 'Mexico'),
(33, 1, 'Netherlands'),
(33, 2, 'Netherlands'),
(34, 1, 'Norway'),
(34, 2, 'Norway'),
(35, 1, 'New Zealand'),
(35, 2, 'New Zealand'),
(36, 1, 'Poland'),
(36, 2, 'Poland'),
(37, 1, 'Portugal'),
(37, 2, 'Portugal'),
(38, 1, 'Dpr Korea'),
(38, 2, 'Dpr Korea'),
(39, 1, 'Romania'),
(39, 2, 'Romania'),
(40, 1, 'Russia'),
(40, 2, 'Russia'),
(41, 1, 'South Africa'),
(41, 2, 'South Africa'),
(42, 1, 'Sweden'),
(42, 2, 'Sweden'),
(43, 1, 'Slovenia'),
(43, 2, 'Slovenia'),
(44, 1, 'Spain'),
(44, 2, 'Spain'),
(45, 1, 'Slovakia'),
(45, 2, 'Slovakia'),
(46, 1, 'Switzerland'),
(46, 2, 'Switzerland'),
(47, 1, 'Thailand'),
(47, 2, 'Thailand'),
(48, 1, 'Tokyo'),
(48, 2, 'Tokyo'),
(49, 1, 'Taiwan'),
(49, 2, 'Taiwan'),
(50, 1, 'Turkey'),
(50, 2, 'Turkey'),
(51, 1, 'Ukraine'),
(51, 2, 'Ukraine'),
(52, 1, 'USA'),
(52, 2, 'USA'),
(53, 1, 'Yugoslavia'),
(53, 2, 'Yugoslavia');

-- --------------------------------------------------------

--
-- Table structure for table `States`
--

DROP TABLE IF EXISTS  `States`;
CREATE TABLE IF NOT EXISTS `States` (
  `S_ID` int(11) NOT NULL,
  `S_CountryID` int(11) NOT NULL,
  `S_Identifier` varchar(2) NOT NULL,
  PRIMARY KEY  (`S_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `States`
--

INSERT INTO `States` (`S_ID`, `S_CountryID`, `S_Identifier`) VALUES
(1, 7, ''),
(2, 7, ''),
(3, 7, ''),
(4, 7, ''),
(5, 7, ''),
(6, 7, ''),
(7, 7, ''),
(8, 7, ''),
(9, 7, ''),
(10, 7, ''),
(11, 7, ''),
(12, 7, ''),
(13, 7, ''),
(14, 52, ''),
(15, 52, ''),
(16, 52, ''),
(17, 52, ''),
(18, 52, ''),
(19, 52, ''),
(20, 52, ''),
(21, 52, ''),
(22, 52, ''),
(23, 52, ''),
(24, 52, ''),
(25, 52, ''),
(26, 52, ''),
(27, 52, ''),
(28, 52, ''),
(29, 52, ''),
(30, 52, ''),
(31, 52, ''),
(32, 52, ''),
(33, 52, ''),
(34, 52, ''),
(35, 52, ''),
(36, 52, ''),
(37, 52, ''),
(38, 52, ''),
(39, 52, ''),
(40, 52, ''),
(41, 52, ''),
(42, 52, ''),
(43, 52, ''),
(44, 52, ''),
(45, 52, ''),
(46, 52, ''),
(47, 52, ''),
(48, 52, ''),
(49, 52, ''),
(50, 52, ''),
(51, 52, ''),
(52, 52, ''),
(53, 52, ''),
(54, 52, ''),
(55, 52, ''),
(56, 52, ''),
(57, 52, ''),
(58, 52, ''),
(59, 52, ''),
(60, 52, ''),
(61, 52, ''),
(62, 52, ''),
(63, 52, ''),
(64, 52, '');

-- --------------------------------------------------------

--
-- Table structure for table `StatesIndex`
--

DROP TABLE IF EXISTS  `StatesIndex`;
CREATE TABLE IF NOT EXISTS `StatesIndex` (
  `SI_StateID` int(11) NOT NULL,
  `SI_LanguageID` int(11) NOT NULL,
  `SI_Name` varchar(255) default NULL,
  PRIMARY KEY  (`SI_StateID`,`SI_LanguageID`),
  KEY `FK_StateIndex_State1` (`SI_StateID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `StatesIndex`
--

INSERT INTO `StatesIndex` (`SI_StateID`, `SI_LanguageID`, `SI_Name`) VALUES
(1, 1, 'Alberta'),
(1, 2, 'Alberta'),
(2, 1, 'Colombie-Britanique'),
(2, 2, 'British Columbia'),
(3, 1, 'Manitoba'),
(3, 2, 'Manitoba'),
(4, 1, 'Nouveau-Brunswick'),
(4, 2, 'New Brunswick'),
(5, 1, 'Terre-Neuve-et-Labrador'),
(5, 2, 'Newfoundland & Labrador'),
(6, 1, 'Nouvelle-Écosse'),
(6, 2, 'Nova Scotia'),
(7, 1, 'Territoires du Nord-Ouest'),
(7, 2, 'Northwest Territories'),
(8, 1, 'Nunavut'),
(8, 2, 'Nunavut'),
(9, 1, 'Ontario'),
(9, 2, 'Ontario'),
(10, 1, 'île-du-Prince-Édouard'),
(10, 2, 'Prince Edward Island'),
(11, 1, 'Québec'),
(11, 2, 'Quebec'),
(12, 1, 'Saskatchewan'),
(12, 2, 'Saskatchewan'),
(13, 1, 'Yukon'),
(13, 2, 'Yukon'),
(14, 1, 'Alaska'),
(14, 2, 'Alaska'),
(15, 1, 'Alabama'),
(15, 2, 'Alabama'),
(16, 1, 'Arkansas'),
(16, 2, 'Arkansas'),
(17, 1, 'Arizona'),
(17, 2, 'Arizona'),
(18, 1, 'Californie'),
(18, 2, 'California'),
(19, 1, 'Colorado'),
(19, 2, 'Colorado'),
(20, 1, 'Connecticut'),
(20, 2, 'Connecticut'),
(21, 1, 'District de Columbia'),
(21, 2, 'District of Columbia'),
(22, 1, 'Delaware'),
(22, 2, 'Delaware'),
(23, 1, 'Floride'),
(23, 2, 'Florida'),
(24, 1, 'Géorgie'),
(24, 2, 'Georgia'),
(25, 1, 'Hawaii'),
(25, 2, 'Hawaii'),
(26, 1, 'Iowa'),
(26, 2, 'Iowa'),
(27, 1, 'Idaho'),
(27, 2, 'Idaho'),
(28, 1, 'Illinois'),
(28, 2, 'Illinois'),
(29, 1, 'Indiana'),
(29, 2, 'Indiana'),
(30, 1, 'Kansas'),
(30, 2, 'Kansas'),
(31, 1, 'Kentucky'),
(31, 2, 'Kentucky'),
(32, 1, 'Louisiane'),
(32, 2, 'Louisiana'),
(33, 1, 'Massachusetts'),
(33, 2, 'Massachusetts'),
(34, 1, 'Maryland'),
(34, 2, 'Maryland'),
(35, 1, 'Maine'),
(35, 2, 'Maine'),
(36, 1, 'Michigan'),
(36, 2, 'Michigan'),
(37, 1, 'Minnesota'),
(37, 2, 'Minnesota'),
(38, 1, 'Missouri'),
(38, 2, 'Missouri'),
(39, 1, 'Mississippi'),
(39, 2, 'Mississippi'),
(40, 1, 'Montana'),
(40, 2, 'Montana'),
(41, 1, 'Caroline du Nord'),
(41, 2, 'North Carolina'),
(42, 1, 'Dakota du Nord'),
(42, 2, 'North Dakota'),
(43, 1, 'Nebraska'),
(43, 2, 'Nebraska'),
(44, 1, 'New Hampshire'),
(44, 2, 'New Hampshire'),
(45, 1, 'New Jersey'),
(45, 2, 'New Jersey'),
(46, 1, 'Nouveau-Mexique'),
(46, 2, 'New Mexico'),
(47, 1, 'Nevada'),
(47, 2, 'Nevada'),
(48, 1, 'New York'),
(48, 2, 'New York'),
(49, 1, 'Ohio'),
(49, 2, 'Ohio'),
(50, 1, 'Oklahoma'),
(50, 2, 'Oklahoma'),
(51, 1, 'Oregon'),
(51, 2, 'Oregon'),
(52, 1, 'Pennsylvanie'),
(52, 2, 'Pennsylvania'),
(53, 1, 'Rhode Island'),
(53, 2, 'Rhode Island'),
(54, 1, 'Caroline du Sud'),
(54, 2, 'South Carolina'),
(55, 1, 'Dakota du Sud'),
(55, 2, 'South Dakota'),
(56, 1, 'Tennessee'),
(56, 2, 'Tennessee'),
(57, 1, 'Texas'),
(57, 2, 'Texas'),
(58, 1, 'Utah'),
(58, 2, 'Utah'),
(59, 1, 'Virginie'),
(59, 2, 'Virginia'),
(60, 1, 'Vermont'),
(60, 2, 'Vermont'),
(61, 1, 'Washington'),
(61, 2, 'Washington'),
(62, 1, 'Wisconsin'),
(62, 2, 'Wisconsin'),
(63, 1, 'Virginie-Occidentale'),
(63, 2, 'West Virginia'),
(64, 1, 'Wyoming'),
(64, 2, 'Wyoming');

-- --------------------------------------------------------

--
-- Structure de la table `Cities`
--

DROP TABLE IF EXISTS `Cities`;
CREATE TABLE IF NOT EXISTS `Cities` (
  `C_ID` int(11) NOT NULL,
  `C_StateID` int(11) NOT NULL,
  `C_Name` varchar(255) NOT NULL,
  PRIMARY KEY  (`C_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `References`
--

CREATE TABLE IF NOT EXISTS `References` (
  `R_ID` int(11) NOT NULL auto_increment,
  `R_TypeRef` enum('subscrArg','unsubscrArg') NOT NULL,
  PRIMARY KEY  (`R_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `References`
--

INSERT INTO `References` (`R_ID`, `R_TypeRef`) VALUES
(3, 'subscrArg'),
(4, 'subscrArg'),
(5, 'subscrArg'),
(6, 'unsubscrArg'),
(7, 'unsubscrArg');

-- --------------------------------------------------------

--
-- Table structure for table `ReferencesIndex`
--

CREATE TABLE IF NOT EXISTS `ReferencesIndex` (
  `RI_RefId` int(11) NOT NULL,
  `RI_LanguageID` int(2) NOT NULL,
  `RI_Value` varchar(50) default NULL,
  `RI_Seq` int(11) default NULL,
  PRIMARY KEY  (`RI_RefId`,`RI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ReferencesIndex`
--

INSERT INTO `ReferencesIndex` (`RI_RefId`, `RI_LanguageID`, `RI_Value`, `RI_Seq`) VALUES
(3, 1, 'Sur un site spécialisé', 1),
(4, 1, 'Dans la presse', 2),
(5, 1, 'Par un fournisseur', 3),
(6, 1, 'Pas de contenu pertinent', 1),
(7, 1, 'Trop d''envois par mois', 2);
-- --------------------------------------------------------

--
-- Table structure for table `Blocks`
--

CREATE TABLE IF NOT EXISTS `Blocks` (
  `B_ID` int(10) NOT NULL auto_increment,
  `B_PageID` int(10) NOT NULL,
  `B_ModuleID` int(10) NOT NULL,
  `B_ZoneID` int(11) NOT NULL default '1',
  `B_Position` int(10) default '1',
  `B_ShowHeader` int(2) NOT NULL,
  `B_Draft` int(1) default '0',
  `B_Online` int(1) default '0',
  `B_Secured` TINYINT( 1 ) NOT NULL DEFAULT '0',
  `B_LastModified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`B_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `BlocksIndex`
--

CREATE TABLE IF NOT EXISTS `BlocksIndex` (
  `BI_BlockID` int(11) NOT NULL,
  `BI_LanguageID` int(11) NOT NULL,
  `BI_BlockTitle` varchar(255) NOT NULL,
  PRIMARY KEY  (`BI_BlockID`,`BI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `C_ID` int(11) NOT NULL auto_increment,
  `C_ParentID` int(11) NOT NULL,
  `C_ModuleID` int(11) NOT NULL,
  `C_PageID` int(11) default NULL,
  `C_ShowInRss` tinyint(4) NOT NULL default '0',
  `C_RssItemsCount` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`C_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `CategoriesIndex`
--
DROP TABLE IF EXISTS `CategoriesIndex`;
CREATE TABLE IF NOT EXISTS `CategoriesIndex` (
  `CI_CategoryID` int(11) NOT NULL,
  `CI_LanguageID` int(11) NOT NULL,
  `CI_Title` text NOT NULL,
  `CI_WordingShowAllRecords` text NOT NULL,
  UNIQUE KEY `CI_CategorieID` (`CI_CategoryID`,`CI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `Extranet_Groups`
--

CREATE TABLE IF NOT EXISTS `Extranet_Groups` (
  `EG_ID` int(11) NOT NULL auto_increment,
  `EG_Status` enum('inactive','active') NOT NULL,
  PRIMARY KEY  (`EG_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_Groups`
--

INSERT INTO `Extranet_Groups` (`EG_ID`, `EG_Status`) VALUES
(1, 'active'),
(2, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_GroupsIndex`
--

CREATE TABLE IF NOT EXISTS `Extranet_GroupsIndex` (
  `EGI_GroupID` int(11) NOT NULL,
  `EGI_LanguageID` int(11) NOT NULL,
  `EGI_Name` varchar(255) NOT NULL,
  `EGI_Description` longtext NOT NULL,
  PRIMARY KEY  (`EGI_GroupID`,`EGI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_GroupsIndex`
--

INSERT INTO `Extranet_GroupsIndex` (`EGI_GroupID`, `EGI_LanguageID`, `EGI_Name`, `EGI_Description`) VALUES
(1, 1, 'Administrateurs', 'Ce groupe donne accès à tout ce qui est contrôlable dans l\'extranet. De la structure du site Web à la gestion des autres administrateurs.'),
(1, 2, 'Administrators', 'This group provides access to all that is controllable in the extranet. The structure of the website to the management of other directors.'),
(2, 1, 'Rédacteurs', 'Ce groupe permet de créer, éditer et valider les données.'),
(2, 2, 'Redactors', 'This group allows to create, edit and validate data.');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Groups_Pages_Permissions`
--

CREATE TABLE IF NOT EXISTS `Extranet_Groups_Pages_Permissions` (
  `EGPP_GroupID` int(11) NOT NULL,
  `EGPP_PageID` int(11) NOT NULL,
  `EGPP_Structure` enum('Y','N') NOT NULL default 'N',
  `EGPP_Data` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`EGPP_GroupID`,`EGPP_PageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Groups_RolesResources`
--

CREATE TABLE IF NOT EXISTS `Extranet_Groups_RolesResources` (
  `EGRRP_GroupID` int(11) NOT NULL,
  `EGRRP_RoleResourceID` int(11) NOT NULL,
  `EGRRP_Access` enum('allow','deny') NOT NULL,
  PRIMARY KEY  (`EGRRP_GroupID`,`EGRRP_RoleResourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Permissions`
--

CREATE TABLE IF NOT EXISTS `Extranet_Permissions` (
  `EP_ID` int(11) NOT NULL auto_increment,
  `EP_ControlName` varchar(255) NOT NULL,
  PRIMARY KEY  (`EP_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_Permissions`
--

INSERT INTO `Extranet_Permissions` (`EP_ID`, `EP_ControlName`) VALUES
(1, 'edit'),
(2, 'submit'),
(3, 'publish'),
(4, 'manage');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Resources`
--

CREATE TABLE IF NOT EXISTS `Extranet_Resources` (
  `ER_ID` int(11) NOT NULL auto_increment,
  `ER_ControlName` varchar(255) NOT NULL,
  PRIMARY KEY  (`ER_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_Resources`
--

INSERT INTO `Extranet_Resources` (`ER_ID`, `ER_ControlName`) VALUES
(1, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_ResourcesIndex`
--

CREATE TABLE IF NOT EXISTS `Extranet_ResourcesIndex` (
  `ERI_ResourceID` int(11) NOT NULL,
  `ERI_LanguageID` int(11) NOT NULL,
  `ERI_Name` varchar(255) NOT NULL,
  PRIMARY KEY  (`ERI_ResourceID`,`ERI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_ResourcesIndex`
--

INSERT INTO `Extranet_ResourcesIndex` (`ERI_ResourceID`, `ERI_LanguageID`, `ERI_Name`) VALUES
(1, 1, 'Textes'),
(1, 2, 'Texts');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Roles`
--

CREATE TABLE IF NOT EXISTS `Extranet_Roles` (
  `ER_ID` int(11) NOT NULL auto_increment,
  `ER_ControlName` varchar(255) NOT NULL,
  PRIMARY KEY  (`ER_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_Roles`
--

INSERT INTO `Extranet_Roles` (`ER_ID`, `ER_ControlName`) VALUES
(1, 'editor'),
(2, 'reviser'),
(3, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_RolesResources`
--

CREATE TABLE IF NOT EXISTS `Extranet_RolesResources` (
  `ERR_ID` int(11) NOT NULL auto_increment,
  `ERR_RoleID` int(11) NOT NULL,
  `ERR_ResourceID` int(11) NOT NULL,
  `ERR_InheritedParentID` int(11) NOT NULL,
  PRIMARY KEY  (`ERR_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_RolesResources`
--

INSERT INTO `Extranet_RolesResources` (`ERR_ID`, `ERR_RoleID`, `ERR_ResourceID`, `ERR_InheritedParentID`) VALUES
(1, 1, 1, 0),
(2, 2, 1, 1),
(3, 3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_RolesResourcesIndex`
--

CREATE TABLE IF NOT EXISTS `Extranet_RolesResourcesIndex` (
  `ERRI_RoleResourceID` int(11) NOT NULL,
  `ERRI_LanguageID` int(11) NOT NULL,
  `ERRI_Name` varchar(255) NOT NULL,
  `ERRI_Description` text NOT NULL,
  PRIMARY KEY  (`ERRI_RoleResourceID`,`ERRI_LanguageID`),
  UNIQUE KEY `ERRI_RoleResourceID` (`ERRI_RoleResourceID`,`ERRI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_RolesResourcesIndex`
--

INSERT INTO `Extranet_RolesResourcesIndex` (`ERRI_RoleResourceID`, `ERRI_LanguageID`, `ERRI_Name`, `ERRI_Description`) VALUES
(1, 1, 'Rédacteur de texte', 'Le rédacteur peut modifier tous les textes du site Web, mais ne peut pas les mettre en ligne'),
(2, 1, 'Réviseur de texte', 'Le réviseur de texte peut modifier tous les textes du site Web et aussi les mettre en ligne'),
(1, 2, 'Drafters', 'The editor can edit all texts from the website but can not put them online'),
(2, 2, 'Text editor', 'The text editor can edit all texts of this website and also put online');

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_RolesResourcesPermissions`
--

CREATE TABLE IF NOT EXISTS `Extranet_RolesResourcesPermissions` (
  `ERRP_RoleResourceID` int(11) NOT NULL,
  `ERRP_PermissionID` int(11) NOT NULL,
  PRIMARY KEY  (`ERRP_RoleResourceID`,`ERRP_PermissionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_RolesResourcesPermissions`
--

INSERT INTO `Extranet_RolesResourcesPermissions` (`ERRP_RoleResourceID`, `ERRP_PermissionID`) VALUES
(1, 1),
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_Users`
--

CREATE TABLE IF NOT EXISTS `Extranet_Users` (
  `EU_ID` int(11) NOT NULL auto_increment,
  `EU_LName` varchar(50) NOT NULL,
  `EU_FName` varchar(50) NOT NULL,
  `EU_Email` varchar(255) default NULL,
  `EU_Username` varchar(50) NOT NULL,
  `EU_Password` varchar(40) NOT NULL,
  `EU_lastAccess` datetime default NULL,
  PRIMARY KEY  (`EU_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `Extranet_Users`
--

INSERT INTO `Extranet_Users` (`EU_ID`, `EU_LName`, `EU_FName`, `EU_Email`, `EU_Username`, `EU_Password`, `EU_lastAccess`) VALUES
(11, 'Goupil', 'Styves', 'styves.goupil@ciblesolutions.com', 'styves', '318bad9b51d84fd8757e722a784f7459', NULL),
(19, 'Studio', 'Cible', 'studio@ciblesolutions.com', 'studio', '14dae4e21624989d8ae661453037935f', NULL),
(20, 'Soares', 'Sergio', 'sergio.soares@ciblesolutions.com', 'sergio', '318bad9b51d84fd8757e722a784f7459', NULL),
(22, 'Reynolds', 'Francis', 'francis.reynolds@ciblesolutions.com', 'francis', '318bad9b51d84fd8757e722a784f7459', NULL),
(24, 'Annick', 'Lavigne', 'annick.lavigne@ciblesolutions.com', 'annick', '318bad9b51d84fd8757e722a784f7459', NULL),
(23, 'Vincent', 'Sacha', 'sacha.vincent@ciblesolutions.com', 'sacha', '318bad9b51d84fd8757e722a784f7459', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Extranet_UsersGroups`
--

CREATE TABLE IF NOT EXISTS `Extranet_UsersGroups` (
  `EUG_UserID` int(11) NOT NULL,
  `EUG_GroupID` int(11) NOT NULL,
  PRIMARY KEY  (`EUG_UserID`,`EUG_GroupID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Extranet_UsersGroups`
--

INSERT INTO Extranet_UsersGroups (EUG_UserID, EUG_GroupID) VALUES
(11, 1),
(19, 1),
(20, 1),
(22, 1),
(23, 1),
(24, 1);

-- --------------------------------------------------------

--
-- Structure de la table `FilesImport`
--

DROP TABLE IF EXISTS `FilesImport`;
CREATE TABLE IF NOT EXISTS `FilesImport` (
  `FI_ID` varchar(255) NOT NULL,
  `FI_Type` varchar(255) default NULL,
  `FI_FileName` varchar(255) NOT NULL,
  `FI_LastModif` datetime NOT NULL,
  `FI_LastAccess` datetime NOT NULL,
  PRIMARY KEY  (`FI_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `GenericProfiles`
--

CREATE TABLE IF NOT EXISTS `GenericProfiles` (
  `GP_MemberID` int(11) NOT NULL auto_increment,
  `GP_Salutation` int(2) NOT NULL COMMENT 'elem:select|src:salutations',
  `GP_FirstName` varchar(255) NOT NULL default '',
  `GP_LastName` varchar(255) NOT NULL default '',
  `GP_Email` varchar(255) NOT NULL default '' COMMENT 'validate:email|unique:true',
  `GP_Password` VARCHAR(50) NULL ,
  `GP_Language` int(2) NOT NULL default '-1' COMMENT 'elem:select|src:languages',
  PRIMARY KEY  (`GP_MemberID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Languages`
--
DROP TABLE IF EXISTS `Languages`;
CREATE TABLE IF NOT EXISTS `Languages` (
  `L_ID` int(10) NOT NULL auto_increment,
  `L_Suffix` char(2) NOT NULL,
  `L_Title` varchar(255) NOT NULL,
  `L_ExtranetUI` tinyint(1) default '0',
  `L_Active` tinyint(1) default '0',
  `L_Seq` int(2) default '0',
  PRIMARY KEY  (`L_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `Languages`
--

INSERT INTO `Languages` (`L_ID`, `L_Suffix`, `L_Title`, `L_ExtranetUI`, `L_Active`, `L_Seq`) VALUES
(1, 'fr', 'Français', 1,1,1),
(2, 'en', 'English', 0,1,2),
(3, 'es', 'Espa&ntilde;ol', 0,0,3),
(4, 'it', 'Italiano', 0,0,4);

-- --------------------------------------------------------

--
-- Table structure for table `Layouts`
--

CREATE TABLE IF NOT EXISTS `Layouts` (
  `L_ID` int(11) NOT NULL auto_increment,
  `L_Name` varchar(255) NOT NULL,
  `L_Path` varchar(255) NOT NULL,
  `L_Image` varchar(255) NOT NULL,
  PRIMARY KEY  (`L_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `Layouts`
--

INSERT INTO `Layouts` (`L_ID`, `L_Name`, `L_Path`, `L_Image`) VALUES
(1, 'mainHome', 'mainHome.phtml', 'image.png'),
(2, 'mainCommon', 'mainCommon.phtml', 'image.png');

-- --------------------------------------------------------

--
-- Table structure for table `Log`
--

CREATE TABLE IF NOT EXISTS `Log` (
  `L_ID` INT(11) NOT NULL AUTO_INCREMENT ,
  `L_ModuleID` INT(11) NULL ,
  `L_Action` VARCHAR(45) NULL ,
  `L_UserID` INT(11) NULL ,
  `L_Datetime` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `L_Data` TEXT NULL ,
  PRIMARY KEY (`L_ID`) )
ENGINE = MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------

--
-- Table structure for table `MemberProfiles`
--

CREATE TABLE IF NOT EXISTS `MemberProfiles` (
  `MP_GenericProfileMemberID` int(11) NOT NULL,
  `MP_CompanyName` varchar(30) default NULL,
  `MP_AddressId` int(11) NOT NULL,
  `MP_Hash` VARCHAR(30) NULL ,
  `MP_ValidateEmail` VARCHAR(30) NULL ,
  `MP_Status` INT(1) NOT NULL ,
  PRIMARY KEY  (`MP_GenericProfileMemberID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `MenuItemData`
--

CREATE TABLE IF NOT EXISTS `MenuItemData` (
  `MID_ID` int(11) NOT NULL auto_increment,
  `MID_MenuID` int(11) NOT NULL,
  `MID_ParentID` int(11) NOT NULL default '0',
  `MID_Position` int(11) NOT NULL,
  `MID_Style` varchar(50) NULL,
  `MID_Secured` tinyint(1) default 0,
  `MID_loadImage` TINYINT( 1 ) NOT NULL DEFAULT '0',
  `MID_Image` VARCHAR( 255 ) NULL,
  `MID_ImgAndTitle` TINYINT( 1 ) NOT NULL DEFAULT '0',
  `MID_Show` INT( 1 ) NOT NULL DEFAULT  '1',
  PRIMARY KEY  (`MID_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `MenuItemIndex`
--

CREATE TABLE IF NOT EXISTS `MenuItemIndex` (
  `MII_ID` int(11) NOT NULL auto_increment,
  `MII_LanguageID` int(11) NOT NULL,
  `MII_MenuItemDataID` int(11) NOT NULL,
  `MII_Title` varchar(255) NOT NULL,
  `MII_Link` varchar(255) NOT NULL,
  `MII_PageID` int(11) NOT NULL default '-1',
  `MII_Placeholder` int(11) default '0',
  PRIMARY KEY  (`MII_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Menus`
--

CREATE TABLE IF NOT EXISTS `Menus` (
  `M_ID` int(11) NOT NULL auto_increment,
  `M_Title` varchar(50) NOT NULL,
  `M_MenuType` int(11) NOT NULL default '1',
  `M_FirstLevelNumberOfElements` int(11) default '-1',
  `M_MaxNesting` int(11) default '-1',
  `M_BgColor` VARCHAR(7) NOT NULL DEFAULT '#F0F0F0',
  `M_Section` VARCHAR(45) NOT NULL DEFAULT 'main',
  `M_Seq` INT(3) NULL,
  `M_ShowSitemap` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`M_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Menus`
--

INSERT INTO `Menus` (`M_ID`, `M_Title`, `M_MenuType`, `M_FirstLevelNumberOfElements`, `M_MaxNesting`) VALUES
(10, 'principal', 1, 5, 3),
(20, 'header', 2, -1, -1),
(30, 'permanent', 2, 2, 2),
(40, 'footer', 2, -1, -1),
(50, 'footerTwo', 2, -1, -1),
(60, 'footerThree', 2, -1, -1),
(70, 'footerFour', 2, -1, -1);


-- --------------------------------------------------------

--
-- Table structure for table `MenuTypes`
--

CREATE TABLE IF NOT EXISTS `MenuTypes` (
  `MT_ID` int(11) NOT NULL auto_increment,
  `MT_Title` varchar(50) default NULL,
  PRIMARY KEY  (`MT_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `MenuTypes`
--

INSERT INTO `MenuTypes` (`MT_ID`, `MT_Title`) VALUES
(1, 'Hierarchical'),
(2, 'Simple');

-- --------------------------------------------------------

--
-- Table structure for table `Modules_ControllersActionsPermissions`
--

CREATE TABLE IF NOT EXISTS `Modules_ControllersActionsPermissions` (
  `MCAP_ID` int(11) NOT NULL auto_increment,
  `MCAP_ModuleID` int(11) NOT NULL,
  `MCAP_ControllerTitle` varchar(255) NOT NULL,
  `MCAP_ControllerActionTitle` varchar(255) NOT NULL,
  `MCAP_PermissionTitle` varchar(255) NOT NULL,
  `MCAP_Position` int(11) default NULL,
  PRIMARY KEY  (`MCAP_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Modules_ControllersActionsPermissions`
--

INSERT INTO `Modules_ControllersActionsPermissions` (`MCAP_ID`, `MCAP_ModuleID`, `MCAP_ControllerTitle`, `MCAP_ControllerActionTitle`, `MCAP_PermissionTitle`, `MCAP_Position`) VALUES
(1, 1, 'index', 'list', 'edit', 1),
(2, 1, 'index', 'list-approbation-request', 'publish', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ModuleCategoryViewPage`
--

CREATE TABLE IF NOT EXISTS `ModuleCategoryViewPage` (
  `MCVP_ID` int(11) NOT NULL auto_increment,
  `MCVP_ModuleID` int(11) NOT NULL,
  `MCVP_CategoryID` int(11) NOT NULL default '0',
  `MCVP_ViewID` int(11) NOT NULL,
  `MCVP_PageID` int(11) NOT NULL,
  PRIMARY KEY  (`MCVP_ID`,`MCVP_ModuleID`,`MCVP_CategoryID`,`MCVP_ViewID`,`MCVP_PageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Modules`
--

CREATE TABLE IF NOT EXISTS `Modules` (
  `M_ID` int(10) NOT NULL,
  `M_Title` varchar(255) NOT NULL,
  `M_MVCModuleTitle` varchar(255) NOT NULL,
  `M_UseProfile` tinyint(1) NOT NULL default 0,
  `M_NeedAuth` tinyint(1) NOT NULL default 0,
  PRIMARY KEY  (`M_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

INSERT INTO Modules (M_ID, M_Title, M_MVCModuleTitle) VALUES
(1, 'Text', 'text'),
(5, 'Site map', 'sitemap'),
(10, 'Search', 'search');

-- --------------------------------------------------------

--
-- Table structure for table `ModuleViews`
--

CREATE TABLE IF NOT EXISTS `ModuleViews` (
  `MV_ID` int(11) NOT NULL auto_increment,
  `MV_Name` varchar(50) default NULL,
  `MV_ModuleID` int(11) default NULL,
  PRIMARY KEY  (`MV_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Dumping data for table `Modules_ControllersActionsPermissions`
--

INSERT INTO ModuleViews (MV_ID, MV_Name, MV_ModuleID) VALUES
(10001, 'index', 10);

--
-- Table structure for table `ModuleViewsIndex`
--

CREATE TABLE IF NOT EXISTS `ModuleViewsIndex` (
  `MVI_ModuleViewsID` int(11) default NULL,
  `MVI_LanguageID` int(11) default NULL,
  `MVI_ActionName` varchar(255) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Dumping data for table `Modules_ControllersActionsPermissions`
--

INSERT INTO ModuleViewsIndex (MVI_ModuleViewsID, MVI_LanguageID, MVI_ActionName) VALUES
(10001, 1, 'resultats'),
(10001, 2, 'results');

--
-- Table structure for table `NotificationManageData`
--

DROP TABLE IF EXISTS `NotificationManagerData`;
CREATE TABLE IF NOT EXISTS `NotificationManagerData` (
  `NM_ID` int(11) NOT NULL auto_increment COMMENT 'exclude:1',
  `NM_ModuleId` int(11) NOT NULL COMMENT 'elem:select|src:modules',
  `NM_Event` varchar(50) NOT NULL ,
  `NM_Type` enum('email', 'screen') DEFAULT 'email' COMMENT 'elem:enum',
  `NM_Recipient` enum('client', 'admin') DEFAULT 'client' COMMENT 'elem:enum',
  `NM_Active` tinyint(1) NOT NULL Default 1 COMMENT 'elem:checkbox',
  `NM_Message` varchar(255) NOT NULL COMMENT 'elem:select|src:salutations',
  `NM_Title` varchar(255) NOT NULL ,
  `NM_Email` varchar(255) NOT NULL default 'empty' COMMENT 'validate:email|unique:true',
  `NM_ModifDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'exclude:1',
  PRIMARY KEY (`NM_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=UTF8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- ------------------------------------------------------

--
-- Table structure for table `Pages`
--

CREATE TABLE IF NOT EXISTS `Pages` (
  `P_ID` int(10) NOT NULL auto_increment,
  `P_Position` int(10) NOT NULL,
  `P_ParentID` int(10) NOT NULL,
  `P_Home` int(1) NOT NULL,
  `P_LayoutID` int(11) default NULL,
  `P_ThemeID` int(11) NOT NULL default '1',
  `P_ViewID` int(11) NOT NULL default '1',
  `P_ShowSiteMap` int(1) default '1',
  `P_ShowMenu` int(1) default '1',
  `P_ShowTitle` tinyint(4) default '1',
  `P_BannerGroupID` int(11) NULL,
  PRIMARY KEY  (`P_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Dumping data for table `Pages`
--

INSERT INTO `Pages` (`P_ID`, `P_Position`, `P_ParentID`, `P_Home`, `P_LayoutID`, `P_ThemeID`, `P_ViewID`, `P_ShowSiteMap`, `P_ShowMenu`, `P_ShowTitle`) VALUES
(1, 1, 0, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 0, 0, 2, 1, 2, 1, 1, 1),
(3, 3, 0, 0, 2, 1, 2, 1, 1, 1),
(4, 4, 0, 0, 2, 1, 2, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `PagesIndex`
--

CREATE TABLE IF NOT EXISTS `PagesIndex` (
  `PI_PageID` int(10) NOT NULL,
  `PI_LanguageID` int(10) NOT NULL,
  `PI_PageIndex` varchar(255) NOT NULL,
  `PI_PageIndexOtherLink` varchar(255) NOT NULL,
  `PI_PageTitle` varchar(255) NOT NULL,
  `PI_TitleImageSrc` varchar(255) NULL,
  `PI_TitleImageAlt` varchar(255) NULL,
  `PI_MetaDescription` text NOT NULL,
  `PI_MetaKeywords` text NOT NULL,
  `PI_MetaTitle` text NOT NULL,
  `PI_MetaOther` text NOT NULL,
  `PI_Status` tinyint(4) NOT NULL,
  `PI_Secure` enum('non','oui') NOT NULL,
  `PI_AltPremiereImage` VARCHAR( 255 ) NOT NULL,

  PRIMARY KEY  (`PI_PageID`,`PI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Dumping data for table `Pages`
--

INSERT INTO `PagesIndex` (`PI_PageID`, `PI_LanguageID`, `PI_PageIndex`, `PI_PageIndexOtherLink`, `PI_PageTitle`, `PI_TitleImageSrc`, `PI_TitleImageAlt`, `PI_MetaTitle`, `PI_MetaDescription`, `PI_MetaKeywords`,`PI_MetaOther`, `PI_Status`, `PI_Secure`) VALUES
(1, 1, 'accueil', '', 'Accueil', '', '', '', '', '','', 1, 'non'),
(1, 2, 'home', '', 'Home', '', '', '', '', '','', 1, 'non'),
(2, 1, 'page-non-trouvee', '', 'Page non trouvée', '', '', '', 'page 404', '404','', 1, 'non'),
(2, 2, 'page-not-found', '', 'Page not found', '', '', '', 'page 404', '404','', 1, 'non'),
(3, 1, 'politique-de-confidentialite', '', 'Politique de confidentialité', '', '', '', '', '','', 1, 'non'),
(3, 2, 'privacy-policy', '', 'Privacy policy', '', '', '', '', '','', 1, 'non'),
(4, 1, 'plan-du-site', '', 'Plan du site', '', '', '', '', '','', 1, 'non'),
(4, 2, 'site-map', '', 'Sitemap', '', '', '', '', '','', 1, 'non');

-- --------------------------------------------------------

--
-- Table structure for table `Page_Themes`
--

CREATE TABLE IF NOT EXISTS `Page_Themes` (
  `PT_ID` int(11) NOT NULL auto_increment,
  `PT_Name` varchar(50) default NULL,
  `PT_Folder` varchar(255) default NULL,
  PRIMARY KEY  (`PT_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Page_Themes`
--

INSERT INTO `Page_Themes` (`PT_ID`, `PT_Name`, `PT_Folder`) VALUES
(1, 'Default', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `Parameters`
--

CREATE TABLE IF NOT EXISTS `Parameters` (
  `P_BlockID` int(11) NOT NULL,
  `P_Number` int(11) NOT NULL,
  `P_Value` text NOT NULL,
  UNIQUE KEY `P_BlockID` (`P_BlockID`,`P_Number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;


-- --------------------------------------------------------

--
-- Table structure for table `Salutations`
--

CREATE TABLE IF NOT EXISTS `Salutations` (
  `S_ID` int(11) NOT NULL auto_increment,
  `S_StaticTitle` varchar(255) NOT NULL,
  PRIMARY KEY  (`S_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `Salutations`
--

INSERT INTO `Salutations` (`S_ID`, `S_StaticTitle`) VALUES
(1, 'salutation_mr'),
(2, 'salutation_mrs');

-- --------------------------------------------------------

--
-- Table structure for table `Static_Texts`
--

CREATE TABLE IF NOT EXISTS `Static_Texts` (
  `ST_Identifier` varchar(100) NOT NULL,
  `ST_LangID` int(11) NOT NULL default '1',
  `ST_Value` text,
  `ST_Type` enum('cible','client') default 'cible',
  `ST_Desc_backend` text NOT NULL,
  `ST_Editable` tinyint(4) NOT NULL default '0',
  `ST_ModuleID` int(5) NOT NULL default '0',
  `ST_ModifDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ST_Identifier`,`ST_LangID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `Static_Texts`
--

REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('extranet', 1, 'Extranet', 'cible', '', 0, 0),
('form_title_login', 1, 'Identification', 'cible', '', 0, 0),
('OLD_login_username', 1, 'Nom d''usager', 'cible', '', 0, 0),
('form_label_password', 1, 'Mot de passe', 'cible', '', 0, 0),
('button_authenticate', 1, 'Connexion', 'cible', '', 0, 0),
('error_auth_failure', 1, 'Votre nom d''usager ou votre mot de passe sont incorrects.', 'cible', '', 0, 0),
('error_auth_failure', 2, 'Your user name and/or your password are incorrect.', 'cible', '', 0, 0),
('contact_salutation', 1, 'Salutations', 'cible', '', 0, 0),
('contact_madame', 1, 'Madame', 'cible', '', 0, 0),
('contact_monsieur', 1, 'Monsieur', 'cible', '', 0, 0),
('contact_fname', 1, 'Prénom', 'cible', '', 0, 0),
('contact_lname', 1, 'Nom', 'cible', '', 0, 0),
('contact_email', 1, 'Adresse courriel', 'cible', '', 0, 0),
('contact_address', 1, 'Adresse', 'cible', '', 0, 0),
('contact_province', 1, 'Province', 'cible', '', 0, 0),
('contact_country', 1, 'Pays', 'cible', '', 0, 0),
('contact_postalCode', 1, 'Code postal', 'cible', '', 0, 0),
('contact_phone', 1, 'Téléphone', 'cible', '', 0, 0),
('contact_fax', 1, 'Télécopieur', 'cible', '', 0, 0),
('contact_question', 1, 'Questions, suggestions, commentaires', 'cible', '', 0, 0),
('button_submit', 1, 'Soumettre', 'cible', '', 0, 0),
('button_reset', 1, 'Recommencer', 'cible', '', 0, 0),
('error_field_required', 1, 'Ce champ est obligatoire.', 'cible', '', 0, 0),
('error_field_required', 2, 'This field is required.', 'cible', '', 0, 0),
('button_refresh_captcha', 1, 'Rafraichir', 'cible', '', 0, 0),
('module_blog', 1, 'Blogue', 'cible', '', 0, 0),
('module_achievement', 1, 'Réalisations', 'cible', '', 0, 0),
('module_contact', 1, 'Contact', 'cible', '', 0, 0),
('module_sitemap', 1, 'Plan de site', 'cible', '', 0, 0),
('module_text', 1, 'Texte', 'cible', '', 0, 0),
('page_add_sub_section', 1, 'Ajouter une sous-section', 'cible', '', 0, 0),
('error_message_password_notSame', 1, 'Le mot de passe confirmé doit être identique au nouveau mot de passe.', 'cible', '', 0, 0),
('error_message_password_isEmpty', 1, 'Veuillez confirmer le nouveau mot de passe.', 'cible', '', 0, 0),
('page_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer la page <em><b>%PAGE_TITLE%</b></em>, tous ces enfants, et ce, dans toutes les langues?', 'cible', '', 0, 0),
('button_yes', 1, 'Oui', 'cible', '', 0, 0),
('button_no', 1, 'Non', 'cible', '', 0, 0),
('page_delete_message_cant_find_page', 1, 'Impossible de trouver la page à supprimer', 'cible', '', 0, 0),
('page_action_delete', 1, 'Supprimer', 'cible', '', 0, 0),
('page_action_edit', 1, 'Éditer', 'cible', '', 0, 0),
('page_action_add', 1, 'Ajouter une sous-section à cette page', 'cible', '', 0, 0),
('page_form_edit_title', 1, 'Modification de l''information de la page', 'cible', '', 0, 0),
('page_error_message_no_block_found', 1, 'Il n''y a aucun bloc dans cette page.', 'cible', '', 0, 0),
('block_title_add_block', 1, 'Ajouter un bloc %BLOCK_TYPE%', 'cible', '', 0, 0),
('block_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer le bloc <em><b>%BLOCK_TITLE%</b></em>?', 'cible', '', 0, 0),
('block_delete_message_cant_find_block', 1, 'Impossible de trouver ce bloc', 'cible', '', 0, 0),
('button_return', 1, 'Retour', 'cible', '', 0, 0),
('button_return', 2, 'Go back', 'cible', '', 0, 0),
('block_title_edit_block', 1, 'Éditer le bloc %BLOCK_TYPE%', 'cible', '', 0, 0),
('button_edit', 1, 'Éditer', 'cible', '', 0, 0),
('button_delete', 1, 'Supprimer', 'cible', '', 0, 0),
('button_move_up', 1, 'Monter', 'cible', '', 0, 0),
('button_move_down', 1, 'Descendre', 'cible', '', 0, 0),
('cible_all_right_reserved', 1, '© CIBLE solutions d''affaires ##YEAR##  -- Tous droits réservés', 'cible', '', 0, 0),
('extranet_profile_modify_link', 1, 'Profil', 'cible', '', 0, 0),
('extranet_welcome_message', 1, 'Bienvenue dans l''extranet', 'cible', '', 0, 0),
('extranet_back_main_menu', 1, 'Tableau de bord', 'cible', '', 0, 0),
('extranet_logout_link', 1, 'Quitter', 'cible', '', 0, 0),
('extranet_cache_clear_client_texts', 1, 'Effacer la cache des textes clients', 'cible', '', 0, 0),
('extranet_cache_clear_cible_texts', 1, 'Effacer la cache des textes de base', 'cible', '', 0, 0),
('administrator_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer l''administrateur <em><b>%ADMINISTRATOR_NAME%</b></em>?', 'cible', '', 0, 0),
('page_error_message_no_block_found_in_zone', 1, 'Aucun bloc présentement', 'cible', '', 0, 0),
('administrator_group_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer le groupe d''administrateurs <b>%GROUP_NAME% </b>?', 'cible', '', 0, 0),
('status_online', 1, 'En ligne', 'cible', '', 0, 0),
('status_online', 2, 'Online', 'cible', '', 0, 0),
('status_offline', 1, 'Hors ligne', 'cible', '', 0, 0),
('status_offline', 2, 'Offline', 'cible', '', 0, 0),
('module_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer l''élément %ELEMENT_TITLE%?', 'cible', '', 0, 0),
('module_element_nout_found', 1, 'L''élément que vous tentez de supprimer est inexistant.', 'cible', '', 0, 0),
('validation_message_invalid_date', 1, 'Veuillez entrer une date valide.', 'cible', '', 0, 0),
('validation_message_invalid_text', 1, 'Veuillez saisir un texte.', 'cible', '', 0, 0),
('button_save', 1, 'Sauvegarder', 'cible', '', 0, 0),
('button_save', 2, 'Save', 'cible', '', 0, 0),
('button_save_close', 1, 'Sauvegarder et Quitter', 'cible', '', 0, 0),
('button_save_close', 2, 'Save and Close', 'cible', '', 0, 0),
('button_save_publish', 1, 'Sauvegarder et mettre en ligne', 'cible', '', 0, 0),
('button_save_return_writing', 1, 'Sauvegarder et retourner en rédaction', 'cible', '', 0, 0),
('button_save_return_writing', 2, 'Save and return to writing', 'cible', '', 0, 0),
('button_save_submit_auditor', 1, 'Sauvegarder et soumettre au réviseur', 'cible', '', 0, 0),
('button_save_submit_auditor', 2, 'Save and submit to the auditor', 'cible', '', 0, 0),
('button_save_publish', 2, 'Save and publish', 'cible', '', 0, 0),
('button_cancel', 1, 'Annuler', 'cible', '', 0, 0),
('button_cancel', 2, 'Cancel', 'cible', '', 0, 0),
('validation_message_empty_daterange', 1, 'Veuillez entrer au moins une plage de dates valide.', 'cible', '', 0, 0),
('validation_message_invalid_daterange', 1, 'Veuillez vérifier que vos plages sont valides.', 'cible', '', 0, 0),
('validation_message_invalid_date_format', 1, 'Veuillez vérifier le format des dates (AAAA-MM-JJ).', 'cible', '', 0, 0),
('validation_message_endDate_earlier', 1, 'Veuillez vérifier que vos dates de fin sont supérieures à vos date de début.', 'cible', '', 0, 0),
('validation_message_empty_field', 1, 'Ce champ doit être rempli.', 'cible', '', 0, 0),
('validation_message_empty_field', 2, 'This field should not be empty', 'cible', '', 0, 0),
('form_label_title', 1, 'Titre', 'cible', '', 0, 0),
('form_label_text', 1, 'Texte', 'cible', '', 0, 0),
('form_label_title', 2, 'Title', 'cible', '', 0, 0),
('form_label_text', 2, 'Text', 'cible', '', 0, 0),
('extranet_welcome_message', 2, 'Welcome to the extranet', 'cible', '', 0, 0),
('extranet_profile_modify_link', 2, 'Edit your profile', 'cible', '', 0, 0),
('extranet_back_main_menu', 2, 'Dashboard', 'cible', '', 0, 0),
('extranet_logout_link', 2, 'Exit', 'cible', '', 0, 0),
('form_label_fname', 1, 'Prénom', 'cible', '', 0, 0),
('form_label_fname', 2, 'First name', 'cible', '', 0, 0),
('form_label_lname', 1, 'Nom', 'cible', '', 0, 0),
('form_label_lname', 2, 'Last name', 'cible', '', 0, 0),
('form_label_email', 1, 'Courriel', 'cible', '', 0, 0),
('form_label_email', 2, 'Email', 'cible', '', 0, 0),
('form_label_username', 1, 'Nom d''utilisateur', 'cible', '', 0, 0),
('form_label_username', 2, 'Username', 'cible', '', 0, 0),
('form_label_newPwd', 1, 'Nouveau mot de passe', 'cible', '', 0, 0),
('form_label_newPwd', 2, 'New password', 'cible', '', 0, 0),
('form_label_confirmNewPwd', 1, 'Confirmer le nouveau mot de passe', 'cible', '', 0, 0),
('form_label_confirmNewPwd', 2, 'Confirm new password:', 'cible', '', 0, 0),
('validation_message_emailAddressInvalid', 1, '''%value%'' n''est pas une adresse courriel valide.', 'cible', '', 0, 0),
('validation_message_emailAddressInvalid', 2, '''%value%'' is not a valid email address', 'cible', '', 0, 0),
('error_message_password_isEmpty', 2, 'Please confirm the new password.', 'cible', '', 0, 0),
('error_message_password_notSame', 2, 'The confirmation of the password must be identical to the new password.', 'cible', '', 0, 0),
('form_label_name', 1, 'Nom', 'cible', '', 0, 0),
('form_label_name', 2, 'Name', 'cible', '', 0, 0),
('form_label_description', 1, 'Description', 'cible', '', 0, 0),
('form_label_description', 2, 'Description', 'cible', '', 0, 0),
('form_label_status', 1, 'Statut', 'cible', '', 0, 0),
('form_label_status', 2, 'Status', 'cible', '', 0, 0),
('form_label_noRight', 1, 'Aucun droit', 'cible', '', 0, 0),
('form_label_noRight', 2, 'No right', 'cible', '', 0, 0),
('error_message_permission', 1, 'Vous n''avez pas les droits nécessaire pour effectuer cette action.', 'cible', '', 0, 0),
('error_message_permission', 2, 'You do not have necessary permissions to perform this action.', 'cible', '', 0, 0),
('cible_all_right_reserved', 2, '© ##YEAR## Cible Solutions d''Affaires -- All Rights Reserved', 'cible', '', 0, 0),
('form_label_menu_link', 1, 'Lien *', 'cible', '', 0, 0),
('form_label_description_image', 1, 'Description de l''image', 'cible', '', 0, 0),
('form_label_description_image', 2, 'Image description', 'cible', '', 0, 0),
('form_label_short_text', 1, 'Texte bref', 'cible', '', 0, 0),
('form_label_short_text', 2, 'Short text', 'cible', '', 0, 0),
('form_label_company', 1, 'Nom de l''entreprise', 'cible', '', 0, 0),
('form_label_company', 2, 'Company name', 'cible', '', 0, 0),
('form_label_company_id', 1, 'Identifiant de l''entreprise', 'cible', '', 0, 0),
('form_label_company_id', 2, 'Company id', 'cible', '', 0, 0),
('form_label_city', 1, 'Ville', 'cible', '', 0, 0),
('form_label_city', 2, 'City', 'cible', '', 0, 0),
('form_label_address_more', 1, 'Complément d''adresse', 'cible', '', 0, 0),
('form_label_address_more', 2, 'Additional address', 'cible', '', 0, 0),
('form_label_state', 1, 'Province / Etat', 'cible', '', 0, 0),
('form_label_state', 2, 'Province / State', 'cible', '', 0, 0),
('profile_title_Members', 1, 'Client', 'cible', '', 0, 0),
('profile_title_Members', 2, 'Client', 'cible', '', 0, 0),
('form_label_zip_code', 1, 'Code postal', 'cible', '', 0, 0),
('form_label_zip_code', 2, 'Zip code', 'cible', '', 0, 0),
('form_label_country', 1, 'Pays', 'cible', '', 0, 0),
('form_label_country', 2, 'Country', 'cible', '', 0, 0),
('form_label_account_number', 1, 'Numéro de compte', 'cible', '', 0, 0),
('form_label_account_number', 2, 'Account number', 'cible', '', 0, 0),
('module_delete_message_confirmation ', 2, 'Are you sure you want to delete the item %ELEMENT_TITLE%?', 'cible', '', 0, 0),
('button_yes', 2, 'Yes', 'cible', '', 0, 0),
('button_no', 2, 'No', 'cible', '', 0, 0),
('form_title_login', 2, 'Identification', 'cible', '', 0, 0),
('form_label_password', 2, 'Password', 'cible', '', 0, 0),
('button_authenticate', 2, 'Connection', 'cible', '', 0, 0),
('block_title_add_block', 2, 'Add block %BLOCK_TYPE%', 'cible', '', 0, 0),
('block_form_label_showHeader', 1, 'Afficher l''en-tête et le titre du bloc sur le site Web.', 'cible', '', 0, 0),
('block_form_label_showHeader', 2, 'Display the header and title block in the website', 'cible', '', 0, 0),
('button_add', 1, 'Ajouter', 'cible', '', 0, 0),
('button_add', 2, 'Add', 'cible', '', 0, 0),
('form_label_zone', 1, 'Zone', 'cible', '', 0, 0),
('form_label_zone', 2, 'Zone', 'cible', '', 0, 0),
('form_label_position', 1, 'Position - Ordre d''affichage', 'cible', '', 0, 0),
('form_label_position', 2, 'Position', 'cible', '', 0, 0),
('form_label_language', 1, 'Langue', 'cible', '', 0, 0),
('module_text', 2, 'Text', 'cible', '', 0, 0),
('form_label_model', 1, 'Modèle', 'cible', '', 0, 0),
('form_label_model', 2, 'Model', 'cible', '', 0, 0),
('form_legend_settings', 1, 'Paramètres', 'cible', '', 0, 0),
('form_legend_settings', 2, 'Settings', 'cible', '', 0, 0),
('form_label_category', 1, 'Catégorie', 'cible', '', 0, 0),
('form_label_category', 2, 'Category', 'cible', '', 0, 0),
('form_label_showOnline', 1, 'Afficher sur le Web', 'cible', '', 0, 0),
('form_label_showOnline', 2, 'Show on the Web', 'cible', '', 0, 0),
('form_label_language', 2, 'Language', 'cible', '', 0, 0),
('error_page_selection_required', 1, 'Vous devez sélectionner une page de l''arborescence ci-dessus.', 'cible', '', 0, 0),
('error_invalid_url', 1, 'L''URL ''%value%'' est invalide.', 'cible', '', 0, 0),
('form_label_page_picker', 1, 'Choisir une page de l''arborescence *', 'cible', '', 0, 0),
('page_delete_item_message_confirmation', 1, 'Voulez-vous vraiment supprimer l''élément %ITEM_NAME%?', 'cible', '', 0, 0),
('page_delete_item_and_children_message_confirmation', 1, 'Voulez-vous vraiment supprimer l''élément %ITEM_NAME% ainsi que ses sous-éléments?', 'cible', '', 0, 0),
('validation_message_used_email', 1, 'Cette adresse courriel est déjà utilisée par une autre personne.', 'cible', '', 0, 0),
('validation_message_used_email', 2, 'This email is already used by another person', 'cible', '', 0, 0),
('newsletterMember_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer %MEMBER_NAME% de cette liste d''envoi?', 'cible', '', 0, 0),
('newsletterMember_delete_message_confirmation', 2, 'Are you sure you want to permanently delete %Member_Name% of the mailing list for this newsletter?', 'cible', '', 0, 0),
('dashboard_page_title', 1, 'Tableau de bord', 'cible', '', 0, 0),
('dashboard_page_title', 2, 'Dashboard', 'cible', '', 0, 0),
('dashboard_page_description', 1, '<b>Bienvenue sur votre tableau de bord</b><br><br>Toutes les fonctionnalités du système de gestion<br>de contenu sont accessibles par cette page.<br><br>Vous pouvez accéder en tout temps au tableau de<br>bord en cliquant sur l''onglet en en-tête.', 'cible', '', 0, 0),
('dashboard_data_management', 1, 'Gestion des données par module', 'cible', '', 0, 0),
('dashboard_reminder_title', 1, 'Activités à valider sur le site', 'cible', '', 0, 0),
('dashboard_administration_title', 1, 'Administration', 'cible', '', 0, 0),
('dashboard_administration_website_title', 1, 'Site Internet', 'cible', '', 0, 0),
('dashboard_administration_website_sitemap_management', 1, 'Arborescence', 'cible', '', 0, 0),
('dashboard_administration_website_page_structure', 1, 'Gestion de la structure des pages', 'cible', '', 0, 0),
('dashboard_administration_administrators_title', 1, 'Administrateurs', 'cible', '', 0, 0),
('dashboard_administration_administrators_groups_management', 1, 'Groupes et permissions', 'cible', '', 0, 0),
('dashboard_administration_administrators_administrator_management', 1, 'Administrateurs', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_title', 1, 'Sections sécurisées', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_management', 1, 'Gestion des sections sécurisées', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_groups_management', 1, 'Gestion des groupes', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_users_management', 1, 'Gestion des utilisateurs', 'cible', '', 0, 0),
('dashboard_administration_contents_indexing_title', 1, 'Indexation des contenus', 'cible', '', 0, 0),
('text_module_name', 1, 'Textes', 'cible', '', 0, 0),
('contact_module_name', 1, 'Contact', 'cible', '', 0, 0),
('achievement_module_name', 1, 'Réalisations', 'cible', '', 0, 0),
('sitemap_module_name', 1, 'Plan du site', 'cible', '', 0, 0),
('blog_module_name', 1, 'Blogue', 'cible', '', 0, 0),
('dashboard_page_description', 2, 'Welcome on your instrument panel. Visualize and organize the actions to be carried out systematically', 'cible', '', 0, 0),
('dashboard_data_management', 2, 'Data management', 'cible', '', 0, 0),
('dashboard_reminder_title', 2, 'Activities to be validated on the site', 'cible', '', 0, 0),
('dashboard_administration_title', 2, 'Administration', 'cible', '', 0, 0),
('dashboard_administration_website_title', 2, 'Website', 'cible', '', 0, 0),
('dashboard_administration_website_sitemap_management', 2, 'Sitemap management', 'cible', '', 0, 0),
('dashboard_administration_website_page_structure', 2, 'Website page structure', 'cible', '', 0, 0),
('dashboard_administration_administrators_title', 2, 'Administrators', 'cible', '', 0, 0),
('dashboard_administration_administrators_groups_management', 2, 'Administrators groups management', 'cible', '', 0, 0),
('dashboard_administration_administrators_administrator_management', 2, 'Administrators management', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_title', 2, 'Protected sections', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_management', 2, 'Protected sections management', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_groups_management', 2, 'Groups management', 'cible', '', 0, 0),
('dashboard_administration_protected_sections_users_management', 2, 'Users management', 'cible', '', 0, 0),
('dashboard_administration_contents_indexing_title', 2, 'Contents indexing', 'cible', '', 0, 0),
('text_module_name', 2, 'Text', 'cible', '', 0, 0),
('contact_module_name', 2, 'Contact', 'cible', '', 0, 0),
('achievement_module_name', 2, 'Achievements', 'cible', '', 0, 0),
('sitemap_module_name', 2, 'Sitemap', 'cible', '', 0, 0),
('blog_module_name', 2, 'Blog', 'cible', '', 0, 0),
('treeview_contents_management_title', 1, 'Liste des contenus', 'cible', '', 0, 0),
('treeview_contents_management_title', 2, 'Contents management', 'cible', '', 0, 0),
('treeview_data_management_title', 1, 'Gestion des données par module', 'cible', '', 0, 0),
('treeview_data_management_title', 2, 'Data management', 'cible', '', 0, 0),
('outil_gestion_title', 1, 'SYSTÈME DE GESTION DE CONTENU', 'cible', '', 0, 0),
('outil_gestion_title', 2, 'MANAGEMENT TOOLS', 'cible', '', 0, 0),
('manage_block_settings', 1, 'Paramètres', 'cible', '', 0, 0),
('manage_block_settings', 2, 'Settings', 'cible', '', 0, 0),
('manage_block_publish', 1, 'Publier', 'cible', '', 0, 0),
('manage_block_publish', 2, 'Publish', 'cible', '', 0, 0),
('manage_block_unpublish', 1, 'Mettre hors ligne', 'cible', '', 0, 0),
('manage_block_unpublish', 2, 'Unpublish', 'cible', '', 0, 0),
('manage_block_delete', 1, 'Supprimer', 'cible', '', 0, 0),
('manage_block_delete', 2, 'Delete', 'cible', '', 0, 0),
('page_website_sitemap_title', 1, 'Gestion de l''arborescence', 'cible', '', 0, 0),
('page_website_sitemap_description', 1, '<b>Faites glisser l''icône</b> <img src="/extranet/icons/file.png" align=middle> pour déplacer une page.<br><br>Vous pouvez <b>ajouter une page à la racine</b>.<br><br>Vous pouvez <b>modifier</b> ou <b>gérer la structure</b><br>en cliquant sur le nom de la page.', 'cible', '', 0, 0),
('form_parameters_fieldset', 1, 'Paramètres', 'cible', '', 0, 0),
('form_parameters_fieldset', 2, 'Parameters', 'cible', '', 0, 0),
('block_title_add_block_description', 1, 'Vous pouvez ajouter un bloc à cette page.', 'cible', '', 0, 0),
('block_title_add_block_description', 2, 'This page allows you to add a block to this page.', 'cible', '', 0, 0),
('form_label_menu_link', 2, 'Link *', 'cible', '', 0, 0),
('dashboard_administration_website_menu_structure', 1, 'Menus', 'cible', '', 0, 0),
('menu_treeview_add', 1, 'Ajouter', 'cible', '', 0, 0),
('menu_treeview_add', 2, 'Add', 'cible', '', 0, 0),
('menu_treeview_edit', 1, 'Modifier', 'cible', '', 0, 0),
('menu_treeview_edit', 2, 'Edit', 'cible', '', 0, 0),
('menu_treeview_delete', 1, 'Supprimer', 'cible', '', 0, 0),
('menu_treeview_delete', 2, 'Delete', 'cible', '', 0, 0),
('menu_treeview_homepage', 1, 'Définir comme accueil', 'cible', '', 0, 0),
('menu_treeview_homepage', 2, 'Set as home page', 'cible', '', 0, 0),
('menu_treeview_pagestructure', 1, 'Structure de la page', 'cible', '', 0, 0),
('menu_treeview_pagestructure', 2, 'Page structure', 'cible', '', 0, 0),
('menu_treeview_drag_item', 1, 'Déplacer cette page', 'cible', '', 0, 0),
('menu_treeview_drag_item', 2, 'Drag and drop this page', 'cible', '', 0, 0),
('menu_generate_from_structure', 1, 'Générer le menu à partir de l''arborescence', 'cible', '', 0, 0),
('menu_add_root_item', 1, 'Ajouter un élément à la racine', 'cible', '', 0, 0),
('menu_submenu_action_generate', 1, 'Générer automatiquement les sous-menus', 'cible', '', 0, 0),
('menu_submenu_action_add', 1, 'Ajouter un sous-menu', 'cible', '', 0, 0),
('menu_submenu_action_edit', 1, 'Modifier', 'cible', '', 0, 0),
('menu_submenu_action_delete', 1, 'Supprimer', 'cible', '', 0, 0),
('form_label_menu_title', 1, 'Libellé du menu', 'cible', '', 0, 0),
('form_label_menu_type', 1, 'Type de menu', 'cible', '', 0, 0),
('form_label_menu_load_image', 1, 'Utiliser une image (png transparent)', 'cible', '', 0, 0),
('form_label_menu_load_image', 2, 'Use an image', 'cible', '', 0, 0),
('form_label_menu_display_image_and_title', 1, 'Afficher le titre et l''image', 'cible', '', 0, 0),
('form_label_menu_display_image_and_title', 2, 'Display the title and the image', 'cible', '', 0, 0),
('dashboard_administration_administrators_administrator_management_description', 1, 'Cette page vous permet de gérer les administrateurs.', 'cible', '', 0, 0),
('dashboard_administration_administrators_administrator_management_description', 2, 'This page is to manage the administrators.', 'cible', '', 0, 0),
('dashboard_administration_administrators_groups_management_description', 1, 'Cette page vous permet de gérer les groupes d''administrateurs. ', 'cible', '', 0, 0),
('dashboard_administration_administrators_groups_management_description', 2, 'This page is to manage the administrators groups.', 'cible', '', 0, 0),
('dashboard_administration_website_menu_structure', 2, 'Menus management', 'cible', '', 0, 0),
('extranet_profile_modify_link_description', 1, '<b>Entrez les renseignements requis</b> pour votre profil.<br><br>Vous pouvez redéfinir votre mot de passe sur cette page.', 'cible', '', 0, 0),
('extranet_profile_modify_link_description', 2, 'This page is to modify your profile.', 'cible', '', 0, 0),
('button_search_label', 1, 'Rechercher', 'cible', '', 0, 0),
('search_searched_keyword', 1, 'Résultat(s) de recherche pour « <strong>%KEYWORD%</strong> »', 'cible', '', 0, 0),
('search_result_items_found', 1, 'Résultat(s) de recherche : <br />%ITEM_COUNT% élément(s) trouvé(s)', 'cible', '', 0, 0),
('search_empty_recordset', 1, 'Aucun résultat pour cette recherche. Essayez de nouveaux mots-clés ou cliquez sur <b>Voir la liste complète</b>.', 'cible', '', 0, 0),
('search_empty_recordset', 2, 'No result for this search. Try with new keywords or click on <b>See the complete list</b>.', 'cible', '', 0, 0),
('search_box_default_value', 1, 'Rechercher', 'client', '', 1, 0),
('search_box_default_value', 2, 'Search', 'client', '', 1, 0),
('button_add_administrators', 1, 'Ajouter un administrateur', 'cible', '', 0, 0),
('button_add_administrators', 2, 'Add an administrator', 'cible', '', 0, 0),
('button_add_administrators_group', 1, 'Ajouter un groupe d''administrateurs', 'cible', '', 0, 0),
('button_add_administrators_group', 2, 'Add an administrator group', 'cible', '', 0, 0),
('button_add_root_page', 1, 'Ajouter une page à la racine', 'cible', '', 0, 0),
('button_add_root_page', 2, 'Add a page at root', 'cible', '', 0, 0),
('page_add_page_title', 1, 'Ajout d''une page', 'cible', '', 0, 0),
('page_add_page_title', 2, 'Add a page', 'cible', '', 0, 0),
('page_add_page_description', 1, 'Cette section vous permet d''ajouter une nouvelle page à votre site Web.', 'cible', '', 0, 0),
('page_add_page_description', 2, 'This section is to add a new page to your Web site.', 'cible', '', 0, 0),
('page_edit_page_title', 1, 'Édition d''une page', 'cible', '', 0, 0),
('page_edit_page_title', 2, 'Edit a page', 'cible', '', 0, 0),
('page_edit_page_description', 1, 'Cette section vous permet d''éditer une page de votre site Web.', 'cible', '', 0, 0),
('page_edit_page_description', 2, 'This section is to edit a page of your Web site.', 'cible', '', 0, 0),
('page_add_page_root_breadcrumb', 1, 'Racine du site Web', 'cible', '', 0, 0),
('page_add_page_root_breadcrumb', 2, 'Root of the Web site', 'cible', '', 0, 0),
('list_column_PI_PageTitle', 1, 'Titre de la page', 'cible', '', 0, 0),
('list_column_PI_Status', 1, 'Statut', 'cible', '', 0, 0),
('list_column_action_panel', 1, 'Actions', 'cible', '', 0, 0),
('page_structure_management_title', 1, 'Gestion de la structure', 'cible', '', 0, 0),
('page_structure_management_title', 2, 'Page structure management', 'cible', '', 0, 0),
('page_structure_management_description', 1, '<b>Faites glisser l''icône</b> du bloc dans la zone de votre choix.<br><br>Vous pouvez <b>publier</b> sur le site, éditer les <b>paramètres</b><br>ou <b>supprimer</b> chacun des blocs.<br><br>Vous pouvez éditer le contenu des blocs en cliquant<br> sur le bouton <b>Gérer les contenus de cette page</b>.', 'cible', '', 0, 0),
('page_structure_management_description', 2, 'On this page, you can manage the structure of the pages by adding the desired blocks to it.', 'cible', '', 0, 0),
('block_edit_parameters_title', 1, 'Paramètres', 'cible', '', 0, 0),
('block_edit_parameters_title', 2, 'Block settings', 'cible', '', 0, 0),
('block_edit_parameters_description', 1, 'Cette page vous permet de gérer les paramètres du bloc.', 'cible', '', 0, 0),
('block_edit_parameters_description', 2, 'This page is to manage the settings of your block.', 'cible', '', 0, 0),
('breadcrumb_default_text', 1, 'Les pages actuelles de votre site', 'cible', '', 0, 0),
('breadcrumb_default_text', 2, 'Current pages of your Web site', 'cible', '', 0, 0),
('page_content_management_title', 1, 'Gestion de contenu', 'cible', '', 0, 0),
('page_content_management_title', 2, 'Management of the contents of the page', 'cible', '', 0, 0),
('page_content_management_description', 1, 'Cliquez sur <b>les boutons d''action</b> pour éditer les contenus<br>de chacun des blocs de la page %PAGE_NAME%.<br><br>Vous pouvez ajouter ou supprimer des blocs<br>en cliquant sur le bouton <b>Gérer la structure<br>de cette page</b>.', 'cible', '', 0, 0),
('page_content_management_description', 2, 'On this page, you can manage the contents of the blocks for the page %PAGE_NAME%.', 'cible', '', 0, 0),
('header_list_administrator_groups_text', 1, 'Gestion des groupes<br>et des permissions', 'cible', '', 0, 0),
('header_list_administrator_groups_text', 2, 'Administrators groups list', 'cible', '', 0, 0),
('header_list_administrator_groups_description', 1, '<b>Cliquez sur le bouton</b> <img src="/extranet/icons/list_actions_icon.png" align=middle> pour éditer un groupe.<br><br>Vous pouvez ajouter un groupe et en exporter la liste<br>vers un fichier Excel.<br><br>Vous pouvez <b>rechercher par mots-clés</b> parmi la liste<br>des groupes. Pour revenir à la liste complète,<br>cliquez sur <b>Voir la liste complète</b>.', 'cible', '', 0, 0),
('header_list_administrator_groups_description', 2, 'This page is to consult the administrators groups list.', 'cible', '', 0, 0),
('header_list_administrator_text', 1, 'Liste des administrateurs', 'cible', '', 0, 0),
('header_list_administrator_text', 2, 'Administrators list', 'cible', '', 0, 0),
('header_list_administrator_description', 1, 'Cette page vous permet de consulter la liste des administrateurs.', 'cible', '', 0, 0),
('header_list_administrator_description', 2, 'This page is to consult the administrators list.', 'cible', '', 0, 0),
('header_add_administrator_text', 1, 'Ajout d''un administrateur', 'cible', '', 0, 0),
('header_add_administrator_text', 2, 'Add an administrator', 'cible', '', 0, 0),
('header_add_administrator_description', 1, 'Cette page vous permet d''ajouter un administrateur.', 'cible', '', 0, 0),
('header_add_administrator_description', 2, 'This page is to add an administrator.', 'cible', '', 0, 0),
('header_add_administrator_group_text', 1, 'Ajout d''un groupe d''administrateurs', 'cible', '', 0, 0),
('header_add_administrator_group_text', 2, 'Add an administrator group', 'cible', '', 0, 0),
('header_add_administrator_group_description', 1, 'Cette page vous permet d''ajouter un groupe d''administrateurs.', 'cible', '', 0, 0),
('header_add_administrator_group_description', 2, 'This page is to add an administrator group.', 'cible', '', 0, 0),
('header_edit_administrator_text', 1, 'Édition du profil d''un administrateur', 'cible', '', 0, 0),
('header_edit_administrator_text', 2, 'Administrator edit', 'cible', '', 0, 0);
REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('header_edit_administrator_description', 1, '<b>Entrez les renseignements requis</b><br>pour le profil de cet administrateur.<br><br>Vous pouvez redéfinir le mot de passe sur cette page.', 'cible', '', 0, 0),
('header_edit_administrator_description', 2, 'This page is to edit the information of an administrator.', 'cible', '', 0, 0),
('header_edit_administrator_group_text', 1, 'Édition d''un groupe', 'cible', '', 0, 0),
('header_edit_administrator_group_text', 2, 'Administratiors group edit', 'cible', '', 0, 0),
('header_edit_administrator_group_description', 1, '<b>Entrez les renseignements requis</b> pour éditer le profil<br>de ce groupe d''administrateurs.', 'cible', '', 0, 0),
('header_edit_administrator_group_description', 2, 'This page is to edit an administrators group.', 'cible', '', 0, 0),
('header_associate_administrator_text', 1, 'Droits d''administration', 'cible', '', 0, 0),
('header_associate_administrator_text', 2, 'Association des droits', 'cible', '', 0, 0),
('header_associate_administrator_description', 1, 'Cette page vous permet d''éditer les droits d''un administrateur.', 'cible', '', 0, 0),
('header_associate_administrator_description', 2, 'Cette page vous permert d''éditer les droits d''un administrateur.', 'cible', '', 0, 0),
('header_associate_administrator_group_text', 1, 'Droits d''administration', 'cible', '', 0, 0),
('header_associate_administrator_group_text', 2, 'Association des droits', 'cible', '', 0, 0),
('header_associate_administrator_group_description', 1, 'Cette page vous permet d''éditer les droits d''un groupe d''administrateurs.', 'cible', '', 0, 0),
('header_associate_administrator_group_description', 2, 'This page is to edit the administrators permissions.', 'cible', '', 0, 0),
('header_menu_structure_management_text', 1, 'Gestion des menus', 'cible', '', 0, 0),
('header_menu_structure_management_text', 2, 'Menus management', 'cible', '', 0, 0),
('header_menu_structure_management_description', 1, 'Cette page vous permet de gérer la structure des menus de votre site Web.', 'cible', '', 0, 0),
('header_menu_structure_management_description', 2, 'This page is to manage the menus structure of your Web site.', 'cible', '', 0, 0),
('search_list_all_items', 1, 'Voir la liste complète', 'cible', '', 0, 0),
('dashboard_administration_profil_list', 1, 'Inscription', 'cible', '', 0, 0),
('dashboard_administration_profil_list', 2, 'Subscriptions', 'cible', '', 0, 0),
('dashboard_administration_profil_list_description', 1, 'Banque d''usagers complète', 'cible', '', 0, 0),
('menu_submenu_action_add', 2, 'Add a sub-menu', 'cible', '', 0, 0),
('menu_submenu_action_delete', 2, 'Delete', 'cible', '', 0, 0),
('menu_submenu_action_edit', 2, 'Edit', 'cible', '', 0, 0),
('menu_submenu_action_generate', 2, 'Generate automatically the sub-menus', 'cible', '', 0, 0),
('list_column_lastName', 1, 'Nom de famille', 'cible', '', 0, 0),
('list_column_lastName', 2, 'Last Name', 'cible', '', 0, 0),
('list_column_firstName', 1, 'Prénom', 'cible', '', 0, 0),
('list_column_firstName', 2, 'First Name', 'cible', '', 0, 0),
('list_column_email', 1, 'Adresse courriel', 'cible', '', 0, 0),
('list_column_email', 2, 'Email', 'cible', '', 0, 0),
('list_column_action_panel', 2, 'Actions', 'cible', '', 0, 0),
('ajax_please_wait', 1, 'Veuillez patienter...', 'cible', '', 0, 0),
('ajax_please_wait', 2, 'Please wait...', 'cible', '', 0, 0),
('page_website_sitemap_description', 2, 'Sitemap management help goes here.', 'cible', '', 0, 0),
('page_website_sitemap_title', 2, 'Sitemap Management', 'cible', '', 0, 0),
('menu_generate_from_structure', 2, 'Generate menu from structure', 'cible', '', 0, 0),
('menu_add_root_item', 2, 'Add a menu item to the root', 'cible', '', 0, 0),
('export_to_excel', 1, 'Exporter vers Excel', 'cible', '', 0, 0),
('export_to_excel', 2, 'Export to Excel', 'cible', '', 0, 0),
('button_associate', 1, 'Association des droits', 'cible', '', 0, 0),
('button_associate', 2, 'Associate', 'cible', '', 0, 0),
('form_label_isMember', 1, 'Membre du Groupement des Chefs', 'cible', '', 0, 0),
('form_label_isMember', 2, 'Member of the "Groupement des Chefs"', 'cible', '', 0, 0),
('list_column_EGI_Name', 1, 'Nom', 'cible', '', 0, 0),
('list_column_EGI_Description', 1, 'Description', 'cible', '', 0, 0),
('list_column_EGI_Name', 2, 'Name', 'cible', '', 0, 0),
('list_column_EGI_Description', 2, 'Description', 'cible', '', 0, 0),
('list_column_EU_FName', 1, 'Prénom', 'cible', '', 0, 0),
('list_column_EU_LName', 1, 'Nom', 'cible', '', 0, 0),
('list_column_EU_Email', 1, 'Adresse courriel', 'cible', '', 0, 0),
('list_column_EU_FName', 2, 'First Name', 'cible', '', 0, 0),
('list_column_EU_LName', 2, 'Last Name', 'cible', '', 0, 0),
('list_column_EU_Email', 2, 'Email', 'cible', '', 0, 0),
('button_search_label', 2, 'Search', 'cible', '', 0, 0),
('form_paginator_previous', 1, 'Précédent', 'cible', '', 0, 0),
('form_paginator_previous', 2, 'Previous', 'cible', '', 0, 0),
('form_paginator_next', 1, 'Suivant', 'cible', '', 0, 0),
('form_paginator_next', 2, 'Next', 'cible', '', 0, 0),
('form_list_items_per_page_start', 1, 'Afficher', 'cible', '', 0, 0),
('form_list_items_per_page_start', 2, 'Show', 'cible', '', 0, 0),
('form_list_items_per_page_end', 1, 'Éléments par page', 'cible', '', 0, 0),
('form_list_items_per_page_end', 2, 'items per page', 'cible', '', 0, 0),
('form_paginator_searchbox_label', 1, 'Rechercher', 'cible', '', 0, 0),
('form_paginator_searchbox_label', 2, 'Search', 'cible', '', 0, 0),
('categories_page_title', 1, 'Gestion des catégories pour le module «%MODULE_NAME%»', 'cible', '', 0, 0),
('categories_page_description', 1, '<b>Remplissez les champs requis</b><br>pour éditer une catégorie.', 'cible', '', 0, 0),
('categories_page_title', 2, 'Categorie Management for %MODULE_NAME%', 'cible', '', 0, 0),
('categories_page_description', 2, 'Help on categorie management', 'cible', '', 0, 0),
('form_category_title_label', 1, 'Nom de la catégorie', 'cible', '', 0, 0),
('form_select_no_parent', 1, 'Aucun parent', 'cible', '', 0, 0),
('form_select_no_parent', 2, 'No parent', 'cible', '', 0, 0),
('form_category_parent_label', 1, 'Parent de cette catégorie', 'cible', '', 0, 0),
('profile_delete_message_confirmation', 1, 'Voulez-vous supprimer définitivement la fiche de cette personne?', 'cible', '', 0, 0),
('profile_delete_message_confirmation', 2, 'Are you sure you want to permanently remove that person from the system?', 'cible', '', 0, 0),
('form_category_view_all_label', 1, 'Description', 'cible', '', 0, 0),
('form_category_title_label', 2, 'Category Name:', 'cible', '', 0, 0),
('form_category_parent_label', 2, 'Parent Category:', 'cible', '', 0, 0),
('form_category_view_all_label', 2, 'Description:', 'cible', '', 0, 0),
('form_select_option_view_search_index', 1, 'Résultat de recherche', 'cible', '', 0, 0),
('form_select_option_view_search_index', 2, 'Search results', 'cible', '', 0, 0),
('list_column_NI_Title', 1, 'Titre', 'cible', '', 0, 0),
('list_column_NI_Title', 2, 'Title', 'cible', '', 0, 0),
('list_column_ND_ReleaseDate', 1, 'Date de parution', 'cible', '', 0, 0),
('list_column_ND_Status', 1, 'Statut', 'cible', '', 0, 0),
('list_column_ND_ReleaseDate', 2, 'Publication date', 'cible', '', 0, 0),
('list_column_ND_Status', 2, 'Status', 'cible', '', 0, 0),
('list_column_CI_Title', 1, 'Catégorie', 'cible', '', 0, 0),
('list_column_CI_WordingShowAllRecords', 1, 'Description', 'cible', '', 0, 0),
('list_column_CI_Title', 2, 'Category Name', 'cible', '', 0, 0),
('list_column_CI_WordingShowAllRecords', 2, 'Category Description', 'cible', '', 0, 0),
('button_add_category', 2, 'Add a category', 'cible', '', 0, 0),
('button_add_category', 1, 'Ajouter une catégorie', 'cible', '', 0, 0),
('form_category_associated_page_label', 1, 'Page associée', 'cible', '', 0, 0),
('form_category_associated_page_label', 2, 'Associated Page:', 'cible', '', 0, 0),
('categories_add_page_title', 1, 'Ajouter une catégorie', 'cible', '', 0, 0),
('categories_add_page_title', 2, 'Add a Category', 'cible', '', 0, 0),
('menu_treeview_content_management', 1, 'Gestion des contenus', 'cible', '', 0, 0),
('menu_treeview_content_management', 2, 'Contents management', 'cible', '', 0, 0),
('header_add_profile_text', 1, 'Ajout d''une personne', 'cible', '', 0, 0),
('header_add_profile_text', 2, 'Add a person', 'cible', '', 0, 0),
('header_add_profile_description', 1, 'Cette page permet d''ajouter une nouvelle personne dans le système en spécifiant les renseignements utiles.', 'cible', '', 0, 0),
('header_add_profile_description', 2, 'This page allows you to add a new person into the system by specifying the information on it.', 'cible', '', 0, 0),
('header_edit_profile_text', 1, 'Modification', 'cible', '', 0, 0),
('header_edit_profile_text', 2, 'Modification', 'cible', '', 0, 0),
('header_edit_profile_description', 1, 'Cette page permet de modifier les renseignements sur une personne.', 'cible', '', 0, 0),
('header_edit_profile_description', 2, 'This page allows you to edit information for a person', 'cible', '', 0, 0),
('header_delete_profile_text', 1, 'Suppression ', 'cible', '', 0, 0),
('header_delete_profile_text', 2, 'Remove', 'cible', '', 0, 0),
('header_delete_profile_description', 1, 'Cette page permet de supprimer définitivement la fiche d''une personne.', 'cible', '', 0, 0),
('menu_principal', 1, 'Menu principal', 'cible', '', 0, 0),
('profile_title_general_information', 1, 'Renseignements généraux', 'cible', '', 0, 0),
('profile_title_general_information', 2, 'General Information', 'cible', '', 0, 0),
('profile_title_newsletter', 1, 'Infolettre', 'cible', '', 0, 0),
('profile_title_newsletter', 2, 'Newsletter', 'cible', '', 0, 0),
('profile_title_groupementChefsMembers', 1, 'Membres du Groupement des Chefs', 'cible', '', 0, 0),
('profile_title_groupementChefsMembers', 2, 'Members of the "Groupement des Chefs"', 'cible', '', 0, 0),
('list_column_S_Code', 1, 'Statut', 'cible', '', 0, 0),
('list_column_S_Code', 2, 'Status', 'cible', '', 0, 0),
('button_add_profile', 2, 'Add new person', 'cible', '', 0, 0),
('button_add_profile', 1, 'Ajouter une nouvelle personne', 'cible', '', 0, 0),
('button_add_general', 1, 'Ajouter une nouvelle personne', 'cible', '', 0, 0),
('button_add_general', 2, 'Add new person', 'cible', '', 0, 0),
('header_list_profile_text', 1, 'Liste ', 'cible', '', 0, 0),
('header_list_profile_description', 1, 'Liste des personnes présentes dans le système', 'cible', '', 0, 0),
('form_label_account_status', 1, 'Status du compte', 'cible', '', 0, 20),
('form_label_account_status', 2, 'Account status', 'cible', '', 0, 20),
('search_result_items_found', 2, 'Your search generated<br />%ITEM_COUNT% items found', 'cible', '', 0, 0),
('search_list_all_items', 2, 'See the complete list »', 'cible', '', 0, 0),
('button_preview_close', 1, 'Fermer', 'cible', '', 0, 0),
('button_preview_close', 2, 'Close', 'cible', '', 0, 0),
('text_draft_title', 1, 'Brouillon', 'cible', '', 0, 1),
('text_draft_title', 2, 'Draft', 'cible', '', 0, 1),
('text_online_title', 1, 'En ligne', 'cible', '', 0, 1),
('text_online_title', 2, 'Online', 'cible', '', 0, 1),
('list_column_EI_Title', 1, 'Titre', 'cible', '', 0, 1),
('list_column_EI_Title', 2, 'Title', 'cible', '', 0, 1),
('header_edit_text_text', 1, 'Édition d''un texte', 'cible', '', 0, 1),
('header_edit_text_text', 2, 'Text edit', 'cible', '', 0, 1),
('header_edit_text_description', 1, 'Cette page vous permet d''éditer un texte.', 'cible', '', 0, 1),
('header_edit_text_description', 2, 'This page is to edit a text.', 'cible', '', 0, 1),
('management_module_text_list', 1, 'Blocs textes', 'cible', '', 0, 1),
('management_module_text_list', 2, 'Texts', 'cible', '', 0, 1),
('management_module_text_list_approbation_request', 1, 'Approbation de textes', 'cible', '', 0, 1),
('management_module_text_list_approbation_request', 2, 'Texts to be approved', 'cible', '', 0, 1);
REPLACE INTO Static_Texts (ST_Identifier, ST_LangID, ST_Value, ST_Type, ST_Desc_backend, ST_Editable, `ST_ModuleID`) VALUES
('form_legend_infoPage', 1, 'Données de la page', 'cible', '', 0, 1),
('form_legend_infoPage', 2, 'Page data', 'cible', '', 0, 1),
('form_legend_blockData', 1, 'Données du bloc', 'cible', '', 0, 1),
('form_legend_blockData', 2, 'Block Data', 'cible', '', 0, 1),
('management_module_blog_list', 1, 'Édition des nouvelles', 'cible', '', 0, 0),
('management_module_blog_list', 2, 'Blog', 'cible', '', 0, 0),
('list_column_title', 1, 'Titre', 'cible', '', 0, 0),
('action', 1, 'Actions', 'cible', '', 0, 0),
('list_column_action', 1, 'Actions', 'cible', '', 0, 0),
('categories_edit_page_title', 1, 'Édition d''une catégorie', 'cible', '', 0, 0),
('header_list_text_approbation_title', 1, 'Textes à approuver', 'cible', '', 0, 0),
('header_list_text_approbation_description', 1, '<b>Cliquez sur le titre du texte</b> que vous souhaitez approuver.<br><br>Vous pouvez <b>rechercher par mots-clés</b> parmi la liste<br>des textes à approuver. Pour revenir à la liste complète,<br>cliquez sur <b>Voir la liste complète</b>.<br><br>Vous pouvez <b>modifier ou supprimer le texte</b><br>en cliquant sur l''icône <img src="/extranet/icons/list_actions_icon.png" align=middle>.', 'cible', '', 0, 0),
('list_column_TD_DraftTitle', 1, 'Titre du texte', 'cible', '', 0, 1),
('dictionnary_add_code_snippet', 1, 'Ajouter une balise d''aide', 'cible', '', 0, 0),
('dictionnary_add_code_snippet', 2, 'Insert an help code snippet', 'cible', '', 0, 0),
('button_all', 1, 'TOUS', 'cible', '', 0, 0),
('button_all', 2, 'ALL', 'cible', '', 0, 0),
('filter_empty_category', 1, 'Filtrer par catégories', 'cible', '', 0, 0),
('filter_empty_category', 2, 'Filter by categories', 'cible', '', 0, 0),
('filter_empty_language', 1, 'Filtrer par langues', 'cible', '', 0, 0),
('filter_empty_language', 2, 'Filtrer par langues', 'cible', '', 0, 0),
('header_list_arbo_text_text', 1, 'Liste des textes', 'cible', '', 0, 0),
('header_list_arbo_text_description', 1, '<b>Cliquez sur le nom de la page</b> dans la liste suivante<br>pour modifier les textes de votre site.<br><br>Vous pouvez <b>déployer les onglets</b> pour accéder aux sous-sections.', 'cible', '', 0, 0),
('header_list_arbo_text_text', 2, 'List texts of the Web site', 'cible', '', 0, 0),
('header_list_arbo_text_description', 2, 'This page is to manage the texts of your Web site', 'cible', '', 0, 0),
('filter_empty_status', 1, 'Filtrer par statut', 'cible', '', 0, 0),
('filter_empty_status', 2, 'Filter by status', 'cible', '', 0, 0),
('filter_empty_label', 1, 'Choisir une valeur', 'cible', '', 0, 0),
('filter_empty_label', 2, 'Select a value', 'cible', '', 0, 0),
('form_paginator_filters_label', 1, 'Filtres', 'cible', '', 0, 0),
('form_paginator_filters_label', 2, 'Filters', 'cible', '', 0, 0),
('list_return_to_list_link', 1, 'Revenir à la liste', 'cible', '', 0, 0),
('categories_delete_page_description', 1, 'Aide pour la suppression des catégories', 'cible', '', 0, 0),
('categories_delete_page_title', 1, 'Suppression de catégorie', 'cible', '', 0, 0),
('categories_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer la catégorie « %CATEGORY_NAME% »?', 'cible', '', 0, 0),
('list_column_7_CI_Title', 1, 'Catégories', 'cible', '', 0, 0),
('list_column_8_CI_Title', 1, 'Infolettre', 'cible', '', 0, 0),
('list_column_2_CI_Title', 1, 'Catégories', 'cible', '', 0, 0),
('form_category_select_a_view_label', 1, 'Choisir une vue', 'cible', '', 0, 0),
('menu_topRight', 1, 'Menu <i>Nous joindre</i>', 'cible', '', 0, 0),
('menu_topLeft', 1, 'Menu <i>Le Groupement à l''international</i>', 'cible', '', 0, 0),
('list_column_NR_Title', 1, 'Titre', 'cible', '', 0, 0),
('list_column_NR_Date', 1, 'Date de parution', 'cible', '', 0, 0),
('list_column_NR_Online', 1, 'En ligne', 'cible', '', 0, 0),
('list_column_NR_Status', 1, 'Envoyée', 'cible', '', 0, 0),
('filter_empty_send', 1, 'Filter par envoyée', 'cible', '', 0, 0),
('filter_empty_send', 2, 'Filter par envoyée', 'cible', '', 0, 0),
('salutation_mr', 1, 'M.', 'cible', '', 0, 0),
('salutation_mr', 2, 'Mr', 'cible', '', 0, 0),
('salutation_mrs', 1, 'Mme', 'cible', '', 0, 0),
('salutation_mrs', 2, 'Mrs', 'cible', '', 0, 0),
('send_0', 1, 'Non envoyée', 'cible', '', 0, 0),
('send_0', 2, 'Not sent', 'cible', '', 0, 0),
('send_1', 1, 'Envoyée', 'cible', '', 0, 0),
('send_1', 2, 'Sent', 'cible', '', 0, 0),
('send_2', 1, 'Non envoyée', 'cible', '', 0, 0),
('send_2', 2, 'Not sent', 'cible', '', 0, 0),
('send_3', 1, 'À envoyer à tous', 'cible', '', 0, 0),
('send_3', 2, 'To send to everyone', 'cible', '', 0, 0),
('form_label_view_module', 1, 'Vue du module', 'cible', '', 0, 0),
('form_select_option_view_module_inscription', 1, 'Inscription', 'cible', '', 0, 0),
('form_select_option_view_module_inscription', 2, 'Registration', 'cible', '', 0, 0),
('footer_copyright', 1, '<p>&copy; %%%GET_DATE_YEAR%%%, Edith -- Tout droits r&eacute;serv&eacute;s </p>', 'client', 'Texte du pied de page', 1, 0),
('footer_copyright', 2, '<p>&copy; %%%GET_DATE_YEAR%%%, Edith -- All right reserved</p>', 'client', 'Texte du pied de page', 1, 0),
('footer_cible_realisation', 1, 'Conception Web : <a href="http://www.ciblesolutions.com" target="_blank">CIBLE</a>', 'client', '', 0, 0),
('footer_cible_realisation', 2, 'Web Design : <a href="http://www.ciblesolutions.com" target="_blank">CIBLE</a>', 'client', '', 0, 0),
('module_search', 1, 'Rechercher', 'cible', '', 0, 0),
('module_search', 2, 'Search', 'cible', '', 0, 0),
('search_module_name', 1, 'Recherche', 'cible', '', 0, 0),
('search_module_name', 2, 'Rechercher', 'cible', '', 0, 0),
('form_select_option_view_default_index', 1, 'Défaut', 'cible', '', 0, 0),
('form_select_label_associated_view', 1, 'Vue du module', 'cible', '', 0, 0),
('items_under_menu', 1, '<div class="clear_both spacerXLarge"></div>\n        <div id="under-menu-content">\n            <div id="box-membres-partenaires">\n                <div id="box-conditions-formulaire"><a href="%BASE_URL%/%BECOME_MEMBER_URL%">DEVENIR MEMBRE</a></div>\n                \n                <div class="clear_both spacerSmall"></div>\n                <div id="box-conditions-formulaire"><a href="%BASE_URL%/%CONDITIONS_URL%">Conditions d''admissibilité</a></div>\n            </div>\n        </div>\n        \n        <div class="clear_both spacerMedium"></div>\n        <div id="box-gallery-photos">\n            <a href="%BASE_URL%/%GALLERY_URL%">GALERIE PHOTOS</a>\n            <div><a href="%BASE_URL%/%GALLERY_URL%"><img src="%BASE_URL%/themes/default/images/common/under-menu-gallery-photo.jpg" border="0" alt="" /></a></div>\n            <div id="box-see-gallery"><a href="%BASE_URL%/%GALLERY_URL%">Voir les galeries photos</a></div>\n            \n            <div class="clear_both spacerSmall"></div>\n            <div id="under-menu-spacer-dot-share" class="clear_both"></div>\n        </div>\n\n <div class="clear_both spacerMedium"></div>\n        <div id="box-newsletter">\n            <a href="%BASE_URL%/%NEWSLETTER_URL%">INFOLETTRE</a>\n            <div class="links"><a href="%BASE_URL%/%NEWSLETTER_URL%">Inscription</a></div>\n            \n            <div class="clear_both spacerSmall"></div>\n            <div id="under-menu-spacer-dot-share" class="clear_both"></div>\n        </div>', 'client', '', 0, 0),
('button_save_draft', 1, 'Sauvegarder le brouillon', 'cible', '', 0, 0),
('form_extranet_group_status_active', 1, 'Ce groupe est actif.', 'cible', '', 0, 0),
('form_extranet_group_status_active', 2, 'This group is active', 'cible', '', 0, 0),
('forms_become_partner_label_name', 1, 'Nom', 'client', '', 0, 0),
('forms_become_partner_label_enterprise', 1, 'Entreprise', 'client', '', 0, 0),
('forms_become_partner_label_service', 1, 'Service', 'client', '', 0, 0),
('forms_become_partner_label_phone', 1, 'Téléphone', 'client', '', 0, 0),
('forms_become_partner_label_email', 1, 'Adresse courriel', 'client', '', 0, 0),
('forms_become_partner_label_conditions', 1, 'Je confirme avoir lu les conditions d''admissibilité.', 'client', '', 0, 0),
('form_select_option_view_forms_become_partner', 1, 'Devenir partenaire', 'cible', '', 0, 0),
('become_member_thank_you', 1, 'Merci', 'client', '', 0, 0),
('become_partner_thank_you', 1, 'Merci', 'client', '', 0, 0),
('validation_message_confirmation', 1, 'Vous devez confirmer que vous avez bien lu les conditions d''admissibilité.', 'cible', '', 0, 0),
('share_print_text', 1, 'Imprimer', 'client', '', 0, 0),
('share_print_text', 2, 'Print', 'client', '', 0, 0),
('share_share_text', 1, 'Partager', 'client', '', 0, 0),
('share_share_text', 2, 'Share', 'client', '', 0, 0),
('form_check_label_online', 1, 'Cocher pour mettre en ligne', 'cible', '', 0, 0),
('form_check_label_online', 2, 'Check to put online', 'cible', '', 0, 0),
('form_extranet_group_status_inactive', 1, 'Ce groupe est inactif.', 'cible', '', 0, 0),
('form_extranet_group_status_inactive', 2, 'This group is inactive', 'cible', '', 0, 0),
('dashboard_administration_profil_list_description', 2, 'Management of the registered users', 'cible', '', 0, 0),
('form_label_text_draft', 1, 'Texte version brouillon', 'cible', '', 0, 0),
('form_label_text_draft', 2, 'Draft text version', 'cible', '', 0, 0),
('button_compare_text', 1, 'Comparer brouillon/en ligne', 'cible', '', 0, 0),
('button_compare_text', 2, 'Compare draft / online', 'cible', '', 0, 0),
('button_preview_text', 1, 'Prévisualiser le brouillon', 'cible', '', 0, 0),
('button_preview_text', 2, 'Preview draft', 'cible', '', 0, 0),
('manage_block_contents', 1, 'Mettre à jour le contenu de ce bloc', 'cible', '', 0, 0),
('manage_block_contents', 2, 'Update the contents of this block', 'cible', '', 0, 0),
('list_column_BI_BlockTitle', 1, 'Titre du texte', 'cible', '', 0, 0),
('text_manage_block_contents', 1, 'Modifier le texte', 'cible', '', 0, 0),
('text_manage_block_contents', 2, 'Modify the text', 'cible', '', 0, 0),
('button_save_draft', 2, 'Save draft', 'cible', '', 0, 0),
('manage_content', 1, 'Gérer les contenus de cette page', 'cible', '', 0, 0),
('manage_content', 2, 'Manage page content', 'cible', '', 0, 0),
('manage_structure', 1, 'Gérer la structure de cette page', 'cible', '', 0, 0),
('manage_structure', 2, 'Manage page structure', 'cible', '', 0, 0),
('manage_block_online_status', 1, 'En ligne', 'cible', '', 0, 0),
('manage_block_online_status', 2, 'Online', 'cible', '', 0, 0),
('button_modify_existing_Profile', 1, 'Modifier le profil de l''usager existant', 'cible', '', 0, 0),
('button_search_another_profile_email', 1, 'Recommencer avec une autre adresse courriel', 'cible', '', 0, 0),
('profile_message_email_already_exists', 1, 'Cette adresse courriel est déjà utilisée par un usager.', 'cible', '', 0, 0),
('button_continue', 1, 'Continuer', 'cible', '', 0, 0),
('form_label_date', 1, 'Date', 'cible', '', 0, 0),
('form_label_date', 2, 'Date', 'cible', '', 0, 0),
('dashboard_administration_utilities_googleAnalytics', 1, 'Statistiques d''audience Internet', 'cible', '', 0, 0),
('dashboard_administration_website_reindexing', 1, 'Réindexation des contenus', 'cible', '', 0, 0),
('utilities_googleAnalytics_account', 1, 'Google Analytics', 'cible', '', 0, 0),
('utilities_googleAnalytics_username', 1, 'Nom d''utilisateur', 'cible', '', 0, 0),
('utilities_googleAnalytics_password', 1, 'Mot de passe', 'cible', '', 0, 0);

REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('utilities_googleAnalytics_access_link', 1, 'Accéder à Google Analytics maintenant', 'cible', '', 0, 0),
('utilities_googleAnalytics_title', 1, 'Statistiques d''audience Internet', 'cible', '', 0, 0),
('utilities_googleAnalytics_description', 1, 'Cette page vous permet d''accéder à votre outil de statistiques d''audience Internet.', 'cible', '', 0, 0),
('header_reindexing_text', 1, 'Réindexation des contenus', 'cible', '', 0, 0),
('header_reindexing_description', 1, 'Cette page vous permet de réindexer tous les contenus texte de votre site Internet. Lors de la réindexation, il est important de ne pas quitter cette page avant la fin de l''exécution.', 'cible', '', 0, 0),
('reindexing_message_confirmation', 1, 'Désirez-vous faire la réindexation des contenus de votre site Web?', 'cible', '', 0, 0),
('list_column_ND_Date', 1, 'Date', 'cible', '', 0, 0),
('form_label_menu_type_page', 1, 'Page', 'cible', '', 0, 0),
('form_label_menu_type_placeholder', 1, 'Menu déroulant', 'cible', '', 0, 0),
('form_label_menu_type_external', 1, 'Lien externe', 'cible', '', 0, 0),
('menu_bottom', 1, 'Menu du bas', 'cible', '', 0, 0),
('validation_message_int_field', 2, 'This field must contain a numeric value', 'cible', '', 0, 0),
('forms_module_name', 1, 'Formulaire de contact', 'cible', '', 0, 0),
('forms_module_name', 2, 'Contact Form', 'cible', '', 0, 0),
('form_select_option_view_forms_become_member', 1, 'Devenir membre', 'cible', '', 0, 0),
('forms_become_member_heard_ad', 1, 'publicité', 'client', '', 0, 0),
('forms_become_member_heard_bank', 1, 'par mon banquier', 'client', '', 0, 0),
('forms_become_member_heard_friend', 1, 'par un ami/une connaissance/un membre de la famille', 'client', '', 0, 0),
('forms_become_member_heard_other', 1, 'Autre', 'client', '', 0, 0),
('forms_become_member_heard_website', 1, 'site Internet', 'client', '', 0, 0),
('forms_become_member_label_become_member', 1, 'Je suis intéressé à devenir membre :', 'client', '', 0, 0),
('forms_become_member_label_enterprise', 1, 'Entreprise :', 'client', '', 0, 0),
('forms_become_member_label_heard_from', 1, 'Comment avez-vous entendu parler du Groupement? :', 'client', '', 0, 0),
('forms_become_member_label_message', 1, 'Message (facultatif) :', 'cible', '', 0, 0),
('forms_become_member_label_more_info', 1, 'J''aimerais obtenir plus d''information :', 'client', '', 0, 0),
('forms_become_member_label_name', 1, 'Nom', 'client', '', 0, 0),
('forms_become_member_label_other', 1, 'Préciser :', 'client', '', 0, 0),
('forms_become_member_label_phone', 1, 'Téléphone :', 'client', '', 0, 0),
('forms_become_member_label_region', 1, 'Régions :', 'client', '', 0, 0),
('forms_become_member_regions_abitibi_temiscamingue', 1, 'Abitibi-Témiscamingue', 'client', '', 0, 0),
('forms_become_member_regions_autre', 1, 'Autre', 'client', '', 0, 0),
('forms_become_member_regions_bas_saint_laurent', 1, 'Bas Saint-Laurent', 'client', '', 0, 0),
('forms_become_member_regions_capitale_nationale', 1, 'Capitale-Nationale', 'client', '', 0, 0),
('forms_become_member_regions_centre_du_quebec', 1, 'Centre-du-Québec', 'client', '', 0, 0),
('forms_become_member_regions_chaudiere_appalaches', 1, 'Chaudière-Appalaches', 'client', '', 0, 0),
('forms_become_member_regions_cote_nord', 1, 'Côte-Nord', 'client', '', 0, 0),
('forms_become_member_regions_estrie', 1, 'Estrie', 'client', '', 0, 0),
('forms_become_member_regions_europe', 1, 'Europe', 'client', '', 0, 0),
('forms_become_member_regions_gaspesie_iles_de_la_madeleine', 1, 'Gaspésie?Îles-de-la-Madeleine', 'client', '', 0, 0),
('forms_become_member_regions_lanaudiere', 1, 'Lanaudière', 'client', '', 0, 0),
('forms_become_member_regions_laurentides', 1, 'Laurentides', 'client', '', 0, 0),
('forms_become_member_regions_laval', 1, 'Laval', 'client', '', 0, 0),
('forms_become_member_regions_mauricie', 1, 'Mauricie', 'client', '', 0, 0),
('forms_become_member_regions_monteregie', 1, 'Montérégie', 'client', '', 0, 0),
('forms_become_member_regions_montreal', 1, 'Montréal', 'client', '', 0, 0),
('forms_become_member_regions_nord_du_quebec', 1, 'Nord-du-Québec', 'client', '', 0, 0),
('forms_become_member_regions_nouveau_brunswick', 1, 'Nouveau-Brunswick', 'client', '', 0, 0),
('forms_become_member_regions_outaouais', 1, 'Outaouais', 'client', '', 0, 0),
('forms_become_member_regions_saguenay_lac_saint_jean', 1, 'Saguenay?Lac-Saint-Jean', 'client', '', 0, 0),
('validation_message_int_field', 1, 'Ce champ doit contenir une valeur numérique.', 'cible', '', 0, 0),
('categories_2_delete_errror_message', 1, 'Vous ne pouvez supprimer la catégorie ''%CATEGORY_NAME%'', car elle est encore utilisée.', 'cible', '', 0, 0),
('categories_2_delete_page_title', 1, 'Suppression d''une catégorie de nouvelles', 'cible', '', 0, 0),
('categories_categories_2_delete_page_description', 1, 'Cette page vous permet de supprimer la catégorie sélectionnée.', 'cible', '', 0, 0),
('categories_2_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer la catégorie ''%CATEGORY_NAME%''?', 'cible', '', 0, 0),
('categories_7_delete_page_title', 1, 'Suppression d''une catégorie d''événements', 'cible', '', 0, 0),
('categories_categories_7_delete_page_description', 1, 'Cette page vous permet de supprimer la catégorie sélectionnée.', 'cible', '', 0, 0),
('reindexing_message_execution1', 1, 'Suppression de l''indexation en cours...', 'cible', '', 0, 0),
('reindexing_message_execution1', 2, 'Remove indexing in progress ...', 'cible', '', 0, 0),
('form_label_menu_destination_link', 1, 'Destination (URL) ', 'cible', '', 0, 0),
('form_label_menu_destination_page', 1, 'Destination (sélectionnez la page) ', 'cible', '', 0, 0),
('reindexing_message_execution2', 1, 'Suppression terminée', 'cible', '', 0, 0),
('reindexing_message_execution2', 2, 'Complete', 'cible', '', 0, 0),
('reindexing_message_execution3', 1, 'Réindexation en cours... Cette opération peut prendre quelques minutes.', 'cible', '', 0, 0),
('reindexing_message_execution3', 2, 'Re-indexing in progress ... (this may take a few minutes)', 'cible', '', 0, 0),
('reindexing_message_execution4', 1, 'Réindexation terminée', 'cible', '', 0, 0),
('reindexing_message_execution4', 2, 'Re-indexing is complete', 'cible', '', 0, 0),
('page_menu_title', 1, 'Ajout/modification d''un élément de menu', 'cible', '', 0, 0),
('page_menu_description', 1, 'Cette page vous permet d''ajouter ou de modifier un élément de menu.', 'cible', '', 0, 0),
('reindexing_message_inprogress', 1, 'Réindexation en cours... Ne pas quitter cette page avant l''apparition du bouton <b>Retour</b>.', 'cible', '', 0, 0),
('reindexing_message_inprogress', 2, 'Re-indexing in progress (do not leave this page before the release of the "back" button)', 'cible', '', 0, 0),
('categories_7_delete_errror_message', 1, 'Vous ne pouvez supprimer la catégorie ''%CATEGORY_NAME%'', car elle est encore utilisée.', 'cible', '', 0, 0),
('categories_7_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer la catégorie ''%CATEGORY_NAME%''?', 'cible', '', 0, 0),
('categories_8_delete_page_title', 1, 'Suppression d''une infolettre', 'cible', '', 0, 0),
('categories_categories_8_delete_page_description', 1, 'Cette page vous permet de supprimer l''infolettre sélectionnée.', 'cible', '', 0, 0),
('categories_8_delete_errror_message', 1, 'Vous ne pouvez supprimer l''infolettre ''%CATEGORY_NAME%'', car elle est encore utilisée.', 'cible', '', 0, 0),
('categories_8_delete_message_confirmation', 1, 'Voulez-vous vraiment supprimer l''infolettre ''%CATEGORY_NAME%''?', 'cible', '', 0, 0),
('form_check_label_show_title', 1, 'Afficher le titre de la page', 'cible', '', 0, 0),
('form_select_option_pageLayouts_1', 1, 'Accueil', 'cible', '', 0, 0),
('form_select_option_pageLayouts_2', 1, 'Commun', 'cible', '', 0, 0),
('form_select_option_zoneViews_1', 1, 'Accueil', 'cible', '', 0, 0),
('form_select_option_zoneViews_2', 1, 'Commun', 'cible', '', 0, 0),
('menu_header', 1, 'Menu haut, Principal', 'cible', '', 0, 0),
('home_page_link_title', 1, '%%%SITE_NAME%%%', 'client', 'Attribut "Alt" du logo principal', 1, 0),
('home_page_link_title', 2, '%%%SITE_NAME%%%', 'client', 'Attribut "Alt" du logo principal', 1, 0),
('button_add_collection', 1, 'Ajouter une collection', 'cible', '', 0, 0),
('button_add_collection', 2, 'Add a collection', 'cible', '', 0, 0),
('dashboard_administration_static_text_title', 1, 'Textes statiques', 'cible', '', 0, 0),
('dashboard_administration_static_text_title', 2, 'Static texts', 'cible', '', 0, 0),
('dashboard_administration_static_text_description', 1, 'Libellés et autres textes', 'cible', '', 0, 0),
('dashboard_administration_static_text_description', 2, 'Management of the static texts', 'cible', '', 0, 0),
('dashboard_administration_references_description', 1, 'Listes de valeurs', 'cible', '', 0, 0),
('dashboard_administration_references_description', 2, 'References texts', 'cible', '', 0, 0),
('header_list_static_text_text', 1, 'Liste des textes statiques en français', 'cible', '', 0, 0),
('header_list_static_text_text', 2, 'List of the English static text', 'cible', '', 0, 0),
('header_list_static_text_description', 1, 'Cette page vous permet de consulter la liste des textes statiques français.<br /><br />Vous pouvez éditer la valeur française et anglaise.', 'cible', '', 0, 0),
('header_list_static_text_description', 2, 'This page gives a list of static texts English. <br /> <br /> You can edit the value of French and English.', 'cible', '', 0, 0),
('list_column_ST_Desc_backend', 1, 'Nom du texte statique', 'cible', '', 0, 0),
('list_column_ST_Desc_backend', 2, 'Name of the static text', 'cible', '', 0, 0),
('list_column_ST_Value', 1, 'Valeur du texte statique', 'cible', '', 0, 0),
('list_column_ST_Value', 2, 'Static text value', 'cible', '', 0, 0),
('header_edit_static_text_text', 1, 'Édition d''un texte statique', 'cible', '', 0, 0),
('header_edit_static_text_text', 2, 'Static text edition', 'cible', '', 0, 0),
('header_edit_static_text_description', 1, 'Cette page vous permet de modifier la valeur d''un texte statique.', 'cible', '', 0, 0),
('header_edit_static_text_description', 2, 'This page allows you to modify the value of the static text', 'cible', '', 0, 0),
('list_column_ST_LangID', 1, 'Langue du texte statique', 'cible', '', 0, 0),
('list_column_ST_LangID', 2, 'Static text language', 'cible', '', 0, 0),
('form_select_option_view_forms_forms_contact', 1, 'Formulaire - Nous joindre', 'cible', '', 0, 0),
('form_select_option_view_forms_forms_demandes_informations', 1, 'Formulaire - Demande d''information', 'cible', '', 0, 0),
('form_select_option_view_forms_forms_dons', 1, 'Formulaire - Dons', 'cible', '', 0, 0),
('form_select_option_view_forms_forms_inscriptions_ateliers', 1, 'Formulaire - Inscription aux ateliers', 'cible', '', 0, 0),
('form_select_option_position_below', 1, 'En-dessous de < %TEXT% >', 'cible', '', 0, 0),
('form_select_option_position_below', 2, 'Below < %TEXT% >', 'cible', '', 0, 0),
('form_label_phone_1', 1, 'Téléphone', 'cible', '', 0, 0),
('form_label_phone_1', 2, 'Phone number', 'cible', '', 0, 0),
('form_label_questions', 1, 'Message', 'cible', '', 0, 0),
('form_label_questions', 2, 'Message', 'cible', '', 0, 0),
('form_field_required_label', 1, 'Champs requis', 'cible', '', 0, 0),
('form_field_required_label', 2, 'Required fields', 'cible', '', 0, 0),
('form_label_explain_captcha', 1, 'Pour des raisons de sécurité, veuillez entrer les caractères alphanumériques de l''image dans l''espace ci-dessous.', 'cible', '', 0, 0),
('form_label_explain_captcha', 2, 'For safety measures, please enter the image alphanumeric characters (6) in the space below.', 'cible', '', 0, 0),
('validation_message_captcha_error', 1, 'Veuillez saisir la chaîne ci-dessus correctement.', 'cible', '', 0, 0),
('validation_message_captcha_error', 2, 'Captcha value is wrong.', 'cible', '', 0, 0),
('form_contact_sent_tank_you_message', 1, 'Votre message nous a été envoyé avec succès.<br />Merci!', 'client', '', 0, 0),
('form_contact_sent_tank_you_message', 2, 'Your message was sent to us successfully.<br />Thank you!', 'client', '', 0, 0),
('form_label_phone_work', 1, 'Téléphone travail', 'cible', '', 0, 0),
('form_label_phone_home', 1, 'Téléphone résidence', 'cible', '', 0, 0),
('form_label_phone_home', 2, 'Téléphone résidence', 'cible', '', 0, 0),
('form_label_phone_work', 2, 'Téléphone travail', 'cible', '', 0, 0),
('form_label_address', 1, 'Adresse', 'cible', '', 0, 0),
('form_label_address', 2, 'Adresse', 'cible', '', 0, 0),
('form_label_code_postal', 1, 'Code postal', 'cible', '', 0, 0),
('form_label_code_postal', 2, 'Code postal', 'cible', '', 0, 0),
('form_label_fname_lname', 1, 'Nom et prénom', 'cible', '', 0, 0),
('form_label_fname_lname', 2, 'Nom et prénom', 'cible', '', 0, 0),
('form_label_ville', 1, 'Ville', 'cible', '', 0, 0),
('form_label_ville', 2, 'Ville', 'cible', '', 0, 0),
('form_label_province', 1, 'Province', 'cible', '', 0, 0),
('form_label_province', 2, 'Province', 'cible', '', 0, 0),
('module_forms', 1, 'Formulaire', 'cible', '', 0, 0),
('module_forms', 2, 'Formulaire', 'cible', '', 0, 0),
('login_form_email_label', 1, 'Courriel', 'client', '', 0, 0),
('login_form_email_label', 2, 'Email', 'client', '', 0, 0),
('login_form_password_label', 1, 'Mot de passe', 'client', '', 0, 0),
('login_form_password_label', 2, 'Password', 'client', '', 0, 0),
('login_form_stayOn_label', 1, 'Rester conneté', 'client', '', 0, 0),
('login_form_auth_fail_error', 1, 'Votre courriel ou votre mot de passe sont incorrects.', 'client', '', 0, 0),
('login_form_auth_fail_error', 2, 'Your email or password is incorrect.', 'client', '', 0, 0),
('login_form_stayOn_label', 2, 'Stay signed in', 'client', '', 0, 0),
('lost_password_link', 1, 'Mot de passe oublié?', 'client', '', 0, 0),
('lost_password_link', 2, 'Forgot password?', 'client', '', 0, 0),
('login_form_create_account_title', 1, 'Vous n?êtes pas encore inscrit?', 'client', '', 0, 0),
('login_form_create_account_title', 2, 'Not yet registered?', 'client', '', 0, 0),
('login_form_create_account_message', 1, 'A renseigner', 'client', 'Message de notification: Création de compte', 1, 0),
('login_form_create_account_message', 2, 'To create', 'client', 'Message for the email of the  account notification', 1, 0),
('login_form_create_account_button', 1, 'Inscrivez-vous', 'client', '', 0, 0),
('login_form_create_account_button', 2, 'Click here to register', 'client', '', 0, 0),
('login_form_login_title', 1, 'Vous avez déjà un compte', 'client', '', 0, 0),
('login_form_login_title', 2, 'You already have an account', 'client', '', 0, 0),
('button_edit', 2, 'Edit', 'cible', '', 0, 0),
('button_delete', 2, 'Delete', 'cible', '', 0, 0),
('menu_footer', 1, 'Menu de bas de page', 'cible', '', 0, 0),
('menu_footer', 2, 'Footer menu', 'cible', '', 0, 0),
('menu_permanent', 1, 'Menu permanent', 'cible', '', 0, 0),
('menu_permanent', 2, 'Permanent menu', 'cible', '', 0, 0),
('account_logout_link', 1, 'Déconnexion', 'client', '', 0, 0),
('account_logout_link', 2, 'Logout', 'client', '', 0, 0),
('account_modify_page_title', 1, 'Modifier votre compte', 'client', '', 0, 0),
('account_modify_page_title', 2, 'Modify your account', 'client', '', 0, 0),
('list_column_date', 1, 'Date', 'cible', '', 0, 0),
('list_column_date', 2, 'Date', 'cible', '', 0, 0),
('list_column_status', 1, 'Statut', 'cible', '', 0, 0),
('list_column_status', 2, 'Status', 'cible', '', 0, 0);
REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('form_label_subscribe', 1, 'Restez au courant! Inscrivez-vous pour recevoir l''information sur nos produits, nos événements et nos nouvelles. Nous respectons vos <a href="%URL_PRIVACY_POLICY%" target="_blank">renseignements personnels</a> et vous pouvez vous désabonner en tout temps.', 'cible', '', 0, 0),
('form_label_subscribe', 2, 'Stay in the loop. Sign up to receive the latest info on XXXXXXXXXX products, news and happenings. We strongly respect your <a href="%URL_PRIVACY_POLICY%" target="_blank">privacy</a> and you may unsubscribe at any time.', 'cible', '', 0, 0),
('form_label_salutation', 1, 'Salutation', 'cible', '', 0, 0),
('form_label_salutation', 2, 'Gender', 'cible', '', 0, 0),
('form_label_terms_agreement', 1, 'J''accepte la totalité de ces <a href="%URL_TERMS_CONDITIONS%" target="_blank">conditions</a>', 'client', '', 0, 0),
('form_label_terms_agreement', 2, 'J''accepte la totalité de ces <a href="%URL_TERMS_CONDITIONS%" target="_blank">conditions</a>', 'client', '', 0, 0),
('frontend_language_switcher_fr', 1, 'Français', 'client', 'Texte pour la langue fr', 0, 0),
('frontend_language_switcher_fr', 2, 'Français', 'client', 'Text for the fr language', 0, 0),
('frontend_language_switcher_en', 1, 'English', 'client', 'Texte pour la langue en', 0, 0),
('frontend_language_switcher_en', 2, 'English', 'client', 'Text for the en language', 0, 0),
('validated_notification_client_email_message', 1, '', 'client', 'Notification de validation de courriel envoyé à l''utilisateur qui se crée un compte', 1, 0),
('validated_notification_client_email_message', 2, '', 'client', 'Notification of validation email sent to the user who created an account', 1, 0),
('revalidated_notification_client_email_message', 1, '', 'client', 'Courriel de renvoi de confirmation', 1, 0),
('revalidated_notification_client_email_message', 2, '', 'client', 'Text for the Email adress confirmation', 1, 0),
('need_confirm_email_text', 1, '', 'client', 'Texte pour indiquer à l''utilisateur qu''il va recevoir un courriel', 1, 0),
('need_confirm_email_text', 2, '<p>In a few minutes, you will receive an email message containing a link to use to confirm your email address and activate your account.</p>\n <p>Notes : You may check the spam folder in your email account. Occasionally, email spam filters incorrectly classify valid emails as spam.</p>', 'client', 'Text for the user notification email', 1, 0),
('cart_need_confirm_email_text', 1, '', 'client', 'Texte indiquant au client de confirmer son courriel', 1, 0),
('cart_need_confirm_email_text', 2, '', 'client', 'Text indicating the customer to confirm his email ', 1, 0),
('terms_agreement_error_message', 1, '* Vous devez accepter les conditions', 'client', 'Courriel de renvoi de confirmation', 1, 0),
('terms_agreement_error_message', 2, '* You must agree with the terms.', 'client', 'Text for the Email adress confirmation', 1, 0),
('validation_message_email_already_exists', 1, 'Un compte existe déjà pour ce courriel.', 'client', 'Courriel existe déjà', 1, 0),
('validation_message_email_already_exists', 2, 'An account already exists for this email address.', 'client', 'Email already exists', 1, 0),
('dashboard_administration_website_reindexing', 2, 'Content reindexing', 'cible', '', 0, 0),
('dashboard_administration_utilities_googleAnalytics', 2, 'Content reindexing', 'cible', '', 0, 0),
('menu_footer_simple', 1, 'Menu pied de page [1 niveau]', 'cible', '', 0, 0),
('menu_footer_simple', 2, 'Menu pied de page [1 niveau]', 'cible', '', 0, 0),
('menu_footer_double', 1, 'Menu pied de page [2 niveau]', 'cible', '', 0, 0),
('menu_footer_double', 2, 'Menu pied de page [2 niveau]', 'cible', '', 0, 0),
('form_select_default_label', 1, '-- Sélectionner --', 'cible', '', 0, 0),
('form_select_default_label', 2, '-- Select --', 'cible', '', 0, 0),
('dashboard_administration_utilities_references', 1, 'Données de références', 'cible', '', 0, 0),
('dashboard_administration_utilities_references', 2, 'Refernces data', 'cible', '', 0, 0),
('search_multiple_results_text', 1, 'résultats trouvés pour ', 'cible', '', 0, 0),
('search_multiple_results_text', 2, 'results found for ', 'cible', '', 0, 0),
('search_single_result_text', 1, 'résultat trouvé pour ', 'cible', '', 0, 0),
('search_single_result_text', 2, 'result found for ', 'cible', '', 0, 0),
('manage_block_secured_status', 1, 'Affiché le bloc', 'cible', '', 0, 0),
('manage_block_secured_status', 2, 'Display block', 'cible', '', 0, 0),
('manage_block_secured_menu_status', 1, 'Affiché seulement si connecté', 'cible', '', 0, 0),
('manage_block_secured_menu_status', 2, 'Display only if logged in', 'cible', '', 0, 0),
('connect_to_display_secured_block', 1, 'Si vous disposez d''un compte client chez ##NOM_A_REMPLACER##, veuillez vous identifier afin de pouvoir accéder aux contenus sécurisés.', 'client', 'Blocks sécurisés: message pour la connexion', 1, 0),
('connect_to_display_secured_block', 2, 'If you have a customer account with ##NOM_A_REMPLACER##, please, log in in order to access to the secured contents', 'client', '', 1, 0),
('manage_block_secured_none', 1, 'Toujours', 'cible', '', 0, 0),
('manage_block_secured_none', 2, 'Always', 'cible', '', 0, 0),
('manage_block_secured_logged', 1, 'Si connecté', 'cible', '', 0, 0),
('manage_block_secured_logged', 2, 'if logged', 'cible', '', 0, 0),
('manage_block_secured_notlog', 1, 'si non connecté', 'cible', '', 0, 0),
('manage_block_secured_notlog', 2, 'if not logged', 'cible', '', 0, 0),
('email_notification_footer', 1, 'Ce courriel vous a été envoyer par ##SITE-NAME##', 'client', 'Notification email: footer en français.', 1, 0),
('email_notification_footer', 2, 'This email was send to you by ##SITE-NAME##.', 'client', 'Notification email: footer en anglais.', 1, 0),
('email_notification_header', 1, 'Ce courriel vous a été envoyer par ##SITE-NAME##.', 'client', 'Notification email: header en français.', 1, 0),
('email_notification_header', 2, 'This email was send to you by ##SITE-NAME##.', 'client', 'Notification email: header en anglais.', 1, 0),
('label_number_to_show', 1, 'Nombre à afficher :', 'cible', '', 0, 0),
('label_number_to_show', 2, 'Number to show : ', 'cible', '', 0, 0),
('label_view', 1, 'Vue : ', 'cible', '', 0, 0),
('label_view', 2, 'View : ', 'cible', '', 0, 0),
('label_show_brief_text', 1, 'Afficher le texte bref', 'cible', '', 0, 0),
('label_show_brief_text', 2, 'Show brief text', 'cible', '', 0, 0),
('label_order_display', 1, 'Ordre d''affichage', 'cible', '', 0, 0),
('label_order_display', 2, 'Display view', 'cible', '', 0, 0),
('label_date_desc', 1, 'Dates décroissantes', 'cible', '', 0, 0),
('label_date_desc', 2, 'Date descending', 'cible', '', 0, 0),
('label_date_asc', 1, 'Dates croissantes', 'cible', '', 0, 0),
('label_date_asc', 2, 'Date ascending', 'cible', '', 0, 0),
('label_alpha_asc', 1, 'Titre alphabéthique', 'cible', '', 0, 0),
('label_alpha_asc', 2, 'Title alphabetical', 'cible', '', 0, 0),
('label_administrator_actives', 1, 'Groupes d''administrateurs actifs', 'cible', '', 0, 0),
('label_administrator_actives', 2, 'Actives administrators group', 'cible', '', 0, 0),
('label_static_text_edition', 1, 'Édition d''un texte statique', 'cible', '', 0, 0),
('label_static_text_edition', 2, 'Static text edition', 'cible', '', 0, 0),
('label_titre_page', 1, 'Titre de la page (balise H1)', 'cible', '', 0, 0),
('label_titre_page', 2, 'Page title (tag H1)', 'cible', '', 0, 0),
('label_index_already_exists', 1, 'Cet index existe déjà.', 'cible', '', 0, 0),
('label_index_already_exists', 2, 'This index already exists', 'cible', '', 0, 0),
('label_index_reserved', 1, 'Cet index est réservé.', 'cible', '', 0, 0),
('label_index_reserved', 2, 'This index already existed', 'cible', '', 0, 0),
('label_name_controller', 1, 'Nom du contrôleur (doit être unique)', 'cible', '', 0, 0),
('label_name_controller', 2, 'Name of the controller (must be unique)', 'cible', '', 0, 0),
('label_index_more_char', 1, 'L''index doit contenir plus de %min% charactères', 'cible', '', 0, 0),
('label_index_more_char', 2, 'The index must contains at least %min% characters', 'cible', '', 0, 0),
('label_index_less_char', 1, 'L''index doit contenir moins de %max% charactères', 'cible', '', 0, 0),
('label_index_less_char', 2, 'The index cannot be longer than %max% characters', 'cible', '', 0, 0),
('label_description_meta', 1, 'Description (meta)', 'cible', '', 0, 0),
('label_description_meta', 2, 'Description (meta)', 'cible', '', 0, 0),
('label_keywords_meta', 1, 'Mots-clés (meta)', 'cible', '', 0, 0),
('label_keywords_meta', 2, 'Keywords (meta)', 'cible', '', 0, 0),
('label_other_meta', 1, 'Autres (meta)', 'cible', '', 0, 0),
('label_other_meta', 2, 'Others (meta)', 'cible', '', 0, 0),
('label_layout_page', 1, 'Layout de la page', 'cible', '', 0, 0),
('label_layout_page', 2, 'Layout of the page', 'cible', '', 0, 0),
('label_model_page', 1, 'Modèle de la page', 'cible', '', 0, 0),
('label_model_page', 2, 'Model of the page', 'cible', '', 0, 0),
('label_only_character_allowed', 1, 'L''index peut contenir seulement des caractères alphabétiques (a-z) et numéric (0-9). Utiliser le caractère ''-'' pour séparer 2 mots', 'cible', '', 0, 0),
('label_only_character_allowed', 2, 'The index can contain only alphabetic characters (az) and numeric (0-9). Use the ''-'' character to separate 2 words', 'cible', '', 0, 0),
('label_gestion_contents', 1, 'Gestion du contenu des pages (cocher les pages dont l''administrateur peut faire la gestion du contenu)', 'cible', '', 0, 0),
('label_gestion_contents', 2, 'Management structure of the pages (check the page to allow the administrator to modify its contents)', 'cible', '', 0, 0),
('label_gestion_structure', 1, 'Gestion de la structure des pages (cocher les pages dont l''administrateur peut faire la gestion de la structure)', 'cible', '', 0, 0),
('label_gestion_structure', 2, 'Management structure of the pages (check the page to allow the administrator to modify its structure)', 'cible', '', 0, 0),
('label_category', 1, 'Catégorie : ', 'cible', '', 0, 0),
('label_category', 2, 'Category : ', 'cible', '', 0, 0),
('menu_footerTwo', 1, 'Menu bas de page (colonne 2)', 'cible', '', 0, 0),
('menu_footerTwo', 2, 'Footer menu (column 2)', 'cible', '', 0, 0),
('menu_footerThree', 1, 'Menu bas de page (colonne 3)', 'cible', '', 0, 0),
('menu_footerThree', 2, 'Footer menu (column 3)', 'cible', '', 0, 0),
('menu_footerFour', 1, 'Menu bas de page (colonne 4)', 'cible', '', 0, 0),
('menu_footerFour', 2, 'Footer menu (column 4)', 'cible', '', 0, 0),
('header_list_header_image_text', 1, 'Liste des images d''entête', 'cible', '', 0, 0),
('header_list_header_image_text', 2, 'List of header images', 'cible', '', 0, 0),
('header_list_header_image_description', 1, 'Cette page vous permet de consulter la liste des images d''entête.', 'cible', '', 0, 0),
('header_list_header_image_description', 2, 'This page lists the header images.', 'cible', '', 0, 0),
('header_edit_header_image_text', 1, 'Édition d''une image d''entête', 'cible', '', 0, 0),
('header_edit_header_image_text', 2, 'Header image edition', 'cible', '', 0, 0),
('header_edit_header_image_description', 1, 'Cette page vous permet de modifier une image d''entête', 'cible', '', 0, 0),
('header_edit_header_image_description', 2, 'This page allows the modification of an header image', 'cible', '', 0, 0),
('dashboard_administration_header_image_title', 1, 'Images', 'cible', '', 0, 0),
('dashboard_administration_header_image_title', 2, 'Images', 'cible', '', 0, 0),
('dashboard_administration_header_image', 1, 'Images d''entête', 'cible', '', 0, 0),
('dashboard_administration_header_image', 2, 'Header Images', 'cible', '', 0, 0),
('search_in_catalog', 1, 'Afficher la liste de prduits correspondants.', 'cible', '', 0, 0),
('search_in_catalog', 2, 'Display the corresponding products list. ', 'cible', '', 0, 0),
('search_no_result_text', 1, 'Aucun résultat trouvé pour ', 'client', '', 0, 0),
('search_no_result_text', 2, 'No result found for ', 'client', '', 0, 0),
('form_label_menu_title_style', 1, 'Style spécifique pour ce menu', 'cible', '', 0, 0),
('form_label_menu_title_style', 2, 'Specific style for this item', 'cible', '', 0, 0),
('form_module_name', 1, 'Formulaires', 'cible', '', 0, 0),
('form_module_name', 2, 'Form', 'cible', '', 0, 0),
('management_module_form_list', 1, 'Liste des formulaires', 'cible', '', 0, 0),
('management_module_form_list', 2, 'Form list', 'cible', '', 0, 0);

REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('dashboard_administration_utilities_references', 1, 'Données de références', 'cible', '', 0, 0),
('dashboard_administration_utilities_references', 2, 'Refernces data', 'cible', '', 0, 0),
('button_add_references', 1, 'Ajouter', 'cible', '', 0, 0),
('button_add_references', 2, 'Add', 'cible', '', 0, 0),
('form_reference_value_label', 1, 'Valeur', 'cible', '', 0, 0),
('form_reference_value_label', 2, 'Value', 'cible', '', 0, 0),
('form_reference_type_label', 1, 'Type', 'cible', '', 0, 0),
('form_reference_type_label', 2, 'Type', 'cible', '', 0, 0),
('form_reference_seq_label', 1, 'Séquence', 'cible', '', 0, 0),
('form_reference_seq_label', 2, 'Sequence', 'cible', '', 0, 0),
('header_list_references_text', 1, 'Liste des references', 'cible', '', 0, 0),
('header_list_references_text', 2, 'References list', 'cible', '', 0, 0),
('header_list_references_description', 1, 'Cette page permet de gérer la liste des references.', 'cible', '', 0, 0),
('header_list_references_description', 2, 'This page is to manage references', 'cible', '', 0, 0),
('header_add_references_text', 1, 'Ajouter', 'cible', '', 0, 0),
('header_add_references_text', 2, 'Add', 'cible', '', 0, 0),
('header_edit_references_text', 1, 'Modification', 'cible', '', 0, 0),
('header_edit_references_text', 2, 'Edit', 'cible', '', 0, 0),
('header_edit_references_description', 1, 'Cette page permet de modifier des informations de la référence sélectionnée.', 'cible', '', 0, 0),
('header_edit_references_description', 2, 'This page is to edit data of the selected reference.', 'cible', '', 0, 0),
('header_delete_references_text', 1, 'Suppression d''une référence', 'cible', '', 0, 0),
('header_delete_references_text', 2, 'Deletion of a reference', 'cible', '', 0, 0),
('header_add_references_description', 1, 'Renseigner les champs du formulaire.', 'cible', '', 0, 0),
('header_add_references_description', 2, 'Fill the form to add data.', 'cible', '', 0, 0),
('list_column_R_ID', 1, 'Id', 'cible', '', 0, 0),
('list_column_R_ID', 2, 'Id', 'cible', '', 0, 0),
('list_column_R_TypeRef', 1, 'Type', 'cible', '', 0, 0),
('list_column_R_TypeRef', 2, 'Type', 'cible', '', 0, 0),
('list_column_RI_Value', 1, 'Valeur', 'cible', '', 0, 0),
('list_column_RI_Value', 2, 'Value', 'cible', '', 0, 0),
('form_enum_subscrArg', 1, 'Abonnement', 'cible', '', 0, 0),
('form_enum_subscrArg', 2, 'Subscribe', 'cible', '', 0, 0),
('form_label_menu_show_item', '1', 'Afficher cet item du menu', 'cible', '', '0', '0'),
('form_label_menu_show_item', '2', 'Show this menu item', 'cible', '', '0', '0'),
('form_enum_unsubscrArg', 1, 'Raison désabonnement', 'cible', '', 0, 0),
('form_enum_unsubscrArg', 2, 'Unsubscription', 'cible', '', 0, 0),
('label_altFirstImage', '1', 'ALT de la première image', 'cible', '', '0', '0'),
('label_altFirstImage', '2', 'ALT of the first image', 'cible', '', '0', '0'),
('profile_tab_title_general', '1', 'Général', 'cible', '', '0', '0'),
('profile_tab_title_general', '2', 'General', 'cible', '', '0', '0'),
('profile_delete_alert_existing_profiles', '1', "Cet utilsateur possède ##NBPROFILE## profil(s) actif(s). <br />
La suppression de l'utilisateur est <strong>DÉFINITIVE</strong>. <strong>Toutes les données relatives aux profils seront perdues.</strong> <br /><br />
Profil(s) associé(s):<br />
##PROFILESLIST##<br />
Pour supprimer un profil en particulier, passez par l'édition des profil.", 'cible', '', '0', '20'),
('profile_delete_alert_existing_profiles', '2', "This user has ##NBPROFILE## active profile(s). <br />
The removal of the user is <strong>FINAL</strong>. <strong>All profile data will be lost.</strong> <br />
Releated profile(s):<br />
##PROFILESLIST##<br />
To delete a profile, in particular, go through the profile editing.", 'cible', '', '0', '20'),
('form_legend_infoPage', '1', 'Info page', 'cible', '', '0', '1'),
('form_legend_infoPage', '2', 'Page info', 'cible', '', '0', '1'),
('form_legend_blockData', '1', 'Texte', 'cible', '', '0', '1'),
('form_legend_blockData', '2', 'Text', 'cible', '', '0', '1'),
('list_column_GP_LastName', '1', 'Nom', 'cible', '', '0', '0'),
('list_column_GP_LastName', '2', 'Name', 'cible', '', '0', '0'),
('list_column_GP_FirstName', '1', 'Prénom', 'cible', '', '0', '0'),
('list_column_GP_FirstName', '2', 'Firstname', 'cible', '', '0', '0'),
('list_column_GP_Email', '1', 'Courriel', 'cible', '', '0', '0'),
('list_column_GP_Email', '2', 'Email', 'cible', '', '0', '0'),
('captcha_label', 1, "<br /><br />Pour des raisons de sécurité, veuillez entrer les caractères alphanumériques de l'image dans l'espace ci-dessous.", 'cible', '', 0, 0),
('captcha_label', 2, '<br /><br />For security reasons, please enter the alphanumeric <br />characters from the image into the space below.', 'cible', '', 0, 0);

REPLACE INTO `Static_Texts` (`ST_Identifier`, `ST_LangID`, `ST_Value`, `ST_Type`, `ST_Desc_backend`, `ST_Editable`, `ST_ModuleID`) VALUES
('form_label_select_city', 1, 'S&eacute;lectionnez une ville', 'cible', '', 0, 0),
('form_label_select_city', 2, 'Choose a city', 'cible', '', 0, 0),
('form_label_select_state', 1, 'S&eacute;lectionnez une province', 'cible', '', 0, 0),
('form_label_select_state', 2, 'Choose a province', 'cible', '', 0, 0),
('form_label_firstAddress', 1, 'Adresse', 'cible', '', 0, 0),
('form_label_firstAddress', 2, 'Address', 'cible', '', 0, 0),
('form_label_secondAddress', 1, 'Adresse', 'cible', '', 0, 0),
('form_label_secondAddress', 2, 'Address', 'cible', '', 0, 0),
('form_label_zipCode', 1, 'Code postal', 'cible', '', 0, 0),
('form_label_zipCode', 2, 'Zip code', 'cible', '', 0, 0),
('form_label_fax', 1, 'Fax', 'cible', '', 0, 0),
('form_label_fax', 2, 'Fax', 'cible', '', 0, 0),
('form_label_webSite', 1, 'Site internet', 'cible', '', 0, 0),
('form_label_webSite', 2, 'Website', 'cible', '', 0, 0),
('form_label_firstTel', 1, 'Téléphone 1', 'cible', '', 0, 0),
('form_label_firstTel', 2, 'Phone 1', 'cible', '', 0, 0),
('form_label_secondTel', 1, 'Téléphone 2', 'cible', '', 0, 0),
('form_label_secondTel', 2, 'Phone 2', 'cible', '', 0, 0),
('form_label_cityTxt', 1, "Ville ", 'cible', '', 0, 0),
('form_label_cityTxt', 2, 'City ', 'cible', '', 0, 0),
('dashboard_administration_video_description', '1', 'Liste de vidéos', 'cible', '', '0', 21),
('dashboard_administration_video_description', '2', 'List of videos', 'cible', '', '0', 21),
('list_column_V_Alias', '1', 'Alias', 'cible', '', '0', 21),
('list_column_V_Alias', '2', 'Alias', 'cible', '', '0', 21),
('list_column_VI_Description', '1', 'Description', 'cible', '', '0', 21),
('list_column_VI_Description', '2', 'Description', 'cible', '', '0', 21),
('header_list_video_text', '1', 'Liste des vidéos', 'cible', '', '0', 21),
('header_list_video_text', '2', 'List of video', 'cible', '', '0', 21),
('header_list_video_description', '1', 'Cliquez sur Ajouter une video pour
créer une nouvelle.<br />
Vous pouvez rechercher par mots-clés,
la liste des videos. Pour revenir à la liste complète,
cliquez sur Voir la liste complète. <br />
Vous pouvez modifier ou supprimer une
video. ', 'cible', '', '0', 21),
('header_list_video_description', '2', 'Cliquez sur Ajouter une video pour
créer une nouvelle.<br />
Vous pouvez rechercher par mots-clés,
la liste des videos. Pour revenir à la liste complète,
cliquez sur Voir la liste complète.<br />
Vous pouvez modifier ou supprimer une
video. ', 'cible', '', '0', 21),
('dashboard_administration_video', '1', 'Vidéos', 'cible', '', '0', 21),
('dashboard_administration_video', '2', 'Videos', 'cible', '', '0', 21),
('header_add_video_description', '1', 'Cette page vous permet d''ajouter une vidéo ', 'cible', '', '0', 21),
('header_add_video_description', '2', 'Add a video to the video library. ', 'cible', '', '0', 21),
('extranet_video_autoPlay', '1', 'Autoplay', 'cible', '', '0', 21),
('extranet_video_autoPlay', '2', 'Autoplay', 'cible', '', '0', 21),
('form_label_video_autoplay', '1', 'Autoplay', 'cible', '', '0', 21),
('form_label_video_autoplay', '2', 'Autoplay', 'cible', '', '0', 21),
('button_add_video', '1', 'Ajouter une vidéo', 'cible', '', '0', 21),
('button_add_video', '2', 'Add a video', 'cible', '', '0', 21),
('form_label_video_alias', '1', 'Alias', 'cible', '', '0', 21),
('form_label_video_alias', '2', 'Alias', 'cible', '', '0', 21),
('form_label_video_name', '1', 'Nom', 'cible', '', '0', 21),
('form_label_video_name', '2', 'Name', 'cible', '', '0', 21),
('form_label_video_width', '1', 'Largeur', 'cible', '', '0', 21),
('form_label_video_width', '2', 'Width', 'cible', '', '0', 21),
('form_label_video_height', '1', 'Hauteur', 'cible', '', '0', 21),
('form_label_video_height', '2', 'Height', 'cible', '', '0', 21),
('form_label_video_description', '1', 'Description', 'cible', '', '0', 21),
('form_label_video_description', '2', 'Description', 'cible', '', '0', 21),
('form_label_video_poster', '1', 'Poster', 'cible', '', '0', 21),
('form_label_video_poster', '2', 'Poster', 'cible', '', '0', 21),
('form_label_video_VI_MP4', '1', 'Vidéo MP4', 'cible', '', '0', 21),
('form_label_video_VI_MP4', '2', 'Video MP4', 'cible', '', '0', 21),
('form_label_video_VI_WEBM', '1', 'Vidéo WEBM', 'cible', '', '0', 21),
('form_label_video_VI_WEBM', '2', 'Video WEBM', 'cible', '', '0', 21),
('form_label_video_VI_OGG', '1', 'Vidéo OGG', 'cible', '', '0', 21),
('form_label_video_VI_OGG', '2', 'Video OGG', 'cible', '', '0', 21),
('header_delete_video_text', '1', 'Suppression d''une vidéo', 'cible', '', '0', 21),
('header_delete_video_text', '2', 'Delete a video', 'cible', '', '0', 21),
('header_edit_video_text', '1', 'Edition d''une vidéo', 'cible', '', '0', 21),
('header_edit_video_text', '2', 'Edit a video', 'cible', '', '0', 21),
('header_edit_video_description', '1', 'Cette page permet d''éditer une vidéo', 'cible', '', '0', 21),
('header_edit_video_description', '2', 'This page allows the edition of a video', 'cible', '', '0', 21),
('video_module_name', '1', 'Vidéos', 'cible', '', '0', 21),
('video_module_name', '2', 'Videos', 'cible', '', '0', 21),
('management_module_video_list', '1', 'Liste des vidéos', 'cible', '', '0', 21),
('management_module_video_list', '2', 'Videos'' list', 'cible', '', '0', 21),
('header_add_video_text', '1', 'Ajouter une vidéo', 'cible', '', '0', 21),
('header_add_video_text', '2', 'Add a video', 'cible', '', '0', 21);

-- --------------------------------------------------------

--
-- Table structure for table `Status`
--

CREATE TABLE IF NOT EXISTS `Status` (
  `S_ID` int(11) NOT NULL auto_increment,
  `S_Code` varchar(55) default NULL,
  PRIMARY KEY  (`S_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Status`
--

INSERT INTO `Status` (`S_ID`, `S_Code`) VALUES
(1, 'online'),
(2, 'offline');

-- --------------------------------------------------------

--
-- Table structure for table `TextData`
--

CREATE TABLE IF NOT EXISTS `TextData` (
  `TD_ID` bigint(20) NOT NULL auto_increment,
  `TD_BlockID` int(10) NOT NULL,
  `TD_LanguageID` int(10) NOT NULL,
  `TD_OnlineTitle` varchar(255) default NULL,
  `TD_OnlineText` text,
  `TD_DraftTitle` varchar(255) default NULL,
  `TD_DraftText` text,
  `TD_ToApprove` int(11) NOT NULL,
  PRIMARY KEY  (`TD_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `Videos`
--

DROP TABLE IF EXISTS `Videos`;
CREATE TABLE IF NOT EXISTS `Videos` (
  `V_ID` int(11) NOT NULL auto_increment,
  `V_Alias` varchar(255) NOT NULL,
  `V_Width` int(11) NOT NULL,
  `V_Height` int(11) NOT NULL,
  `V_Autoplay` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`V_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `VideosIndex`
--

DROP TABLE IF EXISTS `VideosIndex`;
CREATE TABLE IF NOT EXISTS `VideosIndex` (
  `VI_ID` int(11) NOT NULL auto_increment,
  `VI_LanguageID` int(11) NOT NULL,
  `VI_Description` text NOT NULL,
  `VI_Name` varchar(255) NOT NULL,
  `VI_Poster` varchar(255) NOT NULL,
  `VI_MP4` varchar(255) NOT NULL,
  `VI_WEBM` varchar(255) NOT NULL,
  `VI_OGG` varchar(255) NOT NULL,
  PRIMARY KEY  (`VI_ID`,`VI_LanguageID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Views`
--

CREATE TABLE IF NOT EXISTS `Views` (
  `V_ID` int(11) NOT NULL auto_increment,
  `V_Name` varchar(255) NOT NULL,
  `V_ZoneCount` int(11) NOT NULL default '1',
  `V_Path` varchar(255) NOT NULL,
  `V_Image` varchar(255) NOT NULL,
  PRIMARY KEY  (`V_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `Views`
--

INSERT INTO `Views` (`V_ID`, `V_Name`, `V_ZoneCount`, `V_Path`, `V_Image`) VALUES
(1, 'Home', 2, 'template/home.phtml', 'image.png'),
(2, 'Common', 2, 'template/common.phtml', 'image.png');