<?php


class News_IndexController extends Cible_Controller_Categorie_Action
{

    protected $_moduleID = 2;
    protected $_defaultAction = 'list';

    protected function delete($blockID)
    {
        /*
          $blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
          $blockParam = Cible_FunctionsBlocks::getBlockParameters($blockID);

          $categoryID = $blockParam[0]['P_Value'];

          // get all news blocks with the same category of the present block
          $blockSelect = new Blocks();
          $select = $blockSelect->select()->setIntegrityCheck(false)
          ->from('Blocks')
          ->join('Parameters', 'P_BlockID = B_ID')
          ->where('B_ID <> ?', $blockID)
          ->where('B_ModuleID = ?', $this->_config->modules->news->id)
          ->where('B_Online = 1')
          ->where('P_Number = 1')
          ->where('P_Value = ?', $categoryID);

          $blockData = $blockSelect->fetchRow($select);

          if(count($blockData) == 0){
          // get all news with the category X
          $newsSelect = new NewsData();
          $select = $newsSelect->select()
          ->where('ND_CategoryID = ?', $categoryID);
          $newsData = $newsSelect->fetchAll($select);

          $availableLanguages = Cible_FunctionsGeneral::getAllLanguage();
          foreach($newsData as $news){
          foreach($availableLanguages as $language){
          $indexData['moduleID']  = $this->_config->modules->news->id;
          $indexData['contentID'] = $news['ND_ID'];
          $indexData['languageID'] = $language['L_ID'];
          $indexData['action'] = 'delete';
          Cible_FunctionsIndexation::indexation($indexData);
          }
          }
          }
         */
    }

    public function setOnlineBlockAction()
    {
        parent::setOnlineBlockAction();
        /*
          $blockID = $this->getRequest()->getParam('blockID');
          $pageID  = $this->getRequest()->getParam('ID');

          $blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
          $blockParam = Cible_FunctionsBlocks::getBlockParameters($blockID);

          $categoryID = $blockParam[0]['P_Value'];
          $status     = $blockData['B_Online'];

          // offline
          if($status == 0){
          // get all news blocks with the same category of the present block
          $blockSelect = new Blocks();
          $select = $blockSelect->select()->setIntegrityCheck(false)
          ->from('Blocks')
          ->join('Parameters', 'P_BlockID = B_ID')
          ->where('B_ID <> ?', $blockID)
          ->where('B_ModuleID = ?', $this->_config->modules->news->id)
          ->where('B_Online = 1')
          ->where('P_Number = 1')
          ->where('P_Value = ?', $categoryID);
          $blockData = $blockSelect->fetchRow($select);

          if(count($blockData) == 0){

          // get all news with the category X
          $newsSelect = new NewsData();
          $select = $newsSelect->select()
          ->where('ND_CategoryID = ?', $categoryID);
          $newsData = $newsSelect->fetchAll($select);

          $availableLanguages = Cible_FunctionsGeneral::getAllLanguage();
          foreach($newsData as $news){
          foreach($availableLanguages as $language){
          $indexData['moduleID']  = $this->_config->modules->news->id;
          $indexData['contentID'] = $news['ND_ID'];
          $indexData['languageID'] = $language['L_ID'];
          $indexData['action'] = 'delete';
          Cible_FunctionsIndexation::indexation($indexData);
          }
          }
          }

          }
          // online
          elseif($status == 1){
          // get all news with the category X
          $newsSelect = new NewsData();
          $select = $newsSelect->select()->setIntegrityCheck(false)
          ->from('NewsData')
          ->join('NewsIndex', 'NI_NewsDataID = ND_ID')
          ->where('ND_CategoryID = ?', $categoryID)
          ->where('NI_Status = 1');
          $newsData = $newsSelect->fetchAll($select);

          foreach($newsData as $news){
          $indexData['pageID']    = $categoryID;
          $indexData['moduleID']  = $this->_config->modules->news->id;
          $indexData['contentID'] = $news['ND_ID'];
          $indexData['languageID'] = $news['NI_LanguageID'];
          $indexData['title']     = $news['NI_Title'];
          $indexData['text']      = '';
          $indexData['link']      = '';
          $indexData['contents']  = $news['NI_Title'] . " " . $news['NI_Brief'] . " " . $news['NI_Text'] . " " . $news['NI_ImageAlt'];
          $indexData['action']    = 'update';

          //print_r($indexData);
          Cible_FunctionsIndexation::indexation($indexData);

          }
          }
         */
    }

    public function getManageDescription($blockID = null)
    {
        $baseDescription = parent::getManageDescription($blockID);

        $listParams = $baseDescription;

        $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
        if ($blockParameters)
        {
            $blockParams = $blockParameters->toArray();

            // Catégorie
            $categoryID = $blockParameters[0]['P_Value'];
            $categoryDetails = Cible_FunctionsCategories::getCategoryDetails($categoryID);
            $categoryName = $categoryDetails['CI_Title'];
            $listParams .= "<div class='block_params_list'><strong>";
            $listParams .= $this->view->getCibleText('label_category');
            $listParams .= "</strong>" . $categoryName . "</div>";

            // Nombre d'events afficher
            $nbNewsShow = $blockParameters[1]['P_Value'];
            $listParams .= "<div class='block_params_list'><strong>";
            $listParams .= $this->view->getCibleText('label_number_to_show');
            $listParams .= "</strong>" . $nbNewsShow . "</div>";
        }

        return $listParams;
    }

