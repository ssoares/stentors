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
class MemberProfilesObject extends DataObject
{

    protected $_dataClass   = 'MemberProfilesData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'MP_GenericProfileId';

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
        $oYears = new YearsParticipateObject();
        $oYears->manageData($id, $data['MP_YearsParticipate']);
        $data['MP_BirthDate'] = $data['MP_BirthDateDt'];
        $years = $this->calculateAge($data['MP_BirthDate']);
        $data['MP_Age'] = $years;
        $data['MP_PassportExpiracyDate'] = $data['MP_PassportExpiracyDateDt'];
        if ($data['MP_BirthDate'] != $data['MP_PassportBirthDate'])
            $data['MP_PassportBirthDate'] = $data['MP_BirthDate'];

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
            $first  = $data['MP_FirstParent'];
            $second = $data['MP_SecondParent'];
            $data['MP_BirthDateDt'] = $data['MP_BirthDate'];
            $oParent = new ParentProfilesObject();
            $firstPar = $oParent->getParentDetails($first, array('PP_GenericProfileId' => $first));
            $secPar   = $oParent->getParentDetails($second, array('PP_GenericProfileId' => $second));

            if (!empty($firstPar))
            {
                $firstLink = array(
                    $firstPar['RoleLabel'],
                    $firstPar['GP_MemberID'],
                    $firstPar['GP_FirstName'],
                    $firstPar['GP_LastName'],
                    $firstPar['PP_TaxReceipt']);
                $data['firstP'] = $firstLink;
            }
            if (!empty($secPar))
            {
                $secLink = array(
                    $secPar['RoleLabel'],
                    $secPar['GP_MemberID'],
                    $secPar['GP_FirstName'],
                    $secPar['GP_LastName'],
                    $secPar['PP_TaxReceipt']);
                $data['secP'] = $secLink;
            }
//            $billId = $data['MP_BillingAddrId'];
//            $shipId = $data['MP_ShippingAddrId'];
//
//            if (!empty($shipId))
//            {
//                $shipAddr = $oAddress->getAll($langId, true, $shipId);
//                $shipAddr = $shipAddr[0];
//                $shipAddr['MP_ShippingAddrId'] = $shipId;
//            }
//
//            if (!empty($billId))
//            {
//                $billAddr = $oAddress->getAll($langId, true, $billId);
//                $billAddr = $billAddr[0];
//                $billAddr['MP_BillingAddrId'] = $billId;
//            }
//
//            if (isset($shipAddr['A_Duplicate']) && !$shipAddr['A_Duplicate'])
//                $shipAddr['duplicate'] = 0;
//
//            $data['addressFact'] = $billAddr;
//            $data['addressShipping'] = $shipAddr;
            $years = 0;
            if(isset($data['MP_BirthDate']))
                $years = $this->calculateAge($data['MP_BirthDate']);

            $data['MP_Age'] = $years;
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
    public function _sectionSrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $roles = $oRef->getRefByType('section');

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

    public function _memberCategorySrc($meta = array())
    {
        $src = array();
        $oRef = new ReferencesObject();
        $values = $oRef->getRefByType('memberCategory');

        foreach ($values as $value)
        {
            $src[$value['R_ID']] = $value['RI_Value'];
        }

        return $src;
    }

    public function calculateAge($birthDate)
    {
        Zend_Date::setOptions(array('format_type' => 'php'));
        $today = new Zend_Date();
        $dateOfBirth = new Zend_Date($birthDate);
        $age = $today->toString('Y') - $dateOfBirth->toString('Y');
        if ($today->toString('z') < $dateOfBirth->toString('z')) {
            $age--;
        }

//        if (empty($birthDate))
//            return null;
//
//        list($birthYear,$birthMonth,$birthDay) = explode("-", $birthDate);
//        $yearDiff = date("Y") - $birthYear;
//        $monthDiff = date("m") - $birthMonth;
//        $dayDiff = date("d") - $birthDay;
//
//        // If the birthday has not occured this year
//        if ($monthDiff == 0)
//            if($dayDiff < 0)
//                $yearDiff--;
//
//        if ($monthDiff < 0)
//                $yearDiff--;

        return $age;
    }
}