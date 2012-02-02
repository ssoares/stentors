<?php
/**
 * Module Utilities
 * Management of the references data.
 *
 * @category  Application_Module
 * @package   Application_Module_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReferencesObject.php 435 2011-03-28 03:57:25Z ssoares $id
 */

/**
 * Manage data from references table.
 *
 * @category  Application_Module
 * @package   Application_Module_References
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReferencesObject.php 435 2011-03-28 03:57:25Z ssoares $id
 */
class ReferencesObject extends DataObject
{
    protected $_dataClass   = 'ReferencesData';
    
    protected $_indexClass      = 'ReferencesIndex';
    protected $_indexLanguageId = 'RI_LanguageID';
//    protected $_constraint      = '';
  

    public function referencesCollection($typeRef,$langId)
    {
        (array) $array = array();
        
        $select = $this->getAll($langId, false);
        $select->where('R_TypeRef = ?', $typeRef);
        $products =$this->_db->fetchAll($select);     

        return $products;
    }




}