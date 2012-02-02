<?php
/*****
 * 
 *  Return the string that will be put in the robots.txt
 * 
 ****/
class BannersRobots extends DataObject
{
    protected $_dataClass   = 'NewsData';
    
    protected $_indexClass      = '';
    protected $_indexLanguageId = '';
    
    protected $_specificAction = array();  // this are the action that need a specific treatment
    
    public function getXMLFilesString($path = "", $title = "")
    {        
        return "";
    }
    
     public function getXMLFile($path = "", $lang = ""){
         $arrayForXML = array();  
         return $arrayForXML;
     }
}