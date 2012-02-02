<?php
/**
 * Module Utilities
 * Management of the references data.
 *
 * @category  Application_Module
 * @package   Application_Module_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: AddressObject.php 435 2011-03-28 03:57:25Z ssoares $id
 */

/**
 * Manage data from references table.
 *
 * @category  Application_Module
 * @package   Application_Module_References
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: AddressObject.php 435 2011-03-28 03:57:25Z ssoares $id
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

    /**
     * Return the state id according of the selected address
     * @param int $id
     *
     * @return int
     */
    public function getStateId($id)
    {
        $stateId = 0;
        
        $data = $this->getAll(null, true, $id);

        $stateId = $data[0]['A_StateId'];

        Return $stateId;
    }


}