<?php
    // ensure that the errors appear
    error_reporting(E_ALL|E_STRICT);
    ini_set('display_errors', true);
    ini_set('magic_quotes_gpc','off');
    ini_set('mbstring.internal_encoding', 'UTF-8');

    // define the timezone
    date_default_timezone_set('Canada/Eastern');
    setlocale(LC_CTYPE, 'fr_CA.UTF-8');

    $rootDir = dirname(dirname(dirname(__FILE__)));
    $application_path = "{$rootDir}/application";
    $extranet_path = "{$rootDir}/extranet";
    $lib_path = "{$rootDir}/lib";
    $cache_path = "{$rootDir}/tmp";
    $web_root = dirname($_SERVER['PHP_SELF']);
    $www_root = dirname(dirname($_SERVER['PHP_SELF']));
    if($www_root == '/')
        $www_root = "";
    $absolute_web_root = "http://{$_SERVER['SERVER_NAME']}{$www_root}";

    // setting up directories and loading of classes
    set_include_path('.'
        . PATH_SEPARATOR . "$extranet_path/includes"
        . PATH_SEPARATOR . "$rootDir/lib"
        . PATH_SEPARATOR . "$extranet_path/modules/banners/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/cart/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/catalog/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/default/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/events/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/form/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/forms/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/gallery/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/member/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/news/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/newsletter/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/order/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/page/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/parent/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/profile/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/video/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/retailers/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/rss/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/search/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/text/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/users/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/utilities/models/"
        . PATH_SEPARATOR . "$extranet_path/modules/rssreader/models/"
        . PATH_SEPARATOR . "$rootDir/lib/Cible/Models/"
        . PATH_SEPARATOR . get_include_path());
    require_once 'Zend/Loader.php';
    Zend_Loader::registerAutoload();

    // loading configuration
    $host   = explode('.', $_SERVER['HTTP_HOST']);
    $envVar = 'production';

    $envVar = 'production';

    if(in_array('sandboxes', $host) || in_array('localhost', $host))
        $envVar = 'development';
    elseif(in_array('staging', $host))
        $envVar = 'staging';

    $app_config = new Zend_Config_Ini("{$application_path}/config.ini", $envVar);

    $imgCfg = new Zend_Config_Ini("{$application_path}/config.ini", 'Images', true);
    $config = new Zend_Config_Ini("{$extranet_path}/config.ini", 'general', true);
    $config->merge($imgCfg);
    $config->readOnly();

    $registry = Zend_Registry::getInstance();
    $registry->set('config', $config);
    $registry->set('relativeRootPath', $web_root);
    $registry->set('absolute_web_root', $absolute_web_root);

    // establishment of the database
    $db = Zend_Db::factory($app_config->db);
    Zend_Db_Table::setDefaultAdapter($db);
    $db->query('SET NAMES utf8');
    $registry->set('db', $db);

    $registry->set('extranet_root', $extranet_path);
    $registry->set('www_root', $www_root);

    $registry->set('lucene_index', realpath(dirname(__FILE__).'/../')."/indexation/all_index");

    // Enables the loading of helpers from /lib/Cible/View/Helper
    $view = new Zend_View();
    $view->addHelperPath("Cible/View/Helper", "Cible_View_Helper");
    $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    $view->addBasePath("{$lib_path}/Cible/View");
    $view->addBasePath("{$lib_path}/ZendX/JQuery/View");
    $view->addBasePath("{$lib_path}/Cible/Validate");

    $jquery = $view->jQuery();
    $jquery->setCdnVersion('1.4.1');
    $jquery->setUiCdnVersion('1.8.2');
    $jquery->addStylesheet( "{$web_root}/themes/default/css/jquery/smoothness/jquery-ui-1.8.2.custom.css");

    $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
    $viewRenderer->setView($view);
    Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

    $frontendOptions = array(
       'lifetime' => 0, // cache lifetime of 2 hours
       'automatic_serialization' => true
    );

    $backendOptions = array(
        'cache_dir' => $cache_path // Directory where to put the cache files
    );

    // getting a Zend_Cache_Core object
    $cache = Zend_Cache::factory('Core',
                                 'File',
                         $frontendOptions,
                         $backendOptions);

    $registry->set('cache', $cache);

    // setup the layout
    require_once 'Zend/Layout.php';
    Zend_Layout::startMvc(array('layoutPath'=>"{$extranet_path}/layouts"));

    // setup controller
    $frontController = Zend_Controller_Front::getInstance();

    $frontController->addModuleDirectory("{$extranet_path}/modules")
                    ->registerPlugin(new Cible_Plugins_Auth())
                    ->throwExceptions(false);

    // run
    $frontController->dispatch();



    // Clear all variables so that they don't get in the global scope
    unset($rootDir, $application_path, $extranet_path, $lib_path, $cache_path, $app_config, $config, $db, $registry, $cache, $frontController);
?>
