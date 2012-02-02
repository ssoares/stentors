<?php
/**
 * Module Catalog
 * Management of the products.
 *
 * @category  Apploication_Module
 * @package   Application_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ProductsCollection.php 598 2011-09-07 19:32:59Z freynolds $
 */

/**
 * Manage data from products table.
 *
 * @category  Application_Module
 * @package   Application_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ProductsCollection.php 598 2011-09-07 19:32:59Z freynolds $
 */
class ProductsCollection extends Zend_Db_Table
{
    /**
     * The database object instance
     *
     * @var Zend_Db
     */
    protected $_db; 
    /**
     * Current language
     *
     * @var int
     */
    protected $_currentLang;
    /**
     * Block id
     *
     * @var int
     */
    protected $_blockID = null;  
    /**
     * Parameters of the block
     *
     * @var array
     */
    protected $_blockParams = array();

    protected $_actions = array();

    protected $_name = 'Catalog_ProductsData';

    protected $_keywords = array();
    protected $_filter   = array();
    protected $_catId    = 0;
    protected $_subCatId = 0;
    protected $_prodId   = 0;
    protected $_limit    = 9;
    protected $_type     = '';
    protected $_bonus    = false;

    /**
     * Fetch the parameter value
     *
     * @param int $param_name Number identifying the parameter
     *
     * @return string
     */
    public function getBlockParam($param_name)
    {
        return $this->_blockParams[$param_name];
    }

    /**
     * Getter for hasBonus. The product allows to cumulate bonus point.
     *
     * @return bool
     */
    public function getBonus()
    {
        return $this->_bonus;
    }



    /**
     * Return the number of product by page
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * Return the category id
     *
     * @return int
     */
    public function getCatId()
    {
        return $this->_catId;
    }

    /**
     * Return the product id
     *
     * @return int
     */
    public function getProdId()
    {
        return $this->_prodId;
    }

    /**
     * Return the sub-category id
     *
     * @return int
     */
    public function getSubCatId()
    {
        return $this->_subCatId;
    }

    /**
     * Return the parameters array
     *
     * @return array
     */
    public function getBlockParams()
    {
        return $this->_blockParams;
    }

    /**
     * Return the filter attribute.
     *
     * @return array
     */
    public function getFilter()
    {
        return $this->_filter;
    }
    /**
     * Return the actions attribute
     *
     * @return array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * Class constructor
     *
     * @param int $blockID Id of the block. Default value = null
     */
    public function __construct($params = array())
    {
        if(isset($params['lang'])){
            $this->_currentLang = $params['lang'];
        }
        else{
            $this->_currentLang = Zend_Registry::get('languageID');
        }
        $this->_db           = Zend_Registry::get('db');

        $this->setParameters($params);

    }

    /**
     * Set parameters given in the url
     * @param array $params Parameters from url to set to build le product list.
     * @return void
     */
    public function setParameters($params = array())
    {
        foreach ($params as $property => $value)
        {
            if ($property == 'BlockID')
                $property = 'blockID';

            $methodName = 'set' . ucfirst($property);

            if (property_exists($this, '_' . $property)
                && method_exists($this, $methodName))
            {
                $this->$methodName($value);
            }
        }
    }

    public function setBlockID($value)
    {
        $this->_blockID = $value;
        $_params = Cible_FunctionsBlocks::getBlockParameters($value);

        foreach ($_params as $param)
        {
            $this->_blockParams[$param['P_Number']] = $param['P_Value'];
        }
    }

