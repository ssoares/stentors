<?php
/**
 * Module Catalog
 * Management of the products for Logiflex.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormBlockCatalog.php 456 2011-04-18 00:41:54Z ssoares $id
 */

/**
 * Form to add a new catalog block.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: FormBlockCatalog.php 456 2011-04-18 00:41:54Z ssoares $id
 */
class FormBlockCatalog extends Cible_Form_Block
{
    protected $_moduleName = 'catalog';

    public function __construct($options = null)
    {       
        $baseDir = $options['baseDir'];
        $pageID = $options['pageID'];

        parent::__construct($options);
            

        /****************************************/
        // PARAMETERS
        /****************************************/

        // select box category (Parameter #1)
        $blockCategory = new Zend_Form_Element_Select('Param1');
        $blockCategory->setLabel(Cible_Translation::getCibleText('catalog_category_block_page'))
        ->setAttrib('class','largeSelect')
        ->setOrder(2);

        $langId = $this->getView()->_defaultEditLanguage;

        $oCategory  = new CatalogCategoriesObject();
        $categories = $oCategory->getAll($langId);

        foreach ($categories as $category){
            $blockCategory->addMultiOption($category['CC_ID'],$category['CCI_Name']);
        }

        $this->addElement($blockCategory);

        $this->removeDisplayGroup('parameters');

        $this->addDisplayGroup(array('Param1', 'Param999'),'parameters');
        $parameters = $this->getDisplayGroup('parameters');
    }
}
