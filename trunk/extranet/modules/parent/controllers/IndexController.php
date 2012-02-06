<?php

class Parent_IndexController extends Cible_Extranet_Controller_Import_Action implements Zend_Acl_Resource_Interface
{
    protected $_moduleID      = 30;
    protected $_defaultAction = '';
    protected $_moduleTitle   = 'parent';
    protected $_name          = 'index';
    protected $_ID            = 'id';

    public function  getResourceId()
    {
        return $this->_moduleTitle;
    }

    public function listAction()
    {
        // web page title
        $this->view->title = "Membres";
        if ($this->view->aclIsAllowed($this->_moduleTitle,'edit',true))
        {
            $profile = new GenericProfilesObject();
            $parent  = new ParentProfilesObject();
            $oRef  = new ReferencesObject();
            $select= $profile->getAll(null, false);
            $select->columns(
                array(
                'member_id' => 'GP_memberID',
                'lastName'  => 'GP_LastName',
                'firstName' => 'GP_FirstName',
                'email'     => 'GP_Email')
                );
            $select->joinRight(
                $parent->getDataTableName(),
                $parent->getDataId() . ' = ' . $profile->getDataId(),
                array('role' => 'PP_Role')
            );
            $select->joinLeft(
                $oRef->getDataTableName(),
                $oRef->getDataId() . ' = PP_Role',
                array('R_TypeRef')
            );
            $select->joinLeft(
                $oRef->getIndexTableName(),
                $oRef->getIndexId() . ' = ' . $oRef->getDataId(),
                array('role' => 'RI_Value')
            );

            $tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'ParentsProfile' => array('PP_GenericProfileId', 'PP_Role'),
                $oRef->getDataTableName() => array('R_TypeRef'),
                $oRef->getIndexTableName() => array('RI_Value')
            );

            $field_list = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'  => array('width' => '250px'),
                'role'  => array('width' => '250px'),
            );

            $this->view->params = $this->_getAllParams();

            $pageID = $this->_getParam( 'pageID' );
            $langId = $this->_registry->languageID;
            $options = array(
                'commands' => array(
                    $this->view->link(
                            $this->view->url(
                                    array(
                                        'module'=>'users',
                                        'controller'=>'index',
                                        'action'=> 'general',
                                        'actionKey'=> 'add',
                                        'returnModule'=> $this->_moduleTitle,
                                        'returnAction'=>'list'
                                        )
                                    ),
                            $this->view->getCibleText('button_add_profile'),
                            array('class'=>'action_submit add')
                            )
                ),
//                'excludedColums' => array('Nom'), // columns to exclude from search
                'disable-export-to-excel' => '',
                'to-excel-action' => 'clients-to-excel',
                'filters' => array(
//                    'filter_1' => array(
//                        'label' => 'Liste des ...',
//                        'default_value' => null,
//                        'associatedTo' => 'GP_MemberID',
//                        'equalTo' => 'R_GenericProfileId',
//                        'choices' => array(
//                            ''  => 'Liste des détaillants',
//                            '1' => "--> Affichés sur le site"
//                            )
//                    ),
//                    'filter_2' => array(
//                        'label' => 'Liste des ...',
//                        'default_value' => null,
//                        'associatedTo' => 'MP_Status',
//                        'choices' => array(
//                            '' => 'Filtrer par statut',
//                            '-1' => 'Désactivé',
//                            '0' => 'Email non validé',
//                            '1' => 'À valider',
//                            '2' => 'Activé'
//                        )
//                    )
                ),
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                            'edit' => array(
                            'label' => $this->view->getCibleText('menu_submenu_action_edit'),
                            'url' => $this->view->url(
                                    array('module'=>'users',
                                        'action' => 'general',
                                        'actionKey'=> 'edit',
                                        $this->_ID  => "-ID-",
                                        'returnModule'=>$this->_moduleTitle,
                                        'returnAction'=>'list'
                                        )
                                    ),
                            'findReplace' => array(
                                'search' => '-ID-',
                                'replace' => 'member_id'
                            )
                        ),
                        'delete' => array(
                            'label' => $this->view->getCibleText('menu_submenu_action_delete'),
                            'url' => $this->view->url(
                                        array('module'=>'users',
                                        'action' => 'general',
                                        'actionKey'=> 'delete',
                                        $this->_ID  => "-ID-",
                                        'returnModule'=>$this->_moduleTitle,
                                        'returnAction'=>'list'
                                        )
                                    ),
                            'findReplace' => array(
                                'search' => '-ID-',
                                'replace' => 'member_id'
                            )
                        )
                    )
                )
            );

            $mylist = New Cible_Paginator(
                    $select,
                    $tables,
                    $field_list,
                    $options
                    );
            $this->view->assign('mylist', $mylist);
        }
    }

    public function addAction(){
        throw new Exception('Not implemented');
    }

    public function editAction(){
        throw new Exception('Not implemented');
    }

    public function deleteAction(){
        throw new Exception('Not implemented');
    }
}