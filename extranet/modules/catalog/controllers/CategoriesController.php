<?php

/**
 * Module Catalog
 * Controller for the backend administration.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: CategoriesController.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Manage actions for the categories.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: CategoriesController.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class Catalog_CategoriesController extends Cible_Controller_Block_Abstract
{

    protected $_moduleID = 14;
    protected $_defaultAction = 'list-categories';
    protected $_moduleTitle = 'catalog';
    protected $_name = 'categories';
    protected $_paramId = 'catId';
    protected $_catalogImageFolder;
    protected $_imageFolder;
    protected $_rootImgPath;
    protected $_imageSrc = "CC_imageCat";

    public function init()
    {
        parent::init();
        $dataImagePath = "../../"
                . $this->_config->document_root
                . "/data/images/";

//        $this->_catalogImageFolder = $dataImagePath
//                                        . $this->_moduleTitle . "/";

        $this->_imageFolder = $dataImagePath
                . $this->_moduleTitle . "/"
                . $this->_name . "/";

        $this->_rootImgPath = Zend_Registry::get("www_root")
                . "/data/images/"
                . $this->_moduleTitle . "/"
                . $this->_name . "/";
    }

    public function addAction()
    {
        // web page title
        $this->view->title = "Ajout d'une catégorie";
//        $this->_registry->currentEditLanguage = $this->_defaultEditLanguage;

        // variables
        $returnAction = $this->_getParam('return');
        $baseDir = $this->view->baseUrl();
        $cancelUrl = $baseDir . "/"
                . $this->_moduleTitle . "/"
                . $this->_name . "/"
                . $this->_defaultAction . "/";

        if ($returnAction)
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $returnAction;
        else
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_defaultAction;

        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $imageSrc = $this->view->baseUrl() . "/icons/image_non_ disponible.jpg";
            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();
                if ($formData[$this->_imageSrc] <> "")
                    if ($formData[$this->_imageSrc] <> "")
                        $imageSrc = $this->_rootImgPath
                                . "tmp/mcith/mcith_"
                                . $formData[$this->_imageSrc];
            }
            // generate the form
            $options = array(
                'moduleName' => $this->_moduleTitle . '/' . $this->_name,
                'baseDir'    => $baseDir,
                'cancelUrl'  => $cancelUrl,
                'imageSrc'   => $imageSrc,
                'dataId'     => '',
                'isNewImage' => true
            );
            if (!empty($this->_imageSrc))
                $options['imgField'] = $this->_imageSrc;

            $form = new FormCategories($options);

            $this->view->form = $form;

            if ($this->_request->isPost())
            {
                $formData = $this->_request->getPost();

                if ($form->isValid($formData))
                {
                    $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['CCI_Name']);
                    $formData['CCI_ValUrl'] = $formattedName;

                    $oCategories = new CatalogCategoriesObject();
                    $recordID = $oCategories->insert(
                                    $formData,
                                    $this->_currentEditLanguage
                    );

                    /* IMAGES */
                    mkdir($this->_imageFolder . $recordID)
                            or die("Could not make directory");
                    mkdir($this->_imageFolder . $recordID . "/tmp")
                            or die("Could not make directory");
                    if ($form->getValue($this->_imageSrc) <> '')
                    {

                        $config = Zend_Registry::get('config')->toArray();
                        $srcOriginal = $this->_imageFolder . "tmp/" . $form->getValue($this->_imageSrc);
                        $originalMaxHeight = $config[$this->_moduleTitle]['image']['original']['maxHeight'];
                        $originalMaxWidth = $config[$this->_moduleTitle]['image']['original']['maxWidth'];
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
                        if ($mediumMaxHeight > 0 && $mediumMaxWidth > 0)
                        {
                            $mediumName = str_replace(
                                            $form->getValue($this->_imageSrc),
                                            $mediumMaxWidth
                                            . 'x'
                                            . $mediumMaxHeight
                                            . '_'
                                            . $form->getValue($this->_imageSrc),
                                            $form->getValue($this->_imageSrc)
                            );
                            copy($srcOriginal, $srcMedium);
                        }
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

                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcOriginal,
                                            'maxWidth' => $originalMaxWidth,
                                            'maxHeight' => $originalMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcThumb,
                                            'maxWidth' => $thumbMaxWidth,
                                            'maxHeight' => $thumbMaxHeight)
                        );

                        if ($mediumMaxHeight > 0 && $mediumMaxWidth > 0)
                        {
                            Cible_FunctionsImageResampler::resampled(
                                            array(
                                                'src' => $srcMedium,
                                                'maxWidth' => $mediumMaxWidth,
                                                'maxHeight' => $mediumMaxHeight)
                            );
                            rename($srcMedium, $this->_imageFolder . $recordID . "/" . $mediumName);
                        }

                        rename($srcOriginal, $this->_imageFolder . $recordID . "/" . $originalName);
                        rename($srcThumb, $this->_imageFolder . $recordID . "/" . $thumbName);
                    }
                    $this->_redirect($returnUrl);
                }
                else
                {
                    $form->populate($formData);
                }
            }
        }
    }

    public function editAction()
    {
        // web page title
        $this->view->title = "Édition d'une catégorie";
        // variables
        $recordID = $this->_getParam($this->_paramId);
        $returnAction = $this->_getParam('return');
        $baseDir = $this->view->baseUrl();
        $lang = $this->_getParam('lang');
        if (!$lang)
            $this->_registry->currentEditLanguage = $this->_defaultEditLanguage;

        $langId = $this->_registry->currentEditLanguage;

        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $cancelUrl = $baseDir . "/"
                    . $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_defaultAction . "/";

            if ($returnAction)
                $returnUrl = $this->_moduleTitle . "/"
                        . $this->_name . "/"
                        . $returnAction;
            else
                $returnUrl = $this->_moduleTitle . "/"
                        . $this->_name . "/"
                        . $this->_defaultAction . "/";

            // get event details
            $oCategories = new CatalogCategoriesObject();
            $data = $oCategories->populate($recordID, $langId);

            // image src.
            $config = Zend_Registry::get('config')->toArray();
            $thumbMaxHeight = $config[$this->_moduleTitle]['image']['thumb']['maxHeight'];
            $thumbMaxWidth = $config[$this->_moduleTitle]['image']['thumb']['maxWidth'];

            $this->view->assign('imgPrev', $this->_imageSrc . '_preview');
            $this->view->assign(
                    'imageUrl',
                    $this->_rootImgPath
                    . $recordID . "/"
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
                                . $recordID
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
                                . $recordID . "/"
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
                            . $recordID . "/"
                            . str_replace(
                                    $data[$this->_imageSrc],
                                    $thumbMaxWidth
                                    . 'x'
                                    . $thumbMaxHeight . '_'
                                    . $data[$this->_imageSrc],
                                    $data[$this->_imageSrc]);
            }

            // generate the form
            $form = new FormCategories(
                    array(
                        'moduleName' => $this->_moduleTitle . '/' . $this->_name,
                        'baseDir' => $baseDir,
                        'cancelUrl' => $cancelUrl,
                        'imageSrc' => $imageSrc,
                        'imgField' => $this->_imageSrc,
                        'dataId' => $recordID,
                        'isNewImage' => true
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
                    $formattedName = Cible_FunctionsGeneral::formatValueForUrl($formData['CCI_Name']);
                    $formData['CCI_ValUrl'] = $formattedName;

                    if ($formData['isNewImage'] == 'true' && $form->getValue($this->_imageSrc) <> '')
                    {
                        $config = Zend_Registry::get('config')->toArray();
                        $srcOriginal = $this->_imageFolder
                                . $recordID
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
                                . $recordID . "/tmp/medium_"
                                . $form->getValue($this->_imageSrc);

                        $mediumMaxHeight = $config[$this->_moduleTitle]['image']['medium']['maxHeight'];
                        $mediumMaxWidth = $config[$this->_moduleTitle]['image']['medium']['maxWidth'];

                        if ($mediumMaxHeight > 0 && $mediumMaxWidth > 0)
                        {
                            $mediumName = str_replace(
                                            $form->getValue($this->_imageSrc),
                                            $mediumMaxWidth
                                            . 'x'
                                            . $mediumMaxHeight . '_'
                                            . $form->getValue($this->_imageSrc),
                                            $form->getValue($this->_imageSrc));

                            copy($srcOriginal, $srcMedium);
                        }

                        $srcThumb = $this->_imageFolder
                                . $recordID
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

                        copy($srcOriginal, $srcThumb);

                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcOriginal,
                                            'maxWidth' => $originalMaxWidth,
                                            'maxHeight' => $originalMaxHeight)
                        );
                        Cible_FunctionsImageResampler::resampled(
                                        array(
                                            'src' => $srcThumb,
                                            'maxWidth' => $thumbMaxWidth,
                                            'maxHeight' => $thumbMaxHeight)
                        );
                        if ($mediumMaxHeight > 0 && $mediumMaxWidth > 0)
                        {
                            Cible_FunctionsImageResampler::resampled(
                                            array(
                                                'src' => $srcMedium,
                                                'maxWidth' => $mediumMaxWidth,
                                                'maxHeight' => $mediumMaxHeight)
                            );
                            rename($srcMedium,
                                    $this->_imageFolder
                                    . $recordID . "/" . $mediumName);
                        }
                        rename($srcOriginal,
                                $this->_imageFolder
                                . $recordID . "/" . $originalName);
                        rename($srcThumb,
                                $this->_imageFolder
                                . $recordID . "/" . $thumbName);
                    }

                    $oCategories->save($recordID, $formData, $langId);

                    // redirect

                    if (!empty($pageID))
                        $this->_redirect(
                                $this->_moduleTitle . "/"
                                . $this->_name . "/"
                                . $this->_defaultAction . "/blockID/$blockID/pageID/$pageID");
                    else
                        $this->_redirect($returnUrl);
                }
            }
        }
    }

    public function deleteAction()
    {
        // web page title
        $this->view->title = "Suppression d'une categorie";

        if ($this->view->aclIsAllowed($this->_moduleTitle, 'manage', true))
        {
            // Get the product line id
            $id = (int) $this->_getParam($this->_paramId);
            // generate the form
            $returnUrl = $this->_moduleTitle . "/"
                    . $this->_name . "/"
                    . $this->_defaultAction . "/";

            $this->view->assign(
                    'return',
                    $this->view->baseUrl() . "/" . $returnUrl
            );

            $oCategories = new CatalogCategoriesObject();
            $select = $oCategories->getAll(null, false, $id);
            $data = $this->_db->fetchRow($select);

            $this->view->category = $data;

            if ($this->_request->isPost())
            {
                $del = $this->_request->getPost('delete');
                if ($del && $id > 0)
                {
                    // get all';
                    $oCategories->delete($id);
                    Cible_FunctionsGeneral::delFolder($this->_imageFolder . $id);
                }

                $this->_redirect($returnUrl);
            }
        }
    }

    public function listCategoriesAction()
    {
        // web page title
        $this->view->title = "Catégories";
        if ($this->view->aclIsAllowed($this->_moduleTitle, 'edit', true))
        {
            $tables = array(
                'Catalog_CategoriesData' => array(
                    'CC_ID'),
                'Catalog_CategoriesIndex' => array(
                    'CCI_CategoryID',
                    'CCI_LanguageID',
                    'CCI_Name')
            );
            $field_list = array(
                'CC_ID' => array('width' => '50px'),
                'CCI_Name' => array('width' => '150px'),
            );

            $this->view->params = $this->_getAllParams();
            $pageID = $this->_getParam('pageID');
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

            $lines = new CatalogCategoriesObject();
            $select = $lines->getAll($langId, false);
            $select->joinLeft('BannerGroup', 'BG_ID = C_BannerGroupID');

            $commands = array();
            if ($langId == $this->_defaultEditLanguage)
                $commands = array(
                    $this->view->link($this->view->url(
                            array(
                                'controller' => $this->_name,
                                'action' => 'add'
                            )
                        ), $this->view->getCibleText('button_add_news'), array('class' => 'action_submit add')
                    )
                );
            $options = array(
                'commands' => $commands,
                'disable-export-to-excel' => 'true',
//                    'filters' => array(
//                        'productline-status-filter' => array(
//                            'label' => 'Filtre 1',
//                            'default_value' => null,
//                            'associatedTo' => 'S_Code',
//                            'choices' => array(
//                                '' => $this->view->getCibleText('filter_empty_status'),
//                                'online' => $this->view->getCibleText('status_online'),
//                                'offline' => $this->view->getCibleText('status_offline')
//                            )
//                        )
//                    ),
                'action_panel' => array(
                    'width' => '50',
                    'actions' => array(
                        'edit' => array(
                            'label' => $this->view->getCibleText('button_edit'),
                            'url' => $this->view->baseUrl() . "/"
                            . $this->_moduleTitle . "/"
                            . $this->_name
                            . "/edit/"
                            . $this->_paramId
                            . "/%ID%",
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'CC_ID'
                            )
                        ),
                        'delete' => array(
                            'label' => $this->view->getCibleText('button_delete'),
                            'url' => $this->view->baseUrl() . "/"
                            . $this->_moduleTitle . "/"
                            . $this->_name
                            . "/delete/"
                            . $this->_paramId
                            . "/%ID%/"
                            . $pageID,
                            'findReplace' => array(
                                'search' => '%ID%',
                                'replace' => 'CC_ID'
                            )
                        )
                    )
                )
            );

            $mylist = New Cible_Paginator($select, $tables, $field_list, $options);
            $this->view->assign('mylist', $mylist);
        }
    }

}