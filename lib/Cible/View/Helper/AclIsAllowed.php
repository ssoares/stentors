<?php
    class Cible_View_Helper_aclIsAllowed extends Zend_View_Helper_Abstract
    {
        public function aclIsAllowed($resource, $action, $noRender = false){
            $auth = Zend_Auth::getInstance();
            $data = (array)$auth->getStorage()->read();
            $authID = $data['EU_ID'];
            
            $aclSession = new Zend_Session_Namespace();
            $acl = $aclSession->acl;
            
            if ( $this->view->IsAdministrator() || $acl->isAllowed($authID, $resource, $action)){
                return true;
            }
                
            else{
                if ($noRender){
                    Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender();
                    echo($this->view->render('errors/acl-denied.phtml'));
                }
                return false;
            }
        }
    }