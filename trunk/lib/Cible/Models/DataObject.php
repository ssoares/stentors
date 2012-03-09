<?php
/**
 * Cible solution d'affaires: Edith Framework
 *
 * @category   Cible
 * @package    Cible_Models
 * @copyright  Copyright (c) 2010 Cible (http://www.ciblesolutions.com)
 * @version    $Id: DataObject.php 826 2012-02-01 04:15:13Z ssoares $
 */

/**
 * <b>Class for data management.<b>
 * <p>
 * This class contains methods that allow to manipulate data according to
 * the object.
 * Tables, from database, are defined in two separates classes (for data and
 * translation if needed). This class build object to process data.
 * </p>
 *
 * @category   Cible
 * @package    Cible_Models
 * @copyright  Copyright (c) 2010 Cible (http://www.ciblesolutions.com)
 */
class DataObject
{
    protected $i = 0;
    protected $j = 2;

    protected $_db;
    protected $_oData;
    protected $_oDataTableName;
    protected $_oIndex;
    protected $_oIndexTableName;
    protected $_schema     = '';
    protected $_dataClass  = '';
    protected $_dataId     = '';
    protected $_indexId    = '';
    protected $_constraint = '';
    protected $_foreignKey = '';
    protected $_indexClass = '';

    protected $_delimiter       = ";";
    protected $_fieldToEncrypt  = "";
    protected $_indexLanguageId = '';
    protected $_fileWithHeader  = true;
    protected $_excludeTables   = array();
    protected $_dataColumns     = array();
    protected $_indexColumns    = array();
    protected $_searchColumns   = array();

    protected $_indexSelectColumns = array();
    protected $_orderBy = "";
    protected $_position = "";
    protected $_query;
    protected $_columns = array();
    protected $_colsData;
    protected $_colsIndex;
    protected $_enum;
    protected $_filters;

    /**
     * Set a query instance to join with data table
     *
     * @param type $_query Zend_Db_Select
     */
    public function setQuery(Zend_Db_Select $_query)
    {
        $this->_query = $_query;
    }
    /**
     * Set filers to get data.
     *
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->_filters = $filters;
    }

    /**
     * Getter for the data table name
     *
     * @return string
     */
    public function getDataTableName()
    {
        return $this->_oDataTableName;
    }

    /**
     * Getter for the foreign key. Column relating an other table
     *
     * @return string
     */
    public function getForeignKey()
    {
        if(isset($this->_foreignKey)){
        return $this->_foreignKey;
    }
        else{
            return '';
        }
    }


    /**
     * Getter for the constraint. Column relating an other table
     *
     * @return string
     */
    public function getConstraint()
    {
        return $this->_constraint;
    }

    /**
     * Getter for the index table name
     *
     * @return string
     */
    public function getIndexTableName()
    {
        return $this->_oIndexTableName;
    }

    /**
     * Getter for $_dataId
     *
     * @return string name of the column for primary index
     */
    public function getDataId()
    {
        return $this->_dataId;
    }

    /**
     * Getter for $_indexId
     *
     * @return string name of the column for primary index
     */
    public function getIndexId()
    {
        return $this->_indexId;
    }

    /**
     * Getter for the columns utilzed for the select clause when we need
     * specifics data
     *
     * @return array
     */
    public function getIndexSelectColumns()
    {
        return $this->_indexSelectColumns;
    }

    /**
     * Getter for the data columns.
     *
     * @return array
     */
    public function getDataColumns()
    {
        return $this->_dataColumns;
    }

    /**
     * Getter for the language id column.
     *
     * @return array
     */
    public function getIndexLanguageId()
    {
        return $this->_indexLanguageId;
    }
    /**
     * Getter for the schema of the data table.
     *
     * @return array
     */
    public function getColsData()
    {
        return $this->_colsData;
    }
    /**
     * Getter for the schema of the index table.
     *
     * @return array
     */
    public function getColsIndex()
    {
        return $this->_colsIndex;
    }
    /**
     * Getter for the schema of the index table.
     *
     * @return array
     */
    public function getEnum($field = "")
    {
        $tmpData = $this->_colsData[$field]['DATA_TYPE'];
        $cleanString = preg_replace('/[-()\']/', '', $tmpData);
        $cleanString = preg_replace('/enum/', '', $cleanString);

        $enum = explode(',', $cleanString);

        return $enum;
    }

