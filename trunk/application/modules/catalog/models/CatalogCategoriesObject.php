<?php
/**
 * Module Catalog
 * Management of the products for Logiflex.
 *
 * @category  Application_Module
 * @package   Application_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: CatalogCategoriesObject.php 598 2011-09-07 19:32:59Z freynolds $id
 */

/**
 * Manage data from colletion table. 
 *
 * @category  Application_Module
 * @package   Application_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: CatalogCategoriesObject.php 598 2011-09-07 19:32:59Z freynolds $id
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
    protected $_query = '';
    protected $_searchColumns = array(
        'data' => array(),
        'index' => array(
            'CCI_Name'
        )
    );

    /**
     * Setter for the query used to find data.
     *
     * @param Zend_Db_Select $query
     * @return void
     */
    public function setQuery(Zend_Db_Select $query)
    {
        $this->_query = $query;
    }

    /**
     * Fetch the id of a category according the formatted string fron URL.
     * 
     * @param string $string
     *
     * @return int Id of the searched category
     */
    public function getIdByName($string)
    {
        $select = $this->_db->select()
                ->from($this->_oDataTableName, $this->_dataId)
                ->joinLeft(
                        $this->_oIndexTableName,
                        $this->_dataId . " = " . $this->_indexId,
                        '')
                ->where("CCI_ValUrl LIKE ?", "%" . $string . "%")
                ;
        
        $id = $this->_db->fetchRow($select);

        return $id[$this->_dataId];
    }

    public function getDataCatagory($langId = null, $array = true, $id = null)
    {
        if (isset($this->_query) && $this->_query instanceof Zend_Db_Select)
        {
            $select = $this->_query;

            $select->join(
                    $this->_oDataTableName,
                    $this->_dataId . ' = SC_CategoryID',
                    array($this->_dataId)
                    )
                ->joinLeft(
                    $this->_oIndexTableName,
                    $this->_dataId . " = " . $this->_indexId,
                    array( 'CCI_Name', 'CCI_ValUrl')
                    );
        }

        if (!is_null($langId))
        {
            $select->where("{$this->_indexLanguageId} = ?", $langId);
        }

        if ($array)
            return $products = $this->_db->fetchAll($select);
        else
            return $select;
    }
}