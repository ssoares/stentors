<?php

class Volunteers_IndexController extends Cible_Extranet_Controller_Import_Action implements Zend_Acl_Resource_Interface
{
    protected $_moduleID      = 30;
    protected $_defaultAction = '';
    protected $_moduleTitle   = 'volunteers';
    protected $_name          = 'index';
    protected $_ID            = 'id';

    public function  getResourceId()
    {
        return $this->_moduleTitle;
    }

    public function listAction()
    {
        // web page title
        $this->view->title = "Bénévoles";
        if ($this->view->aclIsAllowed($this->_moduleTitle,'edit',true))
        {
            $generic = new GenericProfilesObject();
            $generic->setOrderBy('GP_LastName');

            $profile  = new VolunteersProfilesObject();
            $oRef   = new ReferencesObject();
            $oYears = new YearsParticipateObject();
            $select = $profile->getAll(null, false);
            $select->joinLeft(
                $generic->getDataTableName(),
                $profile->getDataId() . ' = ' . $generic->getDataId(),
                array(
                    'lastName'  => 'GP_LastName',
                    'firstName' => 'GP_FirstName',
                    'email'     => 'GP_Email',
                    'member_id' => 'GP_MemberID'
                    )
                );
            $select->joinLeft(
                    $oYears->getDataTableName(),
                    $oYears->getForeignKey() . ' = ' . $profile->getDataId(),
                    array('YP_Year')
                );
            $select->joinLeft(
                    $oRef->getDataTableName(),
                    $oRef->getDataId() . ' = VP_Job',
                    array('R_TypeRef')
                );
            $select->joinLeft(
                    $oRef->getIndexTableName(),
                    $oRef->getIndexId() . ' = ' . $oRef->getDataId(),
                    array('VP_Job' => 'RI_Value')
                );

            $tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'VolunteersProfile' => array('VP_GenericProfileId', 'VP_Job'),
                $oRef->getDataTableName() => array('R_TypeRef'),
                $oRef->getIndexTableName() => array('RI_Value')
            );

            $field_list = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'  => array('width' => '250px'),
                'VP_Job'  => array('width' => '250px'),
            );

            $filtersList = $profile->_jobsListSrc();
            $filtersList[0] =$this->view->getCibleText('form_select_default_label');
            ksort($filtersList);

            $filterYears = $oYears->getListForFilter();
            sort($filtersList);


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
                'enable-print' => false,
                'disable-export-to-excel' => '',
//                'to-excel-action' => 'clients-to-excel',
                'filters' => array(
                    'filter_1' => array(
                        'label' => 'Poste',
                        'default_value' => null,
                        'associatedTo' => 'VP_Job',
//                        'equalTo' => 'R_GenericProfileId',
                        'choices' => $filtersList
                    ),
                    'filter_2' => array(
                        'label' => 'Année',
                        'default_value' => null,
                        'associatedTo' => 'YP_Year',
                        'choices' => $filterYears
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
                                        array(
                                            'module'=>'users',
                                            'action' => 'general',
                                            'actionKey'=> 'delete',
                                            $this->_ID => "-ID-",
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

    public function toExcelAction()
    {
        $this->filename = 'benevolesList.xls';
        $this->type = 'Excel5';
        $generic = new GenericProfilesObject();
            $generic->setOrderBy('GP_LastName');

            $profile  = new VolunteersProfilesObject();
            $oRef  = new ReferencesObject();
            $this->select = $profile->getAll(null, false);
            $this->select->joinLeft(
                $generic->getDataTableName(),
                $profile->getDataId() . ' = ' . $generic->getDataId(),
                array(
                    'lastName'  => 'GP_LastName',
                    'firstName' => 'GP_FirstName',
                    'email'     => 'GP_Email',
                    'member_id' => 'GP_MemberID'
                    )
                );
            $this->select->joinLeft(
                    $oRef->getDataTableName(),
                    $oRef->getDataId() . ' = VP_Job',
                    array('R_TypeRef')
                );
            $this->select->joinLeft(
                    $oRef->getIndexTableName(),
                    $oRef->getIndexId() . ' = ' . $oRef->getDataId(),
                    array('VP_Job' => 'RI_Value')
                );

            $this->tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'VolunteersProfile' => array('VP_GenericProfileId', 'VP_Job'),
                $oRef->getDataTableName() => array('R_TypeRef'),
                $oRef->getIndexTableName() => array('RI_Value')
            );

            $this->fields = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'   => array('width' => '250px'),
                'VP_Job'  => array('width' => '250px'),
            );

        $this->filters = array(

        );

        parent::toExcelAction();
    }
}