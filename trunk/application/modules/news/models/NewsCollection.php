<?php
    class NewsCollection// extends objectsCollection
    {
        protected $_db;
        protected $_current_lang;
        protected $_blockID;
        protected $_blockParams;
        
        public function __construct($blockID = null){
            $this->_db = Zend_Registry::get('db');
            $this->_current_lang = Zend_Registry::get('languageID');
            
            if( $blockID ){
                $this->_blockID = $blockID;
                $_params = Cible_FunctionsBlocks::getBlockParameters( $blockID );
                
                foreach( $_params as $param){
                    $this->_blockParams[ $param['P_Number'] ] = $param['P_Value'];
                }    
            } else {
                
                $this->_blockID = null;
                $this->_blockParams = array();   
            }
        }
        
        public function getDetails($id){
            $select = $this->_db->select();
            
            $select->from('NewsData', array('ND_ID', 'ND_Date', 'ND_ReleaseDate'))
                   ->distinct()
                   ->join('NewsIndex','NewsIndex.NI_NewsDataID = NewsData.ND_ID' )
                   ->where('NewsIndex.NI_LanguageID = ?', $this->_current_lang )
                   ->where('NewsData.ND_ID = ?', $id )
                   ->where('NewsIndex.NI_Status = ?', 1 );
            
            $news = $this->_db->fetchAll($select);
            
            return $news;
        }
        
        public function getList($limit = null){
            
            $select = $this->_db->select();
            
            $select->from('NewsData', array('ND_ID', 'ND_Date', 'ND_ReleaseDate'))
                   ->distinct()
                   ->join('NewsIndex','NewsIndex.NI_NewsDataID = NewsData.ND_ID' )
                   ->where('NewsIndex.NI_LanguageID = ?', $this->_current_lang )
                   ->where('NewsData.ND_CategoryID = ?', $this->_blockParams[1] )
                   ->where('NewsIndex.NI_Status = ?', 1 )
                   ->where('NewsData.ND_ReleaseDate <= ?', date('Y-m-d') )
                   ->order( $this->_blockParams[4] );
                   
            if( $limit )
                   $select->limit($limit);
            
            $news = $this->_db->fetchAll($select);
            
            return $news;
        }
        
        public function getOtherNews($limit = null, $not_ID){
            $select = $this->_db->select();
            
            $select->from('NewsData', array('ND_ID', 'ND_Date', 'ND_ReleaseDate'))
                   ->distinct()
                   ->join('NewsIndex','NewsIndex.NI_NewsDataID = NewsData.ND_ID' )
                   ->where('NewsIndex.NI_LanguageID = ?', $this->_current_lang )
                   ->where('NewsData.ND_CategoryID = ?', $this->_blockParams[1] )
                   ->where('NewsIndex.NI_Status = ?', 1 )
                   ->where('NewsData.ND_ID <> ?', $not_ID)
                   ->where('NewsData.ND_ReleaseDate <= ?', date('Y-m-d') )
                   ->order( $this->_blockParams[4] );
                   
            if( $limit )
                   $select->limit($limit);
            
            return $this->_db->fetchAll($select);
        }
        
        public function getBlockParam($param_name){
            return $this->_blockParams[$param_name];
        }
        
        public function getBlockParams(){
            return $this->_blockParams;
        }   
        
        /**
         * Fetch the id of a news according the formatted string from URL.
         *
         * @param string $string
         *
         * @return int Id of the searched news
         */
        public function getIdByName($string){
            $select = $this->_db->select();            
            $select->from('NewsIndex','NI_NewsDataID')                          
                    ->where("NI_ValUrl = ?", $string);            
            $id = $this->_db->fetchRow($select);
            return $id['NI_NewsDataID'];
        }
    }
?>