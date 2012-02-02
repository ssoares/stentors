<?php
class NewsletterArticles extends Zend_Db_Table
{
    protected $_name = 'Newsletter_Articles';    
    
    public function getArticleIdByName($string){
        $select = $this->_db->select();            
        $select->from('Newsletter_Articles','NA_ID')                          
                ->where("NA_ValUrl = ?", $string);         
       // echo $select;        
        $id = $this->_db->fetchRow($select);
        return $id['NA_ID'];
    }
    public function getNewsletterIdByName($string){
        $select = $this->_db->select();            
        $select->from('Newsletter_Articles','NA_ReleaseID')                          
                ->where("NA_ValUrl = ?", $string);         
       // echo $select;        
        $id = $this->_db->fetchRow($select);
        return $id['NA_ReleaseID'];
    }
}