    public function setActions($value)
    {
        $exclude = array('index', 'page', 'keywords', 'collection', 'product');
        $include = array('collection', 'product');
        $tmpArray = explode("/", $value);

        array_shift($tmpArray);
        array_shift($tmpArray);

        $lastVal = end($tmpArray);
        if ($lastVal == "")
            array_pop($tmpArray);
        
//        $hasKeys = array_intersect($include, $tmpArray);
//        foreach ($exclude as $value)
//        {
//            $key = array_search($value, $tmpArray);
//            if ($key)
//                unset($tmpArray[$key]);
//            if(($value == 'page' || $value == 'keywords') && $key)
//                unset($tmpArray[$key + 1]);
//        }
//        $this->_actions = $tmpArray;
        
        $test = array();

        if (!empty ($tmpArray))
            $test[1] = $tmpArray[0];

        foreach ($include as $value)
        {
            $key = array_search($value, $tmpArray);
            
            if ($key)
                $test[] = $tmpArray[$key + 1];
    }
        
        $this->_actions = $test;
    }
    public function setCatId($value)
    {
        $this->_catId = $value;
    }

    public function setSubCatId($value)
    {
        $this->_subCatId = $value;
    }

    public function setType($value)
    {
        $this->_type = $value;
    }

    public function setLimit($value)
    {
        $this->_limit = $value;
    }

    public function setKeywords($value)
    {
        if (!empty($value)
            && $value != Cible_Translation::getCibleText('form_search_catalog_keywords_label'))
            $this->_keywords = explode(" ", trim($value));
    }

    public function setFilter($filters)
    {
        if (is_array($filters))
        {
            foreach ($filters as $value)
            {
                $data = explode('_', $value);
                $this->_filter[$data[0]] = $data[1];
            }
        }
        else
        {
            $data = explode('_', $filters);
            $this->_filter[$data[0]] = $data[1];
        }
    }

    public function getDetails($id, $itemId = 0, $resume = false)
    {
        $products   = array();
        $originalId = 0;

        $oProduct = new ProductsObject();
        $oItem    = new ItemsObject();

        if ($resume)
            $oItem->setRenderResume($resume);

        $products['data'] = $oProduct->populate(
                    $id,
                    Zend_Registry::get('languageID'));

        $hasBonus = $products['data']['P_CumulPoint'];
        if (!$this->_bonus && $hasBonus)
                $this->_bonus = $hasBonus;

        if($itemId)
            $products['items'] = $oItem->populate($itemId, Zend_Registry::get('languageID'));
        
        return $products;
    }

