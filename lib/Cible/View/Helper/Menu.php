<?php
/**
 * Cible
 *
 *
 * @category   Cible
 * @package    Cible_View
 * @subpackage Cible_View_Helper
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaires
 *             (http://www.ciblesolutions.com)
 * @version    $Id: Menu.php 716 2011-12-02 20:03:02Z freynolds $
 */

/**
 * Manage menus for frontend
 *
 * @category   Cible
 * @package    cible_View
 * @subpackage Cible_View_Helper
 * @copyright  Copyright (c) 2009 Cible Solutions d'affaire
 *             (http://www.ciblesolutions.com)
 */
class Cible_View_Helper_Menu extends Cible_View_Helper_Tree
{
    /**
     * Name of the current page. Allows to set selected css style.
     *
     * @var string
     */
    protected $_selectedPage;
    /**
     * Id of the current page. Allows to set selected css style for parent menu..
     * 
     * @var string
     */
    protected $_selectedPageId;
    /**
     * Parameter to enable/disable children displaying.
     * 
     * @var boolean
     */
    protected $_disable_nesting;
    /**
     * Allows to define an alternative id value if the same menu is used more
     * than once.<br/>
     * Add parentAltId in options array
     * 
     * @var string
     */
    protected $_parent_alt_id = "parentid-";
    /**
     * Id true add li tag before and after the menu element for specific CSS 
     * (i.e. allows to set round background corners)
     * Given thru the options array
     * 
     * @var boolean
     */
    protected $_addEnclosure = false;
    
    /**
     * add li tag with a seprator beetween different menu element
     * (i.e. "|" or "&bull;" or image)
     * Given thru the options array
     * 
     * @var boolean
     */
    protected $_addSeparator = ""; 
    
    /**
     * First level menu to set the selected css class when the current page
     * belongs to one of its submenu.
     *
     * @var int
     */
    
    protected $_parentsMenuId = array();
    /**
     * Sets the level to start menu when building submenu.
     *
     * @var int
     */
    protected $_startLevel = 1;
    /**
     * Sets if this is for sitemap 
     *
     * @var bool
     */
    protected $_isSiteMap = false;
    
    protected $html  = "";
    protected $limit = 8;
    protected $level = "";
    protected $title = "";

    /**
     * Set default values and the first level container (ul)
     *
     * @param Mixed $menu    If String: Fecth menu data according its name.<br/>
     *                       If Array: It must contain the menu tree.
     *
     * @param array $options Options to manage menu behaviour<br />
     *                       Ex: disable_nesting => true, parentAltId => (string)
     *
     * @return string html code to display the menu and is children
     */
    public function menu($menu, $options = array())
    {
        $this->_addSeparator = "";
        
        $menuItem = array();
        $_menu = "";
        if (isset($options['addEnclosure']))
            $this->_addEnclosure = $options['addEnclosure'];
        if (isset($options['class']) && $options['class'] == 'sitemap')
            $this->_isSiteMap = true;
        else
            $this->_isSiteMap = false;

        if ($this->view->selectedPage)
            $this->_selectedPage = $this->view->selectedPage;
        elseif (Zend_Registry::isRegistered('selectedPage'))
            $this->_selectedPage = Zend_Registry::get('selectedPage');
        else
        {
            $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
            $this->_selectedPage = $params['controller'];
            if ($params['controller'] == 'index')
                $this->_selectedPage = Cible_FunctionsPages::getPageNameByID(1);
        }
        
        if (isset($options['addSeparator']))
            $this->_addSeparator = $options['addSeparator'];
        
        $oPages = new PagesObject();
        $pageData = $oPages->pageIdByController($this->_selectedPage);
        $this->_selectedPageId = $pageData['P_ID'];
        
        if (!is_array($menu))
        {
            $_menu = new MenuObject($menu);
            $parentId = isset($options['parentId']) ? $options['parentId'] : 0;
            $tree = $_menu->populate($parentId);            
        }
        elseif(count($menu) > 1 && empty ($menu['MID_MenuID']))
        {
            $tree = $menu;            
        }
        else
        {
            $_menu = new MenuObject($menu['MID_MenuID']);
            unset($menu['MID_MenuID'], $menu['MID_ParentId']);

            $tree = $menu;
            
            if (Zend_Registry::isRegistered('selectedCatalogPage'))
            {
                $currentUrl = preg_replace('#\/page\/[0-9]*#', '', substr($this->view->request->getPathInfo(), 1));
                if (strrpos($currentUrl, '/') == 1)
                    $currentUrl = substr($currentUrl, -1);
                $nbParts = substr_count($currentUrl, '/');
                if ($nbParts >= 3)
                {
                    $lastPos = strrpos($currentUrl, '/');
                    $currentUrl = substr($this->view->request->getPathInfo(), 1, $lastPos);
                }
                elseif ($nbParts == 0)
                    $currentUrl = $tree[0]['Link'];

                $this->_selectedPage = $currentUrl;
            }
        }
        if (is_object($_menu) && $_menu->getId())
            $menuItem = $_menu->getMenuItemByPageId($this->_selectedPageId);
        
        if ($menuItem)
        {
            $this->_getParentsMenuId($menuItem, $_menu);
            $this->view->assign('menuId', $menuItem['MID_MenuID']);
        }
        
        if (is_array($options))
        {
            $parentId = isset($options['parentId']) ? $options['parentId'] : 0;
            $this->_parent_alt_id = isset($options['parentIdAlt']) ? $options['parentIdAlt'] : "parentid-";
        }
        if (!empty($options['startLevel']))
            $this->_startLevel = $options['startLevel'];
        
        $this->_disable_nesting = isset($options['disable_nesting']) && $options['disable_nesting'] == true ? true : false;

        parent::tree($tree, $options);
        
       // var_dump($tree);
        if($tree){
            return "<ul id='{$this->_ul_id}' class='{$this->_class}' {$this->_attribs}>" . $this->generateList($tree, true) . "</ul>";
        }
    }

