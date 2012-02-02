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
 * @version   $Id: FormNewsletterProfile.php 748 2012-01-04 22:06:29Z ssoares $id
 */

/**
 * Form to manage the newsletter registration.
 * Allows to define which newsletter the user will recieve.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Users
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormNewsletterProfile.php 748 2012-01-04 22:06:29Z ssoares $id
 */
class FormNewsletterProfile extends Cible_Form
{

    public function __construct($options = null)
    {
        $this->_disabledDefaultActions = false;
        unset($options['object']);

        parent::__construct($options);

        $label = new Cible_Form_Element_Html(
            'txtAddToNewsletter',
            array(
                'value'=>$this->getView()->getCibleText('profile_addto_newletter_label')
            )
        );
        $label->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'h2')),
        ));


        $newsletterCategories = $this->getView()->GetAllNewsletterCategories();
        $newsletterCategories = $newsletterCategories->toArray();

        foreach ($newsletterCategories as $cat)
            $catLst[$cat['C_ID']] = $cat['CI_Title'];

        $chkCat = new Zend_Form_Element_MultiCheckbox("NP_Categories");
        $chkCat->addMultiOptions($catLst);
        $chkCat->setDecorators(array(
            'ViewHelper',
            array('label', array('placement' => 'append')),
            array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'id' => '', 'class' => 'label_after_checkbox')),
        ));

        $this->addElement($label);
        $this->addElement($chkCat);
    }

}