<?php

abstract class Cible_FunctionsPages extends DataObject
{
    public static function getPageDetails($PageID, $langId = null){
        $langId = is_null($langId) ? Zend_Registry::get('currentEditLanguage') : $langId;

        $Pages = new PagesIndex();
        $Select = $Pages->select()
                        ->setIntegrityCheck(false)
                        ->from('Pages')
                        ->joinLeft('PagesIndex','Pages.P_ID = PagesIndex.PI_PageID')
                        ->joinLeft('Views', 'Pages.P_ViewID = Views.V_ID')
                        ->where('PagesIndex.PI_LanguageID = ?', $langId)
                        ->where('PagesIndex.PI_PageID= ?', $PageID);


        $row = $Pages->fetchRow($Select);

        if( empty( $row ) ){

            $Pages = new Pages();
            $Select = $Pages->select()
                            ->where('P_ID = ?', $PageID);

            $row = $Pages->fetchRow($Select);
        }

        return $row;
    }

    public static function getMenuDetails($menuId, $langId = null, $menuTitle){
        $langId = is_null($langId) ? Zend_Registry::get('currentEditLanguage') : $langId;

        $menu = new MenuObject($menuTitle);

        $menuItem = $menu->getMenuItemById($menuId);

        return $menuItem;
    }

    /**
     * Fetch menuID from menu.
     *
     * @param int $menuID
     * @param int $pageId
     *
     * @return array
     */
    public static function findMenuID($menuID,$pageId)
    {
        $lang  = Zend_Registry::get("languageID");

        $parentArray = new Pages();
        $select = $parentArray->select()
        ->setIntegrityCheck(false)
        ->from('MenuItemIndex')
            ->join('MenuItemData', 'MID_ID = MII_MenuItemDataID')
            ->where('MII_PageID = ?', $pageId)
            ->where('MID_MenuID = ?',$menuID)
            ->where('MII_LanguageID = ?', $lang);

        return $parentArray->fetchRow($select);
    }

    public static function getPageNameByID($pageId, $lang = null, $isSitemap = false){
          if( $lang == null )
            $lang = Zend_Registry::get('languageID');

          $db = Zend_Registry::get("db");
          $select = $db->select()
              ->from('Pages', array())
              ->joinLeft('PagesIndex', 'P_ID = PI_PageID', 'PI_PageIndex')
              ->where('PI_PageID = ?', $pageId)
              ->where('PI_LanguageID = ?', $lang);
          if ($isSitemap)
              $select->where('P_ShowSiteMap = ?', 1);

          $page_index = $db->fetchRow($select);

          return $page_index['PI_PageIndex'];
    }

    public static function getPageLinkByID($pageId, $lang = null){
          $page = self::getPageNameByID($pageId, $lang);

          $baseUrl = Zend_Registry::get('baseUrl');
          return "$baseUrl/$page";
    }

    public static function getActionNameByLang($actionName, $lang = null){

          if( is_null($lang) )
            $lang = Zend_Registry::get('languageID');

          $db = Zend_Registry::get("db");
          $viewID = $db->fetchOne("SELECT MV_ID FROM ModuleViews WHERE MV_Name = '{$actionName}'");

          return $db->fetchOne("SELECT MVI_ActionName FROM ModuleViewsIndex WHERE MVI_ModuleViewsID = '{$viewID}' AND MVI_LanguageID = '{$lang}'");
      }

    public static function getPageViewDetails($pageID){
        $page = new Pages();
        $page_select = $page->select()->setIntegrityCheck(false);
        $page_select->from('Pages')
                    ->join('Views', 'Pages.P_ViewID = Views.V_ID')
                    ->where('P_ID = ?', $pageID);

        return $page->fetchRow($page_select)->toArray();
    }

    public static function getLayoutPath($id){
        $db = Zend_Registry::get('db');
        $select = $db->select();

        $select->from('Pages',array())
               ->joinLeft('Layouts', 'Pages.P_LayoutID = Layouts.L_ID', array('L_Path'))
               ->where('Pages.P_ID = ?', $id);

       // echo $select;
        //exit;
        return $db->fetchOne($select);
    }

