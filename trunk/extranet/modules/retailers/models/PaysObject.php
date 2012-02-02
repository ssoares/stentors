<?php
/**
 * Cible Solutions - Vêtements SP
 * Retailer management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: PaysObject.php 422 2011-03-24 03:25:10Z ssoares $
 */

/**
 * Manage data for cities
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class PaysObject extends DataObject
{
    protected $_dataClass   = 'PaysData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = 'PaysIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'PI_LanguageID';
//    protected $_indexColumns    = array();

    protected $_indexSelectColumns = array();
}