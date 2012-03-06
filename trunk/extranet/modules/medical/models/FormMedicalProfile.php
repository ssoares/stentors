<?php

/**
 * Module Users
 * Data management for the registered users.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Users
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormMembersProfile.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Form to manage specific data.
 * Fields will change for each project.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Users
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormMembersProfile.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class FormMedicalProfile extends Cible_Form_GenerateForm
{

    public function __construct($options = null)
    {
        if (isset($options['isXmlHttpRequest']) && $options['isXmlHttpRequest'])
            $this->_disabledDefaultActions = true;

        $this->_disabledLangSwitcher = true;
        if (!empty($options['object']))
        {
            $this->_object = $options['object'];
            unset($options['object']);
        }
        parent::__construct($options);

        $this->addDisplayGroup(
            array(
                'MR_AssuMaladie',
                'MR_ExpiracyDate'),
            'assu');
        $this->getDisplayGroup('assu')
            ->setLegend('Assurance maladie')
            ->setAttrib('class','infosFieldset assu')
            ->removeDecorator('DtDdWrapper');

        $this->addDisplayGroup(
            array(
                'MR_HasTravelInsur',
                'MR_TravelInduranceName',
                'MR_TravelIndurancePhone',
                'MR_TravelInduranceNum',
                'MR_TravelInduranceExpiracy',
                ),
            'travel');
        $this->getDisplayGroup('travel')
            ->setLegend('Assurance hors Québec')
            ->setAttrib('class','infosFieldset travel')
            ->removeDecorator('DtDdWrapper');

        $this->addDisplayGroup(
            array(
                'MR_EmergyPhone',
                'MR_OtherHouse',
                'MR_OtherWork',
                'MR_OtherCell'
                ),
            'emergency');
        $this->getDisplayGroup('emergency')
            ->setLegend("En cas d'urgence")
            ->setAttrib('class','infosFieldset emergency')
            ->removeDecorator('DtDdWrapper');

        $this->addDisplayGroup(
            array(
                'MR_Allergy',
                'MR_AllergyOther',
                'MR_AllergyMedic',
                'MR_AllergyMedicName',
                'MR_AllergyMedicQty',
                'MR_AllowEmergencyCares',
                ),
            'allergy');
        $this->getDisplayGroup('allergy')
            ->setLegend("Allergies")
            ->setAttrib('class','infosFieldset allergy')
            ->removeDecorator('DtDdWrapper');

        $this->addDisplayGroup(array('MR_Diseases'),'diseases');
        $this->getDisplayGroup('diseases')
            ->setLegend("Maladies")
            ->setAttrib('class','infosFieldset diseases')
            ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(
            array(
                'MR_HasGlasses',
                'MR_HasLens',
                'MR_Fracture',
                'MR_Chirurgie',
                'MR_Specific',
                'MR_Notes',
                ),
            'others');
        $this->getDisplayGroup('others')
            ->setLegend("Informations complémentaires")
            ->setAttrib('class','infosFieldset others')
            ->removeDecorator('DtDdWrapper');
    }
}