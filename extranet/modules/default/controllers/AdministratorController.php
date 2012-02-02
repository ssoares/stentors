<?php
/**
* Make the management of the directors of the extranet
*
* The system can view, add, edit and remove directors of the extranet. It also allows an administrator to associate one or several groups to provide access rights.
*
* PHP versions 5
*
* LICENSE: 
*
* @category   Controller
* @package    Default
* @author     Alexandre Beaudet <alexandre.beaudet@ciblesolutions.com>
* @copyright  2009 CIBLE Solutions d'Affaires
* @license    http://www.ciblesolutions.com
* @version    CVS: <?php $ ?> Id:$
*/
class AdministratorController extends Cible_Extranet_Controller_Module_Action
{
    function indexAction()
    {
        // NEW LIST GENERATOR CODE //
        $tables = array(                
                'Extranet_Users' => array('EU_ID','EU_LName','EU_FName','EU_Email')
        );
        
        $field_list = array(
            'EU_FName' => array(
                'width' => '300px'
            ),
            'EU_LName' => array(
                'width' => '300px'
            ),
            'EU_Email' => array(
                'width' => '300px'
            )
        );
        
        $administratorData = new ExtranetUsers();
        $select = $administratorData->select();
        
        $options = array(
            'commands' => array(
                $this->view->link($this->view->url(array('controller'=>'administrator','action'=>'add')),$this->view->getCibleText('button_add_administrators'), array('class'=>'action_submit add') )
            ),
            //'disable-export-to-excel' => 'true',            
            'action_panel' => array(
                'width' => '50',
                'actions' => array(
                    'edit' => array(
                        'label' => $this->view->getCibleText('button_edit'),
                        'url' => "{$this->view->baseUrl()}/default/administrator/edit/administratorID/%ID%",
                        'findReplace' => array(
                            'search' => '%ID%',
                            'replace' => 'EU_ID'
                        )
                    ),
                    'delete' => array(
                        'label' => $this->view->getCibleText('button_delete'),
                        'url' => "{$this->view->baseUrl()}/default/administrator/delete/administratorID/%ID%",
                        'findReplace' => array(
                            'search' => '%ID%',
                            'replace' => 'EU_ID'
                        )
                    )
                ) 
            )
        );
        
        $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
        
        $this->view->assign('mylist', $mylist);
    }
    
