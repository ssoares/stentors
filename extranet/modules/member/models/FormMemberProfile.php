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
class FormMemberProfile extends Cible_Form_GenerateForm
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
    }

    public function populate(array $values)
    {
        $baseDir = $this->getView()->BaseUrl();
        $content = '';
        parent::populate($values);

        if (!empty($values['firstP']))
        {
            $subject = '##ROLE## : ' . $this->getView()->link('##HREF##', '##FNAME## ##LNAME##');
            $href = $baseDir . '/users/index/general/actionKey/edit/id/' . $values['firstP']['1'];
            $values['firstP']['1'] = $href;
            $content = str_replace(array('##ROLE##', '##HREF##', '##FNAME##', '##LNAME##'), $values['firstP'], $subject);

        }
        if (!empty($values['secP']))
        {
            $subject = '##ROLE## : ' . $this->getView()->link('##HREF##', '##FNAME## ##LNAME##');
            $href = $baseDir . '/users/index/general/actionKey/edit/id/' . $values['secP']['1'];
            $values['secP']['1'] = $href;
            $content .= '<br />';
            $content .= str_replace(array('##ROLE##', '##HREF##', '##FNAME##', '##LNAME##'), $values['secP'], $subject);

        }
            $firstP = new Cible_Form_Element_Html(
                        'parents',
                        array(
                            'value' => $content
                        )
            );
            $firstP->setDecorators(
                    array(
                        'ViewHelper',
                        array('label', array('placement' => 'prepend')),
                        array(
                            array('row' => 'HtmlTag'),
                            array(
                                'tag' => 'dd',
                                'class' => 'form_title_inline left')
                        ),
                    )
            );
//            $nextElemPos = $this->getElement('MP_AgreePhotos')->getOrder();
            $firstP->setOrder(12);
            $this->addElement($firstP);
    }
}