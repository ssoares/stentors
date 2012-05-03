<?php
/**
 * Years Profile data
 * Management of the Items.
 *
 * @category  Cible
 * @package   Cible_YearsParticipateObject
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: MemberProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */

/**
 * Manages years data.
 *
 * @category  Cible
 * @package   Cible_YearsParticipate
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: MemberProfilesObject.php 826 2012-02-01 04:15:13Z ssoares $id
 */
class YearsParticipateObject extends DataObject
{

    protected $_dataClass   = 'YearsParticipateData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = 'YP_GenericProfileId';
    protected $_distinct;

    public function setDistinct($distinct)
    {
        $this->_distinct = $distinct;
    }

    public function manageData($id, $data)
    {
        $where = $this->_db->quoteInto($this->_foreignKey . '= ?', $id);
        $this->_db->delete($this->_oDataTableName, $where);
        $years = explode(',', $data);

        foreach ($years as $value)
        {
            $tmp = array(
                $this->_foreignKey => $id,
                'YP_Year' => $value
            );

            parent::insert($tmp, 1);
        }
    }

    public function findData($filters = array())
    {
        $data = parent::findData($filters);
        foreach ($data as $value)
        {
            $tmp[] = $value['YP_Year'];
        }

        $data = implode(',', $tmp);

        return $data;
    }

    public function getListForFilter()
    {
        $data = array();
        $select = $this->_db->select()
            ->distinct()
            ->from($this->_oDataTableName, 'YP_Year');

        $list = $this->_db->fetchAll($select);
        foreach ($list as $value)
        {
            $data[$value['YP_Year']] = $value['YP_Year'];
        }

        return $data;
    }
}