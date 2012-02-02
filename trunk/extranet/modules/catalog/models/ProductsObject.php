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
 * @version   $Id: ProductsObject.php 451 2011-04-13 20:23:56Z ssoares $id
 */

/**
 * Manage data from products table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ProductsObject.php 451 2011-04-13 20:23:56Z ssoares $id
 */
class ProductsObject extends DataObject
{
    protected $_dataClass   = 'ProductsData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = 'ProductsIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'PI_LanguageID';
    protected $_foreignKey      = 'P_SubCategoryID';
//    protected $_constraint      = 'P_SubCategoryID';
//    protected $_indexColumns    = array();

    /**
     * Build the list of products for a dropdown select.
     * Data are grouped by category and subcategory.
     * 
     * @param int $langId
     * 
     * @return array
     */
    public function productsCollection($langId)
    {
        (array) $array = array();
        
        $select = $this->getAll($langId, false);

        $select->joinLeft(
            'Catalog_SousCategoriesData',
            'P_SubCategoryID = SC_ID',
            array())
            ->join(
                'Catalog_SousCategoriesIndex',
                'SC_ID = SCI_SousCategoryID',
                'SCI_Name')
            ->join(
                'Catalog_CategoriesData',
                'SC_CategoryID = CC_ID',
                array())
            ->join('Catalog_CategoriesIndex', 'CC_ID = CCI_CategoryID', 'CCI_Name')
//            ->where($this->_indexLanguageId .' = ?', $langId)
            ->where('SCI_LanguageID = ?', $langId)
            ->where('CCI_LanguageID = ?', $langId)
            ->order('CCI_Name')
        ;

        $products =$this->_db->fetchAll($select);
        
        foreach ($products as $data)
        {
            $key = $data['CCI_Name'] . "-" . $data['SCI_Name'];
            //If cat not in array add it as an array
            if(!array_key_exists($key, $array))
            {
                $array[$key] = array($data['P_ID'] => $data['PI_Name']);
                
            }
            //Else Add values product id and product name into the subcat array
            else
            {
                $array[$key][$data['P_ID']] = $data['PI_Name'];
            }

        }

        return $array;
    }
}