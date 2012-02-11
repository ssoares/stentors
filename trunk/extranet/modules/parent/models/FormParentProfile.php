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
class FormParentProfile extends Cible_Form_GenerateForm
{

    public function __construct($options = null)
    {
        $this->_disabledDefaultActions = false;
        $this->_disabledLangSwitcher = true;
        if (!empty($options['object']))
        {
            $this->_object = $options['object'];
            unset($options['object']);
        }
        parent::__construct($options);

         $this->addDisplayGroup(
            array(
                'PP_TaxReceipt',
                'PP_EmploiTps',
                'PP_Role',
                'PP_Notes'),
            'data');
        $this->getDisplayGroup('data')
            ->setLegend('Infornations')
            ->setAttrib('class','infosFieldsetParent')
            ->removeDecorator('DtDdWrapper');

        $subForm = new Cible_Form_SubForm();
        $subForm->setName('parentForm')
            ->removeDecorator('DtDdWrapper');
        $subForm->setLegend('Adresse');

        $address = new Cible_View_Helper_FormAddress($subForm);

        $address->enableFields(
            array(
                'firstAddress'  => false,
                'secondAddress' => false,
                'cityTxt'       => false,
                'zipCode'       => false,
                'state'         => false,
                'country'       => false,
                'firstTel'      => false,
                'secondTel'     => false
                )
            );

        $address->formAddress();
        $this->addSubForm($subForm, 'parentForm');
    }
}