<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bootstrap
 *
 * @author soaser
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function run()
    {
        // Cela permet d'avoir le fichier de configuration disponible depuis n'importe ou dans l'application.
        Zend_Registry::set('config', new Zend_Config($this->getOptions()));
        exit;
        parent::run();
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
                'basePath' => APPLICATION_PATH ));
        return $loader;
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
        var_dump($db);
        exit;
        return $db;
    }

    /**
     * Initialize session
     *
     * @return Zend_Session_Namespace
     */
    protected function _initSession()
    {
        // On initialise la session
        $session = new Zend_Session_Namespace('edith', true);
        return $session;
    }

}
