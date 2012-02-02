<?php
/*****
 * 
 *  Return the string that will be put in the robots.txt
 * 
 ****/

class TextRobots extends DataObject
{
    protected $_dataClass   = 'Text';
    
    protected $_indexClass      = '';
    protected $_indexLanguageId = '';

    public function getXMLFilesString($path = "", $title = "")
    {        
        return "";
    }
    
    public function getXMLFile($path = "", $lang = ""){
         $arrayForXML = array();  
         return $arrayForXML;
     }
}