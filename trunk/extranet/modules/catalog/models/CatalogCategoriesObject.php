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
 * @version   $Id: CatalogCategoriesObject.php 422 2011-03-24 03:25:10Z ssoares $id
 */

/**
 * Manage data from colletion table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: CatalogCategoriesObject.php 422 2011-03-24 03:25:10Z ssoares $id
 */
class CatalogCategoriesObject extends DataObject
{
    protected $_dataClass   = 'CatalogCategoriesData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();
    
    protected $_indexClass      = 'CatalogCategoriesIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'CCI_LanguageID';
//    protected $_indexColumns    = array();
}