<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Bootstrap.php';

/**
 * Description of Cron
 *
 * @author soaser
 */
class Bootstrap_Cron extends Bootstrap
{

    public function run()
    {
        // Cela permet d'avoir le fichier de configuration disponible depuis n'importe ou dans l'application.
        Zend_Registry::set('config', new Zend_Config($this->getOptions(), true));

        try
        {
            if ($this->hasPluginResource('cron'))
            {
                $this->bootstrap('cron');
                $server = $this->getResource('cron');
                $server->run();
            }
            else
            {
                echo 'The cron plugin resource needs to be configured in application.ini.' . PHP_EOL;
            }
        }
        catch (Exception $e)
        {
            echo 'An error has occured.' . PHP_EOL;
            Zend_Debug::dump($e);
        }
    }

    /**
     * Initialize data bases
     *
     * @return Zend_Db::factory
     */
    protected function _initDb()
    {
        //on charge notre fichier de configuration
        $config = new Zend_Config($this->getOptions());
        //On essaye de faire une connection a la base de donnee.
        try
        {
            $db = Zend_Db::factory($config->db);
            //on test si la connection se fait
            $db->getConnection();
        }
        catch (Exception $e)
        {
            exit($e->getMessage());
        }
        // on stock notre dbAdapter dans le registre
        Zend_Registry::set('db', $db);

        Zend_Db_Table::setDefaultAdapter($db);

        return $db;
    }

    /**
     * Initialize Module
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath' => APPLICATION_PATH . '/extranet'));
        return $loader;
    }

    protected function _initCache()
    {
        $frontendOptions = array(
//           'lifetime' => 0, // cache lifetime of 2 hours
//           'automatic_serialization' => true
        );

        // Directory where to put the cache files
        $backendOptions = array(
            'cache_dir' => APPLICATION_PATH .'/tmp'
        );
        $cache = Zend_Cache::factory('Core',
                                 'File',
                         $frontendOptions,
                         $backendOptions);

        Zend_Registry::set('cache', $cache);
    }

    protected function _initHelper()
    {
        $lib_path = APPLICATION_PATH . "/lib";

        $view = new Zend_View();
        $view->addHelperPath("Cible/View/Helper", "Cible_View_Helper");
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->addBasePath("{$lib_path}/Cible/View");
        $view->addBasePath("{$lib_path}/ZendX/JQuery/View");
        $view->addBasePath("{$lib_path}/Cible/Validate");

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function _initPaths()
    {
        $config = new Zend_Config($this->getOptions());
        $www_root = dirname(dirname($_SERVER['PHP_SELF']));
        $tmpPath = "";
            $www_root = "";

        $isSandbox = preg_match('/sandboxes/', $config->domainName);

        if ($isSandbox)
        {
//            $tmpPath = "/{$config->project->programer}/{$config->project->name}/www";
            $tmpPath = "/ss/ciblesolutions/www";
            $www_root .= $tmpPath;
//            $www_root .= "/{$config->project->programer}/{$config->project->name}/www";
        }

        $absolute_web_root = "http://{$config->domainName}{$tmpPath}";

        Zend_Registry::set('www_root', $www_root);
        Zend_Registry::set('absolute_web_root', $absolute_web_root);
    }
}