<?php

class Search_IndexController extends Cible_Controller_Action
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
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $newsRob = new SearchRobots();  
        $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));            

        parent::siteMapAction($dataXml); 
    }
    
    public function indexAction()
    {
        $params['keywords'] = $searchParams['words'] = $searchWords = $this->_getParam('words');
        $this->view->assign('words', $searchParams['words']);

        $languageID = $lang = Zend_Registry::get('languageID');
        $db = Zend_Registry::get("db");
        $searchResults = array();

        $searchCount = 0;

        $searchResult = Cible_FunctionsIndexation::indexationSearch($searchParams);
        //$this->view->dump($searchResult);
        /*         * ********* TEXT ************* */
        $moduleID = 1;
        $i = 0;
        $y = 0;
        $pageSelect = new PagesIndex();
        $searchPage = array();
        $pageIDArray = array();
        foreach ($searchResult as $result)
        {
            if (($result['moduleID'] == $moduleID && $result['languageID'] == $languageID) || ($result['moduleID'] == 0 && $result['languageID'] == $languageID))
            {
                $select = $pageSelect->select()
                        ->where('PI_PageID = ?', $result['pageID'])
                        ->where('PI_LanguageID = ?', $result['languageID'])
                        ->where('PI_Status = 1');

                $pageData = $pageSelect->fetchRow($select);

                if ($pageData && !in_array($result['pageID'], $pageIDArray))
                {
                    $pageIDArray[] = $result['pageID'];

                    $searchPage[$y]['moduleID'] = $result['moduleID'];
                    $searchPage[$y]['pageID'] = $result['pageID'];
                    $searchPage[$y]['contentID'] = $result['contentID'];
                    $searchPage[$y]['languageID'] = $result['languageID'];
                    $searchPage[$y]['title'] = $result['title'];
                    $searchPage[$y]['text'] = $result['text'];
                    $searchPage[$y]['link'] = $result['link'];
                    $searchPage[$y]['pageTitle'] = $pageData['PI_PageTitle'];
                    $searchPage[$y]['pageIndex'] = $pageData['PI_PageIndex'];
                    $y++;

                    $searchCount++;
                }

                array_splice($searchResult, $i, 1);
            }
            else
                $i++;
        }

        $this->view->assign('searchPage', $searchPage);

        /*         * *************************** */
        /*         * ********* NEWS ************* */
        $moduleID = 2;
        $i = 0;
        $y = 0;
        $searchNews = array();
        foreach ($searchResult as $result)
        {
            if ($result['moduleID'] == $moduleID && $result['languageID'] == $languageID)
            {
                $newsSelect = new NewsData();
                $select = $newsSelect->select()
                        ->where('ND_ID = ?', $result['contentID']);
                $newsData = $newsSelect->fetchRow($select);

                $link = Cible_FunctionsCategories::getPagePerCategoryView($result['pageID'], 'details');
                if ($newsData['ND_ReleaseDate'] <= date('Y-m-d') && $link <> '')
                {
                    $searchNews[$y]['moduleID'] = $result['moduleID'];
                    $searchNews[$y]['pageID'] = $result['pageID'];
                    $searchNews[$y]['contentID'] = $result['contentID'];
                    $searchNews[$y]['languageID'] = $result['languageID'];
                    $searchNews[$y]['title'] = $result['title'];
                    $searchNews[$y]['text'] = $result['text'];
                    $searchNews[$y]['link'] = $link;
                    $y++;

                    $searchCount++;
                }

                array_splice($searchResult, $i, 1);
            }
            else
                $i++;
        }

        $this->view->assign('searchNews', $searchNews);

        /*         * *************************** */
        /*         * ********* EVENTS ************* */
        $moduleID = 7;
        $i = 0;
        $y = 0;
        $searchEvents = array();
        foreach ($searchResult as $result)
        {

            if ($result['moduleID'] == $moduleID && $result['languageID'] == $languageID)
            {
                $link = Cible_FunctionsCategories::getPagePerCategoryView($result['pageID'], 'details');
                if ($link <> '')
                {
                    $searchEvents[$y]['moduleID'] = $result['moduleID'];
                    $searchEvents[$y]['pageID'] = $result['pageID'];
                    $searchEvents[$y]['contentID'] = $result['contentID'];
                    $searchEvents[$y]['languageID'] = $result['languageID'];
                    $searchEvents[$y]['title'] = $result['title'];
                    $searchEvents[$y]['text'] = $result['text'];
                    $searchEvents[$y]['link'] = $link;
                    $y++;

                    $searchCount++;
                }

                array_splice($searchResult, $i, 1);
            }
            else
                $i++;
        }

        $this->view->assign('searchEvents', $searchEvents);

        /*         * *************************** */
        /*         * ********* NEWSLETTERS ************* */

        $moduleID = 8;
        $i = 0;
        $y = 0;
        $searchNewsletters = array();
        foreach ($searchResult as $result)
        {
            if ($result['moduleID'] == $moduleID && $result['languageID'] == $languageID)
            {
                $searchNewsletters[$y]['moduleID'] = $result['moduleID'];
                $searchNewsletters[$y]['pageID'] = $result['pageID'];
                $searchNewsletters[$y]['contentID'] = $result['contentID'];
                $searchNewsletters[$y]['languageID'] = $result['languageID'];
                $searchNewsletters[$y]['title'] = $result['title'];
                $searchNewsletters[$y]['text'] = $result['text'];
                if ($result['link'] == 'release')
                    $searchNewsletters[$y]['link'] = Cible_FunctionsCategories::getPagePerCategoryView(0, 'details_release', 8) . "/ID/" . $result['contentID'];
                else
                    $searchNewsletters[$y]['link'] = Cible_FunctionsCategories::getPagePerCategoryView(0, 'details_article', 8) . "/ID/" . $result['contentID'];

                $y++;

                $searchCount++;
                array_splice($searchResult, $i, 1);
            }
            else
                $i++;
        }

        $this->view->assign('searchNewsletters', $searchNewsletters);

        /*         * *************************** */
        /*         * ********* GALLERY ************* */
        $moduleID = 9;
        $i = 0;
        $y = 0;
        $searchGalleries = array();
        $galleryID = array();
        foreach ($searchResult as $result)
        {
            if ($result['moduleID'] == $moduleID && $result['languageID'] == $languageID)
            {
                $link = Cible_FunctionsCategories::getPagePerCategoryView($result['pageID'], 'details', 9);
                if ($link <> '' && !in_array($result['contentID'], $galleryID))
                {
                    $searchGalleries[$y]['moduleID'] = $result['moduleID'];
                    $searchGalleries[$y]['pageID'] = $result['pageID'];
                    $searchGalleries[$y]['contentID'] = $result['contentID'];
                    $searchGalleries[$y]['languageID'] = $result['languageID'];
                    $searchGalleries[$y]['title'] = $result['title'];
                    $searchGalleries[$y]['text'] = $result['text'];

                    $galleryID[] = $result['contentID'];
                    /*
                      if($result['link'] == 'image')
                      $searchGalleries[$y]['link']     = $link."/ID/{$result['pageID']}";
                      else
                      $searchGalleries[$y]['link']     = $link."/ID/{$result['contentID']}";
                     */
                    $searchGalleries[$y]['link'] = $link . "/ID/{$result['contentID']}";

                    $y++;

                    $searchCount++;
                }

                array_splice($searchResult, $i, 1);
            }
            else
                $i++;
        }

        $this->view->assign('searchGalleries', $searchGalleries);

        $this->view->assign('searchCount', $searchCount);
    }

    public function listAction()
    {

    }

    public function addAction()
    {
        
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {
        
    }

}
?>