    function editAction()
    {
        // page title
        $this->view->title = "Profil de l'administrateur";    
        
        // get param
        $administratorID = $this->_getParam('administratorID');
        $order           = $this->_getParam('order'); 
        $tablePage       = $this->_getParam('tablePage'); 
        $search          = $this->_getParam('search'); 
        
        $paramsArray = array("order" => $order, "tablePage" => $tablePage, "search" => $search);
        
        
        // get user data
        $userData = Cible_FunctionsAdministrators::getAdministratorData($administratorID);
        
        // get group data
        $groupsData = Cible_FunctionsAdministrators::getAllAdministratorGroups();
        
        
        /********** ACTIONS ***********/
        $returnLink = $this->view->url(array('controller' => 'administrator', 'action' => 'index', 'administratorID' => null));
        $form = new FormExtranetUser(array(
            'baseDir'   => $this->view->baseUrl(),
            'cancelUrl' => "$returnLink"
            ),
            $groupsData->toArray()
        );
        
        $this->view->assign('administratorID', $administratorID);
        $this->view->assign('form', $form);
        
        if ( !$this->_request->isPost() ){
            
            $userGroups = Cible_FunctionsAdministrators::getAllUserGroups($administratorID);
            
            $groupIDArray = array();
            $i = 0;
            foreach ($userGroups as $userGroup){
                $groupIDArray[$i] = $userGroup['EUG_GroupID'];
                $i++;
            }
            $form->getElement('groups')->setValue($groupIDArray);
            
            $form->populate($userData->toArray());
        } 
        else {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                // validate username is unique
                $findUser = new ExtranetUsers();
                $select = $findUser->select()
                ->where('EU_Username = ?', $userData['EU_Username'])
                ->where('EU_ID <> ?', $administratorID);
                    
                $findUserData = $findUser->fetchAll($select);
                
                // username is allready use
                if ($findUserData->count() > 0){
                    $form->getElement('EU_Username')->addError('Un autre utilisateur possède déjà ce nom d\'utilisateur');    
                }
                // save user information
                else{
                    $userData['EU_LName']       = $form->getValue('EU_LName');
                    $userData['EU_FName']       = $form->getValue('EU_FName');
                    $userData['EU_Email']       = $form->getValue('EU_Email');
                    $userData['EU_Username']    = $form->getValue('EU_Username');
                    
                    if ($form->getValue('EU_Password') <> ""){
                        $userData['EU_Password']  = md5($form->getValue('EU_Password'));    
                    }

                    $userData->save();
                    
                    
                    // delete all user and group association for that user
                    $userGroups = new ExtranetUsersGroups();
                    $where = 'EUG_UserID = ' . $administratorID;
                    $userGroups->delete($where);
                    
                    // insert all user and group association for that user
                    if ($formData['groups']){
                        foreach ($formData['groups'] as $group){
                            $userGroupAssociationData = new ExtranetUsersGroups();
                            
                            $row = $userGroupAssociationData->createRow();
                            $row->EUG_UserID    =   $administratorID;
                            $row->EUG_GroupID   =   $group;
                            
                            $row->save();
                        }   
                    }
                    header("location:".$returnLink);
                }
            }
        }
    }
    
    function addAction()
    {
        // page title
        $this->view->title = "Ajout d'un administrateur";    
        
        // get group data
        $groupsData = Cible_FunctionsAdministrators::getAllAdministratorGroups();
                
        /********** ACTIONS ***********/
        $returnLink = $this->view->url(array('controller' => 'administrator', 'action' => 'index'));
        $form = new FormExtranetUser(array(
            'baseDir'   => $this->view->baseUrl(),
            'cancelUrl' => "$returnLink"
            ),
            $groupsData->toArray()
        );
        
        
        $form->getElement('cancel')->setAttrib('onclick', 'document.location.href="'.$returnLink.'"');
        $form->getElement("EU_Password")->setRequired(true);
        $form->getElement("EU_Password")->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => "Veuillez entrer un mot de passe")));
        $this->view->form = $form;
        
        if ($this->_request->isPost() ){
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                // validate username is unique
                $findUser = new ExtranetUsers();
                $select = $findUser->select()
                ->where('EU_Username = ?', $form->getValue('EU_Username'));
                    
                $findUserData = $findUser->fetchAll($select);
                
                // username is allready use
                if ($findUserData->count() > 0){
                    $form->getElement('EU_Username')->addError('Un autre utilisateur possède déjà ce nom d\'utilisateur');    
                }
                else{
                    $userData = new ExtranetUsers();
                    $row = $userData->createRow();
                    $row->EU_LName      = $form->getValue('EU_LName');    
                    $row->EU_FName      = $form->getValue('EU_FName');
                    $row->EU_Email      = $form->getValue('EU_Email');
                    $row->EU_Username   = $form->getValue('EU_Username');
                    $row->EU_Password   = md5($form->getValue('EU_Password'));
                    
                    $newInsertID = $row->save();
                    
                    // insert all user and group association for that user
                    if ($formData['groups']){
                        foreach ($formData['groups'] as $group){
                            $userGroupAssociationData = new ExtranetUsersGroups();
                            
                            $rowGroup = $userGroupAssociationData->createRow();
                            $rowGroup->EUG_UserID    =   $newInsertID;
                            $rowGroup->EUG_GroupID   =   $group;
                            
                            $rowGroup->save();
                        }   
                    }                    
                    header("location:".$returnLink); 
                } 
                
            }    
        }
    }
    
    function deleteAction()
    {
        // set page title
        $this->view->title = "Supprimer un administrateur";
        
        // get params
        $administratorID = (int)$this->_getParam( 'administratorID' );
        
        if ($this->_request->isPost()) {
            // if is set delete, then delete
            $delete = isset($_POST['delete']);
            $returnLink = $this->view->url(array('controller' => 'administrator', 'action' => 'index', 'administratorID' => null));
            if ($delete && $administratorID > 0) {
                $user = new ExtranetUsers();
                $where = 'EU_ID = ' . $administratorID;
                $user->delete($where);
                
                
            }
            //$this->_redirect($returnLink);
            header("location:".$returnLink); 
        }
        else 
        {
            if ($administratorID > 0) {
                $administrator = new ExtranetUsers();
                $this->view->administrator = $administrator->fetchRow('EU_ID='.$administratorID);
            }
        }
    }
    
    public function toExcelAction(){
        $this->filename = 'Administrators.xlsx';
        
        $this->tables = array(
                'Extranet_Users' => array('EU_ID','EU_LName','EU_FName','EU_Email')
        );
        
        $this->fields = array(
            'EU_FName' => array(
                'width' => '',
                'label' => ''
            ),
            'EU_LName' => array(
                'width' => '',
                'label' => ''
            ),
            'EU_Email' => array(
                'width' => '',
                'label' => ''
            )
        );
        
        $this->filters = array(
            
        );
        
        $administratorData = new ExtranetUsers();
        $this->select = $administratorData->select();
        
        parent::toExcelAction();
    }
    
    public function profileAction(){
        // page title
        $this->view->title = "Votre profil";
        
        // get user data
        $authData = $this->view->user;
        $authID     = $authData['EU_ID'];
        
        $users = new ExtranetUsers();
        $select = $users->select()
        ->where("EU_ID = ?", $authID);
        
        $userData = $users->fetchRow($select);
        
        /********** ACTIONS ***********/
        $form = new FormExtranetUser(array(
            'baseDir'   => $this->view->baseUrl(),
            'cancelUrl' => $this->getFrontController()->getBaseUrl(),
            'profile' => true
        ));
        $this->view->form = $form;
        
        if ( !$this->_request->isPost() ){
            
            $form->populate($userData->toArray());
            
        } 
        else {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                // validate username is unique
                $findUser = new ExtranetUsers();
                $select = $findUser->select()
                ->where('EU_Username = ?', $userData['EU_Username'])
                ->where('EU_ID <> ?', $authID);
                    
                $findUserData = $findUser->fetchAll($select);
                
                // username is allready use
                if ($findUserData->count() > 0){
                    $form->getElement('EU_Username')->addError('Un autre utilisateur possède déjà ce nom d\'utilisateur');    
                }
                // save user information
                else{
                    $userData['EU_LName']       = $form->getValue('EU_LName');
                    $userData['EU_FName']       = $form->getValue('EU_FName');
                    $userData['EU_Email']       = $form->getValue('EU_Email');
                    $userData['EU_Username']    = $form->getValue('EU_Username');
                    
                    if ($form->getValue('EU_Password') <> ""){
                        $userData['EU_Password']  = md5($form->getValue('EU_Password'));    
                    }

                    $userData->save();
                    $this->_redirect('');
                }
            }
        }
    }
}
