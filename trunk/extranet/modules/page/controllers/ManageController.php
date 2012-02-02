<?php

class Page_ManageController extends Cible_Extranet_Controller_Module_Action
{
    protected $_moduleTitle   = 'page';
    protected $_imageFolder;
    protected $_folderMenu = 'menu';
    protected $_rootImgPath;
    protected $_imgMenuFolder;
    protected $_rootMenuImgPath;

    public function init()
    {
        $this->view->headScript()->appendFile($this->view->locateFile('tiny_mce.js', 'tiny_mce'));
        $this->view->headScript()->appendFile($this->view->locateFile('mcimagemanager.js', 'tiny_mce/plugins/imagemanager/js/'));

        parent::init();
        $dataPath = "../../"
                    . $this->_config->document_root
                    . "/data/";

        $this->_imageFolder = $dataPath
                              . "images/"
                              . $this->_moduleTitle . "/";

        $this->_rootImgPath = Zend_Registry::get("www_root")
                                . "/data/images/"
                                . $this->_moduleTitle . "/";

        $this->_imgMenuFolder = $dataPath
                              . "images/"
                              . $this->_folderMenu . "/";
        $this->_rootMenuImgPath = Zend_Registry::get("www_root")
                                . "/data/images/"
                                . $this->_folderMenu . "/";
    }

    /**
     * Initiates data of importation files.
     * Scan the folder containing import files and format some data.
     * Save data into database to be used in the import actions.
     *
     * @return array
     */
    protected function _findImagesFiles()
    {
        $filesList = array();

        if (is_dir($this->_imageFolder))
        {
            $dirHandler = opendir($this->_imageFolder);
            if ($dirHandler)
            {
                // for each file in the folder
                while (($file = readdir($dirHandler)) !== false)
                {
                    // get name and data (date of modif...)
                    $realPath = realpath($this->_imageFolder . $file);
                    $info     = pathinfo($this->_imageFolder . $file);
                    $fileName = $info['filename'];
                    // store it in an array
                    if (filetype($realPath) == 'file')
                    {
                        $filesList[$fileName] = $info['basename'];
                    }
                }

                closedir($dirHandler);
            }
        }

        // return this array
        return $filesList;
    }

    function indexAction(){
        // retrieve the ID of the requested page
        $pageID = $this->view->pageID = $this->_getParam( 'ID' );

        $pageDetails = new PagesIndex();
        $pageDetailsSelect = $pageDetails->select();
        $pageDetailsSelect->where('PI_PageID = ?', $pageID)
                          ->where('PI_LanguageID = ?', $this->_defaultEditLanguage);
        $pageDetailsData = $pageDetails->fetchRow($pageDetailsSelect)->toArray();
        $this->view->assign("pageTitle",$pageDetailsData["PI_PageTitle"]);

        $authData = $this->view->user;
        $authID     = $authData['EU_ID'];
        if (Cible_FunctionsAdministrators::checkAdministratorPageAccess($authID,$pageID,"structure")){
            // Retrieve the page view layout
            $page = new Pages();
            $page_select = $page->select()->setIntegrityCheck(false);
            $page_select->from('Pages')
                        ->join('Views', 'Pages.P_ViewID = Views.V_ID')
                        ->where('P_ID = ?', $pageID);

            $page_info = Cible_FunctionsPages::getPageViewDetails($pageID);

            $template_file = 'manage/' . $page_info['V_Path'];
            $_zone_count = $page_info['V_ZoneCount'];


            // make a request to get all the blocks to be displayed
            $blocks = new Blocks();
            $select = $blocks->select()->setIntegrityCheck(false);
            $select->from('Blocks')
                    ->join('Modules', 'Modules.M_ID = Blocks.B_ModuleID')
                    ->join('Pages', 'Blocks.B_PageID = P_ID')
                    ->join('BlocksIndex', 'Blocks.B_ID = BlocksIndex.BI_BlockID')
                    ->where('Blocks.B_PageID = ?', $pageID)
                    ->where('BlocksIndex.BI_LanguageID = ?', Zend_Registry::get('languageID'))
                    ->order('Blocks.B_Position ASC');

            //Send the results to the view
            $rows = $blocks->fetchAll($select);

            $_blocks = array();

            foreach($rows as $row){
                // create the placeholder object if not already defined
                if( !isset( $_blocks[$row['B_ZoneID']] ) )
                   $_blocks[$row['B_ZoneID']] = array();

                $_blocks[$row['B_ZoneID']][] = $row->toArray();
            }

            $this->view->assign('template_file', $template_file);
            $this->view->assign('zone_count', $_zone_count);
            $this->view->assign('blocks', $_blocks);

            // Load the modules in the view
            $Modules = new Modules();
            $modules = $Modules->fetchAll();
            $this->view->assign('modules', $modules->toArray());
        }
        else{
            $this->view->assign('template_file', "");
            $this->view->assign('error_message_permission', $this->view->getCibleText('error_message_permission'));
        }

    }

    function pageStructureAction(){

    }

