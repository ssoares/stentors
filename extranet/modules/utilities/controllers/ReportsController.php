<?php
/**
* Data management for the listings and reports
*
* PHP versions 5
*
* LICENSE:
*
* @category   Controller
* @package    Default
* @author     ssoares <sergio.soares@ciblesolutions.com>
* @copyright  2010 CIBLE Solutions d'Affaires
* @license    http://www.ciblesolutions.com
* @version    $Id:$
*/
class Utilities_ReportsController extends Cible_Controller_Block_Abstract
{
    protected $_labelSuffix;
    protected $_colTitle      = array();
    protected $_moduleID      = 0;
    protected $_defaultAction = 'list';
    protected $_moduleTitle   = 'utilities';
    protected $_name          = 'reports';
    protected $_ID            = 'id';
    protected $_currentAction = '';
    protected $_actionKey     = '';
    protected $_imageSrc      = '';

    protected $_imageFolder;
    protected $_rootImgPath;
    protected $_formName   = '';
    protected $_joinTables = array();
    protected $_objectList = array(
        'reports' => 'ReportsObject'
    );
    protected $_actionsList = array();

    protected $_disableExportToExcel = false;
    protected $_disableExportToPDF   = false;
    protected $_disableExportToCSV   = false;
    protected $_enablePrint          = false;
    protected $_setFilters           = false;
    protected $_filterParams         = array();
    protected $_modules              = array() ;

