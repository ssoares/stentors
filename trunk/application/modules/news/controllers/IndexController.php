<?php
    
    class News_IndexController extends Cible_Controller_Action
    {
        protected $_showBlockTitle = false;

       /**
        * Overwrite the function defined in the SiteMapInterface implement in Cible_Controller_Action
        *
        * This function return the sitemap specific for this module
        *
        * @access public
        *
        * @return a string containing xml sitemap
        */
        public function siteMapAction(){
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            $newsRob = new NewsRobots();
            $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));

            parent::siteMapAction($dataXml);


            // http://sandboxes.ciblesolutions.com/fr/edith/www/news/index/site-map/lang/2
        }

        public function init()
        {
            parent::init();
            $this->setModuleId();
            $this->view->headLink()->offsetSetStylesheet($this->_moduleID, $this->view->locateFile('news.css'));
            $this->view->headLink()->appendStylesheet($this->view->locateFile('news.css'));
        }


        public function detailsAction(){

            $_blockID = $this->_request->getParam('BlockID');
            $news = new NewsCollection($_blockID);
            $id = 0;
            $titleUrl = Cible_FunctionsGeneral::getTitleFromPath($this->_request->getPathInfo());
            if($titleUrl!=""){
                $id = $news->getIdByName($titleUrl);
            }
            $listall_page = Cible_FunctionsCategories::getPagePerCategoryView( $news->getBlockParam('1'), 'listall' );
            $this->view->assign('params', $news->getBlockParams());
            $this->view->assign('news', $news->getDetails($id) );
            if(!empty($_SERVER['HTTP_REFERER'])){
                $this->view->assign('pagePrecedente', $_SERVER['HTTP_REFERER']);
            }
            else{
                 $this->view->assign('pagePrecedente','');
            }
            $this->view->assign('listall_page', $listall_page);
        }

        public function homepagelistAction(){
            $_blockID = $this->_request->getParam('BlockID');

            $news = new NewsCollection($_blockID);

            $listall_page = Cible_FunctionsCategories::getPagePerCategoryView( $news->getBlockParam('1'), 'listall' );
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $news->getBlockParam('1'), 'details' );
            //exit;
            $this->view->assign('listall_page', $listall_page);
            $this->view->assign('details_page', $details_page);
            $this->view->assign('params', $news->getBlockParams());
            $this->view->assign('news', $news->getList($news->getBlockParam('2')) );
        }

        public function listallAction(){
            $_blockID = $this->_request->getParam('BlockID');
            $newsObject = new NewsCollection($_blockID);
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $newsObject->getBlockParam('1'), 'details' );
            $this->view->assign('details_page', $details_page);
            $news = $newsObject->getList();
            $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_Array( $news ) );
            $paginator->setItemCountPerPage( $newsObject->getBlockParam('2') );
            $paginator->setCurrentPageNumber($this->_request->getParam('page'));
            $this->view->assign('params', $newsObject->getBlockParams());
            $this->view->assign('paginator', $paginator);
        }

        public function listall2columnsAction(){
            $_blockID = $this->_request->getParam('BlockID');
            $newsObject = new NewsCollection($_blockID);
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $newsObject->getBlockParam('1'), 'details' );
            $this->view->assign('details_page', $details_page);
            $news = $newsObject->getList();
            $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_Array( $news ) );
            $paginator->setItemCountPerPage( $newsObject->getBlockParam('2') );
            $paginator->setCurrentPageNumber($this->_request->getParam('page'));
            $this->view->assign('params', $newsObject->getBlockParams());
            $this->view->assign('paginator', $paginator);
        }

        public function listall3columnsAction(){
            $_blockID = $this->_request->getParam('BlockID');
            $newsObject = new NewsCollection($_blockID);
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $newsObject->getBlockParam('1'), 'details' );
            $this->view->assign('details_page', $details_page);
            $news = $newsObject->getList();
            $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_Array( $news ) );
            $paginator->setItemCountPerPage( $newsObject->getBlockParam('2') );
            $paginator->setCurrentPageNumber($this->_request->getParam('page'));
            $this->view->assign('params', $newsObject->getBlockParams());
            $this->view->assign('paginator', $paginator);
        }



    }
?>