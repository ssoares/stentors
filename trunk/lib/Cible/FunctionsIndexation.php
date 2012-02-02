<?php

abstract class Cible_FunctionsIndexation
{

    //var $directory = '../../www/indexation/all_index';

    public static function indexation($indexationData)
    {
        $directory = Zend_Registry::get('lucene_index');
        try
        {
            $index = Zend_Search_Lucene::open($directory);
            //echo("Ouverture d'un index existant : $path");
        }
        catch (Zend_Search_Lucene_Exception $e)
        {
            try
            {
                $index = Zend_Search_Lucene::create($directory);
                //echo("Création d'un nouvel index : $path");
            }
            catch (Zend_Search_Lucene_Exception $e)
            {
                //echo("Impossible d'ouvrir ou créer un index $path");
                //echo($e->getMessage());
                //echo "Impossible d'ouvrir ou créer un index:".
                //   "{$e->getMessage()}";
                exit(1);
            }
        }


        if ($indexationData['action'] == "add")
        {
            Cible_FunctionsIndexation::indexationAdd($indexationData);
        }
        elseif ($indexationData['action'] == "delete")
        {
            Cible_FunctionsIndexation::indexationDelete($indexationData);
        }
        elseif ($indexationData['action'] == "update")
        {
            Cible_FunctionsIndexation::indexationDelete($indexationData);
            Cible_FunctionsIndexation::indexationAdd($indexationData);
        }
    }

