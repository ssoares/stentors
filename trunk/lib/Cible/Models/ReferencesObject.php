<?php
/**
 * Module Utilities
 * Management of the references data.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReferencesObject.php 582 2011-08-29 17:21:09Z ssoares $id
 */

/**
 * Manage data from references table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_References
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReferencesObject.php 582 2011-08-29 17:21:09Z ssoares $id
 */
class ReferencesObject extends DataObject
{
    protected $_dataClass   = 'ReferencesData';

    protected $_indexClass      = 'ReferencesIndex';
    protected $_indexLanguageId = 'RI_LanguageID';
//    protected $_constraint      = '';

    function getValueById($id)
    {
        $select = parent::getAll(null, false, $id);

        $result = $this->_db->fetchRow($select);

        return array('reason' => $result['R_TypeRef'], 'value' => $result['RI_Value']);
    }

    function getRefByType($type)
    {
        $select = parent::getAll(null, false);
        $select->where('R_TypeRef = ?', $type);

        $result = $this->_db->fetchAll($select);

        return $result;
    }
}