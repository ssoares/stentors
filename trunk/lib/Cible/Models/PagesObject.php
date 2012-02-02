<?php
/**
 * Pages
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_Pages
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: PagesObject.php 693 2011-10-27 21:55:32Z freynolds $id
 */

/**
 * Manage data from items table.
 *
 * @category  Cible
 * @package   Cible_Pages
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: PagesObject.php 693 2011-10-27 21:55:32Z freynolds $id
 */
class PagesObject extends DataObject
{

    protected $_dataClass   = 'Pages';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = 'PagesIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'PI_LanguageID';
//    protected $_indexColumns    = array();
    protected $_constraint      = 'PI_PageIndex';
    protected $_foreignKey      = '';

    public function pageIdByController($controller)
    {
        $select = $this->getAll(Zend_Registry::get('languageID'), false);

        $select->where($this->_constraint . ' = ?', $controller);

        $data = $this->_db->fetchRow($select);

        return $data;
    }

    public function getParentRelatedID($pageId){
        $select = $this->_db->select()
            ->from('Pages', array('P_ParentID'))
            ->where('P_ID = ?', $pageId)
            ->limit(1);
        $menu = $this->_db->fetchRow($select);

        return $menu['P_ParentID'];

    }

    public function getParentRelatedName($pageId){
        $select = $this->_db->select()
            ->from('PagesIndex', array('PI_PageIndex'))
            ->where('	PI_PageID = ?', $pageId)
            ->limit(1);
        $menu = $this->_db->fetchRow($select);

        return $menu['PI_PageIndex'];

    }



    public function getRelatedMenu($pageId)
    {
        $select = $this->_db->select()
            ->from('MenuItemData', array('MID_ID', 'MID_MenuId', 'MID_ParentID', 'MID_Style'))
            ->joinLeft('MenuItemIndex', 'MID_ID = MII_MenuItemDataID', array())
            ->where('MII_PageID = ?', $pageId)
            ->where('MII_LanguageID = ?', Zend_Registry::get('languageID'))
            ->order('MID_MenuID ASC')
            ->order('MID_ID ASC')
            ->limit(1);
        $menu = $this->_db->fetchRow($select);

        return $menu;
    }
}