    /**
     * Getter for the index columns.
     *
     * @return array
     */
    public function getIndexColumns()
    {
        return $this->_indexColumns;
    }

    /**
     * Set the list of tables already added in the join clause for the search
     * engine.<br />
     * This is usefull when part of query is built before the addition of the
     * keywords used for search.
     *
     * @param array $excludeTables Array of the tables to exclude in the query.
     *                             No need to set the keys, must contain only
     *                             values.
     *
     * @return void
     */
    public function setExcludeTables($excludeTables)
    {
        $this->_excludeTables = $excludeTables;
    }

    /**
     * Set the field to order the query results.<br />
     * The value is a string that contains the field name and ASC or DESC.
     *
     * @param string $orderBy The field and the direction. i.e: FIELD ASC
     *
     * @return void
     */
    public function setOrderBy($orderBy)
    {
        $this->_orderBy = $orderBy;
    }

    /**
     * Class constructor
     *
     * @param mixed $options parameters to set
     */
    public function __construct($options = null)
    {
        $this->_db = Zend_Registry::get('db');
        $dbConfig = $this->_db->getConfig();

        if (!empty($this->_dataClass))
        {
            $this->_oData = new $this->_dataClass();
            $this->_oDataTableName = $this->_oData->info('name');
        }
        if (!empty($this->_indexClass))
        {
            $this->_oIndex = new $this->_indexClass();
            $this->_oIndexTableName = $this->_oIndex->info('name');
        }

        $this->_schema = $dbConfig['dbname'];

        $this->_colsData = $this->_oData->info(Zend_Db_Table_Abstract::METADATA);
        $dataId   = $this->_oData->info(Zend_Db_Table_Abstract::PRIMARY);
        $this->_dataId  = current($dataId);

        if($this->_oIndex)
        {
            $this->_colsIndex = $this->_oIndex->info(Zend_Db_Table_Abstract::METADATA);
            $indexId   = $this->_oIndex->info(Zend_Db_Table_Abstract::PRIMARY);

            $this->_indexId = current($indexId);
        }
        if(count($this->_dataColumns) == 0)
            $this->_dataColumns = array_combine(
                            array_keys($this->_colsData),
                            array_keys($this->_colsData));

        if(count($this->_indexColumns) == 0 && $this->_oIndex)
            $this->_indexColumns = array_combine(
                            array_keys($this->_colsIndex),
                            array_keys($this->_colsIndex));
    }

    protected function cleanup($data)
    {

        $tmp = array();

        foreach ($data as $_key => $_val)
        {
            if (isset($this->_indexColumns[$_key]) || isset($this->_dataColumns[$_key]))
                $tmp[$_key] = $_val;
        }

        return $tmp;
    }