    public static function getAvailableLayouts(){
        $db = Zend_Registry::get('db');

        return $db->fetchAll('SELECT * FROM Layouts');
    }

    public static function getAvailableTemplates(){
        $db = Zend_Registry::get('db');

        return $db->fetchAll('SELECT * FROM Views ORDER BY V_ZoneCount');
    }

    public static function getAllPositions($ParentID){
        $Positions  = Zend_Registry::get("db");
        $Select     = $Positions->select()
                                ->from('PagesIndex')
                                ->join('Pages','Pages.P_ID = PagesIndex.PI_PageID')
                                ->where('P_ParentID = ?', (int)$ParentID)
                                ->where('PI_LanguageID = ?', Zend_Registry::get("languageID"))
                                ->where('P_Home = ?', 0)
                                ->order('P_Position');

        return $Positions->fetchAll($Select);
    }

    public static function fillSelectPosition($Form, $PositionsArray, $Action){
        $TotalPos = count($PositionsArray);
        if ($TotalPos > 0){
            $Cpt=0;
            foreach ($PositionsArray as $Pos){
                if($Cpt == 0){
                    $Form->P_Position->addMultiOption($Pos["P_Position"], 'Première position');
                    if ($Cpt == $TotalPos-1 && $Action == "add"){
                        $Form->P_Position->addMultiOption($Pos["P_Position"]+1, 'Dernière position');
                    }
                }
                elseif($Cpt == $TotalPos-1){
                    if($Action == "add"){
                        $Form->P_Position->addMultiOption($Pos["P_Position"], str_replace('%TEXT%',$PositionsArray[$Cpt-1]["PI_PageTitle"],Cible_Translation::getCibleText("form_select_option_position_below")));
                        $Form->P_Position->addMultiOption($Pos["P_Position"]+1, 'Dernière position');
                    }
                    else{
                        $Form->P_Position->addMultiOption($Pos["P_Position"], 'Dernière position');
                    }
                }
                else{
                    $Form->P_Position->addMultiOption($Pos["P_Position"], str_replace('%TEXT%',$PositionsArray[$Cpt-1]["PI_PageTitle"],Cible_Translation::getCibleText("form_select_option_position_below")));
                }
                $Cpt++;
            }
        }
        else{
           $Form->P_Position->addMultiOption('1', 'Première position');
        }
        return $Form;
    }

    public static function fillSelectLayouts($Form, $layouts){

        foreach($layouts as $layout){
            $Form->P_LayoutID->addMultiOption( $layout['L_ID'] , Cible_Translation::getCibleText("form_select_option_pageLayouts_".$layout['L_ID']));
        }

        return $Form;
    }

    public static function fillSelectTemplates($Form, $templates){

        foreach($templates as $template){
            $Form->P_ViewID->addMultiOption( $template['V_ID'] , Cible_Translation::getCibleText("form_select_option_zoneViews_".$template['V_ID']));
        }

        return $Form;
    }

    public static function deleteAllChildPage($ParentID){
        if($ParentID <> 0){
            $Pages = new Pages();
            $Select = $Pages->select()
                            ->where("P_ParentID = ?", $ParentID);

            $PageArray = $Pages->fetchAll($Select);
            foreach($PageArray as $Page){
                $PageID = $Page["P_ID"];
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

                $PageObj = new Pages();
                $Where = 'P_ID = ' . $PageID;
                $PageObj->delete($Where);

                $PageIndex = new PagesIndex();
                $Where = 'PI_PageID = ' . $PageID;
                $PageIndex->delete($Where);

                //echo("DELETE PAGE : ".$Page["P_ID"]."<br/>");
                //echo("DELETE PAGEINDEX : ".$Page["P_ID"]."<br/>");
            }
        }
    }

