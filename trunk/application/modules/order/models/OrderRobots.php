<?php
/*****
 * 
 *  Return the string that will be put in the robots.txt
 * 
 ****/

class OrderRobots extends DataObject
{
    protected $_dataClass   = 'NewsData';
    
    protected $_indexClass      = '';
    protected $_indexLanguageId = '';

    public function getXMLFilesString($path = "", $title = "")
    {
        return "";
    }
    
    public function getXMLFile($path = "", $lang = "")
    {
        $arrayForXML = array();
        return $arrayForXML;
    }
}