    public function populate($id, $langId)
    {
        if (empty($id))
            Throw new Exception('Parameter id is empty.');

        if (empty($langId))
            Throw new Exception('Parameter langId is empty.');

        // If both dataClass and indexClass are set, we query with a join clause
        if (!empty($this->_dataClass) && !empty($this->_indexClass))
        {
            $_objectData = new $this->_dataClass();
            $_objectDataTableName = $_objectData->info('name');

            $_objectIndex = new $this->_indexClass();
            $_objectIndexTableName = $_objectIndex->info('name');

            $select = $_objectData->select()
                            ->from($_objectDataTableName)
                            ->setIntegrityCheck(false)
                            ->join($_objectIndexTableName, "$_objectDataTableName.{$this->_dataId} = $_objectIndexTableName.{$this->_indexId}")
                            ->where("{$this->_dataId} = ?", $id)
                            ->where("{$this->_indexLanguageId} = ?", $langId);

            $_row = $_objectData->fetchRow($select);
            /*
              $_object = new $this->_dataClass();
              $select = $_object->select()
              ->from($this->_dataClass)
              ->setIntegrityCheck(false)
              ->join($this->_indexClass, "{$this->_dataClass}.{$this->_dataId} = {$this->_indexClass}.{$this->_indexId}")
              ->where("{$this->_dataId} = ?", $id)
              ->where("{$this->_indexLanguageId} = ?", $langId);

              $_row = $_object->fetchRow($select);
             */
        }

        $tmp = array();
        $object = array();

        // If $_row is empty, there are 2 possibilities
        // 1 - $_row is empty since there is an entry in dataClass and not in indexClass
        // 2 - only dataClass is defined, so we need to query only dataClass
        if (empty($_row) || empty($this->_indexClass))
        {
            $_objectData = new $this->_dataClass();
            $_objectDataTableName = $_objectData->info('name');

            $select = $_objectData->select()
                            ->from($_objectDataTableName)
                            ->where("{$this->_dataId} = ?", $id);

            $_row = $_objectData->fetchRow($select);
            /*
              $select = $_object->select()
              ->from($this->_dataClass)
              ->where("{$this->_dataId} = ?", $id);

              $_row = $_object->fetchRow($select);
             */
        }

        // if row is still empty, it means that nothing as been found for that id
        if (empty($_row))
            return $tmp;

        $tmp = $_row->toArray();

        foreach ($this->_dataColumns as $_key => $_val)
        {
            if (isset($tmp[$_val]))
                $object[$_key] = $tmp[$_val];
        }

        foreach ($this->_indexColumns as $_key => $_val)
        {
            if (isset($tmp[$_val]))
                $object[$_key] = $tmp[$_val];
        }

        return $object;
    }

    public function insert($data, $langId)
    {
        if (empty($data))
            Throw new Exception('Parameter data is empty.');

        if (empty($langId))
            Throw new Exception('Parameter langId is empty.');

        // Creates the data entry in the ObjectData Table
        $data_object = new $this->_dataClass();

        unset($this->_indexColumns[$this->_indexLanguageId]);

        if (!isset($data[$this->_dataId]))
            $data[$this->_dataId] = 0;

        $found = $this->recordExists($data, $langId);

        if (!$found)
        {
            $_row = $data_object->createRow();
            if(!array_key_exists($this->_dataId, $data) || $data[$this->_dataId] === 0)
            unset($this->_dataColumns[$this->_dataId]);

            foreach ($this->_dataColumns as $_key => $_val)
            {
                if (isset($data[$_key]))
                    $_row->$_val = $data[$_key];
            }

            $_row->save();

            $_dataId = $this->_dataId;
            $_insertedId = $_row->$_dataId;
        }
        else
            $_insertedId = $data[$this->_dataId];


        //var_dump($this->_indexClass);
        if (!empty($this->_indexClass))
        {
            unset($this->_indexColumns[$this->_indexId]);
            $_indexId = $this->_indexId;
            $_indexLanguageId = $this->_indexLanguageId;

            // Creates the index entry in the ObjectIndex Table
            $_index_object = new $this->_indexClass();
            $_row = $_index_object->createRow();
            $_row->$_indexId = $_insertedId;
            $_row->$_indexLanguageId = $langId;

            foreach ($this->_indexColumns as $_key => $_val)
            {
                // Very specific code for data import when it's members data
                if ($_key == $this->_fieldToEncrypt || $_val == $this->_fieldToEncrypt)
                    $data[$_key] = md5($data[$_key]);

                if (isset($data[$_key]))
                    $_row->$_val = $data[$_key];
            }

            $_row->save();
        }

        return $_insertedId;
    }