    public static function deleteAllBlock($PageID){
        $textSelect = new Blocks();
        $select = $textSelect->select()->setIntegrityCheck(false)
        ->from('Blocks')
        ->where('B_PageID = ?',$PageID)
        ->join('TextData', 'TD_BlockID = B_ID');
        $textData = $textSelect->fetchAll($select);

        foreach($textData as $text){
            $indexData['moduleID']  = $text['B_ModuleID'];
            $indexData['contentID'] = $text['TD_ID'];
            $indexData['languageID'] = $text['TD_LanguageID'];
            $indexData['action'] = 'delete';
            Cible_FunctionsIndexation::indexation($indexData);
        }


        $Blocks = new Blocks();
        $Where  = "B_PageID = " . $PageID;
        $Blocks->delete($Where);
    }

    public static function findChildPage($ParentID, $lang = null){
        if( $lang == null)
            $lang = Zend_Registry::get("languageID");
        $childArray = new Pages();
        $select = $childArray->select()
        ->setIntegrityCheck(false)
        ->from('Pages')
        ->join('PagesIndex','Pages.P_ID = PagesIndex.PI_PageID')
        ->where('Pages.P_ParentID = ?', $ParentID)
        ->where('PagesIndex.PI_LanguageID = ?', $lang)
        ->order('Pages.P_Position');

        return $childArray->fetchAll($select);
    }
    /**
     * Fecth data from the parent page for front-end usage.
     *
     * @param int $pageId
     *
     * @return array
     */
    public static function findParentPageID($pageId)
    {
        $lang        = Zend_Registry::get("languageID");
        $parentArray = new Pages();

        $select = $parentArray->select()
        ->setIntegrityCheck(false)
        ->from('Pages')
            ->joinLeft('PagesIndex', 'PI_PageID = P_ID')
            ->where('Pages.P_ID = ?', $pageId)
            ->where('PagesIndex.PI_LanguageID = ?', $lang);

        return $parentArray->fetchRow($select);
    }

    public static function getAllPagesDetailsArray($ParentID = 0, $lang = null)
    {
        $pages = Cible_FunctionsPages::findChildPage($ParentID, $lang)->toArray();

        $i=0;
        foreach ($pages as $page){
            $pages[$i]['child'] = Cible_FunctionsPages::getAllPagesDetailsArray($page['P_ID'], $lang);
            $i++;
        }

        return $pages;

    }

    public static function buildUlLiMenu($baseUrl, $parentID = 0, $lang = null){

        if($lang == null)
            $lang = Zend_Registry::get('currentEditLanguage');

        $pages_list = Cible_FunctionsPages::getAllPagesDetailsArray(0, $lang);

        $_pages = array();

        foreach($pages_list as $page){
            $tmp = array(
                'ID' => $page['P_ID'],
                'Title' => $page['PI_PageTitle'],
                'onClick' => "{$baseUrl}/page/index/index/ID/{$page['P_ID']}"
            );

            if( !empty($page['child']) )
                $tmp['child'] = self::fillULLIChildren($baseUrl, $page['child']);

            array_push($_pages, $tmp);
        }
        return $_pages;
    }

