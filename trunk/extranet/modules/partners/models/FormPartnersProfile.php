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
class FormPartnersProfile extends Cible_Form_GenerateForm
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
        $this->setAttrib('id', 'partnersInfo');

        $subForm = new Cible_Form_SubForm();
        $subForm->setName('partnersForm')
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
        $this->addSubForm($subForm, 'partnersForm');
        $this->getSubForm('partnersForm')->getElement('selectedState')->getDecorator('HtmlTag')
                ->setOption('style', 'margin: 0px;');

        $years = new Zend_Form_Element_Text('YP_Year');
        $years->setLabel(
                $this->getView()->getCibleText('form_label_YP_Year'))
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
        ->setDecorators(
                array(
                    'ViewHelper',
                    array('label', array('placement' => 'prepend')),
                    array(
                        'Errors',
                        array('placement' => 'append')
                    ),
                    array(
                        array('row' => 'HtmlTag'),
                        array(
                            'tag'   => 'dd',
                            'class' => 'form_title_inline',
                            )
                        ),
                    )
                )
        ->setAttrib('class','stdTextInput');

        $this->addElement($years);

        $this->addDisplayGroup(
            array(
                'PP_Function',
                'PP_Company',
                'PP_Notes',
                'YP_Year'
                ),
            'partData');
        $this->getDisplayGroup('partData')
            ->setLegend('Informations')
            ->setAttrib('class','infosFieldset')
            ->removeDecorator('DtDdWrapper');

    }

//    public function populate(array $values)
//    {
//        $baseDir = $this->getView()->BaseUrl();
//        $content = '';
//        $isSaved = false;
//
//
//    }
}