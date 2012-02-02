<?php
/**
 * Cible Solutions 
 *
 *
 * @category  Modules
 * @package   Catalog
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: IndexController.php 824 2012-02-01 01:21:12Z ssoares $
 */

/**
 * Catalog index controller
 * Manage actions to display catalog.
 *
 * @category  Modules
 * @package   Catalog
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class Catalog_IndexController extends Cible_Controller_Action
{
    protected $_moduleID      = 14;
    protected $_defaultAction = 'list';
    protected $_moduleTitle   = 'catalog';
    protected $_name          = 'index';

    /**
    * Overwrite the function define in the SiteMapInterface implement Cible_Controller_Action
    *
    * This function return the sitemap specific for this module
    *
    * @access public
    *
    * @return a string containing xml sitemap
    */
    public function siteMapAction(){
        //var_dump($this);
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);


        $newsRob = new CatalogRobots();
        $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));
        parent::siteMapAction($dataXml);


        //echo "dddddddd";
    }

    public function init()
    {
        $Param = $this->getRequest( 'Params' );
        //var_dump($this->getRequest()->getActionName());
        $Action = $this->getRequest()->getActionName();
         parent::init();

       // echo $Action;
        if($Action!="site-map"){
            $langId = Zend_Registry::get('languageID');
            //parent::init();
            $this->setModuleId();
            $this->view->headLink()->offsetSetStylesheet($this->_moduleID, $this->view->locateFile('catalog.css'));
            $this->view->headLink()->appendStylesheet($this->view->locateFile('catalog.css'));

//        $oTypes       = new TypesObject();
            $oCollections = new SubCategoriesObject();
//        $oClientele   = new ClienteleObject();

//        $types       = $oTypes->getAll($langId);
            $collections = $oCollections->getAll($langId);
//        $clienteles  = $oClientele->clienteleList($langId);

//        $this->view->types       = $types;
            $this->view->collections = $collections;
//        $this->view->clientele   = $clienteles;
        }

    }
    /**
     * List products according given parameters.
     * This list is only for display purpose. No actions except Excel export.
     *
     * @return void
     */
    public function listAction()
    {
        $img = $this->_getParam('img');
        if (!empty($img))
        {
            $this->downloadAction();
            exit;
        }

        $this->view->params['actions'] = $this->_request->getPathInfo();
        /* List products */
        $oProducts = new ProductsCollection($this->view->params);
        $products = $oProducts->getList();

        $searchCount = count($products);

        /* Params */
        $subCategoryId = 0;
        $blockParams = $oProducts->getBlockParams();
        $categorieId = $oProducts->getCatId();
        $productId   = $oProducts->getProdId();

        $url = $this->view->absolute_web_root
                 . $this->getRequest()->getPathInfo();
        Cible_View_Helper_LastVisited::saveThis($url);

        if (!$productId)
        {
            if (!$categorieId)
                $categorieId = $blockParams[1];

    //        Zend_Registry::set('bg-body-id', $categorieId);
            $subCategoryId = $oProducts->getSubCatId();
            if ($subCategoryId)
            {
                $oSubCat = new SubCategoriesObject();
                $subCat = $oSubCat->populate($subCategoryId, Zend_Registry::get('languageID'));
                $this->view->subCatName = $subCat['SCI_Name'];
            }
            $searchWords = (isset($this->view->params['keywords']) && $this->view->params['keywords'] != $this->view->getCibleText('form_search_catalog_keywords_label')) ? $this->view->params['keywords'] : '';

            /* Search form */
    //        $searchForm = new FormSearchCatalogue(
    //            array(
    //                'categorieId'   => $categorieId,
    //                'subCategoryId' => $subCategoryId,
    //                'keywords'      => $searchWords)
    //            );
    //
    //        $this->view->assign('searchForm', $searchForm);
            $oCategory = new CatalogCategoriesObject();
            $category  = $oCategory->populate($categorieId, $this->_registry->languageID);
            $this->_registry->set('category', $category);

            $lastSearch = array();
            if(!empty($subCategoryId))
                $lastSearch['sousCatId'] = $subCategoryId;
            if(!empty ($searchWords))
                $lastSearch['keywords'] = $searchWords;

                $this->view->assign('searchUrl', $lastSearch);

            $page = 1;

            $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_Array( $products ) );
            $paginator->setItemCountPerPage( $oProducts->getLimit() );

            if (isset($this->view->params['productId']))
            {
                $productId = $this->view->params['productId'];
                $this->view->assign('productId', $productId);
                foreach ($products as $product)
                {
                    if ($product['P_ID'] != $productId)
                    {
                        $page++;
                    }
                    else
                        break;
                }
            }

            $filter    = $oProducts->getFilter();
            $paramPage = $this->_request->getParam('page');
            $page      = (isset($paramPage)) ? $this->_request->getParam('page') : ceil($page/$paginator->getItemCountPerPage());

            $paginator->setCurrentPageNumber($page);

            $this->view->assign('categoryId', $categorieId);

            $this->view->assign('params', $oProducts->getBlockParams());
            $this->view->assign('paginator', $paginator);

            $this->view->assign('keywords', $searchWords);
            $this->view->assign('searchCount', $searchCount);
            $this->view->assign('filter', $filter);

            if(isset($category['CCI_ValUrl']))
                echo $this->_registry->set('selectedCatalogPage', $category['CCI_ValUrl']);
        }
        else
        {
            $this->_registry->set('category', $this->_registry->get('catId_'));
            $this->_registry->set('productCase','1');
            $url = $this->view->absolute_web_root
                 . $this->getRequest()->getPathInfo();
            Cible_View_Helper_LastVisited::saveThis($url);
            $this->_registry->set('selectedCatalogPage', $products['CCI_ValUrl']);
            $this->view->assign('productDetails', $products);
            $this->renderScript('index/detail-product.phtml');
        }
    }

     public function listnewAction()
    {

        $this->view->params['actions'] = $this->_request->getPathInfo();
        $oProducts = new ProductsCollection($this->view->params);
        $blockParams = $oProducts->getBlockParams();
        $this->view->assign('productsList', $oProducts->getListOfAllNewProducts());
        //$this->view->assign('productsList', $oProducts->getList());
        $this->view->assign('params', $oProducts->getBlockParams());
    }

    public function detailproductAction()
    {
            $this->listAction ();
    }

    public function listcollectionsAction()
    {
        $langId = Zend_Registry::get('languageID');
        $oSubCategories = new SubCategoriesObject();
        $subCategories = $oSubCategories->getSubCatAsCollections($langId);

        $page = Cible_FunctionsPages::getPageNameByID(14080, $langId);

        $this->view->assign('collections', $subCategories);
        $this->view->assign('page', $page);

    }

    public function listpromosAction()
    {
        $blockID = $this->_request->getParam('BlockID');

        if ($blockID)
        {
            $this->_blockID = $blockID;
            $params = Cible_FunctionsBlocks::getBlockParameters($blockID);
        }
        foreach ($params as $param)
        {
            $blockParams[$param['P_Number']] = $param['P_Value'];
        }

        $this->view->assign('autoPlay', $blockParams[2]);
        $this->view->assign('delais', $blockParams[3]);
        $this->view->assign('transition', $blockParams[4]);
        $this->view->assign('navi', $blockParams[5]);
        $this->view->assign('effect', $blockParams[6]);

        if($blockParams[7])
        {
            $category = Cible_FunctionsCategories::getCategoryDetails($blockParams[7]);
            $this->view->assign('promoLbl', $category['CI_Title']);
        }
        else
            $this->view->assign('promoLbl', $this->view->getClientText('catalog_promo_default_label_render'));

        $oPromo = new PromosObject();
        $promos = $oPromo->getPromosByCategory($blockParams[7]);

        $this->view->assign('promos', $promos);
    }

    public function downloadAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->layout()->disableLayout();

        $img = $this->_getParam('img');

        // Process the file
        $config = Zend_Registry::get('config');
        $width  = $config->catalog->image->original->maxWidth;
        $height = $config->catalog->image->original->maxHeight;

        $prefix = $width . 'x' . $height . '_';
        $filename  = $prefix . $img;
        $file = Zend_Registry::get('document_root') . '/data/images/catalog/products/' . $this->_getParam('pid') .'/'. $filename;

        if (file_exists($file)){

            $this->getResponse()
             ->setHeader('Content-Disposition', 'attachment; filename='.$filename)
             ->setHeader('Content-Length', filesize($file));

            $this->getResponse()->sendHeaders();
            readfile($file);
            exit;
        }
    }
}