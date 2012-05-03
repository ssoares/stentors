<?php
/**
 * Reports data
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReportsObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Manages Reports data.
 *
 * @category  Cible
 * @package   Cible_Utilities
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ReportsObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class ReportsObject extends DataObject
{

    protected $_dataClass   = 'ReportsData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = '';
    protected $_formDataName    = '';
    protected $_addressField    = '';

    public function insert($data, $langId)
    {
        $data['RE_DateCrea'] = date('Y-m-d H-i-s', time());
        parent::insert($data, $langId);
    }

    public function save($id, $data, $langId)
    {
        parent::save($id, $data, $langId);
    }

}