    public function save($id, $data, $langId)
    {
        if (empty($id))
            Throw new Exception('Parameter id is empty.');

        if (empty($data))
            Throw new Exception('Parameter data is empty.');

        if (empty($langId))
            Throw new Exception('Parameter langId is empty.');

        $db = $this->_db;
        $saved = false;
        $data_object = array();

        foreach ($this->_dataColumns as $_key => $_val)
        {
            if (isset($data[$_key]))
                $data_object[$_val] = $data[$_key];
        }

        if (!empty($data_object))
        {
            $_objectData          = new $this->_dataClass();
            $_objectDataTableName = $_objectData->info('name');

            if ($this->_constraint && isset($data_object[$this->_constraint]))
            {
                $where = $db->quoteInto("{$this->_dataId} = ?", $id);
                $where .= ' AND ' . $db->quoteInto("{$this->_constraint} = ?", trim($data_object[$this->_constraint]));
            }
            else
                $where = $db->quoteInto("{$this->_dataId} = ?", $id);

            $db->update($_objectDataTableName, $data_object, $where);
            $saved = true;
        }
        $index_object = array();

        foreach ($this->_indexColumns as $_key => $_val)
        {
            if (isset($data[$_key]))
            {
                // Very specific code for data import when it's members data
                if ($_key == $this->_fieldToEncrypt || $_val == $this->_fieldToEncrypt)
                    $data[$_key] = md5($data[$_key]);

                $index_object[$_val] = $data[$_key];
            }
        }

        if (!empty($index_object))
        {
            $_objectIndex = new $this->_indexClass();
            $_objectIndexTableName = $_objectIndex->info('name');

            if (!in_array($this->_indexId, array_keys($index_object)))
                $index_object[$this->_indexId] = $id;

            $found = $this->recordExists($index_object, $langId, FALSE);
            //$found = $db->fetchCol("SELECT true FROM {$this->_indexClass} WHERE {$this->_indexId} = '$id' AND {$this->_indexLanguageId} = '$langId'");

            if ($found)
            {
                $where = array();

                $where[] = $db->quoteInto("{$this->_indexId} = ?", $id);
                $where[] = $db->quoteInto("{$this->_indexLanguageId} = ?", $langId);

                $n = $db->update($_objectIndexTableName, $index_object, $where);
                //$n = $db->update($this->_indexClass, $index_object, $where);
            }
            else
            {

                $index_object[$this->_indexId] = $id;
                $index_object[$this->_indexLanguageId] = $langId;

                $db->insert($_objectIndexTableName, $index_object);
                //$db->insert($this->_indexClass, $index_object);
            }

            $saved = true;
        }

        return $saved;
    }

    public function delete($id)
    {
        if (empty($id))
            Throw new Exception('Parameter id is empty.');

        $db = $this->_db;

        $_objectData = new $this->_dataClass();
        $_objectDataTableName = $_objectData->info('name');
        $db->delete($_objectDataTableName, $db->quoteInto("{$this->_dataId} = ?", $id));

        if (!empty($this->_indexClass))
        {
            $_objectIndex = new $this->_indexClass();
            $_objectIndexTableName = $_objectIndex->info('name');
            $db->delete($_objectIndexTableName, $db->quoteInto("{$this->_indexId} = ?", $id));
        }
    }

    /**
     * Set the section id and the language id.
     *
     * @param array  $data Array with received data
     * @return array $tmp  Filtered data for
     */
    public function getInitialData($data)
    {
        $tmp = array();

        foreach ($data as $_key => $_val)
        {
            switch ($_key)
            {
                case $this->_dataId:
                    $tmp['id'] = $_val;
                    break;
                case $this->_indexId:
                    $tmp['id'] = $_val;
                    break;
                case $this->_indexLanguageId:
                    $tmp['lang'] = $_val;
                    break;
                default:
            }
        }

        return $tmp;
    }

