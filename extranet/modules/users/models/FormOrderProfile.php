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
 * @version   $Id: FormOrderProfile.php 805 2012-01-23 20:35:15Z ssoares $id
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
 * @version   $Id: FormOrderProfile.php 805 2012-01-23 20:35:15Z ssoares $id
 */
class FormOrderProfile extends Cible_Form
{

    public function __construct($options = null)
    {
//        $this->_disabledDefaultActions = true;
//        if (isset($options['object']))
//            $this->_object = $options['object'];

        unset($options['object']);
        parent::__construct($options);
        $this->setAttrib('id', 'orders');
        //  Status of the customer to access to the cart and order process
        $status = new Zend_Form_Element_Select('MP_Status');
        $status->setLabel($this->getView()->getCibleText('form_label_account_status'));
        $statusList = array(
            '-1' => 'Désactivé',
            '0' => 'Email non validé',
            '1' => 'À valider',
            '2' => 'Activé'
        );
        $status->addMultiOptions($statusList);
        $this->addElement($status);

        // Company name
        $company = new Zend_Form_Element_Text('MP_CompanyName');
        $company->setLabel($this->getView()->getCibleText('form_label_company'))
            ->setRequired(false)
//                ->setOrder()
            ->setAttribs(array('class' => 'stdTextInput'));

        $this->addElement($company);

        // Billing address
        $addressFacturationSub = new Cible_Form_SubForm();
        $addressFacturationSub->setName('addressFact')
            ->removeDecorator('DtDdWrapper');
        $addressFacturationSub->setLegend($this->getView()->getCibleText('form_account_subform_addBilling_legend'));
        $addressFacturationSub->setAttrib('class', 'addresseBillingClass subFormClass');
        $billingAddr = new Cible_View_Helper_FormAddress($addressFacturationSub);
        $billingAddr->enableFields(
                array(
                    'firstAddress',
                    'secondAddress',
                    'state',
                    'cityTxt',
                    'zipCode',
                    'country',
                    'firstTel',
                    'seconfTel',
                    'fax'
                    )
                );

        $billingAddr->formAddress();

        $addrBill = new Zend_Form_Element_Hidden('MP_BillingAddrId');
        $addrBill->removeDecorator('label');
        $addressFacturationSub->addElement($addrBill);

        $this->addSubForm($addressFacturationSub, 'addressFact');

        /* delivery address */
        $addrShip = new Zend_Form_Element_Hidden('MP_ShippingAddrId');
        $addrShip->removeDecorator('label');

        $addressShippingSub = new Cible_Form_SubForm();
        $addressShippingSub->setName('addressShipping')
            ->removeDecorator('DtDdWrapper');;
        $addressShippingSub->setLegend($this->getView()->getCibleText('form_account_subform_addShipping_legend'));
        $addressShippingSub->setAttrib('class', 'addresseShippingClass subFormClass');

        $shipAddr = new Cible_View_Helper_FormAddress($addressShippingSub);
        $shipAddr->duplicateAddress($addressShippingSub);
        $shipAddr->enableFields(
            array(
                'firstAddress',
                'secondAddress',
                'state',
                'cityTxt',
                'zipCode',
                'country',
                'firstTel',
                'seconfTel',
                'fax'
                )
            );

        $shipAddr->formAddress();

        $addressShippingSub->addElement($addrShip);
        $this->addSubForm($addressShippingSub,'addressShipping');

        $this->addSubForm($addressShippingSub, 'addressShipping');

    }

}