    function structureAction(){
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/tiny_mce/plugins/imagemanager/js/mcimagemanager.js');

        $this->view->assign('menu', $this->buildMenu() );
        $this->view->assign('params', $this->_request->getParams());
    }

    function pageBreadcrumbAction(){
        $pageID = $this->_getParam( 'ID' );

        $this->disableView();

        if( empty($pageID) )
            return;

        echo Cible_FunctionsPages::buildBreadcrumb($pageID, $this->_defaultEditLanguage);
    }

    function moduleNameAction(){
        $module = $this->_getParam( 'name' );

        $this->disableView();

        if( empty($module) )
            return;

        echo Cible_FunctionsModules::getLocalizedModuleTitle($module);
    }

    function menuAction(){
        $this->view->headScript()->appendFile("{$this->view->baseUrl()}/js/cible.form.element.pagepicker.js");

        $allMenuTitles = MenuObject::getAllMenuTitles();
        $menus = array();

        foreach( $allMenuTitles as $menu){
            $_menu = new MenuObject($menu['Title']);
            array_push($menus, array(
                'ID' => $menu['ID'],
                'Title' => $menu['Title'],
                'Type' => $menu['Type'],
                'BgColor' => $menu['BgColor'],
                'Menu' => $_menu->populate(0, true)
            ));
        }

        $this->view->headScript()->appendFile("{$this->view->baseUrl()}/js/manage.categories.js");
        $this->view->assign( 'menus', $menus );
    }

    function addMenuAction(){

        $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
        $this->view->assign('success', false);

        $_parentId = (int)$this->_request->getParam('parentID');
        $_menuId   = (int)$this->_request->getParam('menuID');
        $_position = (int)$this->_request->getParam('position');

        $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";

        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($formData['menuImage'] <> "")
                $imageSrc = $this->_rootMenuImgPath . "tmp/mcith/mcith_" . $formData['menuImage'];
            else
                $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
        }
        else
            $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";

        // create a new formpage object
        $form = new FormMenu(array(
            'baseDir'   => $this->view->baseUrl(),
            'pageID'	=> 0,
            'addAction' => true,
            'imageSrc' => $imageSrc,
            'menuId' => '',
            'isNewImage' => true,
        ));

        $this->view->assign('form', $form);

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            $isPlaceholder = 0;

            if( $formData['menuItemType'] == 'page' ){

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: block');

            } else if('external') {

                $form->disableElementValidation('pagePicker');
                $form->disableElementValidation('ControllerName');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: block');

            } else {

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->disableElementValidation('pagePicker');
                $form->disableElementValidation('ControllerName');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');

                $isPlaceholder = 1;

            }

            if ($form->isValid($formData))
            {

                // saving the entry
                $menuItem = new MenuObject($_menuId);
                $menuId   = $menuItem->addItem($_parentId, array(
                    'languageID' => $this->_config->defaultEditLanguage,
                    'Title' => $formData['MenuTitle'],
                    'Link' => $formData['MenuLink'],
                    'PageID' => $formData['pagePicker'],
                    'Position' => $_position,
                    'Style' => $formData['MenuTitleStyle'],
                    'menuItemSecured' => $formData['menuItemSecured'],
                    'loadImage' => $formData['loadImage'],
                    'menuImage' => $formData['menuImage'],
                    'menuImgAndTitle' => $formData['menuImgAndTitle'],
                    'MID_Show' => $formData['MID_Show'],
                    'Placeholder' => $isPlaceholder
                ));

                /* IMAGES */
                mkdir($this->_imgMenuFolder . $menuId) or die("Could not make directory");
                mkdir($this->_imgMenuFolder . $menuId . "/tmp") or die("Could not make directory");

                if ($form->getValue('menuImage') <> '')
                {
                    $config = Zend_Registry::get('config')->toArray();
                    $srcOriginal = $this->_imgMenuFolder . "tmp/" . $form->getValue('menuImage');
                    $originalMaxHeight = $config[$this->_folderMenu]['image']['original']['maxHeight'];
                    $originalMaxWidth = $config[$this->_folderMenu]['image']['original']['maxWidth'];
                    $originalName = str_replace($form->getValue('menuImage'), $originalMaxWidth . 'x' . $originalMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));


                    $srcMedium = $this->_imgMenuFolder . "tmp/medium_{$form->getValue('menuImage')}";
                    $mediumMaxHeight = $config[$this->_folderMenu]['image']['medium']['maxHeight'];
                    $mediumMaxWidth = $config[$this->_folderMenu]['image']['medium']['maxWidth'];
                    $mediumName = str_replace($form->getValue('menuImage'), $mediumMaxWidth . 'x' . $mediumMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));

