<?php

    class EventsObject extends DataObject
    {
        protected $_dataClass = 'EventsData';
        protected $_dataId = 'ED_ID';
        protected $_dataColumns = array(
            'CategoryID' => 'ED_CategoryID',
            'ImageSrc' => 'ED_ImageSrc'
        );
         
        protected $_indexClass = 'EventsIndex';
        protected $_indexId = 'EI_EventsDataID';
        protected $_indexLanguageId = 'EI_LanguageID';
        protected $_indexColumns = array(
            'Title' => 'EI_Title',
            'Brief' => 'EI_Brief',
            'Text' => 'EI_Text',
            'ImageAlt' => 'EI_ImageAlt',
            'Status' => 'EI_Status',
            'ValUrl' => 'EI_ValUrl'
        );
        
        public function insert($data, $langId){
            $id = parent::insert($data, $langId);
            
            if( is_array( $data['DateRange'] ) ){
            
                $dateRangeObject = new EventsDateRange();
                $dateRangeObject->delete( $this->_db->quoteInto('EDR_EventsDataID = ?', $id) );
                
                foreach( $data['DateRange'] as $_range ){
                    if( !empty( $_range['from'] ) ){
                        
                        $_range['to'] = !empty( $_range['to'] ) ? $_range['to'] : $_range['from'];
                        
                        $dateRangeObject->insert(array(
                            'EDR_EventsDataID' => $id,
                            'EDR_StartDate' => $_range['from'],
                            'EDR_EndDate' => $_range['to'],
                        ));
                    }
                }
                
            }
            return $id;   
        }
        
        public function save($id, $data, $langId){
            parent::save($id, $data, $langId);
            
            if( is_array( $data['DateRange'] ) ){
            
                $dateRangeObject = new EventsDateRange();
                $dateRangeObject->delete( $this->_db->quoteInto('EDR_EventsDataID = ?', $id) );
                
                foreach( $data['DateRange'] as $_range ){
                    if( !empty( $_range['from'] ) ){
                        
                        $_range['to'] = !empty( $_range['to'] ) ? $_range['to'] : $_range['from'];
                        
                        $dateRangeObject->insert(array(
                            'EDR_EventsDataID' => $id,
                            'EDR_StartDate' => $_range['from'],
                            'EDR_EndDate' => $_range['to'],
                        ));
                    }
                }
                
            }   
        }
        
        
        
        public function populate($id, $langId){
            $object = parent::populate($id, $langId);
            
            if( empty($object['DateRange']) || !is_array($object['DateRange']) )
                $object['DateRange'] = array();
            
            $dateRangeObject = new EventsDateRange();
            $_select = $dateRangeObject->select();
            
            $_select->where( $this->_db->quoteInto('EDR_EventsDataID = ?', $id) );
            
            $ranges = $dateRangeObject->fetchAll( $_select );
            
            foreach($ranges as $_range){
                array_push($object['DateRange'], array('from' => $_range['EDR_StartDate'], 'to' => $_range['EDR_EndDate']));
            }
            
            return $object;
        }
    }
    
?>
