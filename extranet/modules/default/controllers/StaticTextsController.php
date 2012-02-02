<?php
/**
* Make the management of some StaticText for the frontend showing
*
*
* PHP versions 5
*
* LICENSE: 
*
* @category   Controller
* @package    Default
* @author     JP Bernard <jean-philip.bernard@ciblesolutions.com>
* @copyright  2010 CIBLE Solutions d'Affaires
* @license    http://www.ciblesolutions.com
* @version    CVS: <?php $ ?> Id:$
*/
class StaticTextsController extends Cible_Extranet_Controller_Module_Action
{
    function indexAction()
    {
        // NEW LIST GENERATOR CODE //        
        $tables = array(                
                'Static_Texts' => array('ST_Identifier','ST_Desc_backend','ST_Value')
        );
        
        $field_list = array(
            'ST_Desc_backend' => array(
                'width' => '450px'
            ),
            'ST_Value' => array(
                'width' => '450px'
            )
        );
                
        $select = $this->_db->select()->from('Static_Texts', $tables['Static_Texts'])                                
                                ->where('Static_Texts.ST_LangID  = ?', Zend_Registry::get('languageID'))
                                ->where('Static_Texts.ST_Editable = ?', 1)
                                ->where('Static_Texts.ST_Type = ?', 'client');
                                
        
        $options = array(
            /*'commands' => array(
                $this->view->link($this->view->url(array('controller'=>'administrator','action'=>'add')),$this->view->getCibleText('button_add_administrators'), array('class'=>'action_submit add') )
            ),*/
            'disable-export-to-excel' => '',            
            'action_panel' => array(
                'width' => '50',
                'actions' => array(
                    'edit' => array(
                        'label' => $this->view->getCibleText('button_edit'),
                        'url' => "{$this->view->baseUrl()}/default/static-texts/edit/identifierID/%Identifier%",
                        'findReplace' => array(
                            'search' => '%Identifier%',
                            'replace' => 'ST_Identifier'
                        )
                    )/*,
                    'delete' => array(
                        'label' => $this->view->getCibleText('button_delete'),
                        'url' => "{$this->view->baseUrl()}/default/administrator/delete/administratorID/%ID%",
                        'findReplace' => array(
                            'search' => '%ID%',
                            'replace' => 'EU_ID'
                        )
                    )*/
                ) 
            )
        );
        
        $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
        
        $this->view->assign('mylist', $mylist);
    }
    
    function editAction()
    {
        // page title
        $this->view->title = $this->view->getCibleText('label_static_text_edition');
        
        // get param
        $identifierID = $this->_getParam('identifierID');
        $order           = $this->_getParam('order'); 
        $tablePage       = $this->_getParam('tablePage'); 
        $search          = $this->_getParam('search'); 
        
        $paramsArray = array("order" => $order, "tablePage" => $tablePage, "search" => $search);
        
        
        // get static text data        
        $staticTextData = Cible_FunctionsGeneral::getClientStaticText($identifierID, $this->_currentEditLanguage);
        
        
        /********** ACTIONS ***********/
        $returnLink = $this->view->url(array('controller' => 'static-texts', 'action' => 'index', 'identifierID' => null));
        $form = new FormStaticTexts(array(
            'baseDir'   => $this->view->baseUrl(),
            'cancelUrl' => "$returnLink",
            'identifierID' => $identifierID
            )
        );
        
        $this->view->assign('identifierID', $identifierID);
        $this->view->assign('form', $form);
        
        if ( !$this->_request->isPost() )
        {            
            $form->populate($staticTextData->toArray());
        } 
        else
        {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData))
            {                
                $staticTextData['ST_Value']       = $form->getValue('ST_Value');
                /*$staticTextData['EU_FName']       = $form->getValue('EU_FName');
                $staticTextData['EU_Email']       = $form->getValue('EU_Email');
                $staticTextData['EU_Username']    = $form->getValue('EU_Username');*/
                
                // Sauvegarde des nouvelles données
                $staticTextData->save();
                
                // Sauvegarde de la cache
                $tag = 'client';
        
                if( in_array( $tag, array('cible','client')) ){
                    $cache = Zend_Registry::get('cache');
                    $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array($tag) );
                    //$this->_redirect( 'cache' );
                }
                
                header("location:".$returnLink);
            }
        }
    }
    
    public function toExcelAction()
    {
        $this->filename = 'StaticTexts.xlsx';
        
        
        $this->tables = array(                
                'Static_Texts' => array('ST_Identifier','ST_Desc_backend','ST_Value','ST_LangID')
        );
        
        $this->fields = array(
            'ST_LangID' => array(
                'width' => '',
                'label' => ''
            ),
            'ST_Desc_backend' => array(
                'width' => '',
                'label' => ''
            ),
            'ST_Value' => array(
                'width' => '',
                'label' => ''
            )            
        );
        
        $this->filters = array(
            
        );
        
        $staticText = new StaticTexts();
                $this->select = $staticText->select()
                ->where('Static_Texts.ST_Editable = ?', 1)
                ->where('Static_Texts.ST_Type = ?', 'client');
        
        parent::toExcelAction();
    }
    
    function addAction()
    {
        
    }
    
    function deleteAction()
    {
        
    }
    
    public function profileAction()
    {
        
    }
}