    /**
     * Get the whole data for this object.
     * Accordind to $array parameter, it will return an array or an instance of
     * Zend_Db_Select.
     *
     * @param int  $langId Id of the language
     * @param bool $array  Default = true. Define the type of data to return.
     * @param int  $id     Element id.
     *
     * @return array|Zend_Db_Select instance
     */
    public function getAll($langId = null, $array = true, $id = null)
    {
        $typeData = array();

        $dataTableName = $this->_oDataTableName;

        $select = $this->_oData->select()
                        ->from($dataTableName)
                        ->setIntegrityCheck(false);

        if (!empty($this->_indexClass))
        {
            $indexTableName = $this->_oIndexTableName;

            if (isset($this->_indexColumns[0]))
                $columns = $this->_indexColumns[0];
            else
                $columns = $this->_indexColumns;

            $select->joinLeft(
                    array($indexTableName => $indexTableName),
                    "{$dataTableName}.{$this->_dataId} = {$indexTableName}.{$this->_indexId}",
                    $columns);


            if (!is_null($langId))
            {
                $select->where("{$this->_indexLanguageId} = ?", $langId);
            }
            else
            {
                if (isset($this->_indexColumns[0]))
                {
                    unset($this->_indexColumns[0]);
                    $i = 2;
                    foreach ($this->_indexColumns as $cols)
                    {
                        $select->joinLeft(
                                array('T' . $i => $indexTableName),
                                "(T{$i}.{$this->_indexId} = {$indexTableName}.{$this->_indexId} AND T{$i}.{$this->_indexLanguageId} != {$indexTableName}.{$this->_indexLanguageId})",
                                $cols);
                        $select->group("{$indexTableName}." . $this->_indexId);
                        $i++;
                    }
                }
            }
        }

        if (!empty($this->_filters))
        {
            foreach ($this->_filters as $key => $value)
            {
                if (isset($this->_dataColumns[$key]))
                {
                    if (is_string($value ))
                        $select->where("{$this->_dataColumns[$key]} like '%{$value}%'");
                    elseif (is_integer($value))
                        $select->where($this->_dataColumns[$key] . ' = ?',$value);
                }
            }
        }

        if (!empty($this->_orderBy))
            $select->order($this->_orderBy);

        if (!is_null($id))
            $select->where("{$this->_dataId} = ?", $id);

        if ($array)
            $typeData = $this->_oData->fetchAll($select)->toArray();
        else
            $typeData = $select;

        return $typeData;
    }

    /**
     * Prepare data received from files and select
     * the action to do: insert or update.
     *
     * @param array   $data       Lines from the cvs files.
     * @param array   $tmpArray   $data already splitted. Data have partially
     *                            been formatted. Specific process needed.
     * @param boolean $isCombined If some process has already been done.
     *
     * @return int $nbLines Numbers of line processed.
     */
    public function processImport($data, $tmpArray = array(), $isCombined = false)
    {
        $update = 0;
        $insert = 0;
        $exist = false;
        $nbLines = array(
            'updated' => $update,
            'inserted' => $insert,
        );
        $langId = Zend_Registry::get('currentEditLanguage');

        if (!$isCombined)
        {
            //Nb of languages managed.
            $langs = Cible_FunctionsGeneral::getAllLanguage();
            // Find the columns name and fill an array.
            if ($this->_fileWithHeader)
            {
                $tmpList = explode($this->_delimiter, trim($data[0]));
                unset($data[0]);
                foreach ($tmpList as $string)
                {
                    $columnsList[] = trim($string);
                }
            }

            foreach ($data as $line)
            {
                $line = trim($line);
                //Clean last delimiter if it ends the line
                $lastChar = substr($line, -1);
                if($lastChar == $this->_delimiter)
                    $line = substr($line, 0,-1);

                // Split each line of the file
                $splitArray2 = $splitArray = explode($this->_delimiter, $line);
                // Test if the line is empty
                $notEmptyLine = strlen($line) > 0 ? true : false;

                // Set the name of the column data for each record
                if (!empty($this->_indexClass) && $notEmptyLine)
                {
                    if ($this->_fileWithHeader)
                    {
                        $tmpData = $this->_nbLanguages($columnsList, $langs);

                        $columnsList = $tmpData['columnsName'];

                        $length = $tmpData['nbLang'];
                        $offset = current($tmpData['positions']);

                        $splitArray = array_combine($columnsList, $splitArray2);
                    }
                    else
                    {
                        $length = count($langs);
                        $tmpData = explode($this->_delimiter, $data[0]);
                        $offset = count($tmpData) - $length;

                        $splitArray = array_combine($this->_dataColumns, $splitArray2);
                    }

                    //Set data for 2 tables - Index table
                    $tmpArrayIndex = array_splice($splitArray, $offset, $length);
                    if (isset($splitArray['MP_LanguageID'])
                            && !empty($splitArray['MP_LanguageID']))
                        $langId = $splitArray['MP_LanguageID'];

                    //Order data from the line to be compliant with the db table columns
                    foreach ($tmpData['positions'] as $langSuffix => $value)
                    {
                        $val = utf8_decode($splitArray2[$value]);
                        $key = array_search($val, $tmpArrayIndex);
                        unset($tmpArrayIndex[$key]);
                        $tmpArrayIndex[$langSuffix] = trim($val);
                    }
                    // and data table
//                    $tmpArray = array_combine($this->_dataColumns,
//                            array_splice($splitArray, 0, $offset));
                    $params['tmpIndex'] = $tmpArrayIndex;
                    $params['tmpData'] = $splitArray;
                }
                elseif ($notEmptyLine)
                {
                    // table with only data and no language translation
                    if ($this->_fileWithHeader)
                    {
                        $tmpArray = array_combine($columnsList, $splitArray2);
                    }
                    else
                    {
                        $tmpArray = array_combine($this->_dataColumns, $splitArray);
                    }
                    $params['tmpData'] = $tmpArray;
                }

                $params['langId'] = $langId;
                $params['nbLines'] = $nbLines;

                $nbLines = $this->_dataProcess($params);
            }
        }
        else
        {
            /**
             * @todo: Pr�voir le cas d'un chargement de donn�es particulier
             *        avec une table index incluse.
             */
            if (!empty($this->_indexClass))
                $params['tmpIndex'] = $tmpArrayIndex;

            $params['tmpData'] = $tmpArray;
            $params['langId'] = $langId;
            $params['nbLines'] = $nbLines;
            if ($tmpArray[$this->_dataId])
                $nbLines = $this->_dataProcess($params);
        }
        return $nbLines;
    }

