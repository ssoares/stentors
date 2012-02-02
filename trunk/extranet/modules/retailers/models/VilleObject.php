<?php
/**
 * Cible Solutions - Vêtements SP
 * Retailer management. Data import.
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 * @version   $Id: VilleObject.php 824 2012-02-01 01:21:12Z ssoares $
 */

/**
 * Manage data for cities
 *
 * @category  Extranet_Modules
 * @package   Extranet_Modules_Retailer
 * @copyright Copyright (c) Cibles solutions d'affaires. (http://www.ciblesolutions.com)
 */
class VilleObject extends DataObject
{
    protected $_dataClass   = 'VilleData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
    protected $_indexId         = '';
    protected $_indexLanguageId = '';
    protected $_indexColumns    = array();

    /**
     * Get cities list for th selected state.
     *
     * @param int $stateId
     *
     * @return void
     */
    public function getCitiesDataByStates($stateId)
    {
        $select = $this->_db->select()
                    ->from($this->_oDataTableName)
                    ->joinLeft('States', 'C_StateID = S_ID')
                    ->order('C_Name');

        if (!is_numeric($stateId))
            $select->where('S_Identifier = ?', $stateId);
        elseif((int) $stateId)
            $select->where('S_ID = ?', $stateId);

        $cities = $this->_db->fetchAll($select);

        return $cities;
    }
}