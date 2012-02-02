<?php
    
    class Events_IndexController extends Cible_Controller_Categorie_Action
    {
        protected $_moduleID = 7;
        protected $_defaultAction = 'list';
        
        protected function delete($blockID){
            /*
            $blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
            $blockParam = Cible_FunctionsBlocks::getBlockParameters($blockID);
            
            $categoryID = $blockParam[0]['P_Value'];
            
            // get all news blocks with the same category of the present block
            $blockSelect = new Blocks();
            $select = $blockSelect->select()->setIntegrityCheck(false)
            ->from('Blocks')
            ->join('Parameters', 'P_BlockID = B_ID')
            ->where('B_ID <> ?', $blockID)
            ->where('B_ModuleID = ?',$this->_config->modules->events->id)
            ->where('B_Online = 1')
            ->where('P_Number = 1')
            ->where('P_Value = ?', $categoryID);
            
            $blockData = $blockSelect->fetchRow($select);
            
            
            if(count($blockData) == 0){
                // get all events with the category X
                $eventsSelect = new EventsData();
                $select = $eventsSelect->select()
                ->where('ED_CategoryID = ?', $categoryID);
                $eventsData = $eventsSelect->fetchAll($select);
                
                $availableLanguages = Cible_FunctionsGeneral::getAllLanguage();
                foreach($eventsData as $events){
                    foreach($availableLanguages as $language){
                        $indexData['moduleID']  = $this->_config->modules->events->id;
                        $indexData['contentID'] = $events['ED_ID'];
                        $indexData['languageID'] = $language['L_ID'];
                        $indexData['action'] = 'delete';
                        Cible_FunctionsIndexation::indexation($indexData);            
                    }
                }
            }
            */ 
        }
        
        public function setOnlineBlockAction(){
            parent::setOnlineBlockAction();
            /*
            $blockID = $this->getRequest()->getParam('blockID');
            $pageID  = $this->getRequest()->getParam('ID');
            
            $blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
            $blockParam = Cible_FunctionsBlocks::getBlockParameters($blockID);
            
            $categoryID = $blockParam[0]['P_Value'];
            $status     = $blockData['B_Online'];
            
            // offline
            if($status == 0){
                // get all news blocks with the same category of the present block
                $blockSelect = new Blocks();
                $select = $blockSelect->select()->setIntegrityCheck(false)
                ->from('Blocks')
                ->join('Parameters', 'P_BlockID = B_ID')
                ->where('B_ID <> ?', $blockID)
                ->where('B_ModuleID = ?', $this->_config->modules->events->id)
                ->where('B_Online = 1')
                ->where('P_Number = 1')
                ->where('P_Value = ?', $categoryID);
                $blockData = $blockSelect->fetchRow($select);
                
                if(count($blockData) == 0){
                    
                    // get all news with the category X
                    $eventsSelect = new EventsData();
                    $select = $eventsSelect->select()
                    ->where('ED_CategoryID = ?', $categoryID);
                    $eventsData = $eventsSelect->fetchAll($select);
                    
                    $availableLanguages = Cible_FunctionsGeneral::getAllLanguage();
                    foreach($eventsData as $events){
                        foreach($availableLanguages as $language){
                            $indexData['moduleID']  = $this->_config->modules->events->id;
                            $indexData['contentID'] = $events['ED_ID'];
                            $indexData['languageID'] = $language['L_ID'];
                            $indexData['action'] = 'delete';
                            Cible_FunctionsIndexation::indexation($indexData);            
                        }
                    }
                }
                
            }
            // online
            elseif($status == 1){
                // get all news with the category X
                $eventsSelect = new EventsData();
                $select = $eventsSelect->select()->setIntegrityCheck(false)
                ->from('EventsData')
                ->join('EventsIndex', 'EI_EventsDataID = ED_ID')
                ->where('ED_CategoryID = ?', $categoryID)
                ->where('EI_Status = 1');
                $eventsData = $eventsSelect->fetchAll($select);
                
                
                foreach($eventsData as $events){
                    $indexData['pageID']    = $categoryID;
                    $indexData['moduleID']  = $this->_config->modules->events->id;
                    $indexData['contentID'] = $events['ED_ID'];
                    $indexData['languageID'] = $events['EI_LanguageID'];
                    $indexData['title']     = $events['EI_Title'];
                    $indexData['text']      = '';
                    $indexData['link']      = '';
                    $indexData['contents']  = $events['EI_Title'] . " " . $events['EI_Brief'] . " " . $events['EI_Text'] . " " . $events['EI_ImageAlt'];
                    $indexData['action']    = 'update';
                    
                    //print_r($indexData);
                    Cible_FunctionsIndexation::indexation($indexData);    
                    
                }
            }
            */
        }
        
        public function getManageDescription($blockID = null){
            $baseDescription = parent::getManageDescription($blockID);
            
            $listParams = $baseDescription;
            
            $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
            if($blockParameters)
            {
                $blockParams = $blockParameters->toArray();
                
                // Cat�gorie
                $categoryID = $blockParameters[0]['P_Value'];
                $categoryDetails = Cible_FunctionsCategories::getCategoryDetails($categoryID);
                $categoryName = $categoryDetails['CI_Title'];
               
                $listParams .= "<div class='block_params_list'><strong>";
                $listParams .= $this->view->getCibleText('label_category');
                $listParams .= "</strong>" . $categoryName . "</div>";

                // Nombre d'events afficher
                $nbNewsShow = $blockParameters[1]['P_Value'];
                $listParams .= "<div class='block_params_list'><strong>";
                $listParams .= $this->view->getCibleText('label_number_to_show');
                $listParams .= "</strong>" . $nbNewsShow . "</div>";
            }
            
            return $listParams;
        }
        
        public function getIndexDescription($blockID = null){
            
            $listParams = '';
            $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
            if($blockParameters)
            {
                $blockParams = $blockParameters->toArray();
                
                // Cat�gorie
                $categoryID = $blockParameters[0]['P_Value'];
                $categoryDetails = Cible_FunctionsCategories::getCategoryDetails($categoryID);
                $categoryName = $categoryDetails['CI_Title'];
                $listParams .= "<div class='block_params_list'><strong>Cat�gorie : </strong>" . $categoryName . "</div>";
            }
            
            // Nombre d'events Online 
            $listParams .= "<div class='block_params_list'><strong>�v�nements en ligne : </strong>" . $this->getEventsOnlineCount($categoryID) . "</div>";
             
            return $listParams;
        }
        
        public function listAction(){                        
            $tables = array(
                    'EventsData' => array('ED_ID','ED_CategoryID'),
                    'EventsIndex' => array('EI_EventsDataID','EI_LanguageID','EI_Title','EI_Status'),
                    'Status' => array('S_Code')
            );
            
            $field_list = array(
                'EI_Title' => array(
                    //'width' => '300px'
                ),
                'S_Code' => array(
                    'width' => '80px',
                    'postProcess' => array(
                        'type' => 'dictionnary',
                        'prefix' => 'status_'
                    )
                )
            );
            
            $this->view->params = $this->_getAllParams();
            $blockID = $this->_getParam( 'blockID' );
            $pageID  = $this->_getParam( 'pageID' );
            
            $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
            
            $categoryID = $blockParameters[0]['P_Value'];
            
            $category = new CategoriesIndex();
            $select = $category->select()
            ->where('CI_CategoryID = ?', $categoryID)
            ->where('CI_LanguageID = ?', $this->_defaultEditLanguage);
            
            $categoryArray = $category->fetchRow($select);
            $this->view->assign('categoryName', $categoryArray['CI_Title']);
             
            $events = new EventsData();
            $select = $events->select()
                ->from('EventsData')
                ->setIntegrityCheck(false)
                ->join('EventsIndex', 'EventsData.ED_ID = EventsIndex.EI_EventsDataID')
                ->join('Status', 'EventsIndex.EI_Status = Status.S_ID')
                ->where('ED_CategoryID = ?', $categoryID)
                ->where('EI_LanguageID = ?', $this->_defaultEditLanguage);
                //->order('EI_Title');
            
            
            $options = array(
                'commands' => array(
                    $this->view->link($this->view->url(array('controller'=>'index','action'=>'add')),$this->view->getCibleText('button_add_events'), array('class'=>'action_submit add') )
                ),
                //'disable-export-to-excel' => 'true',
                'filters' => array(
                    'events-status-filter' => array(
                        'label' => 'Filtre 1',
                        'default_value' => null,
                        'associatedTo' => 'S_Code',
                        'choices' => array(
                            '' => $this->view->getCibleText('filter_empty_status'),
                            'online' => $this->view->getCibleText('status_online'),
                            'offline' => $this->view->getCibleText('status_offline')
                        )
                    )
                ),            
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                        'edit' => array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => "{$this->view->baseUrl()}/events/index/edit/eventID/%ID%/pageID/".$pageID."/blockID/".$blockID,
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'ED_ID'
                            )
                        ),
                        'delete' => array(
                            'label' => $this->view->getCibleText('button_delete'),
                            'url' => "{$this->view->baseUrl()}/events/index/delete/eventID/%ID%/pageID/".$pageID."/blockID/".$blockID,
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'ED_ID'
                            )
                        )
                    ) 
                )
            );
            
            $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
            
            $this->view->assign('mylist', $mylist);
        }
        
        public function listAllAction(){
            
            if ($this->view->aclIsAllowed('events','edit',true)){
                
                // NEW LIST GENERATOR CODE //
                $tables = array(                
                        'EventsData' => array('ED_ID','ED_CategoryID'),
                        'EventsIndex' => array('EI_EventsDataID','EI_LanguageID','EI_Title','EI_Status'),
                        'Status' => array('S_Code'),
                        'CategoriesIndex' => array('CI_Title') 
                );                
                
                $field_list = array(
                    'EI_Title' => array(
                        'width' => '300px'
                    ),
                    'CI_Title' => array(
                        /*'width' => '80px',
                        'postProcess' => array(
                            'type' => 'dictionnary',
                            'prefix' => 'status_'
                        )*/
                    ),
                    'S_Code' => array(
                        'width' => '80px',
                        'postProcess' => array(
                            'type' => 'dictionnary',
                            'prefix' => 'status_'
                        )
                    )
                );
                
                $events = new EventsData();
                $select = $events->select()                    
                    ->from('EventsData')
                    ->setIntegrityCheck(false)
                    ->join('EventsIndex', 'EventsData.ED_ID = EventsIndex.EI_EventsDataID')
                    ->join('Status', 'EventsIndex.EI_Status = Status.S_ID')
                    ->joinRight('CategoriesIndex', 'EventsData.ED_CategoryID = CategoriesIndex.CI_CategoryID')
                    ->joinRight('Categories', 'EventsData.ED_CategoryID = Categories.C_ID')
                    ->joinRight('Languages', 'Languages.L_ID = EventsIndex.EI_LanguageID')                                         
                    ->where('EI_LanguageID = ?', $this->_defaultEditLanguage)
                    ->where('EventsIndex.EI_LanguageID = CategoriesIndex.CI_LanguageID')
                    ->where('C_ModuleID = ?', $this->_moduleID);
                    //->order('EI_Title');
                     
                   
                $options = array(
                    'commands' => array(
                        $this->view->link($this->view->url(array('controller'=>'index','action'=>'add')),$this->view->getCibleText('button_add_events'), array('class'=>'action_submit add') )
                    ),
                    //'disable-export-to-excel' => 'true',
                    'filters' => array(
                        'events-category-filter' => array(
                            'default_value' => null,                            
                            'associatedTo' => 'ED_CategoryID',
                            'choices' => Cible_FunctionsCategories::getFilterCategories($this->_moduleID)
                        ),
                        'events-status-filter' => array(
                            'label' => 'Filtre 2',
                            'default_value' => null,
                            'associatedTo' => 'S_Code',
                            'choices' => array(
                                '' => $this->view->getCibleText('filter_empty_status'),
                                'online' => $this->view->getCibleText('status_online'),
                                'offline' => $this->view->getCibleText('status_offline'),
                            )
                        )
                    ),            
                    'action_panel' => array(
                        'width' => '50',
                        'actions' => array(
                            'edit' => array(
                                'label' => $this->view->getCibleText('button_edit'),
                                'url' => "{$this->view->baseUrl()}/events/index/edit/eventID/%ID%/lang/%LANG%/return/list-all/",
                                'findReplace' => array(
                                     array(
                                        'search' => '%ID%',
                                        'replace' => 'ED_ID'    
                                    ),
                                    array(
                                        'search' => '%LANG%',
                                        'replace' => 'L_Suffix'
                                    )
                                )
                            ),
                            'delete' => array(
                                'label' => $this->view->getCibleText('button_delete'),
                                'url' => "{$this->view->baseUrl()}/events/index/delete/eventID/%ID%/return/list-all/",
                                'findReplace' => array(
                                    'search' => '%ID%',
                                    'replace' => 'ED_ID'
                                )
                            )
                        ) 
                    )
                );
                
                $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
                
                $this->view->assign('mylist', $mylist);                
            }
        }
                
        public function addAction(){
            
            // variables
            $pageID = $this->_getParam('pageID');
            $blockID = $this->_getParam('blockID');
            $returnAction = $this->_getParam('return');
            $baseDir = $this->view->baseUrl();
            
            if(empty($pageID))
                $categoriesList = 'true';
            else
                $categoriesList = 'false';
            
            if($returnAction)
                $returnUrl = "/events/index/$returnAction";
            elseif($blockID)
                $returnUrl = "/events/index/list/blockID/$blockID/pageID/$pageID";
            else
                $returnUrl = "/events/index/list-all/";
            
            if ($this->view->aclIsAllowed('events','edit',true)){
                $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg"; 
                
                if ($this->_request->isPost()) {
                    $formData = $this->_request->getPost();
                    if($formData['ImageSrc'] <> "")
                        $imageSrc   = Zend_Registry::get("www_root")."/data/images/event/tmp/mcith/mcith_".$formData['ImageSrc'];    
                }
                // generate the form
                $form = new FormEvents(array(
                    'baseDir'   => $baseDir,
                    'imageSrc'  => $imageSrc,
                    'cancelUrl' => "$baseDir$returnUrl",
                    'categoriesList' => "$categoriesList",
                    'eventID'=>'',
                    'isNewImage'=>true
                ));
                $this->view->form = $form;
                
                if ($this->_request->isPost()){
                    $formData = $this->_request->getPost();    
                        if ($form->isValid($formData)) {
                            if(!empty($pageID))
                            {
                                $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
                                $formData['CategoryID'] = $blockParameters[0]['P_Value'];
                            }
                            else
                                $formData['CategoryID'] = $this->_getParam('Param1');
                                
                            if($formData['Status'] == 0)
                                $formData['Status'] = 2;
            
                            $eventsObject = new EventsObject();
                            $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['Title']);
                            $formData['ValUrl'] = $formattedName;
                            $eventID = $eventsObject->insert( $formData, Zend_Registry::get("currentEditLanguage"));
                            
                            
                            /*IMAGES*/
                            mkdir("../../{$this->_config->document_root}/data/images/event/".$eventID) or die ("Could not make event directory");
                            mkdir("../../{$this->_config->document_root}/data/images/event/".$eventID."/tmp") or die ("Could not make tmp directory");
                                
                            if($form->getValue('ImageSrc') <> ''){
                                $config = Zend_Registry::get('config')->toArray(); 
                                $srcOriginal    = "../../{$this->_config->document_root}/data/images/event/tmp/".$form->getValue('ImageSrc');
                                $originalMaxHeight  = $config['event']['image']['original']['maxHeight'];
                                $originalMaxWidth   = $config['event']['image']['original']['maxWidth'];
                                $originalName       = str_replace($form->getValue('ImageSrc'),$originalMaxWidth.'x'.$originalMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                                
                            
                                
                                $srcMedium       = "../../{$this->_config->document_root}/data/images/event/tmp/medium_{$form->getValue('ImageSrc')}";
                                $mediumMaxHeight = $config['event']['image']['medium']['maxHeight'];
                                $mediumMaxWidth  = $config['event']['image']['medium']['maxWidth'];
                                $mediumName      = str_replace($form->getValue('ImageSrc'),$mediumMaxWidth.'x'.$mediumMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                                
                                
                                $srcThumb       = "../../{$this->_config->document_root}/data/images/event/tmp/thumb_{$form->getValue('ImageSrc')}";
                                $thumbMaxHeight = $config['event']['image']['thumb']['maxHeight'];
                                $thumbMaxWidth  = $config['event']['image']['thumb']['maxWidth'];
                                $thumbName      = str_replace($form->getValue('ImageSrc'),$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                                
                                
                                copy($srcOriginal,$srcMedium);
                                copy($srcOriginal,$srcThumb);
                                
                                Cible_FunctionsImageResampler::resampled(array('src'=>$srcOriginal, 'maxWidth'=>$originalMaxWidth, 'maxHeight'=>$originalMaxHeight));
                                Cible_FunctionsImageResampler::resampled(array('src'=>$srcMedium, 'maxWidth'=>$mediumMaxWidth, 'maxHeight'=>$mediumMaxHeight));
                                Cible_FunctionsImageResampler::resampled(array('src'=>$srcThumb, 'maxWidth'=>$thumbMaxWidth, 'maxHeight'=>$thumbMaxHeight));
                                
                                rename($srcOriginal,"../../{$this->_config->document_root}/data/images/event/$eventID/$originalName");
                                rename($srcMedium,"../../{$this->_config->document_root}/data/images/event/$eventID/$mediumName");
                                rename($srcThumb,"../../{$this->_config->document_root}/data/images/event/$eventID/$thumbName");
                            }
                            
                            /**********************/
                            
                            if(!empty($pageID))
                            {
                                //$blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
                                //$blockStatus    = $blockData['B_Online'];
                            
                                $indexData['pageID']    = $formData['CategoryID'];
                                $indexData['moduleID']  = $this->_moduleID;
                                $indexData['contentID'] = $eventID;
                                $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                                $indexData['title']     = $formData['Title'];
                                $indexData['text']      = '';
                                $indexData['link']      = '';
                                $indexData['contents']  = $formData['Title'] . " " . $formData['Brief'] . " " . $formData['Text'] . " " . $formData['ImageAlt'];
                                    
                                if($formData['Status'] == 1)
                                    $indexData['action'] = 'update';
                                else
                                    $indexData['action'] = 'delete';
                        
                                Cible_FunctionsIndexation::indexation($indexData);    
                            }
                            
                            // redirect
                            if(!empty($pageID))
                                $this->_redirect("/events/index/list/blockID/$blockID/pageID/$pageID");
                            else
                                $this->_redirect($returnUrl);
                        }
                        else{
                            $form->populate($formData);
                     }
                } 
            }
            
            
        }
        
        public function editAction(){
            
            // variables
            $eventID = $this->_getParam('eventID');
            $pageID = $this->_getParam('pageID');
            $returnAction = $this->_getParam('return');
            $blockID = $this->_getParam('blockID');
            $baseDir = $this->view->baseUrl();
            
            if ($this->view->aclIsAllowed('events','edit',true)){
                
                if($returnAction)
                    $returnUrl = "/events/index/$returnAction";
                elseif($blockID)
                    $returnUrl = "/events/index/list/blockID/$blockID/pageID/$pageID";
                else
                    $returnUrl = "/events/index/list-all/";
                    
                // get event details
                $eventsObject = new EventsObject();
                $event = $eventsObject->populate($eventID, $this->getCurrentEditLanguage()); 
                
                // image src.
                $config = Zend_Registry::get('config')->toArray(); 
                $thumbMaxHeight = $config['event']['image']['thumb']['maxHeight'];
                $thumbMaxWidth  = $config['event']['image']['thumb']['maxWidth'];
                
                //$this->view->assign('imageUrl', $event['ImageSrc']);
                $this->view->assign('imageUrl', Zend_Registry::get("www_root")."/data/images/event/$eventID/".str_replace($event['ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$event['ImageSrc'],$event['ImageSrc']));

                $isNewImage = 'false';
                if ($this->_request->isPost()){
                    $formData = $this->_request->getPost();
                    if ($formData['ImageSrc'] <> $event['ImageSrc']){
                        if ($formData['ImageSrc'] == "")
                            $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = Zend_Registry::get("www_root")."/data/images/event/$eventID/tmp/mcith/mcith_".$formData['ImageSrc'];        
                        
                        $isNewImage = 'true';
                    }
                    else{
                        if ($event['ImageSrc'] == "")
                            $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = Zend_Registry::get("www_root")."/data/images/event/$eventID/".str_replace($event['ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$event['ImageSrc'],$event['ImageSrc']);        
                    }                                                                                 
                }
                else{
                    if ( empty( $event['ImageSrc'] ) )
                        $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                    else
                        $imageSrc = Zend_Registry::get("www_root")."/data/images/event/$eventID/".str_replace($event['ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$event['ImageSrc'],$event['ImageSrc']);    
                }
                
                // generate the form
                $form = new FormEvents(array(
                    'baseDir'   => $baseDir,
                    'imageSrc'  => $imageSrc,
                    'cancelUrl' => "$baseDir$returnUrl",
                    'categoriesList' => "false",
                    'eventID' => $eventID,
                    'isNewImage'=>$isNewImage                    
                ));
                $this->view->form = $form;
                
                // action 
                if ( !$this->_request->isPost() ){
                    
                    if(isset($event['Status']) && $event['Status'] == 2)
                        $event['Status'] = 0;
                        
                    $form->populate($event);
                    
                } 
                else {
                    $formData = $this->_request->getPost();
                    if ($form->isValid($formData)) {        
                        if($formData['isNewImage'] == 'true' && $form->getValue('ImageSrc') <> ''){
                            $config = Zend_Registry::get('config')->toArray(); 
                            $srcOriginal    = "../../{$this->_config->document_root}/data/images/event/$eventID/tmp/".$form->getValue('ImageSrc');
                            $originalMaxHeight  = $config['event']['image']['original']['maxHeight'];
                            $originalMaxWidth   = $config['event']['image']['original']['maxWidth'];
                            $originalName       = str_replace($form->getValue('ImageSrc'),$originalMaxWidth.'x'.$originalMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                        
                            
                            $srcMedium       = "../../{$this->_config->document_root}/data/images/event/$eventID/tmp/medium_{$form->getValue('ImageSrc')}";
                            $mediumMaxHeight = $config['event']['image']['medium']['maxHeight'];
                            $mediumMaxWidth  = $config['event']['image']['medium']['maxWidth'];
                            $mediumName      = str_replace($form->getValue('ImageSrc'),$mediumMaxWidth.'x'.$mediumMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            $srcThumb       = "../../{$this->_config->document_root}/data/images/event/$eventID/tmp/thumb_{$form->getValue('ImageSrc')}";
                            $thumbMaxHeight = $config['event']['image']['thumb']['maxHeight'];
                            $thumbMaxWidth  = $config['event']['image']['thumb']['maxWidth'];
                            $thumbName      = str_replace($form->getValue('ImageSrc'),$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            copy($srcOriginal,$srcMedium);
                            copy($srcOriginal,$srcThumb);
                            
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcOriginal, 'maxWidth'=>$originalMaxWidth, 'maxHeight'=>$originalMaxHeight));
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcMedium, 'maxWidth'=>$mediumMaxWidth, 'maxHeight'=>$mediumMaxHeight));
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcThumb, 'maxWidth'=>$thumbMaxWidth, 'maxHeight'=>$thumbMaxHeight));
                            
                            rename($srcOriginal,"../../{$this->_config->document_root}/data/images/event/$eventID/$originalName");
                            rename($srcMedium,"../../{$this->_config->document_root}/data/images/event/$eventID/$mediumName");
                            rename($srcThumb,"../../{$this->_config->document_root}/data/images/event/$eventID/$thumbName");   
                        }
                        if(!empty($pageID))
                        {
                            //$blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
                            //$blockStatus    = $blockData['B_Online'];
                            
                            $indexData['pageID']    = $event['CategoryID'];
                            $indexData['moduleID']  = $this->_moduleID;
                            $indexData['contentID'] = $eventID;
                            $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                            $indexData['title']     = $formData['Title'];
                            $indexData['text']      = '';
                            $indexData['link']      = '';
                            $indexData['contents']  = $formData['Title'] . " " . $formData['Brief'] . " " . $formData['Text'] . " " . $formData['ImageAlt'];
                            //Cible_FunctionsIndexation::indexation($indexData);        
                            
                            if($formData['Status'] == 1)
                                $indexData['action'] = 'update';
                            else
                                $indexData['action'] = 'delete';
                        
                            Cible_FunctionsIndexation::indexation($indexData);        
                            
                        }
                        
                        if($formData['Status'] == 0)
                            $formData['Status'] = 2;
                            
                        $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['Title']);
                        $formData['ValUrl'] = $formattedName;
                       // echo $formData['ValUrl'];
                       // exit; 
                        $eventsObject->save($eventID, $formData, $this->getCurrentEditLanguage()); 
                        
                        // redirect
                        
                        if(!empty($pageID))
                            $this->_redirect("/events/index/list/blockID/$blockID/pageID/$pageID");
                        else
                            $this->_redirect($returnUrl);
                        
                    }
                }
            }
        }
        
        public function deleteAction(){
            
             // variables
            $pageID = (int)$this->_getParam( 'pageID' );
            $blockID = (int)$this->_getParam( 'blockID' );
            $eventID = (int)$this->_getParam( 'eventID' ); 
            
            $this->view->return = $this->view->baseUrl()."/events/index/list/blockID/$blockID/pageID/$pageID";
            
            $eventsObject = new EventsObject();
            
             if(Cible_ACL::hasAccess($pageID)){
                 if ($this->_request->isPost()) {
                     $del = $this->_request->getPost('delete');
                     if ($del && $eventID > 0) {
                         $eventsObject->delete($eventID);
                         
                         $indexData['moduleID']  = $this->_moduleID;
                         $indexData['contentID'] = $eventID;
                         $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                         $indexData['action']    = 'delete';
                         Cible_FunctionsIndexation::indexation($indexData);
                         
                         Cible_FunctionsGeneral::delFolder("../../{$this->_config->document_root}/data/images/event/".$eventID);       
                     }
                        
                     if(!empty($pageID))                           
                        $this->_redirect("/events/index/list/blockID/$blockID/pageID/$pageID");    
                     else
                        $this->_redirect("/events/index/list-all/");
                 }
                 else {
                    if ($eventID > 0) {
                        // get event details

                        $this->view->event = $eventsObject->populate($eventID, Zend_Registry::get('currentEditLanguage'));
                     }
                 }
             }            
        }
        
        public function toExcelAction(){
            $this->filename = 'Events.xlsx';
            
            $tables = array(                
                    'EventsData' => array('ED_ID','ED_CategoryID'),
                    'EventsIndex' => array('EI_EventsDataID','EI_LanguageID','EI_Title','EI_Status'),
                    'Status' => array('S_Code')
            );
                 
            $this->fields = array(
                'EI_Title' => array(
                    'width' => '',
                    'label' => ''
                ),
                'S_Code' => array(
                    'width' => '',
                    'label' => ''
                )
            );
            
            $this->filters = array(
                
            );
            
            $this->view->params = $this->_getAllParams();
            
            $events = new EventsData();
            $this->select = $this->_db->select()
                ->from('EventsData')
                //->setIntegrityCheck(false)
                ->join('EventsIndex', 'EventsData.ED_ID = EventsIndex.EI_EventsDataID')
                ->join('Status', 'EventsIndex.EI_Status = Status.S_ID')
                ->where('EI_LanguageID = ?', $this->_defaultEditLanguage)
                ->order('EI_Title');
           
            $blockID = $this->_getParam( 'blockID' );
            $pageID  = $this->_getParam( 'pageID' );
            
            if( $blockID && $pageID ){
                $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
                $categoryID = $blockParameters[0]['P_Value'];
                
                $this->select->where('ED_CategoryID = ?', $categoryID);
            }
           
            parent::toExcelAction();
        }
        
        public function deleteCategoriesAction(){
            
            if( $this->view->aclIsAllowed($this->view->current_module, 'edit') ){ 
                $id = $this->_getParam('ID');
                
                if($this->_request->isPost() && isset($_POST['delete']) ){
                    
                    $this->_db->delete('Categories', "C_ID = '$id'");
                    $this->_db->delete('CategoriesIndex', "CI_CategoryID = '$id'");
                    
                    $this->_redirect("/events/index/list-categories/");
                    
                } else if( $this->_request->isPost() && isset($_POST['cancel']) ){
                    $this->_redirect('/events/index/list-categories/');
                } else {
                    $fails = false;
                
                    $select = $this->_db->select();
                    $select->from('CategoriesIndex', array('CI_Title'))
                           ->where('CategoriesIndex.CI_CategoryID = ?', $id);
                           
                    $categoryName = $this->_db->fetchOne($select);
                    
                    $this->view->assign('category_id', $id);
                    $this->view->assign('category_name', $categoryName);

                    $select = $this->_db->select();
                    $select->from('EventsData')
                           ->where('EventsData.ED_CategoryID = ?', $id);
                           
                    $result = $this->_db->fetchAll($select);
                    
                    if( $result ){
                        $fails = true;
                    }
                    
                    if( !$fails ){
                        $select = $this->_db->select();
                        $select->from('Blocks')
                               ->joinRight('Parameters', 'Parameters.P_BlockID = Blocks.B_ID')
                               ->where('Parameters.P_Number = ?', 1)
                               ->where('Parameters.P_Value = ?', $id)
                               ->where('Blocks.B_ModuleID = ?', $this->_moduleID);
                               
                        $result = $this->_db->fetchAll($select);    
                        
                        if( $result ){
                            $fails = true;
                        }
                    }
                    
                    $this->_db->delete('ModuleCategoryViewPage', $this->_db->quoteInto('MCVP_CategoryID = ?',$id));
                    
                    $this->view->assign('module_name', $this->_moduleName);
                    $this->view->assign('module_id', $this->_moduleID);
                    $this->view->assign('returnUrl', '/events/index/list-categories/');
                    $this->view->assign('fails', $fails);
                }
                
            }
        }
        
        private function getEventsOnlineCount($categoryID)
        {
            return $this->_db->fetchOne("SELECT COUNT(*) FROM EventsData LEFT JOIN EventsIndex ON EventsData.ED_ID = EventsIndex.EI_EventsDataID WHERE ED_CategoryID = '$categoryID' AND EI_Status = '1'");
        }
    }
?>