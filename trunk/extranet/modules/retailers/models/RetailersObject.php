<?php
/**
 * Cible Solutions 
 * Retailer management.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: RetailersObject.php 422 2011-03-24 03:25:10Z ssoares $
 */

/**
 * Manage data for cities
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class RetailersObject extends DataObject
{
    protected $_dataClass   = 'RetailersData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();

    protected $_indexSelectColumns = array();

    public function getRetailerInfos($memberId, $langId = 0)
    {
        if (empty($langId))
            Zend_Registry::get('defaultEditLanguage');
            
        $select = $this->getAll($langId, false);
        
        $select->where('R_GenericProfileId = ?', $memberId);
        
        $tmpData = $this->_db->fetchRow($select);
        
        return $tmpData;

    }
}