    public function getIndexDescription($blockID = null)
    {

        $listParams = '';
        $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
        if ($blockParameters)
        {
            $blockParams = $blockParameters->toArray();

            // Catégorie
            $categoryID = $blockParameters[0]['P_Value'];
            $categoryDetails = Cible_FunctionsCategories::getCategoryDetails($categoryID);
            $categoryName = $categoryDetails['CI_Title'];
            $listParams .= "<div class='block_params_list'><strong>Catégorie : </strong>" . $categoryName . "</div>";
        }

        // Nombre de news Online
        $listParams .= "<div class='block_params_list'><strong>Nouvelles en ligne : </strong>" . $this->getNewsOnlineCount($categoryID) . "</div>";

        return $listParams;
    }

    public function listAction()
    {

        if ($this->view->aclIsAllowed('news', 'edit', true))
        {

            // NEW LIST GENERATOR CODE //
            $tables = array(
                'NewsData' => array('ND_ID', 'ND_CategoryID', 'ND_Date', 'ND_ReleaseDate'),
                'NewsIndex' => array('NI_NewsDataID', 'NI_LanguageID', 'NI_Title', 'NI_Status'),
                'Status' => array('S_Code')
            );

            $field_list = array(
                'NI_Title' => array(
                    'width' => '300px'
                ),
                'ND_Date' => array(
                //'width' => '300px'
                ),
                'S_Code' => array(
                    'width' => '80px',
                    'postProcess' => array(
                        'type' => 'dictionnary',
                        'prefix' => 'status_'
                    )
                )
            );

            $this->view->params = $this->_getAllParams();
            $blockID = $this->_getParam('blockID');
            $pageID = $this->_getParam('pageID');

            $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);

            $categoryID = $blockParameters[0]['P_Value'];

            $category = new CategoriesIndex();
            $select = $category->select()
                    ->where('CI_CategoryID = ?', $categoryID)
                    ->where('CI_LanguageID = ?', $this->_defaultEditLanguage);

            $categoryArray = $category->fetchRow($select);
            $this->view->assign('categoryName', $categoryArray['CI_Title']);

            $news = new NewsData();
            $select = $news->select()
                    ->from('NewsData')
                    ->setIntegrityCheck(false)
                    ->join('NewsIndex', 'NewsData.ND_ID = NewsIndex.NI_NewsDataID')
                    ->join('Status', 'NewsIndex.NI_Status = Status.S_ID')
                    ->where('ND_CategoryID = ?', $categoryID)
                    ->where('NI_LanguageID = ?', $this->_defaultEditLanguage);
            //->order('NI_Title');


            $options = array(
                'commands' => array(
                    $this->view->link($this->view->url(array('controller' => 'index', 'action' => 'add')), $this->view->getCibleText('button_add_news'), array('class' => 'action_submit add'))
                ),
                //'disable-export-to-excel' => 'true',
                'filters' => array(
                    'news-status-filter' => array(
                        'label' => 'Filtre 1',
                        'default_value' => null,
                        'associatedTo' => 'S_Code',
                        'choices' => array(
                            '' => $this->view->getCibleText('filter_empty_status'),
                            'online' => $this->view->getCibleText('status_online'),
                            'offline' => $this->view->getCibleText('status_offline')
                        )
                    )
                ),
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                        'edit' => array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => "{$this->view->baseUrl()}/news/index/edit/newsID/%ID%/pageID/" . $pageID . "/blockID/" . $blockID,
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'ND_ID'
                            )
                        ),
                        'delete' => array(
                            'label' => $this->view->getCibleText('button_delete'),
                            'url' => "{$this->view->baseUrl()}/news/index/delete/newsID/%ID%/pageID/" . $pageID . "/blockID/" . $blockID,
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'ND_ID'
                            )
                        )
                    )
                )
            );

            $mylist = New Cible_Paginator($select, $tables, $field_list, $options);

            $this->view->assign('mylist', $mylist);
        }
    }

    public function listAllAction()
    {

        if ($this->view->aclIsAllowed('news', 'edit', true))
        {

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

            // NEW LIST GENERATOR CODE //
            $tables = array(
                'NewsData' => array('ND_ID', 'ND_CategoryID', 'ND_Date'),
                'NewsIndex' => array('NI_NewsDataID', 'NI_LanguageID', 'NI_Title', 'NI_Status'),
                'Status' => array('S_Code'),
                'CategoriesIndex' => array('CI_Title')
            );

            $field_list = array(
                'NI_Title' => array(
                    'width' => '300px'
                ),
                'ND_Date' => array(
                //'width' => '300px'
                ),
                'CI_Title' => array(
                /* 'width' => '80px',
                  'postProcess' => array(
                  'type' => 'dictionnary',
                  'prefix' => 'status_'
                  ) */
                ),
                'S_Code' => array(
                    'width' => '80px',
                    'postProcess' => array(
                        'type' => 'dictionnary',
                        'prefix' => 'status_'
                    )
                )
            );

            $news = new NewsData();
            $select = $news->select()
                    ->from('NewsData')
                    ->setIntegrityCheck(false)
                    ->join('NewsIndex', 'NewsData.ND_ID = NewsIndex.NI_NewsDataID')
                    ->join('Status', 'NewsIndex.NI_Status = Status.S_ID')
                    ->joinRight('CategoriesIndex', 'NewsData.ND_CategoryID = CategoriesIndex.CI_CategoryID')
                    ->joinRight('Categories', 'NewsData.ND_CategoryID = Categories.C_ID')
                    ->joinRight('Languages', 'Languages.L_ID = NewsIndex.NI_LanguageID')
                    ->where('NI_LanguageID = ?', $langId)
                    ->where('NewsIndex.NI_LanguageID = CategoriesIndex.CI_LanguageID')
                    ->where('C_ModuleID = ?', $this->_moduleID);
            //->order('NI_Title');


            $options = array(
                'commands' => array(
                    $this->view->link($this->view->url(array('controller' => 'index', 'action' => 'add')), $this->view->getCibleText('button_add_news'), array('class' => 'action_submit add'))
                ),
                //'disable-export-to-excel' => 'true',
                'to-excel-action' => 'all-news-to-excel',
                'filters' => array(
                    'news-category-filter' => array(
                        'default_value' => null,
                        'associatedTo' => 'ND_CategoryID',
                        'choices' => Cible_FunctionsCategories::getFilterCategories($this->_moduleID)
                    ),
                    'news-status-filter' => array(
                        'label' => 'Filtre 2',
                        'default_value' => null,
                        'associatedTo' => 'S_Code',
                        'choices' => array(
                            '' => $this->view->getCibleText('filter_empty_status'),
                            'online' => $this->view->getCibleText('status_online'),
                            'offline' => $this->view->getCibleText('status_offline')
                        )
                    )
                ),
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                        'edit' => array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => "{$this->view->baseUrl()}/news/index/edit/newsID/%ID%/lang/%LANG%/return/list-all/",
                            'findReplace' => array(
                                array(
                                    'search' => '%ID%',
                                    'replace' => 'ND_ID'
                                ),
                                array(
                                    'search' => '%LANG%',
                                    'replace' => 'L_Suffix'
                                )
                            )
                        ),
                        'delete' => array(
                            'label' => $this->view->getCibleText('button_delete'),
                            'url' => "{$this->view->baseUrl()}/news/index/delete/newsID/%ID%/return/list-all/",
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'ND_ID'
                            )
                        )
                    )
                )
            );

            $mylist = New Cible_Paginator($select, $tables, $field_list, $options);

            $this->view->assign('mylist', $mylist);
        }
    }

    public function addAction()
    {

        // variables
        $pageID = $this->_getParam('pageID');
        $blockID = $this->_getParam('blockID');
        $returnAction = $this->_getParam('return');
        $baseDir = $this->view->baseUrl();

        if (empty($pageID))
            $categoriesList = 'true';
        else
            $categoriesList = 'false';

        if ($returnAction)
            $returnUrl = "/news/index/$returnAction";
        elseif ($blockID)
            $returnUrl = "/news/index/list/blockID/$blockID/pageID/$pageID";
        else
            $returnUrl = "/news/index/list-all/";

        if ($this->view->aclIsAllowed('news', 'edit', true))
        {
            $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";

            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();
                if ($formData['ImageSrc'] <> "")
                    $imageSrc = Zend_Registry::get("www_root") . "/data/images/news/tmp/mcith/mcith_" . $formData['ImageSrc'];
            }
            // generate the form
            $form = new FormNews(array(
                    'baseDir' => $baseDir,
                    'imageSrc' => $imageSrc,
                    'cancelUrl' => "$baseDir$returnUrl",
                    'categoriesList' => "$categoriesList",
                    'newsID' => '',
                    'isNewImage' => true,
                    'addAction' => true
                    /* ,
                      'toApprove' => 0,
                      'status'    => 2 */
                ));
            $this->view->form = $form;

            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();
                if ($form->isValid($formData))
                {
                    /* if (isset($_POST['submitSaveSubmit'])){
                      $formData['ToApprove'] = 1;
                      }
                      elseif (isset($_POST['submitSaveReturnWriting'])){
                      $formData['Status'] = 2;
                      $formData['ToApprove'] = 0;
                      }
                      elseif(isset($_POST['submitSaveOnline'])){
                      $formData['Status'] = 1;
                      $formData['ToApprove'] = 0;
                      }
                      else{
                      $formData['Status'] = 2;
                      $formData['ToApprove'] = 0;
                      } */

                    if (!empty($pageID))
                    {
                        $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
                        $formData['CategoryID'] = $blockParameters[0]['P_Value'];
                    }
                    else
                        $formData['CategoryID'] = $this->_getParam('Param1');

                    if ($formData['Status'] == 0)
                        $formData['Status'] = 2;

                    $newsObject = new NewsObject();
                    //var_dump($formData);
                    //exit;
                    $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['Title']);
                    $formData['ValUrl'] = $formattedName;
                    $newsID = $newsObject->insert($formData, $this->_config->defaultEditLanguage);

                    /* IMAGES */
                    mkdir("../../{$this->_config->document_root}/data/images/news/" . $newsID) or die("Could not make directory");
                    mkdir("../../{$this->_config->document_root}/data/images/news/" . $newsID . "/tmp") or die("Could not make directory");

                    if ($form->getValue('ImageSrc') <> '')
                    {
                        $config = Zend_Registry::get('config')->toArray();
                        $srcOriginal = "../../{$this->_config->document_root}/data/images/news/tmp/" . $form->getValue('ImageSrc');
                        $originalMaxHeight = $config['news']['image']['original']['maxHeight'];
                        $originalMaxWidth = $config['news']['image']['original']['maxWidth'];
                        $originalName = str_replace($form->getValue('ImageSrc'), $originalMaxWidth . 'x' . $originalMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));


                        $srcMedium = "../../{$this->_config->document_root}/data/images/news/tmp/medium_{$form->getValue('ImageSrc')}";
                        $mediumMaxHeight = $config['news']['image']['medium']['maxHeight'];
                        $mediumMaxWidth = $config['news']['image']['medium']['maxWidth'];
                        $mediumName = str_replace($form->getValue('ImageSrc'), $mediumMaxWidth . 'x' . $mediumMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));

                        $srcThumb = "../../{$this->_config->document_root}/data/images/news/tmp/thumb_{$form->getValue('ImageSrc')}";
                        $thumbMaxHeight = $config['news']['image']['thumb']['maxHeight'];
                        $thumbMaxWidth = $config['news']['image']['thumb']['maxWidth'];
                        $thumbName = str_replace($form->getValue('ImageSrc'), $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));

                        copy($srcOriginal, $srcMedium);
                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(array('src' => $srcOriginal, 'maxWidth' => $originalMaxWidth, 'maxHeight' => $originalMaxHeight));
                        Cible_FunctionsImageResampler::resampled(array('src' => $srcMedium, 'maxWidth' => $mediumMaxWidth, 'maxHeight' => $mediumMaxHeight));
                        Cible_FunctionsImageResampler::resampled(array('src' => $srcThumb, 'maxWidth' => $thumbMaxWidth, 'maxHeight' => $thumbMaxHeight));

                        rename($srcOriginal, "../../{$this->_config->document_root}/data/images/news/$newsID/$originalName");
                        rename($srcMedium, "../../{$this->_config->document_root}/data/images/news/$newsID/$mediumName");
                        rename($srcThumb, "../../{$this->_config->document_root}/data/images/news/$newsID/$thumbName");
                    }

                    /*                     * ******************* */

                    if (!empty($pageID))
                    {
                        if ($formData['Status'] == 1)
                        {
                            //$blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
                            //$blockStatus    = $blockData['B_Online'];

                            $indexData['pageID'] = $formData['CategoryID'];
                            $indexData['moduleID'] = $this->_moduleID;
                            $indexData['contentID'] = $newsID;
                            $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                            $indexData['title'] = $formData['Title'];
                            $indexData['text'] = '';
                            $indexData['link'] = '';
                            $indexData['contents'] = $formData['Title'] . " " . $formData['Brief'] . " " . $formData['Text'] . " " . $formData['ImageAlt'];
                            $indexData['action'] = 'add';

                            Cible_FunctionsIndexation::indexation($indexData);
                        }
                    }

                    if (isset($formData['submitSaveClose']))
                        $this->_redirect($returnUrl);
                    else
                        $this->_redirect('news/index/edit/newsID/' . $newsID . "/");
                }
                else
                    $form->populate($formData);
            }
        }
    }

    public function editAction()
    {

        // variables
        $newsID = $this->_getParam('newsID');
        $pageID = $this->_getParam('pageID');
        $returnAction = $this->_getParam('return');
        $blockID = $this->_getParam('blockID');
        $baseDir = $this->view->baseUrl();

        if ($this->view->aclIsAllowed('news', 'edit', true))
        {

            if (empty($pageID))
                $categoriesList = 'true';
            else
                $categoriesList = 'false';

            if ($returnAction)
                $returnUrl = "/news/index/$returnAction";
            elseif ($blockID)
                $returnUrl = "/news/index/list/blockID/$blockID/pageID/$pageID";
            else
                $returnUrl = "/news/index/list-all/";

            $newsObject = new NewsObject();
            $news = $newsObject->populate($newsID, Zend_Registry::get("currentEditLanguage"));

            // image src.
            $config = Zend_Registry::get('config')->toArray();
            $thumbMaxHeight = $config['news']['image']['thumb']['maxHeight'];
            $thumbMaxWidth = $config['news']['image']['thumb']['maxWidth'];

            if (!empty($news['ImageSrc'])){
                $this->view->assign('imageUrl', Zend_Registry::get("www_root") . "/data/images/news/$newsID/" . str_replace($news['ImageSrc'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $news['ImageSrc'], $news['ImageSrc']));
            }
            $isNewImage = 'false';
            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();
                if ($formData['ImageSrc'] <> $news['ImageSrc'])
                {
                    if ($formData['ImageSrc'] == "")
                        $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                    else
                        $imageSrc = Zend_Registry::get("www_root") . "/data/images/news/$newsID/tmp/mcith/mcith_" . $formData['ImageSrc'];

                    $isNewImage = 'true';
                }
                else
                {
                    if ($news['ImageSrc'] == "")
                        $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                    else
                        $imageSrc = Zend_Registry::get("www_root") . "/data/images/news/$newsID/" . str_replace($news['ImageSrc'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $news['ImageSrc'], $news['ImageSrc']);
                }
            }
            else
            {
                if (empty($news['ImageSrc']))
                    $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
                else
                    $imageSrc = Zend_Registry::get("www_root") . "/data/images/news/$newsID/" . str_replace($news['ImageSrc'], $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $news['ImageSrc'], $news['ImageSrc']);
            }

            if (empty($pageID))
               $categoriesList = 'true';
            else
                $categoriesList = 'false';

            // generate the form
            $form = new FormNews(array(
                    'baseDir' => $baseDir,
                    'imageSrc' => $imageSrc,
                    'cancelUrl' => "$baseDir$returnUrl",
                    'categoriesList' => $categoriesList,
                    'newsID' => $newsID,
                    'catagoryID' => $news['CategoryID'],
                    'isNewImage' => $isNewImage
                ));
            $this->view->form = $form;

            // action
            if (!$this->_request->isPost())
            {

                if (isset($news['Status']) && $news['Status'] == 2)
                    $news['Status'] = 0;

                $form->populate($news);
            }
            else
            {
                $formData = $this->_request->getPost();

                if ($form->isValid($formData))
                {

                    if ($formData['isNewImage'] == 'true' && $form->getValue('ImageSrc') <> '')
                    {
                        $config = Zend_Registry::get('config')->toArray();
                        $srcOriginal = "../../{$this->_config->document_root}/data/images/news/$newsID/tmp/" . $form->getValue('ImageSrc');
                        $originalMaxHeight = $config['news']['image']['original']['maxHeight'];
                        $originalMaxWidth = $config['news']['image']['original']['maxWidth'];
                        $originalName = str_replace($form->getValue('ImageSrc'), $originalMaxWidth . 'x' . $originalMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));

                        $srcMedium = "../../{$this->_config->document_root}/data/images/news/$newsID/tmp/medium_{$form->getValue('ImageSrc')}";
                        $mediumMaxHeight = $config['news']['image']['medium']['maxHeight'];
                        $mediumMaxWidth = $config['news']['image']['medium']['maxWidth'];
                        $mediumName = str_replace($form->getValue('ImageSrc'), $mediumMaxWidth . 'x' . $mediumMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));

                        $srcThumb = "../../{$this->_config->document_root}/data/images/news/$newsID/tmp/thumb_{$form->getValue('ImageSrc')}";
                        $thumbMaxHeight = $config['news']['image']['thumb']['maxHeight'];
                        $thumbMaxWidth = $config['news']['image']['thumb']['maxWidth'];
                        $thumbName = str_replace($form->getValue('ImageSrc'), $thumbMaxWidth . 'x' . $thumbMaxHeight . '_' . $form->getValue('ImageSrc'), $form->getValue('ImageSrc'));

                        copy($srcOriginal, $srcMedium);
                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(array('src' => $srcOriginal, 'maxWidth' => $originalMaxWidth, 'maxHeight' => $originalMaxHeight));
                        Cible_FunctionsImageResampler::resampled(array('src' => $srcMedium, 'maxWidth' => $mediumMaxWidth, 'maxHeight' => $mediumMaxHeight));
                        Cible_FunctionsImageResampler::resampled(array('src' => $srcThumb, 'maxWidth' => $thumbMaxWidth, 'maxHeight' => $thumbMaxHeight));

                        rename($srcOriginal, "../../{$this->_config->document_root}/data/images/news/$newsID/$originalName");
                        rename($srcMedium, "../../{$this->_config->document_root}/data/images/news/$newsID/$mediumName");
                        rename($srcThumb, "../../{$this->_config->document_root}/data/images/news/$newsID/$thumbName");
                    }
                    if ($formData['Status'] == 0)
                        $formData['Status'] = 2;

                    $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['Title']);
                    $formData['ValUrl'] = $formattedName;
                    $formData['CategoryID'] = $this->_getParam('Param1');
                    $newsObject->save($newsID, $formData, Zend_Registry::get("currentEditLanguage"));

                    if (!empty($pageID))
                    {
                        //$blockData  = Cible_FunctionsBlocks::getBlockDetails($blockID);
                        //$blockStatus    = $blockData['B_Online'];

                        $indexData = array();
                        $indexData['pageID'] = $formData['CategoryID'];
                        $indexData['moduleID'] = $this->_moduleID;
                        $indexData['contentID'] = $newsID;
                        $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                        $indexData['title'] = $formData['Title'];
                        $indexData['text'] = '';
                        $indexData['link'] = '';
                        $indexData['contents'] = $formData['Title'] . " " . $formData['Brief'] . " " . $formData['Text'] . " " . $formData['ImageAlt'];
                        $indexData['action'] = '';

                        if ($formData['Status'] == 1)
                            $indexData['action'] = 'update';
                        else
                            $indexData['action'] = 'delete';


                        Cible_FunctionsIndexation::indexation($indexData);

                        $this->_redirect($returnUrl);
                    }
                    else
                    {
                        if (isset($formData['submitSaveClose']))
                            $this->_redirect($returnUrl);
                        else
                        {
                            $lang = "";
                            if ($this->_getParam('lang'))
                                $lang = '/lang/' . $this->_getParam('lang');

                            $this->_redirect('news/index/edit/newsID/'
                                . $newsID . $lang);
                        }
                    }
                }
            }
        }
    }

    public function deleteAction()
    {

        // variables
        $pageID = (int) $this->_getParam('pageID');
        $blockID = (int) $this->_getParam('blockID');
        $newsID = (int) $this->_getParam('newsID');

        if ($this->view->aclIsAllowed('news', 'publish', true))
        {
            $this->view->return = !empty($pageID) ? $this->view->baseUrl() . "/news/index/list/blockID/$blockID/pageID/$pageID" : $this->view->baseUrl() . "/news/index/list-all/";

            $newsObject = new NewsObject();

            if ($this->_request->isPost())
            {
                $del = $this->_request->getPost('delete');
                if ($del && $newsID > 0)
                {
                    $newsObject->delete($newsID);
                    $indexData['moduleID'] = $this->_moduleID;
                    $indexData['contentID'] = $newsID;
                    $indexData['languageID'] = Zend_Registry::get("currentEditLanguage");
                    $indexData['action'] = 'delete';
                    Cible_FunctionsIndexation::indexation($indexData);

                    Cible_FunctionsGeneral::delFolder("../../{$this->_config->document_root}/data/images/news/" . $newsID);
                }

                if (!empty($pageID))
                    $this->_redirect("/news/index/list/blockID/$blockID/pageID/$pageID");
                else
                    $this->_redirect("/news/index/list-all/");
            }
            elseif ($newsID > 0)
                $this->view->news = $newsObject->populate($newsID, Zend_Registry::get('currentEditLanguage'));
        }
    }

    public function toExcelAction()
    {
        $this->filename = 'News.xlsx';

        $tables = array(
            'NewsData' => array('ND_ID', 'ND_CategoryID', 'ND_Date', 'ND_ReleaseDate'),
            'NewsIndex' => array('NI_NewsDataID', 'NI_LanguageID', 'NI_Title', 'NI_Status'),
            'Status' => array('S_Code')
        );

        $this->fields = array(
            'NI_Title' => array(
                'width' => '',
                'label' => ''
            ),
            'ND_ReleaseDate' => array(
                'width' => '',
                'label' => ''
            ),
            'ND_Date' => array(
                'width' => '',
                'label' => ''
            ),
            'S_Code' => array(
                'width' => '',
                'label' => ''
            )
        );

        $this->filters = array(
        );

        $this->view->params = $this->_getAllParams();
        $blockID = $this->_getParam('blockID');
        $pageID = $this->_getParam('pageID');

        $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);

        $categoryID = $blockParameters[0]['P_Value'];

        $news = new NewsData();
        $this->select = $this->_db->select()
                ->from('NewsData')
                //->setIntegrityCheck(false)
                ->join('NewsIndex', 'NewsData.ND_ID = NewsIndex.NI_NewsDataID')
                ->join('Status', 'NewsIndex.NI_Status = Status.S_ID')
                ->where('ND_CategoryID = ?', $categoryID)
                ->where('NI_LanguageID = ?', $this->_defaultEditLanguage)
                ->order('NI_Title');

        parent::toExcelAction();
    }

    public function allNewsToExcelAction()
    {
        $this->filename = 'News.xlsx';

        $tables = array(
            'NewsData' => array('ND_ID', 'ND_CategoryID', 'ND_Date', 'ND_ReleaseDate'),
            'NewsIndex' => array('NI_NewsDataID', 'NI_LanguageID', 'NI_Title', 'NI_Status'),
            'Status' => array('S_Code'),
            'CategoriesIndex' => array('CI_Title'),
        );

        $this->fields = array(
            'NI_Title' => array(
                'width' => '',
                'label' => ''
            ),
            'ND_ReleaseDate' => array(
                'width' => '',
                'label' => ''
            ),
            'ND_Date' => array(
                'width' => '',
                'label' => ''
            ),
            'CI_Title' => array(
                'width' => '',
                'label' => ''
            ),
            'S_Code' => array(
                'width' => '',
                'label' => ''
            )
        );

        $this->filters = array(
        );

        $this->view->params = $this->_getAllParams();

        $news = new NewsData();
        $this->select = $this->_db->select()
                ->from('NewsData')
                //->setIntegrityCheck(false)
                ->join('NewsIndex', 'NewsData.ND_ID = NewsIndex.NI_NewsDataID')
                ->join('Status', 'NewsIndex.NI_Status = Status.S_ID')
                ->join('CategoriesIndex', 'NewsData.ND_CategoryID = CategoriesIndex.CI_CategoryID')
                ->where('NI_LanguageID = ?', $this->_defaultEditLanguage)
                ->order('NI_Title');

        $blockID = $this->_getParam('blockID');
        $pageID = $this->_getParam('pageID');

        if ($blockID && $pageID)
        {
            $blockParameters = Cible_FunctionsBlocks::getBlockParameters($blockID);
            $categoryID = $blockParameters[0]['P_Value'];

            $this->select->where('ND_CategoryID = ?', $categoryID);
        }

        parent::toExcelAction();
    }

    public function listApprobationRequestAction()
    {
        if ($this->view->aclIsAllowed('news', 'publish'))
        {

            $tables = array(
                'NewsData' => array('ND_ID', 'ND_CategoryID', 'ND_ReleaseDate'),
                'NewsIndex' => array('NI_NewsDataID', 'NI_LanguageID', 'NI_Title', 'NI_Status'),
                'CategoriesIndex' => array('CI_Title')
            );

            $field_list = array(
                'NI_Title' => array(
                    'width' => '400px'
                ),
                'CI_Title' => array(
                /* 'width' => '80px',
                  'postProcess' => array(
                  'type' => 'dictionnary',
                  'prefix' => 'status_'
                  ) */
                ),
                'ND_ReleaseDate' => array(
                    'width' => '120px'
                )
            );

            $news = new NewsData();
            $select = $news->select()
                    ->from('NewsData')
                    ->setIntegrityCheck(false)
                    ->join('NewsIndex', 'NewsData.ND_ID = NewsIndex.NI_NewsDataID')
                    ->joinRight('CategoriesIndex', 'NewsData.ND_CategoryID = CategoriesIndex.CI_CategoryID')
                    ->joinRight('Languages', 'Languages.L_ID = NewsIndex.NI_LanguageID')
                    ->where('NewsData.ND_ToApprove = ?', 1)
                    ->where('NewsIndex.NI_LanguageID = CategoriesIndex.CI_LanguageID')
                    ->order('NI_Title');


            $options = array(
                'disable-export-to-excel' => 'true',
                'filters' => array(
                    'filter_1' => array(
                        'default_value' => null,
                        'associatedTo' => 'CI_Title',
                        'choices' => Cible_FunctionsCategories::getFilterCategories($this->_moduleID)
                    ),
                    'filter_2' => array(
                        'default_value' => null,
                        'associatedTo' => 'CI_LanguageID',
                        'choices' => Cible_FunctionsGeneral::getFilterLanguages()
                    )
                ),
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                        'edit' => array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => "{$this->view->baseUrl()}/news/index/edit/newsID/%ID%/lang/%LANG%/approbation/true",
                            'findReplace' => array(
                                array(
                                    'search' => '%ID%',
                                    'replace' => 'ND_ID'
                                ),
                                array(
                                    'search' => '%LANG%',
                                    'replace' => 'L_Suffix'
                                )
                            )
                        )
                    )
                )
            );

            $mylist = New Cible_Paginator($select, $tables, $field_list, $options);

            $this->view->assign('mylist', $mylist);
        }
    }

    public function addCategoriesAction()
    {

        if ($this->view->aclIsAllowed($this->view->current_module, 'edit'))
        {
            $categoriesObject = new CategoriesObject();
            $options = array(
                'moduleID' => $this->_moduleID,
                'cancelUrl' => $this->view->url(array('module' => $this->view->current_module, 'controller' => 'index', 'action' => 'list-categories')),
                'addAction' => true
            );

            $form = new FormCategory($options);

            $this->view->assign('form', $form);

            if ($this->_request->isPost())
            {

                $formData = $this->_request->getPost();
                if ($form->isValid($formData))
                {

                    // save
                    $category_id = $categoriesObject->insert($formData, $this->_currentEditLanguage);

                    $views = Cible_FunctionsCategories::getCategoryViews($this->_moduleID);

                    foreach ($views as $view)
                    {

                        $data = array(
                            'MCVP_ModuleID' => $this->_moduleID,
                            'MCVP_CategoryID' => $category_id,
                            'MCVP_ViewID' => $view['MV_ID'],
                            'MCVP_PageID' => $formData["{$view['MV_Name']}_pageID"]
                        );
                        if (!empty($formData["{$view['MV_Name']}_pageID"]))
                            $this->_db->insert('ModuleCategoryViewPage', $data);
                    }

                    $this->_redirect(
                        "{$this->view->current_module}/index/list-categories"
                    );
                } else
                {

                    $form->populate($formData);
                }
            }
        }
    }

    public function editCategoriesAction()
    {

        if ($this->view->aclIsAllowed($this->view->current_module, 'edit'))
        {
            $id = $this->_getParam('ID');

            $categoriesObject = new CategoriesObject();

            $options = array(
                'moduleID' => Cible_FunctionsModules::getModuleIDByName($this->view->current_module),
                'cancelUrl' => "{$this->view->baseUrl()}/{$this->view->current_module}/index/list-categories/"
            );

            $form = new FormCategory($options);

            $this->view->assign('form', $form);

            if ($this->_request->isPost())
            {

                $formData = $this->_request->getPost();
                if ($form->isValid($formData))
                {
                    // save
                    $categoriesObject->save($id, $formData, $this->_currentEditLanguage);

                    $allViews = Cible_FunctionsCategories::getCategoryViews($this->_moduleID);
                    $views = Cible_FunctionsCategories::getCategoryViews($this->_moduleID, $id);

                    $reference_views = array();

                    foreach ($views as $view)
                        $reference_views[$view['MV_ID']] = $view;

                    $views = $reference_views;
                    $this->view->dump($views);

                    foreach ($allViews as $view)
                    {
                        $this->view->dump($view);
                        $data = array(
                            'MCVP_ModuleID' => $this->_moduleID,
                            'MCVP_CategoryID' => $id,
                            'MCVP_ViewID' => $view['MV_ID'],
                            'MCVP_PageID' => $formData["{$view['MV_Name']}_pageID"]
                        );

                        if (!empty($formData["{$view['MV_Name']}_pageID"]))
                        {

                            if (isset($views[$view['MV_ID']]) && isset($views[$view['MV_ID']]['MCVP_ID']))
                                $this->_db->update('ModuleCategoryViewPage', $data, "MCVP_ID = '{$views[$view['MV_ID']]['MCVP_ID']}'");
                            else
                                $this->_db->insert('ModuleCategoryViewPage', $data);
                        }
                    }
                    $this->_redirect("{$this->view->current_module}/index/list-categories/");
                } else
                {

                    $formData = $this->_request->getPost();
                    $form->populate($formData);
                }
            }
            else
            {
                $data = $categoriesObject->populate($id, $this->_currentEditLanguage);

                $views = Cible_FunctionsCategories::getCategoryViews($this->_moduleID, $id);

                if ($views)
                {
                    foreach ($views as $view)
                    {
                        if (!empty($view['MCVP_PageID']))
                        {
                            $data["{$view['MV_Name']}_pageID"] = $view['MCVP_PageID'];
                            $data["{$view['MV_Name']}_controllerName"] = $view['PI_PageIndex'];
                        }
                    }
                }

                $form->populate(
                    $data
                );
            }
        }
    }

    public function deleteCategoriesAction()
    {

        if ($this->view->aclIsAllowed($this->view->current_module, 'edit'))
        {
            $id = $this->_getParam('ID');

            if ($this->_request->isPost() && isset($_POST['delete']))
            {

                $this->_db->delete('Categories', "C_ID = '$id'");
                $this->_db->delete('CategoriesIndex', "CI_CategoryID = '$id'");

                $this->_redirect("/news/index/list-categories/");
            }
            else if ($this->_request->isPost() && isset($_POST['cancel']))
            {
                $this->_redirect('/news/index/list-categories/');
            }
            else
            {
                $fails = false;

                $select = $this->_db->select();
                $select->from('CategoriesIndex', array('CI_Title'))
                    ->where('CategoriesIndex.CI_CategoryID = ?', $id);

                $categoryName = $this->_db->fetchOne($select);

                $this->view->assign('category_id', $id);
                $this->view->assign('category_name', $categoryName);

                $select = $this->_db->select();
                $select->from('NewsData')
                    ->where('NewsData.ND_CategoryID = ?', $id);

                $result = $this->_db->fetchAll($select);

                if ($result)
                {
                    $fails = true;
                }

                if (!$fails)
                {
                    $select = $this->_db->select();
                    $select->from('Blocks')
                        ->joinRight('Parameters', 'Parameters.P_BlockID = Blocks.B_ID')
                        ->where('Parameters.P_Number = ?', 1)
                        ->where('Parameters.P_Value = ?', $id)
                        ->where('Blocks.B_ModuleID = ?', $this->_moduleID);

                    $result = $this->_db->fetchAll($select);

                    if ($result)
                    {
                        $fails = true;
                    }
                }

                $this->_db->delete('ModuleCategoryViewPage', $this->_db->quoteInto('MCVP_CategoryID = ?', $id));

                $this->view->assign('module_name', $this->_moduleName);
                $this->view->assign('module_id', $this->_moduleID);
                $this->view->assign('returnUrl', '/news/index/list-categories/');
                $this->view->assign('fails', $fails);
            }
        }
    }

    private function getNewsOnlineCount($categoryID)
    {
        return $this->_db->fetchOne("SELECT COUNT(*) FROM NewsData LEFT JOIN NewsIndex ON NewsData.ND_ID = NewsIndex.NI_NewsDataID WHERE ND_CategoryID = '$categoryID' AND NI_Status = '1'");
    }

}
?>