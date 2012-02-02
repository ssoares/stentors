<?php
    
    class Text_IndexController extends Cible_Controller_Action
    {
        
        /**
        * Overwrite the function define in the SiteMapInterface implement in Cible_Controller_Action
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

            $newsRob = new TextRobots();
            $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));            

            parent::siteMapAction($dataXml); 
        }
        
        public function indexAction()
        {
            $BlockID = $this->_getParam( 'BlockID' ); 
            
            $Select = $this->_db->select()
                    ->from('TextData')
                    ->where('TD_BlockID = ?', $BlockID)
                    ->where('TD_LanguageID = ?', Zend_Registry::get("languageID"));
            $this->view->block = $this->_db->fetchRow($Select);
        }
    }
?>