    /**
     * Get the list of the products for the current category
     *
     * @param int $limit
     *
     * @return array
     */
    public function getList()
    {
        $products   = array();
        $this->getDataByName();
        
        $oProducts = new ProductsObject();
        $oSubCat   = new SubCategoriesObject();
        $oCategory = new CatalogCategoriesObject();      
//        $oType     = new TypesObject();
//        $oCli      = new ClienteleObject();

        $oSubCat->setOrderBy('SCI_Seq');
        
        if (isset($this->_blockParams['1']))
            Zend_Registry::set('defaultCategory', $this->_blockParams['1']);
       
        if (!$this->_prodId)
        {
            // If no category selected, set the default one.
            if (!$this->_catId && !$this->_keywords && isset ($this->_blockParams['1'])){
                $categoryId = $this->_blockParams['1'];           
            }
            else{
                $categoryId = $this->_catId;               
           }

            Zend_Registry::set('catId_',$categoryId);
            $subCategories = $oSubCat->getSubCatByCategory(
                    $categoryId,
                    false,
                    $this->_currentLang);
            
//            if($this->_subCatId)
//                $subCategories->where('SC_ID = ?', $this->_subCatId);
            
            $oCategory->setQuery($subCategories);
            $categoryQuery = $oCategory->getDataCatagory($this->_currentLang, false);
            $oProducts->setQuery($categoryQuery);          

            $select = $oProducts->getProducts($this->_currentLang, false);
//            $oType->setQuery($select);
//            $select = $oType->getDataTypes($this->_currentLang, false);
//            $oCli->setQuery($select);
//            $select = $oCli->getDataClientele($this->_currentLang, false);

            $select->order('PI_Seq ASC');
            
            if ($this->_subCatId){
                $select->where('P_SubCategoryID = ?', $this->_subCatId);
                 Zend_Registry::set('subCatId_',$this->_subCatId);
            }

            if (count($this->_keywords))
                $this->_setFilterByKeyword($select);
            
            if (count($this->_filter))
            {
                $filterClause = "";
                foreach ($this->_filter as $key => $value)
                {
                    $select = $this->_addFilterQuery($key, $value, $select);
                }
            }
            
            $products = $this->_db->fetchAll($select);

            $num_products = count($products);
        }
        else
        {
            $product      = $oProducts->getAll($this->_currentLang, true, $this->_prodId);
            $tmpArray     = $product[0];
            $dataSubCat   = $oSubCat->getAll($this->_currentLang, true, $tmpArray['P_SubCategoryID']);
            $subCategory  = $dataSubCat[0];
            $dataCategory = $oCategory->getAll($this->_currentLang, true, $subCategory['SC_CategoryID']);
            $category     = $dataCategory[0];
            $products     = array_merge($tmpArray, $subCategory, $category);
            
//            $type = $oType->getAll($this->_currentLang, true, $tmpArray['P_TypeID']);
//            $cli  = $oCli->getAll($this->_currentLang, true, $tmpArray['P_ClienteleID']);
            
            $products['type'] = $type[0];
            $products['clientele'] = $cli[0];
            
//            $oItems       = new ItemsObject();
//            $items        = $oItems->getItemsByProductId($this->_prodId);

            Zend_Registry::set('catId_',$subCategory['SC_CategoryID']);
            Zend_Registry::set('subCatId_',$tmpArray['P_SubCategoryID']);

//            $products['items'] = $items;
            
//            $oAssocProd = new ProductsAssociationObject();
//            $relations  = $oAssocProd->getAll($this->_currentLang, true, $this->_prodId );
//            $tmp        = array();
//            
//            $relatedProd    = array();
//            
//            foreach ($relations as $relProd)
//            {
//                if ($relProd['AP_RelatedProductID'] != -1)
//                {
//                    $tmp = $oProducts->populate($relProd['AP_RelatedProductID'], $this->_currentLang);
//                    $subCat    = $oSubCat->getAll($this->_currentLang, true, $tmp['P_SubCategoryID']);
//                    $tmpSubCat = $subCat[0];
//                    $category  = $oCategory->getAll($this->_currentLang, true, $tmpSubCat['SC_CategoryID']);
//                    $tmpCat    = $category[0];
//                    $tmp       = array_merge($tmp, $tmpSubCat, $tmpCat);
//
//                    $relatedProd[]  = $tmp;
//                }
//            }
//
//            $products['relatedProducts'] = $relatedProd;
                }

//            $products['relatedProducts'] = $relatedProd;
//        }

       
        return $products;
    }

    /**
     * Fecth categories and related sub-Categories
     *
     * @return array $categoryList An array with the categories
     *                             and sub categories
     */
    public function getCategorySubCategory()
    {
        $oSubCat = new SubCategoriesObject();
        $oCategory    = new CatalogCategoriesObject();
        $categories   = $oCategory->getAll($this->_currentLang);
        $fieldCatId   = $oCategory->getDataId();

        foreach ($categories as $category)
        {
            $sousCategories = $oSubCat->getSousCatByCategory(
                $category[$fieldCatId],
                true,
                $this->_currentLang);
            $categoryList[$category['CCI_Name']] = $sousCategories;
        }

        return $categoryList;
    }

    /**
     * Set filters for search by keywords.
     *
     * @param Zend_Db_Select $select The begining of the query to complete.
     *
     * @return void
     */
    private function _setFilterByKeyword(Zend_Db_Select $select)
    {
        $source = array(
            'Products',
//            'Types',
//            'Clientele',
            'SubCategories',
//            'Items',
            'CatalogCategories'
        );
        $excludeTables = array(
            'Catalog_SousCategoriesData',
            'Catalog_ProductsData',
            'Catalog_CategoriesData',
            'Catalog_SousCategoriesIndex',
            'Catalog_ProductsIndex',
            'Catalog_CategoriesIndex'
        );
        $where = "";
        foreach ($source as $table)
        {
            $oData  = $table . 'Object';
            $object = new $oData();
            $object->setExcludeTables($excludeTables);

            if (strlen($where) > 0)
                $where .= ' OR ';
            
            $where .= $object->keywordExist(
                    $this->_keywords,
                    $select,
                    $this->_currentLang);
        }

        $select->where($where);
    }

