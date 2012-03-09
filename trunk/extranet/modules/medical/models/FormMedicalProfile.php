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
        $id = new Zend_Form_Element_Hidden('MR_ID');
        $id->removeDecorator('Label');
        $id->removeDecorator('DtDdWrapper');
        $this->addElement($id);
        $this->addDisplayGroup(
            array(
            'MR_AssuMaladie',
            'MR_ExpiracyDate'), 'assu');
        $assu = $this->getDisplayGroup('assu');

        $assu->setLegend('Assurance maladie')
            ->setAttrib('class', 'infosFieldset assu')
            ->removeDecorator('DtDdWrapper');
        $assu->setOrder(1);
        $this->addDisplayGroup(
            array(
            'MR_HasTravelInsur',
            'MR_TravelInduranceName',
            'MR_TravelIndurancePhone',
            'MR_TravelInduranceNum',
            'MR_TravelInduranceExpiracy',
            ), 'travel');
        $travel = $this->getDisplayGroup('travel');

        $travel->setLegend('Assurance hors Québec')
            ->setAttrib('class', 'infosFieldset travel')
            ->removeDecorator('DtDdWrapper');
            $travel->setOrder(2);

        $this->addDisplayGroup(
            array(
            'MR_EmergyPhone',
            'MR_OtherHouse',
            'MR_OtherWork',
            'MR_OtherCell'
            ), 'emergency');
        $emergency = $this->getDisplayGroup('emergency');
        $emergency->setLegend("En cas d'urgence")
            ->setAttrib('class', 'infosFieldset emergency')
            ->removeDecorator('DtDdWrapper');
        $emergency->setOrder(3);

        $this->addDisplayGroup(
            array(
            'MR_Allergy',
            'MR_AllergyOther',
            'MR_AllergyMedic',
            'MR_AllergyMedicName',
            'MR_AllergyMedicQty',
            'MR_AllowEmergencyCares',
            ), 'allergy');
        $allergy = $this->getDisplayGroup('allergy');
        $allergy->setLegend("Allergies")
            ->setAttrib('class', 'infosFieldset allergy')
            ->removeDecorator('DtDdWrapper');
            $allergy->setOrder(4);

        $this->addDisplayGroup(array('MR_Diseases'), 'diseases');
        $dieases = $this->getDisplayGroup('diseases');
        $dieases->setLegend("Maladies")
            ->setAttrib('class', 'infosFieldset diseases')
            ->removeDecorator('DtDdWrapper');
        $dieases->setOrder(5);
        $this->addDisplayGroup(
            array(
            'MR_HasGlasses',
            'MR_HasLens',
            'MR_Fracture',
            'MR_Chirurgie',
            'MR_Specific',
            'MR_Notes',
            ), 'others');
        $other = $this->getDisplayGroup('others');
        $other->setLegend("Informations complémentaires")
            ->setAttrib('class', 'infosFieldset others clearBoth')
            ->removeDecorator('DtDdWrapper');
        $other->setOrder(99);
    }

    public function populate(array $values)
    {
        $diseasesList = $this->_object->_diseasesSrc();
        $oDiseases = new DiseasesDetailsObject();
        $dData =$oDiseases->findData(array($oDiseases->getForeignKey() => $values[$this->_object->getForeignKey()]));
        $fieldSet = $this->getDisplayGroup('diseases');
        $i = 6;
        foreach ($diseasesList as $id => $disease)
        {
            $tmpForm = new FormDiseasesDetails(
                array(
                    'object' => $oDiseases,
                    'isXmlHttpRequest' => true
                    )
                );
            if (in_array($id, $values['MR_Diseases']))
            {
                $oDiseases->setFilters(
                    array(
                        $oDiseases->getForeignKey() => $values[$this->_object->getForeignKey()],
                        'DD_MedicalRecordId' => $id
                    )
                );
                $data = $oDiseases->getAll();
                $data[0]['DD_TypeMedic'] = explode(',', $data[0]['DD_TypeMedic']);

                $tmpForm->populate($data[0]);
            }
            $elems = $tmpForm->getElements();
            $test = new Zend_Form_SubForm();
            $test->setDisableLoadDefaultDecorators(true);
            $test->addElements($elems);
            $test->removeDecorator('DtDdWrapper');
            $test->setLegend('Détails pour ' . $disease);
            $test->setAttrib('class', 'infosFieldsetParent fieldsetDiseaseDetails');
            $test->setOrder($i++);
            $this->addSubForm($test, 'dd_'.$id);
        }

        parent::populate($values);
    }

}