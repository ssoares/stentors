<?php

    class Events_IndexController extends Cible_Controller_Action
    {
        protected $_showBlockTitle = false;

        /**
        * Overwrite the function define in the SiteMapInterface implement in Cible_Controller_Action
        *
        * This function return the sitemap specific for this module
        *
        * @access public
        *
        * @return a string containing xml sitemap
        */
        public function siteMapAction(){
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            $newsRob = new EventsRobots();
            $dataXml = $newsRob->getXMLFile($this->_registry->absolute_web_root,$this->_request->getParam('lang'));

            parent::siteMapAction($dataXml);
        }

        public function detailssidelistAction(){

            $_blockID = $this->_request->getParam('BlockID');
            $id = $this->_request->getParam('ID');

            $events = new EventsCollection($_blockID);

            $listall_page = Cible_FunctionsCategories::getPagePerCategoryView( $events->getBlockParam('1'), 'listall' );
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $events->getBlockParam('1'), 'details' );

            $this->view->assign('listall_page', $listall_page);

            $this->view->assign('details_page', $details_page);

            $this->view->assign('events', $events->getOtherEvents($events->getBlockParam('2'), $id) );
        }

        public function detailsAction(){

            if(!empty($_SERVER['HTTP_REFERER'])){
                $this->view->assign('pagePrecedente', $_SERVER['HTTP_REFERER']);
            }
            else{
                 $this->view->assign('pagePrecedente','');
            }

            $titleUrl = Cible_FunctionsGeneral::getTitleFromPath($this->_request->getPathInfo());
            $id = 0;
            $events = new EventsCollection();
            if($titleUrl!=""){
                $id = $events->getIdByName($titleUrl);
            }
            $this->view->assign('events', $events->getDetails($id));
        }

        public function homepagelistAction(){
            $_blockID = $this->_request->getParam('BlockID');

            $events = new EventsCollection($_blockID);

            $listall_page = Cible_FunctionsCategories::getPagePerCategoryView( $events->getBlockParam('1'), 'listall' );
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $events->getBlockParam('1'), 'details' );

            $this->view->assign('listall_page', $listall_page);

            $this->view->assign('details_page', $details_page);

            $this->view->assign('events', $events->getList($events->getBlockParam('2')) );
        }

        public function listallAction(){

            $_blockID = $this->_request->getParam('BlockID');

            $eventsObject = new EventsCollection($_blockID);

            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $eventsObject->getBlockParam('1'), 'details' );

            $this->view->assign('details_page', $details_page);

            $events = $eventsObject->getList();

            $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_Array( $events ) );
            $paginator->setItemCountPerPage( $eventsObject->getBlockParam('2') );
            $paginator->setCurrentPageNumber($this->_request->getParam('page'));

            $this->view->assign('paginator', $paginator);
        }

        public function calendrierpetitAction()
        {

            $_blockID = $this->_request->getParam('BlockID');
            $this->view->BlockID = $_blockID;




            if($this->_isXmlHttpRequest)
            {
                $_year = $this->_request->getParam('Year');
                $_month = $this->_request->getParam('Month');


                $eventsObject = new EventsCollection($_blockID);

                $events = $eventsObject->getListYearMonth($_year, $_month, null);
                $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $eventsObject->getBlockParam('1'), 'details' );
                $detail_page = $this->view->baseUrl() . '/' . $details_page . "/";

                $responseObject = array();
                $resultObject = array();

                foreach ($events as $key => $result)
                {
                    //$date = new Zend_Date($result['EDR_StartDate'],null, (Zend_Registry::get('languageSuffix') == 'fr' ? 'fr_CA' : 'en_CA'));
                    //$date_string_url = Cible_FunctionsGeneral::dateToString($date,Cible_FunctionsGeneral::DATE_SQL,'-');
                    $resultObject['EventID'] = $result['ED_ID'];
                    $resultObject['Title'] = strip_tags(utf8_encode($result['EI_Title']));
                    $resultObject['Description'] = strip_tags($result['EI_Brief']);
                    //$resultObject['URL'] =  $this->baseUrl() . '/' . $this->details_page . "/"  . $date_string_url . "/" . $event['EI_ValUrl'];

                    $date_string = '';
                    $strd = '';

                    foreach($result['dates'] as $keydate => $row)
                    {
                        //$resultObject['StartDate'] = $row['EDR_StartDate'];
                        //$resultObject['EndDate'] = $row['EDR_EndDate'];

                        $startDate = new Zend_Date($row['EDR_StartDate'], null, 'fr_CA');
                        $endDate = new Zend_Date($row['EDR_EndDate'], null, 'fr_CA');
                        $date_stringURL = sprintf("%d-%d-%d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH), $startDate->get(Zend_Date::YEAR));


                        if( !empty($date_string) )
                            $date_string .= ' et ';

                        if( $startDate->get(Zend_Date::MONTH) == $endDate->get(Zend_Date::MONTH) && $startDate->get(Zend_Date::YEAR) == $endDate->get(Zend_Date::YEAR) ){
                            if( $startDate->get(Zend_Date::DAY) != $endDate->get(Zend_Date::DAY) )
                                $date_string .= sprintf("%d-%d %s %d", $startDate->get(Zend_Date::DAY), $endDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR));
                            else
                                $date_string .= sprintf("%d %s %d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR));
                        }
                        else
                            $date_string .= sprintf("%d %s %d au %d %s %d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR), $endDate->get(Zend_Date::DAY), $endDate->get(Zend_Date::MONTH_NAME), $endDate->get(Zend_Date::YEAR));

                        //list($a, $m, $j) = explode("-", $row['EDR_StartDate']);
                        //$resultObject['CellId'] = $m . $j . $a;

                        $arrayDates = getDays($row['EDR_StartDate'], $row['EDR_EndDate']);

                        if($strd != "")
                            $strd .= "|";

                        $strd .= implode("|", $arrayDates);


                        //var_dump($resultObject);
                    }
                    $resultObject['URL'] = $detail_page . $date_stringURL . "/" . $result['EI_ValUrl'];
                    $resultObject['DateComplete'] = utf8_encode($date_string);
                    $resultObject['CellsIds'] = $strd;

                    array_push($responseObject, $resultObject);

                }

                //var_dump($responseObject);
                $this->getHelper('viewRenderer')->setNoRender();
                echo json_encode($responseObject);
            }


        }


        public function calendrierAction(){
            $_blockID = $this->_request->getParam('BlockID');
            $this->view->BlockID = $_blockID;
            $events1 = new EventsCollection($_blockID);
            $details_page = Cible_FunctionsCategories::getPagePerCategoryView( $events1->getBlockParam('1'), 'details' );
            $detail_page = $this->view->baseUrl() . '/' . $details_page . "/";

            if($this->_isXmlHttpRequest)
            {
                $_year = $this->_request->getParam('Year');
                $_month = $this->_request->getParam('Month');
                $eventsObject = new EventsCollection($_blockID);
                $events = $eventsObject->getListYearMonth($_year, $_month, null);
                $responseObject = array();
                $resultObject = array();
                foreach ($events as $key => $result)
                {
                    foreach($result['dates'] as $keydate1 => $row1)
                    {
                        $date_string = '';
                        $date_stringURL = '';

                        $resultObject['EventID'] = $result['ED_ID'];
                        $resultObject['Title'] = strip_tags(utf8_encode($result['EI_Title']));
                        $resultObject['Description'] = strip_tags($result['EI_Brief']);
                        $resultObject['StartDate'] = $row1['EDR_StartDate'];
                        $resultObject['EndDate'] = $row1['EDR_EndDate'];
                        //$resultObject['URL'] =  $this->baseUrl() . '/' . $this->details_page . "/"  . $date_string_url . "/" . $event['EI_ValUrl'];
                        foreach($result['dates'] as $keydate => $row)
                        {
                            $startDate = new Zend_Date($row['EDR_StartDate'], null, 'fr_CA');
                            $endDate = new Zend_Date($row['EDR_EndDate'], null, 'fr_CA');
                            $date_stringURL = sprintf("%d-%d-%d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH), $startDate->get(Zend_Date::YEAR));

                            if( !empty($date_string) )
                                $date_string .= ' et ';

                            if( $startDate->get(Zend_Date::MONTH) == $endDate->get(Zend_Date::MONTH) && $startDate->get(Zend_Date::YEAR) == $endDate->get(Zend_Date::YEAR) ){
                                if( $startDate->get(Zend_Date::DAY) != $endDate->get(Zend_Date::DAY) )
                                    $date_string .= sprintf("%d-%d %s %d", $startDate->get(Zend_Date::DAY), $endDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR));
                                else
                                    $date_string .= sprintf("%d %s %d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR));
                            }
                            else
                                $date_string .= sprintf("%d %s %d au %d %s %d", $startDate->get(Zend_Date::DAY), $startDate->get(Zend_Date::MONTH_NAME), $startDate->get(Zend_Date::YEAR), $endDate->get(Zend_Date::DAY), $endDate->get(Zend_Date::MONTH_NAME), $endDate->get(Zend_Date::YEAR));
                        }
                        $resultObject['URL'] = $detail_page . $date_stringURL . "/" . $result['EI_ValUrl'];
                        $resultObject['DateComplete'] = utf8_encode($date_string);
                        $resultObject['CellsIds'] = "";

                        array_push($responseObject, $resultObject);
                    }
                }
                $this->getHelper('viewRenderer')->setNoRender();
                echo json_encode($responseObject);
            }
        }
    }

    function getDays($sStartDate, $sEndDate)
    {
        // Firstly, format the provided dates.
        // This function works best with YYYY-MM-DD
        // but other date formats will work thanks
        // to strtotime().
        $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
        $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));

        // Start the variable off with the start date
        $aDays[] = gmdate("mdY", strtotime($sStartDate));

        // Set a 'temp' variable, sCurrentDate, with
        // the start date - before beginning the loop
        $sCurrentDate = $sStartDate;

        // While the current date is less than the end date
        while($sCurrentDate < $sEndDate)
        {
            $tmp = $sCurrentDate;
            // Add a day to the current date
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($tmp)));
            $sCurrentDateStr = gmdate("mdY", strtotime("+1 day", strtotime($tmp)));

            // Add this new day to the aDays array
            $aDays[] = $sCurrentDateStr;
        }

        // Once the loop has finished, return the
        // array of days.
        return $aDays;
    }
?>