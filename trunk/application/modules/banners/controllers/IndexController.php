<?php


class Banners_IndexController extends Cible_Controller_Action
{

    protected $_labelSuffix;
    protected $_colTitle = array();
    protected $_moduleID = 18;
    protected $_defaultAction = 'list';
    protected $_moduleTitle = 'banners';
    protected $_name = 'index';
    protected $_ID = 'id';
    protected $_currentAction = '';
    protected $_actionKey = '';
    protected $_imageSrc = 'BI_Filename';
    protected $_imagesFolder;
    protected $_rootImgPath;
    protected $_formName = '';
    protected $_joinTables = array();
    protected $_objectList = array(
        'list-group' => 'GroupObject',
        'list-images' => 'ImageObject'
    );
    protected $_actionsList = array();
    protected $_disableExportToExcel = false;
    protected $_disableExportToPDF = false;
    protected $_disableExportToCSV = false;
    protected $_enablePrint = false;

    /**
    * Overwrite the function define in the SiteMapInterface implement in Cible_Controller_Action
    *
    * This function return the sitemap specific for this module
    *
    * @access public
    *
    * @return a string containing xml sitemap
    */
    public function siteMapAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $bannersRob = new BannersRobots();
        $dataXml = $bannersRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));
        parent::siteMapAction($dataXml);
    }

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
        $this->setModuleId();
        $this->view->headLink()->offsetSetStylesheet($this->_moduleID, $this->view->locateFile('banners.css'));
        $this->view->headLink()->appendStylesheet($this->view->locateFile('banners.css'));

        $dataImagePath = "data/images/";

        $this->_imagesFolder = $dataImagePath
            . $this->_moduleTitle . "/";

        $this->_rootImgPath = Zend_Registry::get("www_root")
            . "/data/images/"
            . $this->_moduleTitle . "/";

        $this->_rootFilePath = "data/files/"
            . $this->_moduleTitle . "/";

        $this->view->cleaction = $this->_name;
        $this->_imgIndex = 'imagefeat';
    }

    /**
     * Display the list
     *
     *
     *
     * @return void
     */
    public function indexAction()
    {
        $blockID = $this->_request->getParam('BlockID');

        if ($blockID)
        {
            $this->_blockID = $blockID;
            $params = Cible_FunctionsBlocks::getBlockParameters($blockID);

            $groupId;
            $autoPlay;
            $delais;
            $transition;
            $navi;
            $effect;
            foreach ($params as $param)
            {
                $blockParams[$param['P_Number']] = $param['P_Value'];

            }
            $groupId = $blockParams[1];
            $autoPlay = $blockParams[2];
            $delais = $blockParams[3];
            $transition = $blockParams[4];
            $navi = $blockParams[5];
            $effect = $blockParams[6];
            $this->view->bannerRenderer($groupId,$autoPlay,$delais,$transition,$navi,$effect);
        }
    }

    /**
     * Display the list
     *
     *
     *
     * @return void
     */
    public function featuredAction()
    {
        $langId  = Zend_Registry::get('languageID');
        $blockID = $this->_request->getParam('BlockID');

        if ($blockID)
        {
            $this->_blockID = $blockID;
            $params = Cible_FunctionsBlocks::getBlockParameters($blockID);

            $groupId = 0;
            $autoPlay = 0;
            $delais = 0;
            $transition = 0;
            $navi = 0;
            $effect = 0;

            foreach ($params as $param)
            {
                $blockParams[$param['P_Number']] = $param['P_Value'];
            }

            $groupId = str_replace('_f', '', $blockParams[1]);
            $this->view->autoPlay = $blockParams[2];
            $this->view->delais = $blockParams[3];
            $this->view->transition = $blockParams[4];
            $this->view->navi = $blockParams[5];
            $this->view->effect = $blockParams[6];

            $oBannerFeat = new BannerFeaturedObject();
            $oImageFeat  = new BannerFeaturedImageObject();

            $banner    = $oBannerFeat->populate($groupId, $langId);
            $imgBanner = $oImageFeat->getData($langId, $groupId);

            $config    = Zend_Registry::get('config');
            $cfgBanner = $config->banners->imagefeat->toArray();

            $this->view->imgCfg  = $cfgBanner;
            $this->view->imgFeat = $imgBanner;
            $this->view->imgPath = $this->_imagesFolder . 'featured/' . $groupId . '/';
        }
    }

}