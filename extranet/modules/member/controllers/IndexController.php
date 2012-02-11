<?php

class Member_IndexController extends Cible_Extranet_Controller_Import_Action implements Zend_Acl_Resource_Interface
{
    protected $_moduleID      = 30;
    protected $_defaultAction = '';
    protected $_moduleTitle   = 'member';
    protected $_name          = 'index';
    protected $_ID            = 'id';

    public function  getResourceId()
    {
        return $this->_moduleTitle;
    }

    public function listMembersAction()
    {
        // web page title
        $this->view->title = "Membres";
        if ($this->view->aclIsAllowed($this->_moduleTitle,'edit',true))
        {
            $profile = new GenericProfilesObject();
            $member  = new MemberProfilesObject();
            $oRef  = new ReferencesObject();
            $select = $profile->getAll(null, false);
            $select->columns(
                array(
                    'lastName'  => 'GP_LastName',
                    'firstName' => 'GP_FirstName',
                    'email'     => 'GP_Email',
                    'member_id' => 'GP_MemberID'
                    )
                );
            $select->joinRight(
                    $member->getDataTableName(),
                    $member->getDataId() . ' = ' . $profile->getDataId()
                    );
            $select->joinLeft(
                    $oRef->getDataTableName(),
                    $oRef->getDataId() . ' = MP_Section',
                    array('R_TypeRef')
                );
            $select->joinLeft(
                    $oRef->getIndexTableName(),
                    $oRef->getIndexId() . ' = ' . $oRef->getDataId(),
                    array('section' => 'RI_Value')
                );

            $tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'MemberProfiles' => array('MP_GenericProfileId', 'MP_Section'),
                $oRef->getDataTableName() => array('R_TypeRef'),
                $oRef->getIndexTableName() => array('RI_Value')
            );

            $field_list = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'  => array('width' => '250px'),
                'section'  => array('width' => '250px'),
            );

            $filtersList = $member->_sectionSrc();
            $filtersList[0] = 'Section';
            ksort($filtersList);
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
                                    'returnAction'=>'list-members'
                                    )
                                ),
                            $this->view->getCibleText('button_add_profile'),
                            array('class'=>'action_submit add')
                            )
                ),
//                'excludedColums' => array('Nom'), // columns to exclude from search
                'enable-print' => true,
                'disable-export-to-excel' => '',
//                'to-excel-action' => 'clients-to-excel',
                'filters' => array(
                    'filter_2' => array(
                        'label' => 'École',
                        'default_value' => null,
                        'associatedTo' => 'MP_School',
                        'choices' => ''
                    ),
                    'filter_3' => array(
                        'label' => 'Age',
                        'default_value' => null,
                        'associatedTo' => 'MP_Age',
                        'choices' => ''
                    ),
                    'filter_1' => array(
                        'label' => 'Section',
                        'default_value' => null,
                        'associatedTo' => 'MP_Section',
//                        'equalTo' => 'R_GenericProfileId',
                        'choices' => $filtersList
                    ),
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
                                        'returnAction'=>'list-members'
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
                                        array(
                                            'module'=>'users',
                                            'action' => 'general',
                                            'actionKey'=> 'delete',
                                            $this->_ID => "-ID-",
                                            'returnModule'=>$this->_moduleTitle,
                                            'returnAction'=>'list-members'
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