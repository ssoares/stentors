<?php

/**
 * Module Utilities
 * Data management for the registered users.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Utilities
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
 * @package   Extranet_Module_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormMembersProfile.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class FormReports extends Cible_Form_GenerateForm
{

    public function __construct($options = null)
    {
//        $formDataName = 'reportsForm';
        $this->_disabledDefaultActions = false;
        $this->_addSubmitSaveClose = true;
        $this->_disabledLangSwitcher = true;
        if (!empty($options['object']))
        {
            $this->_object = $options['object'];
            unset($options['object']);
        }
        parent::__construct($options);
        $this->setAttrib('id', 'reports');


        $this->addDisplayGroup(
            array('RE_Label'),
            'reportsData');
        $this->getDisplayGroup('reportsData')
//            ->setLegend('Informations')
            ->setAttrib('class','infosFieldset')
            ->removeDecorator('DtDdWrapper');

    }

//    public function populate(array $values)
//    {
//        $baseDir = $this->getView()->BaseUrl();
//        $content = '';
//        $isSaved = false;
//
//    }
}