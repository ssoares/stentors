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
 * @version   $Id: AddressObject.php 801 2012-01-23 04:46:26Z ssoares $id
 */

/**
 * Manage data from references table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_References
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: AddressObject.php 801 2012-01-23 04:46:26Z ssoares $id
 */
class AddressObject extends DataObject
{

    protected $_dataClass = 'AddressData';
    protected $_indexClass = 'AddressIndex';
    protected $_indexLanguageId = 'AI_LanguageID';

//    protected $_constraint      = '';

    /*
      public function addressCollection($addID,$langId){
      $select = $this->getAll($langId, false);
      $select->where('AI_AddressId = ?', $addID);
      $addresses =$this->_db->fetchAll($select);
      return $addresses;
      } */

    public function save($id, $data, $langId)
    {
        $addrId = $id; 
        if (empty($id))
            $addrId = parent::insert($data, $langId);
        else
            parent::save($id, $data, $langId);

        return $addrId;
    }

}