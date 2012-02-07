<?php
/**
 * Member Profile data
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_MemberProfilesObject
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: MemberProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Manages Member Profile data.
 *
 * @category  Cible
 * @package   Cible_MemberProfiles
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: MemberProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class ParentProfilesObject extends DataObject
{

    protected $_dataClass   = 'ParentProfilesData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'PP_GenericProfileId';

    public function save($id, $data, $langId)
    {
        $billId = 0;
        $shipId = 0;
        if (isset($data['addressFact']))
        {
            $oAdress = new AddressObject();
            $addrBill = $data['addressFact'];
            $addrShip = $data['addressShipping'];
        }
        if (!empty($addrBill))
        {
            $billId = $oAdress->save($addrBill['MP_BillingAddrId'], $addrBill, $langId);

            if ($addrShip['duplicate'] == 1)
            {
                $addrBill['A_Duplicate'] = $billId;
                $shipId = $oAdress->save($addrShip['MP_ShippingAddrId'], $addrBill, $langId);
            }
            else
            {
                $addrShip['A_Duplicate'] = 0;
                $shipId = $oAdress->save($addrShip['MP_ShippingAddrId'], $addrShip, $langId);
            }
            $data['MP_BillingAddrId'] = $billId;
            $data['MP_ShippingAddrId'] = $shipId;
        }

        parent::save($id, $data, $langId);
    }

    public function findData($filters = array())
    {
        $billAddr = array();
        $shipAddr = array();
        $oAddress = new AddressObject();
        $langId   = Zend_Registry::get('languageID');
        $data = parent::findData($filters);
        if (!empty($data))
        {
            $data = $data[0];
            $billId = $data['PP_AddressId'];

            if (!empty($billId))
            {
                $billAddr = $oAddress->getAll($langId, true, $billId);
                $billAddr = $billAddr[0];
                $billAddr['PP_AddressId'] = $billId;
            }

            $data['addressFact'] = $billAddr;
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
    public function _parentsProfileSrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $roles = $oRef->getRefByType('role');

        foreach ($roles as $role)
        {
            $src[$role['R_ID']] = $role['RI_Value'];
        }

        return $src;
    }

    public function _listRespSrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $roles = $oRef->getRefByType('garde');

        foreach ($roles as $role)
        {
            $src[$role['R_ID']] = $role['RI_Value'];
        }

        return $src;
    }
}