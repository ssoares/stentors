<?php
 /**
 * Control errors from controllers or actions unknown
 *
 * The system checks whether the controller or action is present in the database and call the controller page if it does.
 * If not present, a custom 404 page is displayed.
 *
 * PHP versions 5
 *
 * LICENSE:
 *
 * @category   Controller
 * @package    Default
 * @author     Alexandre Beaudet <alexandre.beaudet@ciblesolutions.com>
 * @copyright  2009 CIBLE Solutions d'Affaires
 * @license    http://www.ciblesolutions.com
 * @version   : <?php $ ?> Id:$
 */
    class ErrorController extends Cible_Controller_Action
    {

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

        }


        /**
        * This is the function that loop through all modules to construct the robots.txt
        *
        * This function return the robots.txt file all format and ready to be read by a spider robots
        *
        * @access private
        *
        * @return an txt file
        */
        private function robotsAction(){
            $db = Zend_Registry::get('db');
            $robotsString = "";
            $robotsString = header('Content-Type: text/txt');
            $moduleName = ucfirst("DefaultRobots");
            if (class_exists($moduleName)){
               //var_dump($moduleName);
                $newsO = new $moduleName();
                $robotsString .= $newsO->getXMLFilesString($this->_registry->absolute_web_root, 'default');
            }
            $select = $db->select()
                ->distinct()
                ->from('Modules');
            $modules = $db->fetchAll($select);
            foreach ($modules as $module){
                $module['M_MVCModuleTitle'];
                $moduleName = ucfirst($module['M_MVCModuleTitle'] . "Robots");
                if (class_exists($moduleName)) {
                    //var_dump($moduleName);
                    $newsO = new $moduleName();
                    $robotsString .= $newsO->getXMLFilesString($this->_registry->absolute_web_root, $module['M_MVCModuleTitle']);
                }
            }
           echo $robotsString;
        }

        public function errorAction()
        {
            $errors = $this->_getParam('error_handler');
            //$exception = $errors->exception;
            Zend_Registry::set('baseUrl', $this->getFrontController()->getBaseUrl());

            switch ($errors->type) {
                // if it is a controller error
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:

                    // grab the controller, action and settings requested by the user
                    $ParamsArray = $errors->request->getParams();
                    if($this->_request->controller=="robots.txt")
                    {
                        $this->robotsAction();
                        $this->_helper->layout()->disableLayout();
                        $this->_helper->viewRenderer->setNoRender(true);

                        //Cible_FunctionsRobots::robotsAction();
                        exit;
                    }
                    $ControllerName = $ParamsArray['controller'];
                    $ActionName = $ParamsArray['action'];

                    // check if the name of the controller is in the database
                    $Pages = new PagesIndex();
                    $Select = $Pages->select()->setIntegrityCheck(false)
                        ->from('PagesIndex')
                        ->join('Languages','Languages.L_ID = PagesIndex.PI_LanguageID')
                        ->join('Pages', 'Pages.P_ID = PagesIndex.PI_PageID')
                        ->join('Page_Themes', 'Page_Themes.PT_ID = Pages.P_ThemeID')
                        ->join('Views', 'Views.V_ID = Pages.P_ViewID')
                        ->where('PagesIndex.PI_PageIndex = ?', $ControllerName)
                        ->where('PI_Status = 1')
                        ->limit(1);

                    $Row = $Pages->fetchRow($Select);
                    // if the controller is found in the database
                    if(count($Row) == 1){
                        // call page controller to display blocks
                        $this->_helper->actionStack('index','page','default', array('Row' => $Row, 'Param' => $ParamsArray));

                        // does not render the page Error404
                        $this->disableView();
                    }
                    else{

                        $session = new Cible_Sessions();

                        $currentLanguageID = !empty($session->languageID) ? $session->languageID : $this->_config->defaultInterfaceLanguage;

                        $page_not_found_id = $this->_config->page_not_found->pageID;

                        $pageIndexName = Cible_FunctionsPages::getPageNameByID($page_not_found_id, $currentLanguageID);

                        // check if the name of the controller is in the database
                        $Pages = new PagesIndex();
                        $Select = $Pages->select()->setIntegrityCheck(false)
                            ->from('PagesIndex')
                            ->join('Languages','Languages.L_ID = PagesIndex.PI_LanguageID')
                            ->join('Pages', 'Pages.P_ID = PagesIndex.PI_PageID')
                            ->join('Page_Themes', 'Page_Themes.PT_ID = Pages.P_ThemeID')
                            ->join('Views', 'Views.V_ID = Pages.P_ViewID')
                            ->where('PagesIndex.PI_PageIndex = ?', $pageIndexName)
                            ->where('PI_Status = 1')
                            ->limit(1);

                        $Row = $Pages->fetchRow($Select);

                        if(count($Row) == 1){
                            // call page controller to display blocks
                            $this->_helper->actionStack('index','page','default', array('Row' => $Row, 'Param' => $ParamsArray));

                            // does not render the page Error404
                            $this->disableView();
                        } else {
                            $this->disableLayout();

                            echo "404 Page not found.";
                        }

                        $this->getResponse()
                             ->setRawHeader('HTTP/1.1 404 Not Found');

                    }
                break;

                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    // does not render the page Error404
                    $this->disableLayout();
                    $this->disableView();
                    echo("Erreur d'action   ");

                break;

                default:
                    // application error; display error page, but don't change
                    // status code

                    // ...

                    // Log the exception:
                    $exception = $errors->exception;
                    /*$log = new Zend_Log(
                        new Zend_Log_Writer_Stream(
                            '/tmp/applicationException.log'
                        )
                    );
                    $log->debug($exception->getMessage() . "\n" .
                                $exception->getTraceAsString());
                    */
                    echo <<< End_of_error
                    <p>
                    <strong>Error</strong>
                    {$exception->getMessage()}
                    </p>
                    <p>
                    <strong>Stack Trace</strong>
                    {$exception->getTraceAsString()}
                    </p>
End_of_error;

                    break;
            }


       }
    }
?>