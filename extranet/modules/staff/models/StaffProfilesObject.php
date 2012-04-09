<?php
/**
 * Staff Profile data
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_StaffProfilesObject
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: StaffProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Manages Staff Profile data.
 *
 * @category  Cible
 * @package   Cible_StaffProfiles
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: StaffProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class StaffProfilesObject extends DataObject
{

    protected $_dataClass   = 'StaffProfilesData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'SP_GenericProfileId';
    protected $_formDataName    = 'staffForm';
    protected $_addressField    = 'SP_AddressId';

    public function getFormDataName()
    {
        return $this->_formDataName;
    }

    public function getAddressField()
    {
        return $this->_addressField;
    }

    public function save($id, $data, $langId)
    {
        $addrId = 0;
        if (isset($data[$this->_formDataName]))
        {
            $oAdress = new AddressObject();
            $addr = $data[$this->_formDataName];
//            $addrShip = $data['addressShipping'];
        }

        if (!empty($addr))
        {
            $addrId = $oAdress->save($data[$this->_addressField], $addr, $langId);
        }
        else
        {
            $addrId = $oAdress->insert($data[$this->_formDataName], $langId);
        }
        if (empty($data[$this->_addressField]))
            $data[$this->_addressField] = $addrId;

        parent::save($id, $data, $langId);
    }

    public function findData($filters = array())
    {
        $addr = array();
//        $shipAddr = array();
        $oAddress = new AddressObject();
        $langId   = Zend_Registry::get('languageID');
        $data = parent::findData($filters);
        if (!empty($data))
        {
            $data = $data[0];
            $addrId = $data[$this->_addressField];

            if (!empty($addrId))
            {
                $addr = $oAddress->getAll($langId, true, $addrId);
                $addr = $addr[0];
                $addr[$this->_addressField] = $addrId;
            }

            $data[$this->_formDataName] = $addr;
//            $data['addressShipping'] = $shipAddr;
        }

        return $data;
    }

    public function getParentDetails($id, $filters = array())
    {
        $data = array();
        $parentData = $this->findData($filters);
        if ($id > 0 && !empty($parentData))
        {
            $oGeneric = new GenericProfilesObject();
            $data = $oGeneric->populate($id, 1);

            $role = $this->_parentsProfileSrc();
            $roleId = $parentData['PP_Role'];

            $roleLabel = $role[$roleId];
            $parentData['RoleLabel'] = $roleLabel;
            $data = array_merge($parentData, $data);
        }
        return $data;
    }

}