    /**
     * Tests if the current data already exist for the id and langId.
     *
     * @param array   $tmpArray Data for the current element.
     * @param int     $langId   Id of the language to process search. Required
     *                          only if search done in index table too.
     * @param boolean $data     Defines if the language id has to be set in the
     *                          where clause. If false, then we are only
     *                          testing the data table and not the index table.
     *                          Default = true.
     *
     * @return int $exist Number of records
     */
    public function recordExists($tmpArray, $langId = null, $data = true)
    {
        $exist = false;
        $select = $this->_db->select()
                        ->from($this->_oDataTableName, 'count(' . $this->_dataId . ')')
                        ->group($this->_dataId);
        if ($data)
        {
            if (isset($tmpArray[$this->_dataId]))
                $select->where($this->_dataId . ' = ?', trim($tmpArray[$this->_dataId]));

            if ($this->_constraint)
                $select->where($this->_constraint . " = ?", trim($tmpArray[$this->_constraint]));
        }
        else
        {
            if(is_null($langId))
                throw new Exception('To process search record in index table, language id is required');

            $select->joinLeft(
                    $this->_oIndexTableName,
                    $this->_dataId . ' = ' . $this->_indexId);

            if (isset($tmpArray[$this->_indexId]) && trim($tmpArray[$this->_indexId]) > 0)
                $select->where($this->_indexId . ' = ?', trim($tmpArray[$this->_indexId]));

            if (isset($tmpArray[$this->_dataId]) && trim($tmpArray[$this->_dataId]) > 0)
                $select->where($this->_indexId . ' = ?', trim($tmpArray[$this->_dataId]));

            $select->where($this->_indexLanguageId . " = ?", $langId);
        }

        $exist = $this->_db->fetchOne($select);

        return $exist;
    }

