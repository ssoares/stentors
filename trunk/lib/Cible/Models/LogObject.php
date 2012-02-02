<?php
/**
 * Log
 * Management of activities for each module.
 *
 * @category  Cible
 * @package   Cible_Models
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: LogObject.php 662 2011-10-12 03:18:37Z ssoares $
 */

/**
 * Manage data from log tables.
 *
 * @category  Cible
 * @package   Cible_Models
 * @copyright Copyright (c)2010 Cibles solutions d'affaires
 *            http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: LogObject.php 662 2011-10-12 03:18:37Z ssoares $
 */
class LogObject extends DataObject
{

    protected $_dataClass   = 'LogData';
//    protected $_dataId      = '';
//    protected $_dataColumns = array();

    protected $_indexClass      = '';
//    protected $_indexId         = '';
    protected $_indexLanguageId = '';
//    protected $_indexColumns    = array();
    protected $_constraint      = '';
    protected $_foreignKey      = '';

    protected $pairSepar = '|';
    protected $separator = '||';
    protected $_moduleId = 0;

    /**
     * Set the module id which we look for data.
     *
     * @param type $_moduleId Id of the module
     *
     * @return void
     */
    public function setModuleId($_moduleId)
    {
        $this->_moduleId = $_moduleId;
    }


    /**
     * Insert Data into the log table.
     *
     * @param array $data Data to insert int he table
     *
     * @return void
     */
    public function writeData(array $data = array())
    {
        if (is_array($data['L_Data']))
            $data['L_Data'] = $this->_toStringData($data['L_Data']);

        $isValid = $this->_isCorrectFormat($data['L_Data']);
        if ($isValid)
            $this->insert($data, 1);
        else
            throw new Exception("Wrong format: please check the values for the L_Data field");
    }
    /**
     * Tests if data are already recorded in the log table.
     * According the result, it allows to define if data will be inseted.
     *
     * @param array $data Data to insert in the table.
     *
     * @return boolean
     */
    public function findRecords(array $data = array())
    {
        $exist = (bool) false;

        $result = 0;
        if ($data['L_UserID'] > 0)
        {
            $select = $this->_db->select()
                ->from($this->_oDataTableName, 'count(*)')
                ->where('L_ModuleID = ?', $data['L_ModuleID'])
                ->where('L_UserID = ?', $data['L_UserID'])
                ->where('L_Action = ?', $data['L_Action'])
                ->where('L_Data = ?', $data['L_Data'])        ;

            $result = $this->_db->fetchOne($select);
        }

        if ($result > 0 )
            $exist = true;

        return $exist;

    }

    /**
     * @see DataObject::getAll()
     *
     * @param type $langId
     * @param type $array
     * @param type $id
     *
     * @return Zend_Db_Select
     */
    public function getAll($langId = null, $array = true, $id = null)
    {
        $select = parent::getAll($langId, $array, $id);

        if ($select instanceof Zend_Db_Select && $this->_moduleId > 0)
            $select->where('L_ModuleID = ?', $this->_moduleId);

        return $select;
    }

    /**
     * Transforms an array into a string for the log informations.
     *
     * @param array $data An associative array with the values to log.
     *
     * @return string
     */
    protected function _toStringData(array $data = array())
    {
        $string = "";

        if (count($data) > 0)
            foreach ($data as $key => $value)
                $string .= $key . $this->pairSepar . $value . $this->separator;
        else
            throw new Exception('Empty array : no data to build information to insert in the log');

        return $string;
    }

    /**
     * Tests if the string containing log informations is correctly formated. <br>
     * In the pair <b>param|value</b> param must be a string and value can't be empty.<br>
     * The string must finish with the defined separator (default = ||)
     *
     * @param string $string Informatins to insert in the log.
     *
     * @return boolean
     */
    protected function _isCorrectFormat($string = "")
    {
        (bool) $valid = false;

        $lastChar = substr($string, -2);
        if ($lastChar == $this->separator)
            $hasLast = true;

        $tmpPairs = $this->explodeData($string);
        $pairs    = $this->getDataPairs($tmpPairs);

        foreach ($pairs as $key => $value)
        {
            $keyIsString = is_string($key);
            $notEmpty    = empty($value);
            if (!$keyIsString && $notEmpty)
                break;
        }

        if($hasLast && $keyIsString && !$notEmpty)
            $valid = true;

        return $valid;
    }

    public function explodeData($string)
    {
        $tmpPairs = array();

        $tmpPairs = explode($this->separator, $string);

        return $tmpPairs;
    }

    /**
     * Creates an array with the parameters from the log table.
     * Explodes the string (or array) into pairs of param => value.
     * 
     * @param string|array $pairs The parameters saved in the log data column
     * @return array
     */
    public function getDataPairs($pairs)
    {
        $data = array();

        if (is_string($pairs))
            $pairs = $this->explodeData ($pairs);

        foreach ($pairs as $value)
        {
            $tmpVal = explode($this->pairSepar, $value);

            if (strlen($tmpVal[0]) > 0)
                $data[$tmpVal[0]] = $tmpVal[1];
        }

        return $data;
    }
}