    /**
     * Generate the sub menu list = Children of the menu
     *
     * @param array $tree                An array with all the menu data.
     * @param bool  $addFirstLastClasses Set CSS class for the first and last li
     * @param int   $level               Used to exclude duplicate style by
     * level.
     *
     * @return string
     */
    protected function generateList($tree, $addFirstLastClasses = false, $level = 1)
    {

        $content     = '';
        $menuContent = '';

        $i = 1;
        $item_number = count($tree);
        $current_page = '';
        
        foreach ($tree as $object)
        {
            if(isset($object)){
                $tmp = '';
                $object['Title'] = htmlentities($object['Title']);
                $nbElem = 0;

                $liclass = array();

                $url = $link = empty($object['PageID']) || $object['PageID'] == -1 ? $object['Link'] : Cible_FunctionsPages::getPageNameByID($object['PageID'], Zend_Registry::get('languageID'));
                if ($object['PageID'] > 0 && !empty ($object['Link']) )
                    $link = $link . $object['Link'];

                $external = false;
                if (empty($link))
                {
                    $link = 'javascript:void(0)';
                    array_push($liclass, 'placeholder');
                }
                else
                {
                    if (substr($link, 0, 4) == 'http')
                    {
                        $link = "{$link}";
                        $external = true;
                    }
                    else
                        $link = "{$this->view->baseUrl()}/{$link}";
                }


                if ($external)
                    $menuContent = "<a href='{$link}' class='level-{$level}' target='_blank'>{$object['Title']}</a>\r\n";
                elseif (isset ($object['loadImage']) && (bool)$object['loadImage'] && $this->_startLevel == 1 && !$this->_isSiteMap)
                {
                    $folder    = 'menu';
                    $config    = Zend_Registry::get('config');
                    $imgPrefix = $config->menu->image->thumb->maxWidth . 'x'
                                 . $config->menu->image->thumb->maxHeight . '_';
                    if ($object['Placeholder'] == 2)
                    {
                        $imgPrefix = $config->catalog->subcategory->thumb->maxWidth . 'x'
                                     . $config->catalog->subcategory->thumb->maxHeight . '_';
                        $folder = 'catalog/sub-categories';
                    }

                    $imgFolder = $this->view->baseUrl() . '/data/images/'. $folder .'/' . $object['ID'] . "/";
                    $source    = $imgFolder . $imgPrefix .$object['menuImage'];

                    $menuContent  = "<p class='imgMenuCont'>";
                    if (!empty($object['menuImage']))
                    {
                        $menuContent .= "<a href='{$link}' class='level-{$level}'>";
                        $menuContent .= $this->view->image($source, array('alt' => $object['Title']));
                        $menuContent .= "</a>\r\n";
                    }
                    else
                    {
                        $menuContent .= "<a href='{$link}' class='level-{$level}'>";
                        $menuContent .= $this->view->clientImage('pix.gif', array('alt' => $object['Title'], 'style' =>'height:91px;'));
                        $menuContent .= "</a>\r\n";

                    }
                        $menuContent .= "&nbsp;";

                    $menuContent .= "</p>";

                    if ((bool)$object['menuImgAndTitle'])
                    {
                        $menuContent .= "<p class='imgTitle'>";
                        $menuContent .= "<a href='{$link}' class='level-{$level}'>";
                        $menuContent .= $object['Title'];
                        $menuContent .= "</a>\r\n";
                        $menuContent .= "</p>";

                    }

                }
                else
                    $menuContent = "<a href='{$link}' class='level-{$level}'>{$object['Title']}</a>\r\n";


                $tmp .= $menuContent;

                if (!empty($object['child']) && is_array($object['child']) && !$this->_disable_nesting)
                {
                    $addPositionArrow = $this->_hasMenuImage($object['child']);

                    if($addPositionArrow && $level == 1 && !$this->_isSiteMap)
                    {
                        $this->_nbChild = count($object['child']);
                        $tmp .= "<div id='positionArrow-{$object['ID']}' class='positionArrow {$object['Style']}'>";
                        $tmp .= "";
                        $tmp .= "</div>";
                    }
                    if ($object['Placeholder'] == 2 && empty ($this->html))
                    {
                        $this->title = $object['Title'];
                        $this->level = $level;
                        $this->limit = 8;
                        $this->html = $this->_addListAllLink($link);
                    }
                    else
                    {
                        $properties = get_class_vars(get_class($this));
                        $this->limit = $properties['limit'];
                        $this->html = "";
                    }

                    $tmp .= "<ul class='level-{$level}'>\r\n";
                    $tmp .= $this->generateList($object['child'], $addFirstLastClasses, $level + 1);
                    $tmp .= $this->html;
                    $tmp .= "</ul>\r\n";
                }

                if (!empty($this->_liClass))
                    array_push($liclass, $this->_liClass);

                $tmpArray = array();
                if (count($this->_parentsMenuId))
                    $tmpArray = $this->_parentsMenuId;

                if ($url == $this->_selectedPage || in_array($object['ID'], $tmpArray))
                {
                    //$test = $this->_getParentsMenuId($object);
                    array_push($liclass, 'selected');

                    if ($level > Zend_Registry::get('selectedItemMenuLevel'))
                    {
                        $selectedItemMenuID = $object['ID'];
                        Zend_Registry::set('selectedItemMenuLevel', $level);
                        Zend_Registry::set('selectedItemMenuID', $selectedItemMenuID);
                        $session = new Zend_Session_Namespace('breadcrumb');
                        if ($i == $this->limit )
                            array_push($liclass, 'lastFirstLine');
                    }
                }

                $separatorClass = "";

                if ($addFirstLastClasses)
                {                                
                    if ($i == 1)
                    {
                        array_push($liclass, 'first');
                        $separatorClass = 'first';
                    }

                    if ($i == $item_number)
                    {
                        array_push($liclass, 'last');
                        $separatorClass = 'last';
                    }
                }

                array_push($liclass, "level-{$level}");

                if( !empty( $object['Style'] ))
                    array_push( $liclass, $object['Style']);

                if ($this->_addEnclosure)
                    $content .= "<li class='left" . ($url == $this->_selectedPage ? ' selectedLeft' : '') . "'></li>";

                $current_class = count($liclass) > 0 ? "class='" . implode(' ', $liclass) . "'" : '';

                $content .= "<li {$current_class} id='" . $this->_parent_alt_id . "{$object['ID']}'>" .
                    $tmp .
                    "</li>\r\n";

                if ($this->_addEnclosure)
                    $content .= "<li class='right" . ($url == $this->_selectedPage ? ' selectedRight' : '') . "'></li>";

                if ($this->_addSeparator)
                    $content .= "<li class='verticalSeparator " . $separatorClass . "'>" . $this->_addSeparator . "</li>";

                if($i > $this->limit && $this->_startLevel == 1  && !$this->_isSiteMap)
                {
                    $content .= $this->html;
                    $content .= "</ul>\r\n";
                    $content .= "<ul class='level-{$this->level} repeat'>\r\n";
                    $this->html = "";
                }
                $i++;

            }
        }
        return $content;
    }

