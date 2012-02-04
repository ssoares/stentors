<?php
    // ensure that the errors appear
    error_reporting(E_ALL|E_STRICT);
    ini_set('display_errors', true);
    ini_set('magic_quotes_gpc','off');
    ini_set('mbstring.internal_encoding', 'UTF-8');
    setlocale(LC_CTYPE, 'fr_CA.UTF-8');

    $rootDir = dirname(dirname(__FILE__));
    $application_path = "{$rootDir}/application";
    $orders_path = "{$rootDir}/orders";
    $lib_path = "{$rootDir}/lib";
    $cache_path = "{$rootDir}/tmp";
    $web_root = dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF']) ;
    $absolute_web_root = "http://{$_SERVER['SERVER_NAME']}{$web_root}";

    //die($absolute_web_root);

    // setting up directories and loading of classes
    set_include_path('.'
        . PATH_SEPARATOR . "{$lib_path}"
        . PATH_SEPARATOR . "{$lib_path}/Cible/Models"
        . PATH_SEPARATOR . "$application_path/modules/banners/models/"
        . PATH_SEPARATOR . "$application_path/modules/cart/models/"
        . PATH_SEPARATOR . "$application_path/modules/catalog/models/"
        . PATH_SEPARATOR . "$application_path/modules/default/models/"
        . PATH_SEPARATOR . "$application_path/modules/events/models/"
        . PATH_SEPARATOR . "$application_path/modules/form/models/"
        . PATH_SEPARATOR . "$application_path/modules/forms/models/"
        . PATH_SEPARATOR . "$application_path/modules/gallery/models/"
        . PATH_SEPARATOR . "$application_path/modules/news/models/"
        . PATH_SEPARATOR . "$application_path/modules/newsletter/models/"
        . PATH_SEPARATOR . "$application_path/modules/order/models/"
        . PATH_SEPARATOR . "$application_path/modules/page/models/"
        . PATH_SEPARATOR . "$application_path/modules/profile/models/"
        . PATH_SEPARATOR . "$application_path/modules/retailers/models/"
        . PATH_SEPARATOR . "$application_path/modules/rss/models/"
        . PATH_SEPARATOR . "$application_path/modules/search/models/"
        . PATH_SEPARATOR . "$application_path/modules/sitemap/models/"
        . PATH_SEPARATOR . "$application_path/modules/text/models/"
        . PATH_SEPARATOR . "$application_path/modules/video/models/"        
        . PATH_SEPARATOR . "$application_path/modules/rssreader/models/"
        . PATH_SEPARATOR . get_include_path());
    require_once 'Zend/Loader.php';
    Zend_Loader::registerAutoload();

    // define the timezone
    date_default_timezone_set('Canada/Eastern');
    $locale = new Zend_Locale('fr_CA');
    Zend_Registry::set('Zend_Locale', $locale);

    // loading configuration
    $host   = explode('.',$_SERVER['HTTP_HOST']);
    $envVar = 'production';

    if(in_array('sandboxes', $host) || in_array('localhost', $host))
        $envVar = 'development';
    elseif(in_array('staging', $host))
        $envVar = 'staging';

    $config = new Zend_Config_Ini("{$application_path}/config.ini", 'general', true);
    $imgCfg = new Zend_Config_Ini("{$application_path}/config.ini", 'Images', true);
    $envCfg = new Zend_Config_Ini("{$application_path}/config.ini", $envVar);

    $config->merge($imgCfg);
    $config->merge($envCfg);
    $config->setReadOnly();
    $registry = Zend_Registry::getInstance();
    $registry->set('config', $config);

    // establishment of the database
    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);
    $db->query('SET NAMES utf8');
    $registry->set('db', $db);

    $registry->set('siteName', $config->site->title);
    $registry->set('application_path', $application_path);
    $registry->set('orders_path', $orders_path);
    $registry->set('document_root', "$rootDir/{$config->document_root}");
    $registry->set('web_root', $web_root);
    $registry->set('www_root', $web_root);
    $registry->set('absolute_web_root', $absolute_web_root);

    $registry->set('lucene_index', realpath(dirname(__FILE__))."/indexation/all_index");

    // Enables the loading of helpers from /lib/Cible/View/Helper
    $view = new Zend_View();
    $view->addHelperPath("Cible/View/Helper", "Cible_View_Helper");
    $view->addHelperPath("AB/View/Helper", "AB_View_Helper");
    $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    $view->addBasePath("{$lib_path}/Cible/View");
    $view->addBasePath("{$lib_path}/ZendX/JQuery/View");

    /* This is how we set the jquery version application wide */
    $jquery = $view->jQuery();
    $jquery->setCdnVersion('1.4.2');
    $jquery->setUiCdnVersion('1.8.2');

    $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
    $viewRenderer->setView($view);
    Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
//    $viewRenderer->view->doctype('XHTML1_STRICT');
    $viewRenderer->view->doctype('XHTML1_TRANSITIONAL');

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
    Zend_Layout::startMvc(array('layoutPath'=>"{$application_path}/layouts"));

    // setup controller
    $frontController = Zend_Controller_Front::getInstance();
    $frontController->addModuleDirectory("{$application_path}/modules");

    // does not show errors
    $frontController->throwExceptions(false);


    $router = $frontController->getRouter();
    $route = new Zend_Controller_Router_Route(
	'rss/:lang/:catID/:feed',
        array(
	    'lang'       => 'en',
        'catID'     => '1',
	    'feed'       => 'rss.xml',
	    'module'     => 'rss',
	    'controller' => 'index',
	    'action'     => 'read'
        )
    );
    $router->addRoute('rss', $route);

    // run
    $frontController->dispatch();

    // Clear all variables so that they don't get in the global scope
    unset($rootDir, $application_path, $cache_path, $config, $db, $registry, $frontController);
?>