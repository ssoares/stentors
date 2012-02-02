<?php

class CitiesData extends Zend_Db_Table
{
    protected $_name = 'Cities';
    protected $_filter = false;

    public function setFilter ($value)
    {
        $this->_filter = $value;
    }

    public function getCitiesDataByStates($stateId)
    {
        $select = $this->_db->select()
                    ->from(array('city'=> $this->_name))
                    ->distinct()
                    ->joinLeft('States', 'C_StateID = S_ID', array())
                    ->order('C_Name');

        if ($this->_filter)
        {
            $select->distinct();
            $select->joinLeft('AddressData', 'A_CityId = C_ID', '');
            $select->joinLeft('RetailersData', 'R_AddressId = A_AddressId', '');
            $select->where('R_Status= 2');
        }

        if (!is_numeric($stateId))
            $select->where('S_Identifier = ?', $stateId);
        elseif((int) $stateId)
            $select->where('S_ID = ?', $stateId);

        $cities = $this->_db->fetchAll($select);

        return $cities;
    }
}
