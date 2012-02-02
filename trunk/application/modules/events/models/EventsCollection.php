<?php
    class EventsCollection// extends objectsCollection
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
            
            $select->from('EventsData', array('ED_ID', 'ED_ImageSrc'))
                   ->distinct()
                   ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
                   ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID', array())
                   ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
                   ->where('EventsData.ED_ID = ?', $id )
                   ->where('EventsIndex.EI_Status = ?', 1 );           
            $events = $this->_db->fetchAll($select);

            $num_events = count($events);            
            for( $i = 0; $i < $num_events; $i++ ){
                $events[$i]['dates'] = $this->getEventDates( $events[$i]['ED_ID'] );
            }
            
            return $events;
        }
        
        public function getList($limit = null){
            
            $select = $this->_db->select();
            
            /*$select->from('EventsData', array('ED_ID', 'ED_ImageSrc'))
                   ->distinct()
                   ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
                   ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID', array())
                   ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
                   ->where('EventsData.ED_CategoryID = ?', $this->_blockParams[1] )
                   ->where('EventsIndex.EI_Status = ?', 1 )
                   ->where('EventsDateRange.EDR_EndDate >= NOW()' )
                   ->order('EventsDateRange.EDR_StartDate ASC');*/
            $select->from('EventsData')
               ->distinct()
               ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
               ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID')
               ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
               ->where('EventsData.ED_CategoryID = ?', $this->_blockParams[1] )
               ->where('EventsIndex.EI_Status = ?', 1 )
               ->where('EventsDateRange.EDR_EndDate >= NOW()' )
               ->order('EventsDateRange.EDR_StartDate ASC');
                   
            if( $limit )
                   $select->limit($limit);
            
            $events = $this->_db->fetchAll($select);
            
            $num_events = count($events);
            
            for( $i = 0; $i < $num_events; $i++ ){
                $events[$i]['dates'] = $this->getEventDates( $events[$i]['ED_ID'] );
            }
            
            return $events;
        }
        
        public function getOtherEvents($limit = null, $not_ID){
            $select = $this->_db->select();
            
            $select->from('EventsData', array('ED_ID', 'ED_ImageSrc'))
                   ->distinct()
                   ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
                   ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID', array())
                   ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
                   ->where('EventsData.ED_CategoryID = ?', $this->_blockParams[1] )
                   ->where('EventsIndex.EI_Status = ?', 1 )
                   ->where('EventsData.ED_ID <> ?', $not_ID)
                   ->where('EventsDateRange.EDR_EndDate >= NOW()' )
                   ->order('EventsDateRange.EDR_StartDate ASC');
                   
            if( $limit )
                   $select->limit($limit);
            
            $events = $this->_db->fetchAll($select);
            
            $num_events = count($events);
            
            for( $i = 0; $i < $num_events; $i++ ){
                $events[$i]['dates'] = $this->getEventDates( $events[$i]['ED_ID'] );
            }
            
            return $events;
        }
        
        public function getBlockParam($param_name){
            return $this->_blockParams[$param_name];
        }
        
        public function getBlockParams(){
            return $this->_blockParams;
        }
        
        private function getEventDates( $eventID ){
            $select = $this->_db->select();
            
            $select->from('EventsDateRange',array('EDR_StartDate', 'EDR_EndDate'))
                   ->where('EventsDateRange.EDR_EventsDataID = ?', $eventID )
                   ->order('EventsDateRange.EDR_StartDate');
                   
            return $this->_db->fetchAll($select);
        }

        /**
         * Fetch the id of an event according the formatted string from URL.
         *
         * @param string $string
         *
         * @return int Id of the searched event
         */
        public function getIdByName($string){
            $select = $this->_db->select();
            $select->from('EventsIndex','EI_EventsDataID')
                    ->where("EI_ValUrl = ?", $string);
            $id = $this->_db->fetchRow($select);
            return $id['EI_EventsDataID'];
        }

        public function getListYearMonth($Year, $Month, $limit = null){

            $select = $this->_db->select();

           /*$select->from('EventsData', array('ED_ID', 'ED_ImageSrc'))
                   ->distinct()
                   ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
                   ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID', array())
                   ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
                   ->where('EventsData.ED_CategoryID = ?', $this->_blockParams[1] )
                   ->where('EventsIndex.EI_Status = ?', 1 )
                   ->where('Year(EventsDateRange.EDR_StartDate) = ' . intval($Year) )
                   ->where('Month(EventsDateRange.EDR_StartDate) = ' . intval($Month) )
                   ->order('EventsDateRange.EDR_StartDate ASC');*/

            $select->from('EventsData')
                   ->distinct()
                   ->join('EventsIndex','EventsIndex.EI_EventsDataID = EventsData.ED_ID' )
                   ->joinRight('EventsDateRange', 'EventsDateRange.EDR_EventsDataID = EventsData.ED_ID')
                   ->where('EventsIndex.EI_LanguageID = ?', $this->_current_lang )
                   ->where('EventsData.ED_CategoryID = ?', $this->_blockParams[1] )
                   ->where('EventsIndex.EI_Status = ?', 1 )
                   ->where('Year(EventsDateRange.EDR_StartDate) = ' . intval($Year) )
                   ->where('Month(EventsDateRange.EDR_StartDate) = ' . intval($Month) )
                   ->order('EventsDateRange.EDR_StartDate ASC');


           
            if( $limit )
                   $select->limit($limit);

            $events = $this->_db->fetchAll($select);

            $num_events = count($events);

            for( $i = 0; $i < $num_events; $i++ ){
                $events[$i]['dates'] = $this->getEventDates( $events[$i]['ED_ID'] );
            }

            return $events;
        }
            
    }
?>