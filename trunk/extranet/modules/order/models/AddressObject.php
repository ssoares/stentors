<?php
/**
 * Module Utilities
 * Management of the references data.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: AddressObject.php 422 2011-03-24 03:25:10Z ssoares $id
 */

/**
 * Manage data from references table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_References
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: AddressObject.php 422 2011-03-24 03:25:10Z ssoares $id
 */
class AddressObject extends DataObject
{
    protected $_dataClass   = 'AddressData';
    protected $_indexClass      = 'AddressIndex';
    protected $_indexLanguageId = 'AI_LanguageID';
//    protected $_constraint      = '';
  
/*
    public function addressCollection($addID,$langId){    
        $select = $this->getAll($langId, false);
        $select->where('AI_AddressId = ?', $addID);
        $addresses =$this->_db->fetchAll($select);
        return $addresses;
    }*/




}