    /**
     * Update or insert data from file to import
     *
     * @param array $params Data for loading process
     */
    protected function _dataProcess($params)
    {
        $onlyData = true;
        if (!empty($this->_indexClass))
        {
            $tmpArrayIndex = $params['tmpIndex'];
            $onlyData = false;
        }

        $tmpArray = $params['tmpData'];
        $langId = $params['langId'];
        $nbLines = $params['nbLines'];

        // test if a value already exist
        $exist = $this->recordExists(
                        $tmpArray,
                        $langId,
                        $onlyData
        );

        // insert or update the row
        if ($exist)
        {
            //update data table
            // and index table for translation if necessary
            if (!empty($this->_indexClass))
            {
                $languages = Cible_FunctionsGeneral::getAllLanguage();

                foreach ($languages as $key => $lang)
                {
                    //set data to update
                    reset($this->_indexColumns);
                    if (isset($tmpArrayIndex[$lang['L_Suffix']]))
                        $tmpArray[current($this->_indexColumns)] = $tmpArrayIndex[$lang['L_Suffix']];
                    else
                        $lang['L_ID'] = $langId;

                    $this->save($tmpArray[$this->_dataId], $tmpArray, $lang['L_ID']);
                }
            }
            else
            {
                $this->save($tmpArray[$this->_dataId], $tmpArray, $langId);
            }

            $nbLines['updated'] = ++$nbLines['updated'];
        }
        else
        {
            // and index table for translation if necessary
            if (!empty($this->_indexClass))
            {
                $languages = Cible_FunctionsGeneral::getAllLanguage();
                foreach ($languages as $key => $lang)
                {
                    //set data to update
                    reset($this->_indexColumns);

                    if (isset($tmpArrayIndex[$lang['L_Suffix']]))
                        $tmpArray[current($this->_indexColumns)] = $tmpArrayIndex[$lang['L_Suffix']];
                    else
                        $lang['L_ID'] = $langId;

                    $this->insert($tmpArray, $lang['L_ID']);

                    if (!isset($tmpArrayIndex[$lang['L_Suffix']]))
                        break;
                }
            }
            else
            {
                $this->insert($tmpArray, $langId);
            }

            $nbLines['inserted'] = ++$nbLines['inserted'];
        }

        return $nbLines;
    }

    /**
     * This method allows to set the colums for the select clause.
     * If we need to filter specific colums when retrieving the data
     *
     * @return void
     */
    public function setIndexSelectColumns()
    {
        if (!empty($this->_indexSelectColumns))
            $this->_indexColumns = $this->_indexSelectColumns;
    }

    public function keywordExist(array $keywords, Zend_Db_Select $prodSelect, $langId = null)
    {
        $tmpFilter = $prodSelect;
        $select = new Zend_Db_Select($this->_db);

        if (count($keywords) == 0)
            throw new Exception('No key words.');

        $select = $this->_db->select()
                        ->from(array('_data' => $this->_oDataTableName), $this->_dataId);

        $this->_addJoinWhere($keywords, $prodSelect, $langId);

        return $this->_where;
    }

