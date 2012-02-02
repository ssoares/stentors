<?php
    class Newsletter_ArticleController extends Cible_Extranet_Controller_Module_Action
    {
        function indexAction(){
            
        }
        
        function addAction(){
            $this->view->title = "Ajout d'un article à une parution";
            
            $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
            $this->view->assign('success', false);
            
            if ($this->view->aclIsAllowed('newsletter','manage',true)){
                //$this->headScript()->appendFile($this->view->baseUrl() .'/js/tiny_mce/tiny_mce.js');
                //$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tiny_mce/tiny_mce.js');
                $pageID         = $this->_getParam('pageID');
                $releaseID      = $this->_getParam('releaseID');
                $zoneID         = $this->_getParam('zoneID');
                $positionID     = $this->_getParam('positionID');
                $baseDir        = $this->view->baseUrl(); 
                
                $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg"; 
                
                if ($this->_request->isPost()) {
                    $formData = $this->_request->getPost();
                    if($formData['ImageSrc'] <> "")
                        $imageSrc   = Zend_Registry::get("www_root")."/data/images/newsletter/tmp/mcith/mcith_".$formData['ImageSrc'];    
                }
                
                // generate the form
                $cancelUrl =  $this->view->baseUrl()."/newsletter/index/edit/newsletterID/$releaseID";
                $form = new FormNewsletterArticle(array(
                    'baseDir'   => $baseDir,
                    'cancelUrl' => $cancelUrl,
                    'newsletterID'=>$releaseID,
                    'imageSrc'  => $imageSrc,
                    'isNewImage'=> true   
                ));
               
                $this->view->form = $form;
                
                if ($this->_request->isPost()){
                    $formData = $this->_request->getPost();    
                    if ($form->isValid($formData)) {
                        
                        // update position for all article higher
                        $newsletterArticlePosition = new NewsletterArticles();
                        $where  = 'NA_PositionID >= '.$positionID;
                        $where .= ' AND NA_ReleaseID = '.$releaseID;
                        $where .= ' AND NA_ZoneID = '.$zoneID;
                        $newsletterArticlePosition->update(array('NA_PositionID'=>new Zend_Db_Expr('NA_PositionID + 1 ')),$where);
                        
                        // add article in BD
                        $newsletterArticle = new NewsletterArticles();
                        $newsletterArticleData = $newsletterArticle->createRow();
                        $newsletterArticleData->NA_ReleaseID    = $releaseID;
                        $newsletterArticleData->NA_ZoneID       = $zoneID;
                        $newsletterArticleData->NA_PositionID   = $positionID;
                        $newsletterArticleData->NA_Title    = $form->getValue('NA_Title');
                        $newsletterArticleData->NA_Resume   = $form->getValue('NA_Resume');
                        $newsletterArticleData->NA_Text     = $form->getValue('NA_Text');
                        $newsletterArticleData->NA_URL   = $form->getValue('NA_URL');
                        $newsletterArticleData->NA_TextLink   = $form->getValue('NA_TextLink');
                        $newsletterArticleData->NA_ValUrl   = Cible_FunctionsGeneral::formatValueForUrl($form->getValue('NA_Title'));
                        $newsletterArticleData->save();
                        
                        $articleID =  $newsletterArticleData->NA_ID;
                        
                        if($form->getValue('ImageSrc') <> ''){
                            $config = Zend_Registry::get('config')->toArray(); 
                            $srcOriginal    = "../../{$this->_config->document_root}/data/images/newsletter/$releaseID/tmp/".$form->getValue('ImageSrc');
                            $originalMaxHeight  = $config['newsletter']['image']['original']['maxHeight'];
                            $originalMaxWidth   = $config['newsletter']['image']['original']['maxWidth'];
                            $originalName       = str_replace($form->getValue('ImageSrc'),$originalMaxWidth.'x'.$originalMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            $srcThumb       = "../../{$this->_config->document_root}/data/images/newsletter/$releaseID/tmp/thumb_{$form->getValue('ImageSrc')}";
                            $thumbMaxHeight = $config['newsletter']['image']['thumb']['maxHeight'];
                            $thumbMaxWidth  = $config['newsletter']['image']['thumb']['maxWidth'];
                            $thumbName      = str_replace($form->getValue('ImageSrc'),$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            copy($srcOriginal,$srcThumb);
                            
                            
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcOriginal, 'maxWidth'=>$originalMaxWidth, 'maxHeight'=>$originalMaxHeight));
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcThumb, 'maxWidth'=>$thumbMaxWidth, 'maxHeight'=>$thumbMaxHeight));
                            
                            if(!is_dir("../../{$this->_config->document_root}/data/images/newsletter/$releaseID")){
                                mkdir("../../{$this->_config->document_root}/data/images/newsletter/$releaseID") or die ("Could not make directory");
                            }
                            
                            mkdir("../../{$this->_config->document_root}/data/images/newsletter/$releaseID/$articleID") or die ("Could not make directory");
                            
                            rename($srcOriginal,"../../{$this->_config->document_root}/data/images/newsletter/$releaseID/$articleID/$originalName") or die();
                            rename($srcThumb,"../../{$this->_config->document_root}/data/images/newsletter/$releaseID/$articleID/$thumbName") or die();   
                                
                           $newsletterArticleData['NA_ImageSrc'] = $form->getValue('ImageSrc');
                           $newsletterArticleData['NA_ImageAlt'] = $form->getValue('NA_ImageAlt');
                           
                           $newsletterArticleData->save();  
                        }
                        
                        
                        $this->_redirect("/newsletter/index/edit/newsletterID/$releaseID");
                            
                                
                            }
                    else{
                        $form->populate($formData);
                    }
                }
                
            }    
        }
        
        function editAction(){
            $this->view->title = "'Modification d'un article à une parution";
            
            $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
            $this->view->assign('success', false);
            
            if ($this->view->aclIsAllowed('newsletter','manage',true)){
                $pageID         = $this->_getParam('pageID'); 
                $newsletterID   = $this->_getParam('newsletterID');
                $articleID      = $this->_getParam('articleID');
                $baseDir        = $this->view->baseUrl();
                
                $newsletterArticleSelect = new NewsletterArticles();
                $select = $newsletterArticleSelect->select();
                $select->where('NA_ID = ?', $articleID);
                $newsletterArticleData = $newsletterArticleSelect->fetchRow($select);
                
                // generate the form
                $config = Zend_Registry::get('config')->toArray(); 
                $thumbMaxHeight = $config['newsletter']['image']['thumb']['maxHeight'];
                $thumbMaxWidth  = $config['newsletter']['image']['thumb']['maxWidth'];
                $this->view->assign('imageUrl', $imageSrc = Zend_Registry::get("www_root")."/data/images/newsletter/$newsletterID/{$newsletterArticleData['NA_ID']}/".str_replace($newsletterArticleData['NA_ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$newsletterArticleData['NA_ImageSrc'],$newsletterArticleData['NA_ImageSrc']));
                
                $isNewImage = 'false';
                if ($this->_request->isPost()){
                    $formData = $this->_request->getPost();
                    if ($formData['ImageSrc'] <> $newsletterArticleData['NA_ImageSrc']){
                        if ($formData['ImageSrc'] == "")
                            $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = Zend_Registry::get("www_root")."/data/images/newsletter/$newsletterID/tmp/mcith/mcith_".$formData['ImageSrc'];        
                        
                        $isNewImage = 'true';
                    }
                    else{
                        if ($newsletterArticleData['NA_ImageSrc'] == "")
                            $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = Zend_Registry::get("www_root")."/data/images/newsletter/$newsletterID/{$newsletterArticleData['NA_ID']}/".str_replace($newsletterArticleData['NA_ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$newsletterArticleData['NA_ImageSrc'],$newsletterArticleData['NA_ImageSrc']);                                    
                    }
                }
                else{
                    if ($newsletterArticleData['NA_ImageSrc'] == '')
                        $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                    else
                        $imageSrc = Zend_Registry::get("www_root")."/data/images/newsletter/$newsletterID/{$newsletterArticleData['NA_ID']}/".str_replace($newsletterArticleData['NA_ImageSrc'],$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$newsletterArticleData['NA_ImageSrc'],$newsletterArticleData['NA_ImageSrc']);        
                }
                
                $cancelUrl =  $this->view->baseUrl()."/newsletter/index/edit/newsletterID/$newsletterID";
                $form = new FormNewsletterArticle(array(
                    'baseDir'   => $baseDir,
                    'cancelUrl' => $cancelUrl,
                    'newsletterID'=>$newsletterID,
                    'imageSrc'  => $imageSrc,
                    'isNewImage'=> $isNewImage  
                ));
                
                if ($this->_request->isPost()) {
                    $formData = $this->_request->getPost();
                    if ($form->isValid($formData)) {
                        $newsletterArticleData['NA_ImageAlt'] = $form->getValue('NA_ImageAlt');                        
                        if($formData['isNewImage'] == 'true' && $form->getValue('ImageSrc') <> ''){
                            $srcOriginal    = "../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/tmp/".$form->getValue('ImageSrc');
                            $originalMaxHeight  = $config['newsletter']['image']['original']['maxHeight'];
                            $originalMaxWidth   = $config['newsletter']['image']['original']['maxWidth'];
                            $originalName       = str_replace($form->getValue('ImageSrc'),$originalMaxWidth.'x'.$originalMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            $srcThumb       = "../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/tmp/thumb_{$form->getValue('ImageSrc')}";
                            $thumbMaxHeight = $config['newsletter']['image']['thumb']['maxHeight'];
                            $thumbMaxWidth  = $config['newsletter']['image']['thumb']['maxWidth'];
                            $thumbName      = str_replace($form->getValue('ImageSrc'),$thumbMaxWidth.'x'.$thumbMaxHeight.'_'.$form->getValue('ImageSrc'),$form->getValue('ImageSrc'));
                            
                            copy($srcOriginal,$srcThumb);                            
                            
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcOriginal, 'maxWidth'=>$originalMaxWidth, 'maxHeight'=>$originalMaxHeight));
                            Cible_FunctionsImageResampler::resampled(array('src'=>$srcThumb, 'maxWidth'=>$thumbMaxWidth, 'maxHeight'=>$thumbMaxHeight));
                            
                            if(!is_dir("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID")){
                                mkdir("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID") or die ("Could not make directory");
                            }
                            else{
                                foreach(glob("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID/*.*") as $v)
                                    unlink($v);
                            }
                            
                            rename($srcOriginal,"../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID/$originalName");
                            rename($srcThumb,"../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID/$thumbName");   
                                
                           $newsletterArticleData['NA_ImageSrc'] = $form->getValue('ImageSrc');
                        }
                        elseif(($formData['isNewImage'] == 'true')&&($form->getValue('ImageSrc')=='')){
                            if(is_dir("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID")){
                                foreach(glob("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID/*.*") as $v)
                                    unlink($v);
                            }
                            $newsletterArticleData['NA_ImageSrc'] = '';
                            $newsletterArticleData['NA_ImageAlt'] = '';                               
                        }                        
                        $newsletterArticleData['NA_Title']  = $form->getValue('NA_Title');
                        $newsletterArticleData['NA_ValUrl']   = Cible_FunctionsGeneral::formatValueForUrl($form->getValue('NA_Title'));
                        $newsletterArticleData['NA_Resume'] = $form->getValue('NA_Resume');
                        $newsletterArticleData['NA_Text']   = $form->getValue('NA_Text');
                        $newsletterArticleData['NA_URL']   = $form->getValue('NA_URL');
                        $newsletterArticleData['NA_TextLink']   = $form->getValue('NA_TextLink');                        
                        $newsletterArticleData->save();                        
                        $this->_redirect("/newsletter/index/edit/newsletterID/$newsletterID");
                    }
                    else{
                        $this->view->form = $form;
                    }
                }
                else{
                    $form->populate($newsletterArticleData->toArray());
                    $this->view->form = $form;
                    
                }         
            }    
        }
        
        function deleteAction(){
            $this->view->title = "'Suppression d'un article à une parution";
            
            $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
            $this->view->assign('success', false);
            
            if ($this->view->aclIsAllowed('newsletter','manage',true)){
                 // variables
                $articleID      = $this->_getParam('articleID');
                $newsletterID   = $this->_getParam('newsletterID');
                $return =  "/newsletter/index/edit/newsletterID/$newsletterID";
                $this->view->return = $this->view->baseUrl() . $return;
                
                $newsletterArticleSelect = new NewsletterArticles();
                $select = $newsletterArticleSelect->select();
                $select->where('NA_ID = ?', $articleID);
                $newsletterArticleData = $newsletterArticleSelect->fetchRow($select);
                
                if(!$newsletterArticleData){
                    if ($this->_request->isPost()) {
                        $this->view->assign('success', true);
                    }
                    $this->view->assign('deleted', true);    
                    $this->view->assign('articleID', $articleID);    
                }
                else{
                    $this->view->assign('deleted', false);    
                    $this->view->newsletterArticle =  $newsletterArticleData->toArray();
                    if ($this->_request->isPost()) {
                         $del = $this->_request->getPost('delete');
                         if ($del && $newsletterArticleData) {
                             // delete index
                             // check if release is online
                            $releaseSelect = new NewsletterReleases();
                            $select = $releaseSelect->select()
                            ->where('NR_ID = ?', $newsletterArticleData['NA_ReleaseID']);
                            $releaseData = $releaseSelect->fetchRow($select);
                            
                             $indexData['moduleID']  = 8;
                             $indexData['contentID'] = $newsletterArticleData['NA_ID'];
                             $indexData['languageID'] = $releaseData['NR_LanguageID'];
                             $indexData['action']    = 'delete';
                             Cible_FunctionsIndexation::indexation($indexData);        
                             
                             Cible_FunctionsGeneral::delFolder("../../{$this->_config->document_root}/data/images/newsletter/$newsletterID/$articleID");
                             
                             // update position for all article higher
                             $newsletterArticlePosition = new NewsletterArticles();
                             $where  = 'NA_PositionID > '.$newsletterArticleData['NA_PositionID'];
                             $where .= ' AND NA_ReleaseID = '.$newsletterArticleData['NA_ReleaseID'];
                             $where .= ' AND NA_ZoneID = '.$newsletterArticleData['NA_ZoneID'];
                             $newsletterArticlePosition->update(array('NA_PositionID'=>new Zend_Db_Expr('NA_PositionID - 1 ')),$where);
                             
                             $newsletterArticleData->delete();
                             
                             if( !$this->_isXmlHttpRequest ){
                                $this->_redirect($return);
                             }
                             else{
                                $this->view->assign('success', true);
                                $this->view->assign('articleID', $articleID);    
                            }    
                         }
                         else{
                             $this->_redirect($return);
                         }
                    }
                }
            }    
        }
        
        function updatepositionAction(){
            $articleID  = $this->_getParam('articleID');
            $newpositionID = $this->_getParam('newpositionID');
            
            $newsletterArticleSelect = new NewsletterArticles();
            $select = $newsletterArticleSelect->select();
            $select->where('NA_ID = ?', $articleID);
            $newsletterArticleData = $newsletterArticleSelect->fetchRow($select);
            
            $releaseID  = $newsletterArticleData['NA_ReleaseID'];
            $zoneID     = $newsletterArticleData['NA_ZoneID'];
            $oldpositionID = $newsletterArticleData['NA_PositionID'];
            
            
            // update position for all article higher
            $newsletterArticlePosition = new NewsletterArticles();
            
            if($newpositionID > $oldpositionID){
                $where  = 'NA_PositionID > '.$oldpositionID;
                $where .= ' AND NA_PositionID <= '.$newpositionID;
                $updatePosition = "-1";
            }
            else{
                $where  = 'NA_PositionID >= '.$newpositionID;
                $where .= ' AND NA_PositionID < '.$oldpositionID;
                $updatePosition = "1";
            }    
            $where .= ' AND NA_ReleaseID = '.$releaseID;
            $where .= ' AND NA_ZoneID = '.$zoneID;
            $newsletterArticlePosition->update(array('NA_PositionID'=>new Zend_Db_Expr('NA_PositionID + '.$updatePosition)),$where);
            
            
            $where  = ' NA_ID = ' . $articleID; 
            $newsletterArticlePosition->update(array('NA_PositionID'=>$newpositionID),$where); 
            
            $this->getHelper('viewRenderer')->setNoRender();                        
                
        }
        
        function updatezoneAction(){
            $articleID      = $this->_getParam('articleID');
            $newpositionID  = $this->_getParam('newpositionID');
            $newzoneID      = $this->_getParam('newzoneID');
            
            $newpositionID = $this->_getParam('newpositionID');
            
            $newsletterArticleSelect = new NewsletterArticles();
            $select = $newsletterArticleSelect->select();
            $select->where('NA_ID = ?', $articleID);
            $newsletterArticleData = $newsletterArticleSelect->fetchRow($select);
            
            $releaseID      = $newsletterArticleData['NA_ReleaseID'];
            $oldzoneID      = $newsletterArticleData['NA_ZoneID'];
            $oldpositionID  = $newsletterArticleData['NA_PositionID'];
            
            //update zone
            $newsletterArticleData['NA_ZoneID'] = $newzoneID;
            $newsletterArticleData->save();
            
            // update position (old zone)
            $newsletterArticlePosition = new NewsletterArticles();
            $where  = 'NA_PositionID > '.$oldpositionID;
            $where .= ' AND NA_ReleaseID = '.$releaseID;
            $where .= ' AND NA_ZoneID = '.$oldzoneID;
            $newsletterArticlePosition->update(array('NA_PositionID'=>new Zend_Db_Expr('NA_PositionID - 1 ')),$where); 
            
            // update position (new zone)
            $where  = 'NA_PositionID >= '.$newpositionID;
            $where .= ' AND NA_ReleaseID = '.$releaseID;
            $where .= ' AND NA_ZoneID = '.$newzoneID;
            $newsletterArticlePosition->update(array('NA_PositionID'=>new Zend_Db_Expr('NA_PositionID + 1 ')),$where); 
            
            // update position article
            $newsletterArticleData['NA_PositionID'] = $newpositionID;
            $newsletterArticleData->save();
            
            $this->getHelper('viewRenderer')->setNoRender();                            
        }
    }
?>
