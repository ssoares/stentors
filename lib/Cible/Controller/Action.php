<?php

abstract class Cible_Controller_Action extends Zend_Controller_Action implements Cible_Controller_SiteMapInterface
{
    protected $_db;
    protected $_moduleID = 0;
    protected $_isXmlHttpRequest = false;
    protected static $defaultEditLanguage = 1;
    protected $_defaultEditLanguage = 1;
    protected $_currentEditLanguage = 1;
    protected $_defaultInterfaceLanguage = 1;
    protected $_currentInterfaceLanguage = 1;
    protected $_config;
    protected $_registry;
    protected $type;


    public function siteMapAction(array $dataXml = array()){
        if (count($dataXml)>0){
            $xmlString = "";
            $xmlString = header('Content-Type: text/xml');
            $xmlString .= '<?xml version="1.0" encoding="UTF-8"?>';
            $xmlString .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

            foreach ($dataXml as $i) {
                $xmlString .= "<url><loc>";
                $xmlString .= $i[0];
                $xmlString .= '</loc><lastmod>';
                $xmlString .= $i[1];
                $xmlString .= '</lastmod><changefreq>';
                $xmlString .= $i[2];
                $xmlString .= '</changefreq><priority>';
                $xmlString .= $i[3];
                $xmlString .= '</priority></url>';
            }
            $xmlString .= '</urlset>';
            echo $xmlString;
        }
        //echo "";
    }

    public function getRobot(){}

    /**
     * Set the module id.
     *
     * @return int
     */
   public function setModuleId()
    {
        $this->_moduleID = Cible_FunctionsModules::getModuleIDByName($this->_request->getModuleName());
    }
    /**
     * Get the module id from controller.
     *
     * @return int
     */
    public function getModuleID()
    {
        return $this->_moduleID;
    }


    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->_registry = Zend_Registry::getInstance();

        $this->view->assign('current_module', $this->_request->getModuleName());

        if($this->_getParam('enableDictionnary')){
            $this->_registry->set('enableDictionnary', 'true');
            $this->view->assign('enableDictionnary', 'true');
        } else {
            $this->_registry->set('enableDictionnary', 'false');
            $this->view->assign('enableDictionnary', 'false');
        }

        $this->_db = $this->_registry->get('db');

        $this->_config = $this->_registry->get('config');

        if( $this->_config->defaultEditLanguage )
        {
            $this->_defaultEditLanguage = $this->_config->defaultEditLanguage;
            self::$defaultEditLanguage = $this->_config->defaultEditLanguage;
        }
        if( $this->_config->dictionnary_is_allowed == true )
            $this->_registry->set('dictionnary_is_allowed', 'true');
        else
            $this->_registry->set('dictionnary_is_allowed', 'false');

        $_request = $this->_request;

        if ($this->_request->isXmlHttpRequest()) {

            $session = new Cible_Sessions();

            if( !empty($session->languageID) )
                $this->_registry->set('languageID', $session->languageID);

            $this->_isXmlHttpRequest = true;
            $this->disableLayout();

            if($_request->isPost()){

                foreach($_request->getPost() as $key => $value)
                {
                    if (is_array($value))
                        $_request->setPost($key, $value);
                    else
                    $_request->setPost($key, utf8_decode($value) );
                }

                $this->setRequest($_request);

            }
        } else {

            $this->view->assign('params', $_request->getParams());

        }

