<?php
/**
 * Cible Solutions - Vêtements SP
 * Retailer management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: ProvinceObject.php 826 2012-02-01 04:15:13Z ssoares $
 */

/**
 * Manage data for states
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class ProvinceObject extends DataObject
{
    protected $_dataClass   = 'ProvinceData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = 'ProvinceIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'PI_LanguageID';
    protected $_indexColumns    = array();

//    protected $_indexSelectColumns = array(
//        array('Nom_FR' => 'PI_Nom'),
//        array('Nom_EN' => 'PI_Nom')
//    );
}