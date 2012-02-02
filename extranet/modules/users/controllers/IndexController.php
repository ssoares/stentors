<?php

class Users_IndexController extends Cible_Extranet_Controller_Module_Action
{
    protected $_labelSuffix;
    protected $_colTitle      = array();
    protected $_moduleID      = 20;
    protected $_defaultAction = '';
    protected $_defaultRender = 'list';
    protected $_moduleTitle   = 'users';
    protected $_name          = 'index';
    protected $_ID            = 'id';
    protected $_currentAction = '';
    protected $_actionKey     = '';
    protected $_imageSrc      = '';

    protected $_imageFolder;
    protected $_rootImgPath;
    protected $_formName   = 'FormGenericProfile';

    protected $_disableExportToExcel = false;
    protected $_disableExportToPDF   = false;
    protected $_disableExportToCSV   = false;
    protected $_enablePrint          = false;
    protected $_constraint;

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

        $dataImagePath = "../../"
                . $this->_config->document_root
                . "/data/images/";

        $this->_imageFolder = $dataImagePath
                . $this->_moduleTitle . "/";

        $this->_rootImgPath = Zend_Registry::get("www_root")
                . "/data/images/"
                . $this->_moduleTitle . "/";
    }

    /**
     * List the users registered on the website (front office).
     * This list is to create for each new project.
     */
    public function listAction()
    {

        $searchfor = utf8_decode($this->_request->getParam('searchfor'));
        $filters = '';

        $profile = new GenericProfile();
        $member = new MemberProfile();
        $select = $profile->getSelectStatement();
//        $oRetailer = new RetailersObject();
//        $selectRetailer = $oRetailer->getAll(null, false);
//        $selectRetailer->joinRight(
//            $profile->getGenericTable(),
//            'R_GenericProfileId = GP_MemberID',
//            array(
//                'lastName' => 'GP_LastName',
//                'firstName' => 'GP_FirstName',
//                'email' => 'GP_Email')
//        );
//
//        $select = $selectRetailer->joinRight(
//                $member->getTable(),
//                'GP_MemberID = MP_GenericProfileMemberID',
//                array(
//                    'member_id' => 'MP_GenericProfileMemberID',
//                    'MP_Status' => 'MP_Status')
//        );

        $tables = array(
            'GenericProfiles' => array('GP_LastName', 'GP_FirstName', 'GP_Email'),
            'RetailersData' => array('R_ID', 'R_GenericProfileID', 'R_AddressId', 'R_Status'),
        );

        $field_list = array(
            'lastName' => array('width' => '150px'),
            'firstName' => array('width' => '150px'),
            'email' => array('width' => '300px'),
//                'MP_Status'  => array('width' => '150px')
        );

        $options = array(
            'commands' => array(
                $this->view->link($this->view->url(array('controller' => 'index', 'action' => 'add')), $this->view->getCibleText('button_add_profile'), array('class' => 'action_submit add'))
            ),
            'disable-export-to-excel' => '',
            'filters' => array(
                'filter_1' => array(
                    'label' => 'Liste des détaillants',
                    'default_value' => null,
                    'associatedTo' => 'GP_MemberID',
                    'equalTo' => 'R_GenericProfileId',
                    'choices' => array(
                        '' => "--> A",
                        '1' => "--> Affichés sur le site"
                    )
                ),
                'filter_2' => array(
                    'label' => 'Liste des détaillants',
                    'default_value' => null,
                    'associatedTo' => 'MP_Status',
                    'choices' => array(
                        '' => 'Désactivé',
                        '0' => 'Email non validé',
                        '1' => 'À valider',
                        '2' => 'Activé'
                    )
                )
            ),
            'action_panel' => array(
                'width' => '50',
                'actions' => array(
                    'edit' => array(
                        'label' => $this->view->getCibleText('menu_submenu_action_edit'),
                        //'url' => "{$this->view->baseUrl()}/profile/index/edit/ID/%ID%",
                        'url' => $this->view->url(array('action' => 'edit', 'id' => "-ID-")),
                        'findReplace' => array(
                            'search' => '-ID-',
                            'replace' => 'member_id'
                        )
                    ),
                    'delete' => array(
                        'label' => $this->view->getCibleText('menu_submenu_action_delete'),
                        //'url' => "{$this->view->baseUrl()}/profile/index/delete/ID/%ID%",
                        'url' => $this->view->url(array('action' => 'delete', 'id' => '-ID-')),
                        'findReplace' => array(
                            'search' => '-ID-',
                            'replace' => 'member_id'
                        )
                    )
                )
            )
        );

        $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
        $this->view->assign('mylist', $mylist);
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
        $oDataName = 'GenericProfilesObject';
        $oData     = new $oDataName();

        $lang = $this->_getParam('lang');
        if (!$lang)
        {
            $this->_registry->currentEditLanguage = $this->_defaultEditLanguage;
            $langId = $this->_defaultEditLanguage;
        }
        else
        {
            $langId = Cible_FunctionsGeneral::getLanguageID($lang);
            $this->_registry->currentEditLanguage = $langId;
        }

        $returnAction = $this->_getParam('return');

        $baseDir = $this->view->baseUrl() . "/";

        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();
                if (isset($formData[$this->_imageSrc]) && $formData[$this->_imageSrc] <> "")
                    if ($formData[$this->_imageSrc] <> "")
                        $imageSrc = $this->_rootImgPath
                                . "tmp/mcith/mcith_"
                                . $formData[$this->_imageSrc];
            }

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
                        'moduleName' => $this->_moduleTitle . "/",
                        'imageSrc'   => $imageSrc,
                        'imgField'   => $this->_imageSrc,
                        'dataId'     => '',
                        'object'     => $oData,
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
                    $recordID = $oData->insert($formData, $langId);
                    /* IMAGES */
                    if (!empty($this->_imageSrc))
                    {
                        mkdir($this->_imageFolder . $recordID)
                                or die("Could not make directory");
                        mkdir($this->_imageFolder . $recordID . "/tmp")
                                or die("Could not make directory");
                    }

                    if ($form->getValue($this->_imageSrc) <> '')
                    {
                        //Get config data
                        $config = Zend_Registry::get('config')->toArray();
                        //Set sizes for the image
                        $srcOriginal       = $this->_imageFolder . "tmp/" . $form->getValue($this->_imageSrc);
                        $originalMaxHeight = $config[$this->_moduleTitle]['image']['original']['maxHeight'];
                        $originalMaxWidth  = $config[$this->_moduleTitle]['image']['original']['maxWidth'];

                        $originalName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $originalMaxWidth
                                        . 'x'
                                        . $originalMaxHeight
                                        . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc)
                        );


                        $srcMedium = $this->_imageFolder
                                . "tmp/medium_"
                                . $form->getValue($this->_imageSrc);
                        $mediumMaxHeight = $config[$this->_moduleTitle]['image']['medium']['maxHeight'];
                        $mediumMaxWidth = $config[$this->_moduleTitle]['image']['medium']['maxWidth'];
                        $mediumName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $mediumMaxWidth
                                        . 'x'
                                        . $mediumMaxHeight
                                        . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc)
                        );

                        $srcThumb = $this->_imageFolder
                                . "tmp/thumb_"
                                . $form->getValue($this->_imageSrc);
                        $thumbMaxHeight = $config[$this->_moduleTitle]['image']['thumb']['maxHeight'];
                        $thumbMaxWidth = $config[$this->_moduleTitle]['image']['thumb']['maxWidth'];
                        $thumbName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $thumbMaxWidth
                                        . 'x'
                                        . $thumbMaxHeight
                                        . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc)
                        );

                        copy($srcOriginal, $srcMedium);
                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcOriginal,
                                            'maxWidth' => $originalMaxWidth,
                                            'maxHeight' => $originalMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcMedium,
                                            'maxWidth' => $mediumMaxWidth,
                                            'maxHeight' => $mediumMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcThumb,
                                            'maxWidth' => $thumbMaxWidth,
                                            'maxHeight' => $thumbMaxHeight)
                        );

                        rename($srcOriginal, $this->_imageFolder . $recordID . "/" . $originalName);
                        rename($srcMedium, $this->_imageFolder . $recordID . "/" . $mediumName);
                        rename($srcThumb, $this->_imageFolder . $recordID . "/" . $thumbName);
                    }

                    // redirect
                    $this->_redirect($returnUrl);
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
                . "page/" . $page;

        $oDataName = 'GenericProfile';
        $oGenericProfile = new GenericProfilesObject();
        $oData = new $oDataName();


        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $this->view->headScript()->appendFile($this->view->locateFile('jquery.validate.min.js', 'jquery'));
            $this->view->headScript()->appendFile($this->view->locateFile('additional-methods.min.js', 'jquery'));
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_currentAction . "/"
                    . "page/" . $page;

            // Get data details
            $data = $oData->getMemberDetails($id);

            // image src.
            if (!empty($data[$this->_imageSrc]))
            {
                $config = Zend_Registry::get('config')->toArray();
                $thumbMaxHeight = $config[$this->_moduleTitle]['image']['thumb']['maxHeight'];
                $thumbMaxWidth = $config[$this->_moduleTitle]['image']['thumb']['maxWidth'];

                $this->view->assign(
                        'imageUrl',
                        $this->_rootImgPath
                        . $id . "/"
                        . str_replace(
                                $data[$this->_imageSrc],
                                $thumbMaxWidth
                                . 'x'
                                . $thumbMaxHeight
                                . '_'
                                . $data[$this->_imageSrc],
                                $data[$this->_imageSrc])
                );
                $isNewImage = 'false';

                if ($this->_request->isPost())
                {
                    $formData = $this->_request->getPost();
                    if ($formData[$this->_imageSrc] <> $data[$this->_imageSrc])
                    {
                        if ($formData[$this->_imageSrc] == "")
                            $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = $this->_rootImgPath
                                    . $id
                                    . "/tmp/mcith/mcith_"
                                    . $formData[$this->_imageSrc];

                        $isNewImage = 'true';
                    }
                    else
                    {
                        if ($data[$this->_imageSrc] == "")
                            $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                        else
                            $imageSrc = $this->_rootImgPath
                                    . $id . "/"
                                    . str_replace(
                                            $data[$this->_imageSrc],
                                            $thumbMaxWidth
                                            . 'x'
                                            . $thumbMaxHeight . '_'
                                            . $data[$this->_imageSrc],
                                            $data[$this->_imageSrc]);
                    }
                }
                else
                {
                    if (empty($data[$this->_imageSrc]))
                        $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                    else
                        $imageSrc = $this->_rootImgPath
                                . $id . "/"
                                . str_replace(
                                        $data[$this->_imageSrc],
                                        $thumbMaxWidth
                                        . 'x'
                                        . $thumbMaxHeight . '_'
                                        . $data[$this->_imageSrc],
                                        $data[$this->_imageSrc]);
                }
            }
            // generate the form
            $form = new FormGenericProfile(
                            array(
                                'moduleName' => $this->_moduleTitle . "/",
                                'baseDir'    => $baseDir,
                                'cancelUrl'  => $cancelUrl,
                                'imageSrc'   => $imageSrc,
                                'imgField'   => $this->_imageSrc,
                                'dataId'     => $id,
                                'data'       => $data,
                                'object'     => $oGenericProfile,
                                'isNewImage' => 'true'
                            )
            );
            $this->view->form = $form;

            // action
            if (!$this->_request->isPost())
            {

                $form->populate($data);
            }
            else
            {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData))
                {
                    $formData = $this->_mergeFormData($formData);
                    if ($formData['isNewImage'] == 'true' && $form->getValue($this->_imageSrc) <> '')
                    {
                        $config = Zend_Registry::get('config')->toArray();
                        $srcOriginal = $this->_imageFolder
                                . $id
                                . "/tmp/"
                                . $form->getValue($this->_imageSrc);
                        $originalMaxHeight = $config[$this->_moduleTitle]['image']['original']['maxHeight'];
                        $originalMaxWidth = $config[$this->_moduleTitle]['image']['original']['maxWidth'];
                        $originalName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $originalMaxWidth
                                        . 'x'
                                        . $originalMaxHeight . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc));


                        $srcMedium = $this->_imageFolder
                                . $id . "/tmp/medium_"
                                . $form->getValue($this->_imageSrc);

                        $mediumMaxHeight = $config[$this->_moduleTitle]['image']['medium']['maxHeight'];
                        $mediumMaxWidth = $config[$this->_moduleTitle]['image']['medium']['maxWidth'];
                        $mediumName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $mediumMaxWidth
                                        . 'x'
                                        . $mediumMaxHeight . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc));

                        $srcThumb = $this->_imageFolder
                                . $id
                                . "/tmp/thumb_"
                                . $form->getValue($this->_imageSrc);
                        $thumbMaxHeight = $config[$this->_moduleTitle]['image']['thumb']['maxHeight'];
                        $thumbMaxWidth = $config[$this->_moduleTitle]['image']['thumb']['maxWidth'];
                        $thumbName = str_replace(
                                        $form->getValue($this->_imageSrc),
                                        $thumbMaxWidth
                                        . 'x'
                                        . $thumbMaxHeight . '_'
                                        . $form->getValue($this->_imageSrc),
                                        $form->getValue($this->_imageSrc));

                        copy($srcOriginal, $srcMedium);
                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcOriginal,
                                            'maxWidth' => $originalMaxWidth,
                                            'maxHeight' => $originalMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcMedium,
                                            'maxWidth' => $mediumMaxWidth,
                                            'maxHeight' => $mediumMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcThumb,
                                            'maxWidth' => $thumbMaxWidth,
                                            'maxHeight' => $thumbMaxHeight)
                        );

                        rename($srcOriginal,
                                $this->_imageFolder
                                . $id . "/" . $originalName);
                        rename($srcMedium,
                                $this->_imageFolder
                                . $id . "/" . $mediumName);
                        rename($srcThumb,
                                $this->_imageFolder
                                . $id . "/" . $thumbName);
                    }
                    $oData->save($id, $formData, $this->getCurrentEditLanguage());
                    // redirect
                    $this->_redirect($returnUrl);
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

    public function userExistsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->_isXmlHttpRequest)
        {
            $isValid = "";
            $email   = $this->_getParam('email');

            $oGenericProfile = new GenericProfile();
            $profile = $oGenericProfile->findMembers(array('email' => $email));

            if (count($profile) > 0)
            {
                $isValid = false;
                echo $isValid;
            }

        }

    }
}