    public function getDataByName()
    {
        $nbVal = count($this->_actions);
        
        switch ($nbVal)
        {
            case 3:
                $oProd         = new ProductsObject();
                $this->_prodId = $oProd->getIdByName($this->_actions[3]);
          //    break;
            case 2:
                $oSubCat         = new SubCategoriesObject();
                $this->_subCatId = $oSubCat->getIdByName($this->_actions[2]);
//                break;
            case 1:
                $oCat         = new CatalogCategoriesObject();
                $this->_catId = $oCat->getIdByName($this->_actions[1]);
//                break;

            default:
                break;
        }
    }

    /**
     * Get the list of the products for all categories
     *
     *
     * @return array
     */
    public function getListOfAllNewProducts(){
        $products   = array();
        $this->getDataByName();
        $oProducts = new ProductsObject();
        $oSubCat   = new SubCategoriesObject();
        $oCategory = new CatalogCategoriesObject();

        $subCategories = $oSubCat->getSubCatByCategory(0,false,$this->_currentLang);
            
        $oCategory->setQuery($subCategories);
        $categoryQuery = $oCategory->getDataCatagory($this->_currentLang, false);
        $oProducts->setQuery($categoryQuery);
        $select = $oProducts->getProducts($this->_currentLang, false);
        $select->where('P_New = 1');
        $select->order('PI_Name ASC');       
        $products = $this->_db->fetchAll($select);
        //echo $select;
        return $products;
    }

    protected function _addFilterQuery($key, $value, $select)
    {
        $db    = Zend_Registry::get('db');
        $where = "";
        switch ($key)
        {
            case 'fabrication':
                if ($value == 'madeInQuebec')
                    $select->where('P_MadeInQc = ?', 1);
//                    $where = $db->quoteInto();
                break;
            case 'collections':
                $select->where('SCI_ValUrl = ?', $value);
                break;
            case 'typeVetements':
//                ->joinLeft('Catalog_TypesData', 'P_TypeID = T_ID', array())
//                    ->joinLeft('Catalog_TypesIndex', 'TI_TypeID = T_ID', array())
                $select->where('TI_ValUrl = ?', $value);
                break;
            case 'clienteles':
//                ->joinLeft('Catalog_ClienteleData', 'P_ClienteleID = CL_ID', array())
//                    ->joinLeft('Catalog_ClienteleIndex', 'CLI_ClienteleID = CL_ID', array())
                $select->where('CLI_ValUrl = "' . $value .'"' );

                break;
            default:
                break;
        }

        return $select;
    }

    public function getProductsUrl(){
        $productsURL   = array();
        $productsURLValid = array();
        $oCategory = new CatalogCategoriesObject();
        $oCategories = $oCategory->getAll($this->_currentLang);
        //echo $this->_currentLang;
       // var_dump($oCategories);
        foreach ($oCategories as $oCat){
            //var_dump($oCat);
            $this->setCatId($oCat['CC_ID']);
            $productsURL = $this->getList();
            array_push($productsURLValid,$oCat['CCI_ValUrl']);
            foreach ($productsURL as $proURL){
               // var_dump($proURL);
                $str = $proURL['CCI_ValUrl'] . "/collection/" . $proURL['SCI_ValUrl'] . "/product/" . $proURL['PI_ValUrl'];
                array_push($productsURLValid,$str);
            }
           // $subCategories = $oSubCategories->getSubCatByCategory($subC[0],array(),$this->_currentLang);
            //var_dump($productsURL);
        }        

       // catalogue/my-first-category/collection/sous-categorie-1/product/produit-1-en
        
       //var_dump($productsURLValid);
       return $productsURLValid;
    }
}
