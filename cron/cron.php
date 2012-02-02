<?php

date_default_timezone_set('Canada/Eastern');
setlocale(LC_CTYPE, 'fr_CA.UTF-8');

$rootDir = dirname(dirname(__FILE__));
$application_path = "{$rootDir}/application";
$extranet_path = "{$rootDir}/extranet";
$lib_path = "{$rootDir}/lib";
$cache_path = "{$rootDir}/tmp";
$web_root = dirname($_SERVER['PHP_SELF']);
$www_root = dirname(dirname($_SERVER['PHP_SELF']));

if ($www_root == '/')
    $www_root = "";

$absolute_web_root = "http://{$_SERVER['SERVER_NAME']}{$www_root}";
// Setting the environment
$host = explode('.', $_SERVER['HTTP_HOST']);
$envVar = 'production';

if (in_array('sandboxes', $host) || in_array('localhost', $host))
    $envVar = 'development';
elseif (in_array('staging', $host))
    $envVar = 'staging';

define('APPLICATION_ENV', $envVar);
define('APPLICATION_PATH', $rootDir);
define("ISCRONJOB", true);

// setting up directories and loading of classes
set_include_path('.'
    . PATH_SEPARATOR . "$extranet_path/includes"
    . PATH_SEPARATOR . "$extranet_path/modules"
    . PATH_SEPARATOR . "$rootDir/cron"
    . PATH_SEPARATOR . "$rootDir/lib"
    . PATH_SEPARATOR . "$extranet_path/modules/banners/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/cart/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/catalog/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/default/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/events/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/form/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/forms/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/gallery/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/news/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/newsletter/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/order/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/page/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/profile/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/retailers/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/rss/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/search/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/text/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/users/models/"
    . PATH_SEPARATOR . "$extranet_path/modules/utilities/models/"
    . PATH_SEPARATOR . "$rootDir/lib/Cible/Models/"
    . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Loader.php';
    Zend_Loader::registerAutoload();

require_once 'Zend/Application.php';

$application = new Zend_Application(
        APPLICATION_ENV,
        array(
            'bootstrap' => array(
                'class' => 'Bootstrap_Cron',
                'path' => $lib_path . '/Cible/Bootstrap/Cron.php',
            ),
            'config' => $application_path . '/config.ini',
        ),
        array(
            'pluginPaths' => array(
                'Cible_Plugin_Cron' => $lib_path . '/Cible/Plugins/Resource'
            )
        )
);
$application->bootstrap()->run();