    /**
     * Set some properties to redirect and process actions.
     *
     * @access public
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        // Sets the called action name. This will be dispatched to the method
        $this->_currentAction = $this->_getParam('action');

        // The action (process) to do for the selected object
        $this->_actionKey = $this->_getParam('actionKey');

        $this->_formatName();
        $this->view->assign('cleaction', $this->_labelSuffix);
        $dataImagePath = "../../"
                . $this->_config->document_root
                . "/data/images/";

        if(isset($this->_objectList[$this->_currentAction]))
            $this->_imageFolder = $dataImagePath
                    . $this->_moduleTitle . "/"
                    . $this->_objectList[$this->_currentAction] . "/";

        if(isset($this->_objectList[$this->_currentAction]))
            $this->_rootImgPath = Zend_Registry::get("www_root")
                    . "/data/images/"
                    . $this->_moduleTitle . "/"
                    . $this->_objectList[$this->_currentAction] . "/";

        $this->_modules = Cible_FunctionsModules::modulesFilters();
        $this->view->headScript()->appendFile($this->view->locateFile('reportsAction.js'));
        $this->view->headLink()->appendStylesheet($this->view->locateFile('reports.css'));
    }

    /**
     * Dispatches actions for the references.
     *
     * @access public
     *
     * @return void
     */
    public function reportsAction($getParams = false)
    {
        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
//            $this->_disableExportToExcel = true;
            $this->_colTitle = array(
                'RE_ID'      => array('width' => '150px'),
                'RE_Label' => array('width' => '150px')
                );

            $this->_setFilters = true;
//            $oRef = new ReferencesObject();
//            $data = $oRef->getAll($this->_defaultEditLanguage);
//            $choices = array();
//            $choices[''] = $this->view->getCibleText('filter_empty_label');
//            foreach ($data as $ref)
//            {
//                $choices[$ref['R_TypeRef']] = $this->view->getCibleText('form_enum_' . $ref['R_TypeRef']);
//            }
//
//            $this->_filterParams = array(
//                'references-status-filter' => array(
//                    'label' => 'Filtre 1',
//                    'default_value' => null,
//                    'associatedTo' => 'R_TypeRef',
//                    'choices' => array_unique($choices)
//                )
//            );

            if($getParams)
            {
                $params = array(
                    'columns'    => $this->_colTitle,
                    'joinTables' => $this->_joinTables);

                return $params;
            }

            $this->_formName = 'FormReports';
            $this->_redirectAction();
        }
    }

    /**
     * Add action for the current object.
     *
     * @access public
     *
     * @return void
     */
    public function addAction()
    {
        $oDataName = $this->_objectList[$this->_currentAction];
        $oData     = new $oDataName();

        $this->_registry->currentEditLanguage = $this->_registry->languageID;

        $returnAction = $this->_getParam('return');

        $baseDir = $this->view->baseUrl() . "/";

        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            if ($returnAction)
                $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $returnAction;
            else
                $returnUrl = $this->_moduleTitle
                        . "/" . $this->_name . "/" . $this->_currentAction . "/";

            // generate the form
            $form = new $this->_formName(array(
                        'baseDir'    => $baseDir,
                        'cancelUrl'  => "$baseDir$returnUrl",
                        'moduleName' => $this->_moduleTitle . "/"
                            . $this->_objectList[$this->_currentAction],
                        'imageSrc'   => $imageSrc,
                        'imgField'   => $this->_imageSrc,
                        'object'     => $oData,
                        'dataId'     => '',
                        'isNewImage' => true
                    ));

            $this->view->form = $form;

            // action
            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();

//                $formData['LANGUAGE'] = $this->getCurrentEditLanguage();

                if ($form->isValid($formData))
                {
                    $formData = $this->_mergeFormData($formData);
                    $recordID = $oData->insert($formData, $this->_currentEditLanguage);
                    // redirect
                    if (isset($formData['submitSaveClose']))
                        $this->_redirect($returnUrl);
                    else
                    {
                        $this->_redirect(
                            $this->_moduleTitle . "/"
                        . $this->_name . "/"
                        . $this->_currentAction . "/actionKey/edit/"
                        . $this->_ID . "/" . $recordID
                        . "/return/" . $returnAction);
                    }
                }
                else
                {
                    $form->populate($formData);
                }
            }
        }
    }

    /**
     * Edit action for the current object.
     *
     * @access public
     *
     * @return void
     */
    public function editAction()
    {
        $imageSrc = "";
        $id       = (int) $this->_getParam($this->_ID);
        $page     = (int) $this->_getParam('page');

        $baseDir      = $this->view->baseUrl() . "/";
        $returnAction = $this->_getParam('return');
        $cancelUrl    = $baseDir . "/"
                . $this->_moduleTitle . "/"
                . $this->_name . "/"
                . $this->_currentAction
                . "/page/" . $page;

        $oDataName = $this->_objectList[$this->_currentAction];

        $oData = new $oDataName();


        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_currentAction . "/"
                    . "page/" . $page;

            // Get data details
            $data = $oData->populate($id, $this->_defaultEditLanguage);

            // generate the form
            $form = new $this->_formName(
                            array(
                                'moduleName' => $this->_moduleTitle . "/"
                                    . $this->_objectList[$this->_currentAction],
                                'baseDir'    => $baseDir,
                                'cancelUrl'  => $cancelUrl,
                                'imageSrc'   => $imageSrc,
                                'imgField'   => $this->_imageSrc,
                                'dataId'     => $id,
                                'data'       => $data,
                                'object'     => $oData,
                                'isNewImage' => 'true'
                            )
            );
            $this->view->form = $form;
            $this->view->modules = $this->_modules;
            // action
            if (!$this->_request->isPost())
            {
                $this->view->id = $id;
                $form->populate($data);
            }
            else
            {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData))
                {
                    $formData = $this->_mergeFormData($formData);
                    $oData->save($id, $formData, $this->getCurrentEditLanguage());
                    if (!$this->_isXmlHttpRequest)
                    {
                        // redirect
                        if (isset($formData['submitSaveClose']))
                            $this->_redirect($returnUrl);
                        else
                        {
                            $this->_redirect(
                                $this->_moduleTitle . "/"
                            . $this->_name . "/"
                            . $this->_currentAction . "/actionKey/edit/"
                            . $this->_ID . "/" . $id
                            . "/return/" . $returnAction);
                        }
                    }
                    else
                    {
                        echo json_encode(true);
                    }
                }
            }
        }
    }

    /**
     * Delete action for the current object.
     *
     * @access public
     *
     * @return void
     */
    public function deleteAction()
    {
        // variables
        $page = (int) $this->_getParam('page');
        $blockId = (int) $this->_getParam('blockID');
        $id = (int) $this->_getParam($this->_ID);

        $this->view->return = $this->view->baseUrl() . "/"
                . $this->_moduleTitle . "/"
                . $this->_name . "/"
                . $this->_currentAction . "/"
                . "page/" . $page;

        $this->view->action = $this->_currentAction;

        $returnAction = $this->_getParam('return');

        if ($returnAction)
            $returnUrl = $this->_moduleTitle . "/index/" . $returnAction;
        else
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_currentAction . "/"
                    . "page/" . $page;

        $oDataName = $this->_objectList[$this->_currentAction];
        $oData = new $oDataName();

        if (Cible_ACL::hasAccess($page))
        {
            if ($this->_request->isPost())
            {
                $del = $this->_request->getPost('delete');
                if ($del && $id > 0)
                {
                    $oData->delete($id);
                }

                $this->_redirect($returnUrl);
            }
            elseif ($id > 0)
            {
                // get date details
                $this->view->data = $oData->populate($id, $this->getCurrentEditLanguage());
            }
        }
    }

    /**
     * Creates the list of data for this action for the current object.
     *
     * @access public
     *
     * @param string $objectName String tot create the good object.
     *
     * @return void
     */
    private function _listAction($objectName)
    {
        $page = $this->_getParam('page');

        if ($page == '')
            $page = 1;
        // Create the object from parameter
        $oData = new $objectName();
        // get needed data to create the list
        $columnData  = $oData->getDataColumns();
        $dataTable   = $oData->getDataTableName();
        $indexTable  = $oData->getIndexTableName();
        $columnIndex = $oData->getIndexColumns();
        $tabId = $oData->getDataId();
        //Set the tables from previous collected data
        $tables = array(
            $dataTable => $columnData,
            $indexTable => $columnIndex
        );
        // Set the select query to create the paginator and the list.
        $select = $oData->getAll($this->_defaultEditLanguage, false);
        $params = array('constraint' => $oData->getConstraint());
        /* If needs to add some data from other table, tests the joinTables
         * property. If not empty add tables and join clauses.
         */
        $select = $this->_addJoinQuery($select, $params);
        // Set the the header of the list (columns name used to display the list)
        $field_list = $this->_colTitle;
        // Set the options of the list = links for actions (add, edit, delete...)
        $options = $this->_setActionsList($tabId, $page);
        //Create the list with the paginator data.
        $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
        // Assign a the view for rendering
        $this->_helper->viewRenderer->setRender($this->_defaultAction);
        //Assign to the render the list created previously.
        $this->view->assign('mylist', $mylist);
    }



    /**
     * Export data according to given parameters.
     *
     * @return void
     */
    public function toExcelAction()
    {
        $this->type     = 'CSV';
        $this->filename = $this->_actionKey . '.csv';
        $params         = array();

        $actionName = $this->_actionKey . 'Action';
        $params     = $this->$actionName(true);
        $oDataName  = $this->_objectList[$this->_actionKey];
        $lines      = new $oDataName();
        $constraint = $lines->getConstraint();

        $params['constraint'] = $constraint;

        $this->tables = array(
            $lines->getDataTableName() => $lines->getDataColumns()
        );

        $this->view->params = $this->_getAllParams();

        $columns       = array_keys($params['columns']);
        $this->fields  = array_combine($columns, $columns);
        $this->filters = array();

        $pageID = $this->_getParam('pageID');
        $langId = $this->_defaultEditLanguage;

        $select = $lines->getAll($langId, false);
        $select = $this->_addJoinQuery($select, $params);

        $this->select = $select;

        parent::toExcelAction();
    }

    /**
     * Format the current action name to bu used for label texts translations.
     *
     * @access private
     *
     * @return void
     */
    private function _formatName()
    {
        $this->_labelSuffix = str_replace(array('/', '-'), '_', $this->_currentAction);
    }

    /**
     * Reditects the current action to the "real" action to process.
     *
     * @access public
     *
     * @return void
     */
    private function _redirectAction()
    {
        //Redirect to the real action to process If no actionKey = list page.
        switch ($this->_actionKey)
        {
            case 'add':
                $this->addAction();
                $this->_helper->viewRenderer->setRender('add');
                break;
            case 'edit':
                $this->editAction();
                $this->_helper->viewRenderer->setRender('edit');
                break;
            case 'delete':
                $this->deleteAction();
                $this->_helper->viewRenderer->setRender('delete');
                break;

            default:
                $this->_listAction($this->_objectList[$this->_currentAction]);
                break;
        }
    }

    /**
     * Set options array or the list view. Options are the actions in the page.
     *
     * @access public
     *
     * @param int $tabId Id of the row to be processed.
     * @param int $page  Id of the page if selected with the paginator.
     *
     * @return void
     */
    private function _setActionsList($tabId, $page = 1)
    {
        $commands = array();
        $actions = array();
        $actionPanel = array(
            'width' => '50px'
        );

        $options = array();

        if (count($this->_actionsList) == 0)
            $this->_actionsList = array(
                array('commands' => 'add'),
                array('action_panel' => 'edit', 'delete'),
            );

        foreach ($this->_actionsList as $key => $controls)
        {
            $actionOpt = array();
            foreach ($controls as $key => $action)
            {
                $valAction = $action;
                //Redirect to the real action to process If no actionKey = list page.
                if (is_array($action))
                {
                    $valAction = key($action);
                     $actionOpt = $action[$valAction];
                }
                switch ($valAction)
                {
                    case 'add':
//                        $lang = $this->_getParam('lang');
//                        if (!empty ($lang))
//                            $langId = Cible_FunctionsGeneral::getLanguageID($lang);
//                        if ($langId == $this->_defaultEditLanguage)
//                        {
                            if (empty($actionOpt))
                                $actionsOpt = array(
                                    'controller' => $this->_name,
                                    'action' => $this->_currentAction,
                                    'actionKey' => $valAction);

                            $commands = array(
                                $this->view->link($this->view->url($actionsOpt),
                                $this->view->getCibleText(
                                    'button_add_' . $this->_labelSuffix),
                                    array('class' => 'action_submit add'))
                            );
//                        }
                        break;

                    case 'edit':
                        $edit = array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => $this->view->baseUrl() . "/"
                                . $this->_moduleTitle . "/"
                                . $this->_name . "/"
                                . $this->_currentAction . "/"
                                . "actionKey/edit/"
                                . $this->_ID . "/%ID%/page/" . $page,
                            'findReplace' => array(
                                array(
                                    'search' => '%ID%',
                                    'replace' => $tabId
                                )
                            ),
                        );

                        $actions['edit'] = $edit;
                        break;

                    case 'delete':
                        $delete = array(
                            'label' => $this->view->getCibleText('button_delete'),
                            'url'   => $this->view->baseUrl() . "/"
                                . $this->_moduleTitle . "/"
                                . $this->_name . "/"
                                . $this->_currentAction . "/"
                                . "actionKey/delete/"
                                . $this->_ID . "/%ID%/page/" . $page,
                            'findReplace' => array(
                                array(
                                    'search' => '%ID%',
                                    'replace' => $tabId
                                )
                            )
                        );

                        $actions['delete'] = $delete;
                        break;

                    default:

                        break;
                }
            }
        }
        $actionPanel['actions'] = $actions;

        $options = array(
            'commands'     => $commands,
            'action_panel' => $actionPanel
        );
        if ($this->_disableExportToExcel)
            $options['disable-export-to-excel']= 'true';
        if ($this->_disableExportToPDF)
            $options['disable-export-to-pdf']= 'true';
        if ($this->_disableExportToCSV)
            $options['disable-export-to-csv']= 'true';
        if ($this->_enablePrint)
            $options['enable-print']= 'true';

        $options['actionKey'] = $this->_currentAction;

        return $options;
    }

    /**
     * Transforms data of the posted form in one array
     *
     * @param array $formData Data to save.
     *
     * @return array
     */
    protected function _mergeFormData(array $formData)
    {
        (array)$tmpArray = array();

        foreach($formData as $key => $data)
        {
            if(is_array($data))
            {
                $tmpArray = array_merge($tmpArray,$data);
            }
            else
                $tmpArray[$key] = $data;
        }

        return $tmpArray;
    }

    /**
     * Add some data from other table, tests the joinTables
     * property. If not empty add tables and join clauses.
     *
     * @param Zend_Db_Table_Select $select
     * @param array $params
     *
     * @return Zend_Db_Table_Select
     */
    private function _addJoinQuery($select, array $params = array())
    {
        if (isset($params['joinTables']) && count($params['joinTables']))
            $this->_joinTables = $params['joinTables'];

        /* If needs to add some data from other table, tests the joinTables
         * property. If not empty add tables and join clauses.
         */
        if (count($this->_joinTables) > 0)
        {
            // Get the constraint attribute = foreign key to link tables.
            $constraint = $params['constraint'];
            // Loop on tables list(given by object class) to build the query
            foreach ($this->_joinTables as $key => $object)
            {
                //Create an object and fetch data from object.
                $tmpObject = new $object();
                $tmpDataTable = $tmpObject->getDataTableName();
                $tmpIndexTable = $tmpObject->getIndexTableName();
                $tmpColumnData = $tmpObject->getDataColumns();
                $tmpColumnIndex = $tmpObject->getIndexColumns();
                //Add data to tables list
                $tables[$tmpDataTable] = $tmpColumnData;
                $tables[$tmpIndexTable] = $tmpColumnIndex;
                //Get the primary key of the first data object to join table
                $tmpDataId = $tmpObject->getDataId();
                // If it's the first loop, join first table to the current table
                if ($key == 0)
                {
                    $select->joinLeft($tmpDataTable, $tmpDataId . ' = ' . $constraint);
                    //If there's an index table then it too and filter according language
                    if (!empty($tmpIndexTable))
                    {
                        $tmpIndexId = $tmpObject->getIndexId();
                        $select->joinLeft(
                                $tmpIndexTable,
                                $tmpDataId . ' = ' . $tmpIndexId);
                        $select->where(
                                $tmpObject->getIndexLanguageId() . ' = ?',
                                $this->_defaultEditLanguage);
                    }
                    /* If there's more than one table to link, store the current
                     * table name for the next loop
                     */
                    if (count($this->_joinTables) > 1)
                        $prevConstraint = $tmpObject->getConstraint();;
                }
                elseif ($key > 0)
                {
                    // We have an other table to join to previous.
                    $tmpDataId = $tmpObject->getDataId();

                    $select->joinLeft(
                            $tmpDataTable,
                            $prevConstraint . ' = ' . $tmpDataId);
                    if (!empty($tmpIndexTable))
                    {
                        $tmpIndexId = $tmpObject->getIndexId();
                        $select->joinLeft(
                                $tmpIndexTable,
                                $constraint . ' = ' . $tmpIndexId);
                        $select->where(
                                $tmpObject->getIndexLanguageId() . ' = ?',
                                $this->_defaultEditLanguage);
                    }
                }
            }
        }

        return $select;
    }
}