                    $srcThumb = $this->_imgMenuFolder . "tmp/thumb_{$form->getValue('menuImage')}";
                    $thumbMaxHeight = $config[$this->_folderMenu]['image']['thumb']['maxHeight'];
                    $thumbMaxWidth = $config[$this->_folderMenu]['image']['thumb']['maxWidth'];
                    $thumbName = str_replace($form->getValue('menuImage'), $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));

                    copy($srcOriginal, $srcMedium);
                    copy($srcOriginal, $srcThumb);

                    Cible_FunctionsImageResampler::resampled(array('src' => $srcOriginal, 'maxWidth' => $originalMaxWidth, 'maxHeight' => $originalMaxHeight));
                    Cible_FunctionsImageResampler::resampled(array('src' => $srcMedium, 'maxWidth' => $mediumMaxWidth, 'maxHeight' => $mediumMaxHeight));
                    Cible_FunctionsImageResampler::resampled(array('src' => $srcThumb, 'maxWidth' => $thumbMaxWidth, 'maxHeight' => $thumbMaxHeight));

                    rename($srcOriginal, $this->_imgMenuFolder . "$menuId/$originalName");
                    rename($srcMedium, $this->_imgMenuFolder . "$menuId/$mediumName");
                    rename($srcThumb, $this->_imgMenuFolder . "$menuId/$thumbName");
                }

                if( !$this->_isXmlHttpRequest )
                    $this->_redirect('/');
                else {
                    $this->view->assign('success', true);
                    $buttonAction = $formData['buttonAction'];
                    $this->view->assign('buttonAction', $buttonAction);
                    $this->view->assign('menuID', $menuId);
                }
            } else {
                $form->populate($formData);
            }
        }
        else
        {
            $form->getElement('menuImage_preview')->setAttrib('src', $imageSrc);
        }
    }

    function editMenuAction(){
        $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
        $this->view->assign('success', false);

        $_parentId = (int)$this->_request->getParam('parentID');
        $_menuId = (int)$this->_request->getParam('menuID');
        // image src.
        $config = Zend_Registry::get('config')->toArray();
        $thumbMaxHeight = $config[$this->_folderMenu]['image']['thumb']['maxHeight'];
        $thumbMaxWidth = $config[$this->_folderMenu]['image']['thumb']['maxWidth'];

        $menuItem = new MenuObject($_menuId);
        $menuData = $menuItem->loadItem($_parentId);
        if(isset($menuData['menuImage'])){
            $this->view->assign('imageUrl', $this->_rootMenuImgPath . "$_parentId/" . str_replace($menuData['menuImage'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $menuData['menuImage'], $menuData['menuImage']));
        }

        if (!is_dir($this->_imgMenuFolder . "$_parentId/"))
        {
            mkdir($this->_imgMenuFolder . $_parentId) or die("Could not make directory");
            mkdir($this->_imgMenuFolder . $_parentId . "/tmp") or die("Could not make directory");
        }

        $isNewImage = 'false';
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if ($formData['menuImage'] <> $menuData['menuImage'])
            {
                if ($formData['menuImage'] == "")
                    $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                else
                    $imageSrc = $this->_rootMenuImgPath . "$_parentId/tmp/mcith/mcith_" . $formData['menuImage'];

                $isNewImage = 'true';
            }
            else
            {
                if ($menuData['menuImage'] == "")
                    $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                else
                    $imageSrc = $this->_rootMenuImgPath . "$_parentId/" . str_replace($menuData['menuImage'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $menuData['menuImage'], $menuData['menuImage']);
            }
        }
        else
        {
            if (empty($menuData['menuImage']))
                $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
            else
                $imageSrc = $this->_rootMenuImgPath . "$_parentId/" . str_replace($menuData['menuImage'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $menuData['menuImage'], $menuData['menuImage']);
        }

        // create a new formpage object
        $form = new FormMenu(array(
            'baseDir' => $this->view->baseUrl(),
            'pageID'  => 0,
            'menuId'  => $_parentId,
            'imageSrc' => $imageSrc,
            'isNewImage' => $isNewImage
        ));

        $this->view->assign('form', $form);

        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();

            $isPlaceholder = 0;

            if( $formData['menuItemType'] == 'page' ){

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: block');

            } else if( $formData['menuItemType'] == 'external' ) {

                $form->disableElementValidation('pagePicker');
                $form->disableElementValidation('ControllerName');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: block');

            } else {

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');

                $form->disableElementValidation('pagePicker');
                $form->disableElementValidation('ControllerName');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');

                $formData['MenuLink'] = '';
                $isPlaceholder = 1;

            }

            if ($form->isValid($formData))
            {

                // saving the entry
                $menuItem = new MenuObject($_menuId);
                $menuItem->updateItem($_parentId, array(
                    'menuItemSecured' => $formData['menuItemSecured'],
                    'loadImage' => $formData['loadImage'],
                    'menuImage' => $formData['menuImage'],
                    'menuImgAndTitle' => $formData['menuImgAndTitle'],
                    'languageID' => $this->_currentEditLanguage,
                    'Title' => $formData['MenuTitle'],
                    'MID_Show' => $formData['MID_Show'],
                    'Link' => $formData['MenuLink'],
                    'PageID' => $formData['pagePicker'],
                    'Style'  => $formData['MenuTitleStyle'],
                    'Placeholder' => $isPlaceholder
                ));
                /* IMAGES */
                if ($form->getValue('menuImage') <> '' && $isNewImage == 'true')
                {
                    $config = Zend_Registry::get('config')->toArray();
                    $srcOriginal = $this->_imgMenuFolder . $_parentId . "/tmp/" . $form->getValue('menuImage');
                    $originalMaxHeight = $config[$this->_folderMenu]['image']['original']['maxHeight'];
                    $originalMaxWidth = $config[$this->_folderMenu]['image']['original']['maxWidth'];
                    $originalName = str_replace($form->getValue('menuImage'), $originalMaxWidth . 'x' . $originalMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));


                    $srcMedium = $this->_imgMenuFolder . $_parentId . "/tmp/medium_{$form->getValue('menuImage')}";
                    $mediumMaxHeight = $config[$this->_folderMenu]['image']['medium']['maxHeight'];
                    $mediumMaxWidth = $config[$this->_folderMenu]['image']['medium']['maxWidth'];
                    $mediumName = str_replace($form->getValue('menuImage'), $mediumMaxWidth . 'x' . $mediumMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));

                    $srcThumb = $this->_imgMenuFolder . $_parentId . "/tmp/thumb_{$form->getValue('menuImage')}";
                    $thumbMaxHeight = $config[$this->_folderMenu]['image']['thumb']['maxHeight'];
                    $thumbMaxWidth = $config[$this->_folderMenu]['image']['thumb']['maxWidth'];
                    $thumbName = str_replace($form->getValue('menuImage'), $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $form->getValue('menuImage'), $form->getValue('menuImage'));

                    copy($srcOriginal, $srcMedium);
                    copy($srcOriginal, $srcThumb);

                    Cible_FunctionsImageResampler::resampled(array('src' => $srcOriginal, 'maxWidth' => $originalMaxWidth, 'maxHeight' => $originalMaxHeight));
                    Cible_FunctionsImageResampler::resampled(array('src' => $srcMedium, 'maxWidth' => $mediumMaxWidth, 'maxHeight' => $mediumMaxHeight));
                    Cible_FunctionsImageResampler::resampled(array('src' => $srcThumb, 'maxWidth' => $thumbMaxWidth, 'maxHeight' => $thumbMaxHeight));

                    rename($srcOriginal, $this->_imgMenuFolder . "$_parentId/$originalName");
                    rename($srcMedium, $this->_imgMenuFolder . "$_parentId/$mediumName");
                    rename($srcThumb, $this->_imgMenuFolder . "$_parentId/$thumbName");
                }

                if( !$this->_isXmlHttpRequest )
                    $this->_redirect('/');
                else
                {
                    $buttonAction = $formData['buttonAction'];
                    $this->view->assign('buttonAction', $buttonAction);
                    $this->view->assign('success', true);
                    $this->view->assign('menuID', $_parentId);
                }
            } else {
                $form->populate($formData);
            }
        } else {

            $form->populate($menuData);

            $menuData['menuItemType'] = isset( $menuData['menuItemType'] ) ? $menuData['menuItemType'] : 'page';

            if( $menuData['menuItemType'] == 'page' ){

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: block');

            } else if( $menuData['menuItemType'] == 'external' ) {

                $form->disableElementValidation('pagePicker');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: block');

            } else {

                $form->disableElementValidation('MenuLink');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');

                $form->disableElementValidation('pagePicker');
                $form->getDisplayGroup('pageSelectionGroup')->setAttrib('style', 'display: none');
                $form->getDisplayGroup('externalLinkSelectionGroup')->setAttrib('style', 'display: none');
            }
        }
    }

    function deleteMenuAction(){
        $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
        $this->view->assign('success', false);

        $_itemId = (int)$this->_request->getParam('itemID');
        $_menuId = (int)$this->_request->getParam('menuID');

        $this->disableView();

        if ($this->_request->isPost()) {
            $delete = isset($_POST['delete']);

            if( $delete ){
                $menuObject = new MenuObject($_menuId);
                $menuObject->deleteItem($_itemId);
                $dir = $this->_imgMenuFolder . $_itemId;
                Cible_FunctionsGeneral::delFolder($dir);
            }
        }
    }

    function updateMenuPositionAction(){
         $this->disableView();

        if ($this->_request->isPost()) {

            $order = isset($_POST['order']);

            if( $order ){

                $json_object = Zend_Json_Decoder::decode( $_POST['order'] );

                foreach($json_object as $key => $object){

                    $menuName = str_replace('ul_','', $key);
                    $menuObject = new MenuObject( $menuName );

                    $menuObject->updatePositions( $object );
                }
            }
        }
    }

    function autogenerateFromIdAction(){
        $this->disableView();

        $_itemId = $this->_getParam('itemID');
        $_menuId = $this->_getParam('menuID');

        if ($this->_request->isPost()) {


            if( $_itemId != null && $_itemId >= 0 ) {

                $menuObject = new MenuObject( $_menuId );
                $recursive = false;

                if( isset( $_POST['autogenerate_recursive'] ) && $_POST['autogenerate_recursive'] == 'true' )
                    $recursive = true;

                $result = $menuObject->autogenerateFromId( $_itemId, $recursive );
            }
        }
    }

    function addAction(){

        $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
        $this->view->assign('success', false);


        $this->view->title = "Ajouter une page";
        // retrieve the ID of the parent page
        $PageID = $this->_getParam( 'ID' );

        if(Cible_ACL::hasAccess($PageID)){
            // image src.
            if ($this->_request->isPost()){
                $formData = $this->_request->getPost();

                /*if ($formData['PI_TitleImageSrc'] == "")
                    $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
                else
                    $imageSrc = $formData['PI_TitleImageSrc'];*/
            }
            /*else{
                $imageSrc = $this->view->baseUrl()."/icons/image_non_ disponible.jpg";
            }*/

            $imageHeaderArray = $this->_findImagesFiles();

            // create a new formpage object
            $form = new FormPage(array(
                    'baseDir'   => $this->view->baseUrl(),
                    //'imageSrc'  => $imageSrc,
                    'pageID'    => 0,
                    'addAction' => true,
                    'imageHeaderArray'  => $imageHeaderArray
                ));

            // get page informations
            $Page = $this->view->Page = Cible_FunctionsPages::getPageDetails($PageID);

            //get position of all pages on the same level of the current page
            //$Position = Cible_FunctionsPages::getAllPositions($PageID);

            $layouts = Cible_FunctionsPages::getAvailableLayouts();
            $templates = Cible_FunctionsPages::getAvailableTemplates();

            // fill select position
            //$form = Cible_FunctionsPages::fillSelectPosition($form, $Position,"add");

            $form = Cible_FunctionsPages::fillSelectLayouts($form, $layouts);
            $form = Cible_FunctionsPages::fillSelectTemplates($form, $templates);

            //$form->submit->setLabel('Ajouter');
            $this->view->form = $form;

            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();

                if ($form->isValid($formData)) {
                    Zend_Registry::set('currentEditLanguage', $this->_config->defaultEditLanguage);
                    $this->_currentEditLanguage = $this->_config->defaultEditLanguage;

                    $_db = $this->_db;
                    $_lastPosition = $_db->fetchOne( $_db->quoteInto('SELECT P_Position FROM Pages WHERE P_ParentID = ? ORDER BY P_Position DESC', $PageID ) );

                    $page = new Pages();
                    $row = $page->createRow();
                    $row->P_Position = $_lastPosition + 1;
                    $row->P_ParentID = $PageID;
                    $row->P_ViewID = $formData['P_ViewID'];
                    $row->P_LayoutID = $formData['P_LayoutID'];
                    $row->P_ShowTitle = $formData['P_ShowTitle'];

                    $row->save();

                    // get the new page ID
                    $NewPageID = $row->P_ID;

                    $pageIndex = new PagesIndex();
                    $rowPageIndex = $pageIndex->createRow();

                    $rowPageIndex->PI_PageID = $NewPageID;
                    $rowPageIndex->PI_LanguageID = $this->_config->defaultEditLanguage;

                    //Insérer seulement dans la version de la langue en cours, les données saisies par l'usager

                    $rowPageIndex->PI_PageIndex         = $form->getValue('PI_PageIndex');
                    $rowPageIndex->PI_PageTitle         = $form->getValue('PI_PageTitle');
                    $rowPageIndex->PI_MetaTitle        = $form->getValue('PI_MetaTitle');
                    $rowPageIndex->PI_MetaDescription   = $form->getValue('PI_MetaDescription');
                    $rowPageIndex->PI_MetaKeywords      = $form->getValue('PI_MetaKeywords');
                    $rowPageIndex->PI_MetaOther         = $form->getValue('PI_MetaOther');
                    $rowPageIndex->PI_Status            = $form->getValue('PI_Status');
                    $rowPageIndex->PI_TitleImageSrc     = $form->getValue('PI_TitleImageSrc');
                    $rowPageIndex->PI_AltPremiereImage     = $form->getValue('PI_AltPremiereImage');
                   /* $rowPageIndex->PI_TitleImageAlt    = $form->getValue('PI_TitleImageAlt');*/

                    $rowPageIndex->save();


                    if($formData['PI_Status'] == 1){
                        $indexData['pageID']    = $NewPageID;
                        $indexData['moduleID']  = 0;
                        $indexData['contentID'] = $NewPageID;
                        $indexData['languageID'] = $this->_config->defaultEditLanguage;
                        $indexData['title']     = $formData['PI_PageTitle'];
                        $indexData['text']      = '';
                        $indexData['link']      = '';
                        $indexData['contents']  = $formData['PI_PageTitle'];
                        $indexData['action']    = 'add';

                        Cible_FunctionsIndexation::indexation($indexData);
                    }

                    // if not and ajax request, redirect else simply return as json success code and page details
                    if( !$this->_isXmlHttpRequest )
                        $this->_redirect('/');
                    else {
                        $buttonAction = $formData['buttonAction'];
                        $this->view->assign('buttonAction', $buttonAction);
                        $this->view->assign('success', true);
                        $this->view->assign('parentID', $PageID);
                        $this->view->assign('pageID', $NewPageID);
                        $this->view->assign('pageTitle', $formData['PI_PageTitle']);
                        $this->view->assign('currentEditLanguage', $this->_config->defaultEditLanguage);
                    }

                } else {
                    $form->getElement('PI_MetaOther')->clearFilters();
                    $form->populate($formData);
                }
            }
        }
    }

    function editAction(){

        $this->view->assign('isXmlHttpRequest', $this->_isXmlHttpRequest);
        $this->view->assign('success', false);

        $this->view->title = "Modification de la page";
        // retrieve the ID of the requested page
        $PageID = (int)$this->_getParam( 'ID' );
        if(Cible_ACL::hasAccess($PageID)){

            // get page informations
            $Page = Cible_FunctionsPages::getPageDetails($PageID, $this->_currentEditLanguage);

            $page_info = $Page->toArray();

            $this->view->home = $page_info['P_Home'];
            $this->view->pageTitle = isset($page_info['PI_PageTitle']) ? $page_info['PI_PageTitle'] : '';

            $imageHeaderArray = $this->_findImagesFiles();

            $form = new FormPage(array(
                'baseDir'   => $this->view->baseUrl(),
                'pageID'    => $PageID,
                'imageHeaderArray'  => $imageHeaderArray
            ));

            $layouts = Cible_FunctionsPages::getAvailableLayouts();
            $templates = Cible_FunctionsPages::getAvailableTemplates();

            $form = Cible_FunctionsPages::fillSelectLayouts($form, $layouts);
            $form = Cible_FunctionsPages::fillSelectTemplates($form, $templates);

            $this->view->form = $form;

            if ($this->_request->isPost()) {
                $formData = $this->_request->getPost();

                $PageIndex = $formData['PI_PageIndex'];
                // replace all double underscore by simple underscore
                while(substr_count($PageIndex,"__")>0){
                    $PageIndex = str_replace("__","_",$PageIndex);
                }

                if( isset( $page_info['PI_PageIndex'] ) && ($page_info['PI_PageIndex'] == $formData['PI_PageIndex'] ) )
                    $form->getElement('PI_PageIndex')->clearValidators();

                if ($form->isValid($formData)) {

                    $db = $this->_db;

                    // update the P_LayoutID for the current Page
                    $db->update('Pages', array('P_LayoutID'=> $formData['P_LayoutID'], 'P_ShowTitle'=> $formData['P_ShowTitle']), 'P_ID = '. $PageID);

                    // check to update P_ViewID
                    if( $formData['P_ViewID'] != $page_info['P_ViewID'] ){

                        //get current number of zones
                        $previous_zone_count = $page_info['V_ZoneCount'];

                        // update the P_ViewID for the current Page
                        $db->update('Pages', array('P_ViewID'=> $formData['P_ViewID']), 'P_ID = '. $PageID);

                        // Fetch the new selected view details
                        $new_view_details = Cible_FunctionsPages::getPageViewDetails($PageID);
                        $new_zone_count =  $new_view_details['V_ZoneCount'];

                        // if our new zone count is smaller then our previous zone count, we need to move our deleted zone blocks to zone 1
                        if($new_zone_count < $previous_zone_count){

                            //reset where just in case
                            $where = array();
                            $where[] = $db->quoteInto('B_PageID = ?', $PageID);
                            $where[] = $db->quoteInto('B_ZoneID > ?', $new_zone_count);
                            $db->update('Blocks', array('B_ZoneID'=> '-1'), $where);
                        }
                   }

                    //$Page['PI_LanguageID'] = $this->_currentEditLanguage;
                    if( isset( $Page['PI_PageTitle'] ) ){

                        $Page['PI_PageTitle'] = $formData['PI_PageTitle'];
                        $Page['PI_PageIndex'] = $PageIndex;
                        $Page['PI_Status']    = $formData['PI_Status'];

                        $Page['PI_MetaTitle'] = $formData['PI_MetaTitle'];
                        $Page['PI_MetaDescription'] = $formData['PI_MetaDescription'];
                        $Page['PI_MetaKeywords'] = $formData['PI_MetaKeywords'];
                        $Page['PI_MetaOther'] = $formData['PI_MetaOther'];
                        if (!empty($formData['PI_TitleImageSrc']))
                            $Page['PI_TitleImageSrc'] = $formData['PI_TitleImageSrc'];

                        if (!empty($formData['PI_AltPremiereImage']))
                            $Page['PI_AltPremiereImage'] = $formData['PI_AltPremiereImage'];
                        
                        
                        $Page->save();

                    } else {

                        $data = array(
                            'PI_PageID' => $PageID,
                            'PI_LanguageID' => $this->_currentEditLanguage,
                            'PI_PageTitle' => $formData['PI_PageTitle'],
                            'PI_PageIndex' => $PageIndex,
                            'PI_Status' => $formData['PI_Status'],
                            'PI_MetaTitle' => $formData['PI_MetaTitle'],
                            'PI_MetaDescription' => $formData['PI_MetaDescription'],
                            'PI_MetaKeywords' => $formData['PI_MetaKeywords'],
                            'PI_MetaOther' => $formData['PI_MetaOther'],
                            'PI_TitleImageSrc' => $formData['PI_TitleImageSrc'],                           
                            'PI_AltPremiereImage' => $formData['PI_AltPremiereImage']
                            
                            
                        );

                        $this->_db->insert('PagesIndex', $data);

                    }

                    $indexData['pageID']    = $PageID;
                    $indexData['moduleID']  = 0;
                    $indexData['contentID'] = $PageID;
                    $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                    $indexData['title']     = $formData['PI_PageTitle'];
                    $indexData['text']      = '';
                    $indexData['link']      = '';
                    $indexData['contents']  = $formData['PI_PageTitle'];

                    if($formData['PI_Status'] == 1)
                        $indexData['action']    = 'update';
                    else
                        $indexData['action']    = 'delete';

                        Cible_FunctionsIndexation::indexation($indexData);

                   // if not and ajax request, redirect else simply return as json success code and page details
                    if( !$this->_isXmlHttpRequest )
                        $this->_redirect('/');
                    else {
                        $buttonAction = $formData['buttonAction'];
                        $this->view->assign('buttonAction', $buttonAction);
                        $this->view->assign('success', true);
                        $this->view->assign('pageID', $PageID);
                        $this->view->assign('pageTitle', $formData['PI_PageTitle']);
                        $this->view->assign('currentEditLanguage', $this->_currentEditLanguage);
                    }
                }
                else {
                    $form->getElement('PI_MetaOther')->clearFilters();
                    $form->populate($formData);
                }
            }
            else {
                if ($PageID > 0) {
                    $form->getElement('PI_MetaOther')->clearFilters();
                    $form->populate($Page->toArray());
                }
            }
        }
    }

    function deleteAction(){
        $this->view->title = "Supprimer une page";
        // retrieve the ID of the parent page
        $PageID = $this->_getParam( 'ID' );
        if(Cible_ACL::hasAccess($PageID)){
            // generates tree by Language and send the result to the view
            Zend_Registry::set('baseUrl',$this->view->baseUrl());
            $this->view->TreeView = $this->view->getTreeView();

            if ($this->_request->isPost()) {
                //$PageID = (int)$this->_request->getPost('PageID');

                // if is set delete, then delete
                $delete = isset($_POST['delete']);

                if ($delete && $PageID > 0) {
                    Cible_FunctionsPages::deleteAllChildPage($PageID);
                    Cible_FunctionsPages::deleteAllBlock($PageID);

                    $pageSelect = new PagesIndex();
                    $select = $pageSelect->select()
                    ->where('PI_PageID = ?',$PageID);
                    $pageData = $pageSelect->fetchAll($select)->toArray();

                    foreach($pageData as $page){
                        $indexData['moduleID']  = 0;
                        $indexData['contentID'] = $PageID;
                        $indexData['languageID'] = $page['PI_LanguageID'];
                        $indexData['action'] = 'delete';
                        Cible_FunctionsIndexation::indexation($indexData);
                    }

                    $Page = new Pages();
                    $Where = 'P_ID = ' . $PageID;
                    $Page->delete($Where);

                    $PageIndex = new PagesIndex();
                    $Where = 'PI_PageID = ' . $PageID;
                    $PageIndex->delete($Where);

                    if( !$this->_request->isXmlHttpRequest() )
                        $this->_redirect('/');
                }
                else{
                    if( !$this->_isXmlHttpRequest )
                        $this->_redirect('/page/edit/ID/'.$PageID);
                }
            }
            else {
                if ($PageID > 0) {
                    $Page = new Pages();
                    $this->view->page = $Page->fetchRow('P_ID='.$PageID);

                    $PageIndex = new PagesIndex();
                    $Select = $PageIndex->select()
                                        ->where("PI_PageID = ?", $PageID)
                                        ->where("PI_LanguageID = ?", $this->_defaultEditLanguage);
                    $this->view->pageindex = $PageIndex->fetchRow($Select);
                }
            }
        }
    }

    function updatePositionAction(){
        $this->disableView();
        $_updateID = $this->_getParam('updateID');
        $_from_parentID = $this->_getParam('from_parentID');
        $_to_parentID = $this->_getParam('to_parentID');

        $_from_childs = Zend_Json::decode( $this->_getParam('from_childs') );
        $_to_childs = Zend_Json::decode( $this->_getParam('to_childs') );

        $_db = $this->_db;

        //We are simply updating the positions for parentID _to_parentID
        if( $_from_parentID == $_to_parentID ){
             foreach( $_to_childs as $key => $val ){
                $_db->update( 'Pages', array('P_Position' => $key + 1), 'P_ID = ' . $val );
             }
        }
        //We are moving _updateID to _to_parentID, then update order for all _from_childs and _to_childs
        else{

            $_db->update( 'Pages', array('P_ParentID' => $_to_parentID), 'P_ID = ' . $_updateID );

            foreach( $_from_childs as $key => $val ){
                $_db->update( 'Pages', array('P_Position' => $key + 1), 'P_ID = ' . $val );
            }

            foreach( $_to_childs as $key => $val ){
                $_db->update( 'Pages', array('P_Position' => $key + 1), 'P_ID = ' . $val );
            }
        }

    }

    function setAsHomePageAction(){
        $this->disableView();
        $_pageID = $this->_getParam('ID');

        $_db = $this->_db;

        $_db->update('Pages', array('P_Home' => 0),'P_Home = 1');
        $_db->update('Pages', array('P_Home' => 1),'P_ID = '.$_pageID);
    }

    function buildMenu(){
        $Pages = $this->_db;
        $Select = $Pages->select()
                        ->from('PagesIndex')
                        ->join('Pages', 'Pages.P_ID = PagesIndex.PI_PageID')
                        ->where('PagesIndex.PI_LanguageID = ?', $this->_defaultEditLanguage)
                        ->where('Pages.P_ParentID = ?', '0')
                        ->order('Pages.P_Position');

        $Rows = $Pages->fetchAll($Select);
        $menu = '';
        foreach($Rows as $Row){
            $menu .= '<li id="page_'.$Row['PI_PageID'].'" class="sortable_items collapsed possible_homepage '.( $Row['P_Home'] ? ' home ': '' ).'" pageid="'.$Row['PI_PageID'].'" parentid="'.$Row['P_ParentID'].'"><a class="state" onclick="toggleMenu(this)">&nbsp;</a><img class="drag_handle" src="'.$this->view->baseUrl().'/icons/file.png" align="left" /><a class="element" onclick="toggleActionMenu(this)">'.$Row['PI_PageTitle'].'</a><div class="actions-dialog"><div class="hd"><div class="c"></div></div><div class="bd"><div class="c"><div class="s"><div class="actions"><a class="action_add" onclick="addPage(this);">'.$this->view->getCibleText('menu_treeview_add').'</a> <a class="action_edit" onclick="editPage(this);">'.$this->view->getCibleText('menu_treeview_edit').'</a> <a class="action_delete" onclick="deletePage(this);">'.$this->view->getCibleText('menu_treeview_delete').'</a> <a class="action_setAsHomePage" onclick="setAsHomePage(this);">'.$this->view->getCibleText('menu_treeview_homepage').'</a> <a class="action_viewStructure" onclick="viewStructure(this);">'.$this->view->getCibleText('menu_treeview_pagestructure').'</a> <a class="action_viewContents" onclick="viewContents(this)">'.$this->view->getCibleText('menu_treeview_content_management').'</a></div></div></div></div><div class="ft"><div class="c"></div></div></div>';
            // get all childrens of the page
            $menu .=  $this->findChildPages($Row['P_ID']);
            $menu .= '</li>';
        }

        return $menu;
    }

    function findChildPages($ParentID){
        // get all childrens associated with the parentID
        $Pages = $this->_db;
        $Select = $Pages->select()
                        ->from('PagesIndex')
                        ->join('Pages', 'Pages.P_ID = PagesIndex.PI_PageID')
                        ->where('Pages.P_ParentID = ?', $ParentID)
                        ->where('PagesIndex.PI_LanguageID = ?', $this->_defaultEditLanguage)
                        ->where('Pages.P_ParentID <> ?', '0')
                        ->order('Pages.P_Position');

        $Rows = $Pages->fetchAll($Select);

        // continue to build the tree...
        $menu = '';
        if(count($Rows) > 0){
            $menu  = '<ul class="zone tree-view" style="display: none">';
            foreach($Rows as $Row){
               $menu .= '<li id="page_'.$Row['PI_PageID'].'" class="sortable_items collapsed" pageid="'.$Row['PI_PageID'].'" parentid="'.$Row['P_ParentID'].'"><a class="state" onclick="toggleMenu(this)">&nbsp;</a><img class="drag_handle" src="'.$this->view->baseUrl().'/icons/file.png" align="left" /><a class="element" onclick="toggleActionMenu(this)">'.$Row['PI_PageTitle'].'</a><div class="actions-dialog"><div class="hd"><div class="c"></div></div><div class="bd"><div class="c"><div class="s"><div class="actions"><a class="action_add" onclick="addPage(this);">'.$this->view->getCibleText('menu_treeview_add').'</a> <a class="action_edit" onclick="editPage(this);">'.$this->view->getCibleText('menu_treeview_edit').'</a> <a class="action_delete" onclick="deletePage(this);">'.$this->view->getCibleText('menu_treeview_delete').'</a> <a class="action_setAsHomePage" onclick="setAsHomePage(this);">'.$this->view->getCibleText('menu_treeview_homepage').'</a> <a class="action_viewStructure" onclick="viewStructure(this);">'.$this->view->getCibleText('menu_treeview_pagestructure').'</a> <a class="action_viewContents" onclick="viewContents(this)">'.$this->view->getCibleText('menu_treeview_content_management').'</a></div></div></div></div><div class="ft"><div class="c"></div></div></div>';
                // get all childrens of the page
                $menu .=  $this->findChildPages($Row['P_ID']);
                $menu .= '</li>';
            }
            $menu .= '</ul>';
        }
        return $menu;
    }
}
?>