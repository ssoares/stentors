<?php
/**
 * LICENSE
 *
 * @category
 * @package
 * @copyright Copyright (c)2011 Cibles solutions d'affaires - http://www.ciblesolutions.com
 * @license   Empty
 */

/**
 * Build and dispatch the messages for specific events.
 *
 * @category Cible
 * @package
 * @copyright Copyright (c)2011 Cibles solutions d'affaires - http://www.ciblesolutions.com
 * @license   Empty
 * @version   $Id: Notifications.php 823 2012-01-31 22:32:37Z ssoares $
 */
class Cible_Notifications extends Cible_Notify
{
    const NEWACCOUNT = 'newAccount';
    const EDITRESEND = 'editResend';
    const EDITACCOUNT = 'editAccount';
    const WELCOME = 'welcome';
    const NEWORDER = 'newOrder';
    const CONFIRMORDER = 'confirmOrder';
    const REJECTORDER = 'rejectOrder';
    const NEWPWD = 'newPassword';
    const CONTACT = 'contact';

    protected $_view;
    protected $_request;
    protected $_moduleId = "";
    protected $_type = "";
    protected $_isActive = false;
    protected $_event = "";
    protected $_recipient = "";
    protected $_siteName = "";
    protected $_message;
    protected $_emailRenderData = array();
    protected $_data = array();

    public function setIsActive()
    {
        $oData = new NotificationManagerObject();

        $notification = $oData->fetchData($this->_moduleId, $this->_event, $this->_recipient);

        $this->_isActive = (bool)$notification['NM_Active'];
    }

    public function setView($_view)
    {
        $this->_view = $_view;
    }

    public function setRequest($_request)
    {
        $this->_request = $_request;
    }

    public function setModuleId($_moduleId)
    {
        $this->_moduleId = $_moduleId;
    }

    public function setType($_type)
    {
        $this->_type = $_type;
    }

    public function setEvent($_event)
    {
        $this->_event = $_event;
    }

    public function setRecipient($_recipient)
    {
        $this->_recipient = $_recipient;
    }

    public function setData($_data)
    {
        $this->_data = $_data;
    }

    public function setMessage($_message)
    {
        $this->_message = $_message;
    }


    public function __construct($options = null)
    {
        $this->setProperties($options);
        $this->setIsActive();
        if ($this->_isActive)
        {
            $_frontController = Zend_Controller_Front::getInstance();
            $this->_request = $_frontController->getRequest();

            if (null === $this->_view)
            {
                require_once 'Zend/Controller/Action/HelperBroker.php';
                $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
                $this->_view = $viewRenderer->view;
            }

            $this->_siteName = Zend_Registry::get('siteName');
            $this->fetchMessage();

            if (isset ($options['isHtml']))
                $this->_isHtml = $options['isHtml'];

            if (isset ($options['to']))
                $this->addTo($options['to']);

            if (is_null($this->_to))
                $this->addTo($this->_data['email']);

//            $targetObjName = 'Cible_Notifications_' . ucfirst($this->_type);
//            new $targetObjName($options);

        }
    }

    /**
     * Set the properties of he class.
     *
     * @param array $otpions Properties to build the notification.
     *
     * @return void
     */
    public function setProperties($options = array())
    {
        foreach ($options as $property => $value)
        {
            $methodName = 'set' . ucfirst($property);

            if (property_exists($this, '_' . $property)
                && method_exists($this, $methodName))
            {
                $this->$methodName($value);
            }
        }
    }

    protected function fetchMessage()
    {
        $oData = new NotificationManagerObject();

        $notification = $oData->fetchData($this->_moduleId, $this->_event, $this->_recipient);

        $this->_message = $this->_view->getClientText($notification['NM_Message'], $this->_data['language']);

        $this->_title = $this->_view->getClientText($notification['NM_Title'], $this->_data['language']);
        $this->_from = $notification['NM_Email'];

        if ($this->_recipient == 'admin' && is_null($this->_to))
            $this->addTo($notification['NM_Email']);
    }

}
