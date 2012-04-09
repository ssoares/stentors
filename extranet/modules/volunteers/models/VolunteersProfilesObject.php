<?php
/**
 * Volunteers Profile data
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_VolunteersProfilesObject
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: VolunteersProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
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
class VolunteersProfilesObject extends DataObject
{

    protected $_dataClass   = 'VolunteersProfilesData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'VP_GenericProfileId';

    public function save($id, $data, $langId)
    {
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
//            $addrId = $data['PP_AddressId'];
//
//            if (!empty($addrId))
//            {
//                $addr = $oAddress->getAll($langId, true, $addrId);
//                $addr = $addr[0];
//                $addr['PP_AddressId'] = $addrId;
//            }
//
//            $data['parentForm'] = $addr;
//            $data['addressShipping'] = $shipAddr;
        }

        return $data;
    }

    public function getVolunteersDetails($id, $filters = array())
    {
        $data = array();
        $profileData = $this->findData($filters);
        if ($id > 0 && !empty($profileData))
        {
            $oGeneric = new GenericProfilesObject();
            $data = $oGeneric->populate($id, 1);

            $role = $this->_jobsListSrc();
            $roleId = $profileData['VP_Job'];

            $roleLabel = $role[$roleId];
            $profileData['RoleLabel'] = $roleLabel;
            $data = array_merge($profileData, $data);
        }
        return $data;
    }
    public function _jobsListSrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $roles = $oRef->getRefByType('jobs');

        $src[0] = Cible_Translation::getCibleText('form_select_default_label');
        foreach ($roles as $role)
        {
            $src[$role['R_ID']] = $role['RI_Value'];
        }
        return $src;
    }

}