    public static function indexationAdd($indexationData)
    {
        $directory = Zend_Registry::get('lucene_index');

        Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Keyword('pageID', $indexationData['pageID']));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('moduleID', $indexationData['moduleID']));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('contentID', $indexationData['contentID']));
        $doc->addField(Zend_Search_Lucene_Field::Keyword('languageID', $indexationData['languageID']));
        $doc->addField(Zend_Search_Lucene_Field::Text('title', Cible_FunctionsGeneral::html2text($indexationData['title'])));
        $doc->addField(Zend_Search_Lucene_Field::Text('text', Cible_FunctionsGeneral::html2text($indexationData['text'])));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('link', $indexationData['link']));
        $doc->addField(Zend_Search_Lucene_Field::UnStored('contents', strtolower(Cible_FunctionsGeneral::removeAccents(Cible_FunctionsGeneral::html2text($indexationData['contents'])))));

        $newIndex = !is_dir($directory);
        $index = new Zend_Search_Lucene($directory, $newIndex);
        $index->addDocument($doc);
        $index->commit();
    }

    public static function indexationDelete($indexationData)
    {
        $directory = Zend_Registry::get('lucene_index');

        if (is_dir($directory))
        {
            $index = Zend_Search_Lucene::open($directory);

            $term = new Zend_Search_Lucene_Index_Term($indexationData['contentID'], 'contentID');

            foreach ($index->termDocs($term) as $id)
            {
                $doc = $index->getDocument($id);
                if ($doc->languageID == $indexationData['languageID'] && $doc->moduleID == $indexationData['moduleID'])
                    $index->delete($id);
            }
        }
    }

    public static function indexationSearch($searchParams)
    {
        Zend_Search_Lucene::setDefaultSearchField('contents');

        $directory = Zend_Registry::get('lucene_index');
        $index = new Zend_Search_Lucene($directory);


        $words = strtolower(Cible_FunctionsGeneral::removeAccents(Cible_FunctionsGeneral::html2text(utf8_decode($searchParams['words']))));

        $wordsArray = explode(' ', $words);
        if (count($wordsArray) > 1)
        {
            $query = new Zend_Search_Lucene_Search_Query_Phrase($wordsArray);
        }
        else
        {
            if (strlen($words) >= 3)
            {
                $pattern = new Zend_Search_Lucene_Index_Term("$words*");
                $query = new Zend_Search_Lucene_Search_Query_Wildcard($pattern);
            }
            else
            {
                $term = new Zend_Search_Lucene_Index_Term($words);
                $query = new Zend_Search_Lucene_Search_Query_Term($term);
            }
        }


        $hits = $index->find($query);

        //echo($query);

        $i = 0;
        $result = array();
        foreach ($hits as $hit)
        {
            $result[$i]['moduleID'] = $hit->moduleID;
            $result[$i]['pageID'] = $hit->pageID;
            $result[$i]['contentID'] = $hit->contentID;
            $result[$i]['languageID'] = $hit->languageID;
            $result[$i]['title'] = $hit->title;
            $result[$i]['text'] = $hit->text;
            $result[$i]['link'] = $hit->link;
            $i++;
        }

        return $result;
    }

    public static function indexationBuild()
    {
        set_time_limit(0);
        /*         * ****** PAGE ******* */
        $pageSelect = new PagesIndex();
        $select = $pageSelect->select()
                ->where('PI_Status = 1');
        $pageData = $pageSelect->fetchAll($select)->toArray();

        $cpt = count($pageData);
        for ($i = 0; $i < $cpt; $i++)
        {
            $indexData['action'] = "add";
            $indexData['pageID'] = $pageData[$i]['PI_PageID'];
            $indexData['moduleID'] = 0;
            $indexData['contentID'] = $pageData[$i]['PI_PageID'];
            $indexData['languageID'] = $pageData[$i]['PI_LanguageID'];
            $indexData['title'] = $pageData[$i]['PI_PageTitle'];
            $indexData['text'] = '';
            $indexData['link'] = '';
            $indexData['contents'] = $pageData[$i]['PI_PageTitle'];

            Cible_FunctionsIndexation::indexation($indexData);
        }

        /*         * ****** TEXT ******* */
        if (class_exists('Text', false))
        {
            $textSelect = new Text();
            $select = $textSelect->select()->setIntegrityCheck(false)
                    ->from('TextData', array('ID' => 'TD_ID', 'LanguageID' => 'TD_LanguageID', 'Text' => 'TD_OnlineText'))
                    //->where('TD_OnlineTitle <> ""')
                    ->join('Blocks', 'B_ID = TD_BlockID', array('BlockID' => 'B_ID', 'ModuleID' => 'B_ModuleID'))
                    ->where('B_Online = 1')
                    ->join('PagesIndex', 'PI_PageID = B_PageID', array('PageID' => 'PI_PageID', 'Title' => 'PI_PageTitle'))
                    ->where('PI_Status = 1')
                    ->where('PI_LanguageID = TD_LanguageID');

            $textData = $textSelect->fetchAll($select)->toArray();

            $cpt = count($textData);
            for ($i = 0; $i < $cpt; $i++)
            {
                $indexData['action'] = "add";
                $indexData['pageID'] = $textData[$i]['PageID'];
                $indexData['moduleID'] = $textData[$i]['ModuleID'];
                $indexData['contentID'] = $textData[$i]['ID'];
                $indexData['languageID'] = $textData[$i]['LanguageID'];
                $indexData['title'] = $textData[$i]['Title'];
                $indexData['text'] = '';
                $indexData['link'] = '';
                $indexData['contents'] = $textData[$i]['Title'] . " " . $textData[$i]['Text'];

                Cible_FunctionsIndexation::indexation($indexData);
            }
        }

        /*         * ********************* */

        /*         * ****** NEWS ******* */
        if (class_exists('NewsData', false))
        {
            $newsSelect = new NewsData();
            $select = $newsSelect->select()->setIntegrityCheck(false)
                    ->from('NewsData', array('NewsID' => 'ND_ID', 'CategoryID' => 'ND_CategoryID'))
                    ->join('NewsIndex', 'NI_NewsDataID = ND_ID', array('LanguageID' => 'NI_LanguageID', 'NewsTitle' => 'NI_Title', 'NewsBrief' => 'NI_Brief', 'NewsText' => 'NI_Text', 'NewsImageAlt' => 'NI_ImageAlt'))
                    ->where('NI_Status = 1');

            $newsData = $newsSelect->fetchAll($select);

            $cpt = count($newsData);
            for ($i = 0; $i < $cpt; $i++)
            {
                $indexData['action'] = "add";
                $indexData['pageID'] = $newsData[$i]['CategoryID'];
                $indexData['moduleID'] = 2;
                $indexData['contentID'] = $newsData[$i]['NewsID'];
                $indexData['languageID'] = $newsData[$i]['LanguageID'];
                $indexData['title'] = $newsData[$i]['NewsTitle'];
                $indexData['text'] = '';
                $indexData['link'] = '';
                $indexData['contents'] = $newsData[$i]['NewsTitle'] . " " . $newsData[$i]['NewsBrief'] . " " . $newsData[$i]['NewsText'] . " " . $newsData[$i]['NewsImageAlt'];

                Cible_FunctionsIndexation::indexation($indexData);
            }
        }
        /*         * ********************* */

        /*         * ****** EVENTS ******* */
        if (class_exists('EventsIndex', false))
        {
            $eventsSelect = new EventsIndex();
            $select = $eventsSelect->select()->setIntegrityCheck(false)
                    ->from('EventsIndex', array('ID' => 'EI_EventsDataID', 'LanguageID' => 'EI_LanguageID', 'Title' => 'EI_Title', 'Brief' => 'EI_Brief', 'Text' => 'EI_Text', 'ImageAlt' => 'EI_ImageAlt'))
                    ->join('EventsData', 'ED_ID = EI_EventsDataID', array('CategoryID' => 'ED_CategoryID'))
                    ->where('EI_Status = 1');

            $eventsData = $eventsSelect->fetchAll($select)->toArray();

            $cpt = count($eventsData);
            for ($i = 0; $i < $cpt; $i++)
            {
                $indexData['action'] = "add";
                $indexData['pageID'] = $eventsData[$i]['CategoryID'];
                $indexData['moduleID'] = 7;
                $indexData['contentID'] = $eventsData[$i]['ID'];
                $indexData['languageID'] = $eventsData[$i]['LanguageID'];
                $indexData['title'] = $eventsData[$i]['Title'];
                $indexData['text'] = '';
                $indexData['link'] = '';
                $indexData['contents'] = $eventsData[$i]['Title'] . " " . $eventsData[$i]['Brief'] . " " . $eventsData[$i]['Text'] . " " . $eventsData[$i]['ImageAlt'];

                Cible_FunctionsIndexation::indexation($indexData);
            }
        }
        /*         * ********************* */

        /*         * ****** GALLERY ******* */
        if (class_exists('Galleries', false))
        {
            $gallerySelect = new Galleries();
            $select = $gallerySelect->select()->setIntegrityCheck(false)
                    ->from('Galleries', array('ID' => 'G_ID', 'CategoryID' => 'G_CategoryID'))
                    ->where('G_Online = 1')
                    ->join('GalleriesIndex', 'GI_GalleryID = G_ID', array('LanguageID' => 'GI_LanguageID', 'Title' => 'GI_Title', 'Description' => 'GI_Description'))
                    ->join('ImagesIndex', 'II_ImageID = G_ImageID', array('ImageTitle' => 'II_Title', 'ImageDescription' => 'II_Description'))
                    ->where('II_LanguageID = GI_LanguageID');
            $galleryData = $gallerySelect->fetchAll($select);

            $cpt = count($galleryData);
            for ($i = 0; $i < $cpt; $i++)
            {
                $indexData['action'] = "add";
                $indexData['pageID'] = $galleryData[$i]['CategoryID'];
                $indexData['moduleID'] = 9;
                $indexData['contentID'] = $galleryData[$i]['ID'];
                $indexData['languageID'] = $galleryData[$i]['LanguageID'];
                $indexData['title'] = $galleryData[$i]['Title'];
                $indexData['text'] = '';
                $indexData['link'] = 'gallery';
                $indexData['contents'] = $galleryData[$i]['Title'] . " " . $galleryData[$i]['Description'] . " " . $galleryData[$i]['ImageTitle'] . " " . $galleryData[$i]['ImageDescription'];


                Cible_FunctionsIndexation::indexation($indexData);

                $imagesSelect = new GalleriesImages();
                $select = $imagesSelect->select()->setIntegrityCheck(false)
                        ->from('Galleries_Images', array('ID' => 'GI_ImageID'))
                        ->where('GI_GalleryID = ?', $galleryData[$i]['ID'])
                        ->where('GI_Online = 1')
                        ->join('ImagesIndex', 'II_ImageID = GI_ImageID', array('LanguageID' => 'II_LanguageID', 'Title' => 'II_Title', 'Description' => 'II_Description'));
                $imagesData = $imagesSelect->fetchAll($select);

                $cptImage = count($imagesData);
                for ($y = 0; $y < $cptImage; $y++)
                {
                    $indexData['action'] = "add";
                    $indexData['pageID'] = $galleryData[$i]['CategoryID'];
                    $indexData['moduleID'] = 9;
                    $indexData['contentID'] = $galleryData[$i]['ID'];
                    $indexData['languageID'] = $imagesData[$y]['LanguageID'];
                    $indexData['title'] = $imagesData[$y]['Title'];
                    $indexData['text'] = '';
                    $indexData['link'] = 'image';
                    $indexData['contents'] = $imagesData[$y]['Title'] . " " . $imagesData[$y]['Description'];

                    Cible_FunctionsIndexation::indexation($indexData);
                }
            }
        }
        /*         * ********************* */

        /*         * ****** NEWSLETTERS ******* */
        if(class_exists('NewsletterRelease', false))
        {
            $newsletterSelect = new NewsletterReleases();
            $select = $newsletterSelect->select()
                    ->from('Newsletter_Releases', array('ID' => 'NR_ID', 'LanguageID' => 'NR_LanguageID', 'Title' => 'NR_Title'))
                    ->where('NR_Online = 1');
            $newsletterData = $newsletterSelect->fetchAll($select)->toArray();

            $cpt = count($newsletterData);
            for ($i = 0; $i < $cpt; $i++)
            {
                $indexData['action'] = "add";
                $indexData['pageID'] = $newsletterData[$i]['ID'];
                $indexData['moduleID'] = 8;
                $indexData['contentID'] = $newsletterData[$i]['ID'];
                $indexData['languageID'] = $newsletterData[$i]['LanguageID'];
                $indexData['title'] = $newsletterData[$i]['Title'];
                $indexData['text'] = '';
                $indexData['link'] = 'release';
                $indexData['contents'] = $newsletterData[$i]['Title'];

                Cible_FunctionsIndexation::indexation($indexData);

                $articlesSelect = new NewsletterArticles();
                $select = $articlesSelect->select()
                        ->from('Newsletter_Articles', array('ID' => 'NA_ID', 'Title' => 'NA_Title', 'Resume' => 'NA_Resume', 'Text' => 'NA_Text'))
                        ->where('NA_ReleaseID = ?', $newsletterData[$i]['ID']);
                $articlesData = $articlesSelect->fetchAll($select);

                $cptArticle = count($articlesData);
                for ($y = 0; $y < $cptArticle; $y++)
                {
                    $indexData['action'] = "add";
                    $indexData['pageID'] = $newsletterData[$i]['ID'];
                    $indexData['moduleID'] = 8;
                    $indexData['contentID'] = $articlesData[$y]['ID'];
                    $indexData['languageID'] = $newsletterData[$i]['LanguageID'];
                    $indexData['title'] = $articlesData[$y]['Title'];
                    $indexData['text'] = '';
                    $indexData['link'] = 'article';
                    $indexData['contents'] = $articlesData[$y]['Title'] . " " . $articlesData[$y]['Resume'] . " " . $articlesData[$y]['Text'];

                    Cible_FunctionsIndexation::indexation($indexData);
                }
            }
        }
        /*         * ********************* */
    }

    public static function indexationDeleteAll()
    {
        $directory = Zend_Registry::get('lucene_index');

        try
        {
            $index = Zend_Search_Lucene::open($directory);
        }
        catch (Zend_Search_Lucene_Exception $e)
        {
            try
            {
                $index = Zend_Search_Lucene::create($directory);
            }
            catch (Zend_Search_Lucene_Exception $e)
            {
                exit(1);
            }
        }

        for ($count = 0; $count < $index->maxDoc(); $count++)
        {
            $index->delete($count);
        }
    }

}
?>