    private function _hasMenuImage($array)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $child)
            {
                if ($child['loadImage'])
                {
                    return true;
                }
            }
        }
        return false;
    }

    private function _getParentsMenuId(array $itemMenu, MenuObject $oMenu = null)
    {
        if (is_null($oMenu))
        {
            $page = Cible_FunctionsPages::getPageDetails($itemMenu['PageID'], Zend_Registry::get('languageID'));
            $page = $page->toArray();
            
            $menu = Cible_FunctionsPages::getMenuByPageId($page['P_ParentID']);
            /*var_dump($menu);*/
            $oMenu = new MenuObject($menu[0]['MID_MenuID']);
            
            $menuId = $menu[0]['MID_ID'];
        }
        else
            $menuId   = $itemMenu['MID_ParentID'];
        
        $menuId   = $itemMenu['MID_ParentID'];
        $tmpArray = array();
        
        while($menuId != 0)
        {
            $details = $oMenu->getMenuItemById($menuId);

            array_push($tmpArray, $details['MID_ID']);
            $menuId = $details['MID_ParentID'];
        }
        
        $this->_parentsMenuId = $tmpArray;
    }
    
    private function _addListAllLink($link = '')
    {
        $this->view->assign('url', $link);
        $html = $this->view->render('partials/listAllLink.phtml');
        
        return $html;
    }
}