        $this->view->assign('request', $_request);
    }


    public static function getDefaultEditLanguage()
    {
        return self::$defaultEditLanguage;
    }

    public static function getRobotString()
    {
        return self::getRobot();
    }

    public function getCurrentInterfaceLanguage()
    {
        return $this->_currentInterfaceLanguage;
    }

    public function getCurrentEditLanguage()
    {
        return $this->_currentEditLanguage;
    }

    /***
    * disableLayout enables you to disable layout wihtout having to remember how to do it with the layout helper
    *
    */
    protected function disableLayout(){
        $this->_helper->layout->disableLayout();
    }

    /***
    * disableView enables you to disable the viewrenderer wihtout having to remember how to do it with the viewrenderer helper
    *
    */
    protected function disableView(){
        $this->_helper->viewRenderer->setNoRender();
    }

    protected function retrieveActions(){

        $class = new ReflectionClass($this);
        $methods = array();

        foreach($class->getMethods() as $_method){
            $_name = $_method->getName();

            if($_method->isProtected() || $_method->isPublic() ){
                if( strlen($_name) > 6 && substr($_name, -6) == 'Action'){
                    array_push($methods, $_name);
                }
            }
        }

        return $methods;
    }

    protected function retrieveProperties(){

        $class = new ReflectionClass($this);
        $properties = array();

        foreach($class->getProperties() as $_property){
            $_name = $_property->getName();

            if( strlen($_name) > 6 && substr($_name, -6) == 'Action'){
                array_push($properties, $_name);
            }

        }

        return $properties;
    }

    public function toExcelAction(){
        if( empty($this->filename) )
            throw new Exception('You must define $this->filename for the output filename');

        if( empty($this->select) )
            throw new Exception('You must define $this->select a select statement');

        if( empty($this->fields) )
            throw new Exception('You must define $this->fields as an array with all the fields');


        $this->disableLayout();
        $this->disableView();

        if( $this->select ){

            if( $this->filters ){
                $filters = $this->filters;

                foreach($filters as $key => $filter){
                    $filter_val = $this->_getParam($key);
                    if( !empty($filter_val) )
                        $this->select->where("{$filter['associatedTo']} = ?", $filter_val);
                }
            }

            if( $this->_getParam('order')){

                if( in_array( $this->_getParam('order'), array_keys($this->fields) ) ){

                    $direction = 'ASC';
                    if( in_array($this->_getParam('order-direction'), array('ASC','DESC') ) )
                        $direction = $this->_getParam('order-direction');

                    $this->select->order( "{$this->_getParam('order')} {$direction}" );
                }
            }

            $searchfor = utf8_decode($this->_getParam('searchfor'));

            if( $searchfor ){

                $searching_on = array();
                $search_keywords = explode(' ', $searchfor);

                foreach($this->tables as $table => $columns){
                    foreach($columns as $column){
                        array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$searchfor}%"));
                        foreach( $search_keywords as $keyword )
                            array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$keyword}%"));
                    }
                }

                if( !empty($searching_on) )
                    $this->select->where(implode(' OR ', $searching_on));
            }

            $results = $this->_db->fetchAll($this->select);

            $objPHPExcel = new PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0);

            $column = 0;

            foreach($this->fields as $field_name => $field_value)
            {
                if (is_array($field_value))
                {
                    $label = !empty( $field_value['label'] ) ? $field_value['label'] : $this->view->getCibleText( "list_column_{$field_name}" ) ;
                }
                else
                {
                    $label = $field_value;
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, utf8_encode($label));
                $column++;

            }

            $key = 2;
            foreach($results as $value){

                foreach(array_keys($this->fields) as $i => $field_value){
                    if( isset($value[$field_value]) )
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $key, utf8_encode($value[$field_value] ));

                }
                $key++;
            }

            // load the appropriate IO Factory writer
            switch ($this->type)
            {
                case 'Excel5':
                    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                    // output the appropriate headers
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header("Content-Disposition: attachment;filename={$this->filename}");
                    break;

                case 'CSV':
                    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
                    $objWriter->setDelimiter(';');
                    $objWriter->setLineEnding("\r\n");
                    // output the appropriate headers
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment;filename={$this->filename}");
                    break;

                default:
                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                    // output the appropriate headers
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header("Content-Disposition: attachment;filename={$this->filename}");
                    break;
            }

            // output the file
            $objWriter->save('php://output');

            exit;

        }
    }

    public function toPdfAction(){
        if( empty($this->filename) )
            throw new Exception('You must define $this->filename for the output filename');

        if( empty($this->select) )
            throw new Exception('You must define $this->select a select statement');

        if( empty($this->fields) )
            throw new Exception('You must define $this->fields as an array with all the fields');


        $this->disableLayout();
        $this->disableView();

        if( $this->select ){

            if( $this->filters ){
                $filters = $this->filters;

                foreach($filters as $key => $filter){
                    $filter_val = $this->_getParam($key);
                    if( !empty($filter_val) )
                        $this->select->where("{$filter['associatedTo']} = ?", $filter_val);
                }
            }

            if( $this->_getParam('order')){

                if( in_array( $this->_getParam('order'), $this->fields) ){

                    $direction = 'ASC';
                    if( in_array($this->_getParam('order-direction'), array('ASC','DESC') ) )
                        $direction = $this->_getParam('order-direction');

                    $this->select->order( "{$this->_getParam('order')} {$direction}" );
                }
            }

            $searchfor = utf8_decode($this->_getParam('searchfor'));

            if( $searchfor ){

                $searching_on = array();
                $search_keywords = explode(' ', $searchfor);

                foreach($this->tables as $table => $columns){
                    foreach($columns as $column){
                        array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$searchfor}%"));
                        foreach( $search_keywords as $keyword )
                            array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$keyword}%"));
                    }
                }

                if( !empty($searching_on) )
                    $this->select->where(implode(' OR ', $searching_on));
            }

            $results = $this->_db->fetchAll($this->select);

            $objPHPExcel = new PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0);


            foreach(array_keys($this->fields) as $i => $field_value){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, utf8_encode($field_value));
            }

            $key = 2;
            foreach($results as $value){

                foreach(array_keys($this->fields) as $i => $field_value){
                    if( !empty($value[$field_value]) )
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $key, utf8_encode($value[$field_value] ));
                }
                $key++;
            }

            // load the appropriate IO Factory writer
            $objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
            // output the appropriate headers
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment;filename={$this->filename}");

            // output the file
            $objWriter->save('php://output');

            exit;

        }
    }

    /**
     * Create an csv file for data export using PHPExcel library.
     *
     * @return void
     */
    public function toCsvAction(){
        if( empty($this->filename) )
            throw new Exception('You must define $this->filename for the output filename');

        if( empty($this->select) )
            throw new Exception('You must define $this->select a select statement');

        if( empty($this->fields) )
            throw new Exception('You must define $this->fields as an array with all the fields');


        $this->disableLayout();
        $this->disableView();

        if( $this->select ){

            if( $this->filters ){
                $filters = $this->filters;

                foreach($filters as $key => $filter){
                    $filter_val = $this->_getParam($key);
                    if( !empty($filter_val) )
                        $this->select->where("{$filter['associatedTo']} = ?", $filter_val);
}
            }

            if( $this->_getParam('order')){

                if( in_array( $this->_getParam('order'), array_keys($this->fields) ) ){

                    $direction = 'ASC';
                    if( in_array($this->_getParam('order-direction'), array('ASC','DESC') ) )
                        $direction = $this->_getParam('order-direction');

                    $this->select->order( "{$this->_getParam('order')} {$direction}" );
                }
            }

            $searchfor = utf8_decode($this->_getParam('searchfor'));

            if( $searchfor ){

                $searching_on = array();
                $search_keywords = explode(' ', $searchfor);

                foreach($this->tables as $table => $columns){
                    foreach($columns as $column){
                        array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$searchfor}%"));
                        foreach( $search_keywords as $keyword )
                            array_push($searching_on, $this->_db->quoteInto("{$table}.{$column} LIKE ?", "%{$keyword}%"));
                    }
                }

                if( !empty($searching_on) )
                    $this->select->where(implode(' OR ', $searching_on));
            }

            $results = $this->_db->fetchAll($this->select);

            $objPHPExcel = new PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0);

            $column = 0;
            $key = 1;
            // Insert columns label, if needed set $key = 2
            if ($this->addColumnsLabel)
            {
                foreach($this->fields as $field_name => $field_value){

                    if (is_array($field_value))
                    {
                        $label = !empty( $field_value['label'] ) ? $field_value['label'] : $this->view->getCibleText( "list_column_{$field_name}" ) ;
                    }
                    else
                    {
                        $label = trim($field_value);
                    }

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, utf8_encode($label));
                    $column++;

                }
                $key = 2;
            }
            foreach($results as $value){

                foreach(array_keys($this->fields) as $i => $field_value){
                    if( isset($value[$field_value]) )
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $key, utf8_encode(trim($value[$field_value]) ));

                }
                $key++;
            }

            // load the appropriate IO Factory writer
            switch ($this->type)
            {
                case 'Excel5':
                    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                    // output the appropriate headers
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header("Content-Disposition: attachment;filename={$this->filename}");
                    // output the file
                    $objWriter->save('php://output');
                    break;

                case 'CSV':
                    $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
                    $objWriter->setDelimiter(';');
                    $objWriter->setLineEnding("\r\n");
                    // Save file on the server
                    if ($this->_exportFilesFolder)
                        $objWriter->save($this->_exportFilesFolder . $this->filename);
                    else
                        $objWriter->save('php://output');

                    break;

                default:
                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                    // output the appropriate headers
                    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                    header("Content-Disposition: attachment;filename={$this->filename}");
                    // output the file
                    $objWriter->save('php://output');
                    break;
            }


        }
    }

    public function ajaxCitiesAction()
    {
        if ($this->_isXmlHttpRequest)
        {
            $this->getHelper('viewRenderer')->setNoRender();

            $stateId    = $this->_getParam('stateId');
            $cities     = new CitiesObject();
            $citiesData = $cities->getCitiesDataByStates($stateId);

            foreach ($citiesData as $id => $data)
            {
                $citiesData[$id]['C_Name'] = utf8_encode($data['C_Name']);
            }

            echo json_encode($citiesData);
        }
    }

    public function ajaxStatesAction()
    {
        if ($this->_isXmlHttpRequest)
        {
            $this->getHelper('viewRenderer')->setNoRender();

            $countryId  = $this->_getParam('countryId');
            $languageId = $this->_getParam('langId');
            $statesData = array();
            $states = Cible_FunctionsGeneral::getStateByCode(
                    $countryId,
                    null,
                    $languageId);

            if (is_array($states))
            {
                foreach ($states as $id => $data)
                {
                    $statesData[$id ]['id']   = $data['id'];
                    $statesData[$id ]['name'] = utf8_encode($data['name']);
                }
            }

            echo json_encode($statesData);
        }
    }

    public function ajaxAction()
    {
        $action = $this->_getParam('actionAjax');

        switch ($action)
        {
            case 'citiesList':
                $this->disableView();
                $value = $this->_getParam('q');
                $limit = $this->_getParam('limit');
                $oCity = new CitiesObject();

                if (!empty($value))
                    $data = $oCity->autocompleteSearch(
                        $value,
                        $this->getCurrentInterfaceLanguage(),
                        $limit
                        );

                foreach ($data as $value)
                    echo $value['C_Name'] . "\n";
                break;

            default:
                break;
        }
    }
}
