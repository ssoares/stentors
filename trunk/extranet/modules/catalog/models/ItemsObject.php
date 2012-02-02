<?php
/**
 * Module Catalog
 * Management of the Items.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ItemsObject.php 451 2011-04-13 20:23:56Z ssoares $id
 */

/**
 * Manage data from items table.
 *
 * @category  Extranet_Module
 * @package   Extranet_Module_Catalog
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: ItemsObject.php 451 2011-04-13 20:23:56Z ssoares $id
 */
class ItemsObject extends DataObject
{
    protected $_dataClass   = 'ItemsData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();
    
    protected $_indexClass      = 'ItemsIndex';
//    protected $_indexId         = '';
    protected $_indexLanguageId = 'II_LanguageID';
//    protected $_indexColumns    = array();
    protected $_constraint      = 'I_ProductID';
    protected $_foreignKey      = 'I_ProductID';

    /**
     * Fetch items data for the product and build the rendering.
     *
     * @param int $id     Product id
     * @param int $langId 
     *
     * @return string
     */
    public function getAssociatedItems($id, $langId)
    {
        (string) $html = "";
        $listArray     = array();
        
        $select = $this->getAll($langId, false);

        $select->where($this->_constraint . ' = ?', $id)
            ->order('II_Name');

        $data = $this->_db->fetchAll($select);

        $TITLE = 'Items(associez les items aux produits dans la GESTION DES ITEMS)';
        
        foreach($data as $key => $item)
        {
            $listArray[$key][] = $item['II_Name'];

        }
        $html = Cible_FunctionsGeneral::generateHTMLTable($TITLE, array(array('Title' =>'')), $listArray);
        
        return $html;
    }
}