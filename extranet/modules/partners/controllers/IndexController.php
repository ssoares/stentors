<?php

class Partners_IndexController extends Cible_Extranet_Controller_Import_Action implements Zend_Acl_Resource_Interface
{
    protected $_moduleID      = 30;
    protected $_defaultAction = '';
    protected $_moduleTitle   = 'partners';
    protected $_name          = 'index';
    protected $_ID            = 'id';

    public function  getResourceId()
    {
        return $this->_moduleTitle;
    }

    public function listAction()
    {
        // web page title
        $this->view->title = "Partenaires";
        if ($this->view->aclIsAllowed($this->_moduleTitle,'edit',true))
        {
            $generic = new GenericProfilesObject();
            $generic->setOrderBy('GP_LastName');

            $profile  = new PartnersProfilesObject();
            $select = $generic->getAll(null, false);
            $select->columns(
                array(
                    'lastName'  => 'GP_LastName',
                    'firstName' => 'GP_FirstName',
                    'email'     => 'GP_Email',
                    'member_id' => 'GP_MemberID'
                    )
                );
            $select->joinRight(
                    $profile->getDataTableName(),
                    $profile->getDataId() . ' = ' . $generic->getDataId()
                    );

            $tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'MemberProfiles' => array('PP_GenericProfileId'),
            );

            $field_list = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'  => array('width' => '250px'),
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
                'enable-print' => false,
                'disable-export-to-excel' => '',
//                'to-excel-action' => 'clients-to-excel',
                'filters' => array(),
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
        $this->filename = 'partnersList.xls';
        $this->type = 'Excel5';
        $generic = new GenericProfilesObject();
            $generic->setOrderBy('GP_LastName');

            $profile  = new PartnersProfilesObject();
            $oAddr  = new AddressObject();
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
                    $oAddr->getDataTableName(),
                    $oAddr->getDataId() . ' = ' . $profile->getAddressField()
                    );
            $this->select->joinLeft(
                    $oAddr->getIndexTableName(),
                    $oAddr->getIndexId() . ' = ' . $oAddr->getDataId()
                    );

            $this->tables = array(
                'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
                'PartnersProfile' => array('PP_GenericProfileId', 'PP_Company', 'PP_Function'),
                'AddressData' => array('A_CityTextValue'),
                'AddressIndex' => array('AI_FirstTel', 'AI_SecondTel'),
            );

            $this->fields = array(
                'firstName' => array('width' => '250px'),
                'lastName'  => array('width' => '250px'),
                'email'  => array('width' => '250px'),
                'PP_Company'  => array('width' => '250px'),
                'PP_Function'  => array('width' => '250px'),
                'AI_FirstTel'  => array('width' => '250px'),
                'AI_SecondTel'  => array('width' => '250px'),
            );

        $this->filters = array();

        parent::toExcelAction();
    }
}