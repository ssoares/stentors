<?php

class Sitemap_IndexController extends Cible_Controller_Action
{
    protected $_menusLayout = array(
        'main' => array(),
        'corpo' => array(),
        'recreo' => array()
        );

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

        $newsRob = new SitemapRobots();
        $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));

        parent::siteMapAction($dataXml);
    }

    public function init()
    {
        parent::init();
        $this->setModuleId();
        $this->view->headLink()->offsetSetStylesheet($this->_moduleID, $this->view->locateFile('sitemap.css'));
        $this->view->headLink()->appendStylesheet($this->view->locateFile('sitemap.css'));
    }
    
    public function indexAction()
    {
        $tmp        = array();
        $arrayData  = array();
        $groupMenu  = '';
        $layoutFile = Cible_FunctionsPages::getLayoutPath($this->view->currentPageID);
        
        foreach ($this->_menusLayout as $key => $value)
        {
            if (preg_match('/' . $key . '/', $layoutFile))
                $groupMenu = $key;
        }

        $menusList  = MenuObject::getAllMenuList($groupMenu, true);
        $this->_menusLayout[$groupMenu] = $menusList;
        
        foreach ($this->_menusLayout[$groupMenu] as $menuId)
        {
            $oMenu = new MenuObject($menuId);
            
            if (!empty ($tmp))
                $arrayData = $this->appendIfNotFound($oMenu->populate(), $tmp, $arrayData);
            else
            {
                $arrayData = $oMenu->populate();
            }

            $tmp = $this->verifyChildren($arrayData);
        }

        $this->view->assign('menus', $arrayData);
    }

    private function verifyChildren($children, $tmp = array())
    {
        foreach($children as $child)
        {
            $index = '';
            if($child['PageID'] != -1)
            {
                $index = Cible_FunctionsPages::getPageNameByID($child['PageID']);
            }
            else
            {
                $index = $child['Link'];
            }

            if(!empty($index) && !isset($tmp[$index]))
            {
                $tmp[$index] = '';
            }

            if(isset($child['child']))
            {
                $tmp = $this->verifyChildren($child['child'], $tmp);
            }
        }

        return $tmp;
    }

    private function appendIfNotFound($children, $tmp, $appendTo = array())
    {
        foreach($children as $child)
        {
            $index = '';
            if($child['PageID'] != -1)
            {
                $index = Cible_FunctionsPages::getPageNameByID($child['PageID']);
            }
            else
            {
                $index = $child['Link'];
            }

            if(!empty($index) && !isset($tmp[$index]))
            {
                $tmp[$index] = '';
                array_push($appendTo, array(
                    'ID'    => $child['ID'],
                    'Title' => strip_tags($child['Title']),
                    'Link'  => $child['Link'],
                    'PageID' => $child['PageID'],
                    'Placeholder' => $child['Placeholder']
                ));
                if (!empty($child['child']))
                {
                    $index = count($appendTo) - 1;
                    $appendTo[$index]['child'] = $child['child'];
                }
            }
        }

        return $appendTo;
    }

}