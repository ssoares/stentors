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
 * @version   $Id: FormNewsletterUsers.php 487 2011-05-20 03:15:37Z ssoares $id
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
 * @version   $Id: FormNewsletterUsers.php 487 2011-05-20 03:15:37Z ssoares $id
 */
class FormNewsletterUsers extends Cible_Form
{

    public function __construct($options = null)
    {
        $this->_disabledDefaultActions = true;

        parent::__construct($options);

        $newsletterCategories = $this->getView()->GetAllNewsletterCategories();
        $newsletterCategories = $newsletterCategories->toArray();

        foreach ($newsletterCategories as $cat)
        {
            $chkCat = new Zend_Form_Element_Checkbox("chkNewsletter{$cat['C_ID']}");
            $chkCat->setLabel($cat['CI_Title']);
            $chkCat->setDecorators(array(
                'ViewHelper',
                array('label', array('placement' => 'append')),
                array(array('row' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'label_after_checkbox')),
            ));

            $this->addElement($chkCat);
        }
    }

}