    private function _addJoinWhere(array $keywords, Zend_Db_Select $select, $langId = null)
    {
        $whereData = '';
        $whereIndex = '';
        $pos  = count($this->_searchColumns['data']);
        $posI = count($this->_searchColumns['index']);
        $aliasData = '';

        if (!in_array($this->_oDataTableName, $this->_excludeTables))
        {
            $aliasData = '_data' . ++$this->j;
            $select->from(
                    array($aliasData => $this->_oDataTableName),
                    array('*')
                )
                ->where($this->_indexLanguageId . ' = ?', $langId);

            $aliasData .= '.';
        }

        foreach ($this->_searchColumns['data'] as $column)
        {
            $pos--;
            $posKey = count($keywords);

            foreach ($keywords as $value)
            {
                $posKey--;
                if ($pos == 0 && $posKey == 0 && !empty($value) && array_key_exists($column, $this->_dataColumns))
                {
                    $whereData .= $this->_db->quoteInto($aliasData . $column . ' like ?', '%' . $value . '%');
                }
                elseif (!empty($value) && array_key_exists($column, $this->_dataColumns))
                {
                    $whereData .= $this->_db->quoteInto($aliasData . $column . ' like ?', '%' . $value . '%');
                    $whereData .= ' OR ';
                }
            }
        }

        if (strlen($whereData == 0))
        {
            $this->_where = $whereData;
        }
        else
        {
            $this->_where .= ' OR ' . $whereData;
        }

        if (!empty($this->_indexClass))
        {
            $aliasIndex = '';
            if (!in_array($this->_oIndexTableName, $this->_excludeTables))
            {
                $aliasIndex = $this->_oIndexTableName . ++$this->i;
                $select->joinLeft(
                        array($aliasIndex => $this->_oIndexTableName),
                        $aliasData . $this->_dataId . '= ' . $aliasIndex . '.' . $this->_indexId,
                        array()
                );
                $aliasIndex .= '.';
            }

            foreach ($this->_searchColumns['index'] as $column)
            {
                --$posI;
                $posKeyI = count($keywords);

                foreach ($keywords as $value)
                {
                    $posKeyI--;
                    if ($posI == 0 && $posKeyI == 0 && !empty($value) && array_key_exists($column, $this->_indexColumns))
                    {
                        $whereIndex .= $this->_db->quoteInto($aliasIndex . $column . ' like ?', '%' . $value . '%');
        }
                    elseif (!empty($value) && array_key_exists($column, $this->_indexColumns))
        {
                        $whereIndex .= $this->_db->quoteInto($aliasIndex . $column . ' like ?', '%' . $value . '%');
                        $whereIndex .= ' OR ';
        }
                }
            }
        }
        if (strlen($this->_where) == 0)
        {
            $this->_where = $whereIndex;
        }
        else
        {
            $this->_where .= ' OR ' . $whereIndex;
        }

        return $this->_where;
    }

    /**
     * Tests the number of language to import.
     * Used when the !st line of the file is the colums list of the table.
     *
     * @param array $columsHeader List of the columns title
     * @param array $langs        All the languages embedded into Edith.
     *
     * @return int $nbLang Number of language (to set the offset to split
     *                     index table data)
     */
    protected function _nbLanguages($columsHeader = array(), $langs = array())
    {
        $nbLang = 0;
        $position = array();

        foreach ($columsHeader as $key => $colName)
        {
            $tmpStr = explode('_', strtolower(trim($colName)));

            foreach ($langs as $lang)
            {
                $langSuffix = $lang['L_Suffix'];
                if (end($tmpStr) == $langSuffix)
                {
                    $position[$langSuffix] = $key;
                    $nbLang++;
                }
            }

            $columns[] = trim($colName);
        }

        $data = array(
            'nbLang' => $nbLang,
            'positions' => $position,
            'columnsName' => $columns
        );

        return $data;
    }

    /**
     * Fetch the last position
     *
     * @return int
     */
    public function getLastPosition()
    {
        if (!empty ($this->_position))
        {
            $select = $this->getAll(null, false);

            $select->order($this->_position . ' DESC');
            $result = $this->_db->fetchRow($select);

            return $result[$this->_position];
        }
    }

    /**
     * Allows to simply join left a query previously set from an other object
     * and retrieve filtered data.
     *
     * @param bool $array
     *
     * @return array | Zend_Db_Select
     */
    public function joinFetchData($array = false)
    {
        $results = null;
        $select =  null;

        if (!empty($this->_query))
        {
            $select = $this->_query;
            $select->joinLeft($this->_oDataTableName, $this->_foreignKey, $this->_columns);

            if ($array)
                $results = $this->_db->fetchAll($select);
            else
                $results = $select;

        }

        return $results;
    }

    /**
     * Fetch data according to the filters values.<br />
     * Filters are simple orWhere, we'll have to work on that
     *
     * @param array $filters List of values to build filters.
     */
    public function findData($filters = array())
    {

        $select = $this->_db->select();
        $select->from($this->_oDataTableName, $this->_dataColumns);

        foreach ($filters as $key => $value)
        {
            if (isset($this->_dataColumns[$key]))
            {
                if (is_string($value ))
                    $select->where("{$this->_dataColumns[$key]} like '%{$value}%'");
                elseif (is_integer($value))
                    $select->where($this->_dataColumns[$key] . ' = ?',$value);
            }
        }

        return $this->_db->fetchAll($select);
    }
}
