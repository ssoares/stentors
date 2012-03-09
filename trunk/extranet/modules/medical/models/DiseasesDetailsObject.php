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
class DiseasesDetailsObject extends DataObject
{

    protected $_dataClass   = 'DiseasesDetailsData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'DD_GenericProfileId';

    public function insert($data, $langId)
    {

        $id = parent::insert($data, $langId);

        return $id;
    }

    public function save($id, $data, $langId)
    {
        parent::save($id, $data, $langId);
    }

    public function findData($filters = array())
    {
        $addr = array();
        $langId   = Zend_Registry::get('languageID');
        $data = parent::findData($filters);
        if (!empty($data))
        {
            $data = $data[0];
            $data['DD_TypeMedic'] = explode(',', $data['DD_TypeMedic']);
        }

        return $data;
    }

    public function _typeMedicSrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $refs = $oRef->getRefByType('medic');

        foreach ($refs as $ref)
        {
            $src[$ref['R_ID']] = $ref['RI_Value'];
        }

        return $src;
    }
}