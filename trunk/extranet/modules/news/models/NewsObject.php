<?php

    class NewsObject extends DataObject
    {
        protected $_dataClass = 'NewsData';
        protected $_dataId = 'ND_ID';
        protected $_dataColumns = array(
            'CategoryID' => 'ND_CategoryID',
            'ReleaseDate' => 'ND_ReleaseDate',
            'Date' => 'ND_Date'
        );
        
        protected $_indexClass = 'NewsIndex';
        protected $_indexId = 'NI_NewsDataID';
        protected $_indexLanguageId = 'NI_LanguageID';
        protected $_indexColumns = array(
            'Title' => 'NI_Title',
            'Brief' => 'NI_Brief',
            'Text' => 'NI_Text',
            'ImageSrc' => 'NI_ImageSrc',
            'ImageAlt' => 'NI_ImageAlt',
            'Status' => 'NI_Status',
            'ValUrl' => 'NI_ValUrl'
        );
    }
    
?>
