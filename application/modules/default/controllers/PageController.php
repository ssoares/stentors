<?php
/**
* Call controllers associated with the page called
*
* The system checks all the blocks associated with the page and call proper module controllers to display content
*
* PHP versions 5
*
* LICENSE:
*
* @category   Controller
* @package    Default
* @author     Alexandre Beaudet <alexandre.beaudet@ciblesolutions.com>
* @copyright  2009 CIBLE Solutions d'Affaires
* @license    http://www.ciblesolutions.com
* @version    $Id: PageController.php 826 2012-02-01 04:15:13Z ssoares $
*/

class PageController extends Cible_Controller_Action
{

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

    }

    public function indexAction()
    {
        $Param = $this->_getParam( 'Param' );
        $Action = $Param['action'];

        // if user has an account and is logged
        $islogged = Cible_FunctionsGeneral::getAuthentication();
        Zend_Registry::set('user', $islogged);

        // grab the Id, language and title of the page called
        $Row = $this->_getParam( 'Row' );
        $view_template = $Row['V_Path'];

        Zend_Registry::set('pageID', $Row['PI_PageID']);

        Zend_Registry::set('languageID', $Row['PI_LanguageID']);
        Zend_Registry::set('currentUrlAction', $this->_request->getParam('action'));

        $session = new Cible_Sessions();

        $session->languageID = $Row['PI_LanguageID'];

        Zend_Registry::set('altImageFirst',$Row['PI_AltPremiereImage']);
        Zend_Registry::set('languageSuffix', $Row['L_Suffix']);
        $languageSuffix = $Row['L_Suffix'];
        Zend_Registry::set('pageTitle', $Row['PI_PageTitle']);
        Zend_Registry::set('pageIndex', $Row['PI_PageIndex']);
        Zend_Registry::set('current_theme', $Row['PT_Folder']);
        if (!Zend_Registry::isRegistered('selectedItemMenuLevel'))
                Zend_Registry::set ('selectedItemMenuLevel', 0);
        Zend_Registry::set('config', $this->_config);

        $absolute_web_root = Zend_Registry::get('absolute_web_root');

        // Set Meta Tags
        $this->view->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1.0');
        $this->view->headMeta()->appendName('Author', 'Cible Solutions d\'Affaires');
        $this->view->headMeta()->appendName('Copyright', 'Cible Solutions d\'Affaires - ' . date('Y'));
        $this->view->headMeta()->appendName('Publisher', 'Cible Solutions d\'Affaires - ' . date('Y'));
        $this->view->headMeta()->appendName('Language', Zend_Registry::get("languageSuffix"));

        $this->view->placeholder('metaOther')->set($Row['PI_MetaOther']);

        $this->view->headMeta()->appendName('Description', $Row['PI_MetaDescription']);
        $this->view->headMeta()->appendName('Keywords', $Row['PI_MetaKeywords']);
        $this->view->headMeta()->appendName('Robots', 'all, noodp');
        $this->view->headMeta()->appendName('Date-Revision-yyyymmdd', date('Ymd'));
        $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        $this->view->headMeta()->appendHttpEquiv('Content-Language',  $languageSuffix.'-ca');
        $this->view->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=8');

        $this->view->headMeta()->appendName('DC.Description', $Row['PI_MetaDescription']);
        $this->view->headMeta()->appendName('DC.Subject', $Row['PI_MetaKeywords']);
        $this->view->headMeta()->appendName('DC.format', 'text/html');
        $this->view->headMeta()->appendName('DC.language', $languageSuffix.'-ca', array('scheme' => 'RFC3066'));


        $clientLogo = $this->_config->clientLogo->src;
        Zend_Registry::set('addThis', "{$absolute_web_root}/themes/default/images/{$languageSuffix}/{$clientLogo}");

        $this->view->assign('showTitle', $Row['P_ShowTitle']);
        $this->view->assign('selectedPage', $Row['PI_PageIndex']);
        $this->view->assign('imgHeader', $Row['PI_TitleImageSrc']);
        $this->view->assign('PI_Secure', $Row['PI_Secure']);
        $this->view->assign('PI_TitleImageAlt', $Row['PI_TitleImageAlt']);
        $currentPageID = $Row['PI_PageID'];

        // finds the current page layout and swap it
        $layout_file = Cible_FunctionsPages::getLayoutPath($currentPageID);
        $this->_helper->layout->setLayout( str_replace('.phtml','',$layout_file) );

        // put the baseurl on a registry key
        Zend_Registry::set('baseUrl', $this->getFrontController()->getBaseUrl());

        // display the page title on the website
        if (!empty($Row['PI_MetaTitle']))
        {
            $this->view->headTitle($Row['PI_MetaTitle']);
            $this->view->headMeta()->appendName('DC.title', $Row['PI_MetaTitle']);
        }
        else
        {
            $this->view->headTitle($this->_config->site->title);
            $this->view->headTitle()->setSeparator(' > ');
            $this->view->headTitle(Zend_Registry::get("pageTitle"));
        }

        // display metadata on the website
        $this->view->metaDescription = $Row['PI_MetaDescription'];
        $this->view->metaKeywords = $Row['PI_MetaKeywords'];
        $this->view->pageTitle = $Row['PI_PageTitle'];

        // make a request to get all the blocks to be displayed
        $Blocks = new Blocks();
        $Select = $Blocks->select()->setIntegrityCheck(false);
        $Select->from('Blocks')
                ->join('Modules', 'Modules.M_ID = Blocks.B_ModuleID')
                ->join('Parameters','Parameters.P_BlockID = Blocks.B_ID', array('B_Action'=>'P_Value'))
                ->where('Parameters.P_Number  = ?', 999)
                ->where('Blocks.B_PageID = ?', Zend_Registry::get("pageID"))
                ->where('Blocks.B_Online = ?', 1)
                ->order('Blocks.B_Position ASC');

//        if (!$islogged)
//            $Select->where ('B_Secured = ?', 0);

        $Rows = $Blocks->fetchAll($Select);

        // Actions to be called in the view for rendering the page's blocks
        $blocks = array();

        // for all blocks to display, call the proper controller module
        foreach ($Rows as $Row){
            $Module = $Row['M_MVCModuleTitle'];
            $ActionIndex = $Row['B_Action'];

            $Param['BlockID'] = $Row['B_ID'];
            $Param['secured'] = $Row['B_Secured'];

            if( !isset( $blocks[ $Row['B_ZoneID'] ] ) )
                $blocks[ $Row['B_ZoneID'] ] = array();

            array_push($blocks[ $Row['B_ZoneID'] ], array(
                'action'        => $ActionIndex,
                'controller'    => 'index',
                'module'        => $Module,
                'params'        => $Param
            ));
        }

        $this->view->assign('blocks', $blocks);
        $this->view->assign('view_template', $view_template);
        $this->view->assign('currentPageID', $currentPageID);
        $this->view->assign('absolute_web_root', $absolute_web_root);

        $i = 0;
        foreach($this->_config->themes->default->styles as $style){

            if( !is_null($style->ie_version) )
            {
                $this->view->headLink()->offsetSetStylesheet((200 + $i),"{$this->view->baseUrl()}/themes/default/css/{$style->filename}", $style->media, $style->ie_version);
                $this->view->headLink()->prependStylesheet("{$this->view->baseUrl()}/themes/default/css/{$style->filename}", $style->media, $style->ie_version);
            }
            else
            {
                $this->view->headLink()->offsetSetStylesheet(1,"{$this->view->baseUrl()}/themes/default/css/{$style->filename}", $style->media);
                $this->view->headLink()->prependStylesheet("{$this->view->baseUrl()}/themes/default/css/{$style->filename}", $style->media);
            }
            $i++;
        }

        $this->view->headLink(array('rel' => 'canonical', 'href' => $absolute_web_root . $this->_request->getPathInfo()));

        if ($this->_config->fontController->embeded)
        {
            $this->view->headScript()->appendFile($this->view->locateFile('font-controller.js', 'jquery'));
            $this->view->headScript()->appendFile($this->view->locateFile('jquery.cookie.js', 'jquery'));
        }

        if ($this->_config->addthisWidget->embeded)
        {
            $this->view->headScript()->appendFile($this->view->locateFile('addthis_widget.js'));
        }

        if ($this->_config->setBgStyle)
            $this->view->setBgStyle();
    }
}