    /**
     * Make a query to find if a page is a child of any other page other than 0
     *
     * @param  int     $pageID if null, takes the current page ID
     * @param  int     $languageID if null, takes the current language ID
     *
     * @return bool
     */
    public static function getIfIsChildOfOtherPage($pageID=null, $languageID=null)
    {
        $pageID = is_null($pageID) ? Zend_Registry::get('pageID') : $pageID;
        $languageID = is_null($languageID) ? Zend_Registry::get('languageID') : $languageID;
        $Pages = Zend_Registry::get("db");
        $Select = $Pages->select()
                ->from('Pages')
                ->join('PagesIndex', 'PI_PageID = P_ID')
                ->where('Pages.P_ID = ?', $pageID)
                ->where('PI_LanguageID = ?', $languageID)
                ->where('Pages.P_ParentID <> ?', '0');
        $Rows = $Pages->fetchAll($Select);
        if(!empty($Rows)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function fillULLIChildren($baseUrl, $children){
        $_pages = array();

        foreach($children as $page){
            $tmp = array(
                'ID' => $page['P_ID'],
                'Title' => $page['PI_PageTitle'],
                'onClick' => "{$baseUrl}/page/index/index/ID/{$page['P_ID']}"
            );

            if( !empty($page['child']) )
                $tmp['child'] = self::fillULLIChildren($baseUrl, $page['child']);

            array_push($_pages, $tmp);
        }

        return $_pages;
    }

    public static function buildBreadcrumb($pageID, $lang = null){
        if( $lang == null )
            $lang = Cible_Controller_Action::getDefaultEditLanguage();

        $_breadcrumb = array();

        while($pageID != 0){

            $details = self::getPageDetails($pageID, $lang);
            array_push($_breadcrumb, $details['PI_PageTitle']);
            $pageID = $details['P_ParentID'];
        }

        $_breadcrumb = array_reverse($_breadcrumb);

        return implode( ' > ', $_breadcrumb);
    }

    public static function buildTextBreadcrumb($pageID, $lang = null){
        if( $lang == null )
            $lang = Cible_Controller_Action::getDefaultEditLanguage();

        $_baseUrl = Zend_Registry::get('baseUrl');
        $_breadcrumb = array();
        $_first = true;

        while($pageID != 0){

            $_class = '';

            if( $_first ){
                $_first = false;
                $_class = 'current_page';
            }

            $details = self::getPageDetails($pageID, $lang);
            array_push($_breadcrumb, "<a href='{$_baseUrl}/page/index/index/ID/{$pageID}' class='{$_class}'>{$details['PI_PageTitle']}</a>");
            $pageID = $details['P_ParentID'];
        }

        $_breadcrumb = array_reverse($_breadcrumb);

        return implode( ' > ', $_breadcrumb);
    }

    public static function getHomePageDetails(){
        $pagesSelect = new Pages();
        $select = $pagesSelect->select()->setIntegrityCheck(false)
        ->from('Pages')
        ->join('PagesIndex', 'PI_PageID = P_ID')
        ->where('PI_LanguageID = ?', Zend_Registry::get('languageID'))
        ->where('P_Home = 1');

        return $pagesSelect->fetchRow($select)->toArray();
    }

    public static function buildClientBreadcrumb($pageID, $level=0, $showHome=true, $lang = null){
        if( $lang == null )
            $lang = Zend_Registry::get('languageID');

        $_baseUrl = Zend_Registry::get('baseUrl');

        $_breadcrumb = array();
        $_first = true;

        while($pageID != 0){

            $_class = '';

            if( $_first ){$_class = 'current_page';}

            $details = self::getPageDetails($pageID, $lang);

            $link = $_first ? '' : "<a href='{$_baseUrl}/{$details['PI_PageIndex']}' class='{$_class}'>{$details['PI_PageTitle']}</a>";
            array_push($_breadcrumb, $link);
            $pageID = $details['P_ParentID'];

            if( $_first ){$_first = false;}
        }

        if($showHome){
            $homeDetails = self::getHomePageDetails();

            /*if(empty($_baseUrl))
                $_baseUrl = '/';*/

            $link = "<a href='{$_baseUrl}/{$homeDetails['PI_PageIndex']}' class='{$_class}'>".strtoupper($homeDetails['PI_PageTitle'])."</a>";
            array_push($_breadcrumb, $link);
        }
        $_breadcrumb = array_reverse($_breadcrumb);

        for($i=0;$i<$level;$i++){
            array_splice($_breadcrumb,$i+1,1);
        }

        // add the > after the breadcrumb when only on item is found
        if( count($_breadcrumb) == 1 )
            return "{$_breadcrumb[0]} > ";
        else
            return implode( ' > ', $_breadcrumb);
    }

    public static function buildClientBreadcrumbMenu($selectedItemMenuID, $level=0, $menuTitle, $showHome=true, $lang = null){
        if( $lang == null )
            $lang = Zend_Registry::get('languageID');

        $_baseUrl = Zend_Registry::get('baseUrl');

        $_breadcrumb = array();
        $_first = true;
        $_class = '';
        $link   = '';

        while($selectedItemMenuID != 0){


            if( $_first ){$_class = 'current_page';}

            $details     = self::getMenuDetails($selectedItemMenuID, $lang, $menuTitle);
            $pageDetails = self::getPageDetails($details['MII_PageID'], $lang);
            if($details['MII_PageID'] == -1)
                $link = $details['MII_Title'];
            else
                $link = $_first ? '' : "<a href='{$_baseUrl}/{$pageDetails['PI_PageIndex']}' class='{$_class}'>{$details['MII_Title']}</a>";

            array_push($_breadcrumb, $link);
            $selectedItemMenuID = $details['MID_ParentID'];

            if( $_first ){$_first = false;}
        }

        if($showHome){
            $homeDetails = self::getHomePageDetails();

            $link = "<a href='{$_baseUrl}/{$homeDetails['PI_PageIndex']}' class='{$_class}'>".$homeDetails['PI_PageTitle']."</a>";
            array_push($_breadcrumb, $link);
        }
        $_breadcrumb = array_reverse($_breadcrumb);

//        for($i=0;$i<$level;$i++){
//            array_splice($_breadcrumb,$i+1,1);
//        }

        // add the > after the breadcrumb when only on item is found
        if( count($_breadcrumb) == 1 )
            return "{$_breadcrumb[0]} > ";
        else
            return implode( ' > ', $_breadcrumb);
    }

    public static function getSectionParentPageId($pageID, $sectionID){
        $lang = Zend_Registry::get('languageID');
        while($pageID != 0){

            $details = self::getPageDetails($pageID, $lang);

            if( $details['P_ParentID'] == $sectionID)
                return $pageID;

            $pageID = $details['P_ParentID'];
        }
    }

    public static function getPageByModule($moduleId, $viewName, $langId = null)
    {
        if (!$langId)
            $langId = Zend_Registry::get('languageID');

        $db  = Zend_Registry::get("db");

        $select = $db->select()
                ->from('Pages')
                ->distinct()
                ->joinLeft('PagesIndex', 'Pages.P_ID = PagesIndex.PI_PageID')
                ->joinLeft('Blocks', 'Blocks.B_PageID = Pages.P_ID', array())
                ->joinLeft('Modules', 'Blocks.B_ModuleID = Modules.M_ID', array())
                ->joinLeft('ModuleViews', 'Modules.M_ID = ModuleViews.MV_ModuleID', array('MV_Name'))
                ->where('Modules.M_ID = ?', $moduleId)
                ->where('ModuleViews.MV_Name = ?', $viewName)
                ->where('PagesIndex.PI_LanguageID = ?', $langId)
                ;

        $row = $db->fetchAll($select);
        if(!$row)
                return '';

        return $row;

    }

    /**
     * Find the first level menu for the page id.
     * (NB: this is to test and to custom for oher projects works for 1 level)
     *
     * @param int $pageId Id of the page
     * @param int $langId Language id, if null, the language id stored in the registry will be used
     *
     * @return array
     */
    public static function getMenuByPageId($pageId, $langId = null)
    {
        if (!$langId)
            $langId = Zend_Registry::get('languageID');

        $db  = Zend_Registry::get("db");

        $select = $db->select()
                ->from('MenuItemData')
                ->distinct()
                ->joinLeft('MenuItemIndex', 'MID_ID = MII_MenuItemDataID')
                ->where('MII_PageID = ?', $pageId)
                ->where('MII_LanguageID = ?', $langId)
                ->where('MID_ParentID = ?', 0)
                ;

        $row = $db->fetchAll($select);

        if(!$row)
                return '';

        return $row;
    }
}
