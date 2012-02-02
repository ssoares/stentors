<?php
/*****
 * 
 *  Return the string that will be put in the robots.txt
 * 
 ****/

class DefaultRobots extends DataObject
{
     protected $_dataClass   = 'NewsData';
    
    protected $_indexClass      = '';
    protected $_indexLanguageId = '';
    
    protected $_specificAction = array();  // this are the action that need a specific treatment
    
    public function getXMLFilesString($path = "", $title = "")
    {        
        $db = Zend_Registry::get('db');     
        $xmlString = "";
        (array) $array = array();
        
        $select1 = $db->select()
            ->distinct()
            ->from('Languages');
        $langs = $db->fetchAll($select1);        

        foreach ($langs as $lang){            
                $xmlString .= $path . "/" . $title . "/index/site-map/lang/" . $lang['L_ID'] . "\n";            
        }
       // echo  $xmlString;
        return $xmlString;
    }
    
    public function getXMLFile($path = "", $lang = "")
    {        
        $moduleID = 2;
       
        $db = Zend_Registry::get('db');
        $xmlString = "";
        $arrayForXML = array();
        $arrayExcludeViews = array('2002','8003','14001','9002','7002');               
        
        $select2 = $db->select()
            ->distinct()
            ->from(array('pi' => 'PagesIndex'),array('PI_LanguageID','PI_PageID','PI_PageIndex'))
            ->join(array('b' => 'Blocks'),'pi.PI_PageID = b.B_PageID',array('B_ID','B_PageID','B_ModuleID'))
            ->join(array('bi' =>'BlocksIndex'),'b.B_ID = bi.BI_BlockID') 
            ->join(array('p' => 'Pages'), 'pi.PI_PageID = p.P_ID', array('P_ID','P_ShowSiteMap'))
            ->where('bi.BI_LanguageID =?',$lang)
            ->where('pi.PI_LanguageID = ?',$lang)
            ->where('pi.PI_Status=1')
            ->where('p.P_ShowSiteMap!=0')
            ->order('pi.PI_PageID ASC');
        $Rows = $db->fetchAll($select2);
        
        $arrayPageId = array();
       
        foreach ($Rows as $row){
           if (!in_array($row['PI_PageIndex'], $arrayPageId)){
               
               
                $select3 = $db->select()
                    ->distinct()
                    ->from(array('mcvp' => 'ModuleCategoryViewPage'))
                    ->join(array('mv' => 'ModuleViews'),'mcvp.MCVP_ViewID = mv.MV_ID')
                    ->where('mcvp.MCVP_PageID = ?',$row['PI_PageID']);
                $RowsModule = $db->fetchAll($select3);
                
                $addToArray = true;
                foreach ($RowsModule as $rowMod){
                    if(in_array($rowMod['MCVP_ViewID'], $arrayExcludeViews)){
                        $addToArray = false;
                    }
                    
                    
                    
                    //var_dump($rowMod);
                    //  MCVP_ID	MCVP_ModuleID	MCVP_CategoryID	MCVP_ViewID	MCVP_PageID	MV_ID	MV_Name     MV_ModuleID
                    //  2002	2               1               2002            2002            2002	details     2
                    
                }
                
                
                if($addToArray==true){
                    //echo $select3 . "<br />";
                    array_push($arrayPageId,$row['PI_PageIndex']);
                }
            }
            //var_dump($row);
            
        }
        foreach($arrayPageId as $pageId){
            $arrayURL = array();  
            array_push($arrayURL, $path . "/" . $pageId);
            array_push($arrayURL,"0.5");
            array_push($arrayURL,"weekly");
            array_push($arrayURL, date("Y-m-d H:m:s"));
            array_push($arrayForXML,$arrayURL);
        }
        
        return $arrayForXML;
        //echo $select2;
        //var_dump($arrayPageId);
        
        
        
        /*
        $action_details = "details";        
        $moduleID = 2;
        
        $db = Zend_Registry::get('db');     
        $xmlString = "";
        $arrayForXML = array();  
                
        $select2 = $db->select()
                ->distinct()
                ->from('Blocks')
                ->join('BlocksIndex', 'BlocksIndex.BI_BlockID = Blocks.B_ID')
                ->join('ModuleCategoryViewPage','ModuleCategoryViewPage.MCVP_PageID = Blocks.B_PageID')
                ->join('ModuleViews','ModuleViews.MV_ID = ModuleCategoryViewPage.MCVP_ID')
                ->join('PagesIndex','PagesIndex.PI_PageID = ModuleCategoryViewPage.MCVP_PageID')
                ->where('Blocks.B_ModuleID = ?', $moduleID)
                ->where('PagesIndex.PI_LanguageID = ?',$lang)
                ->where('ModuleCategoryViewPage.MCVP_ModuleID = ?', $moduleID)
                ->where('Blocks.B_Online = 1')
                ->where('BlocksIndex.BI_LanguageID =?',$lang)
                ->order('Blocks.B_Position ASC');
        $Rows = $db->fetchAll($select2);
       
        //echo $select2;
        
        foreach ($Rows as $row){
            if (in_array($row['MV_Name'], $this->_specificAction)){
                $select3 = $db->select()
                    ->distinct()
                    ->from('NewsIndex')
                    ->join('NewsData', 'NewsIndex.NI_NewsDataID = NewsData.ND_ID')
                    ->where('NewsIndex.NI_Status = 1')
                    ->where('NewsIndex.NI_LanguageID =?',$lang)
                    ->order('NewsData.ND_ReleaseDate DESC');
                
                $NewsRows = $db->fetchAll($select3);    
                foreach ($NewsRows as $NewsRow){
                    $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $row['MCVP_CategoryID'], 'details', $moduleID, $lang);
                    $arrayURL = array();  
                    array_push($arrayURL, $path . "/" . $details_page . "/" . $NewsRow['ND_ReleaseDate'] . "/" . $NewsRow['NI_ValUrl']);
                    array_push($arrayURL,"0.5");
                    array_push($arrayURL,"weekly");
                    array_push($arrayURL, date("Y-m-d H:m:s"));
                    array_push($arrayForXML,$arrayURL);
                }
            }
            else{
                $arrayURL = array();  
                array_push($arrayURL, $path . "/" . $row['PI_PageIndex']);
                array_push($arrayURL,"0.5");
                array_push($arrayURL,"weekly");
                array_push($arrayURL,date("Y-m-d H:m:s"));
                array_push($arrayForXML,$arrayURL);
            }
                         
        }     
        
        
        return $arrayForXML;*/
    }
}