<?php

class Rssreader_IndexController extends Cible_Controller_Action
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
    public function homepagelistAction()
    {
        $_blockID = $this->_request->getParam('BlockID');

        $languageID = Zend_Registry::get('languageID');

        $link = Cible_FunctionsBlocks::getBlockParameter($_blockID, $languageID);
        $linkMax = Cible_FunctionsBlocks::getBlockParameter($_blockID, 3);

        $block_info = Cible_FunctionsBlocks::getBlockDetails($_blockID);

        $db = Zend_Registry::get("db");

        $feed = Zend_Feed_Reader::import($link);

        $data = array(
            'block_title'  => $block_info["BI_BlockTitle"],
            'linkMax'      => $linkMax,
            'title'        => $feed->getTitle(),
            'link'         => $feed->getLink(),
            'dateModified' => $feed->getDateModified(),
            'description'  => $feed->getDescription(),
            'language'     => $feed->getLanguage(),
            'entries'      => array(),
        );

        foreach ($feed as $entry)
        {
            $edata = array(
                'title'        => $entry->getTitle(),
                'description'  => $entry->getDescription(),
                'dateModified' => $entry->getDateModified(),
                'authors'      => $entry->getAuthors(),
                'link'         => $entry->getLink(),
                'content'      => $entry->getContent()
            );
            $data['entries'][] = $edata;
        }

        $this->view->data = $data;

    }

}
?>