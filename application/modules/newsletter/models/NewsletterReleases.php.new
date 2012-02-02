<?php
class NewsletterReleases extends Zend_Db_Table
{
    protected $_name = 'Newsletter_Releases';    
    
    public function getNewsletterIdByName($string){
        $select = $this->_db->select();
        $select->from('Newsletter_Releases','NR_ID')
                ->where("NR_ValUrl = ?", $string);

        $id = $this->_db->fetchRow($select);
        return $id['NR_ID'];
    }
}